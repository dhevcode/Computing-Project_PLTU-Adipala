<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Mahasiswa PKL</title>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
<<<<<<< HEAD
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #ffffff;
            overflow: hidden;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        /* LEFT */
        .left {
            width: 51%;
            background: #045f63;
            position: relative;
            color: white;
            padding-left: 64px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            position: absolute;
            top: 0;
            left: 0;
            background: white;
            width: 140px;
            height: 60px;
            clip-path: polygon(0 0, 100% 0, 80% 100%, 0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo img {
            width: 108px;
        }

        h1 {
            font-size: 58px;
            font-weight: 800;
            line-height: 1.08;
            margin-bottom: 30px;
        }

        .desc {
            font-size: 18px;
            line-height: 1.45;
            width: 520px;
            font-weight: 400;
            margin-bottom: 34px;
        }

        .company {
            font-size: 18px;
            line-height: 1.4;
            font-weight: 500;
        }

        /* RIGHT */

        .right {
            width: 49%;
            background: #f7f7f7;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 440px;
            background: white;
            border-radius: 28px;
            padding: 42px;
            box-shadow: 0 18px 50px rgba(0, 0, 0, .15);
        }

        .login-box h2 {
            font-size: 34px;
            font-weight: 700;
            color: #202020;
        }

        .subtitle {
            margin-top: 5px;
            font-size: 13px;
            color: #666;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-size: 15px;
        }

        input {
            width: 100%;
            height: 46px;
            border: 1px solid #dadada;
            border-radius: 8px;
            outline: none;
            padding: 0 16px;
            font-size: 15px;
            box-shadow: 0 2px 7px rgba(0, 0, 0, .12);
        }

        input:focus {
            border-color: #0b6f77;
        }

        button {
            width: 100%;
            height: 48px;
            background: #005b84;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: .3s;
            margin-top: 6px;
        }

        button:hover {
            background: #004f73;
        }

        .warning {
            margin-top: 28px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 8px;
            color: #8a8a8a;
            font-size: 12px;
            line-height: 1.4;
        }

        .warning span:first-child {
            color: #ffc107;
            font-size: 14px;
        }

        @media(max-width:1100px) {

=======
        :root {
            --primary-color: #005f66;
            --primary-hover: #004b50;
            --primary-accent: #00828a;
            --bg-light: #f4f7f6;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* LEFT PANEL */
        .left {
            width: 50%;
            background: linear-gradient(135deg, #005f66 0%, #004348 100%);
            position: relative;
            color: white;
            padding: 60px 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-badge {
            position: absolute;
            top: 0;
            left: 0;
            background: white;
            padding: 16px 36px 16px 24px;
            clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%);
            display: flex;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .logo-badge img {
            height: 38px;
            width: auto;
            object-fit: contain;
        }

        .left-content {
            max-width: 520px;
        }

        .left h1 {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }

        .left .desc {
            font-size: 16px;
            line-height: 1.6;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 36px;
        }

        .left .company {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #7ee2df;
            border-left: 3px solid #7ee2df;
            padding-left: 12px;
        }

        /* RIGHT PANEL */
        .right {
            width: 50%;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .login-box {
            width: 100%;
            max-width: 440px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0,0,0,0.03);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .mobile-logo {
            display: none;
            margin-bottom: 24px;
            text-align: center;
        }

        .mobile-logo img {
            height: 40px;
        }

        .login-box h2 {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 28px;
            line-height: 1.4;
        }

        /* FORM ELEMENTS */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-size: 14px;
            font-weight: 500;
        }

        input {
            width: 100%;
            height: 48px;
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            outline: none;
            padding: 0 16px;
            font-size: 14px;
            color: var(--text-dark);
            background-color: #fafafa;
            transition: all 0.2s ease;
        }

        input::placeholder {
            color: #a0aec0;
        }

        input:focus {
            border-color: var(--primary-color);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(0, 95, 102, 0.1);
        }

        /* ALERT NOTIFICATION */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
            display: block;
            font-weight: 500;
        }

        /* BUTTON */
        button[type="submit"] {
            width: 100%;
            height: 48px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
            box-shadow: 0 4px 12px rgba(0, 95, 102, 0.25);
        }

        button[type="submit"]:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 95, 102, 0.3);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        /* WARNING FOOTER */
        .warning {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px dashed var(--border-color);
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: var(--text-muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .warning-icon {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            background: #fffbe3;
            color: #d97706;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11px;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
>>>>>>> 427ec68cbf2ba1ccf18a64a339cc2450dd4af7e2
            .left {
                display: none;
            }

            .right {
                width: 100%;
            }

            .mobile-logo {
                display: block;
            }

            .login-box {
                padding: 32px 24px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

<<<<<<< HEAD
        <div class="left">

            <div class="logo">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="PLN">
            </div>

            <h1>
                Monitoring<br>
                Mahasiswa<br>
                Magang/PKL
            </h1>

            <div class="desc">
                Platform monitoring mahasiswa PKL untuk
                mendukung pengelolaan data yang lebih
                efektif dan efisien.
            </div>

            <div class="company">
                PT PLN Indonesia Power UBP Jawa Tengah 2
                Adipala
            </div>

        </div>

        <div class="right">

            <div class="login-box">

                <h2>Masuk Sebagai Admin</h2>

                <div class="subtitle">
                    Gunakan Kredensial Admin Untuk Membuka Dashboard
                </div>

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf

                    {{-- Pesan Error Jika Login Gagal --}}
                    @if(session('error'))
                    <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 13px;">
                        {{ session('error') }}
                    </div>
                    @endif

                    <div class="form-group">
                        <label>Email</label>
                        {{-- Tambahkan name="email" --}}
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan email...">
                        @error('email')
                        <small style="color: red; font-size: 12px;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        {{-- Tambahkan name="password" --}}
                        <input type="password" name="password" required placeholder="Masukkan password...">
                        @error('password')
                        <small style="color: red; font-size: 12px;">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit">
                        Masuk ke dashboard
                    </button>
                </form>

                <div class="warning">
                    <span>⚠</span>
                    <span>
                        Perhatian: Halaman ini hanya dapat diakses
                        oleh administrator yang memiliki akun resmi.
                    </span>
                </div>

=======
    <!-- LEFT PANEL -->
    <div class="left">
        <div class="logo-badge">
            <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
        </div>

        <div class="left-content">
            <h1>Monitoring<br>Mahasiswa<br>Magang/PKL</h1>

            <p class="desc">
                Platform monitoring terpadu mahasiswa PKL untuk mendukung efisiensi pengelolaan data, presensi, dan rekapitulasi kegiatan secara terstruktur.
            </p>

            <div class="company">
                PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right">
        <div class="login-box">

            <div class="mobile-logo">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
            </div>

            <h2>Masuk Sebagai Admin</h2>
            <p class="subtitle">Gunakan kredensial admin untuk mengakses dashboard</p>

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                @if(session('error'))
                    <div class="alert-error">
                        <span>⚠️</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="Masukkan alamat email..."
                        autocomplete="email"
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
                        placeholder="Masukkan password..."
                    >
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="warning">
                <div class="warning-icon">!</div>
                <div>
                    <strong>Perhatian:</strong> Halaman ini hanya dapat diakses oleh administrator yang memiliki hak akses resmi.
                </div>
>>>>>>> 427ec68cbf2ba1ccf18a64a339cc2450dd4af7e2
            </div>

        </div>
    </div>

</body>
<<<<<<< HEAD

=======
>>>>>>> 427ec68cbf2ba1ccf18a64a339cc2450dd4af7e2
</html>