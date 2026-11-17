<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Informasi akun portal untuk email surat balasan (masukan PLTU, meet 9 Nov):
 * peserta yang diterima langsung mendapat username & password bersama surat balasan.
 *
 * - Belum punya akun  → akun dibuat (username = NIM, password acak) dan password dikirim.
 * - Sudah punya akun → hanya username yang dikirim; password TIDAK direset agar peserta
 *   yang sudah memakai akunnya tidak terkunci.
 * - Pengajuan belum diterima / ditolak → tidak ada informasi akun.
 */
class AkunPesertaService
{
    public const STATUS_DITERIMA = ['Akan Magang', 'Sedang Magang', 'Selesai Magang'];

    /** @return array{username: string, email: string, password: ?string, baru: bool}|null */
    public static function untukSuratBalasan(PengajuanMagang $pengajuan, ?int $olehUserId): ?array
    {
        $mhs = $pengajuan->mahasiswa;
        if (!$mhs || !in_array($pengajuan->status, self::STATUS_DITERIMA, true)) {
            return null;
        }

        $akun = $mhs->user_id ? User::find($mhs->user_id) : User::where('id_mhs', $mhs->id_mhs)->first();
        if ($akun) {
            return ['username' => $akun->username, 'email' => $akun->email, 'password' => null, 'baru' => false];
        }

        return static::buat($mhs, $pengajuan, $olehUserId);
    }

    /** Buat akun peserta (aturan sama dengan "Buat Akun" super admin), password acak yang mudah diketik. */
    public static function buat(Mahasiswa $mhs, ?PengajuanMagang $pengajuan, ?int $olehUserId): array
    {
        $password = Str::password(10, true, true, false, false);
        $username = $mhs->nim;
        if (User::where('username', $username)->exists()) {
            $username .= '_' . rand(10, 99);
        }
        $email = $mhs->email;
        if (User::where('email', $email)->exists()) {
            $email = 'mhs_' . rand(10, 99) . '_' . $email;
        }

        $user = User::create([
            'nama_lengkap' => $mhs->nama_lengkap,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $mhs->no_telepon,
            'role' => 'Peserta',
            'id_divisi' => $pengajuan?->divisi_id,
            'id_mhs' => $mhs->id_mhs,
            'is_active' => true,
            'created_by' => $olehUserId,
        ]);
        $mhs->update(['user_id' => $user->id_users]);

        return ['username' => $username, 'email' => $email, 'password' => $password, 'baru' => true];
    }
}
