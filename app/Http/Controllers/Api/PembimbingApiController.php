<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsMonitoringData;
use App\Models\AppNotification;
use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\DailyEvaluation;
use App\Models\DailyLogbook;
use App\Models\EvaluationCriterion;
use App\Models\EvaluationDetail;
use App\Models\LeaveRequest;
use App\Models\LogbookComment;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * API Portal Pembimbing Lapangan untuk aplikasi mobile.
 * Logika mengikuti App\Http\Controllers\PembimbingController (web): pembimbing hanya melihat
 * dan memproses data mahasiswa bimbingannya sendiri.
 */
class PembimbingApiController extends ApiController
{
    use FormatsMonitoringData;

    /** ID mahasiswa bimbingan (pembimbing_id atau nama_pembimbing, sama dengan web). */
    protected function bimbinganIds(Request $request): array
    {
        $user = $request->user();
        $userName = trim(strtolower($user->nama_lengkap ?? ''));

        return PengajuanMagang::where(function ($q) use ($user, $userName) {
            $q->where('pembimbing_id', $user->id_users);
            if (!empty($userName)) {
                $q->orWhereRaw('LOWER(TRIM(nama_pembimbing)) = ?', [$userName]);
            }
        })
            ->pluck('mahasiswa_id')
            ->unique()
            ->values()
            ->all();
    }

    protected function authorizeMahasiswa(Request $request, int $mahasiswaId): void
    {
        abort_unless(
            in_array($mahasiswaId, $this->bimbinganIds($request)),
            403,
            'Akses ditolak. Anda hanya berwenang memonitor dan mengevaluasi peserta magang yang Anda bimbing.'
        );
    }

    /** Ringkasan mahasiswa yang ditempelkan pada data presensi/logbook/cuti/aduan. */
    protected static function mhsRingkas(?Mahasiswa $m): ?array
    {
        return $m ? [
            'id' => $m->id_mhs,
            'nim' => $m->nim,
            'nama_lengkap' => $m->nama_lengkap,
            'universitas' => $m->universitas->nama_universitas ?? null,
        ] : null;
    }

    protected function withMhs(array $data, $model): array
    {
        return $data + ['mahasiswa' => static::mhsRingkas($model->mahasiswa)];
    }

    protected function filterMahasiswa(Request $request, $query): void
    {
        if ($request->filled('mahasiswa_id') && $request->mahasiswa_id !== 'all') {
            $this->authorizeMahasiswa($request, (int) $request->mahasiswa_id);
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }
    }

    protected function filterStatus(Request $request, $query, string $field = 'status', string $param = 'status'): void
    {
        if ($request->filled($param) && $request->input($param) !== 'all') {
            $query->where($field, $request->input($param));
        }
    }

    /**
     * Teks notifikasi/catatan presensi sesuai pelaku (web pembimbing). AdminApiController menimpa dengan teks web admin.
     * Mengembalikan [judul, pesan] untuk notifikasi, atau [teks] untuk catatan presensi.
     */
    protected function teks(string $jenis, array $v): array
    {
        $catatan = !empty($v['catatan']) ? "Catatan: {$v['catatan']}" : '';

        return match ($jenis) {
            'koreksi' => ['Koreksi Presensi ' . $v['label'], "Pengajuan koreksi presensi tanggal {$v['tanggal']} telah {$v['label']} oleh Pembimbing Lapangan. " . $catatan],
            'catatan_koreksi' => ['Disetujui Pembimbing: '],
            'logbook' => ["Logbook {$v['label']}", "Logbook Anda untuk tanggal {$v['tanggal']} telah {$v['label']} oleh Pembimbing Lapangan. " . $catatan],
            'cuti' => ["Pengajuan Cuti / Izin {$v['label']}", "Pengajuan {$v['jenis']} ({$v['periode']}) telah {$v['label']} oleh Pembimbing Lapangan. " . $catatan],
            'catatan_cuti' => [' (Disetujui Pembimbing): '],
            'aduan' => ['Aduan Ditanggapi Pembimbing', "Aduan #{$v['nomor']} ({$v['judul']}) telah ditanggapi oleh Pembimbing Lapangan Anda. Status: {$v['status']}"],
            'nilai' => ['Nilai Evaluasi Dipublikasikan', "Pembimbing Lapangan Anda telah mempublikasikan penilaian untuk tanggal {$v['tanggal']} dengan Nilai Akhir: {$v['nilai']}."],
        };
    }

