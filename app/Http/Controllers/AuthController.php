<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // sesuaikan lokasi file login.blade.php
    }
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);

        $loginInput = $request->input('email');
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 2. Cek user ada & aktif
        $user = User::where($fieldType, $loginInput)->first();

        if (!$user) {
            return back()->with('error', 'Akun tidak terdaftar. Pastikan email atau NIM benar.');
        }

        if (!$user->is_active) {
            return back()->with('error', 'Akun Anda nonaktif. Silakan hubungi Administrator.');
        }

        // 3. Attempt Login
        $credentials = [
            $fieldType => $loginInput,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user->update([
                'last_login_at' => now()
            ]);

            // 4. Redirection Berdasarkan Role
            return match (strtolower(trim($user->role))) {
                'super admin'          => redirect()->route('superadmin.dashboard'),
                'admin'                => redirect()->route('admin.dashboard'),
                'peserta', 'mahasiswa' => redirect()->route('peserta.dashboard'),
                default                => redirect()->route('login')->with('error', 'Role akun tidak dikenali.'),
            };
        }

        return back()->with('error', 'Password yang Anda masukkan salah.');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
?>
