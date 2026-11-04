<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Belum Terhubung - Sistem Magang</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <!-- Menggunakan font Plus Jakarta Sans agar seragam dengan seluruh aplikasi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        /* ================= RESET & DASAR ================= */
        body {
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #121314; /* Sedikit lebih gelap agar kontras kartu lebih menonjol */
            background-image: radial-gradient(circle at 50% 0%, #1e2022 0%, #121314 60%);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #f6f6f4;
            box-sizing: border-box;
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }

        /* ================= ANIMASI ================= */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ================= KARTU GLASSMORPHISM ================= */
        .np-card {
            width: 100%;
            max-width: 480px;
            background: rgba(34, 36, 36, 0.45);
            backdrop-filter: blur(24px) saturate(140%);
            -webkit-backdrop-filter: blur(24px) saturate(140%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 28px;
            padding: 48px 40px;
            text-align: center;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.08);
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* ================= IKON PERINGATAN ================= */
        .np-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #FCA5A5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 24px;
            box-shadow: 0 0 24px rgba(239, 68, 68, 0.1);
        }

        /* ================= TEKS KONTEN ================= */
        .np-title {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 12px 0;
            letter-spacing: -0.02em;
        }

        .np-desc {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
            margin: 0 0 32px 0;
        }
        .np-desc strong {
            color: #ffffff;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.1);
            padding: 2px 8px;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }

        /* ================= TOMBOL LOGOUT ================= */
        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            height: 46px;
            width: 100%;
            border-radius: 12px;
            font-family: inherit;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.2s ease;
        }
        .btn-outline i {
            font-size: 16px;
            transition: transform 0.2s ease;
        }
        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .btn-outline:hover i {
            transform: translateX(-3px);
        }

        /* ================= RESPONSIVE MOBILE ================= */
        @media (max-width: 480px) {
            body { padding: 16px; }
            .np-card { padding: 36px 24px; border-radius: 24px; }
            .np-icon { width: 68px; height: 68px; font-size: 28px; margin-bottom: 20px; }
            .np-title { font-size: 20px; }
            .np-desc { font-size: 13.5px; margin-bottom: 24px; }
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
            Akun Anda (<strong>{{ $user->email ?? 'Email Tidak Diketahui' }}</strong>) belum terhubung dengan data mahasiswa peserta magang resmi. Silakan hubungi Administrator Divisi SDM PLTU Adipala untuk verifikasi lebih lanjut.
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
