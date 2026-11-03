<aside class="sidebar" id="sidebar" aria-label="Menu utama">
    <div class="sidebar-brand">
        <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
        <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Tutup menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group">
            <div class="nav-group-title">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.data') }}" class="nav-link {{ request()->routeIs('admin.data*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                <span>Data Mahasiswa</span>
            </a>
            <a href="{{ route('admin.dokumen') }}" class="nav-link {{ request()->routeIs('admin.dokumen*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-shield"></i>
                <span>Dokumen Verifikasi</span>
            </a>
            <a href="{{ route('admin.sertifikat') }}" class="nav-link {{ request()->routeIs('admin.sertifikat*') ? 'active' : '' }}">
                <i class="fa-solid fa-award"></i>
                <span>Sertifikat PKL</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-title">Monitoring & Evaluasi</div>
            <a href="{{ route('admin.absensi') }}" class="nav-link {{ request()->routeIs('admin.absensi*') ? 'active' : '' }}">
                <i class="fa-solid fa-location-dot"></i>
                <span>Presensi & Koreksi</span>
            </a>
            <a href="{{ route('admin.logbook') }}" class="nav-link {{ request()->routeIs('admin.logbook*') ? 'active' : '' }}">
                <i class="fa-solid fa-book-open"></i>
                <span>Logbook PKL</span>
            </a>
            <a href="{{ route('admin.cuti') }}" class="nav-link {{ request()->routeIs('admin.cuti*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Cuti & Izin</span>
            </a>
            <a href="{{ route('admin.aduan') }}" class="nav-link {{ request()->routeIs('admin.aduan*') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>Aduan Masalah</span>
            </a>
            <a href="{{ route('admin.nilai') }}" class="nav-link {{ request()->routeIs('admin.nilai*') ? 'active' : '' }}">
                <i class="fa-solid fa-star-half-stroke"></i>
                <span>Nilai Harian</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-title">Sistem & Informasi</div>
            <a href="{{ route('admin.pengumuman') }}" class="nav-link {{ request()->routeIs('admin.pengumuman*') ? 'active' : '' }}">
                <i class="fa-solid fa-bullhorn"></i>
                <span>Info & Pengumuman</span>
            </a>
            <a href="{{ route('admin.chatbot.index') }}" class="nav-link {{ request()->routeIs('admin.chatbot*') ? 'active' : '' }}">
                <i class="fa-solid fa-robot"></i>
                <span>Chatbot FAQ</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout" title="Logout dari Sistem">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
