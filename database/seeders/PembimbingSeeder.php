<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Divisi;
use App\Models\PengajuanMagang;

class PembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisiSI = Divisi::where('nama_divisi', 'LIKE', '%Sistem Informasi%')->first();
        $divisiSDM = Divisi::where('nama_divisi', 'LIKE', '%SDM%')->first();

        // 1. Akun Pembimbing Kang Cihuy
        $pembimbing1 = User::updateOrCreate(
            ['email' => 'pembimbing@pltu.com'],
            [
                'nama_lengkap' => 'Kang Cihuy',
                'username'     => 'cihuy',
                'password'     => Hash::make('pembimbing123'),
                'role'         => 'Pembimbing',
                'id_divisi'    => $divisiSI?->id_divisi,
                'phone'        => '081234567891',
                'is_active'    => true,
            ]
        );

        // 2. Akun Pembimbing Budi Santoso, S.T.
        $pembimbing2 = User::updateOrCreate(
            ['email' => 'budi.santoso@pltu.com'],
            [
                'nama_lengkap' => 'Budi Santoso, S.T.',
                'username'     => 'budisantoso',
                'password'     => Hash::make('pembimbing123'),
                'role'         => 'Pembimbing',
                'id_divisi'    => $divisiSDM?->id_divisi,
                'phone'        => '081234567892',
                'is_active'    => true,
            ]
        );

        // Sinkronisasi pembimbing_id pada pengajuan magang
        PengajuanMagang::where('nama_pembimbing', 'Kang Cihuy')->update([
            'pembimbing_id' => $pembimbing1->id_users,
        ]);

        PengajuanMagang::where('nama_pembimbing', 'Budi Santoso, S.T.')->update([
            'pembimbing_id' => $pembimbing2->id_users,
        ]);
    }
}
