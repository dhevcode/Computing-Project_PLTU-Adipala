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
| **Admin** | `admin` | `admin@pltu.com` | `admin123` | Approval logbook, review absensi & koreksi, kelola cuti, aduan, nilai harian, info perusahaan, verifikasi ID card & sertifikat |
| **Pembimbing Lapangan / Divisi** | `Pembimbing` | `pembimbing@pltu.com` | `pembimbing123` | Approval logbook, review absensi & koreksi, kelola cuti, aduan, nilai harian |
| **Peserta Magang 1** | `2311102001` (NIM) | `rian.pratama@pltu.com` | `mhs123` | Divisi Sistem Informasi, presensi GPS, pengisian logbook, ajukan izin/cuti/aduan |
| **Peserta Magang 2** | `2311102002` (NIM) | `siti.nurhaliza@pltu.com` | `mhs123` | Divisi SDM & Umum, presensi GPS, pengisian logbook, ajukan izin/cuti/aduan |
| **Peserta Magang Telkom** | `2311102324` (NIM) | `mhs1@gmail.com` | `mhs123` | Data mahasiswa Telkom University terintegrasi |

---

## 📱 Integrasi Mobile Backend API (Satu Database Web & Mobile)

Aplikasi Web dan Mobile terhubung ke **satu basis data yang sama** (`monitoring_magang` di MySQL) secara *real-time* melalui RESTful API berbasis **Laravel Sanctum**.

---

### 📄 Konfigurasi Koneksi (`api.ini`)
File konfigurasi [api.ini](api.ini) (juga dapat diakses via HTTP di `public/api.ini`) disediakan untuk memudahkan integrasi pada aplikasi Mobile (Flutter, Android Kotlin, React Native, iOS, dsb.):

```ini
[server]
base_url_local      = "http://127.0.0.1:8000/api"
base_url_emulator   = "http://10.0.2.2:8000/api"     ; Khusus Android Studio Emulator
base_url_lan        = "http://192.168.1.10:8000/api"  ; HP Fisik (Sesuaikan IP WiFi Laptop)

[database]
driver   = "mysql"
host     = "127.0.0.1"
port     = 3307
database = "monitoring_magang"
```

---

### 🔐 Standar Header & Autentikasi API
Setiap *request* ke endpoint yang memerlukan autentikasi (`auth:sanctum`) wajib menyertakan header HTTP berikut:
```http
Accept: application/json
Authorization: Bearer <TOKEN_HASIL_LOGIN>
```

---

### 🌐 Daftar Lengkap Endpoint API untuk Mobile

#### 1. Autentikasi (`/api/*`)
| Method | Endpoint | Hak Akses | Deskripsi |
|---|---|---|---|
| `POST` | `/api/login` | Publik | Login akun (email/NIM & password). Menghasilkan Bearer Token. |
| `POST` | `/api/logout` | Bearer Token | Logout dan mencabut (*revoke*) token akses yang aktif. |
| `GET` | `/api/user` | Bearer Token | Mendapatkan detail profil user yang sedang login beserta relasi mahasiswa. |

* **Request Body `POST /api/login`**:
  ```json
  {
    "email": "rian.pratama@pltu.com",
    "password": "mhs123"
  }
  ```
  *(Catatan: Input `email` juga menerima NIM / username peserta).*

* **Contoh Response Sukses `200 OK`**:
  ```json
  {
    "success": true,
    "message": "Login berhasil.",
    "token": "1|eXamPLeToKenSanCtUm123456789...",
    "token_type": "Bearer",
    "user": {
      "id_users": 3,
      "nama_lengkap": "Rian Pratama",
      "username": "2311102001",
      "email": "rian.pratama@pltu.com",
      "role": "Peserta"
    },
    "mahasiswa": {
      "id_mhs": 1,
      "nim": "2311102001",
      "nama": "Rian Pratama",
      "universitas": { "nama_universitas": "Universitas Telkom" },
      "jurusan": { "nama_jurusan": "S1 Sistem Informasi" }
    }
  }
  ```

---

#### 2. Dashboard Peserta (`/api/peserta/dashboard`)
| Method | Endpoint | Hak Akses | Deskripsi |
|---|---|---|---|
| `GET` | `/api/peserta/dashboard` | Bearer Token | Data ringkasan kehadiran, statistik akumulasi, status presensi hari ini, serta koordinat/radius geofence kantor PLTU Adipala. |

* **Contoh Response `200 OK`**:
  ```json
  {
    "success": true,
    "mahasiswa": { ... },
    "attendance_today": null,
    "company_config": {
      "name": "PT PLN Indonesia Power UBP Adipala",
      "latitude": -7.68081019,
      "longitude": 109.13669216,
      "radius_meters": 150,
      "work_start_time": "07:30:00",
      "work_end_time": "16:00:00"
    },
    "summary": {
      "total_hadir": 22,
      "total_terlambat": 1,
      "total_izin": 2,
      "total_logbook": 20
    }
  }
  ```

---

#### 3. Presensi & Absensi Geofencing (`/api/peserta/absensi/*`)
| Method | Endpoint | Hak Akses | Deskripsi |
|---|---|---|---|
| `GET` | `/api/peserta/absensi/today` | Bearer Token | Cek status absensi hari ini & konfigurasi kantor. |
| `POST` | `/api/peserta/absensi/check-in` | Bearer Token | Presensi masuk (validasi jarak GPS kantor & unggah foto selfie). |
| `POST` | `/api/peserta/absensi/check-out` | Bearer Token | Presensi pulang & kalkulasi durasi jam kerja. |
| `GET` | `/api/peserta/absensi/history` | Bearer Token | Riwayat presensi peserta (mendukung parameter `per_page`). |
| `POST` | `/api/peserta/absensi/koreksi` | Bearer Token | Pengajuan koreksi absensi jika lupa absen/terkendala GPS. |

