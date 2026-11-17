# Sistem Monitoring Magang & PKL - PLTU Adipala

Sistem informasi berbasis web untuk memantau, mencatat, dan mengevaluasi kegiatan Praktik Kerja Lapangan (PKL) / Magang mahasiswa di **PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala**. Sistem ini mencakup portal mandiri bagi peserta (presensi berbasis geofencing & selfie, logbook harian, cuti/izin, aduan, nilai, informasi K3) serta portal manajemen bagi Admin dan Pembimbing Lapangan.

---

## 🛠️ Tech Stack & Kebutuhan Environment

Sebelum menjalankan aplikasi, setiap anggota kelompok wajib memastikan perangkat lokal telah terpasang perangkat lunak pendukung berikut:

### 1. Spesifikasi Software Minimal
| Komponen | Versi Minimal | Keterangan |
|---|---|---|
| **PHP** | `^8.2` (Disarankan PHP 8.2 atau 8.3) | Pastikan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`, `curl`, `xml`, `zip` aktif. **`gd` wajib** agar logo PLN tampil di PDF (sertifikat & export); tanpa `gd` logo diganti teks |
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

> [!NOTE]
> **Email (surat balasan + akun portal, sertifikat, notifikasi pengajuan baru):** untuk pengujian lokal gunakan `MAIL_MAILER=log` — isi email ditulis ke `storage/logs/laravel.log` dan tidak benar-benar terkirim. Di server isi `MAIL_MAILER=smtp` beserta `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` (jangan pernah commit file `.env`).

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

> [!IMPORTANT]
> **Sudah pernah setup sebelumnya?** Setelah `git pull`, cukup jalankan `php artisan migrate` (tanpa `--seed`) untuk menambah kolom baru tanpa menghapus data, lalu `php artisan optimize:clear`.
> Migrasi terbaru: `2026_11_17_120000_add_penerbitan_to_sertifikat_table` (data penerbitan sertifikat) dan `2026_11_17_150000_add_media_sosial_to_companies_table` (Instagram/YouTube/Facebook perusahaan).

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
| **Admin** | `admin` | `admin@pltu.com` | `admin123` | Approval logbook, review presensi & koreksi, kelola ketidakhadiran, aduan, penilaian akhir & penerbitan sertifikat, info perusahaan, verifikasi ID card |
| **Pembimbing Lapangan 1** | `cihuy` | `pembimbing@pltu.com` | `pembimbing123` | Pembimbing Rian Pratama — approval logbook, review presensi & koreksi, ketidakhadiran, aduan, penilaian akhir |
| **Pembimbing Lapangan 2** | `budisantoso` | `budi.santoso@pltu.com` | `pembimbing123` | Pembimbing Siti Nurhaliza — hak akses sama dengan Pembimbing 1 |
| **Peserta Magang 1** | `2311102001` (NIM) | `rian.pratama@pltu.com` | `mhs123` | Divisi Sistem Informasi, presensi GPS, pengisian logbook, ajukan izin/cuti/aduan |
| **Peserta Magang 2** | `2311102002` (NIM) | `siti.nurhaliza@pltu.com` | `mhs123` | Divisi SDM & Umum, presensi GPS, pengisian logbook, ajukan izin/cuti/aduan |
| **Peserta Magang Telkom** | `2311102324` (NIM) | `mhs1@gmail.com` | `mhs123` | Data mahasiswa Telkom University terintegrasi |

---

## 🆕 Fitur Hasil Revisi (November 2026)

| Fitur | Ringkasan |
|---|---|
| **Penamaan menu** | "Presensi (Kehadiran)", "Ketidakhadiran" (izin/sakit/cuti), dan "Penilaian Akhir" di semua role. |
| **Penilaian akhir & status alumni** | Satu penilaian akhir per mahasiswa (grade A ≥ 85, B ≥ 75, C ≥ 60, D). Saat dipublikasikan, status magang menjadi *Selesai Magang* (alumni): fitur pengajuan dikunci, peserta hanya bisa melihat arsip. |
| **Sertifikat magang** | Admin → *Penilaian Akhir* → tombol **Sertifikat**: isi nomor, kota, tanggal, jabatan & nama penandatangan → *Pratinjau PDF* → *Terbitkan & Kirim*. Syarat: penilaian akhir terbit, laporan diunggah, ID card terverifikasi. Peserta mengunduh dari halaman *Transkrip Nilai Akhir*. |
| **Surat balasan + akun portal** | Saat mengunggah surat balasan (menu *Dokumen Verifikasi*), centang "Sertakan akun login portal di email" — akun peserta dibuat otomatis (bila belum ada) dan username/password ikut di email. |
| **Media sosial perusahaan** | Website, Instagram, YouTube, Facebook diatur di *Info & Pengumuman* dan tampil di halaman informasi peserta. |
| **Penghapusan peserta aman** | Peserta berstatus Sedang/Selesai Magang atau yang sudah punya presensi/logbook/nilai tidak bisa dihapus (arsip terjaga). Hapus peserta ikut menghapus akun portalnya. |
| **Notifikasi** | Peserta: aktivitas & hasil tinjauan. Pembimbing: kiriman peserta bimbingan (koreksi presensi, logbook, ketidakhadiran, aduan, balasan aduan). Admin & Super Admin: pengajuan magang baru. |
| **Export peserta** | Presensi & logbook (PDF/Excel) serta transkrip nilai (PDF). |

---

## 📱 Integrasi Mobile Backend API (Satu Database Web & Mobile)

Aplikasi Web dan Mobile (Flutter) memakai **satu basis data yang sama** melalui RESTful API **Laravel Sanctum** (Bearer Token). Referensi lengkap setiap endpoint, parameter, dan aturannya ada di **[api.ini](api.ini)** (salinannya di `public/api.ini`).

### 🔐 Header & Format Respons
```http
Accept: application/json
Authorization: Bearer <TOKEN_HASIL_LOGIN>
```
- Semua respons: `{ "success": bool, "message": string|null, "data": ..., "meta": {...} }`
- Daftar ber-halaman: `meta = { current_page, last_page, per_page, total }` (parameter `?page=` & `?per_page=`)
- Tanggal `Y-m-d`, waktu ISO-8601 WIB (contoh `2026-11-06T07:45:00+07:00`)
- Error validasi: HTTP `422` → `{ message, errors: { field: [pesan] } }`; akses ditolak `403`; konflik aturan (mis. hapus peserta aktif) `409`; terlalu sering `429`

### 🔑 Login (semua role)
`POST /api/login` — field `email` berisi **email, NIM, atau username**.
```json
{ "email": "2311102001", "password": "mhs123" }
```
Respons `200`:
```json
{
  "success": true,
  "message": "Login berhasil.",
  "token": "1|xxxxxxxx",
  "token_type": "Bearer",
  "user": { "id": 3, "nama_lengkap": "Rian Pratama", "username": "2311102001", "role": "Peserta", "role_key": "peserta" },
  "mahasiswa": { "id": 1, "nim": "2311102001", "...": "..." },
  "data": { "token": "...", "user": { }, "mahasiswa": { } }
}
```
`user.role_key` = `peserta` | `pembimbing` | `admin` | `superadmin` → dipakai aplikasi mobile untuk membuka halaman sesuai role. `mahasiswa` bernilai `null` untuk selain peserta.

### 🌐 Kelompok Endpoint

| Prefix | Hak akses | Isi |
|---|---|---|
| `/api/login`, `/api/logout`, `/api/user` | Publik / token | Autentikasi & profil akun yang login |
| `/api/notifikasi` | Semua role | Daftar notifikasi (`meta.unread_count`), `/{id}/read`, `/read-all` |
| `/api/peserta/*` | Peserta | Dashboard, presensi (check-in/out selfie + GPS, koreksi), logbook, ketidakhadiran, aduan, nilai, sertifikat, export, informasi, profil |
| `/api/pembimbing/*` | Pembimbing | Data mahasiswa bimbingan sendiri: presensi & koreksi, logbook, ketidakhadiran, aduan, penilaian akhir, profil |
| `/api/admin/*` | Admin | Seluruh mahasiswa: data & verifikasi pengajuan, dokumen/surat balasan, sertifikat, monitoring, penilaian akhir + terbitkan sertifikat, pengumuman & info perusahaan, chatbot FAQ, profil |
| `/api/superadmin/*` | Super Admin | Kelola akun, pembimbing, peserta (buat akun / reset password), profil |
| `/api/pendaftaran` | Publik | Referensi & kirim pengajuan magang baru (berkas CV, proposal, surat permohonan) |
| `/api/chatbot/*` | Publik | Pertanyaan & jawaban FAQ "Tanya Kami" |

> Unduhan PDF (export peserta & pratinjau sertifikat) memakai **URL bertanda tangan** yang berlaku 10 menit — dibuka langsung di browser tanpa token.

### ⏱️ Batas Permintaan (Rate Limit)
Dihitung terpisah per IP: login **10/menit**, pendaftaran **5/menit**, jawaban chatbot **60/menit**, unduhan bertanda tangan **30/menit**. Melebihi batas → HTTP `429`, tunggu ±1 menit.

---

### 🧪 Panduan Menjalankan & Menguji API dari Mobile

1. **Jalankan Backend Server:**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
   *(Opsi `--host=0.0.0.0` agar server dapat diakses dari emulator/HP).*

2. **Android Emulator:** Base URL `http://10.0.2.2:8000/api` (default aplikasi mobile). Alternatif: jalankan `adb reverse tcp:8000 tcp:8000` lalu pakai `http://127.0.0.1:8000/api`.

3. **HP Fisik (Real Device):** laptop & HP di **Wi-Fi yang sama**, cek IP laptop (`ipconfig`, misal `192.168.1.15`), lalu build aplikasi dengan `--dart-define=API_BASE_URL=http://192.168.1.15:8000/api`.

4. **Uji Cepat via cURL:**
   ```bash
   # 1. Login untuk mendapatkan token
   curl -X POST http://127.0.0.1:8000/api/login \
     -H "Accept: application/json" \
     -d "email=2311102001" \
     -d "password=mhs123"

   # 2. Dashboard peserta
   curl http://127.0.0.1:8000/api/peserta/dashboard \
     -H "Accept: application/json" \
     -H "Authorization: Bearer <TOKEN_DARI_LOGIN>"

   # 3. Notifikasi (semua role)
   curl http://127.0.0.1:8000/api/notifikasi \
     -H "Accept: application/json" \
     -H "Authorization: Bearer <TOKEN_DARI_LOGIN>"
   ```

---

## ❓ FAQ & Troubleshooting Umum

1. **Error: `SQLSTATE[HY000] [2002] Connection refused`**
   * **Penyebab**: MySQL di XAMPP belum menyala atau port database tidak sesuai.
   * **Solusi**: Pastikan modul MySQL di XAMPP sudah aktif. Cek port di XAMPP (jika `3307`, ubah `DB_PORT=3307` di file `.env`).

2. **Error: `Unauthenticated` pada Endpoint API**
   * **Penyebab**: Header `Authorization` tidak dikirim atau format token salah.
   * **Solusi**: Pastikan menyertakan header `Authorization: Bearer <token_dari_api_login>` dan `Accept: application/json`.

3. **Mobile Emulator tidak dapat menghubungi API (`Connection Timeout`)**
   * **Penyebab**: Menggunakan URL `localhost` di dalam emulator atau firewall memblokir port 8000.
   * **Solusi**: Pada Android Emulator ganti `localhost` menjadi `10.0.2.2`. Pastikan server berjalan dengan `php artisan serve --host=0.0.0.0 --port=8000`.

4. **Error: `Vite manifest not found` atau tampilan styling berantakan pada versi Web**
   * **Penyebab**: Aset frontend belum di-compile oleh Vite.
   * **Solusi**: Jalankan perintah `npm run dev` atau `npm run build`.

5. **Kamera Selfie atau GPS tidak merespons di halaman absensi Web**
   * **Penyebab**: Izin perangkat diblokir oleh browser.
   * **Solusi**: Klik ikon gembok/pengaturan situs di sebelah kiri address bar browser (`http://127.0.0.1:8000`), ubah status **Camera** dan **Location** menjadi **Allow**.

6. **Gambar / Lampiran tidak muncul setelah diunggah**
   * **Solusi**: Jalankan perintah `php artisan storage:link` di terminal projek.

7. **Logo PLN di PDF (sertifikat / export) berubah menjadi tulisan**
   * **Penyebab**: Ekstensi PHP `gd` belum aktif (dompdf butuh `gd` untuk gambar PNG).
   * **Solusi**: Aktifkan `extension=gd` di `php.ini` (XAMPP: `xampp/php/php.ini`), lalu restart server.

8. **Error `Column not found` (mis. `nomor_sertifikat` / `instagram_url`) setelah `git pull`**
   * **Solusi**: Jalankan `php artisan migrate` untuk menambahkan kolom baru.

9. **API membalas `429 Too Many Attempts`**
   * **Penyebab**: Terlalu banyak login / pengajuan dalam satu menit dari jaringan yang sama.
   * **Solusi**: Tunggu sekitar 1 menit lalu coba lagi.

10. **Email surat balasan / sertifikat tidak masuk**
    * **Penyebab**: `MAIL_MAILER=log` (mode uji) atau konfigurasi SMTP belum diisi.
    * **Solusi**: Untuk lokal, cek isi email di `storage/logs/laravel.log`; untuk server, isi pengaturan SMTP di `.env`.

---

*Dikembangkan untuk Program Praktik Kerja Lapangan & Magang Industri — PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala.*
