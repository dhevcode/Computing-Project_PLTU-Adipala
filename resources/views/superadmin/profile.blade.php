@extends('superadmin.layouts.app')

@section('title', 'Profile Superadmin')
@section('page_title', 'Profile')
@section('page_subtitle', 'Kelola informasi akun dan keamanan password Anda')

@push('styles')
<style>
    /* =========================================
       LAYOUT GRID PROFIL
       ========================================= */
    .profile-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        align-items: start;
    }

    /* =========================================
       STYLING FORM KHUSUS (Seragam dengan Create/Edit)
       ========================================= */
    .form-group { margin-bottom: 24px; }
    .form-group label {
        display: block;
        font-size: 13.5px;
        font-weight: 500;
        color: var(--text);
        margin-bottom: 4px;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"] {
        width: 100%;
        padding: 10px 0;
        font-family: inherit;
        font-size: 14px;
        color: var(--text);
        background: transparent;
        border: none;
        border-bottom: 1.5px solid var(--primary);
        outline: none;
        transition: border-color .2s;
    }

    .form-group input[readonly],
    .form-group input[disabled] {
        color: var(--muted);
        border-bottom-color: var(--border);
        cursor: not-allowed;
    }

    .form-group input::placeholder { color: #a6afb8; }
    .form-group input:not([readonly]):focus { border-bottom: 2px solid var(--primary-dark); }
    .form-group small { display: block; font-size: 11.5px; color: var(--muted); margin-top: 6px; }

    .section-divider {
        height: 1px;
        background: var(--border);
        margin: 32px 0 24px;
    }

    .password-section h4 {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 4px;
    }
    .password-section p {
        font-size: 12.5px;
        color: var(--muted);
        margin-bottom: 20px;
    }

    /* Tombol Aksi */
    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 32px;
        padding-top: 20px;
    }
    .btn-cancel, .btn-save {
        padding: 12px 20px;
        font-size: 13.5px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all .2s;
    }
    .btn-cancel { background: #fff; color: var(--text); border: 1.5px solid var(--border); }
    .btn-cancel:hover { background: #f4f7f9; }
    .btn-save { background: var(--primary); color: #fff; border: 1.5px solid var(--primary); }
    .btn-save:hover { background: var(--primary-dark); }

    /* =========================================
       ACCOUNT INFO CARD
       ========================================= */
    .account-info {
        padding: 32px 24px;
        text-align: center;
    }
    .account-avatar {
        width: 80px;
        height: 80px;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: var(--primary-soft);
        color: var(--primary);
        font-size: 32px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .account-info h3 {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .role-badge {
        display: inline-block;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
        color: #8a6500;
        background: var(--accent-soft);
        border-radius: 999px;
        margin-bottom: 24px;
    }

    .info-list { text-align: left; }
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid var(--page);
    }
    .info-item:last-child { border-bottom: none; padding-bottom: 0; }

    .info-item span {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--muted);
    }
    .info-item span i { font-size: 14px; width: 16px; text-align: center; }
    .info-item strong { font-size: 13.5px; font-weight: 600; text-align: right; }

    .status-active { color: var(--success-text) !important; background: var(--success-bg); padding: 4px 10px; border-radius: 6px; font-size: 12px !important; }
    .status-inactive { color: var(--muted) !important; background: #eceff2; padding: 4px 10px; border-radius: 6px; font-size: 12px !important; }

    /* =========================================
       RESPONSIVE
       ========================================= */
    @media (max-width: 992px) {
        .profile-grid { grid-template-columns: 1fr; }
        .account-card { order: -1; } /* Kartu info ditaruh di atas saat mode mobile */
    }

    @media (max-width: 760px) {
        .button-group { flex-direction: column-reverse; }
        .btn-cancel, .btn-save { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div style="margin-bottom: 4px;">{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="profile-grid">

        <!-- KIRI: FORM PROFIL -->
        <div class="card profile-card">
            <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
                <div class="card-title-group" style="display: flex; gap: 14px; align-items: center;">
                    <div class="avatar-circle" style="background: var(--primary-soft); color: var(--primary); font-size: 16px; width: 42px; height: 42px;">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 18px;">Informasi Profil</h3>
                        <p style="font-size: 13px;">Perbarui informasi akun dan keamanan password Anda.</p>
                    </div>
                </div>
            </div>

            <div style="padding: 24px;">
                <form action="{{ route('superadmin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" value="{{ $user->role }}" readonly disabled>
                        <small>Role tidak dapat diubah melalui halaman ini.</small>
                    </div>

                    <div class="section-divider"></div>

                    <div class="password-section">
                        <h4><i class="fa-solid fa-lock"></i> Ganti Password</h4>
                        <p>Kosongkan bagian password jika tidak ingin mengganti password Anda.</p>

                        <div class="form-group">
                            <label for="current_password">Password Lama</label>
                            <input type="password" id="current_password" name="current_password" placeholder="Masukkan password lama">
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" id="password" name="password" placeholder="Masukkan password baru">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="button-group">
                        <a href="{{ route('superadmin.dashboard') }}" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KANAN: INFO AKUN -->
        <div class="card account-card">
            <div class="card-header" style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                <div class="card-title-group">
                    <h3 style="font-size: 15px;"><i class="fa-solid fa-shield-halved" style="margin-right: 8px;"></i>Informasi Akun</h3>
                </div>
            </div>

            <div class="account-info">
                <div class="account-avatar">
                    {{ strtoupper(substr($user->nama_lengkap ?? 'S', 0, 1)) }}
                </div>
                <h3>{{ $user->nama_lengkap }}</h3>
                <span class="role-badge">{{ $user->role }}</span>

                <div class="info-list">
                    <div class="info-item">
                        <span><i class="fa-solid fa-user"></i> Username</span>
                        <strong>{{ $user->username }}</strong>
                    </div>

                    <div class="info-item">
                        <span><i class="fa-solid fa-envelope"></i> Email</span>
                        <strong>{{ $user->email }}</strong>
                    </div>

                    <div class="info-item">
                        <span><i class="fa-solid fa-circle"></i> Status</span>
                        @if($user->is_active)
                            <strong class="status-active">Aktif</strong>
                        @else
                            <strong class="status-inactive">Nonaktif</strong>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
