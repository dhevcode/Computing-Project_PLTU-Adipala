<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Pembimbing') - Monitoring Magang PLTU Adipala</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Core Portal CSS -->
    <link rel="stylesheet" href="{{ asset('css/pembimbing/portal.css') }}">
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
                <h2>Portal Pembimbing Lapangan</h2>
                <p>PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala</p>
            </div>
        </div>

        <div class="header-right">
            <!-- User Info Badge -->
            <a href="{{ route('pembimbing.profil') }}" class="user-badge" title="Profil Pembimbing">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'P', 0, 1)) }}
                </div>
                <div class="user-info-text">
                    <div class="name">{{ Str::limit(Auth::user()->nama_lengkap, 20) }}</div>
                    <div class="role">Pembimbing Lapangan</div>
                </div>
            </a>

            <!-- Mobile Menu Toggle -->
            <button type="button" class="header-action-btn menu-btn-mobile" id="menuBtn" title="Menu Navigasi">
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

        <a href="{{ route('pembimbing.dashboard') }}" class="sidebar-menu {{ request()->routeIs('pembimbing.dashboard') ? 'active' : '' }}">
            <i class="fa-regular fa-chart-bar"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('pembimbing.mahasiswa') }}" class="sidebar-menu {{ request()->routeIs('pembimbing.mahasiswa*') ? 'active' : '' }}">
            <i class="fa-regular fa-user"></i>
            <span>Peserta Bimbingan</span>
        </a>

        <div class="sidebar-group-title">Monitoring Lapangan</div>

        <a href="{{ route('pembimbing.absensi') }}" class="sidebar-menu {{ request()->routeIs('pembimbing.absensi*') ? 'active' : '' }}">
            <i class="fa-solid fa-location-dot"></i>
            <span>Presensi & Koreksi</span>
        </a>

        <a href="{{ route('pembimbing.logbook') }}" class="sidebar-menu {{ request()->routeIs('pembimbing.logbook*') ? 'active' : '' }}">
            <i class="fa-regular fa-book-open"></i>
            <span>Logbook PKL</span>
        </a>

        <a href="{{ route('pembimbing.cuti') }}" class="sidebar-menu {{ request()->routeIs('pembimbing.cuti*') ? 'active' : '' }}">
            <i class="fa-regular fa-calendar-check"></i>
            <span>Cuti & Izin</span>
        </a>

        <a href="{{ route('pembimbing.aduan') }}" class="sidebar-menu {{ request()->routeIs('pembimbing.aduan*') ? 'active' : '' }}">
            <i class="fa-regular fa-circle-question"></i>
            <span>Aduan Masalah</span>
        </a>

        <div class="sidebar-group-title">Evaluasi & Penilaian</div>

        <a href="{{ route('pembimbing.nilai') }}" class="sidebar-menu {{ request()->routeIs('pembimbing.nilai*') ? 'active' : '' }}">
            <i class="fa-regular fa-star"></i>
            <span>Evaluasi & Nilai</span>
        </a>

        <div class="sidebar-group-title">Pengaturan Akun</div>

        <a href="{{ route('pembimbing.profil') }}" class="sidebar-menu {{ request()->routeIs('pembimbing.profil*') ? 'active' : '' }}">
            <i class="fa-regular fa-id-badge"></i>
            <span>Profil Pembimbing</span>
        </a>
    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 18px;"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 18px; margin-top: 2px;"></i>
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

    <!-- Mobile Drawer Script -->
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
