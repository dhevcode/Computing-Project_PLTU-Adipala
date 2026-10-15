<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\Mahasiswa;
use App\Models\AppNotification;

class AdminAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $date = $request->input('date', $today);

        $query = Attendance::with(['mahasiswa.universitas', 'mahasiswa.jurusan']);

        if ($request->filled('date')) {
            $query->where('attendance_date', $request->date);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('check_in_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nim', 'LIKE', "%{$search}%");
            });
        }

        $attendances = $query->orderBy('check_in_at', 'desc')->paginate(15);

        // Pengajuan koreksi yang menunggu review
        $pendingCorrections = AttendanceCorrection::with('mahasiswa')
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->get();

        $allCorrections = AttendanceCorrection::with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Statistik
        $totalHadirHariIni = Attendance::where('attendance_date', $date)->where('check_in_status', 'present')->count();
        $totalTerlambatHariIni = Attendance::where('attendance_date', $date)->where('check_in_status', 'late')->count();
        $totalIzinHariIni = Attendance::where('attendance_date', $date)->whereIn('check_in_status', ['leave', 'sick'])->count();
        $totalMhs = Mahasiswa::count();

        return view('admin.absensi.index', compact(
            'attendances',
            'pendingCorrections',
            'allCorrections',
            'date',
            'totalHadirHariIni',
            'totalTerlambatHariIni',
            'totalIzinHariIni',
            'totalMhs'
        ));
    }

    public function reviewKoreksi(Request $request, $id)
    {
        $correction = AttendanceCorrection::with('mahasiswa')->findOrFail($id);

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

        // Jika disetujui, perbarui data absensi
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
            $attendance->notes = ($attendance->notes ? $attendance->notes . ' | ' : '') . 'Dikoreksi admin: ' . ($request->review_note ?: 'Disetujui');
            $attendance->save();
        }

        // Kirim notifikasi ke mahasiswa
        if ($correction->mahasiswa && $correction->mahasiswa->user_id) {
            AppNotification::send(
                $correction->mahasiswa->user_id,
                'Koreksi Absensi ' . ucfirst($request->status),
                "Pengajuan koreksi tanggal {$correction->correction_date} telah " . ($request->status === 'approved' ? 'disetujui' : 'ditolak') . '. ' . ($request->review_note ? "Catatan: {$request->review_note}" : ''),
                $request->status === 'approved' ? 'success' : 'danger',
                route('peserta.absensi')
            );
        }

        return back()->with('success', 'Status pengajuan koreksi absensi berhasil diperbarui.');
    }
}
