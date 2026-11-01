{{--
    SIDEBAR SUPERADMIN
    Untuk menambah / mengubah menu, edit array $menuGroups di bawah.
    - route : nama route
    - icon  : ikon Font Awesome
    - label : teks menu
    - match : daftar route yang membuat menu ini tampil aktif (boleh pakai wildcard)
--}}
@php
    $menuGroups = [
        'Menu Utama' => [
            ['route' => 'superadmin.dashboard',  'icon' => 'fa-users-gear',     'label' => 'Kelola Akun',         'match' => ['superadmin.dashboard', 'superadmin.edit']],
            ['route' => 'superadmin.pembimbing', 'icon' => 'fa-user-tie',       'label' => 'Pembimbing Lapangan', 'match' => ['superadmin.pembimbing*']],
            ['route' => 'superadmin.peserta',    'icon' => 'fa-graduation-cap', 'label' => 'Peserta Magang',      'match' => ['superadmin.peserta*']],
        ],
        'Aksi Cepat' => [
            ['route' => 'superadmin.create',     'icon' => 'fa-user-plus',      'label' => 'Tambah Akun',         'match' => ['superadmin.create']],
        ],
        'Pengaturan' => [
            ['route' => 'superadmin.profile',    'icon' => 'fa-user',           'label' => 'Profile',             'match' => ['superadmin.profile*']],
        ],
    ];
@endphp

<aside class="sidebar" id="sidebar" aria-label="Menu utama">
    <div class="sidebar-brand">
        <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
        <div class="sidebar-brand-text">
            <strong>Monitoring PKL</strong>
            <span>Panel Superadmin</span>
        </div>
        <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Tutup menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        @foreach($menuGroups as $groupTitle => $items)
            <div class="nav-group">
                <div class="nav-group-title">{{ $groupTitle }}</div>
                @foreach($items as $item)
                    @php $isActive = request()->routeIs(...$item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="nav-link {{ $isActive ? 'active' : '' }}"
                       @if($isActive) aria-current="page" @endif>
                        <i class="fa-solid {{ $item['icon'] }}"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        @endforeach
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