* **Payload `POST /api/peserta/absensi/check-in`**:
  - `latitude` (numeric, required)
  - `longitude` (numeric, required)
  - `accuracy` (numeric, optional — default: 20)
  - `notes` (string, optional)
  - `selfie_image` (file gambar `multipart/form-data` atau string base64 `data:image/png;base64,...`)

* **Payload `POST /api/peserta/absensi/check-out`**:
  - `latitude` (numeric, optional)
  - `longitude` (numeric, optional)
  - `selfie_image` (file gambar atau string base64, optional)

* **Payload `POST /api/peserta/absensi/koreksi`**:
  - `attendance_date` (date: `YYYY-MM-DD`, required)
  - `requested_check_in` (time: `HH:mm`, required)
  - `requested_check_out` (time: `HH:mm`, optional)
  - `reason` (string, required)
  - `attachment` (file pdf/jpg/png, optional)

---

#### 4. Logbook Harian (`/api/peserta/logbook/*`)
| Method | Endpoint | Hak Akses | Deskripsi |
|---|---|---|---|
| `GET` | `/api/peserta/logbook` | Bearer Token | Daftar logbook harian beserta catatan revisi/komentar pembimbing. |
| `POST` | `/api/peserta/logbook/store` | Bearer Token | Simpan logbook harian baru (status `draft` atau `submit`). |

* **Payload `POST /api/peserta/logbook/store`**:
  - `logbook_date` (date: `YYYY-MM-DD`, required)
  - `title` (string, max: 200, required)
  - `activity` (string, min: 10 karakter, required)
  - `result` (string, optional)
  - `obstacle` (string, optional)
  - `solution` (string, optional)
  - `next_plan` (string, optional)
  - `action_type` (`draft` atau `submit`, default: `submit`)
  - `attachment` (file dokumen pdf/jpg/png/docx maks 10MB, optional)

---

#### 5. Pengajuan Cuti & Izin (`/api/peserta/cuti/*`)
| Method | Endpoint | Hak Akses | Deskripsi |
|---|---|---|---|
| `GET` | `/api/peserta/cuti` | Bearer Token | Daftar riwayat permohonan cuti / izin dan status approval. |
| `POST` | `/api/peserta/cuti/store` | Bearer Token | Kirim pengajuan cuti/izin baru beserta bukti surat keterangan. |

* **Payload `POST /api/peserta/cuti/store`**:
  - `type` (`izin`, `sakit`, atau `cuti`, required)
  - `start_date` (date: `YYYY-MM-DD`, required)
  - `end_date` (date: `YYYY-MM-DD`, required, `>= start_date`)
  - `reason` (string, required)
  - `attachment` (file surat dokter / izin kampus maks 5MB, optional)

---

#### 6. Aduan Masalah & Helpdesk (`/api/peserta/aduan/*`)
| Method | Endpoint | Hak Akses | Deskripsi |
|---|---|---|---|
| `GET` | `/api/peserta/aduan` | Bearer Token | Daftar tiket aduan beserta respon/tanggapan dari admin. |
| `POST` | `/api/peserta/aduan/store` | Bearer Token | Kirim tiket aduan kendala / fasilitas baru. |

* **Payload `POST /api/peserta/aduan/store`**:
  - `title` (string, max: 200, required)
  - `category` (string: `fasilitas`, `bimbingan`, `k3`, `administrasi`, required)
  - `content` (string deskripsi kendala, required)
  - `attachment` (file foto/bukti pendukung, optional)

---

#### 7. Nilai, Informasi & Profil (`/api/peserta/*`)
| Method | Endpoint | Hak Akses | Deskripsi |
|---|---|---|---|
| `GET` | `/api/peserta/nilai` | Bearer Token | Rekap nilai harian & evaluasi dari pembimbing lapangan. |
| `GET` | `/api/peserta/informasi` | Bearer Token | Informasi operasional perusahaan, tata tertib, dan pedoman K3. |
| `GET` | `/api/peserta/notifikasi` | Bearer Token | Riwayat notifikasi aktivitas akun peserta. |
| `POST` | `/api/peserta/profil/update` | Bearer Token | Pembaruan nomor telepon, alamat, kontak darurat, & foto profil. |

---

### 🧪 Panduan Menjalankan & Menguji API dari Mobile

1. **Jalankan Backend Server:**
   Buka terminal di root project dan jalankan:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
   *(Gunakan opsi `--host=0.0.0.0` agar server dapat diakses dari luar perangkat host).*

2. **Pengujian pada Android Emulator:**
   - Gunakan Base URL: `http://10.0.2.2:8000/api`
   - Android Emulator mengarahkan IP `10.0.2.2` ke `127.0.0.1` (localhost) di komputer host.

3. **Pengujian pada HP Fisik (Real Device):**
   - Pastikan laptop dan HP terhubung ke **jaringan Wi-Fi yang sama**.
   - Cari tahu IP LAN laptop Anda (jalankan `ipconfig` di command prompt, misal `192.168.1.15`).
   - Gunakan Base URL: `http://192.168.1.15:8000/api`.

4. **Uji Coba Cepat via cURL:**
   ```bash
   # 1. Login untuk mendapatkan Token
   curl -X POST http://127.0.0.1:8000/api/login \
     -H "Accept: application/json" \
     -d "email=rian.pratama@pltu.com" \
     -d "password=mhs123"

   # 2. Panggil Data Dashboard Peserta
   curl -X GET http://127.0.0.1:8000/api/peserta/dashboard \
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

---

*Dikembangkan untuk Program Praktik Kerja Lapangan & Magang Industri — PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala.*
