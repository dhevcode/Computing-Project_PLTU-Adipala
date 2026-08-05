<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboardadmin', function () {
    return view('dashboard');
});

Route::get('/mahasiswa/create', function () {
    return view('mahasiswa.create');
})->name('mahasiswa.create');