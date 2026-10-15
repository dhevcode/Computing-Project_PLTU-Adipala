<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Peserta') - Monitoring Magang PLTU Adipala</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('css/peserta/portal.css') }}">
    @stack('styles')
</head>
<body>

    <!-- ================= HEADER ================= -->
    <header class="header">
        <div class="header-left">
            <div class="logo-box">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
            </div>

            <div class="header-title">
                <h2>Portal Peserta Magang</h2>
                <p>PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala</p>
            </div>
        </div>

        <div class="header-right">
            <!-- User Info Badge -->
            <a href="{{ route('peserta.profil') }}" class="user-badge" title="Profil Saya">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'P', 0, 1)) }}
                </div>
                <div class="user-info-text">
                    <div class="name">{{ Str::limit(Auth::user()->nama_lengkap, 16) }}</div>
                    <div class="role">Peserta Magang</div>
                </div>
            </a>

            <!-- Notification Bell -->
            <a href="{{ route('peserta.notifikasi') }}" class="header-action-btn" title="Notifikasi">
                <i class="fa-regular fa-bell"></i>
                @php
                    $unreadCount = Auth::user()->unreadNotificationsCount();
                @endphp
                @if($unreadCount > 0)
                    <span class="notif-badge-pill">{{ $unreadCount }}</span>
                @endif
            </a>

            <!-- Mobile Menu Toggle -->
            <button type="button" class="header-action-btn" id="menuBtn" title="Menu Navigasi">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="header-action-btn" title="Keluar / Logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- ================= SIDEBAR ================= -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-group-title">Menu Utama</div>

        <a href="{{ route('peserta.dashboard') }}" class="sidebar-menu {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
            <i class="fa-regular fa-chart-bar"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('peserta.absensi') }}" class="sidebar-menu {{ request()->routeIs('peserta.absensi*') ? 'active' : '' }}">
            <i class="fa-solid fa-location-dot"></i>
            <span>Presensi & Koreksi</span>
        </a>

        <a href="{{ route('peserta.logbook') }}" class="sidebar-menu {{ request()->routeIs('peserta.logbook*') ? 'active' : '' }}">
            <i class="fa-regular fa-book-open"></i>
            <span>Logbook Harian</span>
        </a>

        <a href="{{ route('peserta.cuti') }}" class="sidebar-menu {{ request()->routeIs('peserta.cuti*') ? 'active' : '' }}">
            <i class="fa-regular fa-calendar-check"></i>
            <span>Cuti & Izin</span>
        </a>

        <div class="sidebar-group-title">Aktivitas & Evaluasi</div>

        <a href="{{ route('peserta.aduan') }}" class="sidebar-menu {{ request()->routeIs('peserta.aduan*') ? 'active' : '' }}">
            <i class="fa-regular fa-circle-question"></i>
            <span>Aduan Masalah</span>
        </a>

        <a href="{{ route('peserta.nilai') }}" class="sidebar-menu {{ request()->routeIs('peserta.nilai*') ? 'active' : '' }}">
            <i class="fa-regular fa-star"></i>
            <span>Arsip Nilai</span>
        </a>

        <div class="sidebar-group-title">Informasi & Pengaturan</div>

        <a href="{{ route('peserta.informasi') }}" class="sidebar-menu {{ request()->routeIs('peserta.informasi*') ? 'active' : '' }}">
            <i class="fa-regular fa-building"></i>
            <span>Info Perusahaan</span>
        </a>

        <a href="{{ route('peserta.notifikasi') }}" class="sidebar-menu {{ request()->routeIs('peserta.notifikasi*') ? 'active' : '' }}">
            <i class="fa-regular fa-bell"></i>
            <span>Notifikasi</span>
            @if($unreadCount > 0)
                <span class="badge">{{ $unreadCount }}</span>
            @endif
        </a>

        <a href="{{ route('peserta.profil') }}" class="sidebar-menu {{ request()->routeIs('peserta.profil*') ? 'active' : '' }}">
            <i class="fa-regular fa-user"></i>
            <span>Profil Saya</span>
        </a>
    </aside>

    <!-- ================= CONTENT ================= -->
    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <strong>Terdapat kesalahan:</strong>
                    <ul style="margin-left: 20px; margin-top: 4px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Script Drawer Navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.getElementById('menuBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (menuBtn && sidebar && sidebarOverlay) {
                menuBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                });

                sidebarOverlay.addEventListener('click', function () {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sidebar && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
