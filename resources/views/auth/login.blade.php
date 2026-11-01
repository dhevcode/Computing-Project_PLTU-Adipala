<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Mahasiswa PKL</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="container">

    <!-- LEFT PANEL -->
    <div class="left">
        <div class="logo-badge">
            <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
        </div>

        <div class="left-content">
            <h1>Monitoring<br>Mahasiswa<br>Magang/PKL</h1>

            <p class="desc">
                Platform monitoring terpadu mahasiswa PKL untuk mendukung efisiensi pengelolaan data, presensi berbasis geofencing, dan rekapitulasi logbook kegiatan secara terstruktur.
            </p>

            <div class="company">
                PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala
            </div>

            <div class="left-register-card">
                <div class="reg-card-tag"><i class="fa-solid fa-graduation-cap"></i> Pendaftaran Online</div>
                <p>Ingin melaksanakan program PKL / Magang di PLTU Adipala? Daftarkan data diri, CV, dan proposal Anda secara mandiri.</p>
                <a href="{{ route('magang.daftar') }}" class="btn-left-register">
                    <span>Ajukan Magang Baru</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right">
        <div class="login-box">

            <div class="mobile-logo">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
            </div>

            <h2>Masuk ke Sistem</h2>
            <p class="subtitle">Gunakan email atau NIM untuk mengakses portal</p>

            @if(session('success'))
                <div class="alert-success">
                    <span style="font-size: 16px;">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email atau NIM</label>
                    <input 
                        type="text" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="Contoh: mhs1@gmail.com / 2311102324"
                        autocomplete="username"
                    >
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="Masukkan password Anda..."
                    >
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Masuk ke Dashboard</span>
                </button>
            </form>

            <!-- CTA PENGAJUAN MAGANG BARU -->
            <div class="register-cta-box">
                <div class="divider-text">
                    <span>Belum terdaftar sebagai peserta?</span>
                </div>

                <a href="{{ route('magang.daftar') }}" class="btn-ajukan-magang">
                    <i class="fa-solid fa-file-signature"></i>
                    <span>Ajukan Pendaftaran Magang</span>
                </a>
            </div>

            <div class="warning" style="font-size: 11.5px; line-height: 1.4;">
                <div class="warning-icon">ℹ️</div>
                <div>
                    <strong>Akun Demo Tersedia:</strong><br>
                    &bull; <strong>Peserta:</strong> <code>mhs1@gmail.com</code> (Pass: <code>mhs123</code>)<br>
                    &bull; <strong>Pembimbing:</strong> <code>pembimbing@pltu.com</code> (Pass: <code>pembimbing123</code>)<br>
                    &bull; <strong>Admin:</strong> <code>admin@pltu.com</code> (Pass: <code>admin123</code>)
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Virtual Assistant Chatbot Widget -->
@include('components.chatbot-widget')

</body>
</html>