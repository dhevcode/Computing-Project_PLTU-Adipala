<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function index()
    {
        // Mengambil data admin beserta relasi divisinya
        $admins = User::with('divisi')
                    ->where('role', 'Admin')
                    ->latest()
                    ->get();

        return view('superadmin.dashboard', compact('admins'));
    }

    public function create()
    {
        $divisi = Divisi::all();
        
        return view('superadmin.create', compact('divisi'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Utama
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:100|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
            'role'         => 'required|in:Admin,Super Admin',
            'id_divisi'    => 'required',
        ]);

        $idDivisi = null;

        // 2. Jika Memilih "Tambah Divisi Baru"
        if ($request->id_divisi === 'new') {
            $request->validate([
                'nama_divisi_baru' => 'required|string|max:100|unique:divisi,nama_divisi',
            ], [
                'nama_divisi_baru.required' => 'Nama divisi baru wajib diisi.',
                'nama_divisi_baru.unique'   => 'Nama divisi tersebut sudah ada.',
            ]);

            // Buat divisi baru (hanya nama_divisi sesuai struktur database)
            $divisiBaru = Divisi::create([
                'nama_divisi' => $request->nama_divisi_baru,
            ]);

            $idDivisi = $divisiBaru->id_divisi;
        } else {
            $idDivisi = $request->id_divisi;
        }

        // 3. Simpan User/Admin ke Database
        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'id_divisi'    => $idDivisi,
            'role'         => $request->role,
            'is_active'    => 1,
            'created_by'   => Auth::id() ?? 1,
        ]);

        return redirect()->route('superadmin.dashboard')
                         ->with('success', 'Data Admin berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $admin = User::findOrFail($id);
        $divisi = Divisi::all();
        
        return view('superadmin.edit', compact('admin', 'divisi'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:100|unique:users,username,' . $admin->id_users . ',id_users',
            'email'        => 'required|email|unique:users,email,' . $admin->id_users . ',id_users',
            'password'     => 'nullable|min:6',
            'role'         => 'required|in:Admin,Super Admin',
            'id_divisi'    => 'required',
            'is_active'    => 'required|in:0,1',
        ]);

        $idDivisi = null;

        if ($request->id_divisi === 'new') {
            $request->validate([
                'nama_divisi_baru' => 'required|string|max:100|unique:divisi,nama_divisi',
            ], [
                'nama_divisi_baru.required' => 'Nama divisi baru wajib diisi.',
                'nama_divisi_baru.unique'   => 'Nama divisi tersebut sudah ada.',
            ]);

            $divisiBaru = Divisi::create([
                'nama_divisi' => $request->nama_divisi_baru,
            ]);

            $idDivisi = $divisiBaru->id_divisi;
        } else {
            $idDivisi = $request->id_divisi;
        }

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'id_divisi'    => $idDivisi,
            'role'         => $request->role,
            'is_active'    => $request->is_active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('superadmin.dashboard')
                         ->with('success', 'Data Admin berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();

        return redirect()->route('superadmin.dashboard')
                         ->with('success', 'Data Admin berhasil dihapus!');
    }
}