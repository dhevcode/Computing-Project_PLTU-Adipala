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
    public function index(Request $request)
    {
        $criteria = EvaluationCriterion::where('is_active', true)->get();
        $mahasiswas = Mahasiswa::with(['universitas', 'latestPengajuan.divisi'])->get();

        $query = DailyEvaluation::with(['mahasiswa.universitas', 'details.criterion', 'evaluator']);

        if ($request->filled('mahasiswa_id') && $request->mahasiswa_id !== 'all') {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $evaluations = $query->orderBy('evaluation_date', 'desc')->paginate(15);

        return view('admin.nilai.index', compact('evaluations', 'mahasiswas', 'criteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id_mhs',
            'evaluation_date' => 'required|date',
            'scores' => 'required|array',
            'participant_note' => 'nullable|string',
            'internal_note' => 'nullable|string',
            'action_type' => 'required|in:draft,publish',
        ]);

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

        $evaluation = DailyEvaluation::updateOrCreate(
            [
                'mahasiswa_id' => $request->mahasiswa_id,
                'evaluation_date' => $request->evaluation_date,
            ],
            [
                'total_score' => $finalScore,
                'participant_note' => $request->participant_note,
                'internal_note' => $request->internal_note,
                'evaluator_id' => Auth::id(),
                'status' => $status,
                'published_at' => ($status === 'published') ? Carbon::now() : null,
            ]
        );

        // Simpan detail nilai
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

        // Kirim notifikasi jika dipublikasikan
        $mhs = Mahasiswa::find($request->mahasiswa_id);
        if ($status === 'published' && $mhs && $mhs->user_id) {
            AppNotification::send(
                $mhs->user_id,
                'Nilai Harian Telah Dipublikasikan',
                "Penilaian untuk tanggal {$request->evaluation_date} telah dipublikasikan dengan Skor: {$finalScore}. Silakan periksa di menu Nilai.",
                'success',
                route('peserta.nilai')
            );
        }

        $msg = ($status === 'published')
            ? "Penilaian berhasil dipublikasikan dengan nilai akhir {$finalScore}."
            : 'Draft penilaian berhasil disimpan.';

        return back()->with('success', $msg);
    }
}
