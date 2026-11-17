<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Models\Company;
use App\Models\DailyEvaluation;
use App\Models\DailyLogbook;
use App\Models\EvaluationCriterion;
use App\Models\Mahasiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * Export rekap peserta (absensi, logbook, transkrip nilai) untuk aplikasi mobile.
 * Memakai view export yang sama dengan web (resources/views/peserta/exports/*).
 *
 * Alur: aplikasi meminta tautan (Bearer token) → server memberi URL bertanda tangan berlaku 10 menit →
 * aplikasi membuka URL di browser/penampil PDF HP. Token login tidak pernah ditaruh di URL.
 */
class PesertaExportApiController extends ApiController
{
    public const JENIS = ['absensi-pdf', 'absensi-excel', 'logbook-pdf', 'logbook-excel', 'nilai-pdf'];

    /** GET /api/peserta/export/{jenis} → {url, expires_at} */
    public function tautan(Request $request, string $jenis)
    {
        if (!in_array($jenis, self::JENIS, true)) {
            return $this->fail('Jenis export tidak dikenal.', 404);
        }
        $mhs = $this->mahasiswaLogin($request);
        if (!$mhs) {
            return $this->fail('Data mahasiswa tidak ditemukan.', 404);
        }
        if ($jenis === 'nilai-pdf' && !$this->evaluasiTerbit($mhs)) {
            return $this->fail('Transkrip belum tersedia. Penilaian akhir magang Anda belum diterbitkan.', 409);
        }

        $berlaku = now()->addMinutes(10);

        return $this->ok([
            'url' => URL::temporarySignedRoute('api.peserta.export.unduh', $berlaku, ['jenis' => $jenis, 'mahasiswa' => $mhs->id_mhs]),
            'expires_at' => static::dateTime($berlaku),
        ]);
    }

    /** GET /api/peserta/export/{jenis}/unduh/{mahasiswa}?signature=… (tanpa token; dilindungi tanda tangan URL) */
    public function unduh(Request $request, string $jenis, int $mahasiswa)
    {
        abort_unless(in_array($jenis, self::JENIS, true), 404);
        $mhs = Mahasiswa::findOrFail($mahasiswa);
        $company = Company::first();
        $penempatan = $mhs->latestPengajuan()->with(['divisi', 'pembimbing'])->first();
        $nim = preg_replace('/[^a-zA-Z0-9_-]/', '_', $mhs->nim ?: 'Peserta');
        $tgl = date('Ymd');

        return match ($jenis) {
            'absensi-pdf' => Pdf::loadView('peserta.exports.absensi_pdf', $this->dataAbsensi($mhs) + compact('mhs', 'company', 'penempatan'))
                ->setPaper('a4', 'portrait')->download("Rekap_Absensi_{$nim}_{$tgl}.pdf"),
            'absensi-excel' => $this->excel(view('peserta.exports.absensi_excel', $this->dataAbsensi($mhs) + compact('mhs', 'company', 'penempatan'))->render(), "Rekap_Absensi_{$nim}_{$tgl}.xls"),
            'logbook-pdf' => Pdf::loadView('peserta.exports.logbook_pdf', $this->dataLogbook($mhs) + compact('mhs', 'company', 'penempatan'))
                ->setPaper('a4', 'portrait')->download("Logbook_Magang_{$nim}_{$tgl}.pdf"),
            'logbook-excel' => $this->excel(view('peserta.exports.logbook_excel', $this->dataLogbook($mhs) + compact('mhs', 'company', 'penempatan'))->render(), "Logbook_Magang_{$nim}_{$tgl}.xls"),
            'nilai-pdf' => Pdf::loadView('peserta.exports.nilai_pdf', [
                'mhs' => $mhs,
                'company' => $company,
                'penempatan' => $penempatan,
                'evaluation' => $this->evaluasiTerbit($mhs),
                'criteria' => EvaluationCriterion::where('is_active', true)->get(),
            ])->setPaper('a4', 'portrait')->download("Transkrip_Nilai_Magang_{$nim}_{$tgl}.pdf"),
        };
    }

    // ---------------------------------------------------------------------
    // Data sama dengan PesertaController::export* di web
    // ---------------------------------------------------------------------

    protected function dataAbsensi(Mahasiswa $mhs): array
    {
        $attendances = Attendance::where('mahasiswa_id', $mhs->id_mhs)->orderBy('attendance_date', 'asc')->get();

        return [
            'attendances' => $attendances,
            'stats' => [
                'total' => $attendances->count(),
                'hadir_tepat' => $attendances->where('check_in_status', 'present')->count(),
                'terlambat' => $attendances->where('check_in_status', 'late')->count(),
                'izin' => $attendances->where('check_in_status', 'leave')->count(),
                'sakit' => $attendances->where('check_in_status', 'sick')->count(),
                'alpa' => $attendances->whereNotIn('check_in_status', ['present', 'late', 'leave', 'sick'])->count(),
                'total_jam_kerja' => $attendances->sum('total_work_minutes') / 60,
            ],
        ];
    }

    protected function dataLogbook(Mahasiswa $mhs): array
    {
        $logbooks = DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)->orderBy('logbook_date', 'asc')->get();

        return [
            'logbooks' => $logbooks,
            'stats' => [
                'total' => $logbooks->count(),
                'approved' => $logbooks->where('status', 'approved')->count(),
                'submitted' => $logbooks->where('status', 'submitted')->count(),
                'revision' => $logbooks->where('status', 'revision_required')->count(),
                'draft' => $logbooks->where('status', 'draft')->count(),
            ],
        ];
    }

    protected function evaluasiTerbit(Mahasiswa $mhs): ?DailyEvaluation
    {
        return DailyEvaluation::where('mahasiswa_id', $mhs->id_mhs)
            ->where('status', 'published')
            ->with(['details.criterion', 'evaluator'])
            ->latest('updated_at')
            ->first();
    }

    protected function excel(string $isi, string $nama)
    {
        return response($isi, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $nama . '"',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    protected function mahasiswaLogin(Request $request): ?Mahasiswa
    {
        $user = $request->user();

        return $user->mahasiswa ?: Mahasiswa::where('user_id', $user->id_users)->first();
    }
}
