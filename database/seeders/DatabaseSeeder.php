<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Divisi;
use App\Models\Mahasiswa;
use App\Models\Company;
use App\Models\AttendancePolicy;
use App\Models\EvaluationCriterion;
use App\Models\CompanyInformation;
use App\Models\AppNotification;
use App\Models\Attendance;
use App\Models\DailyLogbook;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Sample Divisi
        $divisi = Divisi::firstOrCreate(
            ['nama_divisi' => 'SDM & Umum']
        );
        Divisi::firstOrCreate(
            ['nama_divisi' => 'Sistem Informasi']
        );

        // 2. Akun Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@pltu.com'],
            [
                'nama_lengkap' => 'Super Admin PLTU',
                'username'     => 'superadmin',
                'password'     => Hash::make('admin123'),
                'role'         => 'Super Admin',
                'is_active'    => true,
            ]
        );

        // 3. Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@pltu.com'],
            [
                'nama_lengkap' => 'Admin Divisi PLTU',
                'username'     => 'admin',
                'password'     => Hash::make('admin123'),
                'id_divisi'    => $divisi->id_divisi,
                'role'         => 'Admin',
                'is_active'    => true,
            ]
        );

        // 4. Data Perusahaan (PLTU Adipala)
        $company = Company::firstOrCreate(
            ['name' => 'PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala'],
            [
                'description' => 'Unit Bisnis Pembangkitan Jawa Tengah 2 Adipala mengoperasikan PLTU berbahan bakar batubara berkapasitas 1 x 660 MW dengan teknologi Supercritical yang andal dan ramah lingkungan.',
                'industry' => 'Ketenagalistrikan & Energi Terbarukan',
                'address' => 'Jl. Laut, Desa Bunton, Kecamatan Adipala, Kabupaten Cilacap, Jawa Tengah 53271',
                'latitude' => -7.680810191412908,
                'longitude' => 109.1366921585062,
                'phone' => '(0282) 555-PLTU',
                'email' => 'sdm.adipala@indonesiapower.co.id',
                'website' => 'https://plnindonesiapower.co.id',
                'work_start_time' => '08:00:00',
                'work_end_time' => '16:00:00',
                'timezone' => 'Asia/Jakarta',
                'attendance_radius_meters' => 150,
                'is_active' => true,
            ]
        );

        // 5. Kebijakan Absensi
        AttendancePolicy::firstOrCreate(
            ['company_id' => $company->id_company],
            [
                'check_in_start' => '06:30:00',
                'check_in_deadline' => '08:15:00',
                'check_out_start' => '16:00:00',
                'check_out_deadline' => '20:00:00',
                'work_days' => 'Senin,Selasa,Rabu,Kamis,Jumat',
                'allowed_radius_meters' => 150,
                'location_accuracy_limit' => 50,
                'late_tolerance_minutes' => 15,
                'attendance_method' => 'qr_geofence',
                'require_selfie' => false,
                'allow_manual_correction' => true,
            ]
        );

        // 6. Kriteria Penilaian Harian
        $criteria = [
            ['criterion_name' => 'Kedisiplinan & Kehadiran', 'description' => 'Ketepatan waktu kehadiran dan kepatuhan pada jadwal kerja', 'weight' => 15],
            ['criterion_name' => 'Kemampuan Teknis', 'description' => 'Pemahaman materi teknis dan implementasi kerja lapangan', 'weight' => 20],
            ['criterion_name' => 'Komunikasi & Etika', 'description' => 'Kemampuan berkomunikasi santun dengan rekan kerja dan atasan', 'weight' => 15],
            ['criterion_name' => 'Kerja Sama Tim', 'description' => 'Kemampuan berkolaborasi dan kontribusi dalam divisi', 'weight' => 15],
            ['criterion_name' => 'Inisiatif & Kreativitas', 'description' => 'Keaktifan bertanya, memecahkan masalah, dan usulan ide', 'weight' => 15],
            ['criterion_name' => 'Tanggung Jawab & K3', 'description' => 'Kepatuhan terhadap SOP keselamatan kerja dan integritas', 'weight' => 20],
        ];

        foreach ($criteria as $item) {
            EvaluationCriterion::firstOrCreate(
                ['criterion_name' => $item['criterion_name']],
                $item
            );
        }

        // 7. Informasi Perusahaan & Pengumuman
        $infos = [
            [
                'title' => 'Tata Tertib Pelaksanaan PKL & Magang PLTU Adipala',
                'category' => 'work_rule',
                'content' => "1. Jam kerja mahasiswa PKL: Senin - Jumat pukul 08:00 WIB s/d 16:00 WIB.\n2. Berpakaian rapi: Kemeja berkerah, celana/rok kain gelap, sepatu tertutup (safety shoes saat di area pembangkit).\n3. Wajib memakai ID Card PKL dan APD (Helm & Rompi Safety) saat berada di area operasional.\n4. Menjaga kerahasiaan data internal PLTU Adipala.",
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'Panduan Keselamatan & Kesehatan Kerja (K3) PLTU Adipala',
                'category' => 'safety',
                'content' => "Patuhi rambu-rambu bahaya listrik tegangan tinggi, area bertekanan, dan area kebisingan. Seluruh peserta magang dilarang mengoperasikan switchgear atau katup boiler tanpa didampingi pembimbing teknis resmi.",
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(4),
            ],
            [
                'title' => 'Daftar Kontak Penting & Darurat UBP Jateng 2 Adipala',
                'category' => 'contact',
                'content' => "• Pembimbing Lapangan: Kang Cihuy (Ext. 204)\n• Helpdesk IT / Sistem Informasi: Ext. 101\n• Pos Keamanan / Security Gate: (0282) 555-111\n• Tim K3 & Tanggap Darurat: (0282) 555-911\n• SDM & Kepegawaian: sdm.adipala@indonesiapower.co.id",
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(3),
            ],
            [
                'title' => 'Pengumuman Pengisian Logbook Harian Terintegrasi',
                'category' => 'announcement',
                'content' => "Seluruh mahasiswa magang diwajibkan mengisi logbook kegiatan harian sebelum pukul 17:00 WIB setiap hari kerja. Logbook yang telah disetujui akan menjadi syarat utama penerbitan sertifikat magang.",
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($infos as $info) {
            CompanyInformation::firstOrCreate(
                ['title' => $info['title']],
                $info
            );
        }

        // 8. Akun Peserta Mahasiswa (Dummy & Existing)
        $this->call(DummyPesertaSeeder::class);

        $mhs = Mahasiswa::where('nim', '2311102324')->first();
        if ($mhs) {
            $userMhs = User::updateOrCreate(
                ['email' => $mhs->email ?: 'mhs1@gmail.com'],
                [
                    'nama_lengkap' => $mhs->nama_lengkap,
                    'username'     => $mhs->nim,
                    'password'     => Hash::make('mhs123'),
                    'role'         => 'Peserta',
                    'id_mhs'       => $mhs->id_mhs,
                    'is_active'    => true,
                ]
            );

            $mhs->update([
                'user_id' => $userMhs->id_users,
                'emergency_contact_name' => 'Bapak Supardi (Orang Tua)',
                'emergency_contact_phone' => '081234567890',
            ]);
        }
    }
}
