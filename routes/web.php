<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\ProfileSuperAdminController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\AdminAbsensiController;
use App\Http\Controllers\AdminLogbookController;
use App\Http\Controllers\AdminCutiController;
use App\Http\Controllers\AdminAduanController;
use App\Http\Controllers\AdminNilaiController;
use App\Http\Controllers\AdminPengumumanController;
use App\Http\Controllers\PembimbingController;
use App\Http\Controllers\PengajuanMagangPublicController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AdminChatbotController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// =====================================================
// ROUTE HALAMAN UTAMA
// =====================================================
Route::get('/', function () {

    if (Auth::check()) {

        $role = strtolower(trim(Auth::user()->role));

        return match ($role) {
            'super admin'                       => redirect()->route('superadmin.dashboard'),
            'admin'                             => redirect()->route('admin.dashboard'),
            'pembimbing', 'pembimbing lapangan' => redirect()->route('pembimbing.dashboard'),
            'peserta', 'mahasiswa'              => redirect()->route('peserta.dashboard'),
            default                             => redirect()->route('login'),
        };
    }

    return redirect()->route('login');
});


// =====================================================
// GUEST ROUTES (LOGIN & PENGAJUAN MAGANG MANDIRI)
// =====================================================
Route::middleware(['guest'])->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    // Formulir Pendaftaran / Pengajuan Magang Baru
    Route::get('/pengajuan-magang', [PengajuanMagangPublicController::class, 'create'])
        ->name('magang.daftar');

    Route::post('/pengajuan-magang', [PengajuanMagangPublicController::class, 'store'])
        ->name('magang.store');

    Route::get('/daftar-magang', fn() => redirect()->route('magang.daftar'));
});


// =====================================================
// AUTHENTICATED ROUTES
// =====================================================
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});


// =====================================================
// SUPER ADMIN
// =====================================================
Route::redirect('/superadmin', '/superadmin/dashboard');

Route::middleware(['auth', 'role:Super Admin'])->group(function () {

    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])
        ->name('superadmin.dashboard');

    Route::get('/superadmin/create', [SuperAdminController::class, 'create'])
        ->name('superadmin.create');

    Route::post('/superadmin/store', [SuperAdminController::class, 'store'])
        ->name('superadmin.store');

    Route::get('/superadmin/edit/{id}', [SuperAdminController::class, 'edit'])
        ->name('superadmin.edit');

    Route::put('/superadmin/update/{id}', [SuperAdminController::class, 'update'])
        ->name('superadmin.update');

    Route::delete('/superadmin/delete/{id}', [SuperAdminController::class, 'destroy'])
        ->name('superadmin.destroy');

    // ================= DATA & MONITORING PEMBIMBING LAPANGAN =================
    Route::get('/superadmin/pembimbing', [SuperAdminController::class, 'pembimbing'])
        ->name('superadmin.pembimbing');

    // ================= KELOLA PESERTA MAGANG (CRUD) =================
    Route::get('/superadmin/peserta', [SuperAdminController::class, 'peserta'])
        ->name('superadmin.peserta');

    Route::get('/superadmin/peserta/create', [SuperAdminController::class, 'createPeserta'])
        ->name('superadmin.peserta.create');

    Route::post('/superadmin/peserta', [SuperAdminController::class, 'storePeserta'])
        ->name('superadmin.peserta.store');

    Route::get('/superadmin/peserta/{id}', [SuperAdminController::class, 'showPeserta'])
        ->name('superadmin.peserta.show');

    Route::get('/superadmin/peserta/{id}/edit', [SuperAdminController::class, 'editPeserta'])
        ->name('superadmin.peserta.edit');

    Route::put('/superadmin/peserta/{id}', [SuperAdminController::class, 'updatePeserta'])
        ->name('superadmin.peserta.update');

    Route::delete('/superadmin/peserta/{id}', [SuperAdminController::class, 'destroyPeserta'])
        ->name('superadmin.peserta.destroy');

    // ================= AKSI PEMBUATAN & RESET AKUN PESERTA =================
    Route::post('/superadmin/peserta/{id}/buat-akun', [SuperAdminController::class, 'buatAkunPeserta'])
        ->name('superadmin.peserta.buat_akun');

    Route::post('/superadmin/peserta/{id}/reset-password', [SuperAdminController::class, 'resetPasswordPeserta'])
        ->name('superadmin.peserta.reset_password');

    Route::get('/profile', [ProfileSuperAdminController::class, 'edit'])
        ->name('superadmin.profile');

    Route::put('/profile', [ProfileSuperAdminController::class, 'update'])
        ->name('superadmin.profile.update');
});


