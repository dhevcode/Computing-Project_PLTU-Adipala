<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Mahasiswa PKL</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            </div>

        </div>
    </div>

</div>

</body>
</html>