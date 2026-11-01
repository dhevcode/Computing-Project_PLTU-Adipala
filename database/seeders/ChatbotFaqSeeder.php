<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatbotFaq;

class ChatbotFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'kode'        => 'FAQ-01',
                'pertanyaan'  => 'Apakah PLTU Adipala menerima mahasiswa magang?',
                'jawaban'     => 'Ya, PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala menerima mahasiswa perguruan tinggi (D3/D4/S1) maupun siswa SMK untuk melaksanakan program Praktik Kerja Lapangan (PKL) / Magang sesuai dengan kuota dan ketersediaan pembimbing pada masing-masing divisi operasional.',
                'kategori'    => 'Umum',
                'role_access' => 'all', // Guest & Student
                'urutan'      => 1,
                'is_active'   => true,
            ],
            [
                'kode'        => 'FAQ-02',
                'pertanyaan'  => 'Apa saja persyaratan untuk mengajukan magang?',
                'jawaban'     => 'Persyaratan utama pengajuan magang di PLTU Adipala: 1) Surat Permohonan / Pengantar resmi bertandatangan dari pihak Kampus atau Sekolah, 2) Proposal Rencana Magang, 3) Curriculum Vitae (CV) terbaru calon peserta, dan 4) Mengisi formulir pendaftaran online secara lengkap melalui portal ini.',
                'kategori'    => 'Pendaftaran',
                'role_access' => 'all', // Guest & Student
                'urutan'      => 2,
                'is_active'   => true,
            ],
            [
                'kode'        => 'FAQ-03',
                'pertanyaan'  => 'Bagaimana prosedur pengajuan magang?',
                'jawaban'     => 'Prosedur pengajuan magang: 1) Klik tombol "Ajukan Magang Baru" pada halaman login portal ini, 2) Lengkapi formulir biodata pribadi, asal kampus/jurusan, dan divisi yang diminati, 3) Unggah berkas Surat Pengantar, Proposal, dan CV, 4) Kirim formulir pendaftaran, 5) Tim Admin PLTU Adipala akan memverifikasi berkas dan menentukan ploting Pembimbing Lapangan.',
                'kategori'    => 'Pendaftaran',
                'role_access' => 'guest', // Khusus Guest
                'urutan'      => 3,
                'is_active'   => true,
            ],
            [
                'kode'        => 'FAQ-04',
                'pertanyaan'  => 'Apakah semua jurusan dapat mengikuti magang?',
                'jawaban'     => 'PLTU Adipala terbuka untuk berbagai disiplin ilmu sesuai dengan kebutuhan divisi, di antaranya: Teknik Mesin, Teknik Elektro, Teknik Kimia, K3 & Lingkungan, Teknik Informatika/Sistem Informasi, Manajemen/SDM, Keuangan/Akuntansi, dan Pengadaan Barang/Jasa.',
                'kategori'    => 'Pendaftaran',
                'role_access' => 'guest', // Khusus Guest
                'urutan'      => 4,
                'is_active'   => true,
            ],
            [
                'kode'        => 'FAQ-05',
                'pertanyaan'  => 'Berapa kuota penerimaan mahasiswa magang?',
                'jawaban'     => 'Kuota penerimaan peserta magang disesuaikan dengan kapasitas ruang kerja dan rasio ketersediaan Pembimbing Lapangan di setiap divisi pada periode yang diajukan agar proses bimbingan dan transfer pengetahuan berjalan efektif dan aman.',
                'kategori'    => 'Pendaftaran',
                'role_access' => 'guest', // Khusus Guest
                'urutan'      => 5,
                'is_active'   => true,
            ],
            [
                'kode'        => 'FAQ-06',
                'pertanyaan'  => 'Berapa lama durasi pelaksanaan magang?',
                'jawaban'     => 'Durasi pelaksanaan magang di PLTU Adipala umumnya berkisar antara 1 bulan hingga 3 bulan (atau disesuaikan dengan ketetapan SKS kurikulum kampus dan kesepakatan bersama dengan pihak perusahaan).',
                'kategori'    => 'Pelaksanaan',
                'role_access' => 'all', // Guest & Student
                'urutan'      => 6,
                'is_active'   => true,
            ],
            [
                'kode'        => 'FAQ-07',
                'pertanyaan'  => 'Bagaimana cara mengetahui status pengajuan magang?',
                'jawaban'     => 'Sebagai peserta yang telah memiliki akun, Anda dapat langsung melihat status pengajuan magang pada Dashboard portal peserta ini. Status yang tampil meliputi: "Menunggu Verifikasi" (berkas sedang ditinjau), "Akan Magang" (diterima dan telah diplot Pembimbing Lapangan), "Sedang Magang" (telah aktif menjalani magang), atau "Ditolak" (disertai alasan penolakan dari admin).',
                'kategori'    => 'Pelaksanaan',
                'role_access' => 'student', // Khusus Student
                'urutan'      => 7,
                'is_active'   => true,
            ],
            [
                'kode'        => 'FAQ-08',
                'pertanyaan'  => 'Kegiatan apa saja yang dilakukan selama magang?',
                'jawaban'     => 'Kegiatan magang meliputi: Induction Safety & K3L pembangkit, observasi dan praktik langsung pada sistem operasional atau pemeliharaan divisi, pengisian presensi check-in/check-out berbasis geofencing, pengisian logbook aktivitas harian di portal, bimbingan berkala dengan Pembimbing Lapangan, serta presentasi laporan akhir magang.',
                'kategori'    => 'Pelaksanaan',
                'role_access' => 'all', // Guest & Student
                'urutan'      => 8,
                'is_active'   => true,
            ],
            [
                'kode'        => 'FAQ-09',
                'pertanyaan'  => 'Apa saja aturan selama menjalani magang?',
                'jawaban'     => 'Aturan utama selama magang di PLTU Adipala: 1) Wajib melakukan presensi kehadiran tepat waktu via portal, 2) Mengisi logbook kegiatan harian secara rutin dan meminta review pembimbing, 3) Mematuhi Golden Rules K3L dan wajib memakai Alat Pelindung Diri (APD) lengkap di area pembangkit, 4) Berpakaian rapi, sopan, serta mengenakan almamater/seragam, 5) Menjaga etika komunikasi dan kerahasiaan data operasional pembangkit.',
                'kategori'    => 'Aturan',
                'role_access' => 'student', // Khusus Student
                'urutan'      => 9,
                'is_active'   => true,
            ],
            [
                'kode'        => 'FAQ-10',
                'pertanyaan'  => 'Bagaimana cara menghubungi admin magang?',
                'jawaban'     => 'Anda dapat menghubungi tim Administrator Magang PLTU Adipala melalui Bagian SDM & Humas PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala (Jl. Laut, Desa Bunton, Kec. Adipala, Kab. Cilacap, Jawa Tengah). Bagi peserta yang telah login, Anda juga dapat menyampaikan pertanyaan atau kendala secara langsung melalui menu "Aduan Masalah" pada portal ini.',
                'kategori'    => 'Kontak',
                'role_access' => 'all', // Guest & Student
                'urutan'      => 10,
                'is_active'   => true,
            ],
        ];

        foreach ($faqs as $item) {
            ChatbotFaq::updateOrCreate(
                ['kode' => $item['kode']],
                $item
            );
        }
    }
}
