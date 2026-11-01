<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

class PesertaApiController extends Controller
{
    /**
     * Dapatkan instance Mahasiswa dari user yang sedang login via token API
     */
    protected function getMahasiswa(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return null;
        }

        if ($user->mahasiswa) {
            return $user->mahasiswa;
        }

        $mhs = Mahasiswa::where('user_id', $user->id_users)
            ->orWhere('id_mhs', $user->id_mhs)
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
     * Haversine formula untuk menghitung jarak GPS
     */
    protected function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return round($angle * $earthRadius, 2);
    }

    /**
     * 1. GET /api/peserta/dashboard
     */
    public function dashboard(Request $request)
    {
        $mhs = $this->getMahasiswa($request);
        if (!$mhs) {
            return response()->json(['success' => false, 'message' => 'Data mahasiswa tidak ditemukan.'], 404);
        }

        $today = Carbon::today()->toDateString();
        $attendanceToday = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $today)
            ->first();

        $company = Company::first() ?? new Company([
            'name' => 'PT PLN Indonesia Power UBP Adipala',
            'latitude' => -7.680810191412908,
            'longitude' => 109.1366921585062,
            'attendance_radius_meters' => 150,
            'work_start_time' => '07:30:00',
            'work_end_time' => '16:00:00',
        ]);

        $summary = [
            'total_hadir' => Attendance::where('mahasiswa_id', $mhs->id_mhs)->whereNotNull('check_in_at')->count(),
            'total_terlambat' => Attendance::where('mahasiswa_id', $mhs->id_mhs)->where('check_in_status', 'late')->count(),
            'total_izin' => LeaveRequest::where('mahasiswa_id', $mhs->id_mhs)->where('status', 'approved')->count(),
            'total_logbook' => DailyLogbook::where('mahasiswa_id', $mhs->id_mhs)->count(),
        ];

        return response()->json([
            'success' => true,
            'mahasiswa' => $mhs->load(['universitas', 'jurusan', 'latestPengajuan.divisi']),
            'attendance_today' => $attendanceToday,
            'company_config' => [
                'name' => $company->name,
                'latitude' => (float)$company->latitude,
                'longitude' => (float)$company->longitude,
                'radius_meters' => (int)($company->attendance_radius_meters ?: 150),
                'work_start_time' => $company->work_start_time,
                'work_end_time' => $company->work_end_time,
            ],
            'summary' => $summary,
        ]);
    }

    /**
     * 2. GET /api/peserta/absensi/today
     */
    public function todayAttendance(Request $request)
    {
        $mhs = $this->getMahasiswa($request);
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $today)
            ->first();

        $company = Company::first();

        return response()->json([
            'success' => true,
            'today' => $today,
            'attendance' => $attendance,
            'company' => $company,
        ]);
    }

    /**
     * 3. POST /api/peserta/absensi/check-in
     */
    public function checkIn(Request $request)
    {
        $mhs = $this->getMahasiswa($request);
        if (!$mhs) {
            return response()->json(['success' => false, 'message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        $today = Carbon::today()->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'nullable|numeric',
            'notes' => 'nullable|string|max:255',
        ]);

        $company = Company::first() ?? new Company([
            'latitude' => -7.680810191412908,
            'longitude' => 109.1366921585062,
            'attendance_radius_meters' => 150,
        ]);
        $radiusMeters = $company->attendance_radius_meters ?: 150;

        $distance = $this->calculateDistance(
            (float) $request->latitude,
            (float) $request->longitude,
            (float) $company->latitude,
            (float) $company->longitude
        );

        $allowedDistance = $radiusMeters + max(0, min(100, (float)($request->accuracy ?? 20)));
        if ($distance > $allowedDistance && !$request->has('bypass_geofence')) {
            return response()->json([
                'success' => false,
                'message' => "Lokasi Anda berada di luar area kantor. Jarak: {$distance}m (Maksimal: {$radiusMeters}m)",
                'distance' => $distance,
                'allowed_radius' => $radiusMeters,
            ], 422);
        }

        $existing = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $today)
            ->first();

        if ($existing && $existing->check_in_at) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini.',
                'attendance' => $existing,
            ], 400);
        }

        $policy = AttendancePolicy::first();
        $toleranceMinutes = $policy ? $policy->late_tolerance_minutes : 15;
        $workStartTime = ($company && $company->work_start_time) ? substr($company->work_start_time, 0, 8) : '08:00:00';
        $standardStartTime = Carbon::parse($today . ' ' . $workStartTime, 'Asia/Jakarta');
        $deadlineTime = $standardStartTime->copy()->addMinutes($toleranceMinutes);

        $isLate = $now->greaterThan($deadlineTime);
        $lateMinutes = $isLate ? max(0, (int) $now->diffInMinutes($standardStartTime)) : 0;
        $checkInStatus = $isLate ? 'late' : 'present';

        // Simpan foto selfie jika ada
        $selfiePath = null;
        $folder = public_path('uploads/selfie');
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        if ($request->hasFile('selfie_image')) {
            $file = $request->file('selfie_image');
            $fileName = 'selfie_in_' . $mhs->id_mhs . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($folder, $fileName);
            $selfiePath = 'uploads/selfie/' . $fileName;
        } elseif ($request->filled('selfie_image') && str_starts_with($request->selfie_image, 'data:image')) {
            $imageData = $request->selfie_image;
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $imageData = base64_decode($imageData);
            $fileName = 'selfie_in_' . $mhs->id_mhs . '_' . time() . '.png';
            File::put($folder . '/' . $fileName, $imageData);
            $selfiePath = 'uploads/selfie/' . $fileName;
        }

        $attendance = Attendance::updateOrCreate(
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
                'verification_method' => 'mobile_api',
                'approval_status' => 'verified',
                'notes' => $request->notes ?: ($isLate ? "Terlambat {$lateMinutes} menit" : 'Check-in tepat waktu'),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil tercatat.',
            'attendance' => $attendance,
        ]);
    }

    /**
     * 4. POST /api/peserta/absensi/check-out
     */
    public function checkOut(Request $request)
    {
        $mhs = $this->getMahasiswa($request);
        $today = Carbon::today()->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        $attendance = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->where('attendance_date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in_at) {
            return response()->json(['success' => false, 'message' => 'Anda belum melakukan check-in hari ini.'], 400);
        }

        if ($attendance->check_out_at) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan check-out hari ini.'], 400);
        }

        $checkInTime = Carbon::parse($attendance->check_in_at);
        $totalMinutes = max(0, (int) $now->diffInMinutes($checkInTime));

        $selfieOutPath = null;
        $folder = public_path('uploads/selfie');
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        if ($request->hasFile('selfie_image')) {
            $file = $request->file('selfie_image');
            $fileName = 'selfie_out_' . $mhs->id_mhs . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($folder, $fileName);
            $selfieOutPath = 'uploads/selfie/' . $fileName;
        } elseif ($request->filled('selfie_image') && str_starts_with($request->selfie_image, 'data:image')) {
            $imageData = $request->selfie_image;
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $imageData = base64_decode($imageData);
            $fileName = 'selfie_out_' . $mhs->id_mhs . '_' . time() . '.png';
            File::put($folder . '/' . $fileName, $imageData);
            $selfieOutPath = 'uploads/selfie/' . $fileName;
        }

        $attendance->update([
            'check_out_at' => $now,
            'total_work_minutes' => $totalMinutes,
            'latitude_out' => $request->latitude,
            'longitude_out' => $request->longitude,
            'selfie_out_path' => $selfieOutPath ?: $attendance->selfie_out_path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil tercatat.',
            'attendance' => $attendance,
        ]);
    }

    /**
     * 5. GET /api/peserta/absensi/history
     */
    public function attendanceHistory(Request $request)
    {
        $mhs = $this->getMahasiswa($request);
        $perPage = $request->input('per_page', 20);

        $attendances = Attendance::where('mahasiswa_id', $mhs->id_mhs)
            ->orderBy('attendance_date', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $attendances,
        ]);
    }

    /**
     * 6. POST /api/peserta/absensi/koreksi
     */
    public function storeKoreksiAbsensi(Request $request)
    {
        $mhs = $this->getMahasiswa($request);

        $request->validate([
            'attendance_date' => 'required|date',
            'requested_check_in' => 'required',
            'requested_check_out' => 'nullable',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $folder = public_path('uploads/koreksi');
            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }
            $file = $request->file('attachment');
            $fileName = 'koreksi_' . $mhs->id_mhs . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($folder, $fileName);
            $filePath = 'uploads/koreksi/' . $fileName;
        }

        $koreksi = AttendanceCorrection::create([
            'mahasiswa_id' => $mhs->id_mhs,
            'attendance_date' => $request->attendance_date,
            'requested_check_in' => $request->requested_check_in,
            'requested_check_out' => $request->requested_check_out,
            'reason' => $request->reason,
            'proof_document_path' => $filePath,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permohonan koreksi presensi berhasil diajukan.',
            'data' => $koreksi,
        ]);
    }

    /**
     * 7. GET /api/peserta/logbook
     */
    public function logbooks(Request $request)
    {
        $mhs = $this->getMahasiswa($request);
        $logbooks = DailyLogbook::with('comments')
            ->where('mahasiswa_id', $mhs->id_mhs)
            ->orderBy('logbook_date', 'desc')
            ->paginate($request->input('per_page', 15));

        return response()->json(['success' => true, 'data' => $logbooks]);
    }

    /**
     * 8. POST /api/peserta/logbook/store
     */
    public function storeLogbook(Request $request)
    {
        $mhs = $this->getMahasiswa($request);

        $request->validate([
            'logbook_date' => 'required|date',
            'title' => 'required|string|max:200',
            'activity' => 'required|string|min:10',
            'result' => 'nullable|string',
            'obstacle' => 'nullable|string',
            'solution' => 'nullable|string',
            'next_plan' => 'nullable|string',
            'action_type' => 'nullable|in:draft,submit',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $folder = public_path('uploads/logbook');
            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }
            $file = $request->file('attachment');
            $fileName = 'logbook_' . $mhs->id_mhs . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($folder, $fileName);
            $filePath = 'uploads/logbook/' . $fileName;
        }

        $status = ($request->action_type === 'draft') ? 'draft' : 'submitted';

        $logbook = DailyLogbook::updateOrCreate(
            [
                'mahasiswa_id' => $mhs->id_mhs,
                'logbook_date' => $request->logbook_date,
            ],
            [
                'title' => $request->title,
                'activity_detail' => $request->activity,
                'result_summary' => $request->result,
                'obstacles' => $request->obstacle,
                'solutions' => $request->solution,
                'next_plan' => $request->next_plan,
                'status' => $status,
                'documentation_path' => $filePath,
                'submitted_at' => ($status === 'submitted') ? now() : null,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Logbook berhasil disimpan.',
            'data' => $logbook,
        ]);
    }

    /**
     * 9. GET /api/peserta/cuti
     */
    public function leaveRequests(Request $request)
    {
        $mhs = $this->getMahasiswa($request);
        $leaves = LeaveRequest::where('mahasiswa_id', $mhs->id_mhs)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $leaves]);
    }

    /**
     * 10. POST /api/peserta/cuti/store
     */
    public function storeLeaveRequest(Request $request)
    {
        $mhs = $this->getMahasiswa($request);

        $request->validate([
            'type' => 'required|in:izin,sakit,cuti',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $folder = public_path('uploads/cuti');
            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }
            $file = $request->file('attachment');
            $fileName = 'cuti_' . $mhs->id_mhs . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($folder, $fileName);
            $filePath = 'uploads/cuti/' . $fileName;
        }

        $leave = LeaveRequest::create([
            'mahasiswa_id' => $mhs->id_mhs,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'proof_path' => $filePath,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan izin/cuti berhasil diajukan.',
            'data' => $leave,
        ]);
    }

    /**
     * 11. GET /api/peserta/aduan
     */
    public function complaints(Request $request)
    {
        $mhs = $this->getMahasiswa($request);
        $complaints = Complaint::with('responses')
            ->where('mahasiswa_id', $mhs->id_mhs)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $complaints]);
    }

    /**
     * 12. POST /api/peserta/aduan/store
     */
    public function storeComplaint(Request $request)
    {
        $mhs = $this->getMahasiswa($request);

        $request->validate([
            'title' => 'required|string|max:200',
            'category' => 'required|string',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $folder = public_path('uploads/aduan');
            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }
            $file = $request->file('attachment');
            $fileName = 'aduan_' . $mhs->id_mhs . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($folder, $fileName);
            $filePath = 'uploads/aduan/' . $fileName;
        }

        $complaint = Complaint::create([
            'mahasiswa_id' => $mhs->id_mhs,
            'ticket_number' => 'TCK-' . strtoupper(uniqid()),
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->content,
            'proof_path' => $filePath,
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Aduan masalah berhasil dikirim.',
            'data' => $complaint,
        ]);
    }

    /**
     * 13. GET /api/peserta/nilai
     */
    public function evaluations(Request $request)
    {
        $mhs = $this->getMahasiswa($request);
        $evaluations = DailyEvaluation::where('mahasiswa_id', $mhs->id_mhs)
            ->orderBy('evaluation_date', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $evaluations]);
    }

    /**
     * 14. GET /api/peserta/informasi
     */
    public function companyInformation()
    {
        $infos = CompanyInformation::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $infos]);
    }

    /**
     * 15. GET /api/peserta/notifikasi
     */
    public function notifications(Request $request)
    {
        $user = $request->user();
        $notifs = AppNotification::where('user_id', $user->id_users)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $notifs]);
    }

    /**
     * 16. POST /api/peserta/profil/update
     */
    public function updateProfile(Request $request)
    {
        $mhs = $this->getMahasiswa($request);
        $user = $request->user();

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'nama_kontak_darurat' => 'nullable|string|max:100',
            'no_kontak_darurat' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $folder = public_path('uploads/profil');
            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }
            $file = $request->file('foto');
            $fileName = 'profil_' . $mhs->id_mhs . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($folder, $fileName);
            $mhs->foto = 'uploads/profil/' . $fileName;
        }

        if ($request->filled('phone')) {
            $user->phone = $request->phone;
            $user->save();
            $mhs->no_hp = $request->phone;
        }

        if ($request->filled('alamat')) {
            $mhs->alamat = $request->alamat;
        }
        if ($request->filled('nama_kontak_darurat')) {
            $mhs->nama_kontak_darurat = $request->nama_kontak_darurat;
        }
        if ($request->filled('no_kontak_darurat')) {
            $mhs->no_kontak_darurat = $request->no_kontak_darurat;
        }

        $mhs->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'mahasiswa' => $mhs,
            'user' => $user,
        ]);
    }
}

