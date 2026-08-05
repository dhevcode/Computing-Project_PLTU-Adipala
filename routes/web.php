<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route untuk halaman utama (opsional: langsung redirect ke login)
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'Super Admin'
            ? redirect()->route('superadmin.dashboard')
            : redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
});

// Guest Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

<<<<<<< HEAD
    // Super Admin Group
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/superadmin/create', [SuperAdminController::class, 'create'])->name('superadmin.create');
    Route::post('/superadmin/store', [SuperAdminController::class, 'store'])->name('superadmin.store');
});
=======
Route::get('/mahasiswa/edit', function () {
    return view('mahasiswa.edit');
})->name('mahasiswa.edit');

Route::get('/superadmin', function () {
    return view('superadmin.dashboard');
})->name('superadmin.dashboard');
>>>>>>> 1ac5fdd82e5ef02be6b558f8e7553952f5a7912d

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
// Tambahkan route ini:
    Route::get('/admin/mahasiswa/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/mahasiswa', [AdminController::class, 'store'])->name('admin.store');
});
