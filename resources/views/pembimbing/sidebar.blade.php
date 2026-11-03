<aside class="sidebar" id="sidebar" aria-label="Menu utama pembimbing">
    <div class="sidebar-brand">
        <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
        <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Tutup menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group">
            <div class="nav-group-title">Menu Utama</div>
            <a href="{{ route('pembimbing.dashboard') }}" class="nav-link {{ request()->routeIs('pembimbing.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('pembimbing.mahasiswa') }}" class="nav-link {{ request()->routeIs('pembimbing.mahasiswa*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                <span>Mahasiswa Bimbingan</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-title">Monitoring & Validasi</div>
            <a href="{{ route('pembimbing.absensi') }}" class="nav-link {{ request()->routeIs('pembimbing.absensi*') ? 'active' : '' }}">
                <i class="fa-solid fa-location-dot"></i>
                <span>Presensi & Koreksi</span>
            </a>
            <a href="{{ route('pembimbing.logbook') }}" class="nav-link {{ request()->routeIs('pembimbing.logbook*') ? 'active' : '' }}">
                <i class="fa-solid fa-book-open"></i>
                <span>Logbook PKL</span>
            </a>
            <a href="{{ route('pembimbing.cuti') }}" class="nav-link {{ request()->routeIs('pembimbing.cuti*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Cuti & Izin</span>
            </a>
            <a href="{{ route('pembimbing.aduan') }}" class="nav-link {{ request()->routeIs('pembimbing.aduan*') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>Aduan Masalah</span>
            </a>
            <a href="{{ route('pembimbing.nilai') }}" class="nav-link {{ request()->routeIs('pembimbing.nilai*') ? 'active' : '' }}">
                <i class="fa-solid fa-star-half-stroke"></i>
                <span>Penilaian Magang</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-title">Akun & Profil</div>
            <a href="{{ route('pembimbing.profil') }}" class="nav-link {{ request()->routeIs('pembimbing.profil*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear"></i>
                <span>Profil Saya</span>
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