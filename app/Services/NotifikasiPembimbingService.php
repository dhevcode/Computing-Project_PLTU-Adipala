<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Notifikasi ke pembimbing lapangan saat peserta bimbingannya mengirim sesuatu yang perlu ditinjau
 * (koreksi absensi, logbook, ketidakhadiran, aduan). Dipakai web (PesertaController) dan API mobile.
 */
class NotifikasiPembimbingService
{
    /**
     * Pembimbing dari mahasiswa — aturan sama dengan PembimbingController::getBimbinganMahasiswaIds():
     * pengajuan_magang.pembimbing_id ATAU nama_pembimbing = nama_lengkap user pembimbing (tanpa beda huruf besar/kecil).
     */
    public static function penerima(Mahasiswa $mhs): Collection
    {
        $pengajuan = PengajuanMagang::where('mahasiswa_id', $mhs->id_mhs)->get(['pembimbing_id', 'nama_pembimbing']);
        $ids = $pengajuan->pluck('pembimbing_id')->filter()->unique()->values();
        $nama = $pengajuan->pluck('nama_pembimbing')
            ->map(fn ($n) => strtolower(trim((string) $n)))
            ->filter()->unique()->values();

        if ($ids->isEmpty() && $nama->isEmpty()) {
            return collect();
        }

        return User::whereRaw('LOWER(TRIM(role)) IN (?, ?)', ['pembimbing', 'pembimbing lapangan'])
            ->where(function ($q) use ($ids, $nama) {
                if ($ids->isNotEmpty()) {
                    $q->whereIn('id_users', $ids);
                }
                foreach ($nama as $n) {
                    $q->orWhereRaw('LOWER(TRIM(nama_lengkap)) = ?', [$n]);
                }
            })
            ->get()
            ->unique('id_users');
    }

    /** Kirim ke semua pembimbing peserta; gagal kirim tidak membatalkan aksi peserta. */
    public static function kirim(?Mahasiswa $mhs, string $judul, string $pesan, string $routeName, string $type = 'info'): void
    {
        if (!$mhs) {
            return;
        }

        try {
            foreach (self::penerima($mhs) as $pembimbing) {
                AppNotification::send($pembimbing->id_users, $judul, $pesan, $type, route($routeName));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /** "Rian Pratama (2311102001)" */
    public static function namaPeserta(Mahasiswa $mhs): string
    {
        return trim($mhs->nama_lengkap) . ($mhs->nim ? " ({$mhs->nim})" : '');
    }
}
