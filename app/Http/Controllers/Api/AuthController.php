<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsMonitoringData;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    use FormatsMonitoringData;

    /**
     * Kunci role untuk aplikasi mobile (sama dengan redirect role di login web).
     */
    public static function roleKey(?string $role): ?string
    {
        return match (strtolower(trim($role ?? ''))) {
            'super admin' => 'superadmin',
            'admin' => 'admin',
            'pembimbing', 'pembimbing lapangan' => 'pembimbing',
            'peserta', 'mahasiswa' => 'peserta',
            default => null,
        };
    }

    protected function formatUser(User $user): array
    {
        return [
            'id' => $user->id_users,
            'id_users' => $user->id_users,
            'nama_lengkap' => $user->nama_lengkap,
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'role_key' => static::roleKey($user->role),
            'divisi' => $user->divisi->nama_divisi ?? null,
            'is_active' => (bool) $user->is_active,
            'last_login_at' => static::dateTime($user->last_login_at),
        ];
    }

    protected function mahasiswaFor(User $user): ?array
    {
        if (static::roleKey($user->role) !== 'peserta') {
            return null;
        }

        $mhs = $user->mahasiswa ?? Mahasiswa::where('user_id', $user->id_users)
            ->orWhere('email', $user->email)
            ->orWhere('nim', $user->username)
            ->first();

        return $this->formatMahasiswa($mhs?->load(['universitas', 'jurusan']));
    }

    /**
     * POST /api/login — email atau NIM/username + password, untuk semua role.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:100',
        ]);

        $loginInput = trim($request->input('email'));
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($fieldType, $loginInput)->first();

        if (!$user) {
            return $this->fail('Akun tidak terdaftar. Pastikan email atau NIM benar.', 404);
        }
        if (!$user->is_active) {
            return $this->fail('Akun Anda nonaktif. Silakan hubungi Administrator.', 403);
        }
        if (!Hash::check($request->password, $user->password)) {
            return $this->fail('Password yang Anda masukkan salah.', 401);
        }
        if (!static::roleKey($user->role)) {
            return $this->fail('Role akun tidak dikenali.', 403);
        }

        $user->update(['last_login_at' => now()]);
        $token = $user->createToken($request->input('device_name', 'mobile-api-token'))->plainTextToken;

        $userData = $this->formatUser($user);
        $mahasiswa = $this->mahasiswaFor($user);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $userData,
            'mahasiswa' => $mahasiswa,
            'data' => ['token' => $token, 'user' => $userData, 'mahasiswa' => $mahasiswa],
        ]);
    }

    /** GET /api/user */
    public function user(Request $request)
    {
        $user = $request->user();
        $userData = $this->formatUser($user);
        $mahasiswa = $this->mahasiswaFor($user);

        return response()->json([
            'success' => true,
            'message' => null,
            'user' => $userData,
            'mahasiswa' => $mahasiswa,
            'data' => ['user' => $userData, 'mahasiswa' => $mahasiswa],
        ]);
    }

    /** POST /api/logout — cabut token yang dipakai. */
    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->ok(null, 'Berhasil logout.');
    }
}
