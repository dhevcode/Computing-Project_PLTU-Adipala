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
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cek apakah user ada dan is_active = 1
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak terdaftar.');
        }

        if (!$user->is_active) {
            return back()->with('error', 'Akun Anda nonaktif. Silakan hubungi Super Admin.');
        }

        // 3. Proses Attempt Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Update timestamp last_login_at
            $user->update([
                'last_login_at' => now()
            ]);

            // 4. Redirection Berdasarkan Role
            if ($user->role === 'Super Admin') {
                return redirect()->route('superadmin.dashboard');
            } else {
                // Ubah dari 'dashboard' menjadi 'admin.dashboard'
                return redirect()->route('admin.dashboard');
            }
        }

        // Jika password salah
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