<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileSuperAdminController extends Controller
{
    /**
     * Menampilkan halaman profil Superadmin
     */
    public function edit()
    {
        $user = User::findOrFail(Auth::id());

        return view('superadmin.profile', compact('user'));
    }

    /**
     * Memperbarui data profil Superadmin
     */
    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:150',
            ],

            'username' => [
                'required',
                'string',
                'max:150',
                Rule::unique('users', 'username')
                    ->ignore($user->id_users, 'id_users'),
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')
                    ->ignore($user->id_users, 'id_users'),
            ],

            // Password lama hanya wajib kalau ingin mengganti password
            'current_password' => [
                'nullable',
                'string',
                'required_with:password',
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',

            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username tersebut sudah digunakan.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan.',

            'current_password.required_with' =>
                'Password lama wajib diisi jika ingin mengganti password.',

            'password.min' =>
                'Password baru minimal 6 karakter.',

            'password.confirmed' =>
                'Konfirmasi password baru tidak cocok.',
        ]);

        // Update data utama
        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        $user->email = $request->email;

        /*
        |--------------------------------------------------------------------------
        | GANTI PASSWORD
        |--------------------------------------------------------------------------
        */

        if ($request->filled('password')) {

            // Cek password lama
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors([
                        'current_password' => 'Password lama tidak sesuai.'
                    ])
                    ->withInput();
            }

            // Hash password baru
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('superadmin.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
