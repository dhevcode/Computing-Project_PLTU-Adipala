@extends('pembimbing.layouts.app')

@section('title', 'Profil Pembimbing Lapangan')
@section('page_title', 'Profil & Pengaturan Akun')
@section('page_subtitle', 'Kelola data personal, informasi kontak, dan keamanan kredensial portal pembimbing Anda.')

@push('styles')
<style>
    /* ================= CARDS & LAYOUT ================= */
    .profile-grid { display: grid; grid-template-columns: 1fr 2.2fr; gap: 24px; align-items: start; }
    
    .card { background: var(--bg, #ffffff); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border, #e2e8f0); overflow: hidden; padding: 24px; }
    
    .card-header-top { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border, #e2e8f0); padding-bottom: 16px; margin-bottom: 20px; }
    .card-title-wrap { display: flex; align-items: center; gap: 12px; }
    .card-title-wrap i { font-size: 20px; color: var(--primary, #0A5F63); }
    .card-title-wrap h3 { font-size: 16px; font-weight: 700; color: var(--text, #0f172a); margin: 0; }

    /* ================= AVATAR & STATS ================= */
    .avatar-wrapper { text-align: center; margin-bottom: 20px; }
    .avatar-circle-lg { 
        width: 88px; height: 88px; border-radius: 50%; 
        background: var(--primary-soft, #ECFDF5); color: var(--primary, #0A5F63); 
        font-size: 32px; font-weight: 800; display: flex; align-items: center; justify-content: center; 
        margin: 0 auto 16px; border: 2px solid var(--border, #e2e8f0); box-shadow: 0 4px 12px rgba(10, 95, 99, 0.1);
    }
    .profile-name { font-size: 18px; font-weight: 700; color: var(--text, #0f172a); margin: 0 0 4px 0; }
    .profile-email { color: var(--muted, #64748b); font-size: 13px; margin: 0 0 12px 0; }
    
    .badge { padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; }
    .badge-divisi { background: #EFF6FF; color: #2563EB; }
    .badge-role { background: #FEF3C7; color: #D97706; margin-bottom: 16px; }

    .mini-stats { display: flex; justify-content: space-around; text-align: center; padding-top: 20px; border-top: 1px dashed var(--border, #e2e8f0); }
    .mini-stat-value { font-size: 24px; font-weight: 800; color: var(--primary, #0A5F63); line-height: 1; margin-bottom: 4px; }
    .mini-stat-label { font-size: 11.5px; color: var(--muted, #64748b); font-weight: 600; text-transform: uppercase; }
    .mini-stat-icon-success { color: #10B981; font-size: 24px; margin-bottom: 4px; }

    /* ================= FORMS & BUTTONS ================= */
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text, #0f172a); margin-bottom: 8px; }
    .form-control { width: 100%; height: 42px; padding: 0 14px; border-radius: 8px; border: 1.5px solid var(--border, #e2e8f0); outline: none; font-size: 13.5px; transition: 0.2s; background: #fff; font-family: inherit; }
    .form-control:focus { border-color: var(--primary, #0A5F63); box-shadow: 0 0 0 3px rgba(10, 95, 99, 0.1); }
    .form-control:disabled { background: #F8FAFC; color: var(--muted, #64748b); cursor: not-allowed; }
    
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    .btn-submit { padding: 10px 20px; font-size: 13.5px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; transition: 0.2s; background: var(--primary, #0A5F63); color: #fff; display: inline-flex; align-items: center; gap: 8px; justify-content: center; }
    .btn-submit:hover { background: #07474a; }

    /* ================= ALERTS ================= */
    .alert { padding: 14px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 10px; border: 1px solid transparent; }
    .alert-success { background: #ECFDF5; color: #065F46; border-color: #A7F3D0; }
    .alert-error { background: #FEF2F2; color: #991B1B; border-color: #FECACA; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 992px) {
        .profile-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
        .form-row { grid-template-columns: 1fr; gap: 0; }
        .btn-submit { width: 100%; }
    }
</style>
@endpush

@section('content')

    @if(session('success'))
        <div class="alert alert-success animate-fade-up">
            <i class="fa-solid fa-circle-check" style="font-size: 16px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error animate-fade-up">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 16px;"></i>
            <div>
                <ul style="margin: 0; padding-left: 16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="profile-grid">
        
        <!-- CARD RINGKASAN PEMBIMBING -->
        <div class="card animate-fade-up delay-1">
            <div class="avatar-wrapper">
                <div class="avatar-circle-lg">
                    {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                </div>
                <h2 class="profile-name">{{ $user->nama_lengkap }}</h2>
                <p class="profile-email">{{ $user->email }}</p>
                
                <span class="badge badge-role">
                    <i class="fa-solid fa-user-shield"></i> {{ ucfirst($user->role) }}
                </span>
                <br>
                <span class="badge badge-divisi">
                    <i class="fa-solid fa-building"></i> Divisi: {{ $user->divisi->nama_divisi ?? 'PLTU Adipala' }}
                </span>
            </div>

            <div class="mini-stats">
                <div>
                    <div class="mini-stat-value">{{ $totalBimbingan ?? 0 }}</div>
                    <div class="mini-stat-label">Bimbingan</div>
                </div>
                <div>
                    <div class="mini-stat-icon-success"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="mini-stat-label">Akun Aktif</div>
                </div>
            </div>
        </div>

        <!-- FORM EDIT PROFIL & PASSWORD -->
        <div class="card animate-fade-up delay-2">
            <div class="card-header-top">
                <div class="card-title-wrap">
                    <i class="fa-solid fa-user-pen"></i>
                    <h3>Perbarui Data Akun & Sandi</h3>
                </div>
            </div>

            <form method="POST" action="{{ route('pembimbing.profil.update') }}">
                @csrf
                @method('PUT') <!-- Tambahkan jika route menggunakan method PUT -->

                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color: #DC2626;">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Username / ID Karyawan</label>
                        <input type="text" class="form-control" value="{{ $user->username }}" disabled title="Username / ID tidak dapat diubah">
                        <small style="color: var(--muted, #64748b); font-size: 11px; margin-top: 4px; display: block;">Username login utama tidak dapat diubah.</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email Perusahaan / Pribadi <span style="color: #DC2626;">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}" placeholder="Contoh: 08123456789">
                    </div>
                </div>

                <hr style="border: none; border-top: 1px dashed var(--border, #e2e8f0); margin: 24px 0 20px 0;">

                <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-lock" style="color: var(--primary, #0A5F63); font-size: 16px;"></i>
                    <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: var(--primary, #0A5F63);">Ganti Password Akun (Opsional)</h4>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Password Saat Ini (Wajib diisi jika ingin mengganti sandi baru)</label>
                    <input type="password" name="old_password" class="form-control" placeholder="Masukkan password saat ini...">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter...">
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru...">
                    </div>
                </div>

                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
        
    </div>

@endsection