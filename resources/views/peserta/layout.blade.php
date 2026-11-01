<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Peserta') - Monitoring Magang PLTU Adipala</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <!-- Font: SF Pro di perangkat Apple, Inter di perangkat lain -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('css/peserta/portal.css') }}">

    <style>
        /* ==========================================================================
           TEMA: KACA CHARCOAL NETRAL + AKSEN LIME (MENGIKUTI GAMBAR REFERENSI)
           Header & sidebar berbentuk panel kaca melayang (floating).
           Foto latar tetap dari portal.css.
           ========================================================================== */
        :root {
            --hdr-h: 64px;
            --gap: 14px;
            --side-w: 248px;
            --top: calc(var(--hdr-h) + var(--gap) * 2);

            --font-ui: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Inter', 'Segoe UI', Roboto, sans-serif;
            --font-num: 'JetBrains Mono', ui-monospace, 'SF Mono', Menlo, Consolas, monospace;

            /* permukaan kaca (abu-abu netral) */
            --glass-nav: rgba(34, 36, 36, 0.50);
            --panel: rgba(62, 64, 64, 0.38);
            --panel-border: rgba(255, 255, 255, 0.22);
            --card: rgba(24, 25, 25, 0.58);
            --card-2: rgba(255, 255, 255, 0.06);
            --card-2-hover: rgba(255, 255, 255, 0.11);
            --border: rgba(255, 255, 255, 0.10);
            --border-strong: rgba(255, 255, 255, 0.22);

            /* teks */
            --text: #f6f6f4;
            --text-2: rgba(246, 246, 244, 0.78);
            --muted: rgba(246, 246, 244, 0.58);

            /* aksen lime (seperti bar & kartu VISA pada gambar) */
            --accent: #c6ec2f;
            --accent-ink: #1a2205;
            --accent-soft: rgba(198, 236, 47, 0.13);
            --accent-bd: rgba(198, 236, 47, 0.34);

            --ok: #c6ec2f;     --ok-bg: rgba(198, 236, 47, 0.14);  --ok-bd: rgba(198, 236, 47, 0.34);
            --warn: #ffd66b;   --warn-bg: rgba(255, 190, 40, 0.16); --warn-bd: rgba(255, 214, 107, 0.36);
            --danger: #ff9d9d; --danger-bg: rgba(255, 90, 90, 0.16); --danger-bd: rgba(255, 157, 157, 0.36);
            --info: #a5c8ff;   --info-bg: rgba(90, 150, 255, 0.16); --info-bd: rgba(165, 200, 255, 0.34);
            --violet: #cdbdff; --violet-bg: rgba(150, 110, 255, 0.18); --violet-bd: rgba(205, 189, 255, 0.34);

            --drawer-bg: rgba(26, 27, 27, 0.97);
            --badge-ring: #2a2c2c;
        }

        * { box-sizing: border-box; }
        html, body { width: 100%; min-height: 100%; margin: 0; overflow-x: hidden; }

        html body {
            font-family: var(--font-ui);
            -webkit-font-smoothing: antialiased;
            color: var(--text);
        }
        button, input, select, textarea { font-family: inherit; }

        /* Menetralkan warna foto latar (mengurangi tint hijau/teal) tanpa mengganti fotonya */
        .bg-tone {
            position: fixed; inset: 0; z-index: -1; pointer-events: none;
            background: rgba(14, 14, 14, 0.28);
            backdrop-filter: saturate(0.35) brightness(0.95);
            -webkit-backdrop-filter: saturate(0.35) brightness(0.95);
        }

        /* ==========================================================================
           HEADER - bar kaca melayang
           ========================================================================== */
        .header {
            position: fixed !important;
            top: var(--gap); left: var(--gap); right: var(--gap);
            height: var(--hdr-h);
            background: var(--glass-nav) !important;
            backdrop-filter: blur(40px) saturate(140%) !important;
            -webkit-backdrop-filter: blur(40px) saturate(140%) !important;
            border: 1px solid var(--panel-border) !important;
            border-radius: 9999px !important;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.30), inset 0 1px 0 rgba(255, 255, 255, 0.20) !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 12px 0 10px;
            z-index: 1050 !important;
        }

        .header-left { display: flex; align-items: center; gap: 16px; flex: 1; min-width: 0; }
        .logo-box {
            background: #ffffff; height: 44px; padding: 0 18px; border-radius: 9999px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .logo-box img { height: 26px; width: auto; display: block; }

        .header-title { flex: 1; min-width: 0; }
        .header-title h2 {
            font-size: 16px !important; font-weight: 700 !important; letter-spacing: -0.01em;
            color: #ffffff !important; margin: 0; line-height: 1.2;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .header-title p {
            font-size: 11px !important; color: var(--muted) !important; font-weight: 500 !important; margin: 2px 0 0 0;
            font-family: var(--font-num);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .header-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
        .user-badge {
            display: flex; align-items: center; gap: 10px; height: 44px;
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid var(--border-strong) !important; padding: 0 16px 0 5px !important;
            border-radius: 9999px !important; text-decoration: none !important; transition: background .2s ease;
        }
        .user-badge:hover { background: rgba(255, 255, 255, 0.15) !important; }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--accent) !important; color: var(--accent-ink) !important;
            font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .user-info-text .name { font-size: 12.5px !important; font-weight: 600 !important; color: #ffffff !important; line-height: 1.2; margin: 0; }
        .user-info-text .role { font-size: 10.5px !important; color: var(--muted) !important; font-weight: 500 !important; margin: 0; }

        .header-action-btn {
            position: relative; width: 44px; height: 44px; border-radius: 50% !important;
            background: rgba(255, 255, 255, 0.08) !important; border: 1px solid var(--border-strong) !important;
            color: #ffffff !important; display: inline-flex !important; align-items: center; justify-content: center;
            font-size: 15px; cursor: pointer; text-decoration: none !important; transition: background .2s ease; flex-shrink: 0;
            padding: 0;
        }
        .header-action-btn:hover { background: rgba(255, 255, 255, 0.16) !important; }
        .header-action-btn:focus-visible, .sidebar-menu:focus-visible, .user-badge:focus-visible {
            outline: 2px solid var(--accent); outline-offset: 2px;
        }
        .notif-badge-pill {
            position: absolute; top: -3px; right: -3px; background: #ff5a5a !important; color: #ffffff !important;
            font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 9999px; border: 2px solid var(--badge-ring);
        }

        #menuBtn { display: none !important; }

        /* ==========================================================================
           SIDEBAR - panel kaca melayang
           ========================================================================== */
        .sidebar {
            position: fixed !important; top: var(--top) !important; left: var(--gap) !important; bottom: var(--gap) !important;
            width: var(--side-w) !important;
            background: var(--glass-nav) !important;
            backdrop-filter: blur(40px) saturate(140%) !important; -webkit-backdrop-filter: blur(40px) saturate(140%) !important;
            border: 1px solid var(--panel-border) !important;
            border-radius: 32px !important;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.30), inset 0 1px 0 rgba(255, 255, 255, 0.20) !important;
            padding: 12px 12px 16px 12px !important;
            overflow-y: auto !important; z-index: 1040 !important; transform: translateX(0) !important;
            transition: transform 0.3s ease-in-out !important;
            scrollbar-width: none;
        }
        .sidebar::-webkit-scrollbar { display: none; }

        .sidebar-overlay, .sidebar-drawer-header { display: none !important; }
        .sidebar-group-title {
            font-family: var(--font-num);
            font-size: 10px !important; font-weight: 500 !important; text-transform: uppercase; letter-spacing: 0.14em;
            color: var(--muted) !important; margin: 18px 14px 8px 14px !important;
        }
        .sidebar-group-title:first-of-type { margin-top: 8px !important; }

        .sidebar-menu {
            display: flex; align-items: center; gap: 12px; padding: 12px 14px !important; margin-bottom: 4px;
            border-radius: 9999px !important; color: var(--text-2) !important; font-size: 14px !important; font-weight: 500 !important;
            text-decoration: none !important; border: 1px solid transparent; transition: background .2s ease, color .2s ease, border-color .2s ease;
            background: transparent; width: 100%; box-sizing: border-box; cursor: pointer;
        }
        .sidebar-menu i { font-size: 15px; width: 22px; text-align: center; color: var(--muted) !important; transition: color .2s ease; }
        .sidebar-menu:hover { background: rgba(255, 255, 255, 0.09) !important; color: #ffffff !important; }
        .sidebar-menu:hover i { color: #ffffff !important; }
        .sidebar-menu.active {
            background: rgba(255, 255, 255, 0.17) !important; color: #ffffff !important; font-weight: 600 !important;
            border-color: rgba(255, 255, 255, 0.24) !important; box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18) !important;
        }
        .sidebar-menu.active i { color: var(--accent) !important; }
        .sidebar-menu.active::after {
            content: ""; margin-left: auto; width: 7px; height: 7px; border-radius: 50%; background: var(--accent);
        }
        .sidebar-menu .badge {
            margin-left: auto; background: #ff5a5a !important; color: #ffffff !important;
            font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 999px;
        }

        .btn-logout-sidebar {
            background: var(--danger-bg) !important; border: 1px solid var(--danger-bd) !important;
            color: var(--danger) !important; margin-top: 8px;
        }
        .btn-logout-sidebar i { color: var(--danger) !important; }
        .btn-logout-sidebar:hover { background: #ff5a5a !important; color: #ffffff !important; border-color: #ff5a5a !important; }
        .btn-logout-sidebar:hover i { color: #ffffff !important; }

        /* ==========================================================================
           KONTEN UTAMA (SELALU DI TENGAH SISA LAYAR)
           ========================================================================== */
        .content {
            display: block !important;
            float: none !important;
            position: relative !important;
            box-sizing: border-box !important;
            margin: var(--top) 0 0 calc(var(--side-w) + var(--gap) * 2) !important;
            width: calc(100% - var(--side-w) - var(--gap) * 2) !important;
            max-width: none !important;
            min-height: calc(100vh - var(--top)) !important;
            padding: 0 var(--gap) 48px 0 !important;
        }

        .content-wrapper {
            box-sizing: border-box !important;
            width: 100% !important;
            max-width: 1200px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            float: none !important;
            left: auto !important;
            right: auto !important;
            transform: none !important;
        }

        /* ==========================================================================
           TABLET & MOBILE (< 992px)  -> sidebar jadi drawer
           ========================================================================== */
        @media (max-width: 992px) {
            :root { --hdr-h: 58px; --gap: 10px; }

            .sidebar {
                top: 0 !important; left: 0 !important; bottom: 0 !important;
                width: min(300px, 86vw) !important;
                border-radius: 0 32px 32px 0 !important; z-index: 1100 !important;
                transform: translateX(-105%) !important;
                background: var(--drawer-bg) !important;
                padding-top: 14px !important;
            }
            .sidebar.active { transform: translateX(0) !important; }

            .sidebar-overlay {
                position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
                background: rgba(0, 0, 0, 0.55) !important; backdrop-filter: blur(6px) !important;
                z-index: 1090; opacity: 0; visibility: hidden; display: block !important; transition: opacity 0.3s ease;
            }
            .sidebar-overlay.active { opacity: 1; visibility: visible; }

            .sidebar-drawer-header {
                display: flex !important; align-items: center; justify-content: space-between;
                padding: 4px 8px 14px 8px; margin-bottom: 6px; border-bottom: 1px solid var(--border);
            }
            .sidebar-close-btn {
                width: 36px; height: 36px; border-radius: 50%; background: var(--card-2);
                border: 1px solid var(--border-strong); color: #ffffff; display: flex; align-items: center; justify-content: center; cursor: pointer;
            }

            #menuBtn { display: inline-flex !important; }

            .content { margin-left: 0 !important; width: 100% !important; padding: 0 var(--gap) 40px var(--gap) !important; }
        }

        @media (max-width: 768px) {
            .logo-box { height: 38px; padding: 0 14px; }
            .logo-box img { height: 22px !important; }
            .header-left { gap: 10px; }
            .header-title h2 { font-size: 14px !important; }
            .header-title p { display: none !important; }
            .user-info-text { display: none !important; }
            .user-badge { padding: 0 !important; width: 40px; height: 40px; justify-content: center; }
            .user-avatar { width: 32px !important; height: 32px !important; font-size: 13px !important; }
            .header-action-btn { width: 40px !important; height: 40px !important; }
            .header-right { gap: 6px !important; }
            .header { padding: 0 9px 0 8px; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="bg-tone" aria-hidden="true"></div>

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
            <a href="{{ route('peserta.profil') }}" class="user-badge" title="Profil Saya">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'P', 0, 1)) }}
                </div>
                <div class="user-info-text">
                    <div class="name">{{ Str::limit(Auth::user()->nama_lengkap, 16) }}</div>
                    <div class="role">Peserta Magang</div>
                </div>
            </a>
            <a href="{{ route('peserta.notifikasi') }}" class="header-action-btn" title="Notifikasi">
                <i class="fa-regular fa-bell"></i>
                @php $unreadCount = Auth::user()->unreadNotificationsCount() ?? 0; @endphp
                @if($unreadCount > 0)
                    <span class="notif-badge-pill">{{ $unreadCount }}</span>
                @endif
            </a>
            <button type="button" class="header-action-btn" id="menuBtn" title="Menu Navigasi">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- ================= SIDEBAR ================= -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-drawer-header">
            <div style="font-size: 14px; font-weight: 800; color: var(--text); display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-bolt" style="color: var(--accent);"></i> Navigasi Portal
            </div>
            <button type="button" class="sidebar-close-btn" id="closeSidebarBtn">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="sidebar-group-title">Menu Utama</div>
        <a href="{{ route('peserta.dashboard') }}" class="sidebar-menu {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
            <i class="fa-regular fa-chart-bar"></i> <span>Dashboard</span>
        </a>
        <a href="{{ route('peserta.absensi') }}" class="sidebar-menu {{ request()->routeIs('peserta.absensi*') ? 'active' : '' }}">
            <i class="fa-solid fa-location-dot"></i> <span>Presensi & Koreksi</span>
        </a>
        <a href="{{ route('peserta.logbook') }}" class="sidebar-menu {{ request()->routeIs('peserta.logbook*') ? 'active' : '' }}">
            <i class="fa-solid fa-book-open"></i> <span>Logbook Harian</span>
        </a>
        <a href="{{ route('peserta.cuti') }}" class="sidebar-menu {{ request()->routeIs('peserta.cuti*') ? 'active' : '' }}">
            <i class="fa-regular fa-calendar-check"></i> <span>Cuti & Izin</span>
        </a>

        <div class="sidebar-group-title">Aktivitas & Evaluasi</div>
        <a href="{{ route('peserta.aduan') }}" class="sidebar-menu {{ request()->routeIs('peserta.aduan*') ? 'active' : '' }}">
            <i class="fa-regular fa-circle-question"></i> <span>Aduan Masalah</span>
        </a>
        <a href="{{ route('peserta.nilai') }}" class="sidebar-menu {{ request()->routeIs('peserta.nilai*') ? 'active' : '' }}">
            <i class="fa-regular fa-star"></i> <span>Arsip Nilai</span>
        </a>

        <div class="sidebar-group-title">Informasi & Pengaturan</div>
        <a href="{{ route('peserta.informasi') }}" class="sidebar-menu {{ request()->routeIs('peserta.informasi*') ? 'active' : '' }}">
            <i class="fa-regular fa-building"></i> <span>Info Perusahaan</span>
        </a>
        <a href="{{ route('peserta.profil') }}" class="sidebar-menu {{ request()->routeIs('peserta.profil*') ? 'active' : '' }}">
            <i class="fa-regular fa-user"></i> <span>Profil Saya</span>
        </a>

        <div class="sidebar-group-title">Sistem</div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0; padding-bottom: 20px;">
            @csrf
            <button type="submit" class="sidebar-menu btn-logout-sidebar" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Keluar / Logout</span>
            </button>
        </form>
    </aside>

    <!-- ================= KONTEN UTAMA ================= -->
    <main class="content">
        <!-- BUNGKUSAN TENGAH (CONTENT WRAPPER) -->
        <div class="content-wrapper">

            @if(session('success'))
                <div class="alert alert-success" style="display:flex; align-items:flex-start; gap:12px; padding:15px; background:var(--ok-bg); border:1px solid var(--ok-bd); color:var(--ok); backdrop-filter:blur(10px); border-radius:12px; margin-bottom:20px;">
                    <i class="fa-solid fa-circle-check" style="color:var(--ok); font-size:18px; margin-top:2px;"></i>
                    <span style="font-weight:600; font-size:13.5px;">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error" style="display:flex; align-items:flex-start; gap:12px; padding:15px; background:var(--danger-bg); border:1px solid var(--danger-bd); color:var(--danger); backdrop-filter:blur(10px); border-radius:12px; margin-bottom:20px;">
                    <i class="fa-solid fa-circle-exclamation" style="color:var(--danger); font-size:18px; margin-top:2px;"></i>
                    <span style="font-weight:600; font-size:13.5px;">{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-error" style="display:flex; align-items:flex-start; gap:12px; padding:15px; background:var(--danger-bg); border:1px solid var(--danger-bd); color:var(--danger); backdrop-filter:blur(10px); border-radius:12px; margin-bottom:20px;">
                    <i class="fa-solid fa-circle-exclamation" style="color:var(--danger); font-size:18px; margin-top:2px;"></i>
                    <div style="font-size:13.5px;">
                        <strong>Terdapat kesalahan:</strong>
                        <ul style="margin-left: 20px; margin-top: 4px; padding-left:0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Konten Dashboard / Halaman Lainnya -->
            @yield('content')

        </div>
    </main>

    <!-- Script Drawer Navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.getElementById('menuBtn');
            const closeBtn = document.getElementById('closeSidebarBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            }

            if (menuBtn && sidebar && sidebarOverlay) {
                menuBtn.addEventListener('click', toggleSidebar);
                closeBtn.addEventListener('click', toggleSidebar);
                sidebarOverlay.addEventListener('click', toggleSidebar);
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sidebar && sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            });
        });
    </script>
    @stack('scripts')

    <!-- Virtual Assistant Chatbot Widget -->
    @include('components.chatbot-widget')
</body>
</html>
