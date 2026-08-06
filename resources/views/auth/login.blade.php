<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Mahasiswa PKL</title>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
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

            .left {
                display: none;
            }

            .right {
                width: 100%;
            }

        }
    </style>
</head>

<body>

    <div class="container">

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

            </div>

        </div>

    </div>

</body>

</html>