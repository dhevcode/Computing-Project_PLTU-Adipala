<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\DailyLogbook;
use App\Models\LogbookComment;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\DailyEvaluation;
use App\Models\EvaluationCriterion;
use App\Models\EvaluationDetail;
use App\Models\LeaveRequest;
use App\Models\AppNotification;

class PembimbingController extends Controller
{
    /**
     * Ambil array ID mahasiswa yang dibimbing oleh pembimbing lapangan saat ini.
     * Menggabungkan pencocokan via pembimbing_id dan nama_pembimbing (case-insensitive).
     */
    protected function getBimbinganMahasiswaIds(): array
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }

        $userName = trim(strtolower($user->nama_lengkap ?? ''));

        return PengajuanMagang::where(function ($q) use ($user, $userName) {
                $q->where('pembimbing_id', $user->id_users);
                if (!empty($userName)) {
                    $q->orWhereRaw('LOWER(TRIM(nama_pembimbing)) = ?', [$userName]);
                }
            })
            ->pluck('mahasiswa_id')
            ->unique()
            ->toArray();
    }

    /**
     * Pastikan mahasiswa yang diakses benar-benar dibimbing oleh user ini.
     */
    protected function authorizeMahasiswa(int $mahasiswaId): void
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();
        if (!in_array($mahasiswaId, $mhsIds)) {
            abort(403, 'Akses ditolak. Anda hanya berwenang memonitor dan mengevaluasi peserta magang yang Anda bimbing.');
        }
    }

    // =========================================================================
    // 1. DASHBOARD PEMBIMBING
    // =========================================================================
    public function dashboard()
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();
        $today = Carbon::today()->toDateString();

        // Data mahasiswa bimbingan
        $mahasiswaBimbingan = Mahasiswa::with(['universitas', 'jurusan', 'latestPengajuan.divisi'])
            ->whereIn('id_mhs', $mhsIds)
            ->get();

        $totalMahasiswa = $mahasiswaBimbingan->count();

        // Status magang hari ini
        $magangAktif = PengajuanMagang::whereIn('mahasiswa_id', $mhsIds)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();

        // Absensi Hari Ini
        $attendancesHariIni = Attendance::whereIn('mahasiswa_id', $mhsIds)
            ->where('attendance_date', $today)
            ->get();

        $hadirHariIni = $attendancesHariIni->where('check_in_status', 'present')->count();
        $terlambatHariIni = $attendancesHariIni->where('check_in_status', 'late')->count();
        $izinHariIni = $attendancesHariIni->whereIn('check_in_status', ['leave', 'sick'])->count();
        $belumHadir = max(0, $totalMahasiswa - ($hadirHariIni + $terlambatHariIni + $izinHariIni));

        // Pengajuan koreksi absensi menunggu review
        $pendingCorrectionsCount = AttendanceCorrection::whereIn('mahasiswa_id', $mhsIds)
            ->where('status', 'submitted')
            ->count();

        // Logbook menunggu review
        $pendingLogbookCount = DailyLogbook::whereIn('mahasiswa_id', $mhsIds)
            ->where('status', 'submitted')
            ->count();

        // Cuti menunggu review
        $pendingCutiCount = LeaveRequest::whereIn('mahasiswa_id', $mhsIds)
            ->where('status', 'submitted')
            ->count();

        // Aduan aktif
        $activeAduanCount = Complaint::whereIn('mahasiswa_id', $mhsIds)
            ->whereIn('status', ['submitted', 'verified', 'in_progress'])
            ->count();

        // Logbook terbaru yang butuh perhatian
        $recentLogbooks = DailyLogbook::with(['mahasiswa.universitas'])
            ->whereIn('mahasiswa_id', $mhsIds)
            ->orderBy('logbook_date', 'desc')
            ->limit(5)
            ->get();

        // Koreksi absensi terbaru
        $recentCorrections = AttendanceCorrection::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mhsIds)
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pembimbing.dashboard', compact(
            'mahasiswaBimbingan',
            'totalMahasiswa',
            'magangAktif',
            'hadirHariIni',
            'terlambatHariIni',
            'izinHariIni',
            'belumHadir',
            'pendingCorrectionsCount',
            'pendingLogbookCount',
            'pendingCutiCount',
            'activeAduanCount',
            'recentLogbooks',
            'recentCorrections'
        ));
    }

    // =========================================================================
    // 2. DATA MAHASISWA BIMBINGAN
    // =========================================================================
    public function mahasiswa(Request $request)
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();

        $query = Mahasiswa::with(['universitas', 'jurusan', 'latestPengajuan.divisi'])
            ->whereIn('id_mhs', $mhsIds);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nim', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('universitas_id')) {
            $query->where('universitas_id', $request->universitas_id);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            $query->whereHas('latestPengajuan', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $mahasiswas = $query->paginate(12)->withQueryString();

        return view('pembimbing.mahasiswa.index', compact('mahasiswas'));
    }

    public function detailMahasiswa($id)
    {
        $this->authorizeMahasiswa((int) $id);

        $mahasiswa = Mahasiswa::with([
            'universitas',
            'jurusan',
            'latestPengajuan.divisi',
            'pengajuanMagang.divisi',
            'sertifikat'
        ])->findOrFail($id);

        // Statistik Kehadiran Mahasiswa
        $totalPresensi = Attendance::where('mahasiswa_id', $id)->count();
        $totalHadir = Attendance::where('mahasiswa_id', $id)->where('check_in_status', 'present')->count();
        $totalTerlambat = Attendance::where('mahasiswa_id', $id)->where('check_in_status', 'late')->count();
        $totalIzin = Attendance::where('mahasiswa_id', $id)->whereIn('check_in_status', ['leave', 'sick'])->count();

        // Rata-rata nilai
        $avgScore = DailyEvaluation::where('mahasiswa_id', $id)->avg('total_score');

        // Riwayat 10 presensi terakhir
        $recentAttendances = Attendance::where('mahasiswa_id', $id)
            ->orderBy('attendance_date', 'desc')
            ->limit(10)
            ->get();

        // Riwayat 10 logbook terakhir
        $recentLogbooks = DailyLogbook::where('mahasiswa_id', $id)
            ->orderBy('logbook_date', 'desc')
            ->limit(10)
            ->get();

        // Riwayat evaluasi
        $evaluations = DailyEvaluation::where('mahasiswa_id', $id)
            ->with(['details.criterion', 'evaluator'])
            ->orderBy('evaluation_date', 'desc')
            ->get();

        return view('pembimbing.mahasiswa.detail', compact(
            'mahasiswa',
            'totalPresensi',
            'totalHadir',
            'totalTerlambat',
            'totalIzin',
            'avgScore',
            'recentAttendances',
            'recentLogbooks',
            'evaluations'
        ));
    }

    // =========================================================================
    // 3. MONITORING PRESENSI & KOREKSI
    // =========================================================================
    public function absensi(Request $request)
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();
        $today = Carbon::today()->toDateString();
        $date = $request->input('date', $today);

        $query = Attendance::with(['mahasiswa.universitas', 'mahasiswa.jurusan'])
            ->whereIn('mahasiswa_id', $mhsIds);

        if ($request->filled('date')) {
            $query->where('attendance_date', $request->date);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('check_in_status', $request->status);
        }

        if ($request->filled('mahasiswa_id') && $request->mahasiswa_id !== 'all') {
            $this->authorizeMahasiswa((int) $request->mahasiswa_id);
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        $attendances = $query->orderBy('check_in_at', 'desc')->paginate(15)->withQueryString();

        // Pengajuan koreksi yang menunggu review
        $pendingCorrections = AttendanceCorrection::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mhsIds)
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->get();

        $allCorrections = AttendanceCorrection::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mhsIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'corrections_page')
            ->withQueryString();

        // Statistik tanggal terpilih
        $totalHadirHariIni = Attendance::whereIn('mahasiswa_id', $mhsIds)
            ->where('attendance_date', $date)
            ->where('check_in_status', 'present')
            ->count();

        $totalTerlambatHariIni = Attendance::whereIn('mahasiswa_id', $mhsIds)
            ->where('attendance_date', $date)
            ->where('check_in_status', 'late')
            ->count();

        $totalIzinHariIni = Attendance::whereIn('mahasiswa_id', $mhsIds)
            ->where('attendance_date', $date)
            ->whereIn('check_in_status', ['leave', 'sick'])
            ->count();

        $totalMhs = count($mhsIds);

        $mahasiswaList = Mahasiswa::whereIn('id_mhs', $mhsIds)->orderBy('nama_lengkap')->get();

        return view('pembimbing.absensi.index', compact(
            'attendances',
            'pendingCorrections',
            'allCorrections',
            'date',
            'totalHadirHariIni',
            'totalTerlambatHariIni',
            'totalIzinHariIni',
            'totalMhs',
            'mahasiswaList'
        ));
    }

    public function reviewKoreksi(Request $request, $id)
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();

        $correction = AttendanceCorrection::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mhsIds)
            ->findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'review_note' => 'nullable|string|max:255',
        ]);

        $correction->update([
            'status' => $request->status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => Carbon::now(),
            'review_note' => $request->review_note,
        ]);

        // Jika disetujui, update kehadiran
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
                $attendance->total_work_minutes = max(0, (int) Carbon::parse($attendance->check_out_at)->diffInMinutes(Carbon::parse($attendance->check_in_at)));
            }

            $attendance->approval_status = 'corrected';
            $attendance->notes = ($attendance->notes ? $attendance->notes . ' | ' : '') . 'Disetujui Pembimbing: ' . ($request->review_note ?: 'Disetujui');
            $attendance->save();
        }

        // Kirim notifikasi ke mahasiswa
        if ($correction->mahasiswa && $correction->mahasiswa->user_id) {
            $statusLabel = ($request->status === 'approved') ? 'Disetujui' : 'Ditolak';
            AppNotification::send(
                $correction->mahasiswa->user_id,
                'Koreksi Presensi ' . $statusLabel,
                "Pengajuan koreksi presensi tanggal {$correction->correction_date} telah {$statusLabel} oleh Pembimbing Lapangan. " . ($request->review_note ? "Catatan: {$request->review_note}" : ''),
                $request->status === 'approved' ? 'success' : 'danger',
                route('peserta.absensi')
            );
        }

        return back()->with('success', 'Status pengajuan koreksi presensi berhasil diperbarui.');
    }

    // =========================================================================
    // 4. MONITORING LOGBOOK PKL
    // =========================================================================
    public function logbook(Request $request)
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();

        $query = DailyLogbook::with(['mahasiswa.universitas', 'comments.user'])
            ->whereIn('mahasiswa_id', $mhsIds);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('logbook_date', $request->date);
        }

        if ($request->filled('mahasiswa_id') && $request->mahasiswa_id !== 'all') {
            $this->authorizeMahasiswa((int) $request->mahasiswa_id);
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('activity', 'LIKE', "%{$search}%");
            });
        }

        $logbooks = $query->orderBy('logbook_date', 'desc')->paginate(15)->withQueryString();

        $pendingCount = DailyLogbook::whereIn('mahasiswa_id', $mhsIds)->where('status', 'submitted')->count();
        $approvedCount = DailyLogbook::whereIn('mahasiswa_id', $mhsIds)->where('status', 'approved')->count();
        $revisionCount = DailyLogbook::whereIn('mahasiswa_id', $mhsIds)->where('status', 'revision_required')->count();

        $mahasiswaList = Mahasiswa::whereIn('id_mhs', $mhsIds)->orderBy('nama_lengkap')->get();

        return view('pembimbing.logbook.index', compact(
            'logbooks',
            'pendingCount',
            'approvedCount',
            'revisionCount',
            'mahasiswaList'
        ));
    }

    public function reviewLogbook(Request $request, $id)
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();

        $logbook = DailyLogbook::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mhsIds)
            ->findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,revision_required,rejected',
            'review_note' => 'nullable|string|max:500',
            'comment' => 'nullable|string|max:500',
        ]);

        $logbook->update([
            'status' => $request->status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => Carbon::now(),
            'review_note' => $request->review_note,
        ]);

        if ($request->filled('comment')) {
            LogbookComment::create([
                'logbook_id' => $logbook->id_logbook,
                'user_id' => Auth::id(),
                'comment' => $request->comment,
            ]);
        }

        // Kirim notifikasi ke mahasiswa
        if ($logbook->mahasiswa && $logbook->mahasiswa->user_id) {
            $statusLabel = match($request->status) {
                'approved' => 'Disetujui',
                'revision_required' => 'Perlu Revisi',
                default => 'Ditolak',
            };

            $notifType = ($request->status === 'approved') ? 'success' : 'warning';

            AppNotification::send(
                $logbook->mahasiswa->user_id,
                "Logbook {$statusLabel}",
                "Logbook Anda untuk tanggal {$logbook->logbook_date->format('d M Y')} telah {$statusLabel} oleh Pembimbing Lapangan. " . ($request->review_note ? "Catatan: {$request->review_note}" : ''),
                $notifType,
                route('peserta.logbook')
            );
        }

        return back()->with('success', 'Status dan catatan review logbook berhasil disimpan.');
    }

    // =========================================================================
    // 5. MONITORING CUTI & IZIN
    // =========================================================================
    public function cuti(Request $request)
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();

        $query = LeaveRequest::with(['mahasiswa.universitas'])
            ->whereIn('mahasiswa_id', $mhsIds);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type') && $request->leave_type !== 'all') {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('mahasiswa_id') && $request->mahasiswa_id !== 'all') {
            $this->authorizeMahasiswa((int) $request->mahasiswa_id);
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $pendingCount = LeaveRequest::whereIn('mahasiswa_id', $mhsIds)->where('status', 'submitted')->count();
        $approvedCount = LeaveRequest::whereIn('mahasiswa_id', $mhsIds)->where('status', 'approved')->count();
        $rejectedCount = LeaveRequest::whereIn('mahasiswa_id', $mhsIds)->where('status', 'rejected')->count();

        $mahasiswaList = Mahasiswa::whereIn('id_mhs', $mhsIds)->orderBy('nama_lengkap')->get();

        return view('pembimbing.cuti.index', compact(
            'leaves',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'mahasiswaList'
        ));
    }

    public function reviewCuti(Request $request, $id)
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();

        $leave = LeaveRequest::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mhsIds)
            ->findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'review_note' => 'nullable|string|max:255',
        ]);

        $leave->update([
            'status' => $request->status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => Carbon::now(),
            'review_note' => $request->review_note,
        ]);

        // Jika disetujui, update absensi untuk rentang tanggal cuti
        if ($request->status === 'approved') {
            $period = CarbonPeriod::create($leave->start_date, $leave->end_date);
            $attendanceStatus = ($leave->leave_type === 'sick_leave') ? 'sick' : 'leave';

            foreach ($period as $date) {
                if ($date->isWeekend()) {
                    continue;
                }

                Attendance::updateOrCreate(
                    [
                        'mahasiswa_id' => $leave->mahasiswa_id,
                        'attendance_date' => $date->toDateString(),
                    ],
                    [
                        'check_in_status' => $attendanceStatus,
                        'work_status' => $attendanceStatus,
                        'approval_status' => 'verified',
                        'notes' => LeaveRequest::getLeaveTypeLabel($leave->leave_type) . ' (Disetujui Pembimbing): ' . $leave->reason,
                    ]
                );
            }
        }

        // Kirim notifikasi ke mahasiswa
        if ($leave->mahasiswa && $leave->mahasiswa->user_id) {
            $statusLabel = ($request->status === 'approved') ? 'Disetujui' : 'Ditolak';
            $notifType = ($request->status === 'approved') ? 'success' : 'danger';

            AppNotification::send(
                $leave->mahasiswa->user_id,
                "Pengajuan Cuti / Izin {$statusLabel}",
                "Pengajuan " . LeaveRequest::getLeaveTypeLabel($leave->leave_type) . " ({$leave->start_date->format('d M Y')} - {$leave->end_date->format('d M Y')}) telah {$statusLabel} oleh Pembimbing Lapangan. " . ($request->review_note ? "Catatan: {$request->review_note}" : ''),
                $notifType,
                route('peserta.cuti')
            );
        }

        return back()->with('success', 'Status pengajuan cuti/izin berhasil diperbarui.');
    }

    // =========================================================================
    // 6. MONITORING ADUAN MASALAH
    // =========================================================================
    public function aduan(Request $request)
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();

        $query = Complaint::with(['mahasiswa.universitas', 'responses.user'])
            ->whereIn('mahasiswa_id', $mhsIds);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        $complaints = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $pendingCount = Complaint::whereIn('mahasiswa_id', $mhsIds)->where('status', 'submitted')->count();
        $inProgressCount = Complaint::whereIn('mahasiswa_id', $mhsIds)->where('status', 'in_progress')->count();
        $resolvedCount = Complaint::whereIn('mahasiswa_id', $mhsIds)->where('status', 'resolved')->count();

        return view('pembimbing.aduan.index', compact(
            'complaints',
            'pendingCount',
            'inProgressCount',
            'resolvedCount'
        ));
    }

    public function replyAduan(Request $request, $id)
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();

        $complaint = Complaint::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mhsIds)
            ->findOrFail($id);

        $request->validate([
            'message' => 'required|string|min:3',
            'status' => 'required|in:submitted,verified,in_progress,waiting_for_participant,resolved,closed,rejected',
            'is_internal_note' => 'nullable|boolean',
        ]);

        ComplaintResponse::create([
            'complaint_id' => $complaint->id_complaint,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_internal_note' => $request->boolean('is_internal_note'),
        ]);

        $complaint->update([
            'status' => $request->status,
            'assigned_to' => Auth::id(),
            'resolved_at' => in_array($request->status, ['resolved', 'closed']) ? Carbon::now() : $complaint->resolved_at,
        ]);

        // Kirim notifikasi jika bukan internal note
        if (!$request->boolean('is_internal_note') && $complaint->mahasiswa && $complaint->mahasiswa->user_id) {
            AppNotification::send(
                $complaint->mahasiswa->user_id,
                'Aduan Ditanggapi Pembimbing',
                "Aduan #{$complaint->complaint_number} ({$complaint->title}) telah ditanggapi oleh Pembimbing Lapangan Anda. Status: " . strtoupper($request->status),
                'info',
                route('peserta.aduan')
            );
        }

        return back()->with('success', 'Tanggapan berhasil dikirim dan status aduan diperbarui.');
    }

    // =========================================================================
    // 7. EVALUASI & PENILAIAN HARIAN / BERKALA
    // =========================================================================
    public function nilai(Request $request)
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();

        $criteria = EvaluationCriterion::where('is_active', true)->get();
        $mahasiswas = Mahasiswa::with(['universitas', 'latestPengajuan.divisi'])
            ->whereIn('id_mhs', $mhsIds)
            ->get();

        $query = DailyEvaluation::with(['mahasiswa.universitas', 'details.criterion', 'evaluator'])
            ->whereIn('mahasiswa_id', $mhsIds);

        if ($request->filled('mahasiswa_id') && $request->mahasiswa_id !== 'all') {
            $this->authorizeMahasiswa((int) $request->mahasiswa_id);
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $evaluations = $query->orderBy('evaluation_date', 'desc')->paginate(15)->withQueryString();

        return view('pembimbing.nilai.index', compact('evaluations', 'mahasiswas', 'criteria'));
    }

    public function storeNilai(Request $request)
    {
        $mhsIds = $this->getBimbinganMahasiswaIds();

        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id_mhs',
            'evaluation_date' => 'required|date',
            'scores' => 'required|array',
            'participant_note' => 'nullable|string',
            'internal_note' => 'nullable|string',
            'action_type' => 'required|in:draft,publish',
        ]);

        $this->authorizeMahasiswa((int) $request->mahasiswa_id);

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

        // Simpan rincian nilai per kriteria
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
                'Nilai Evaluasi Dipublikasikan',
                "Pembimbing Lapangan Anda telah mempublikasikan penilaian untuk tanggal {$request->evaluation_date} dengan Nilai Akhir: {$finalScore}.",
                'success',
                route('peserta.nilai')
            );
        }

        $msg = ($status === 'published')
            ? "Penilaian evaluasi berhasil dipublikasikan dengan nilai akhir {$finalScore}."
            : 'Draft penilaian berhasil disimpan.';

        return back()->with('success', $msg);
    }

    // =========================================================================
    // 8. PROFIL PEMBIMBING
    // =========================================================================
    public function profil()
    {
        $user = Auth::user()->load('divisi');
        $mhsIds = $this->getBimbinganMahasiswaIds();
        $totalBimbingan = count($mhsIds);

        return view('pembimbing.profil.edit', compact('user', 'totalBimbingan'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email'        => 'required|email|max:150|unique:users,email,' . $user->id_users . ',id_users',
            'phone'        => 'nullable|string|max:30',
            'old_password' => 'nullable|string',
            'password'     => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            if (!$request->filled('old_password') || !Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Password saat ini tidak sesuai.'])->withInput();
            }
            $user->password = Hash::make($request->password);
        }

        $user->nama_lengkap = $request->nama_lengkap;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Profil Pembimbing Lapangan berhasil diperbarui.');
    }
}
