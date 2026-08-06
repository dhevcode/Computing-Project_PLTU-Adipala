<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Jika belum login, lempar ke halaman login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // 2. Normalisasi string role user dan role yang diperbolehkan
        $userRole = strtolower(trim($request->user()->role));
        $allowedRoles = array_map(fn($role) => strtolower(trim($role)), $roles);

        // 3. Cek apakah role user terdaftar
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}