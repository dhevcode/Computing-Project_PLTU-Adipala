<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route Halaman Utama (Mencegah Infinite Redirect Loop)
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

// Guest Routes (Khusus user yang BELUM login)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

// Authenticated Routes (Khusus user yang SUDAH login)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::redirect('/superadmin', '/superadmin/dashboard');

// Super Admin Group (Khusus role Super Admin)
Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/superadmin/create', [SuperAdminController::class, 'create'])->name('superadmin.create');
    Route::post('/superadmin/store', [SuperAdminController::class, 'store'])->name('superadmin.store');
    Route::get('/superadmin/edit/{id}', [SuperAdminController::class, 'edit'])->name('superadmin.edit');
    Route::put('/superadmin/update/{id}', [SuperAdminController::class, 'update'])->name('superadmin.update');
    Route::delete('/superadmin/delete/{id}', [SuperAdminController::class, 'destroy'])->name('superadmin.destroy');
});

// Admin Group (Khusus role Admin)
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/mahasiswa/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/mahasiswa', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/mahasiswa/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/mahasiswa/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/mahasiswa/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
    Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');
});

Route::view('/admin/data', 'admin.data')->name('admin.data');
Route::view('/admin/dokumen', 'admin.dokumen')->name('admin.dokumen');