    /** Pencarian nama/NIM mahasiswa (dipakai daftar admin; pembimbing di web tidak punya filter ini). */
    protected function filterCariMahasiswa(Request $request, $query): void
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', fn ($m) => $m->where('nama_lengkap', 'LIKE', "%{$search}%")->orWhere('nim', 'LIKE', "%{$search}%"));
        }
    }

    // =========================================================================
    // DASHBOARD & MAHASISWA BIMBINGAN
    // =========================================================================

    /** GET /api/pembimbing/dashboard */
    public function dashboard(Request $request)
    {
        $ids = $this->bimbinganIds($request);
        $today = Carbon::today()->toDateString();

        $mahasiswa = Mahasiswa::with(['universitas', 'jurusan', 'latestPengajuan.divisi'])->whereIn('id_mhs', $ids)->get();
        $absenHariIni = Attendance::whereIn('mahasiswa_id', $ids)->where('attendance_date', $today)->get();

        $hadir = $absenHariIni->where('check_in_status', 'present')->count();
        $terlambat = $absenHariIni->where('check_in_status', 'late')->count();
        $izin = $absenHariIni->whereIn('check_in_status', ['leave', 'sick'])->count();

        return $this->ok([
            'summary' => [
                'total_mahasiswa' => $mahasiswa->count(),
                'magang_aktif' => PengajuanMagang::whereIn('mahasiswa_id', $ids)
                    ->whereDate('tanggal_mulai', '<=', $today)
                    ->whereDate('tanggal_selesai', '>=', $today)
                    ->count(),
                'hadir_hari_ini' => $hadir,
                'terlambat_hari_ini' => $terlambat,
                'izin_hari_ini' => $izin,
                'belum_hadir' => max(0, $mahasiswa->count() - ($hadir + $terlambat + $izin)),
                'koreksi_menunggu' => AttendanceCorrection::whereIn('mahasiswa_id', $ids)->where('status', 'submitted')->count(),
                'logbook_menunggu' => DailyLogbook::whereIn('mahasiswa_id', $ids)->where('status', 'submitted')->count(),
                'cuti_menunggu' => LeaveRequest::whereIn('mahasiswa_id', $ids)->where('status', 'submitted')->count(),
                'aduan_aktif' => Complaint::whereIn('mahasiswa_id', $ids)->whereIn('status', ['submitted', 'verified', 'in_progress'])->count(),
            ],
            'mahasiswa' => $mahasiswa->map(fn ($m) => $this->formatMahasiswa($m, $m->latestPengajuan))->values(),
            'recent_logbooks' => DailyLogbook::with(['mahasiswa.universitas', 'reviewer'])
                ->whereIn('mahasiswa_id', $ids)
                ->orderBy('logbook_date', 'desc')->limit(5)->get()
                ->map(fn ($l) => $this->withMhs($this->formatLogbook($l), $l))->values(),
            'recent_corrections' => AttendanceCorrection::with(['mahasiswa.universitas', 'reviewer'])
                ->whereIn('mahasiswa_id', $ids)->where('status', 'submitted')
                ->orderBy('created_at', 'desc')->limit(5)->get()
                ->map(fn ($c) => $this->withMhs($this->formatCorrection($c), $c))->values(),
        ]);
    }

    /** GET /api/pembimbing/mahasiswa?search=&status=&page= */
    public function mahasiswa(Request $request)
    {
        $query = Mahasiswa::with(['universitas', 'jurusan', 'latestPengajuan.divisi'])
            ->whereIn('id_mhs', $this->bimbinganIds($request));

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('nama_lengkap', 'LIKE', "%{$search}%")->orWhere('nim', 'LIKE', "%{$search}%"));
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->whereHas('latestPengajuan', fn ($q) => $q->where('status', $request->status));
        }

        return $this->paginated(
            $query->orderBy('nama_lengkap')->paginate($this->perPage($request, 12)),
            fn ($m) => $this->formatMahasiswa($m, $m->latestPengajuan)
        );
    }

    /** GET /api/pembimbing/mahasiswa/{id} — detail + statistik seperti halaman detail web. */
    public function mahasiswaDetail(Request $request, $id)
    {
        $this->authorizeMahasiswa($request, (int) $id);
        $m = Mahasiswa::with(['universitas', 'jurusan', 'latestPengajuan.divisi'])->findOrFail($id);
        $avg = DailyEvaluation::where('mahasiswa_id', $id)->avg('total_score');

        return $this->ok([
            'mahasiswa' => $this->formatMahasiswa($m, $m->latestPengajuan),
            'stats' => [
                'total_presensi' => Attendance::where('mahasiswa_id', $id)->count(),
                'total_hadir' => Attendance::where('mahasiswa_id', $id)->where('check_in_status', 'present')->count(),
                'total_terlambat' => Attendance::where('mahasiswa_id', $id)->where('check_in_status', 'late')->count(),
                'total_izin' => Attendance::where('mahasiswa_id', $id)->whereIn('check_in_status', ['leave', 'sick'])->count(),
                'rata_rata_nilai' => $avg !== null ? round((float) $avg, 1) : null,
            ],
            'recent_attendances' => Attendance::where('mahasiswa_id', $id)->orderBy('attendance_date', 'desc')->limit(10)->get()
                ->map(fn ($a) => $this->formatAttendance($a))->values(),
            'recent_logbooks' => DailyLogbook::with('reviewer')->where('mahasiswa_id', $id)->orderBy('logbook_date', 'desc')->limit(10)->get()
                ->map(fn ($l) => $this->formatLogbook($l))->values(),
            'evaluations' => DailyEvaluation::with(['details.criterion', 'evaluator'])->where('mahasiswa_id', $id)
                ->orderBy('evaluation_date', 'desc')->get()
                ->map(fn ($e) => $this->formatEvaluation($e, true))->values(),
        ]);
    }

    /** GET /api/pembimbing/referensi — pilihan filter & form. */
    public function references(Request $request)
    {
        return $this->ok([
            'mahasiswa' => Mahasiswa::whereIn('id_mhs', $this->bimbinganIds($request))->orderBy('nama_lengkap')->get()
                ->map(fn ($m) => ['id' => $m->id_mhs, 'nim' => $m->nim, 'nama_lengkap' => $m->nama_lengkap])->values(),
            'criteria' => EvaluationCriterion::where('is_active', true)->get()->map(fn ($c) => [
                'id' => $c->id_criterion,
                'name' => $c->criterion_name,
                'description' => $c->description,
                'weight' => (int) $c->weight,
                'maximum_score' => (int) $c->maximum_score,
            ])->values(),
            'logbook_status' => static::options(self::LOGBOOK_STATUS),
            'leave_status' => static::options(self::LEAVE_STATUS),
            'leave_types' => static::options(self::LEAVE_TYPES),
            'complaint_status' => static::options(self::COMPLAINT_STATUS),
            'complaint_categories' => static::options(self::COMPLAINT_CATEGORIES),
            'complaint_priorities' => static::options(self::COMPLAINT_PRIORITIES),
            'attendance_status' => static::options(self::ATTENDANCE_STATUS),
        ]);
    }

    // =========================================================================
    // PRESENSI & KOREKSI
    // =========================================================================

    /** GET /api/pembimbing/absensi?date=&status=&mahasiswa_id=&page= (+ statistik tanggal) */
    public function attendances(Request $request)
    {
        $ids = $this->bimbinganIds($request);
        $date = $request->input('date', Carbon::today()->toDateString());

        $query = Attendance::with(['mahasiswa.universitas'])->whereIn('mahasiswa_id', $ids);
        if ($request->filled('date')) {
            $query->where('attendance_date', $request->date);
        }
        $this->filterStatus($request, $query, 'check_in_status');
        $this->filterMahasiswa($request, $query);
        $this->filterCariMahasiswa($request, $query);

        $onDate = Attendance::whereIn('mahasiswa_id', $ids)->where('attendance_date', $date);

        return $this->paginated(
            $query->orderBy('attendance_date', 'desc')->orderBy('check_in_at', 'desc')->paginate($this->perPage($request, 15)),
            fn ($a) => $this->withMhs($this->formatAttendance($a), $a),
            [
                'date' => static::date($date),
                'total_mahasiswa' => count($ids),
                'hadir' => (clone $onDate)->where('check_in_status', 'present')->count(),
                'terlambat' => (clone $onDate)->where('check_in_status', 'late')->count(),
                'izin' => (clone $onDate)->whereIn('check_in_status', ['leave', 'sick'])->count(),
                'koreksi_menunggu' => AttendanceCorrection::whereIn('mahasiswa_id', $ids)->where('status', 'submitted')->count(),
            ]
        );
    }

    /** GET /api/pembimbing/absensi/koreksi?status= */
    public function corrections(Request $request)
    {
        $query = AttendanceCorrection::with(['mahasiswa.universitas', 'reviewer'])->whereIn('mahasiswa_id', $this->bimbinganIds($request));
        $this->filterStatus($request, $query);
        $this->filterMahasiswa($request, $query);
        $this->filterCariMahasiswa($request, $query);

        return $this->paginated(
            $query->orderBy('created_at', 'desc')->paginate($this->perPage($request, 15)),
            fn ($c) => $this->withMhs($this->formatCorrection($c), $c)
        );
    }

    /** POST /api/pembimbing/absensi/koreksi/{id}/review {status: approved|rejected, review_note} */
    public function reviewCorrection(Request $request, $id)
    {
        $correction = AttendanceCorrection::with('mahasiswa')->whereIn('mahasiswa_id', $this->bimbinganIds($request))->findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'review_note' => 'nullable|string|max:255',
        ]);

        $correction->update([
            'status' => $request->status,
            'reviewed_by' => $request->user()->id_users,
            'reviewed_at' => Carbon::now(),
            'review_note' => $request->review_note,
        ]);

        // Disetujui: perbarui presensi tanggal tersebut (sama dengan web)
        if ($request->status === 'approved') {
            $attendance = Attendance::firstOrNew([
                'mahasiswa_id' => $correction->mahasiswa_id,
                'attendance_date' => $correction->correction_date,
            ]);

            if ($correction->requested_check_in_at) {
                $attendance->check_in_at = $correction->requested_check_in_at;
                $attendance->check_in_status = 'present';
                $attendance->late_minutes = 0;
            }
            if ($correction->requested_check_out_at) {
                $attendance->check_out_at = $correction->requested_check_out_at;
                $attendance->check_out_status = 'present';
            }
            if ($attendance->check_in_at && $attendance->check_out_at) {
                // Carbon 3: selisih bertanda, hitung dari jam masuk ke jam pulang
                $attendance->total_work_minutes = max(0, (int) Carbon::parse($attendance->check_in_at)->diffInMinutes(Carbon::parse($attendance->check_out_at)));
            }

            $attendance->approval_status = 'corrected';
            $attendance->notes = ($attendance->notes ? $attendance->notes . ' | ' : '') . $this->teks('catatan_koreksi', [])[0] . ($request->review_note ?: 'Disetujui');
            $attendance->save();
        }

        if ($correction->mahasiswa && $correction->mahasiswa->user_id) {
            $label = $request->status === 'approved' ? 'Disetujui' : 'Ditolak';
            AppNotification::send(
                $correction->mahasiswa->user_id,
                $this->teks('koreksi', ['label' => $label, 'status' => $request->status, 'tanggal' => $correction->correction_date->format('Y-m-d'), 'catatan' => $request->review_note])[0],
                $this->teks('koreksi', ['label' => $label, 'status' => $request->status, 'tanggal' => $correction->correction_date->format('Y-m-d'), 'catatan' => $request->review_note])[1],
                $request->status === 'approved' ? 'success' : 'danger',
                route('peserta.absensi')
            );
        }

        return $this->ok(
            $this->withMhs($this->formatCorrection($correction->fresh(['mahasiswa.universitas', 'reviewer'])), $correction),
            'Status pengajuan koreksi presensi berhasil diperbarui.'
        );
    }

    // =========================================================================
    // LOGBOOK
    // =========================================================================

    /** GET /api/pembimbing/logbook?status=&date=&mahasiswa_id=&search=&page= */
    public function logbooks(Request $request)
    {
        $ids = $this->bimbinganIds($request);
        $query = DailyLogbook::with(['mahasiswa.universitas', 'reviewer'])->whereIn('mahasiswa_id', $ids);

        $this->filterStatus($request, $query);
        if ($request->filled('date')) {
            $query->where('logbook_date', $request->date);
        }
        $this->filterMahasiswa($request, $query);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('title', 'LIKE', "%{$search}%")
                ->orWhere('activity', 'LIKE', "%{$search}%")
                ->orWhereHas('mahasiswa', fn ($m) => $m->where('nama_lengkap', 'LIKE', "%{$search}%")->orWhere('nim', 'LIKE', "%{$search}%")));
        }

        return $this->paginated(
            $query->orderBy('logbook_date', 'desc')->paginate($this->perPage($request, 15)),
            fn ($l) => $this->withMhs($this->formatLogbook($l), $l),
            [
                'pending' => DailyLogbook::whereIn('mahasiswa_id', $ids)->where('status', 'submitted')->count(),
                'approved' => DailyLogbook::whereIn('mahasiswa_id', $ids)->where('status', 'approved')->count(),
                'revision' => DailyLogbook::whereIn('mahasiswa_id', $ids)->where('status', 'revision_required')->count(),
            ]
        );
    }

    /** GET /api/pembimbing/logbook/{id} */
    public function logbookDetail(Request $request, $id)
    {
        $logbook = DailyLogbook::with(['mahasiswa.universitas', 'comments.user', 'reviewer'])
            ->whereIn('mahasiswa_id', $this->bimbinganIds($request))
            ->findOrFail($id);

        return $this->ok($this->withMhs($this->formatLogbook($logbook, true), $logbook));
    }

    /** POST /api/pembimbing/logbook/{id}/review {status: approved|revision_required|rejected, review_note, comment} */
    public function reviewLogbook(Request $request, $id)
    {
        $logbook = DailyLogbook::with('mahasiswa')->whereIn('mahasiswa_id', $this->bimbinganIds($request))->findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,revision_required,rejected',
            'review_note' => 'nullable|string|max:500',
            'comment' => 'nullable|string|max:500',
        ]);

        $logbook->update([
            'status' => $request->status,
            'reviewed_by' => $request->user()->id_users,
            'reviewed_at' => Carbon::now(),
            'review_note' => $request->review_note,
        ]);

        if ($request->filled('comment')) {
            LogbookComment::create([
                'logbook_id' => $logbook->id_logbook,
                'user_id' => $request->user()->id_users,
                'comment' => $request->comment,
            ]);
        }

        if ($logbook->mahasiswa && $logbook->mahasiswa->user_id) {
            $label = match ($request->status) {
                'approved' => 'Disetujui',
                'revision_required' => 'Perlu Revisi',
                default => 'Ditolak',
            };
            AppNotification::send(
                $logbook->mahasiswa->user_id,
                $this->teks('logbook', ['label' => $label, 'tanggal' => $logbook->logbook_date->format('d M Y'), 'catatan' => $request->review_note])[0],
                $this->teks('logbook', ['label' => $label, 'tanggal' => $logbook->logbook_date->format('d M Y'), 'catatan' => $request->review_note])[1],
                $request->status === 'approved' ? 'success' : 'warning',
                route('peserta.logbook')
            );
        }

        $logbook = $logbook->fresh(['mahasiswa.universitas', 'comments.user', 'reviewer']);

        return $this->ok($this->withMhs($this->formatLogbook($logbook, true), $logbook), 'Status dan catatan review logbook berhasil disimpan.');
    }

    // =========================================================================
    // CUTI / IZIN
    // =========================================================================

    /** GET /api/pembimbing/cuti?status=&leave_type=&mahasiswa_id=&page= */
    public function leaveRequests(Request $request)
    {
        $ids = $this->bimbinganIds($request);
        $query = LeaveRequest::with(['mahasiswa.universitas', 'reviewer'])->whereIn('mahasiswa_id', $ids);

        $this->filterStatus($request, $query);
        $this->filterStatus($request, $query, 'leave_type', 'leave_type');
        $this->filterMahasiswa($request, $query);
        $this->filterCariMahasiswa($request, $query);

        return $this->paginated(
            $query->orderBy('created_at', 'desc')->paginate($this->perPage($request, 15)),
            fn ($l) => $this->withMhs($this->formatLeave($l), $l),
            [
                'pending' => LeaveRequest::whereIn('mahasiswa_id', $ids)->where('status', 'submitted')->count(),
                'approved' => LeaveRequest::whereIn('mahasiswa_id', $ids)->where('status', 'approved')->count(),
                'rejected' => LeaveRequest::whereIn('mahasiswa_id', $ids)->where('status', 'rejected')->count(),
            ]
        );
    }

    /** POST /api/pembimbing/cuti/{id}/review {status: approved|rejected, review_note} */
    public function reviewLeaveRequest(Request $request, $id)
    {
        $leave = LeaveRequest::with('mahasiswa')->whereIn('mahasiswa_id', $this->bimbinganIds($request))->findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'review_note' => 'nullable|string|max:255',
        ]);

        $leave->update([
            'status' => $request->status,
            'reviewed_by' => $request->user()->id_users,
            'reviewed_at' => Carbon::now(),
            'review_note' => $request->review_note,
        ]);

        // Disetujui: tandai presensi hari kerja dalam rentang cuti (sama dengan web)
        if ($request->status === 'approved') {
            $attendanceStatus = $leave->leave_type === 'sick_leave' ? 'sick' : 'leave';
            foreach (CarbonPeriod::create($leave->start_date, $leave->end_date) as $date) {
                if ($date->isWeekend()) {
                    continue;
                }
                Attendance::updateOrCreate(
                    ['mahasiswa_id' => $leave->mahasiswa_id, 'attendance_date' => $date->toDateString()],
                    [
                        'check_in_status' => $attendanceStatus,
                        'work_status' => $attendanceStatus,
                        'approval_status' => 'verified',
                        'notes' => LeaveRequest::getLeaveTypeLabel($leave->leave_type) . $this->teks('catatan_cuti', [])[0] . $leave->reason,
                    ]
                );
            }
        }

        if ($leave->mahasiswa && $leave->mahasiswa->user_id) {
            $label = $request->status === 'approved' ? 'Disetujui' : 'Ditolak';
            AppNotification::send(
                $leave->mahasiswa->user_id,
                $this->teks('cuti', ['label' => $label, 'jenis' => LeaveRequest::getLeaveTypeLabel($leave->leave_type), 'periode' => "{$leave->start_date->format('d M Y')} - {$leave->end_date->format('d M Y')}", 'catatan' => $request->review_note])[0],
                $this->teks('cuti', ['label' => $label, 'jenis' => LeaveRequest::getLeaveTypeLabel($leave->leave_type), 'periode' => "{$leave->start_date->format('d M Y')} - {$leave->end_date->format('d M Y')}", 'catatan' => $request->review_note])[1],
                $request->status === 'approved' ? 'success' : 'danger',
                route('peserta.cuti')
            );
        }

        $leave = $leave->fresh(['mahasiswa.universitas', 'reviewer']);

        return $this->ok($this->withMhs($this->formatLeave($leave), $leave), 'Status pengajuan cuti/izin berhasil diperbarui.');
    }

    // =========================================================================
    // ADUAN
    // =========================================================================

    /** GET /api/pembimbing/aduan?status=&category=&priority=&page= */
    public function complaints(Request $request)
    {
        $ids = $this->bimbinganIds($request);
        $query = Complaint::with(['mahasiswa.universitas', 'responses.user'])->whereIn('mahasiswa_id', $ids);

        $this->filterStatus($request, $query);
        $this->filterStatus($request, $query, 'category', 'category');
        $this->filterStatus($request, $query, 'priority', 'priority');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('complaint_number', 'LIKE', "%{$search}%")
                ->orWhere('title', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhereHas('mahasiswa', fn ($m) => $m->where('nama_lengkap', 'LIKE', "%{$search}%")->orWhere('nim', 'LIKE', "%{$search}%")));
        }

        $userId = $request->user()->id_users;

        return $this->paginated(
            $query->orderBy('created_at', 'desc')->paginate($this->perPage($request, 15)),
            fn ($c) => $this->withMhs($this->formatComplaint($c, $userId, true), $c),
            [
                'pending' => Complaint::whereIn('mahasiswa_id', $ids)->where('status', 'submitted')->count(),
                'in_progress' => Complaint::whereIn('mahasiswa_id', $ids)->where('status', 'in_progress')->count(),
                'resolved' => Complaint::whereIn('mahasiswa_id', $ids)->where('status', 'resolved')->count(),
            ]
        );
    }

    /** GET /api/pembimbing/aduan/{id} */
    public function complaintDetail(Request $request, $id)
    {
        $complaint = Complaint::with(['mahasiswa.universitas', 'responses.user'])
            ->whereIn('mahasiswa_id', $this->bimbinganIds($request))
            ->findOrFail($id);

        return $this->ok($this->withMhs($this->formatComplaint($complaint, $request->user()->id_users, true), $complaint));
    }

    /** POST /api/pembimbing/aduan/{id}/reply {message, status, is_internal_note} */
    public function replyComplaint(Request $request, $id)
    {
        $complaint = Complaint::with('mahasiswa')->whereIn('mahasiswa_id', $this->bimbinganIds($request))->findOrFail($id);

        $request->validate([
            'message' => 'required|string|min:3',
            'status' => ['required', Rule::in(array_keys(self::COMPLAINT_STATUS))],
            'is_internal_note' => 'nullable|boolean',
        ]);

        $userId = $request->user()->id_users;

        ComplaintResponse::create([
            'complaint_id' => $complaint->id_complaint,
            'user_id' => $userId,
            'message' => $request->message,
            'is_internal_note' => $request->boolean('is_internal_note'),
        ]);

        $complaint->update([
            'status' => $request->status,
            'assigned_to' => $userId,
            'resolved_at' => in_array($request->status, ['resolved', 'closed']) ? Carbon::now() : $complaint->resolved_at,
        ]);

        if (!$request->boolean('is_internal_note') && $complaint->mahasiswa && $complaint->mahasiswa->user_id) {
            AppNotification::send(
                $complaint->mahasiswa->user_id,
                $this->teks('aduan', ['nomor' => $complaint->complaint_number, 'judul' => $complaint->title, 'status' => strtoupper($request->status)])[0],
                $this->teks('aduan', ['nomor' => $complaint->complaint_number, 'judul' => $complaint->title, 'status' => strtoupper($request->status)])[1],
                'info',
                route('peserta.aduan')
            );
        }

        $complaint = $complaint->fresh(['mahasiswa.universitas', 'responses.user']);

        return $this->ok(
            $this->withMhs($this->formatComplaint($complaint, $userId, true), $complaint),
            'Tanggapan berhasil dikirim dan status aduan diperbarui.'
        );
    }

    // =========================================================================
    // PENILAIAN
    // =========================================================================

    /** GET /api/pembimbing/nilai?mahasiswa_id=&status=&page= */
    public function evaluations(Request $request)
    {
        $query = DailyEvaluation::with(['mahasiswa.universitas', 'details.criterion', 'evaluator'])
            ->whereIn('mahasiswa_id', $this->bimbinganIds($request));

        $this->filterMahasiswa($request, $query);
        $this->filterStatus($request, $query);

        return $this->paginated(
            $query->orderBy('evaluation_date', 'desc')->paginate($this->perPage($request, 15)),
            fn ($e) => $this->withMhs($this->formatEvaluation($e, true), $e)
        );
    }

    /**
     * POST /api/pembimbing/nilai/store
     * {mahasiswa_id, evaluation_date, scores: {criterion_id: nilai}, detail_notes?, participant_note, internal_note, action_type: draft|publish}
     */
    public function storeEvaluation(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id_mhs',
            'evaluation_date' => 'required|date',
            'scores' => 'required|array',
            'scores.*' => 'numeric|min:0|max:100',
            'detail_notes' => 'nullable|array',
            'participant_note' => 'nullable|string',
            'internal_note' => 'nullable|string',
            'action_type' => 'required|in:draft,publish',
        ]);

        $this->authorizeMahasiswa($request, (int) $request->mahasiswa_id);

        // Nilai akhir = rata-rata berbobot (sama dengan web)
        $criteria = EvaluationCriterion::where('is_active', true)->get()->keyBy('id_criterion');
        $totalWeight = $criteria->sum('weight') ?: 100;
        $weightedTotal = 0;
        foreach ($request->scores as $criterionId => $score) {
            $weightedTotal += $score * ($criteria->get($criterionId)->weight ?? 10);
        }
        $finalScore = round($weightedTotal / $totalWeight, 2);
        $status = $request->action_type === 'publish' ? 'published' : 'draft';

        $evaluation = DailyEvaluation::updateOrCreate(
            ['mahasiswa_id' => $request->mahasiswa_id, 'evaluation_date' => $request->evaluation_date],
            [
                'total_score' => $finalScore,
                'participant_note' => $request->participant_note,
                'internal_note' => $request->internal_note,
                'evaluator_id' => $request->user()->id_users,
                'status' => $status,
                'published_at' => $status === 'published' ? Carbon::now() : null,
            ]
        );

        foreach ($request->scores as $criterionId => $score) {
            EvaluationDetail::updateOrCreate(
                ['daily_evaluation_id' => $evaluation->id_evaluation, 'criterion_id' => $criterionId],
                ['score' => $score, 'notes' => $request->detail_notes[$criterionId] ?? null]
            );
        }

        $mhs = Mahasiswa::find($request->mahasiswa_id);
        if ($status === 'published' && $mhs && $mhs->user_id) {
            AppNotification::send(
                $mhs->user_id,
                $this->teks('nilai', ['tanggal' => $request->evaluation_date, 'nilai' => $finalScore])[0],
                $this->teks('nilai', ['tanggal' => $request->evaluation_date, 'nilai' => $finalScore])[1],
                'success',
                route('peserta.nilai')
            );
        }

        $evaluation = $evaluation->fresh(['mahasiswa.universitas', 'details.criterion', 'evaluator']);

        return $this->ok(
            $this->withMhs($this->formatEvaluation($evaluation, true), $evaluation),
            $status === 'published'
                ? "Penilaian evaluasi berhasil dipublikasikan dengan nilai akhir {$finalScore}."
                : 'Draft penilaian berhasil disimpan.'
        );
    }

    // =========================================================================
    // PROFIL
    // =========================================================================

    /** GET /api/pembimbing/profil */
    public function profile(Request $request)
    {
        return $this->ok($this->profileData($request));
    }

    protected function profileData(Request $request): array
    {
        $user = $request->user()->load('divisi');

        return [
            'id' => $user->id_users,
            'nama_lengkap' => $user->nama_lengkap,
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'divisi' => $user->divisi->nama_divisi ?? null,
            'total_bimbingan' => count($this->bimbinganIds($request)),
            'last_login_at' => static::dateTime($user->last_login_at),
        ];
    }

    /** POST /api/pembimbing/profil/update {nama_lengkap, email, phone, old_password?, password?, password_confirmation?} */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id_users . ',id_users',
            'phone' => 'nullable|string|max:30',
            'old_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            if (!$request->filled('old_password') || !Hash::check($request->old_password, $user->password)) {
                return $this->fail('Password saat ini tidak sesuai.', 422, ['errors' => ['old_password' => ['Password saat ini tidak sesuai.']]]);
            }
            $user->password = Hash::make($request->password);
        }

        $user->nama_lengkap = $request->nama_lengkap;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return $this->ok($this->profileData($request), 'Profil Pembimbing Lapangan berhasil diperbarui.');
    }
}
