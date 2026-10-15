# Sistem Monitoring Magang & PKL - PLTU Adipala

Sistem informasi berbasis web untuk memantau, mencatat, dan mengevaluasi kegiatan Praktik Kerja Lapangan (PKL) / Magang mahasiswa di **PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala**. Sistem ini mencakup portal mandiri bagi peserta (presensi berbasis geofencing & selfie, logbook harian, cuti/izin, aduan, nilai, informasi K3) serta portal manajemen bagi Admin dan Pembimbing Lapangan.

---

## 🛠️ Tech Stack & Kebutuhan Environment

Sebelum menjalankan aplikasi, setiap anggota kelompok wajib memastikan perangkat lokal telah terpasang perangkat lunak pendukung berikut:

### 1. Spesifikasi Software Minimal
| Komponen | Versi Minimal | Keterangan |
|---|---|---|
| **PHP** | `^8.2` (Disarankan PHP 8.2 atau 8.3) | Pastikan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`, `curl`, `xml` aktif |
| **Web Server & DB** | **XAMPP** (Apache & MySQL/MariaDB) | Alternatif: Laragon atau Docker |
| **Dependency Manager**| **Composer** `v2.x` | Untuk mengelola package PHP / Laravel |
| **Node.js & NPM** | **Node.js** `v18.x` / `v20.x` LTS & NPM `v9+` | Untuk bundling frontend (Vite & Tailwind CSS) |
| **VCS** | **Git** | Untuk clone repository dan kolaborasi cabang |
| **Browser** | Chrome / Edge / Firefox (Versi Terbaru) | Memerlukan izin akses kamera (selfie) & lokasi (geolocation) |

---

## 🚀 Panduan Instalasi & Setup Lokal

Ikuti langkah-langkah berikut secara berurutan saat melakukan *fresh clone* di komputer masing-masing:

### Langkah 1: Clone Repository
Buka terminal (Git Bash / PowerShell) di folder web server Anda (contoh `C:\xampp\htdocs\`):
```bash
cd c:\xampp\htdocs
git clone https://github.com/dhevcode/Computing-Project_PLTU-Adipala.git
cd Computing-Project_PLTU-Adipala
```

### Langkah 2: Install Dependensi Backend (PHP / Composer)
```bash
composer install
```

### Langkah 3: Install Dependensi Frontend (Node.js / NPM)
```bash
npm install
```

### Langkah 4: Konfigurasi File Environment (`.env`)
Duplikasi file `.env.example` menjadi `.env`:
```bash
# Di Windows PowerShell:
copy .env.example .env

# Atau di Git Bash / Command Prompt:
cp .env.example .env
```

Buka file `.env` di text editor (VS Code / Cursor), lalu sesuaikan konfigurasi database:
```env
APP_NAME="Monitoring Magang PLTU Adipala"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=monitoring_magang
DB_USERNAME=root
DB_PASSWORD=
```

> [!NOTE]
> **Penting Mengenai Port MySQL (`DB_PORT`):**
> - Standar port MySQL XAMPP adalah `3306`.
> - Jika di laptop Anda port MySQL XAMPP diubah ke `3307` (misal karena bentrok dengan MySQL bawaan Windows/MariaDB), ubah nilai `DB_PORT=3307`.

### Langkah 5: Generate Application Key
```bash
php artisan key:generate
```

### Langkah 6: Siapkan Database
1. Buka XAMPP Control Panel, lalu nyalakan modul **Apache** dan **MySQL**.
2. Akses `http://localhost/phpmyadmin` di browser.
3. Buat database baru bernama: `monitoring_magang` dengan collation `utf8mb4_general_ci`.

### Langkah 7: Jalankan Migrasi & Seeder Data Awal
Eksekusi perintah berikut untuk membangun seluruh skema tabel beserta data dummy awal (admin, peserta, kebijakan presensi, kriteria penilaian, dan pengumuman):
```bash
php artisan migrate --seed
```
*(Opsional: Jika ingin melakukan reset ulang basis data dari nol beserta seedernya, gunakan `php artisan migrate:fresh --seed`)*.

### Langkah 8: Buat Symbolic Link Storage (Jika Diperlukan)
Untuk memastikan file unggahan (laporan, bukti cuti, avatar) dapat diakses dengan benar:
```bash
php artisan storage:link
```

### Langkah 9: Jalankan Development Server
Aplikasi membutuhkan server PHP dan asset bundler Vite yang berjalan bersamaan. Buka **dua terminal terpisah**:

