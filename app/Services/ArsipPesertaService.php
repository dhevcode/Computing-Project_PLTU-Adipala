<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\DailyEvaluation;
use App\Models\DailyLogbook;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\User;

/**
 * Perlindungan arsip magang (masukan PLTU, meet 9 Nov): peserta yang sedang magang / alumni,
 * atau yang sudah punya riwayat presensi, logbook, maupun nilai, tidak boleh dihapus — cukup diedit
 * atau dibiarkan berstatus Alumni. Hapus hanya untuk pengajuan yang keliru/ganda dan belum berjalan.
 * Dipakai halaman web admin/super admin dan API mobile agar aturannya sama.
 */
class ArsipPesertaService
{
    public static function alasanTidakBolehDihapus(?Mahasiswa $mahasiswa): ?string
    {
        if (!$mahasiswa) {
            return null;
        }
        $berjalan = PengajuanMagang::where('mahasiswa_id', $mahasiswa->id_mhs)
            ->whereIn('status', ['Sedang Magang', 'Selesai Magang'])->exists();
        $adaRiwayat = Attendance::where('mahasiswa_id', $mahasiswa->id_mhs)->exists()
            || DailyLogbook::where('mahasiswa_id', $mahasiswa->id_mhs)->exists()
            || DailyEvaluation::where('mahasiswa_id', $mahasiswa->id_mhs)->exists();

        if ($berjalan || $adaRiwayat) {
            return "Data {$mahasiswa->nama_lengkap} tidak dapat dihapus karena sedang/telah menjalani magang — "
                . 'arsip magang (presensi, logbook, nilai) harus tetap tersimpan. Peserta yang selesai otomatis berstatus Alumni; '
                . 'gunakan Edit bila ada salah input.';
        }

        return null;
    }

    /** Hapus akun portal milik mahasiswa yang datanya dihapus (agar tidak tersisa akun login yatim). */
    public static function hapusAkun(Mahasiswa $mahasiswa): void
    {
        $akun = User::where('id_mhs', $mahasiswa->id_mhs)
            ->when($mahasiswa->user_id, fn ($q) => $q->orWhere('id_users', $mahasiswa->user_id))
            ->get();
        foreach ($akun as $u) {
            if (method_exists($u, 'tokens')) {
                $u->tokens()->delete();
            }
            $u->delete();
        }
    }
}
