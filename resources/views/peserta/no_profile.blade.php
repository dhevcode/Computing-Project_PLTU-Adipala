<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Belum Terhubung - Sistem Magang</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #1a1b1b;
            font-family: 'Inter', sans-serif;
            color: #f6f6f4;
            box-sizing: border-box;
        }

        .np-card {
            width: 100%;
            max-width: 480px;
            background: rgba(34, 36, 36, 0.50);
            backdrop-filter: blur(40px) saturate(140%);
            -webkit-backdrop-filter: blur(40px) saturate(140%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 32px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.10);
        }

        .np-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255, 90, 90, 0.16);
            border: 1px solid rgba(255, 157, 157, 0.36);
            color: #ff9d9d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 20px;
        }

        .np-title {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 10px 0;
            letter-spacing: -0.01em;
        }

        .np-desc {
            font-size: 13.5px;
            color: rgba(246, 246, 244, 0.78);
            line-height: 1.6;
            margin: 0 0 28px 0;
        }
        .np-desc strong {
            color: #ffffff;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 44px;
            padding: 0 24px;
            border-radius: 9999px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.22);
            transition: all 0.2s ease;
        }
        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.16);
            border-color: rgba(255, 255, 255, 0.30);
        }
    </style>
</head>
<body>
    <div class="np-card">
        <div class="np-icon">
            <i class="fa-solid fa-user-xmark"></i>
        </div>
        <h2 class="np-title">Data Peserta Belum Terhubung</h2>
        <p class="np-desc">
            Akun Anda (<strong>{{ $user->email }}</strong>) belum terhubung dengan data mahasiswa peserta magang resmi. Silakan hubungi Administrator Divisi SDM PLTU Adipala untuk verifikasi lebih lanjut.
        </p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-outline">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar / Logout
            </button>
        </form>
    </div>
</body>
</html>
