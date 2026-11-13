<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatbotApiController;
use App\Http\Controllers\Api\PembimbingApiController;
use App\Http\Controllers\Api\PesertaApiController;
use App\Http\Controllers\Api\SuperAdminApiController;

/*
|--------------------------------------------------------------------------
| API Routes Monitoring Magang PLTU Adipala
|--------------------------------------------------------------------------
| Digunakan untuk sinkronisasi data antara aplikasi Mobile dan Web
| dengan 1 database backend yang sama.
|
*/

// Auth Routes (Publik)
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

// Chatbot FAQ "Tanya Kami" (publik; token opsional untuk FAQ khusus peserta)
Route::get('/chatbot/questions', [ChatbotApiController::class, 'questions']);
Route::post('/chatbot/answer', [ChatbotApiController::class, 'answer'])->middleware('throttle:60,1');

// Endpoint dengan autentikasi Bearer Token (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth & User Profile
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Portal Peserta API (role sama dengan route web peserta)
    Route::prefix('peserta')->middleware('role:Peserta,Mahasiswa')->group(function () {
        // Dashboard & referensi pilihan form
        Route::get('/dashboard', [PesertaApiController::class, 'dashboard']);
        Route::get('/referensi', [PesertaApiController::class, 'references']);

        // Presensi / Absensi
        Route::get('/absensi/today', [PesertaApiController::class, 'todayAttendance']);
        Route::post('/absensi/check-in', [PesertaApiController::class, 'checkIn']);
        Route::post('/absensi/check-out', [PesertaApiController::class, 'checkOut']);
        Route::get('/absensi/history', [PesertaApiController::class, 'attendanceHistory']);
        Route::get('/absensi/koreksi', [PesertaApiController::class, 'corrections']);
        Route::post('/absensi/koreksi', [PesertaApiController::class, 'storeKoreksiAbsensi']);

        // Logbook
        Route::get('/logbook', [PesertaApiController::class, 'logbooks']);
        Route::get('/logbook/preset', [PesertaApiController::class, 'logbookPreset']);
        Route::post('/logbook/store', [PesertaApiController::class, 'storeLogbook']);
        Route::get('/logbook/{id}', [PesertaApiController::class, 'logbookDetail'])->whereNumber('id');

        // Pengajuan Cuti / Izin
        Route::get('/cuti', [PesertaApiController::class, 'leaveRequests']);
        Route::post('/cuti/store', [PesertaApiController::class, 'storeLeaveRequest']);
        Route::post('/cuti/{id}/cancel', [PesertaApiController::class, 'cancelLeaveRequest'])->whereNumber('id');

        // Aduan Masalah
        Route::get('/aduan', [PesertaApiController::class, 'complaints']);
        Route::post('/aduan/store', [PesertaApiController::class, 'storeComplaint']);
        Route::get('/aduan/{id}', [PesertaApiController::class, 'complaintDetail'])->whereNumber('id');
        Route::post('/aduan/{id}/reply', [PesertaApiController::class, 'replyComplaint'])->whereNumber('id');

        // Nilai Magang
        Route::get('/nilai', [PesertaApiController::class, 'evaluations']);

        // Info Perusahaan
        Route::get('/informasi', [PesertaApiController::class, 'companyInformation']);

        // Notifikasi
        Route::get('/notifikasi', [PesertaApiController::class, 'notifications']);
        Route::post('/notifikasi/{id}/read', [PesertaApiController::class, 'markNotificationRead'])->whereNumber('id');
        Route::post('/notifikasi/read-all', [PesertaApiController::class, 'markAllNotificationsRead']);

        // Profil Peserta
        Route::get('/profil', [PesertaApiController::class, 'profile']);
        Route::post('/profil/update', [PesertaApiController::class, 'updateProfile']);
    });

    // Portal Pembimbing Lapangan API (role sama dengan route web pembimbing)
    Route::prefix('pembimbing')->middleware('role:Pembimbing,Pembimbing Lapangan')->group(function () {
        Route::get('/dashboard', [PembimbingApiController::class, 'dashboard']);
        Route::get('/referensi', [PembimbingApiController::class, 'references']);

        // Mahasiswa bimbingan
        Route::get('/mahasiswa', [PembimbingApiController::class, 'mahasiswa']);
        Route::get('/mahasiswa/{id}', [PembimbingApiController::class, 'mahasiswaDetail'])->whereNumber('id');

        // Presensi & koreksi
        Route::get('/absensi', [PembimbingApiController::class, 'attendances']);
        Route::get('/absensi/koreksi', [PembimbingApiController::class, 'corrections']);
        Route::post('/absensi/koreksi/{id}/review', [PembimbingApiController::class, 'reviewCorrection'])->whereNumber('id');

        // Logbook
        Route::get('/logbook', [PembimbingApiController::class, 'logbooks']);
        Route::get('/logbook/{id}', [PembimbingApiController::class, 'logbookDetail'])->whereNumber('id');
        Route::post('/logbook/{id}/review', [PembimbingApiController::class, 'reviewLogbook'])->whereNumber('id');

        // Cuti / izin
        Route::get('/cuti', [PembimbingApiController::class, 'leaveRequests']);
        Route::post('/cuti/{id}/review', [PembimbingApiController::class, 'reviewLeaveRequest'])->whereNumber('id');

        // Aduan
        Route::get('/aduan', [PembimbingApiController::class, 'complaints']);
        Route::get('/aduan/{id}', [PembimbingApiController::class, 'complaintDetail'])->whereNumber('id');
        Route::post('/aduan/{id}/reply', [PembimbingApiController::class, 'replyComplaint'])->whereNumber('id');

        // Penilaian
        Route::get('/nilai', [PembimbingApiController::class, 'evaluations']);
        Route::post('/nilai/store', [PembimbingApiController::class, 'storeEvaluation']);

        // Profil
        Route::get('/profil', [PembimbingApiController::class, 'profile']);
        Route::post('/profil/update', [PembimbingApiController::class, 'updateProfile']);
    });

    // Portal Admin API (role sama dengan route web admin)
    Route::prefix('admin')->middleware('role:Admin')->group(function () {
        Route::get('/dashboard', [AdminApiController::class, 'dashboard']);
        Route::get('/referensi', [AdminApiController::class, 'references']);

        // Data mahasiswa / pengajuan magang ({id} = id_pengajuan)
        Route::get('/mahasiswa', [AdminApiController::class, 'dataMahasiswa']);
        Route::post('/mahasiswa', [AdminApiController::class, 'storeMahasiswa']);
        Route::get('/mahasiswa/{id}', [AdminApiController::class, 'detailPengajuan'])->whereNumber('id');
        Route::post('/mahasiswa/{id}/update', [AdminApiController::class, 'updateMahasiswa'])->whereNumber('id');
        Route::delete('/mahasiswa/{id}', [AdminApiController::class, 'destroyMahasiswa'])->whereNumber('id');
        Route::post('/pengajuan/{id}/terima', [AdminApiController::class, 'terimaPengajuan'])->whereNumber('id');
        Route::post('/pengajuan/{id}/tolak', [AdminApiController::class, 'tolakPengajuan'])->whereNumber('id');

        // Dokumen surat balasan ({id} = id_pengajuan)
        Route::get('/dokumen', [AdminApiController::class, 'dokumen']);
        Route::post('/dokumen/{id}/upload', [AdminApiController::class, 'uploadSuratBalasan'])->whereNumber('id');

        // Sertifikat, laporan & ID card ({id} = id_mhs)
        Route::get('/sertifikat', [AdminApiController::class, 'sertifikat']);
        Route::post('/sertifikat/{id}/laporan', [AdminApiController::class, 'uploadLaporan'])->whereNumber('id');
        Route::post('/sertifikat/{id}/verify-id-card', [AdminApiController::class, 'verifyIdCard'])->whereNumber('id');
        Route::post('/sertifikat/{id}/upload', [AdminApiController::class, 'uploadSertifikat'])->whereNumber('id');

        // Monitoring (cakupan seluruh mahasiswa)
        Route::get('/absensi', [AdminApiController::class, 'attendances']);
        Route::get('/absensi/koreksi', [AdminApiController::class, 'corrections']);
        Route::post('/absensi/koreksi/{id}/review', [AdminApiController::class, 'reviewCorrection'])->whereNumber('id');
        Route::get('/logbook', [AdminApiController::class, 'logbooks']);
        Route::get('/logbook/{id}', [AdminApiController::class, 'logbookDetail'])->whereNumber('id');
        Route::post('/logbook/{id}/review', [AdminApiController::class, 'reviewLogbook'])->whereNumber('id');
        Route::get('/cuti', [AdminApiController::class, 'leaveRequests']);
        Route::post('/cuti/{id}/review', [AdminApiController::class, 'reviewLeaveRequest'])->whereNumber('id');
        Route::get('/aduan', [AdminApiController::class, 'complaints']);
        Route::get('/aduan/{id}', [AdminApiController::class, 'complaintDetail'])->whereNumber('id');
        Route::post('/aduan/{id}/reply', [AdminApiController::class, 'replyComplaint'])->whereNumber('id');
        Route::get('/nilai', [AdminApiController::class, 'evaluations']);
        Route::post('/nilai/store', [AdminApiController::class, 'storeEvaluation']);

        // Pengumuman & profil perusahaan
        Route::get('/pengumuman', [AdminApiController::class, 'pengumuman']);
        Route::post('/pengumuman/store', [AdminApiController::class, 'storePengumuman']);
        Route::post('/pengumuman/company', [AdminApiController::class, 'updateCompany']);
        Route::delete('/pengumuman/{id}', [AdminApiController::class, 'destroyPengumuman'])->whereNumber('id');

        // Chatbot FAQ
        Route::get('/chatbot', [AdminApiController::class, 'chatbot']);
        Route::post('/chatbot', [AdminApiController::class, 'storeFaq']);
        Route::get('/chatbot/logs', [AdminApiController::class, 'chatbotLogs']);
        Route::post('/chatbot/clear-logs', [AdminApiController::class, 'clearChatbotLogs']);
        Route::put('/chatbot/{id}', [AdminApiController::class, 'updateFaq'])->whereNumber('id');
        Route::delete('/chatbot/{id}', [AdminApiController::class, 'destroyFaq'])->whereNumber('id');
        Route::post('/chatbot/{id}/toggle', [AdminApiController::class, 'toggleFaq'])->whereNumber('id');

        // Profil admin
        Route::get('/profil', [AdminApiController::class, 'profile']);
        Route::post('/profil/update', [AdminApiController::class, 'updateProfile']);
        Route::post('/profil/password', [AdminApiController::class, 'updatePassword']);
    });

    // Portal Super Admin API (role sama dengan route web superadmin)
    Route::prefix('superadmin')->middleware('role:Super Admin')->group(function () {
        Route::get('/dashboard', [SuperAdminApiController::class, 'dashboard']);
        Route::get('/referensi', [SuperAdminApiController::class, 'references']);

        // Kelola akun
        Route::post('/akun', [SuperAdminApiController::class, 'storeAkun']);
        Route::get('/akun/{id}', [SuperAdminApiController::class, 'akunDetail'])->whereNumber('id');
        Route::put('/akun/{id}', [SuperAdminApiController::class, 'updateAkun'])->whereNumber('id');
        Route::delete('/akun/{id}', [SuperAdminApiController::class, 'destroyAkun'])->whereNumber('id');

        // Pembimbing lapangan
        Route::get('/pembimbing', [SuperAdminApiController::class, 'pembimbing']);

        // Peserta magang ({id} = id_mhs)
        Route::get('/peserta', [SuperAdminApiController::class, 'peserta']);
        Route::post('/peserta', [SuperAdminApiController::class, 'storePeserta']);
        Route::get('/peserta/{id}', [SuperAdminApiController::class, 'pesertaDetail'])->whereNumber('id');
        Route::post('/peserta/{id}/update', [SuperAdminApiController::class, 'updatePeserta'])->whereNumber('id');
        Route::delete('/peserta/{id}', [SuperAdminApiController::class, 'destroyPeserta'])->whereNumber('id');
        Route::post('/peserta/{id}/buat-akun', [SuperAdminApiController::class, 'buatAkunPeserta'])->whereNumber('id');
        Route::post('/peserta/{id}/reset-password', [SuperAdminApiController::class, 'resetPasswordPeserta'])->whereNumber('id');

        // Profil
        Route::get('/profil', [SuperAdminApiController::class, 'profile']);
        Route::post('/profil/update', [SuperAdminApiController::class, 'updateProfile']);
    });
});