**Terminal 1 (Backend Server):**
```bash
php artisan serve
```
*(Aplikasi akan berjalan di `http://127.0.0.1:8000`)*

**Terminal 2 (Frontend Watcher / Vite):**
```bash
npm run dev
```

---

## 🔑 Akun Pengujian Default (Hasil Seeder)

Database seeder telah menyiapkan beberapa akun dengan role berbeda untuk pengujian alur kerja sistem:

| Role | Username / Identifier | Email | Password | Hak Akses Utama |
|---|---|---|---|---|
| **Super Admin** | `superadmin` | `superadmin@pltu.com` | `admin123` | Kelola master akun user, monitoring menyeluruh, profil sistem |
| **Admin Divisi / Pembimbing** | `admin` | `admin@pltu.com` | `admin123` | Approval logbook, review absensi & koreksi, kelola cuti, aduan, nilai harian, info perusahaan, verifikasi ID card & sertifikat |
| **Peserta Magang 1** | `2311102001` (NIM) | `rian.pratama@pltu.com` | `mhs123` | Divisi Sistem Informasi, presensi GPS, pengisian logbook, ajukan izin/cuti/aduan |
| **Peserta Magang 2** | `2311102002` (NIM) | `siti.nurhaliza@pltu.com` | `mhs123` | Divisi SDM & Umum, presensi GPS, pengisian logbook, ajukan izin/cuti/aduan |
| **Peserta Magang Telkom** | `2311102324` (NIM) | *(Sesuai data mahasiswa)* | `mhs123` | Data mahasiswa Telkom University terintegrasi |

---

## 📌 Update Fitur yang Sudah Dilakukan

Berikut adalah daftar modul dan fitur komprehensif yang telah diimplementasikan ke dalam sistem:

### 1. Portal Mandiri Peserta Magang (`/peserta/*`)
* **Dashboard Interaktif**:
  * Ringkasan presensi harian (status masuk, pulang, durasi kerja, dan indikator keterlambatan).
  * Statistik akumulasi kehadiran, sakit, izin, cuti, serta rata-rata skor evaluasi.
  * *Quick Action Cards* untuk akses cepat ke seluruh menu utama.
  * Feed pengumuman perusahaan dan notifikasi terbaru.
* **Presensi Geofencing & Selfie (`/peserta/absensi`)**:
  * Perhitungan jarak koordinat pengguna ke titik koordinat kantor PLTU Adipala secara otomatis menggunakan algoritma *Haversine formula*.
  * Radius validasi presensi (maksimal 150 meter dari titik koordinat PLTU Adipala `-7.6808102, 109.1366922`).
  * Integrasi webcam browser untuk pengambilan foto selfie saat check-in dan check-out.
  * Form pengajuan koreksi presensi jika peserta lupa absen atau terkendala akurasi GPS.
* **Logbook Kegiatan Harian (`/peserta/logbook`)**:
  * Input jam mulai dan jam selesai kegiatan magang per tanggal kerja.
  * Detail deskripsi aktivitas, hasil yang dicapai, kendala yang dihadapi, solusi, serta rencana kegiatan berikutnya.
  * Upload berkas bukti pekerjaan (PDF, JPG, PNG, DOCX).
  * Status alur kerja: `Draft` ➔ `Submitted` ➔ `Revision Required` ➔ `Approved`.
  * Panel riwayat komentar revisi langsung dari pembimbing lapangan.
* **Pengajuan Cuti & Izin Terintegrasi (`/peserta/cuti`)**:
  * Kategori izin: Cuti Pribadi, Sakit, Izin Akademik/Kampus, Tugas Dinas, Keperluan Darurat.
  * Rentang tanggal fleksibel disertai upload dokumen pendukung (misal: surat dokter).
  * Opsi pembatalan mandiri sebelum pengajuan diproses pembimbing.
* **Pusat Aduan Masalah & Helpdesk (`/peserta/aduan`)**:
  * Pelaporan kendala teknis, lingkungan kerja, K3, administrasi, maupun bimbingan.
  * Penomoran tiket aduan otomatis (`ADU-YYYYMMDD-XXXX`).
  * Tingkat kerahasiaan (*confidentiality*) dan prioritas aduan.
  * Sistem *two-way thread response* antara peserta dan admin.
* **Arsip Nilai Harian (`/peserta/nilai`)**:
  * Transparansi evaluasi berdasarkan indikator terstandarisasi (Kedisiplinan, Kemampuan Teknis, Komunikasi, Kerja Sama, Inisiatif, Tanggung Jawab & K3).
  * Rekap catatan evaluasi dan feedback pembimbing.
