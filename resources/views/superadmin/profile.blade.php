<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile Superadmin - Monitoring PKL PLTU</title>

    <link rel="icon"
          type="image/png"
          href="{{ asset('assets/image/logo-sie.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet"
          href="{{ asset('css/superadmin/profile.css') }}">
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <div class="header-left">
            <div class="logo-box">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
            </div>
            <div class="header-title">
                <h2>Profile Superadmin <span>Superadmin</span></h2>
                <p>Kelola informasi akun dan keamanan password Anda</p>
            </div>
        </div>
        <div class="header-right">
            <a href="{{ route('superadmin.dashboard') }}"
               class="btn-header-action">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </header>

    <!-- CONTENT -->
    <div class="content">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif


        @if($errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>

                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif


        <div class="profile-grid">

            <!-- PROFILE CARD -->
            <div class="card profile-card">

                <div class="card-header">
                    <div class="card-title-group">

                        <h3>
                            <i class="fa-solid fa-user"></i>
                            Informasi Profil
                        </h3>

                        <p>
                            Perbarui informasi akun Superadmin.
                        </p>

                    </div>
                </div>


                <div class="card-body">

                    <form action="{{ route('superadmin.profile.update') }}"
                          method="POST">

                        @csrf
                        @method('PUT')


                        <!-- NAMA -->
                        <div class="form-group">

                            <label for="nama_lengkap">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                id="nama_lengkap"
                                name="nama_lengkap"
                                value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                                required
                            >

                        </div>


                        <!-- USERNAME -->
                        <div class="form-group">

                            <label for="username">
                                Username
                            </label>

                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username', $user->username) }}"
                                required
                            >

                        </div>


                        <!-- EMAIL -->
                        <div class="form-group">

                            <label for="email">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                            >

                        </div>


                        <!-- ROLE -->
                        <div class="form-group">

                            <label>
                                Role
                            </label>

                            <input
                                type="text"
                                value="{{ $user->role }}"
                                readonly
                                disabled
                            >

                            <small>
                                Role Superadmin tidak dapat diubah melalui halaman ini.
                            </small>

                        </div>


                        <div class="section-divider"></div>


                        <!-- PASSWORD -->
                        <div class="password-section">

                            <h4>
                                <i class="fa-solid fa-lock"></i>
                                Ganti Password
                            </h4>

                            <p>
                                Kosongkan bagian password jika tidak ingin mengganti password.
                            </p>


                            <!-- PASSWORD LAMA -->
                            <div class="form-group">

                                <label for="current_password">
                                    Password Lama
                                </label>

                                <input
                                    type="password"
                                    id="current_password"
                                    name="current_password"
                                    placeholder="Masukkan password lama"
                                >

                            </div>


                            <!-- PASSWORD BARU -->
                            <div class="form-group">

                                <label for="password">
                                    Password Baru
                                </label>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Masukkan password baru"
                                >

                            </div>


                            <!-- KONFIRMASI -->
                            <div class="form-group">

                                <label for="password_confirmation">
                                    Konfirmasi Password Baru
                                </label>

                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Ulangi password baru"
                                >

                            </div>

                        </div>


                        <!-- BUTTON -->
                        <div class="form-actions">

                            <a href="{{ route('superadmin.dashboard') }}"
                               class="btn-cancel">
                                Batal
                            </a>

                            <button type="submit"
                                    class="btn-save">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Simpan Perubahan
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            <!-- ACCOUNT INFO -->
            <div class="card account-card">

                <div class="card-header">

                    <div class="card-title-group">

                        <h3>
                            <i class="fa-solid fa-shield-halved"></i>
                            Informasi Akun
                        </h3>

                        <p>
                            Detail akun yang sedang digunakan.
                        </p>

                    </div>

                </div>


                <div class="account-info">

                    <div class="account-avatar">
                        {{ strtoupper(substr($user->nama_lengkap ?? 'S', 0, 1)) }}
                    </div>

                    <h3>
                        {{ $user->nama_lengkap }}
                    </h3>

                    <span class="role-badge">
                        {{ $user->role }}
                    </span>


                    <div class="info-item">

                        <span>
                            <i class="fa-solid fa-user"></i>
                            Username
                        </span>

                        <strong>
                            {{ $user->username }}
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            <i class="fa-solid fa-envelope"></i>
                            Email
                        </span>

                        <strong>
                            {{ $user->email }}
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            <i class="fa-solid fa-circle"></i>
                            Status
                        </span>

                        @if($user->is_active)
                            <strong class="status-active">
                                Aktif
                            </strong>
                        @else
                            <strong class="status-inactive">
                                Nonaktif
                            </strong>
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>