<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\AppNotification;

class AdminAduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['mahasiswa.universitas', 'responses.user']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('complaint_number', 'LIKE', "%{$search}%")
                  ->orWhere('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('mahasiswa', function ($m) use ($search) {
                      $m->where('nama_lengkap', 'LIKE', "%{$search}%")
                        ->orWhere('nim', 'LIKE', "%{$search}%");
                  });
            });
        }

        $complaints = $query->orderBy('created_at', 'desc')->paginate(15);

        $pendingCount = Complaint::where('status', 'submitted')->count();
        $inProgressCount = Complaint::where('status', 'in_progress')->count();
        $resolvedCount = Complaint::where('status', 'resolved')->count();

        return view('admin.aduan.index', compact('complaints', 'pendingCount', 'inProgressCount', 'resolvedCount'));
    }

    public function reply(Request $request, $id)
    {
        $complaint = Complaint::with('mahasiswa')->findOrFail($id);

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

        // Kirim notifikasi ke mahasiswa jika bukan internal note
        if (!$request->boolean('is_internal_note') && $complaint->mahasiswa && $complaint->mahasiswa->user_id) {
            AppNotification::send(
                $complaint->mahasiswa->user_id,
                'Aduan Mendapat Tanggapan',
                "Aduan #{$complaint->complaint_number} ({$complaint->title}) telah ditanggapi oleh admin/petugas. Status: " . strtoupper($request->status),
                'info',
                route('peserta.aduan')
            );
        }

        return back()->with('success', 'Tanggapan berhasil dikirim dan status aduan diperbarui.');
    }
}
