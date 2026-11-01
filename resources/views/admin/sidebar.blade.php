<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-title">Menu Utama</div>

    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fa-regular fa-chart-bar"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.data') }}" class="sidebar-menu {{ request()->routeIs('admin.data*') ? 'active' : '' }}">
        <i class="fa-regular fa-user"></i>
        <span>Data Mahasiswa</span>
    </a>

    <a href="{{ route('admin.dokumen') }}" class="sidebar-menu {{ request()->routeIs('admin.dokumen*') ? 'active' : '' }}">
        <i class="fa-regular fa-file-lines"></i>
        <span>Dokumen Verification</span>
    </a>

    <a href="{{ route('admin.sertifikat') }}" class="sidebar-menu {{ request()->routeIs('admin.sertifikat*') ? 'active' : '' }}">
        <i class="fa-regular fa-file"></i>
        <span>Sertifikat</span>
    </a>

    <div class="sidebar-title" style="margin-top: 8px;">Monitoring & Evaluasi</div>

    <a href="{{ route('admin.absensi') }}" class="sidebar-menu {{ request()->routeIs('admin.absensi*') ? 'active' : '' }}">
        <i class="fa-solid fa-location-dot"></i>
        <span>Presensi & Koreksi</span>
    </a>

    <a href="{{ route('admin.logbook') }}" class="sidebar-menu {{ request()->routeIs('admin.logbook*') ? 'active' : '' }}">
        <i class="fa-regular fa-book-open"></i>
        <span>Logbook PKL</span>
    </a>

    <a href="{{ route('admin.cuti') }}" class="sidebar-menu {{ request()->routeIs('admin.cuti*') ? 'active' : '' }}">
        <i class="fa-regular fa-calendar-check"></i>
        <span>Cuti & Izin</span>
    </a>

    <a href="{{ route('admin.aduan') }}" class="sidebar-menu {{ request()->routeIs('admin.aduan*') ? 'active' : '' }}">
        <i class="fa-regular fa-circle-question"></i>
        <span>Aduan Masalah</span>
    </a>

    <a href="{{ route('admin.nilai') }}" class="sidebar-menu {{ request()->routeIs('admin.nilai*') ? 'active' : '' }}">
        <i class="fa-regular fa-star"></i>
        <span>Nilai Harian</span>
    </a>

    <a href="{{ route('admin.pengumuman') }}" class="sidebar-menu {{ request()->routeIs('admin.pengumuman*') ? 'active' : '' }}">
        <i class="fa-regular fa-building"></i>
        <span>Info & Pengumuman</span>
    </a>

    <a href="{{ route('admin.chatbot.index') }}" class="sidebar-menu {{ request()->routeIs('admin.chatbot*') ? 'active' : '' }}">
        <i class="fa-solid fa-robot"></i>
        <span>Chatbot FAQ</span>
    </a>
</div>
