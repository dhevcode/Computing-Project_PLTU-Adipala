@extends('admin.layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Pengaturan Profil')
@section('page_subtitle', 'Kelola informasi akun dan perbarui kata sandi Anda')

@push('styles')
<style>
    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; padding: 24px; }
    .card-header { margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--page); display: flex; align-items: center; gap: 14px; }
    .avatar-circle { background: var(--primary-soft); color: var(--primary); font-size: 20px; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    
    .card-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0 0 4px 0; }
    .card-header p { font-size: 12px; color: var(--muted); margin: 0; }

    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 12.5px; font-weight: 600; color: var(--muted); margin-bottom: 6px; }
    
    .input-box { width: 100%; padding: 10px 0; font-family: inherit; font-size: 13.5px; color: var(--text); background: transparent; border: none; border-bottom: 1.5px solid var(--primary); outline: none; transition: 0.2s; }
    .input-box:focus { border-bottom: 2px solid var(--primary-dark); }
    .input-box:disabled { color: var(--muted); border-bottom-style: dashed; cursor: not-allowed; }

    .btn-primary { background: var(--primary); color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-family: inherit; transition: 0.2s; }
    .btn-primary:hover { background: var(--primary-dark); }

    @media (max-width: 760px) {
        .form-grid { grid-template-columns: 1fr; gap: 0; }
        .btn-primary { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

    @if(session('success'))
        <div class="alert alert-success animate-fade-up">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="alert alert-error animate-fade-up" style="background: #FEF2F2; color: #991B1B; border-color: #FECACA;">
            <i class="fa-solid fa-circle-xmark"></i>
            <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
    @endif

    <div class="form-grid" style="align-items: start;">
        
        <!-- CARD 1: INFORMASI PROFIL -->
        <div class="card animate-fade-up delay-1">
            <div class="card-header">
                <div class="avatar-circle">
                    <i class="fa-regular fa-id-badge"></i>
                </div>
                <div>
                    <h3>Informasi Pribadi</h3>
                    <p>Perbarui nama dan alamat email Anda.</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Role Akses</label>
                    <input type="text" class="input-box" value="Administrator" disabled>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap <span style="color: var(--error-text);">*</span></label>
                    <input type="text" name="nama_lengkap" class="input-box" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                </div>

                <div class="form-group">
                    <label>Alamat Email <span style="color: var(--error-text);">*</span></label>
                    <input type="email" name="email" class="input-box" value="{{ old('email', $user->email) }}" required>
                </div>

                <div style="margin-top: 24px; text-align: right;">
                    <button type="submit" class="btn-primary">
                        <i class="fa-regular fa-floppy-disk"></i> Simpan Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- CARD 2: GANTI PASSWORD -->
        <div class="card animate-fade-up delay-2">
            <div class="card-header">
                <div class="avatar-circle" style="background: #FFFBEB; color: #D97706;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h3>Keamanan & Kata Sandi</h3>
                    <p>Gunakan kombinasi minimal 8 karakter rahasia.</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Password Saat Ini <span style="color: var(--error-text);">*</span></label>
                    <input type="password" name="current_password" class="input-box" placeholder="Masukkan password lama Anda" required>
                </div>

                <div class="form-group">
                    <label>Password Baru <span style="color: var(--error-text);">*</span></label>
                    <input type="password" name="password" class="input-box" placeholder="Minimal 8 karakter" required minlength="8">
                </div>

                <div class="form-group">
                    <label>Ulangi Password Baru <span style="color: var(--error-text);">*</span></label>
                    <input type="password" name="password_confirmation" class="input-box" placeholder="Ketik ulang password baru" required minlength="8">
                </div>

                <div style="margin-top: 24px; text-align: right;">
                    <button type="submit" class="btn-primary" style="background: #D97706;">
                        <i class="fa-solid fa-key"></i> Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>

    </div>

@endsection