// =====================================================
// ADMIN
// =====================================================
Route::middleware(['auth', 'role:Admin'])->group(function () {

    // ================= DASHBOARD =================
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');


    // ================= DATA MAHASISWA =================
    Route::get('/admin/data', [AdminController::class, 'data'])
        ->name('admin.data');


    // ================= TAMBAH MAHASISWA =================
    Route::get('/admin/mahasiswa/create', [AdminController::class, 'create'])
        ->name('admin.create');

    Route::post('/admin/mahasiswa', [AdminController::class, 'store'])
        ->name('admin.store');


    // ================= DETAIL MAHASISWA =================
    Route::get('/admin/mahasiswa/{id}/detail', [AdminController::class, 'detail'])
        ->name('admin.detail');

    // ================= TERIMA & TOLAK PENDAFTARAN (DENGAN PLOTING PEMBIMBING) =================
    Route::post('/admin/pengajuan/{id}/terima', [AdminController::class, 'terimaPengajuan'])
        ->name('admin.pengajuan.terima');

    Route::post('/admin/pengajuan/{id}/tolak', [AdminController::class, 'tolakPengajuan'])
        ->name('admin.pengajuan.tolak');

    // ================= HAPUS MAHASISWA =================
    Route::delete('/admin/mahasiswa/{id}', [AdminController::class, 'destroy'])
        ->name('admin.destroy');

    // ================= EDIT MAHASISWA =================
    Route::get('/admin/data/{id}/edit', [AdminController::class, 'edit'])
        ->name('admin.edit');
    Route::put('/admin/data/{id}', [AdminController::class, 'update'])
        ->name('admin.update');

    // ================= EXPORT DATA =================
    Route::get('/admin/export', [AdminController::class, 'export'])
        ->name('admin.export');

    // ================= DOCUMENT VERIFICATION =================
    Route::get('/admin/dokumen', [DocumentController::class, 'index'])
        ->name('admin.dokumen');

    Route::post('/admin/dokumen/{id}/upload', [DocumentController::class, 'uploadSuratBalasan'])
        ->name('admin.dokumen.upload');

    Route::get('/admin/dokumen/{id}/lihat', [DocumentController::class, 'lihatSuratBalasan'])
        ->name('admin.dokumen.lihat');

    // ================= SERTIFIKAT =================
    Route::get('/admin/sertifikat', [SertifikatController::class, 'index'])
        ->name('admin.sertifikat');

    Route::post('/admin/sertifikat/{id}/upload', [SertifikatController::class, 'upload'])
        ->name('admin.sertifikat.upload');

    Route::post('/admin/sertifikat/{id}/laporan', [SertifikatController::class, 'uploadLaporan'])
        ->name('admin.laporan.upload');

    Route::post('/admin/sertifikat/{id}/verify-id-card', [SertifikatController::class, 'verifyIdCard'])
        ->name('admin.sertifikat.verify-id-card');

    Route::post('/admin/mahasiswa/{id}/verify-id-card', [AdminController::class, 'verifyIdCard'])
        ->name('admin.id-card.verify');

    // ================= NEW ADMIN ROUTES FOR README FEATURES =================
    // 1. Absensi & Koreksi
    Route::get('/admin/absensi', [AdminAbsensiController::class, 'index'])
        ->name('admin.absensi');
    Route::post('/admin/absensi/koreksi/{id}/review', [AdminAbsensiController::class, 'reviewKoreksi'])
        ->name('admin.absensi.koreksi.review');

    // 2. Logbook Harian
    Route::get('/admin/logbook', [AdminLogbookController::class, 'index'])
        ->name('admin.logbook');
    Route::post('/admin/logbook/{id}/review', [AdminLogbookController::class, 'review'])
        ->name('admin.logbook.review');

    // 3. Cuti & Izin
    Route::get('/admin/cuti', [AdminCutiController::class, 'index'])
        ->name('admin.cuti');
    Route::post('/admin/cuti/{id}/review', [AdminCutiController::class, 'review'])
        ->name('admin.cuti.review');

    // 4. Aduan Masalah
    Route::get('/admin/aduan', [AdminAduanController::class, 'index'])
        ->name('admin.aduan');
    Route::post('/admin/aduan/{id}/reply', [AdminAduanController::class, 'reply'])
        ->name('admin.aduan.reply');

    // 5. Penilaian Harian
    Route::get('/admin/nilai', [AdminNilaiController::class, 'index'])
        ->name('admin.nilai');
    Route::post('/admin/nilai/store', [AdminNilaiController::class, 'store'])
        ->name('admin.nilai.store');

    // 6. Informasi & Pengumuman Perusahaan
    Route::get('/admin/pengumuman', [AdminPengumumanController::class, 'index'])
        ->name('admin.pengumuman');
    Route::post('/admin/pengumuman/store', [AdminPengumumanController::class, 'store'])
        ->name('admin.pengumuman.store');
    Route::post('/admin/pengumuman/company', [AdminPengumumanController::class, 'updateCompany'])
        ->name('admin.pengumuman.company');
    Route::delete('/admin/pengumuman/{id}', [AdminPengumumanController::class, 'destroy'])
        ->name('admin.pengumuman.destroy');

    // 7. Pengelolaan Chatbot FAQ & Log Riwayat
    Route::get('/admin/chatbot', [AdminChatbotController::class, 'index'])
        ->name('admin.chatbot.index');
    Route::post('/admin/chatbot', [AdminChatbotController::class, 'store'])
        ->name('admin.chatbot.store');
    Route::put('/admin/chatbot/{id}', [AdminChatbotController::class, 'update'])
        ->name('admin.chatbot.update');
    Route::delete('/admin/chatbot/{id}', [AdminChatbotController::class, 'destroy'])
        ->name('admin.chatbot.destroy');
    Route::post('/admin/chatbot/{id}/toggle', [AdminChatbotController::class, 'toggleActive'])
        ->name('admin.chatbot.toggle');
    Route::post('/admin/chatbot/clear-logs', [AdminChatbotController::class, 'clearLogs'])
        ->name('admin.chatbot.clear-logs');
});