* **Informasi Perusahaan & K3 (`/peserta/informasi`)**:
  * Profil operasional PLTU Adipala, struktur jam kerja, tata tertib PKL, nomor kontak darurat, dan pedoman K3.
* **Pusat Notifikasi & Profil (`/peserta/notifikasi`, `/peserta/profil`)**:
  * Notifikasi database realtime ketika status logbook, cuti, absensi, atau aduan diperbarui.
  * Pembaruan data pribadi, kontak darurat keluarga, dan penggantian password akun.

### 2. Portal Manajemen Admin Divisi & Pembimbing (`/admin/*`)
* **Manajemen & Review Absensi**:
  * Rekapitulasi absensi seluruh peserta (jam datang, jam pulang, foto selfie, koordinat dan akurasi GPS).
  * Modal review persetujuan atau penolakan pengajuan koreksi presensi mahasiswa.
* **Review & Validasi Logbook**:
  * Pemeriksaan berkas dan rincian kerja harian mahasiswa bimbingan.
  * Aksi persetujuan (*Approve*) atau permintaan revisi (*Request Revision*) dilengkapi catatan perbaikan.
* **Manajemen Aduan Masalah**:
  * Pemantauan tiket aduan masuk, penentuan status penanganan (`in_progress`, `resolved`, `closed`), dan pengiriman balasan resmi ke portal peserta.
* **Persetujuan Cuti & Izin**:
  * Verifikasi surat dokter/keterangan kampus dan aksi persetujuan (*Approved*) / penolakan (*Rejected*).
* **Penilaian Harian Mahasiswa**:
  * Form input skor penilaian berkala berdasarkan kriteria pembobotan divisi.
* **Pusat Pengumuman & Konfigurasi Perusahaan**:
  * Pembuatan dan penerbitan pengumuman internal perusahaan.
  * Pengaturan parameter jam kerja dan radius geofencing presensi.
* **Administrasi Dokumen & Sertifikat Magang**:
  * Pengelolaan surat balasan pengajuan magang, verifikasi ID Card, unggah berkas sertifikat akhir magang, dan arsip laporan akhir mahasiswa.

### 3. Peningkatan Arsitektur & Basis Data
* Penambahan migrasi komprehensif (`comprehensive_monitoring_features_table` dan `add_selfie_out_path_to_attendances_table`).
* Implementasi relasi model Eloquent lengkap (`Mahasiswa`, `User`, `Attendance`, `DailyLogbook`, `LeaveRequest`, `Complaint`, `DailyEvaluation`, `CompanyInformation`, `AppNotification`).
* Desain antarmuka modern dengan skema warna korporat PLTU Adipala (Deep Navy `#0f172a`, PLTU Blue `#1e3a8a`, Teal Cyan `#0ea5e9`, dan Gold Amber `#f59e0b`).

---

## 💡 Saran & Rekomendasi Evaluasi Proyek

Untuk pengembangan pada fase berikutnya dan penyempurnaan implementasi di lingkungan operasional riil, berikut beberapa poin evaluasi penting yang direkomendasikan:

### 1. Keamanan Validasi Lokasi (Anti-Fake GPS)
* **Kondisi Saat Ini**: Koordinat lokasi diambil dari browser Geolocation API (`navigator.geolocation`), yang rentan dimanipulasi menggunakan ekstensi browser pengubah lokasi (*mock location*).
* **Saran Solusi**:
  * Terapkan validasi ganda menggunakan **QR Code Dinamis (TOTP / Time-based Rotating QR)** yang ditampilkan pada layar monitor di lobi kantor PLTU. Mahasiswa harus memindai QR fisik tersebut sekaligus mencocokkan koordinat GPS.
  * Tambahkan deteksi kejanggalan akurasi GPS (misal akurasi `0` atau kecepatan perpindahan lokasi yang tidak wajar).

### 2. Implementasi Otomatisasi Face Recognition & Liveness Detection
* **Kondisi Saat Ini**: Verifikasi wajah masih berupa foto selfie statis yang disimpan ke server dan diverifikasi secara manual oleh pembimbing.
* **Saran Solusi**:
  * Integrasikan library AI ringan di frontend (seperti `face-api.js` berbasis TensorFlow.js) atau cloud face recognition API untuk membandingkan kemiripan wajah selfie dengan foto profil terdaftar secara otomatis (*face match score*).
  * Terapkan *liveness detection* sederhana (instruksi kedip mata atau toleh kiri-kanan) untuk mencegah kecurangan menggunakan foto cetak.

