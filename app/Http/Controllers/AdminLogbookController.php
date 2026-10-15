<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DailyLogbook;
use App\Models\LogbookComment;
use App\Models\Mahasiswa;
use App\Models\AppNotification;

class AdminLogbookController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyLogbook::with(['mahasiswa.universitas', 'comments.user']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('logbook_date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('activity', 'LIKE', "%{$search}%")
                  ->orWhereHas('mahasiswa', function ($m) use ($search) {
                      $m->where('nama_lengkap', 'LIKE', "%{$search}%")
                        ->orWhere('nim', 'LIKE', "%{$search}%");
                  });
            });
        }

        $logbooks = $query->orderBy('logbook_date', 'desc')->paginate(15);

        $pendingCount = DailyLogbook::where('status', 'submitted')->count();
        $approvedCount = DailyLogbook::where('status', 'approved')->count();
        $revisionCount = DailyLogbook::where('status', 'revision_required')->count();

        return view('admin.logbook.index', compact('logbooks', 'pendingCount', 'approvedCount', 'revisionCount'));
    }

    public function review(Request $request, $id)
    {
        $logbook = DailyLogbook::with('mahasiswa')->findOrFail($id);

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
                "Logbook Anda untuk tanggal {$logbook->logbook_date->format('d M Y')} telah {$statusLabel} oleh pembimbing. " . ($request->review_note ? "Catatan: {$request->review_note}" : ''),
                $notifType,
                route('peserta.logbook')
            );
        }

        return back()->with('success', 'Status logbook berhasil diperbarui.');
    }
}