// =====================================================
// CHATBOT ASISTEN VIRTUAL (MENU-BASED FAQ)
// =====================================================
Route::get('/chatbot/questions', [ChatbotController::class, 'getQuestions'])
    ->name('chatbot.questions');

Route::post('/chatbot/answer', [ChatbotController::class, 'getAnswer'])
    ->name('chatbot.answer');


// =====================================================
// PESERTA MAGANG (PORTAL MAHASISWA)
// =====================================================
Route::middleware(['auth', 'role:Peserta,Mahasiswa'])->group(function () {

    // 1. Dashboard Peserta
    Route::get('/peserta/dashboard', [PesertaController::class, 'dashboard'])
        ->name('peserta.dashboard');

    // 2. Absensi & Koreksi
    Route::get('/peserta/absensi', [PesertaController::class, 'absensi'])
        ->name('peserta.absensi');
    Route::post('/peserta/absensi/check-in', [PesertaController::class, 'doCheckIn'])
        ->name('peserta.absensi.checkin');
    Route::post('/peserta/absensi/check-out', [PesertaController::class, 'doCheckOut'])
        ->name('peserta.absensi.checkout');
    Route::post('/peserta/absensi/koreksi', [PesertaController::class, 'storeKoreksiAbsensi'])
        ->name('peserta.absensi.koreksi');

    // 3. Logbook Harian
    Route::get('/peserta/logbook', [PesertaController::class, 'logbook'])
        ->name('peserta.logbook');
    Route::post('/peserta/logbook/store', [PesertaController::class, 'storeLogbook'])
        ->name('peserta.logbook.store');
    Route::get('/peserta/logbook/{id}/detail', [PesertaController::class, 'detailLogbook'])
        ->name('peserta.logbook.detail');

    // 4. Aduan Masalah
    Route::get('/peserta/aduan', [PesertaController::class, 'aduan'])
        ->name('peserta.aduan');
    Route::post('/peserta/aduan/store', [PesertaController::class, 'storeAduan'])
        ->name('peserta.aduan.store');
    Route::post('/peserta/aduan/{id}/reply', [PesertaController::class, 'replyAduan'])
        ->name('peserta.aduan.reply');

    // 5. Arsip Nilai Harian
    Route::get('/peserta/nilai', [PesertaController::class, 'nilai'])
        ->name('peserta.nilai');

    // 6. Pengajuan Cuti dan Izin
    Route::get('/peserta/cuti', [PesertaController::class, 'cuti'])
        ->name('peserta.cuti');
    Route::post('/peserta/cuti/store', [PesertaController::class, 'storeCuti'])
        ->name('peserta.cuti.store');
    Route::post('/peserta/cuti/{id}/cancel', [PesertaController::class, 'cancelCuti'])
        ->name('peserta.cuti.cancel');

    // 7. Informasi Perusahaan
    Route::get('/peserta/informasi', [PesertaController::class, 'informasi'])
        ->name('peserta.informasi');

    // 8. Notifikasi
    Route::get('/peserta/notifikasi', [PesertaController::class, 'notifikasi'])
        ->name('peserta.notifikasi');
    Route::post('/peserta/notifikasi/{id}/read', [PesertaController::class, 'markNotifRead'])
        ->name('peserta.notifikasi.read');
    Route::post('/peserta/notifikasi/read-all', [PesertaController::class, 'markAllNotifRead'])
        ->name('peserta.notifikasi.readall');

    // 9. Profil
    Route::get('/peserta/profil', [PesertaController::class, 'profil'])
        ->name('peserta.profil');
    Route::post('/peserta/profil/update', [PesertaController::class, 'updateProfil'])
        ->name('peserta.profil.update');
});