### 3. Pemisahan Hak Akses (Role Pembimbing Lapangan vs Dosen Kampus)
* **Kondisi Saat Ini**: Fungsi monitoring dan penilaian saat ini masih terpusat di role `Admin` Divisi.
* **Saran Solusi**:
  * Pisahkan role menjadi lebih spesifik:
    1. **Pembimbing Lapangan**: Khusus menilai aspek teknis harian, verifikasi logbook, dan presensi di divisi terkait.
    2. **Dosen Pembimbing Kampus**: Diberikan akses *read-only* untuk memantau kehadiran, membaca logbook, dan mengunduh rekap evaluasi akhir mahasiswa bimbingannya.

### 4. Ekspor Rekapitulasi Otomatis (PDF & Excel)
* **Kondisi Saat Ini**: Rekap kehadiran dan logbook baru dapat dilihat di layar antarmuka web.
* **Saran Solusi**:
  * Tambahkan fitur cetak **Buku Logbook Mingguan/Bulanan ke format PDF** (menggunakan library `barryvdh/laravel-dompdf` atau `snappy`) yang dilengkapi lembar pengesahan dan QR code tanda tangan digital (*TTE*) pembimbing.
  * Tambahkan ekspor laporan rekap presensi dan rekap nilai akhir ke format Excel (`maatwebsite/excel`).

### 5. Pengelolaan Penyimpanan Berkas (*Storage Security*)
* **Kondisi Saat Ini**: Berkas selfie, surat dokter, dan lampiran aduan tersimpan di direktori `public/uploads/` yang dapat diakses langsung bila URL diketahui.
* **Saran Solusi**:
  * Pindahkan berkas sensitif (terutama surat izin medis, laporan rahasia perusahaan, dan berkas aduan) ke `storage/app/private/`.
  * Buat route unduh dengan middleware otorisasi agar hanya pemilik berkas dan pembimbing yang bersangkutan yang dapat melihat atau mengunduh file tersebut.

### 6. Otomatisasi Notifikasi & Background Queue
* **Kondisi Saat Ini**: Notifikasi aplikasi tersimpan di basis data dan pengiriman email berjalan secara sinkron.
* **Saran Solusi**:
  * Gunakan **Laravel Queue Worker** (`php artisan queue:work`) untuk pengiriman email notifikasi (surat balasan, sertifikat, reset password) agar proses HTTP request pengguna tidak terhambat (*non-blocking*).
  * Pertimbangkan implementasi WebSocket realtime (Laravel Reverb / Pusher) agar notifikasi revisi logbook atau balasan aduan langsung muncul sebagai *toast popup* tanpa perlu refresh browser.

### 7. Pengujian Otomatis (*Automated Testing*)
* **Kondisi Saat Ini**: Pengujian masih dilakukan secara manual melalui browser.
* **Saran Solusi**:
  * Buat pengujian otomatis unit & feature test (`php artisan test`) untuk skenario kritis, misalnya:
    * Presensi di luar radius 150 meter harus ditolak server.
    * Peserta tidak dapat melakukan check-out sebelum check-in.
    * Pengisian logbook ganda pada tanggal yang sama dicegah oleh validasi backend.

---

## ❓ FAQ & Troubleshooting Umum

1. **Error: `SQLSTATE[HY000] [2002] Connection refused`**
   * **Penyebab**: MySQL di XAMPP belum menyala atau port database tidak sesuai.
   * **Solusi**: Pastikan MySQL di XAMPP sudah berwarna hijau. Cek port di XAMPP (jika `3307`, ubah `DB_PORT=3307` di file `.env`).

2. **Error: `Vite manifest not found` atau tampilan styling berantakan**
   * **Penyebab**: Aset frontend belum di-compile oleh Vite.
   * **Solusi**: Jalankan perintah `npm run dev` atau `npm run build`.

3. **Kamera Selfie atau GPS tidak merespons di halaman absensi**
   * **Penyebab**: Izin perangkat diblokir oleh browser.
   * **Solusi**: Klik ikon gembok/pengaturan situs di sebelah kiri address bar browser (`http://127.0.0.1:8000`), ubah status **Camera** dan **Location** menjadi **Allow**.

4. **Gambar / Lampiran tidak muncul setelah diunggah**
   * **Solusi**: Jalankan perintah `php artisan storage:link` di terminal projek.

---

*Dikembangkan untuk Program Praktik Kerja Lapangan & Magang Industri — PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala.*