<aside class="sidebar" id="sidebar" aria-label="Menu utama">
    <div class="sidebar-brand">
        <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
        <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Tutup menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        @php
            $isAlumni = Auth::user() ? Auth::user()->isAlumni() : false;
        @endphp
        <div class="nav-group">
            <div class="nav-group-title">{{ $isAlumni ? 'Menu Alumni (Arsip)' : 'Menu Utama' }}</div>
            <a href="{{ route('peserta.dashboard') }}" class="nav-link {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('peserta.absensi') }}" class="nav-link {{ request()->routeIs('peserta.absensi*') ? 'active' : '' }}">
                <i class="fa-solid fa-location-dot"></i>
                <span>{{ $isAlumni ? 'Arsip Presensi' : 'Presensi & Koreksi' }}</span>
            </a>
            <a href="{{ route('peserta.logbook') }}" class="nav-link {{ request()->routeIs('peserta.logbook*') ? 'active' : '' }}">
                <i class="fa-solid fa-book-open"></i>
                <span>{{ $isAlumni ? 'Arsip Logbook' : 'Logbook Harian' }}</span>
            </a>
            <a href="{{ route('peserta.nilai') }}" class="nav-link {{ request()->routeIs('peserta.nilai*') ? 'active' : '' }}">
                <i class="fa-solid fa-star-half-stroke"></i>
                <span>{{ $isAlumni ? 'Transkrip Nilai Akhir' : 'Arsip Nilai' }}</span>
            </a>
            @if(!$isAlumni)
                <a href="{{ route('peserta.cuti') }}" class="nav-link {{ request()->routeIs('peserta.cuti*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Cuti & Izin</span>
                </a>
            @endif
        </div>

        <div class="nav-group">
            <div class="nav-group-title">{{ $isAlumni ? 'Layanan & Bantuan' : 'Aktivitas & Evaluasi' }}</div>
            @if(!$isAlumni)
                <a href="{{ route('peserta.aduan') }}" class="nav-link {{ request()->routeIs('peserta.aduan*') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>Aduan Masalah</span>
                </a>
            @else
                <a href="{{ route('peserta.cuti') }}" class="nav-link {{ request()->routeIs('peserta.cuti*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Riwayat Izin / Cuti</span>
                </a>
            @endif
        </div>

        <div class="nav-group">
            <div class="nav-group-title">Informasi & Pengaturan</div>
            <a href="{{ route('peserta.informasi') }}" class="nav-link {{ request()->routeIs('peserta.informasi*') ? 'active' : '' }}">
                <i class="fa-solid fa-building"></i>
                <span>Info & Panduan</span>
            </a>
            <a href="{{ route('peserta.profil') }}" class="nav-link {{ request()->routeIs('peserta.profil*') ? 'active' : '' }}">
                <i class="fa-solid fa-user"></i>
                <span>Profil Saya</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout" title="Logout dari Sistem" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>