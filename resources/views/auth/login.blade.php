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
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        :root {
            --primary: #00a2b9;
            --primary-dark: #007f92;
            --panel: #3fa7c5;
            --accent: #ffc20e;
            --text: #1a2b3c;
            --muted: #6b7785;
            --border: #d5dbe1;
            --bg: #ffffff;
            --success-bg: #e8f7ee;
            --success-text: #1e7e46;
            --error-bg: #fdecec;
            --error-text: #c0392b;
            --gap: 16px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body { height: 100%; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        .container {
            display: flex;
            min-height: 100vh;
            min-height: 100dvh;
        }

        /* ===================== LEFT: FORM ===================== */
        .left {
            position: relative;
            flex: 1 1 0;      /* 50% */
            min-width: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px clamp(32px, 5vw, 96px);
            background: var(--bg);
            overflow: hidden;
        }

        /* hiasan halus di latar form */
        .left::before,
        .left::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .left::before {
            width: 420px; height: 420px;
            top: -170px; left: -150px;
            background: radial-gradient(circle, rgba(0, 162, 185, .13), rgba(0, 162, 185, 0) 70%);
        }
        .left::after {
            width: 360px; height: 360px;
            bottom: -150px; right: -120px;
            background: radial-gradient(circle, rgba(255, 194, 14, .13), rgba(255, 194, 14, 0) 70%);
        }

        .login-box {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 350px;
        }

        .brand-logo { margin-bottom: 22px; }

        .brand-logo img {
            height: 42px;
            width: auto;
            display: block;
        }

        .login-box h2 {
            font-size: 22px;
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 22px;
        }

        /* Alerts */
        .alert-success,
        .alert-error {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13.5px;
            margin-bottom: 22px;
        }
        .alert-success { background: var(--success-bg); color: var(--success-text); }
        .alert-error   { background: var(--error-bg);   color: var(--error-text); }

        /* Form */
        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 1px;
            transition: color .2s;
        }
        .form-group:focus-within label { color: var(--primary); }

        .form-group input {
            width: 100%;
            padding: 8px 0;
            font-family: inherit;
            font-size: 13px;
            color: var(--text);
            background: transparent;
            border: none;
            border-bottom: 1.5px solid var(--border);
            border-radius: 0;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-group input::placeholder { color: #a6afb8; font-size: 12px; }
        .form-group input:focus {
            border-bottom-color: var(--primary);
            box-shadow: 0 1.5px 0 0 var(--primary);
        }

        .password-wrap { position: relative; }
        .password-wrap input { padding-right: 38px; }

        .toggle-password {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 15px;
            padding: 8px;
        }
        .toggle-password:hover,
        .toggle-password:focus-visible { color: var(--primary); }

        .error-message {
            display: block;
            margin-top: 6px;
            font-size: 12.5px;
            color: var(--error-text);
        }

        /* Buttons */
        .btn-login,
        .btn-ajukan-magang {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s, color .2s, border-color .2s, box-shadow .2s, transform .1s;
        }

        .btn-login {
            background: var(--primary);
            color: #fff;
            border: 1.5px solid var(--primary);
            margin-top: 10px;
        }
        .btn-login:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 8px 20px rgba(0, 162, 185, .30);
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(0); }

        .divider-text {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 22px 0 14px;
            font-size: 11.5px;
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
        .btn-ajukan-magang:hover { background: var(--primary); color: #fff; }

        .btn-login:focus-visible,
        .btn-ajukan-magang:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 2px;
        }

        .copy {
            position: absolute;
            left: 0; right: 0;
            bottom: 24px;
            z-index: 1;
            text-align: center;
            font-size: 12px;
            color: #9aa5b1;
        }

        /* ===================== RIGHT: PANEL ===================== */
        /* Panel lebih ramping (potret), menempel ke form di kiri */
        .right {
            position: relative;
            flex: 1 1 0;      /* 50% */
            min-width: 0;
            display: flex;
            margin: var(--gap) var(--gap) var(--gap) 0;
            padding: clamp(20px, 2.4vw, 40px);
            border-radius: 28px;
            background: linear-gradient(165deg, #46b0cd 0%, var(--panel) 55%, #2f94b4 100%);
            overflow: hidden;
            font-family: 'Lato', 'Poppins', sans-serif;
            box-shadow: 0 20px 50px rgba(0, 110, 140, .18);
        }

        .right .arcs {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .glow {
            position: absolute;
            top: 36%;
            left: 52%;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f58b4c;
            filter: blur(10px);
            opacity: .75;
            z-index: 1;
        }

        .hero-card {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            border: 1px solid rgba(255, 255, 255, .6);
            border-radius: 22px;
            background: rgba(255, 255, 255, .16);
            overflow: hidden;
            padding: clamp(28px, 3vw, 48px) clamp(24px, 2.6vw, 40px) 0;
        }

        .hero-img {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 62%;
            object-fit: cover;
            object-position: 72% 30%;
            -webkit-mask-image: linear-gradient(to bottom, transparent 0%, #000 38%);
                    mask-image: linear-gradient(to bottom, transparent 0%, #000 38%);
            opacity: .96;
        }

        .hero-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 90, 120, .45), rgba(0, 90, 120, 0) 45%);
            pointer-events: none;
        }

        .slides {
            position: relative;
            z-index: 3;
            display: grid;
            width: 100%;
            max-width: 640px;
        }

        .slide {
            grid-area: 1 / 1;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: opacity .5s ease, transform .5s ease, visibility .5s;
        }
        .slide.active { opacity: 1; visibility: visible; transform: none; }

        .slide h3 {
            font-size: clamp(24px, 2.3vw, 36px);
            font-weight: 700;
            line-height: 1.38;
            color: #fff;
        }

        .company {
            position: relative;
            z-index: 3;
            margin-top: 16px;
            font-size: clamp(12.5px, .95vw, 14.5px);
            font-weight: 400;
            color: rgba(255, 255, 255, .92);
            border-left: 3px solid var(--accent);
            padding-left: 10px;
        }

        .dots {
            position: absolute;
            z-index: 4;
            left: clamp(24px, 2.6vw, 40px);
            bottom: 26px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .dots button {
            width: 12px;
            height: 12px;
            border: none;
            border-radius: 12px;
            background: rgba(255, 255, 255, .6);
            cursor: pointer;
            padding: 0;
            transition: width .3s, background .3s;
        }
        .dots button.active { width: 38px; background: #fff; }
        .dots button:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; }

        /* ===================== LAPTOP KECIL / TABLET LANDSCAPE ===================== */
        @media (max-width: 1200px) {
            .left { padding: 40px 32px; }
        }

        @media (min-width: 901px) and (max-height: 720px) {
            .brand-logo { margin-bottom: 16px; }
            .brand-logo img { height: 36px; }
            .subtitle { margin-bottom: 16px; }
            .form-group { margin-bottom: 14px; }
            .divider-text { margin: 16px 0 10px; }
            .hero-img { height: 54%; }
            .copy { display: none; }
        }

        /* ===================== TABLET PORTRAIT & MOBILE ===================== */
        @media (max-width: 900px) {
            .container { flex-direction: column; }
            .copy { display: none; }
            .left::before, .left::after { display: none; }

            .right {
                order: -1;
                flex: none;
                margin: 12px 12px 0;
                padding: 10px;
                border-radius: 22px;
                box-shadow: none;
            }

            .hero-card {
                min-height: 210px;
                padding: 24px 22px 56px;
                border-radius: 16px;
            }

            .glow { display: none; }

            .hero-img {
                width: 62%;
                height: 100%;
                object-position: 65% 25%;
                -webkit-mask-image: linear-gradient(to right, transparent 0%, #000 55%);
                        mask-image: linear-gradient(to right, transparent 0%, #000 55%);
            }
            .hero-card::after {
                background: linear-gradient(to right, rgba(50, 150, 185, .85) 0%, rgba(50, 150, 185, .45) 55%, rgba(50, 150, 185, 0) 100%);
            }

            .slides { max-width: 78%; }
            .slide h3 { font-size: clamp(19px, 4.6vw, 28px); line-height: 1.35; }
            .company { font-size: 12px; margin-top: 12px; max-width: 70%; }

            .dots { left: 22px; bottom: 20px; }
            .dots button { width: 9px; height: 9px; }
            .dots button.active { width: 28px; }

            .left {
                flex: 1 1 auto;
                align-items: flex-start;
                padding: 28px 24px 40px;
            }
            .login-box { max-width: 460px; margin: 0 auto; }
            .brand-logo { margin-bottom: 22px; }
            .brand-logo img { height: 50px; }
            .login-box h2 { font-size: 24px; }
            .subtitle { font-size: 13px; margin-bottom: 28px; }
            .form-group { margin-bottom: 22px; }
            .form-group label { font-size: 13px; }
            .form-group input { font-size: 14px; padding: 10px 0; }
            .form-group input::placeholder { font-size: 13px; }
            .btn-login, .btn-ajukan-magang { padding: 12px 16px; font-size: 14px; border-radius: 6px; }
            .btn-login { margin-top: 8px; }
            .divider-text { margin: 26px 0 16px; font-size: 12px; }
        }

        @media (max-width: 480px) {
            .right { margin: 8px 8px 0; padding: 8px; border-radius: 18px; }
            .hero-card { min-height: 190px; padding: 20px 18px 52px; }
            .slides { max-width: 85%; }
            .company { max-width: 85%; }
            .left { padding: 24px 20px 36px; }
        }

        @media (max-width: 360px) {
            .slide h3 { font-size: 17px; }
            .login-box h2 { font-size: 22px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .slide, .dots button, .btn-login { transition: none; }
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

        <p class="copy">&copy; {{ date('Y') }} PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala</p>
    </div>

    <!-- RIGHT PANEL: IMAGE + SLIDER -->
    <div class="right" id="panel">

        {{-- dekorasi lengkung --}}
        <svg class="arcs" viewBox="0 0 600 800" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
            <g fill="none" stroke="#fff" stroke-opacity=".13">
                <circle cx="620" cy="330" r="300" stroke-width="46"/>
                <circle cx="620" cy="330" r="420" stroke-width="30"/>
                <circle cx="620" cy="330" r="520" stroke-width="18"/>
            </g>
            <g fill="none" stroke="#2e93b3" stroke-opacity=".55">
                <path d="M0 300 Q 40 292 70 296" stroke-width="12"/>
                <path d="M0 360 Q 40 352 70 356" stroke-width="12"/>
                <path d="M0 430 Q 40 422 70 426" stroke-width="12"/>
            </g>
            <path d="M120 800 Q 260 640 330 800 Z" fill="#fff" fill-opacity=".1"/>
            <path d="M330 800 Q 420 650 520 800 Z" fill="#fff" fill-opacity=".08"/>
        </svg>

        <span class="glow" aria-hidden="true"></span>

        <div class="hero-card">

            <img class="hero-img" src="{{ asset('assets/image/bg-pln.png') }}" alt="" aria-hidden="true">

            <div class="slides" id="slides">
                <div class="slide active">
                    <h3>Pantau kegiatan magang Anda dalam satu portal terpadu!</h3>
                </div>
                <div class="slide">
                    <h3>Presensi geofencing, kehadiran tercatat akurat sesuai lokasi kerja!</h3>
                </div>
                <div class="slide">
                    <h3>Catat logbook harian dan lihat rekapitulasinya kapan saja!</h3>
                </div>
            </div>

            <div class="company">PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala</div>

            <div class="dots" id="dots">
                <button type="button" class="active" aria-label="Slide 1"></button>
                <button type="button" aria-label="Slide 2"></button>
                <button type="button" aria-label="Slide 3"></button>
            </div>
        </div>

    </div>

</div>

<!-- Virtual Assistant Chatbot Widget -->
@include('components.chatbot-widget')

<script>
    (function () {
        // ---------- Slider ----------
        var slides = document.querySelectorAll('#slides .slide');
        var dots   = document.querySelectorAll('#dots button');
        var panel  = document.getElementById('panel');
        var current = 0, timer = null;

        function show(i) {
            slides[current].classList.remove('active');
            dots[current].classList.remove('active');
            current = (i + slides.length) % slides.length;
            slides[current].classList.add('active');
            dots[current].classList.add('active');
        }

        function start() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            if (timer) clearInterval(timer);
            timer = setInterval(function () { show(current + 1); }, 5000);
        }

        dots.forEach(function (d, i) {
            d.addEventListener('click', function () { show(i); start(); });
        });

        // geser (swipe) di layar sentuh
        var startX = null;
        panel.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
        panel.addEventListener('touchend', function (e) {
            if (startX === null) return;
            var dx = e.changedTouches[0].clientX - startX;
            if (Math.abs(dx) > 40) { show(current + (dx < 0 ? 1 : -1)); start(); }
            startX = null;
        });

        start();

        // ---------- Tampilkan / sembunyikan password ----------
        var toggle = document.getElementById('togglePassword');
        var input  = document.getElementById('password');
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
