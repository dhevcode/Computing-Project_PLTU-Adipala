<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\ProfileSuperAdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// =====================================================
// ROUTE HALAMAN UTAMA
// =====================================================
Route::get('/', function () {

    if (Auth::check()) {

        $role = strtolower(trim(Auth::user()->role));

        return match ($role) {
            'super admin' => redirect()->route('superadmin.dashboard'),
            'admin'       => redirect()->route('admin.dashboard'),
            default       => redirect()->route('login'),
        };
    }

    return redirect()->route('login');
});


// =====================================================
// GUEST ROUTES
// =====================================================
Route::middleware(['guest'])->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');
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
    Route::get(
        '/admin/dokumen',
        [DocumentController::class, 'index']
    )->name('admin.dokumen');

    Route::post(
        '/admin/dokumen/{id}/upload',
        [DocumentController::class, 'uploadSuratBalasan']
    )->name('admin.dokumen.upload');

    Route::get(
        '/admin/dokumen/{id}/lihat',
        [DocumentController::class, 'lihatSuratBalasan']
    )->name('admin.dokumen.lihat');

    // ================= SERTIFIKAT =================
Route::get(
    '/admin/sertifikat',
    [SertifikatController::class, 'index']
)->name('admin.sertifikat');

Route::post(
    '/admin/sertifikat/{id}/upload',
    [SertifikatController::class, 'upload']
)->name('admin.sertifikat.upload');

Route::post(
    '/admin/sertifikat/{id}/laporan',
    [SertifikatController::class, 'uploadLaporan']
)->name('admin.laporan.upload');

Route::post(
    '/admin/sertifikat/{id}/verify-id-card',
    [SertifikatController::class, 'verifyIdCard']
)->name('admin.sertifikat.verify-id-card');

// ================= LAPORAN MAHASISWA =================
Route::post(
    '/admin/laporan/{id}/upload',
    [SertifikatController::class, 'uploadLaporan']
)->name('admin.laporan.upload');

Route::post(
    '/admin/mahasiswa/{id}/verify-id-card',
    [AdminController::class, 'verifyIdCard']
)->name('admin.id-card.verify');
});

