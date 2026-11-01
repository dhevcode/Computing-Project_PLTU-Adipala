{{--
    HEADER SUPERADMIN
    Judul halaman diisi dari tiap halaman lewat:
        @section('page_title', 'Judul')
        @section('page_subtitle', 'Keterangan singkat')
--}}
@php
    $namaUser = optional(auth()->user())->nama_lengkap ?? 'Super Admin';
@endphp

<header class="topbar">
    <div class="topbar-left">
        <button type="button" class="menu-toggle" id="sidebarToggle" aria-label="Buka menu" aria-expanded="false" aria-controls="sidebar">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="topbar-title">
            <h1>@yield('page_title', 'Dashboard')</h1>
            <p>@yield('page_subtitle', 'Sistem Pengelolaan & Kontrol Akses Administrator PLTU')</p>
        </div>
    </div>

    <div class="topbar-right">
        <a href="{{ route('superadmin.create') }}" class="btn-topbar-action" title="Tambah Akun">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Akun</span>
        </a>

        <a href="{{ route('superadmin.profile') }}" class="user-chip" title="Profile">
            <div class="avatar-circle">{{ strtoupper(substr($namaUser, 0, 1)) }}</div>
            <div class="user-chip-text">
                <strong>{{ $namaUser }}</strong>
                <span>Super Admin</span>
            </div>
        </a>
    </div>
</header>
