@extends('pembimbing.layout')

@section('title', 'Profil Pembimbing Lapangan')

@section('content')
<div class="page-header-card">
    <div class="page-header-content">
        <div class="page-header-icon">
            <i class="fa-regular fa-id-badge"></i>
        </div>
        <div>
            <h1>Profil Pembimbing Lapangan</h1>
            <p>Kelola data akun personal, informasi kontak, dan keamanan kredensial portal pembimbing Anda.</p>
        </div>
    </div>
    <span class="badge-mentor-scope">
        <i class="fa-solid fa-user-shield"></i>
        {{ $user->role }}
    </span>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Card Ringkasan Pembimbing -->
    <div class="card-box" style="text-align: center;">
        <div style="width: 84px; height: 84px; border-radius: 50%; background: linear-gradient(135deg, #0A5F63, #0D8288); color: #fff; font-size: 32px; font-weight: 800; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; box-shadow: var(--shadow-glow);">
            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
        </div>

        <h2 style="font-size: 18px; font-weight: 700; color: var(--text-dark);">{{ $user->nama_lengkap }}</h2>
        <p style="color: var(--text-muted); font-size: 13px; margin-top: 2px;">{{ $user->email }}</p>

        <div style="margin-top: 14px;">
            <span class="badge" style="background: #E0F2FE; color: #0284C7; font-size: 12px; padding: 6px 12px;">
                <i class="fa-solid fa-building"></i> Divisi: {{ $user->divisi->nama_divisi ?? 'PLTU Adipala' }}
            </span>
        </div>

        <hr style="border: none; border-top: 1px solid var(--border-color); margin: 20px 0;">

        <div style="display: flex; justify-content: space-around; text-align: center;">
            <div>
                <div style="font-size: 24px; font-weight: 800; color: #0A5F63;">{{ $totalBimbingan }}</div>
                <div style="font-size: 11px; color: var(--text-muted); font-weight: 500;">Peserta Bimbingan</div>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 800; color: #10B981;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div style="font-size: 11px; color: var(--text-muted); font-weight: 500;">Status Akun Aktif</div>
            </div>
        </div>
    </div>

    <!-- Form Edit Profil & Password -->
    <div class="card-box">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-user-pen"></i> Perbarui Data Akun & Password
            </div>
        </div>

        <form method="POST" action="{{ route('pembimbing.profil.update') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" class="form-control" style="width: 100%;" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Username</label>
                    <input type="text" class="form-control" style="width: 100%; background: #F8FAFC;" value="{{ $user->username }}" disabled>
                    <small style="color: var(--text-light); font-size: 11px;">Username login tidak dapat diubah.</small>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Email Perusahaan / Kontak *</label>
                    <input type="email" name="email" class="form-control" style="width: 100%;" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" class="form-control" style="width: 100%;" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789">
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid var(--border-color); margin: 24px 0;">

            <h4 style="font-size: 14px; font-weight: 700; color: #0A5F63; margin-bottom: 14px;">
                <i class="fa-solid fa-lock"></i> Ganti Password Akun (Opsional)
            </h4>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Password Saat Ini (Wajib jika ingin mengganti password)</label>
                <input type="password" name="old_password" class="form-control" style="width: 100%;" placeholder="Masukkan password saat ini...">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Password Baru</label>
                    <input type="password" name="password" class="form-control" style="width: 100%;" placeholder="Minimal 6 karakter...">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" style="width: 100%;" placeholder="Ulangi password baru...">
                </div>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
