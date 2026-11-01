<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PesertaApiController;

/*
|--------------------------------------------------------------------------
| API Routes Monitoring Magang PLTU Adipala
|--------------------------------------------------------------------------
| Digunakan untuk sinkronisasi data antara aplikasi Mobile dan Web
| dengan 1 database backend yang sama.
|
*/

// Auth Routes (Publik)
Route::post('/login', [AuthController::class, 'login']);

// Endpoint dengan autentikasi Bearer Token (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth & User Profile
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Portal Peserta API
    Route::prefix('peserta')->group(function () {
        // Dashboard
        Route::get('/dashboard', [PesertaApiController::class, 'dashboard']);

        // Presensi / Absensi
        Route::get('/absensi/today', [PesertaApiController::class, 'todayAttendance']);
        Route::post('/absensi/check-in', [PesertaApiController::class, 'checkIn']);
        Route::post('/absensi/check-out', [PesertaApiController::class, 'checkOut']);
        Route::get('/absensi/history', [PesertaApiController::class, 'attendanceHistory']);
        Route::post('/absensi/koreksi', [PesertaApiController::class, 'storeKoreksiAbsensi']);

        // Logbook
        Route::get('/logbook', [PesertaApiController::class, 'logbooks']);
        Route::post('/logbook/store', [PesertaApiController::class, 'storeLogbook']);

        // Pengajuan Cuti / Izin
        Route::get('/cuti', [PesertaApiController::class, 'leaveRequests']);
        Route::post('/cuti/store', [PesertaApiController::class, 'storeLeaveRequest']);

        // Aduan Masalah
        Route::get('/aduan', [PesertaApiController::class, 'complaints']);
        Route::post('/aduan/store', [PesertaApiController::class, 'storeComplaint']);

        // Nilai Magang
        Route::get('/nilai', [PesertaApiController::class, 'evaluations']);

        // Info Perusahaan
        Route::get('/informasi', [PesertaApiController::class, 'companyInformation']);

        // Notifikasi
        Route::get('/notifikasi', [PesertaApiController::class, 'notifications']);

        // Profil Peserta
        Route::post('/profil/update', [PesertaApiController::class, 'updateProfile']);
    });
});
