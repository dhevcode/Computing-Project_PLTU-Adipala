<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Universitas;
use App\Models\Jurusan;
use App\Models\Divisi;
use App\Models\PengajuanMagang;
use App\Models\Attendance;
use App\Models\DailyLogbook;
use App\Models\AppNotification;

class DummyPesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan Divisi Tersedia
        $divisiSDM = Divisi::firstOrCreate(
            ['nama_divisi' => 'SDM & Umum']
        );
        $divisiSI = Divisi::firstOrCreate(
            ['nama_divisi' => 'Sistem Informasi']
        );

        // 2. Pastikan Universitas & Jurusan Tersedia
        $univ = Universitas::firstOrCreate(
            ['nama_universitas' => 'Telkom University']
        );

        $jurusanIF = Jurusan::firstOrCreate(
            ['nama_jurusan' => 'S1 Informatika']
        );
        $jurusanSI = Jurusan::firstOrCreate(
            ['nama_jurusan' => 'S1 Sistem Informasi']
        );

        // Admin penanggung jawab / creator
        $admin = User::where('role', 'Admin')->first() ?? User::where('role', 'Super Admin')->first();

        // 3. Konfigurasi 2 Akun Dummy Peserta Magang
        $dummyPeserta = [
            [
                'nim'                    => '2311102001',
                'nama_lengkap'           => 'Rian Pratama',
                'jenis_kelamin'          => 'Laki-laki',
                'email'                  => 'rian.pratama@pltu.com',
                'no_telepon'             => '081234567801',
                'emergency_contact_name' => 'Bapak Joko Pratama (Orang Tua)',
                'emergency_contact_phone'=> '081298765001',
                'universitas_id'         => $univ->id_universitas,
                'jurusan_id'             => $jurusanIF->id_jurusan,
                'divisi_id'              => $divisiSI->id_divisi,
                'nama_pembimbing'        => 'Kang Cihuy',
                'logbook_title'          => 'Implementasi Modul Pemantauan Jaringan & Telemetry PLTU',
                'logbook_activity'       => 'Mempelajari arsitektur database operasional monitoring PLTU dan konfigurasi telemetry sensor suhu boiler.',
                'logbook_result'         => 'Data telemetri berhasil terhubung dengan sistem visualisasi dashboard lokal.',
            ],
            [
                'nim'                    => '2311102002',
                'nama_lengkap'           => 'Siti Nurhaliza',
                'jenis_kelamin'          => 'Perempuan',
                'email'                  => 'siti.nurhaliza@pltu.com',
                'no_telepon'             => '081234567802',
                'emergency_contact_name' => 'Ibu Sri Wahyuni (Orang Tua)',
                'emergency_contact_phone'=> '081298765002',
                'universitas_id'         => $univ->id_universitas,
                'jurusan_id'             => $jurusanSI->id_jurusan,
                'divisi_id'              => $divisiSDM->id_divisi,
                'nama_pembimbing'        => 'Budi Santoso, S.T.',
                'logbook_title'          => 'Pengelolaan Berkas Administrasi & Rekapitulasi Presensi Magang',
                'logbook_activity'       => 'Melakukan validasi data logbook harian, rekapitulasi daftar hadir peserta PKL, dan inventarisasi perlengkapan APD.',
                'logbook_result'         => 'Tersusun rekapitulasi presensi mingguan divisi SDM secara rapi dan terverifikasi.',
            ],
        ];

        foreach ($dummyPeserta as $data) {
            // A. Simpan/Update Data Mahasiswa
            $mhs = Mahasiswa::updateOrCreate(
                ['nim' => $data['nim']],
                [
                    'nama_lengkap'            => $data['nama_lengkap'],
                    'jenis_kelamin'           => $data['jenis_kelamin'],
                    'email'                   => $data['email'],
                    'no_telepon'              => $data['no_telepon'],
                    'emergency_contact_name'  => $data['emergency_contact_name'],
                    'emergency_contact_phone' => $data['emergency_contact_phone'],
                    'universitas_id'          => $data['universitas_id'],
                    'jurusan_id'              => $data['jurusan_id'],
                ]
            );

            // B. Simpan/Update Data Akun User (Role: Peserta)
            $userMhs = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'nama_lengkap' => $data['nama_lengkap'],
                    'username'     => $data['nim'],
                    'password'     => Hash::make('mhs123'),
                    'phone'        => $data['no_telepon'],
                    'role'         => 'Peserta',
                    'id_mhs'       => $mhs->id_mhs,
                    'is_active'    => true,
                ]
            );

            // Sinkronkan user_id ke tabel mahasiswa
            $mhs->update(['user_id' => $userMhs->id_users]);

            // C. Pengajuan Magang (Status: Sedang Magang)
            PengajuanMagang::updateOrCreate(
                ['mahasiswa_id' => $mhs->id_mhs],
                [
                    'divisi_id'        => $data['divisi_id'],
                    'nama_pembimbing'  => $data['nama_pembimbing'],
                    'tanggal_mulai'    => Carbon::today()->subMonth()->startOfMonth()->toDateString(),
                    'tanggal_selesai'  => Carbon::today()->addMonths(2)->endOfMonth()->toDateString(),
                    'status'           => 'Sedang Magang',
                    'created_by'       => $admin?->id_users ?? 2,
                ]
            );

            // D. Sample Absensi Kemarin (Hadir Tepat Waktu untuk Riwayat)
            Attendance::updateOrCreate(
                [
                    'mahasiswa_id'    => $mhs->id_mhs,
                    'attendance_date' => Carbon::yesterday()->toDateString(),
                ],
                [
                    'check_in_at'         => Carbon::yesterday()->setHour(7)->setMinute(45)->setSecond(0),
                    'check_out_at'        => Carbon::yesterday()->setHour(16)->setMinute(5)->setSecond(0),
                    'check_in_status'     => 'present',
                    'check_out_status'    => 'present',
                    'work_status'         => 'wfo',
                    'late_minutes'        => 0,
                    'total_work_minutes'  => 495,
                    'latitude_in'         => -7.6808000,
                    'longitude_in'        => 109.1367000,
                    'distance_in_meters'  => 20.0,
                    'accuracy_in'         => 10.0,
                    'verification_method' => 'qr_geofence',
                    'approval_status'     => 'verified',
                    'notes'               => 'Hadir tepat waktu di PLTU Adipala.',
                ]
            );

            // E. Sample Logbook Kemarin (Disetujui Pembimbing)
            DailyLogbook::updateOrCreate(
                [
                    'mahasiswa_id' => $mhs->id_mhs,
                    'logbook_date' => Carbon::yesterday()->toDateString(),
                ],
                [
                    'title'        => $data['logbook_title'],
                    'start_time'   => '08:00:00',
                    'end_time'     => '16:00:00',
                    'activity'     => $data['logbook_activity'],
                    'result'       => $data['logbook_result'],
                    'obstacle'     => 'Tidak ada kendala berarti selama kegiatan.',
                    'solution'     => 'Melakukan koordinasi aktif bersama tim divisi.',
                    'next_plan'    => 'Melanjutkan tindak lanjut kegiatan harian sesuai arahan pembimbing.',
                    'status'       => 'approved',
                    'submitted_at' => Carbon::yesterday()->setHour(16)->setMinute(10),
                    'reviewed_by'  => $admin?->id_users ?? 2,
                    'reviewed_at'  => Carbon::yesterday()->setHour(17)->setMinute(0),
                    'review_note'  => 'Pekerjaan sangat baik dan deskriptif. Pertahankan!',
                ]
            );

            // F. Sample Notifikasi Portal Peserta
            AppNotification::firstOrCreate(
                [
                    'user_id' => $userMhs->id_users,
                    'title'   => 'Presensi Masuk Berhasil',
                ],
                [
                    'message' => 'Check-in hari ini berhasil tercatat pada pukul 07:45 WIB. Selamat menjalankan aktivitas magang!',
                    'type'    => 'success',
                    'is_read' => false,
                ]
            );

            AppNotification::firstOrCreate(
                [
                    'user_id' => $userMhs->id_users,
                    'title'   => 'Logbook Harian Disetujui',
                ],
                [
                    'message' => 'Logbook tanggal ' . Carbon::yesterday()->format('d M Y') . ' telah disetujui oleh pembimbing.',
                    'type'    => 'info',
                    'is_read' => true,
                    'read_at' => Carbon::yesterday()->setHour(17)->setMinute(5),
                ]
            );
        }
    }
}
