<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Company;
use App\Models\AttendancePolicy;
use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\DailyLogbook;
use App\Models\LogbookComment;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\DailyEvaluation;
use App\Models\LeaveRequest;
use App\Models\CompanyInformation;
use App\Models\AppNotification;

class PesertaController extends Controller
{
    /**
     * Ambil data mahasiswa yang sedang login.
     */
    protected function getMahasiswa()
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        if ($user->mahasiswa) {
            return $user->mahasiswa;
        }

        // Fallback jika id_mhs belum ter-link di users
        $mhs = Mahasiswa::where('user_id', $user->id_users)
            ->orWhere('email', $user->email)
            ->orWhere('nim', $user->username)
            ->first();

        if ($mhs && !$user->id_mhs) {
            $user->update(['id_mhs' => $mhs->id_mhs]);
            if (!$mhs->user_id) {
                $mhs->update(['user_id' => $user->id_users]);
            }
        }

        return $mhs;
    }

    /**
     * Hitung jarak dua koordinat GPS (rumus Haversine) dalam meter.
     */
    protected function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    // =========================================================================
    // 1. DASHBOARD PESERTA (README SECTION 4)
    // =========================================================================
    public function dashboard()
    {
        $user = Auth::user();
        $mhs = $this->getMahasiswa();

        if (!$mhs) {
            return view('peserta.no_profile', compact('user'));
        }

        $today = Carbon::today()->toDateString();
        $company = Company::first() ?? new Company([
            'name' => 'PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala',
            'latitude' => -7.680810191412908,
            'longitude' => 109.1366921585062,
            'attendance_radius_meters' => 150,
            'work_start_time' => '08:00:00',
            'work_end_time' => '16:00:00',
        ]);
        $policy = AttendancePolicy::first();

        // Data Penempatan
        $penempatan = $mhs->latestPengajuan()->with('divisi')->first();

        // Absensi hari ini
        $attendanceToday = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $today)
            ->first();

        // Logbook hari ini
        $logbookToday = DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)
            ->where('logbook_date', $today)
            ->first();

        // Status Cuti Aktif
        $activeLeave = LeaveRequest::where('mahasiswa_id', $mhs->id_mhs)
            ->where('status', 'approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();

        // Statistik
        $totalHadir = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->whereIn('check_in_status', ['present', 'late'])
            ->count();

        $totalTerlambat = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('late_minutes', '>', 0)
            ->count();

        $totalIzinSakit = LeaveRequest::where('mahasiswa_id', $mhs->id_mhs)
            ->where('status', 'approved')
            ->count();

        $totalLogbook = DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)->count();
        $logbookDisetujui = DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)->where('status', 'approved')->count();
        $logbookRevisi = DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)->where('status', 'revision_required')->count();

        $totalAduanAktif = Complaint::where('mahasiswa_id', $mhs->id_mhs)
            ->whereNotIn('status', ['resolved', 'closed', 'rejected'])
            ->count();

        $evaluations = DailyEvaluation::where('mahasiswa_id', $mhs->id_mhs)
            ->where('status', 'published')
            ->get();
        $rataRataNilai = $evaluations->count() > 0 ? round($evaluations->avg('total_score'), 1) : 0;
        $latestEvaluation = $evaluations->sortByDesc('evaluation_date')->first();

        // Notifikasi terbaru
        $notifications = AppNotification::where('user_id', $user->id_users)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Pengumuman terbaru
        $announcements = CompanyInformation::where('is_published', true)
            ->where('category', 'announcement')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('peserta.dashboard', compact(
            'user',
            'mhs',
            'company',
            'policy',
            'penempatan',
            'attendanceToday',
            'logbookToday',
            'activeLeave',
            'totalHadir',
            'totalTerlambat',
            'totalIzinSakit',
            'totalLogbook',
            'logbookDisetujui',
            'logbookRevisi',
            'totalAduanAktif',
            'rataRataNilai',
            'latestEvaluation',
            'notifications',
            'announcements'
        ));
    }

    // =========================================================================
    // 2. FITUR ABSENSI & KOREKSI (README SECTIONS 5 & 6)
    // =========================================================================
    public function absensi(Request $request)
    {
        $user = Auth::user();
        $mhs = $this->getMahasiswa();
        $today = Carbon::today()->toDateString();

        $company = Company::first() ?? new Company([
            'name' => 'PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala',
            'latitude' => -7.680810191412908,
            'longitude' => 109.1366921585062,
            'attendance_radius_meters' => 150,
            'work_start_time' => '08:00:00',
            'work_end_time' => '16:00:00',
        ]);
        $policy = AttendancePolicy::first();

        // Status absensi hari ini
        $attendanceToday = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $today)
            ->first();

        // Riwayat absensi
        $query = Attendance::where('mahasiswa_id', $mhs->id_mhs);
        if ($request->filled('month')) {
            $query->whereMonth('attendance_date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('attendance_date', $request->year);
        }
        $attendances = $query->orderBy('attendance_date', 'desc')->paginate(15);

        // Riwayat koreksi absensi
        $corrections = AttendanceCorrection::where('mahasiswa_id', $mhs->id_mhs)
            ->orderBy('created_at', 'desc')
            ->get();

        // Data penempatan divisi
        $penempatan = $mhs->latestPengajuan()->with('divisi')->first();

        // Token harian
        $dailyQrToken = md5('PLTU-ADIPALA-' . $today . '-' . (config('app.key') ?? 'pltu-salt'));

        return view('peserta.absensi', compact(
            'user',
            'mhs',
            'company',
            'policy',
            'attendanceToday',
            'attendances',
            'corrections',
            'dailyQrToken',
            'penempatan'
        ));
    }

    public function doCheckIn(Request $request)
    {
        $mhs = $this->getMahasiswa();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'nullable|numeric',
            'qr_token' => 'nullable|string',
            'selfie_image' => 'nullable|string',
            'notes' => 'nullable|string|max:255',
        ]);

        $company = Company::first() ?? new Company([
            'latitude' => -7.680810191412908,
            'longitude' => 109.1366921585062,
            'attendance_radius_meters' => 150,
        ]);
        $radiusMeters = $company->attendance_radius_meters ?: 150;

        // Hitung jarak
        $distance = $this->calculateDistance(
            (float) $request->latitude,
            (float) $request->longitude,
            (float) $company->latitude,
            (float) $company->longitude
        );

        // Cek Geofence (Toleransi GPS jika akurasi agak lebar)
        $allowedDistance = $radiusMeters + max(0, min(100, (float)($request->accuracy ?? 20)));
        if ($distance > $allowedDistance && !$request->has('bypass_geofence')) {
            return back()->with('error', "Lokasi Anda berada di luar area PLTU Adipala. Jarak Anda: {$distance} meter (Radius diizinkan: {$radiusMeters} meter).");
        }

        // Cek apakah sudah check-in
        $existing = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $today)
            ->first();

        if ($existing && $existing->check_in_at) {
            return back()->with('error', 'Anda sudah melakukan check-in untuk hari ini.');
        }

        // Cek Keterlambatan (Batas jam kerja sesuai work_start_time + toleransi)
        $policy = AttendancePolicy::first();
        $toleranceMinutes = $policy ? $policy->late_tolerance_minutes : 15;
        $workStartTime = ($company && $company->work_start_time) ? substr($company->work_start_time, 0, 8) : '08:00:00';
        $standardStartTime = Carbon::parse($today . ' ' . $workStartTime, 'Asia/Jakarta');
        $deadlineTime = $standardStartTime->copy()->addMinutes($toleranceMinutes);

        $isLate = $now->greaterThan($deadlineTime);
        $lateMinutes = $isLate ? max(0, (int) $now->diffInMinutes($standardStartTime)) : 0;
        $checkInStatus = $isLate ? 'late' : 'present';

        // Simpan selfie wajib presensi
        $selfiePath = null;
        if ($request->filled('selfie_image') && str_starts_with($request->selfie_image, 'data:image')) {
            $imageData = $request->selfie_image;
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $imageData = base64_decode($imageData);

            $folder = public_path('uploads/selfie');
            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }

            $fileName = 'selfie_in_' . $mhs->id_mhs . '_' . time() . '.png';
            File::put($folder . '/' . $fileName, $imageData);
            $selfiePath = 'uploads/selfie/' . $fileName;
        }

        Attendance::updateOrCreate(
            [
                'mahasiswa_id' => $mhs->id_mhs,
                'attendance_date' => $today,
            ],
            [
                'check_in_at' => $now,
                'check_in_status' => $checkInStatus,
                'work_status' => 'wfo',
                'late_minutes' => $lateMinutes,
                'latitude_in' => $request->latitude,
                'longitude_in' => $request->longitude,
                'distance_in_meters' => $distance,
                'accuracy_in' => $request->accuracy,
                'qr_token' => $request->qr_token,
                'selfie_path' => $selfiePath,
                'verification_method' => 'selfie_geofence',
                'approval_status' => 'verified',
                'notes' => $request->notes ?: ($isLate ? "Terlambat {$lateMinutes} menit" : 'Check-in tepat waktu'),
            ]
        );

        // Notifikasi sistem
        AppNotification::send(
            Auth::id(),
            'Check-in Berhasil',
            "Presensi masuk Anda tercatat pada pukul " . $now->format('H:i') . " WIB." . ($isLate ? " (Terlambat {$lateMinutes} menit)" : " (Tepat waktu)"),
            $isLate ? 'warning' : 'success',
            route('peserta.absensi')
        );

        return back()->with('success', 'Check-in berhasil tercatat pada pukul ' . $now->format('H:i:s') . ' WIB.');
    }

    public function doCheckOut(Request $request)
    {
        $mhs = $this->getMahasiswa();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        $attendance = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in_at) {
            return back()->with('error', 'Anda belum melakukan check-in hari ini, tidak dapat melakukan check-out.');
        }

        if ($attendance->check_out_at) {
            return back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        $checkInTime = Carbon::parse($attendance->check_in_at);
        $totalMinutes = max(0, (int) $now->diffInMinutes($checkInTime));

        $distance = null;
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $company = Company::first() ?? new Company([
                'latitude' => -7.680810191412908,
                'longitude' => 109.1366921585062,
            ]);
            $distance = $this->calculateDistance(
                (float) $request->latitude,
                (float) $request->longitude,
                (float) $company->latitude,
                (float) $company->longitude
            );
        }

        $selfieOutPath = null;
        if ($request->filled('selfie_image') && str_starts_with($request->selfie_image, 'data:image')) {
            $imageData = $request->selfie_image;
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $imageData = base64_decode($imageData);

            $folder = public_path('uploads/selfie');
            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }

            $fileName = 'selfie_out_' . $mhs->id_mhs . '_' . time() . '.png';
            File::put($folder . '/' . $fileName, $imageData);
            $selfieOutPath = 'uploads/selfie/' . $fileName;
        }

        $attendance->update([
            'check_out_at' => $now,
            'check_out_status' => 'present',
            'total_work_minutes' => $totalMinutes,
            'latitude_out' => $request->latitude,
            'longitude_out' => $request->longitude,
            'distance_out_meters' => $distance,
            'selfie_out_path' => $selfieOutPath,
        ]);

        $jam = floor($totalMinutes / 60);
        $menit = $totalMinutes % 60;

        AppNotification::send(
            Auth::id(),
            'Check-out Berhasil',
            "Presensi pulang Anda tercatat pada pukul " . $now->format('H:i') . " WIB. Total jam kerja: {$jam} jam {$menit} menit. Terima kasih atas dedikasi Anda hari ini!",
            'success',
            route('peserta.absensi')
        );

        return back()->with('success', "Check-out berhasil pada pukul {$now->format('H:i:s')} WIB. Total kerja: {$jam} jam {$menit} menit.");
    }

    public function storeKoreksiAbsensi(Request $request)
    {
        $mhs = $this->getMahasiswa();

        $request->validate([
            'correction_date' => 'required|date|before_or_equal:today',
            'correction_type' => 'required|string',
            'requested_check_in_at' => 'nullable|date_format:H:i',
            'requested_check_out_at' => 'nullable|date_format:H:i',
            'reason' => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = 'koreksi_' . $mhs->id_mhs . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/koreksi'), $fileName);
            $attachmentPath = 'uploads/koreksi/' . $fileName;
        }

        $checkInDateTime = $request->requested_check_in_at ? Carbon::parse($request->correction_date . ' ' . $request->requested_check_in_at) : null;
        $checkOutDateTime = $request->requested_check_out_at ? Carbon::parse($request->correction_date . ' ' . $request->requested_check_out_at) : null;

        $existingAttendance = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $request->correction_date)
            ->first();

        AttendanceCorrection::create([
            'attendance_id' => $existingAttendance ? $existingAttendance->id_attendance : null,
            'mahasiswa_id' => $mhs->id_mhs,
            'correction_date' => $request->correction_date,
            'correction_type' => $request->correction_type,
            'requested_check_in_at' => $checkInDateTime,
            'requested_check_out_at' => $checkOutDateTime,
            'reason' => $request->reason,
            'attachment_path' => $attachmentPath,
            'status' => 'submitted',
        ]);

        AppNotification::send(
            Auth::id(),
            'Pengajuan Koreksi Absensi Dikirim',
            "Pengajuan koreksi absensi tanggal {$request->correction_date} telah dikirim dan menunggu tinjauan pembimbing.",
            'info',
            route('peserta.absensi')
        );

        return back()->with('success', 'Pengajuan koreksi absensi berhasil dikirim dan sedang menunggu tinjauan pembimbing.');
    }

    // =========================================================================
    // 3. FITUR LOGBOOK HARIAN (README SECTION 7)
    // =========================================================================
    public function logbook(Request $request)
    {
        $user = Auth::user();
        $mhs = $this->getMahasiswa();
        $today = Carbon::today()->toDateString();

        $query = DailyLogbook::where('mahasiswa_id', $mhs->id_mhs);

        // Filter tab status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('activity', 'LIKE', '%' . $request->search . '%');
            });
        }

        $logbooks = $query->orderBy('logbook_date', 'desc')->paginate(10)->withQueryString();

        $todayLogbook = DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)
            ->where('logbook_date', $today)
            ->first();

        // Ambil riwayat presensi mahasiswa untuk integrasi otomatis logbook
        $attendances = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->orderBy('attendance_date', 'desc')
            ->get();

        $attendanceDates = $attendances->mapWithKeys(function ($item) {
            $dateStr = $item->attendance_date->format('Y-m-d');
            return [$dateStr => [
                'date' => $dateStr,
                'date_formatted' => $item->attendance_date->translatedFormat('l, d F Y'),
                'check_in' => $item->check_in_at ? $item->check_in_at->format('H:i') : null,
                'check_out' => $item->check_out_at ? $item->check_out_at->format('H:i') : null,
                'status' => $item->check_in_status,
                'status_label' => match($item->check_in_status) {
                    'present' => 'Hadir Tepat',
                    'late' => 'Terlambat',
                    'leave' => 'Cuti / Izin',
                    'sick' => 'Sakit',
                    default => 'Hadir'
                },
                'notes' => $item->notes,
            ]];
        });

        $company = Company::first();
        $defaultStartTime = ($company && $company->work_start_time) ? substr($company->work_start_time, 0, 5) : '08:00';
        $defaultEndTime = ($company && $company->work_end_time) ? substr($company->work_end_time, 0, 5) : '16:00';

        return view('peserta.logbook', compact(
            'user',
            'mhs',
            'logbooks',
            'todayLogbook',
            'attendances',
            'attendanceDates',
            'defaultStartTime',
            'defaultEndTime'
        ));
    }

    public function storeLogbook(Request $request)
    {
        $mhs = $this->getMahasiswa();

        $request->validate([
            'logbook_date' => 'required|date',
            'title' => 'required|string|max:200',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'activity' => 'required|string|min:15',
            'result' => 'nullable|string',
            'obstacle' => 'nullable|string',
            'solution' => 'nullable|string',
            'next_plan' => 'nullable|string',
            'action_type' => 'required|in:draft,submit',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
        ]);

        // Integrasi otomatis jam kerja dari presensi pada tanggal tersebut
        $attendance = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $request->logbook_date)
            ->first();

        $company = Company::first();
        $defaultStart = ($company && $company->work_start_time) ? substr($company->work_start_time, 0, 8) : '08:00:00';
        $defaultEnd = ($company && $company->work_end_time) ? substr($company->work_end_time, 0, 8) : '16:00:00';

        if ($attendance && $attendance->check_in_at) {
            $startTime = $attendance->check_in_at->format('H:i:s');
            $endTime = $attendance->check_out_at ? $attendance->check_out_at->format('H:i:s') : ($request->end_time ?: $defaultEnd);
        } else {
            $startTime = $request->start_time ?: $defaultStart;
            $endTime = $request->end_time ?: $defaultEnd;
        }

        $status = ($request->action_type === 'submit') ? 'submitted' : 'draft';

        $attachmentPath = null;
        $attachmentName = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $fileName = 'logbook_' . $mhs->id_mhs . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logbook'), $fileName);
            $attachmentPath = 'uploads/logbook/' . $fileName;
        }

        $logbook = DailyLogbook::updateOrCreate(
            [
                'mahasiswa_id' => $mhs->id_mhs,
                'logbook_date' => $request->logbook_date,
            ],
            [
                'title' => $request->title,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'activity' => $request->activity,
                'result' => $request->result,
                'obstacle' => $request->obstacle,
                'solution' => $request->solution,
                'next_plan' => $request->next_plan,
                'attachment_path' => $attachmentPath ?: $request->existing_attachment,
                'attachment_name' => $attachmentName ?: $request->existing_attachment_name,
                'status' => $status,
                'submitted_at' => ($status === 'submitted') ? Carbon::now() : null,
            ]
        );

        $msg = ($status === 'submitted')
            ? 'Logbook berhasil dikirim kepada pembimbing untuk ditinjau.'
            : 'Draft logbook berhasil disimpan.';

        AppNotification::send(
            Auth::id(),
            ($status === 'submitted') ? 'Logbook Berhasil Dikirim' : 'Draft Logbook Disimpan',
            "Logbook tanggal {$request->logbook_date} telah berstatus: " . strtoupper($status),
            'success',
            route('peserta.logbook')
        );

        return back()->with('success', $msg);
    }

    public function detailLogbook($id)
    {
        $mhs = $this->getMahasiswa();
        $logbook = DailyLogbook::with(['comments.user', 'reviewer'])
            ->where('mahasiswa_id', $mhs->id_mhs)
            ->findOrFail($id);

        return response()->json($logbook);
    }

    // =========================================================================
    // 4. FITUR ADUAN MASALAH (README SECTION 8)
    // =========================================================================
    public function aduan(Request $request)
    {
        $user = Auth::user();
        $mhs = $this->getMahasiswa();

        $query = Complaint::where('mahasiswa_id', $mhs->id_mhs);

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $complaints = $query->with('responses.user')->orderBy('created_at', 'desc')->paginate(10);

        return view('peserta.aduan', compact('user', 'mhs', 'complaints'));
    }

    public function storeAduan(Request $request)
    {
        $mhs = $this->getMahasiswa();

        $request->validate([
            'category' => 'required|string',
            'title' => 'required|string|max:200',
            'description' => 'required|string|min:20',
            'priority' => 'required|in:low,medium,high,urgent',
            'confidentiality' => 'required|in:visible_to_company,visible_to_campus,visible_to_both,restricted',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = 'aduan_' . $mhs->id_mhs . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/aduan'), $fileName);
            $attachmentPath = 'uploads/aduan/' . $fileName;
        }

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
            Auth::id(),
            'Aduan Berhasil Dibuat',
            "Aduan nomor {$complaintNumber} berhasil diajukan dan sedang menunggu verifikasi petugas.",
            'info',
            route('peserta.aduan')
        );

        return back()->with('success', "Aduan Anda telah berhasil dikirim dengan Nomor Aduan: {$complaintNumber}. Petugas akan segera menindaklanjuti.");
    }

    public function replyAduan(Request $request, $id)
    {
        $mhs = $this->getMahasiswa();
        $complaint = Complaint::where('mahasiswa_id', $mhs->id_mhs)->findOrFail($id);

        $request->validate([
            'message' => 'required|string|min:3',
        ]);

        ComplaintResponse::create([
            'complaint_id' => $complaint->id_complaint,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_internal_note' => false,
        ]);

        return back()->with('success', 'Tanggapan berhasil dikirim.');
    }

    // =========================================================================
    // 5. FITUR ARSIP NILAI HARIAN (README SECTION 9)
    // =========================================================================
    public function nilai()
    {
        $user = Auth::user();
        $mhs = $this->getMahasiswa();

        $evaluations = DailyEvaluation::where('mahasiswa_id', $mhs->id_mhs)
            ->where('status', 'published')
            ->with(['details.criterion', 'evaluator'])
            ->orderBy('evaluation_date', 'desc')
            ->get();

        $averageScore = $evaluations->count() > 0 ? round($evaluations->avg('total_score'), 1) : 0;
        $highestScore = $evaluations->count() > 0 ? $evaluations->max('total_score') : 0;
        $lowestScore = $evaluations->count() > 0 ? $evaluations->min('total_score') : 0;

        return view('peserta.nilai', compact('user', 'mhs', 'evaluations', 'averageScore', 'highestScore', 'lowestScore'));
    }

    // =========================================================================
    // 6. FITUR PENGAJUAN CUTI DAN IZIN (README SECTION 10)
    // =========================================================================
    public function cuti()
    {
        $user = Auth::user();
        $mhs = $this->getMahasiswa();

        $leaves = LeaveRequest::where('mahasiswa_id', $mhs->id_mhs)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peserta.cuti', compact('user', 'mhs', 'leaves'));
    }

    public function storeCuti(Request $request)
    {
        $mhs = $this->getMahasiswa();

        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
            'emergency_contact' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = 'cuti_' . $mhs->id_mhs . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cuti'), $fileName);
            $attachmentPath = 'uploads/cuti/' . $fileName;
        }

        LeaveRequest::create([
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
            Auth::id(),
            'Pengajuan Cuti / Izin Dikirim',
            "Pengajuan " . LeaveRequest::getLeaveTypeLabel($request->leave_type) . " ({$request->start_date} s/d {$request->end_date}) telah diajukan.",
            'info',
            route('peserta.cuti')
        );

        return back()->with('success', 'Pengajuan cuti/izin berhasil diajukan dan sedang menunggu persetujuan pembimbing.');
    }

    public function cancelCuti($id)
    {
        $mhs = $this->getMahasiswa();
        $leave = LeaveRequest::where('mahasiswa_id', $mhs->id_mhs)->findOrFail($id);

        if ($leave->status !== 'submitted') {
            return back()->with('error', 'Hanya pengajuan dengan status "Menunggu Review" yang dapat dibatalkan.');
        }

        $leave->update(['status' => 'cancelled']);

        return back()->with('success', 'Pengajuan berhasil dibatalkan.');
    }

    // =========================================================================
    // 7. FITUR INFORMASI PERUSAHAAN (README SECTION 11)
    // =========================================================================
    public function informasi()
    {
        $user = Auth::user();
        $mhs = $this->getMahasiswa();
        $company = Company::first() ?? new Company([
            'name' => 'PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala',
            'description' => 'Unit Bisnis Pembangkitan Jawa Tengah 2 Adipala mengoperasikan PLTU berbahan bakar batubara berkapasitas 1 x 660 MW dengan teknologi Supercritical yang andal dan ramah lingkungan.',
            'industry' => 'Ketenagalistrikan & Energi Terbarukan',
            'address' => 'Jl. Laut, Desa Bunton, Kecamatan Adipala, Kabupaten Cilacap, Jawa Tengah 53271',
            'latitude' => -7.680810191412908,
            'longitude' => 109.1366921585062,
            'phone' => '(0282) 555-PLTU',
            'email' => 'sdm.adipala@indonesiapower.co.id',
            'website' => 'https://plnindonesiapower.co.id',
            'work_start_time' => '08:00:00',
            'work_end_time' => '16:00:00',
            'attendance_radius_meters' => 150,
        ]);
        $policy = AttendancePolicy::first();
        $penempatan = $mhs->latestPengajuan()->with('divisi')->first();

        $informations = CompanyInformation::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('peserta.informasi', compact('user', 'mhs', 'company', 'policy', 'penempatan', 'informations'));
    }

    // =========================================================================
    // 8. FITUR NOTIFIKASI (README SECTION 12)
    // =========================================================================
    public function notifikasi()
    {
        $user = Auth::user();
        $mhs = $this->getMahasiswa();

        $notifications = AppNotification::where('user_id', $user->id_users)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('peserta.notifikasi', compact('user', 'mhs', 'notifications'));
    }

    public function markNotifRead($id)
    {
        $notification = AppNotification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back()->with('success', 'Notifikasi ditandai telah dibaca.');
    }

    public function markAllNotifRead()
    {
        AppNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);

        return back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }

    // =========================================================================
    // 9. FITUR PROFIL PESERTA (README SECTION 3 & 4)
    // =========================================================================
    public function profil()
    {
        $user = Auth::user();
        $mhs = $this->getMahasiswa();
        $penempatan = $mhs ? $mhs->latestPengajuan()->with(['divisi'])->first() : null;

        return view('peserta.profil', compact('user', 'mhs', 'penempatan'));
    }

    public function updateProfil(Request $request)
    {
        $mhs = $this->getMahasiswa();

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

        return back()->with('success', 'Data profil dan kontak darurat berhasil diperbarui.');
    }
}
