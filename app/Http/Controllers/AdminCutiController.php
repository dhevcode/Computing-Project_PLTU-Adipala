<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Models\AppNotification;

class AdminCutiController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['mahasiswa.universitas']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type') && $request->leave_type !== 'all') {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function ($m) use ($search) {
                $m->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nim', 'LIKE', "%{$search}%");
            });
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(15);

        $pendingCount = LeaveRequest::where('status', 'submitted')->count();
        $approvedCount = LeaveRequest::where('status', 'approved')->count();
        $rejectedCount = LeaveRequest::where('status', 'rejected')->count();

        return view('admin.cuti.index', compact('leaves', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function review(Request $request, $id)
    {
        $leave = LeaveRequest::with('mahasiswa')->findOrFail($id);

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

        // Jika disetujui, update absensi untuk setiap tanggal cuti
        if ($request->status === 'approved') {
            $period = CarbonPeriod::create($leave->start_date, $leave->end_date);
            $attendanceStatus = ($leave->leave_type === 'sick_leave') ? 'sick' : 'leave';

            foreach ($period as $date) {
                // Jangan isi hari sabtu / minggu jika bukan hari kerja
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
                        'notes' => LeaveRequest::getLeaveTypeLabel($leave->leave_type) . ': ' . $leave->reason,
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
                "Pengajuan " . LeaveRequest::getLeaveTypeLabel($leave->leave_type) . " ({$leave->start_date->format('d M Y')} - {$leave->end_date->format('d M Y')}) telah {$statusLabel}. " . ($request->review_note ? "Alasan: {$request->review_note}" : ''),
                $notifType,
                route('peserta.cuti')
            );
        }

        return back()->with('success', 'Status pengajuan cuti/izin berhasil diperbarui.');
    }
}
