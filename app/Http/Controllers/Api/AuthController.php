<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa;

class AuthController extends Controller
{
    /**
     * Login API untuk Web dan Mobile
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $request->input('email');
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($fieldType, $loginInput)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak terdaftar. Periksa email atau NIM / username Anda.',
            ], 404);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda nonaktif. Silakan hubungi Administrator.',
            ], 403);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password yang Anda masukkan salah.',
            ], 401);
        }

        // Update waktu login terakhir
        $user->update(['last_login_at' => now()]);

        // Buat token autentikasi Sanctum
        $token = $user->createToken('mobile-api-token')->plainTextToken;

        // Ambil data profil mahasiswa jika role peserta
        $mahasiswa = null;
        if (in_array(strtolower(trim($user->role)), ['peserta', 'mahasiswa'])) {
            $mahasiswa = Mahasiswa::with(['universitas', 'jurusan', 'latestPengajuan.divisi'])
                ->where('user_id', $user->id_users)
                ->orWhere('id_mhs', $user->id_mhs)
                ->first();
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token'   => $token,
            'token_type' => 'Bearer',
            'user'    => [
                'id_users'     => $user->id_users,
                'nama_lengkap' => $user->nama_lengkap,
                'username'     => $user->username,
                'email'        => $user->email,
                'phone'        => $user->phone,
                'role'         => $user->role,
                'is_active'    => $user->is_active,
                'last_login_at'=> $user->last_login_at,
            ],
            'mahasiswa' => $mahasiswa,
        ]);
    }

    /**
     * Ambil data user yang sedang login beserta relasinya
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $mahasiswa = null;

        if (in_array(strtolower(trim($user->role)), ['peserta', 'mahasiswa'])) {
            $mahasiswa = Mahasiswa::with(['universitas', 'jurusan', 'latestPengajuan.divisi'])
                ->where('user_id', $user->id_users)
                ->orWhere('id_mhs', $user->id_mhs)
                ->first();
        }

        return response()->json([
            'success'   => true,
            'user'      => $user,
            'mahasiswa' => $mahasiswa,
        ]);
    }

    /**
     * Logout API (Revoke token)
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout.',
        ]);
    }
}

