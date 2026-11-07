<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsMonitoringData;
use App\Models\AppNotification;
use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\AttendancePolicy;
use App\Models\CompanyInformation;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\DailyEvaluation;
use App\Models\DailyLogbook;
use App\Models\LeaveRequest;
use App\Models\Mahasiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * API Portal Peserta untuk aplikasi mobile.
 * Logika mengikuti App\Http\Controllers\PesertaController (web) agar data dan notifikasi sama.
 */
class PesertaApiController extends ApiController
{
    use FormatsMonitoringData;

    /**
     * Mahasiswa milik user token (sama seperti getMahasiswa() di web).
     */
    protected function mahasiswa(Request $request): Mahasiswa
    {
        $user = $request->user();

        if ($user->mahasiswa) {
            return $user->mahasiswa;
        }

        $mhs = Mahasiswa::where('user_id', $user->id_users)
            ->orWhere('email', $user->email)
            ->orWhere('nim', $user->username)
            ->first();

        abort_if(!$mhs, 404, 'Data mahasiswa untuk akun ini tidak ditemukan. Silakan hubungi admin.');

        if (!$user->id_mhs) {
            $user->update(['id_mhs' => $mhs->id_mhs]);
            if (!$mhs->user_id) {
                $mhs->update(['user_id' => $user->id_users]);
            }
        }

        return $mhs;
    }

