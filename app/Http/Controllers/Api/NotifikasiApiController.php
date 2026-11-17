<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsMonitoringData;
use App\Models\AppNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Notifikasi sistem untuk semua role (Peserta, Pembimbing, Admin, Super Admin).
 * Lonceng di header aplikasi mobile memakai endpoint ini; data sama dengan tabel app_notifications web.
 */
class NotifikasiApiController extends ApiController
{
    use FormatsMonitoringData;

    /** GET /api/v1/notifikasi */
    public function index(Request $request)
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

    /** POST /api/v1/notifikasi/{id}/read */
    public function read(Request $request, $id)
    {
        $notification = AppNotification::where('user_id', $request->user()->id_users)->findOrFail($id);
        $notification->update(['is_read' => true, 'read_at' => Carbon::now()]);

        return $this->ok($this->formatNotification($notification), 'Notifikasi ditandai telah dibaca.');
    }

    /** POST /api/v1/notifikasi/read-all */
    public function readAll(Request $request)
    {
        AppNotification::where('user_id', $request->user()->id_users)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => Carbon::now()]);

        return $this->ok(null, 'Semua notifikasi ditandai telah dibaca.');
    }
}
