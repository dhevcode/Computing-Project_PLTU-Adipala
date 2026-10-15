<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Belum Terhubung - Sistem Magang</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/peserta/portal.css') }}">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="card" style="max-width: 500px; text-align: center; padding: 40px 30px;">
        <div style="width: 70px; height: 70px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px;">
            <i class="fa-solid fa-user-xmark"></i>
        </div>
        <h2 style="font-size: 18px; font-weight: 700; color: #0f172a;">Data Mahasiswa Belum Terhubung</h2>
        <p style="font-size: 13.5px; color: #64748b; margin: 10px 0 24px; line-height: 1.5;">
            Akun Anda (<strong>{{ $user->email }}</strong>) belum terhubung dengan data mahasiswa peserta magang resmi. Silakan hubungi Administrator Divisi SDM PLTU Adipala.
        </p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar / Logout
            </button>
        </form>
    </div>
</body>
</html>
