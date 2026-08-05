<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Route untuk halaman utama
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Guest Routes
Route::middleware(['guest'])->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::redirect('/superadmin', '/superadmin/dashboard');

// Super Admin Group (Only Super Admin role)
Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/superadmin/create', [SuperAdminController::class, 'create'])->name('superadmin.create');
    Route::post('/superadmin/store', [SuperAdminController::class, 'store'])->name('superadmin.store');
    Route::get('/superadmin/edit/{id}', [SuperAdminController::class, 'edit'])->name('superadmin.edit');
    Route::put('/superadmin/update/{id}', [SuperAdminController::class, 'update'])->name('superadmin.update');
    Route::delete('/superadmin/delete/{id}', [SuperAdminController::class, 'destroy'])->name('superadmin.destroy');
});

// Admin Group (Only Admin role)
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/mahasiswa/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/mahasiswa', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/mahasiswa/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/mahasiswa/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/mahasiswa/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
    Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');
});

