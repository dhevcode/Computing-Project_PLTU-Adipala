<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Mahasiswa PKL</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        /* ============================================================
           WARNA: samakan nilai di bawah dengan warna di login.css lama.
           Hanya bagian :root ini yang perlu disesuaikan.
           ============================================================ */
        :root {
            --primary: #00a2b9;        /* warna utama (tombol login, aksen) */
            --primary-dark: #007f92;   /* hover tombol */
            --accent: #ffc20e;         /* warna aksen/kuning */
            --text: #1a2b3c;           /* teks judul */
            --muted: #6b7785;          /* teks sekunder */
            --border: #d5dbe1;         /* garis input */
            --bg: #ffffff;            /* latar panel kiri */
            --success-bg: #e8f7ee;
            --success-text: #1e7e46;
            --error-bg: #fdecec;
            --error-text: #c0392b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body { height: 100%; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* ===================== LEFT: FORM ===================== */
        .left {
            flex: 1 1 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            background: var(--bg);
        }

        .login-box {
            width: 100%;
            max-width: 360px;
        }

        .brand-logo {
            margin-bottom: 28px;
        }

        .brand-logo img {
            height: 56px;
            width: auto;
            display: block;
        }

        .login-box h2 {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 28px;
        }

        /* Alerts */
        .alert-success,
        .alert-error {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .alert-success { background: var(--success-bg); color: var(--success-text); }
        .alert-error   { background: var(--error-bg);   color: var(--error-text); }

        /* Form */
        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 2px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 0;
            font-family: inherit;
            font-size: 14px;
            color: var(--text);
            background: transparent;
            border: none;
            border-bottom: 1.5px solid var(--border);
            border-radius: 0;
            outline: none;
            transition: border-color .2s;
        }

        .form-group input::placeholder {
            color: #a6afb8;
            font-size: 13px;
        }

        .form-group input:focus {
            border-bottom-color: var(--primary);
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap input {
            padding-right: 34px;
        }

        .toggle-password {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 14px;
            padding: 6px;
        }

        .toggle-password:hover,
        .toggle-password:focus-visible { color: var(--primary); }

        .error-message {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: var(--error-text);
        }

        /* Buttons */
        .btn-login,
        .btn-ajukan-magang {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 16px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s, color .2s, border-color .2s;
        }

        .btn-login {
            background: var(--primary);
            color: #fff;
            border: 1.5px solid var(--primary);
            margin-top: 8px;
        }
        .btn-login:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

        .divider-text {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 26px 0 16px;
            font-size: 12px;
            color: var(--muted);
        }
        .divider-text::before,
        .divider-text::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Satu-satunya tombol Ajukan Magang */
        .btn-ajukan-magang {
            background: #fff;
            color: var(--primary);
            border: 1.5px solid var(--primary);
        }
        .btn-ajukan-magang:hover {
            background: var(--primary);
            color: #fff;
        }

        .btn-login:focus-visible,
        .btn-ajukan-magang:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 2px;
        }

        /* ===================== RIGHT: IMAGE SLIDER ===================== */
        .right {
            position: relative;
            flex: 1 1 50%;
            overflow: hidden;
            background: url("{{ asset('assets/image/bg-pln.png') }}") center / cover no-repeat;
            color: #fff;
        }

        .right::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,.65) 0%, rgba(0,0,0,.25) 45%, rgba(0,0,0,.1) 100%);
        }

        .slide-content {
            position: absolute;
            left: 30px;
            right: 30px;
            bottom: 150px;
            z-index: 1;
        }

        .slide { display: none; }
        .slide.active { display: block; animation: slideFade .45s ease; }

        @keyframes slideFade {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .slide p {
            font-size: 18px;
            font-weight: 400;
            line-height: 1.6;
            letter-spacing: .4px;
            margin-bottom: 14px;
        }

        .slide .slide-title {
            font-size: 25px;
            font-weight: 700;
            letter-spacing: 0;
            margin-bottom: 0;
        }

        .company {
            position: absolute;
            left: 30px;
            bottom: 38px;
            z-index: 1;
            max-width: calc(100% - 180px);
            font-size: 20px;
            font-weight: 500;
            color: rgba(255,255,255,.85);
            border-left: 3px solid var(--accent);
            padding-left: 10px;
        }

        .slider-nav {
            position: absolute;
            right: 30px;
            bottom: 28px;
            z-index: 2;
            display: flex;
            gap: 14px;
        }

        .slider-nav button {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 1.5px solid #fff;
            background: transparent;
            color: #fff;
            font-size: 17px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .2s, color .2s;
        }
        .slider-nav button:hover { background: #fff; color: var(--primary); }
        .slider-nav button:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 900px) {
            .right { display: none; }
            .left { flex-basis: 100%; }
        }

        @media (prefers-reduced-motion: reduce) {
            .slide.active { animation: none; }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- LEFT PANEL: FORM -->
    <div class="left">
        <div class="login-box">

            <div class="brand-logo">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
            </div>

            <h2>Masuk ke Sistem</h2>
            <p class="subtitle">Gunakan email atau NIM untuk mengakses portal</p>

            @if(session('success'))
                <div class="alert-success">
                    <span>✅</span>
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
                        placeholder="Masukkan email atau NIM Anda..."
                        autocomplete="username"
                    >
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Masukkan password Anda..."
                            autocomplete="current-password"
                        >
                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Masuk ke Dashboard</span>
                </button>
            </form>

            <!-- SATU-SATUNYA TOMBOL PENGAJUAN MAGANG -->
            <div class="divider-text">
                <span>Belum terdaftar sebagai peserta?</span>
            </div>

            <a href="{{ route('magang.daftar') }}" class="btn-ajukan-magang">
                <i class="fa-solid fa-file-signature"></i>
                <span>Ajukan Magang Baru</span>
            </a>

        </div>
    </div>

    <!-- RIGHT PANEL: IMAGE + SLIDER -->
    <div class="right">

        <div class="slide-content">
            <div class="slide active">
                <p>Platform monitoring terpadu mahasiswa PKL untuk mendukung efisiensi pengelolaan data secara terstruktur.</p>
                <p class="slide-title">Monitoring Mahasiswa Magang/PKL</p>
            </div>
            <div class="slide">
                <p>Presensi berbasis geofencing memastikan kehadiran peserta tercatat akurat sesuai lokasi kerja.</p>
                <p class="slide-title">Presensi Geofencing</p>
            </div>
            <div class="slide">
                <p>Catat kegiatan harian dan lihat rekapitulasi logbook kapan saja dalam satu portal.</p>
                <p class="slide-title">Rekapitulasi Logbook</p>
            </div>
        </div>

        <div class="company">
            PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala
        </div>

        <div class="slider-nav">
            <button type="button" id="prevSlide" aria-label="Sebelumnya"><i class="fa-solid fa-arrow-left"></i></button>
            <button type="button" id="nextSlide" aria-label="Berikutnya"><i class="fa-solid fa-arrow-right"></i></button>
        </div>

    </div>

</div>

<!-- Virtual Assistant Chatbot Widget -->
@include('components.chatbot-widget')

<script>
    (function () {
        // Slider teks panel kanan
        var slides = document.querySelectorAll('.slide');
        var current = 0;

        function show(i) {
            slides[current].classList.remove('active');
            current = (i + slides.length) % slides.length;
            slides[current].classList.add('active');
        }

        document.getElementById('prevSlide').addEventListener('click', function () { show(current - 1); });
        document.getElementById('nextSlide').addEventListener('click', function () { show(current + 1); });

        // Tampilkan / sembunyikan password
        var toggle = document.getElementById('togglePassword');
        var input = document.getElementById('password');
        toggle.addEventListener('click', function () {
            var hidden = input.type === 'password';
            input.type = hidden ? 'text' : 'password';
            toggle.querySelector('i').className = hidden ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
            toggle.setAttribute('aria-label', hidden ? 'Sembunyikan password' : 'Tampilkan password');
        });
    })();
</script>

</body>
</html>