// =====================================================
// PEMBIMBING LAPANGAN
// =====================================================
Route::middleware(['auth', 'role:Pembimbing,Pembimbing Lapangan'])->group(function () {

    // 1. Dashboard Pembimbing
    Route::get('/pembimbing/dashboard', [PembimbingController::class, 'dashboard'])
        ->name('pembimbing.dashboard');

    // 2. Monitoring Mahasiswa Bimbingan
    Route::get('/pembimbing/mahasiswa', [PembimbingController::class, 'mahasiswa'])
        ->name('pembimbing.mahasiswa');
    Route::get('/pembimbing/mahasiswa/{id}/detail', [PembimbingController::class, 'detailMahasiswa'])
        ->name('pembimbing.mahasiswa.detail');

    // 3. Monitoring Presensi & Koreksi
    Route::get('/pembimbing/absensi', [PembimbingController::class, 'absensi'])
        ->name('pembimbing.absensi');
    Route::post('/pembimbing/absensi/koreksi/{id}/review', [PembimbingController::class, 'reviewKoreksi'])
        ->name('pembimbing.absensi.koreksi.review');

    // 4. Monitoring Logbook PKL
    Route::get('/pembimbing/logbook', [PembimbingController::class, 'logbook'])
        ->name('pembimbing.logbook');
    Route::post('/pembimbing/logbook/{id}/review', [PembimbingController::class, 'reviewLogbook'])
        ->name('pembimbing.logbook.review');

    // 5. Monitoring Cuti & Izin
    Route::get('/pembimbing/cuti', [PembimbingController::class, 'cuti'])
        ->name('pembimbing.cuti');
    Route::post('/pembimbing/cuti/{id}/review', [PembimbingController::class, 'reviewCuti'])
        ->name('pembimbing.cuti.review');

    // 6. Monitoring Aduan Masalah
    Route::get('/pembimbing/aduan', [PembimbingController::class, 'aduan'])
        ->name('pembimbing.aduan');
    Route::post('/pembimbing/aduan/{id}/reply', [PembimbingController::class, 'replyAduan'])
        ->name('pembimbing.aduan.reply');

    // 7. Evaluasi & Penilaian
    Route::get('/pembimbing/nilai', [PembimbingController::class, 'nilai'])
        ->name('pembimbing.nilai');
    Route::post('/pembimbing/nilai/store', [PembimbingController::class, 'storeNilai'])
        ->name('pembimbing.nilai.store');

    // 8. Profil Pembimbing
    Route::get('/pembimbing/profil', [PembimbingController::class, 'profil'])
        ->name('pembimbing.profil');
    Route::post('/pembimbing/profil/update', [PembimbingController::class, 'updateProfil'])
        ->name('pembimbing.profil.update');
});
