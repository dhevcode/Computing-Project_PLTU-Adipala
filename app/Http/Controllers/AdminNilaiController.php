<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DailyEvaluation;
use App\Models\EvaluationCriterion;
use App\Models\EvaluationDetail;
use App\Models\Mahasiswa;
use App\Models\AppNotification;

class AdminNilaiController extends Controller
{
    /**
     * Tampilkan daftar Penilaian Akhir Magang untuk Admin.
     * Penilaian bersifat final untuk mahasiswa yang telah menyelesaikan masa magang.
     */
    public function index(Request $request)
    {
        $criteria = EvaluationCriterion::where('is_active', true)->get();

        // Ambil data seluruh mahasiswa yang memiliki riwayat pengajuan magang
        $mahasiswas = Mahasiswa::with(['universitas', 'latestPengajuan.divisi'])
            ->whereHas('latestPengajuan')
            ->get()
            ->sortByDesc(function ($m) {
                // Prioritaskan mahasiswa yang masa magangnya sudah selesai / alumni
                return $m->isAlumni() ? 2 : 1;
            });

        $query = DailyEvaluation::with(['mahasiswa.universitas', 'mahasiswa.latestPengajuan.divisi', 'details.criterion', 'evaluator']);

        if ($request->filled('mahasiswa_id') && $request->mahasiswa_id !== 'all') {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nim', 'LIKE', "%{$search}%");
            });
        }

        $evaluations = $query->orderBy('updated_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.nilai.index', compact('evaluations', 'mahasiswas', 'criteria'));
    }

    /**
     * Simpan / Perbarui Penilaian Akhir Magang (Final Evaluation).
     */
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id_mhs',
            'evaluation_date' => 'nullable|date',
            'scores' => 'required|array',
            'participant_note' => 'nullable|string',
            'internal_note' => 'nullable|string',
            'action_type' => 'required|in:draft,publish',
        ]);

        $evalDate = $request->evaluation_date ? Carbon::parse($request->evaluation_date) : Carbon::today();

        $criteria = EvaluationCriterion::where('is_active', true)->get()->keyBy('id_criterion');
        $totalWeight = $criteria->sum('weight') ?: 100;

        $weightedTotal = 0;
        foreach ($request->scores as $criterionId => $score) {
            $criterion = $criteria->get($criterionId);
            $weight = $criterion ? $criterion->weight : 10;
            $weightedTotal += ($score * $weight);
        }

        $finalScore = round($weightedTotal / $totalWeight, 2);
        $status = ($request->action_type === 'publish') ? 'published' : 'draft';

        // Penilaian bersifat final (1 penilaian akhir per mahasiswa)
        $evaluation = DailyEvaluation::updateOrCreate(
            [
                'mahasiswa_id' => $request->mahasiswa_id,
            ],
            [
                'evaluation_date' => $evalDate,
                'total_score' => $finalScore,
                'participant_note' => $request->participant_note,
                'internal_note' => $request->internal_note,
                'evaluator_id' => Auth::id(),
                'status' => $status,
                'published_at' => ($status === 'published') ? Carbon::now() : null,
            ]
        );

        // Simpan detail rincian per kriteria penilaian
        foreach ($request->scores as $criterionId => $score) {
            EvaluationDetail::updateOrCreate(
                [
                    'daily_evaluation_id' => $evaluation->id_evaluation,
                    'criterion_id' => $criterionId,
                ],
                [
                    'score' => $score,
                    'notes' => $request->detail_notes[$criterionId] ?? null,
                ]
            );
        }

        $mhs = Mahasiswa::with('latestPengajuan')->find($request->mahasiswa_id);

        // Jika nilai final dipublikasikan, update status pengajuan mahasiswa menjadi Selesai Magang jika belum
        if ($status === 'published' && $mhs && $mhs->latestPengajuan) {
            if ($mhs->latestPengajuan->status !== 'Selesai Magang') {
                $mhs->latestPengajuan->update(['status' => 'Selesai Magang']);
            }
        }

        // Kirim notifikasi ke peserta
        if ($status === 'published' && $mhs && $mhs->user_id) {
            $grade = match(true) {
                $finalScore >= 85 => 'A (Sangat Baik)',
                $finalScore >= 75 => 'B (Baik)',
                $finalScore >= 60 => 'C (Cukup)',
                default => 'D (Kurang)',
            };

            AppNotification::send(
                $mhs->user_id,
                'Penilaian Akhir Magang Telah Diterbitkan',
                "Selamat! Penilaian akhir magang Anda telah diterbitkan dengan Skor Akhir: {$finalScore} [Predikat: {$grade}]. Silakan periksa transkrip nilai Anda di portal.",
                'success',
                route('peserta.nilai')
            );
        }

        $msg = ($status === 'published')
            ? "Penilaian akhir magang berhasil dipublikasikan dengan skor {$finalScore}."
            : 'Draft penilaian akhir berhasil disimpan.';

        return back()->with('success', $msg);
    }
}
