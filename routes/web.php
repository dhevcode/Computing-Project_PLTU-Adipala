<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/login', function () {
    return view('auth.login');
});