    protected function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000;
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        return round($earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a)), 2);
    }

    // =========================================================================
    // DASHBOARD & PROFIL
    // =========================================================================

    /** GET /api/peserta/dashboard */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $mhs = $this->mahasiswa($request);
        $today = Carbon::today()->toDateString();

        $evaluations = DailyEvaluation::where('mahasiswa_id', $mhs->id_mhs)
            ->where('status', 'published')
            ->get();
        $latestEvaluation = $evaluations->sortByDesc('evaluation_date')->first();

        $activeLeave = LeaveRequest::where('mahasiswa_id', $mhs->id_mhs)
            ->where('status', 'approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();

        $logbookToday = DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)
            ->where('logbook_date', $today)
            ->first();

        return $this->ok([
            'mahasiswa' => $this->formatMahasiswa($mhs->load(['universitas', 'jurusan'])),
            'attendance_today' => $this->formatAttendance(
                Attendance::where('mahasiswa_id', $mhs->id_mhs)->where('attendance_date', $today)->first()
            ),
            'logbook_today' => $logbookToday ? $this->formatLogbook($logbookToday) : null,
            'active_leave' => $activeLeave ? $this->formatLeave($activeLeave) : null,
            'summary' => [
                'total_hadir' => Attendance::where('mahasiswa_id', $mhs->id_mhs)->whereIn('check_in_status', ['present', 'late'])->count(),
                'total_terlambat' => Attendance::where('mahasiswa_id', $mhs->id_mhs)->where('late_minutes', '>', 0)->count(),
                'total_izin' => LeaveRequest::where('mahasiswa_id', $mhs->id_mhs)->where('status', 'approved')->count(),
                'total_logbook' => DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)->count(),
                'logbook_disetujui' => DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)->where('status', 'approved')->count(),
                'logbook_revisi' => DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)->where('status', 'revision_required')->count(),
                'aduan_aktif' => Complaint::where('mahasiswa_id', $mhs->id_mhs)->whereNotIn('status', ['resolved', 'closed', 'rejected'])->count(),
                'rata_rata_nilai' => $evaluations->count() > 0 ? round($evaluations->avg('total_score'), 1) : 0,
            ],
            'latest_evaluation' => $latestEvaluation
                ? $this->formatEvaluation($latestEvaluation->load(['details.criterion', 'evaluator']))
                : null,
            'notifications' => AppNotification::where('user_id', $user->id_users)
                ->orderBy('created_at', 'desc')->take(5)->get()
                ->map(fn ($n) => $this->formatNotification($n))->values(),
            'unread_notifications' => $user->unreadNotificationsCount(),
            'announcements' => CompanyInformation::where('is_published', true)
                ->where('category', 'announcement')
                ->orderBy('published_at', 'desc')->take(3)->get()
                ->map(fn ($i) => $this->formatInformation($i))->values(),
            'company' => $this->companyConfig(),
        ]);
    }

    /** GET /api/peserta/profil */
    public function profile(Request $request)
    {
        $user = $request->user();
        $mhs = $this->mahasiswa($request)->load(['universitas', 'jurusan']);

        return $this->ok([
            'user' => [
                'id' => $user->id_users,
                'nama_lengkap' => $user->nama_lengkap,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'last_login_at' => static::dateTime($user->last_login_at),
            ],
            'mahasiswa' => $this->formatMahasiswa($mhs),
        ]);
    }

    /** POST /api/peserta/profil/update — field sama dengan form profil web. */
    public function updateProfile(Request $request)
    {
        $mhs = $this->mahasiswa($request);

        $request->validate([
            'no_telepon' => 'required|string|max:20',
            'emergency_contact_name' => 'required|string|max:150',
            'emergency_contact_phone' => 'required|string|max:30',
        ]);

        $mhs->update([
            'no_telepon' => $request->no_telepon,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
        ]);

        return $this->ok(
            $this->formatMahasiswa($mhs->fresh(['universitas', 'jurusan'])),
            'Data profil dan kontak darurat berhasil diperbarui.'
        );
    }

    /** GET /api/peserta/referensi — pilihan dropdown form mobile (sama dengan opsi di web). */
    public function references()
    {
        return $this->ok([
            'leave_types' => static::options(self::LEAVE_TYPES),
            'complaint_categories' => static::options(self::COMPLAINT_CATEGORIES),
            'complaint_priorities' => static::options(self::COMPLAINT_PRIORITIES),
            'complaint_confidentiality' => static::options(self::COMPLAINT_CONFIDENTIALITY),
            'correction_types' => static::options(self::CORRECTION_TYPES),
            'logbook_status' => static::options(self::LOGBOOK_STATUS),
        ]);
    }

    // =========================================================================
    // ABSENSI & KOREKSI
    // =========================================================================

    protected function companyConfig(): array
    {
        $company = $this->company();
        $policy = AttendancePolicy::first();

        return [
            'name' => $company->name,
            'latitude' => (float) $company->latitude,
            'longitude' => (float) $company->longitude,
            'radius_meters' => (int) ($company->attendance_radius_meters ?: 150),
            'work_start_time' => static::time($company->work_start_time ?: '08:00:00'),
            'work_end_time' => static::time($company->work_end_time ?: '16:00:00'),
            'late_tolerance_minutes' => $policy ? (int) $policy->late_tolerance_minutes : 15,
            'require_selfie' => $policy ? (bool) $policy->require_selfie : false,
            'allow_manual_correction' => $policy ? (bool) $policy->allow_manual_correction : true,
            'work_days' => $policy->work_days ?? 'Senin,Selasa,Rabu,Kamis,Jumat',
        ];
    }

    /**
     * Selfie presensi: file multipart dari mobile, atau data URI base64 seperti di web.
     */
    protected function storeSelfie(Request $request, string $prefix): ?string
    {
        if ($request->hasFile('selfie_image')) {
            return $this->storeUpload($request->file('selfie_image'), 'selfie', $prefix);
        }
        if ($request->filled('selfie_image')) {
            return $this->storeBase64Image($request->selfie_image, 'selfie', $prefix);
        }

        return null;
    }

    /** GET /api/peserta/absensi/today */
    public function todayAttendance(Request $request)
    {
        $mhs = $this->mahasiswa($request);
        $today = Carbon::today()->toDateString();
        $attendance = Attendance::where('mahasiswa_id', $mhs->id_mhs)->where('attendance_date', $today)->first();

        return $this->ok([
            'today' => $today,
            'attendance' => $this->formatAttendance($attendance),
            'can_check_in' => !$attendance || !$attendance->check_in_at,
            'can_check_out' => $attendance && $attendance->check_in_at && !$attendance->check_out_at,
            'company' => $this->companyConfig(),
        ]);
    }

    /**
     * POST /api/peserta/absensi/check-in
     * selfie_image boleh berupa file (multipart) atau data URI base64 seperti di web.
     */
    public function checkIn(Request $request)
    {
        $mhs = $this->mahasiswa($request);
        $today = Carbon::today()->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'nullable|numeric',
            'qr_token' => 'nullable|string',
            'notes' => 'nullable|string|max:255',
            'selfie_image' => $request->hasFile('selfie_image') ? 'image|max:5120' : 'nullable|string',
        ]);

        $company = $this->company();
        $radiusMeters = $company->attendance_radius_meters ?: 150;
        $distance = $this->calculateDistance(
            (float) $request->latitude,
            (float) $request->longitude,
            (float) $company->latitude,
            (float) $company->longitude
        );

        // Toleransi GPS sama dengan web: radius + akurasi (maks 100 m)
        $allowedDistance = $radiusMeters + max(0, min(100, (float) ($request->accuracy ?? 20)));
        if ($distance > $allowedDistance) {
            return $this->fail(
                "Lokasi Anda berada di luar area PLTU Adipala. Jarak Anda: {$distance} meter (Radius diizinkan: {$radiusMeters} meter).",
                422,
                ['distance' => $distance, 'allowed_radius' => (int) $radiusMeters]
            );
        }

        $existing = Attendance::where('mahasiswa_id', $mhs->id_mhs)->where('attendance_date', $today)->first();
        if ($existing && $existing->check_in_at) {
            return $this->fail('Anda sudah melakukan check-in untuk hari ini.', 409);
        }

        $policy = AttendancePolicy::first();
        $toleranceMinutes = $policy ? $policy->late_tolerance_minutes : 15;
        $workStartTime = $company->work_start_time ? substr($company->work_start_time, 0, 8) : '08:00:00';
        $standardStartTime = Carbon::parse($today . ' ' . $workStartTime, 'Asia/Jakarta');
        $deadlineTime = $standardStartTime->copy()->addMinutes($toleranceMinutes);

        $isLate = $now->greaterThan($deadlineTime);
        // Carbon 3: selisih bertanda, jadi hitung dari waktu yang lebih awal ke yang lebih akhir
        $lateMinutes = $isLate ? max(0, (int) $standardStartTime->diffInMinutes($now)) : 0;

        $selfiePath = $this->storeSelfie($request, 'selfie_in_' . $mhs->id_mhs);

        $attendance = Attendance::updateOrCreate(
            ['mahasiswa_id' => $mhs->id_mhs, 'attendance_date' => $today],
            [
                'check_in_at' => $now,
                'check_in_status' => $isLate ? 'late' : 'present',
                'work_status' => 'wfo',
                'late_minutes' => $lateMinutes,
                'latitude_in' => $request->latitude,
                'longitude_in' => $request->longitude,
                'distance_in_meters' => $distance,
                'accuracy_in' => $request->accuracy,
                'qr_token' => $request->qr_token,
                'selfie_path' => $selfiePath,
                'verification_method' => 'mobile_api',
                'approval_status' => 'verified',
                'notes' => $request->notes ?: ($isLate ? "Terlambat {$lateMinutes} menit" : 'Check-in tepat waktu'),
            ]
        );

        AppNotification::send(
            $request->user()->id_users,
            'Check-in Berhasil',
            'Presensi masuk Anda tercatat pada pukul ' . $now->format('H:i') . ' WIB.' . ($isLate ? " (Terlambat {$lateMinutes} menit)" : ' (Tepat waktu)'),
            $isLate ? 'warning' : 'success',
            route('peserta.absensi')
        );

        return $this->ok(
            $this->formatAttendance($attendance),
            'Check-in berhasil tercatat pada pukul ' . $now->format('H:i:s') . ' WIB.'
        );
    }

    /** POST /api/peserta/absensi/check-out */
    public function checkOut(Request $request)
    {
        $mhs = $this->mahasiswa($request);
        $today = Carbon::today()->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'selfie_image' => $request->hasFile('selfie_image') ? 'image|max:5120' : 'nullable|string',
        ]);

        $attendance = Attendance::where('mahasiswa_id', $mhs->id_mhs)->where('attendance_date', $today)->first();

        if (!$attendance || !$attendance->check_in_at) {
            return $this->fail('Anda belum melakukan check-in hari ini, tidak dapat melakukan check-out.', 409);
        }
        if ($attendance->check_out_at) {
            return $this->fail('Anda sudah melakukan check-out hari ini.', 409);
        }

        $totalMinutes = max(0, (int) Carbon::parse($attendance->check_in_at)->diffInMinutes($now));

        $distance = null;
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $company = $this->company();
            $distance = min(999999, $this->calculateDistance(
                (float) $request->latitude,
                (float) $request->longitude,
                (float) $company->latitude,
                (float) $company->longitude
            ));
        }

        $selfieOutPath = $this->storeSelfie($request, 'selfie_out_' . $mhs->id_mhs);

        $attendance->update([
            'check_out_at' => $now,
            'check_out_status' => 'present',
            'total_work_minutes' => $totalMinutes,
            'latitude_out' => $request->latitude,
            'longitude_out' => $request->longitude,
            'distance_out_meters' => $distance,
            'selfie_out_path' => $selfieOutPath,
        ]);

        $jam = intdiv($totalMinutes, 60);
        $menit = $totalMinutes % 60;

        AppNotification::send(
            $request->user()->id_users,
            'Check-out Berhasil',
            'Presensi pulang Anda tercatat pada pukul ' . $now->format('H:i') . " WIB. Total jam kerja: {$jam} jam {$menit} menit. Terima kasih atas dedikasi Anda hari ini!",
            'success',
            route('peserta.absensi')
        );

        return $this->ok(
            $this->formatAttendance($attendance->fresh()),
            "Check-out berhasil pada pukul {$now->format('H:i:s')} WIB. Total kerja: {$jam} jam {$menit} menit."
        );
    }

    /** GET /api/peserta/absensi/history?month=&year=&page= */
    public function attendanceHistory(Request $request)
    {
        $mhs = $this->mahasiswa($request);

        $query = Attendance::where('mahasiswa_id', $mhs->id_mhs);
        if ($request->filled('month')) {
            $query->whereMonth('attendance_date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('attendance_date', $request->year);
        }

        return $this->paginated(
            $query->orderBy('attendance_date', 'desc')->paginate($this->perPage($request, 15)),
            fn ($a) => $this->formatAttendance($a)
        );
    }

    /** GET /api/peserta/absensi/koreksi */
    public function corrections(Request $request)
    {
        $mhs = $this->mahasiswa($request);

        return $this->paginated(
            AttendanceCorrection::with('reviewer')
                ->where('mahasiswa_id', $mhs->id_mhs)
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage($request, 15)),
            fn ($c) => $this->formatCorrection($c)
        );
    }

    /** POST /api/peserta/absensi/koreksi — field sama dengan form koreksi di web. */
    public function storeKoreksiAbsensi(Request $request)
    {
        $mhs = $this->mahasiswa($request);

        $request->validate([
            'correction_date' => 'required|date|before_or_equal:today',
            'correction_type' => ['required', 'string', Rule::in(array_keys(self::CORRECTION_TYPES))],
            'requested_check_in_at' => 'nullable|date_format:H:i',
            'requested_check_out_at' => 'nullable|date_format:H:i',
            'reason' => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $attachmentPath = $request->hasFile('attachment')
            ? $this->storeUpload($request->file('attachment'), 'koreksi', 'koreksi_' . $mhs->id_mhs)
            : null;

        $existingAttendance = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $request->correction_date)
            ->first();

        $correction = AttendanceCorrection::create([
            'attendance_id' => $existingAttendance?->id_attendance,
            'mahasiswa_id' => $mhs->id_mhs,
            'correction_date' => $request->correction_date,
            'correction_type' => $request->correction_type,
            'requested_check_in_at' => $request->requested_check_in_at ? Carbon::parse($request->correction_date . ' ' . $request->requested_check_in_at) : null,
            'requested_check_out_at' => $request->requested_check_out_at ? Carbon::parse($request->correction_date . ' ' . $request->requested_check_out_at) : null,
            'reason' => $request->reason,
            'attachment_path' => $attachmentPath,
            'status' => 'submitted',
        ]);

        AppNotification::send(
            $request->user()->id_users,
            'Pengajuan Koreksi Absensi Dikirim',
            "Pengajuan koreksi absensi tanggal {$request->correction_date} telah dikirim dan menunggu tinjauan pembimbing.",
            'info',
            route('peserta.absensi')
        );

        return $this->ok(
            $this->formatCorrection($correction),
            'Pengajuan koreksi absensi berhasil dikirim dan sedang menunggu tinjauan pembimbing.',
            [],
            201
        );
    }

    // =========================================================================
    // LOGBOOK
    // =========================================================================

    /** GET /api/peserta/logbook?status=&search=&page= */
    public function logbooks(Request $request)
    {
        $mhs = $this->mahasiswa($request);
        $query = DailyLogbook::with('reviewer')->where('mahasiswa_id', $mhs->id_mhs);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('activity', 'LIKE', '%' . $request->search . '%');
            });
        }

        return $this->paginated(
            $query->orderBy('logbook_date', 'desc')->paginate($this->perPage($request, 10)),
            fn ($l) => $this->formatLogbook($l)
        );
    }

    /** GET /api/peserta/logbook/{id} */
    public function logbookDetail(Request $request, $id)
    {
        $mhs = $this->mahasiswa($request);
        $logbook = DailyLogbook::with(['comments.user', 'reviewer'])
            ->where('mahasiswa_id', $mhs->id_mhs)
            ->findOrFail($id);

        return $this->ok($this->formatLogbook($logbook, true));
    }

    /**
     * GET /api/peserta/logbook/preset?date=Y-m-d
     * Jam kerja otomatis dari presensi (sama seperti integrasi presensi di form logbook web) + logbook yang sudah ada.
     */
    public function logbookPreset(Request $request)
    {
        $mhs = $this->mahasiswa($request);
        $request->validate(['date' => 'nullable|date']);
        $date = $request->input('date', Carbon::today()->toDateString());

        $attendance = Attendance::where('mahasiswa_id', $mhs->id_mhs)->where('attendance_date', $date)->first();
        $company = $this->company();
        $defaultStart = static::time($company->work_start_time ?: '08:00:00');
        $defaultEnd = static::time($company->work_end_time ?: '16:00:00');
        $existing = DailyLogbook::with('reviewer')->where('mahasiswa_id', $mhs->id_mhs)->where('logbook_date', $date)->first();

        return $this->ok([
            'date' => static::date($date),
            'from_attendance' => (bool) ($attendance && $attendance->check_in_at),
            'attendance' => $this->formatAttendance($attendance),
            'start_time' => $attendance && $attendance->check_in_at ? $attendance->check_in_at->format('H:i') : $defaultStart,
            'end_time' => $attendance && $attendance->check_out_at ? $attendance->check_out_at->format('H:i') : $defaultEnd,
            'existing' => $existing ? $this->formatLogbook($existing) : null,
        ]);
    }

    /** POST /api/peserta/logbook/store — field sama dengan form logbook web. */
    public function storeLogbook(Request $request)
    {
        $mhs = $this->mahasiswa($request);

        $request->validate([
            'logbook_date' => 'required|date|before_or_equal:today',
            'title' => 'required|string|max:200',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'activity' => 'required|string|min:15',
            'result' => 'nullable|string',
            'obstacle' => 'nullable|string',
            'solution' => 'nullable|string',
            'next_plan' => 'nullable|string',
            'action_type' => 'required|in:draft,submit',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
        ]);

        $existing = DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)
            ->where('logbook_date', $request->logbook_date)
            ->first();

        // Web hanya menampilkan tombol edit untuk draft / perlu revisi; API menegakkan aturan yang sama
        if ($existing && !in_array($existing->status, ['draft', 'revision_required'])) {
            return $this->fail(
                'Logbook tanggal ini sudah berstatus "' . (self::LOGBOOK_STATUS[$existing->status] ?? $existing->status) . '" dan tidak dapat diubah lagi.',
                409
            );
        }

        $attendance = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $request->logbook_date)
            ->first();

        $company = $this->company();
        $defaultStart = $company->work_start_time ? substr($company->work_start_time, 0, 8) : '08:00:00';
        $defaultEnd = $company->work_end_time ? substr($company->work_end_time, 0, 8) : '16:00:00';

        if ($attendance && $attendance->check_in_at) {
            $startTime = $attendance->check_in_at->format('H:i:s');
            $endTime = $attendance->check_out_at ? $attendance->check_out_at->format('H:i:s') : ($request->end_time ?: $defaultEnd);
        } else {
            $startTime = $request->start_time ?: $defaultStart;
            $endTime = $request->end_time ?: $defaultEnd;
        }

        $status = $request->action_type === 'submit' ? 'submitted' : 'draft';

        $attachmentPath = $existing?->attachment_path;
        $attachmentName = $existing?->attachment_name;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $this->storeUpload($file, 'logbook', 'logbook_' . $mhs->id_mhs);
        }

        $logbook = DailyLogbook::updateOrCreate(
            ['mahasiswa_id' => $mhs->id_mhs, 'logbook_date' => $request->logbook_date],
            [
                'title' => $request->title,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'activity' => $request->activity,
                'result' => $request->result,
                'obstacle' => $request->obstacle,
                'solution' => $request->solution,
                'next_plan' => $request->next_plan,
                'attachment_path' => $attachmentPath,
                'attachment_name' => $attachmentName,
                'status' => $status,
                'submitted_at' => $status === 'submitted' ? Carbon::now() : null,
            ]
        );

        AppNotification::send(
            $request->user()->id_users,
            $status === 'submitted' ? 'Logbook Berhasil Dikirim' : 'Draft Logbook Disimpan',
            "Logbook tanggal {$request->logbook_date} telah berstatus: " . strtoupper($status),
            'success',
            route('peserta.logbook')
        );

        return $this->ok(
            $this->formatLogbook($logbook->fresh(['reviewer'])),
            $status === 'submitted' ? 'Logbook berhasil dikirim kepada pembimbing untuk ditinjau.' : 'Draft logbook berhasil disimpan.'
        );
    }

    // =========================================================================
    // CUTI / IZIN
    // =========================================================================

    /** GET /api/peserta/cuti */
    public function leaveRequests(Request $request)
    {
        $mhs = $this->mahasiswa($request);

        return $this->paginated(
            LeaveRequest::with('reviewer')
                ->where('mahasiswa_id', $mhs->id_mhs)
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage($request, 10)),
            fn ($l) => $this->formatLeave($l)
        );
    }

    /** POST /api/peserta/cuti/store — field sama dengan form cuti web. */
    public function storeLeaveRequest(Request $request)
    {
        $mhs = $this->mahasiswa($request);

        $request->validate([
            'leave_type' => ['required', 'string', Rule::in(array_keys(self::LEAVE_TYPES))],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
            'emergency_contact' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $attachmentPath = $request->hasFile('attachment')
            ? $this->storeUpload($request->file('attachment'), 'cuti', 'cuti_' . $mhs->id_mhs)
            : null;

        $leave = LeaveRequest::create([
            'mahasiswa_id' => $mhs->id_mhs,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'emergency_contact' => $request->emergency_contact ?: ($mhs->emergency_contact_phone ? "{$mhs->emergency_contact_name} ({$mhs->emergency_contact_phone})" : null),
            'attachment_path' => $attachmentPath,
            'status' => 'submitted',
            'submitted_at' => Carbon::now(),
        ]);

        AppNotification::send(
            $request->user()->id_users,
            'Pengajuan Cuti / Izin Dikirim',
            'Pengajuan ' . LeaveRequest::getLeaveTypeLabel($request->leave_type) . " ({$request->start_date} s/d {$request->end_date}) telah diajukan.",
            'info',
            route('peserta.cuti')
        );

        return $this->ok(
            $this->formatLeave($leave),
            'Pengajuan cuti/izin berhasil diajukan dan sedang menunggu persetujuan pembimbing.',
            [],
            201
        );
    }

    /** POST /api/peserta/cuti/{id}/cancel */
    public function cancelLeaveRequest(Request $request, $id)
    {
        $mhs = $this->mahasiswa($request);
        $leave = LeaveRequest::where('mahasiswa_id', $mhs->id_mhs)->findOrFail($id);

        if ($leave->status !== 'submitted') {
            return $this->fail('Hanya pengajuan dengan status "Menunggu Review" yang dapat dibatalkan.', 409);
        }

        $leave->update(['status' => 'cancelled']);

        return $this->ok($this->formatLeave($leave), 'Pengajuan berhasil dibatalkan.');
    }

    // =========================================================================
    // ADUAN
    // =========================================================================

    /** GET /api/peserta/aduan?category=&status=&page= */
    public function complaints(Request $request)
    {
        $user = $request->user();
        $mhs = $this->mahasiswa($request);
        $query = Complaint::with('responses.user')->where('mahasiswa_id', $mhs->id_mhs);

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return $this->paginated(
            $query->orderBy('created_at', 'desc')->paginate($this->perPage($request, 10)),
            fn ($c) => $this->formatComplaint($c, $user->id_users)
        );
    }

    /** GET /api/peserta/aduan/{id} */
    public function complaintDetail(Request $request, $id)
    {
        $mhs = $this->mahasiswa($request);
        $complaint = Complaint::with('responses.user')->where('mahasiswa_id', $mhs->id_mhs)->findOrFail($id);

        return $this->ok($this->formatComplaint($complaint, $request->user()->id_users));
    }

    /** POST /api/peserta/aduan/store — field sama dengan form aduan web. */
    public function storeComplaint(Request $request)
    {
        $mhs = $this->mahasiswa($request);

        $request->validate([
            'category' => ['required', 'string', Rule::in(array_keys(self::COMPLAINT_CATEGORIES))],
            'title' => 'required|string|max:200',
            'description' => 'required|string|min:20',
            'priority' => 'required|in:low,medium,high,urgent',
            'confidentiality' => 'required|in:visible_to_company,visible_to_campus,visible_to_both,restricted',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
        ]);

        $attachmentPath = $request->hasFile('attachment')
            ? $this->storeUpload($request->file('attachment'), 'aduan', 'aduan_' . $mhs->id_mhs)
            : null;

        $complaintNumber = Complaint::generateComplaintNumber();

        $complaint = Complaint::create([
            'mahasiswa_id' => $mhs->id_mhs,
            'complaint_number' => $complaintNumber,
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'confidentiality' => $request->confidentiality,
            'attachment_path' => $attachmentPath,
            'status' => 'submitted',
            'submitted_at' => Carbon::now(),
        ]);

        AppNotification::send(
            $request->user()->id_users,
            'Aduan Berhasil Dibuat',
            "Aduan nomor {$complaintNumber} berhasil diajukan dan sedang menunggu verifikasi petugas.",
            'info',
            route('peserta.aduan')
        );

        return $this->ok(
            $this->formatComplaint($complaint->load('responses.user'), $request->user()->id_users),
            "Aduan Anda telah berhasil dikirim dengan Nomor Aduan: {$complaintNumber}. Petugas akan segera menindaklanjuti.",
            [],
            201
        );
    }

    /** POST /api/peserta/aduan/{id}/reply */
    public function replyComplaint(Request $request, $id)
    {
        $mhs = $this->mahasiswa($request);
        $complaint = Complaint::where('mahasiswa_id', $mhs->id_mhs)->findOrFail($id);

        $request->validate(['message' => 'required|string|min:3']);

        // Web menyembunyikan kolom balasan untuk aduan selesai / ditutup
        if (in_array($complaint->status, ['closed', 'resolved'])) {
            return $this->fail('Aduan ini sudah selesai atau ditutup dan tidak dapat dibalas lagi.', 409);
        }

        ComplaintResponse::create([
            'complaint_id' => $complaint->id_complaint,
            'user_id' => $request->user()->id_users,
            'message' => $request->message,
            'is_internal_note' => false,
        ]);

        return $this->ok(
            $this->formatComplaint($complaint->load('responses.user'), $request->user()->id_users),
            'Tanggapan berhasil dikirim.'
        );
    }

    // =========================================================================
    // NILAI, INFORMASI, NOTIFIKASI
    // =========================================================================

    /** GET /api/peserta/nilai */
    public function evaluations(Request $request)
    {
        $mhs = $this->mahasiswa($request);
        $evaluations = DailyEvaluation::where('mahasiswa_id', $mhs->id_mhs)
            ->where('status', 'published')
            ->with(['details.criterion', 'evaluator'])
            ->orderBy('evaluation_date', 'desc')
            ->get();

        $hasData = $evaluations->count() > 0;

        return $this->ok(
            $evaluations->map(fn ($e) => $this->formatEvaluation($e))->values(),
            null,
            [
                'average_score' => $hasData ? round($evaluations->avg('total_score'), 1) : 0,
                'highest_score' => $hasData ? (float) $evaluations->max('total_score') : 0,
                'lowest_score' => $hasData ? (float) $evaluations->min('total_score') : 0,
                'total' => $evaluations->count(),
            ]
        );
    }

    /** GET /api/peserta/informasi */
    public function companyInformation(Request $request)
    {
        $mhs = $this->mahasiswa($request);
        $company = $this->company();

        return $this->ok([
            'company' => array_merge($this->companyConfig(), [
                'description' => $company->description,
                'industry' => $company->industry,
                'address' => $company->address,
                'phone' => $company->phone,
                'email' => $company->email,
                'website' => $company->website,
                'logo_url' => static::fileUrl($company->logo_path),
            ]),
            'penempatan' => $this->formatPenempatan($mhs->latestPengajuan()->with('divisi')->first()),
            'informations' => CompanyInformation::where('is_published', true)
                ->orderBy('created_at', 'desc')->get()
                ->map(fn ($i) => $this->formatInformation($i))->values(),
        ]);
    }

    /** GET /api/peserta/notifikasi */
    public function notifications(Request $request)
    {
        $user = $request->user();

        return $this->paginated(
            AppNotification::where('user_id', $user->id_users)
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage($request, 15)),
            fn ($n) => $this->formatNotification($n),
            ['unread_count' => $user->unreadNotificationsCount()]
        );
    }

    /** POST /api/peserta/notifikasi/{id}/read */
    public function markNotificationRead(Request $request, $id)
    {
        $notification = AppNotification::where('user_id', $request->user()->id_users)->findOrFail($id);
        $notification->update(['is_read' => true, 'read_at' => Carbon::now()]);

        return $this->ok($this->formatNotification($notification), 'Notifikasi ditandai telah dibaca.');
    }

    /** POST /api/peserta/notifikasi/read-all */
    public function markAllNotificationsRead(Request $request)
    {
        AppNotification::where('user_id', $request->user()->id_users)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => Carbon::now()]);

        return $this->ok(null, 'Semua notifikasi ditandai telah dibaca.');
    }
}
