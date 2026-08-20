<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Mahasiswa - Sistem Magang</title>

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/data.css') }}">
</head>

<body>

    <!-- ================= HEADER ================= -->

    <header class="header">
        <div class="header-left">
            <div class="logo-box">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
            </div>

            <div class="header-title">
                <h2>Monitoring Mahasiswa PKL</h2>
                <p>Kelola dan pantau data mahasiswa PKL dengan mudah dan efisien.</p>
            </div>
        </div>

        <div class="header-right">
            <button type="button" class="menu-btn" id="menuBtn" title="Menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>


    <!-- ================= SIDEBAR ================= -->

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">

        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-regular fa-chart-bar"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.data') }}"
            class="sidebar-menu {{ request()->routeIs('admin.data') ? 'active' : '' }}">
            <i class="fa-regular fa-user"></i>
            <span>Data Mahasiswa</span>
        </a>

        <a href="{{ route('admin.dokumen') }}"
            class="sidebar-menu {{ request()->routeIs('admin.dokumen') ? 'active' : '' }}">
            <i class="fa-regular fa-file-lines"></i>
            <span>Dokumen Verification</span>
        </a>

        <a href="{{ route('admin.sertifikat') }}"
            class="sidebar-menu {{ request()->routeIs('admin.sertifikat') ? 'active' : '' }}">
            <i class="fa-regular fa-file"></i>
            <span>Sertifikat</span>
        </a>

    </aside>


    <!-- ================= CONTENT ================= -->

    <main class="content">

        <!-- PAGE TITLE -->

        <section class="page-title">
            <h1>Data Mahasiswa</h1>
            <p>Kelola, cari, dan pantau seluruh data mahasiswa magang.</p>
        </section>


        <!-- ================= FILTER ================= -->

        <form method="GET"
            action="{{ route('admin.data') }}"
            class="filter-section">

            <div class="filter-header">
                <div class="filter-icon">
                    <i class="fa-solid fa-filter"></i>
                </div>

                <div>
                    <h3>Filter Data Mahasiswa</h3>
                    <p>Gunakan filter untuk mencari data mahasiswa berdasarkan kriteria tertentu.</p>
                </div>
            </div>


            <div class="filter-grid">

                <!-- PENCARIAN -->

                <div class="input-group">
                    <label for="search">Pencarian</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nama / NIM...">
                </div>


                <!-- JURUSAN -->

                <div class="input-group">
                    <label for="jurusan_id">Jurusan</label>

                    <select name="jurusan_id" id="jurusan_id">
                        <option value="">Semua Jurusan</option>

                        @foreach($listJurusan as $j)
                        <option value="{{ $j->id_jurusan }}"
                            {{ request('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <!-- UNIVERSITAS -->

                <div class="input-group">
                    <label for="universitas_id">Universitas</label>

                    <select name="universitas_id" id="universitas_id">
                        <option value="">Semua Universitas</option>

                        @foreach($listUniversitas as $u)
                        <option value="{{ $u->id_universitas }}"
                            {{ request('universitas_id') == $u->id_universitas ? 'selected' : '' }}>
                            {{ $u->nama_universitas }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <!-- DIVISI -->

                <div class="input-group">
                    <label for="divisi_id">Divisi</label>

                    <select name="divisi_id" id="divisi_id">
                        <option value="">Semua Divisi</option>

                        @foreach($listDivisi as $d)
                        <option value="{{ $d->id_divisi }}"
                            {{ request('divisi_id') == $d->id_divisi ? 'selected' : '' }}>
                            {{ $d->nama_divisi }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <!-- JENIS KELAMIN -->

                <div class="input-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>

                    <select name="jenis_kelamin" id="jenis_kelamin">
                        <option value="">Semua Jenis Kelamin</option>

                        <option value="Laki-laki"
                            {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki
                        </option>

                        <option value="Perempuan"
                            {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>
                </div>


                <!-- TANGGAL MULAI -->

                <div class="input-group">
                    <label for="tanggal_mulai">Tanggal Masuk</label>

                    <input
                        type="date"
                        id="tanggal_mulai"
                        name="tanggal_mulai"
                        value="{{ request('tanggal_mulai') }}">
                </div>


                <!-- TANGGAL SELESAI -->

                <div class="input-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>

                    <input
                        type="date"
                        id="tanggal_selesai"
                        name="tanggal_selesai"
                        value="{{ request('tanggal_selesai') }}">
                </div>


                <!-- TAHUN -->

                <div class="input-group">
                    <label for="tahun">Tahun</label>

                    <select name="tahun" id="tahun">
                        <option value="">Semua Tahun</option>

                        @for($i = date('Y') + 2; $i >= 2020; $i--)
                        <option value="{{ $i }}"
                            {{ request('tahun') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                        @endfor
                    </select>
                </div>


                <!-- STATUS -->

                <div class="input-group">
                    <label for="status">Status Magang</label>

                    <select name="status" id="status">
                        <option value="">Semua Status</option>

                        <option value="Akan Magang"
                            {{ request('status') == 'Akan Magang' ? 'selected' : '' }}>
                            Akan Magang
                        </option>

                        <option value="Sedang Magang"
                            {{ request('status') == 'Sedang Magang' ? 'selected' : '' }}>
                            Sedang Magang
                        </option>

                        <option value="Selesai Magang"
                            {{ request('status') == 'Selesai Magang' ? 'selected' : '' }}>
                            Selesai Magang
                        </option>
                    </select>
                </div>

                <!-- URUTKAN -->

                <div class="input-group sort-group">

                    <label for="sort">Urutkan Berdasarkan</label>

                    <select name="sort" id="sort">

                        <option value="terbaru"
                            {{ request('sort', 'terbaru') == 'terbaru' ? 'selected' : '' }}>
                            Terbaru Ditambahkan
                        </option>

                        <option value="terlama"
                            {{ request('sort') == 'terlama' ? 'selected' : '' }}>
                            Terlama Ditambahkan
                        </option>

                        <option value="nama_asc"
                            {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>
                            Nama A–Z
                        </option>

                        <option value="nama_desc"
                            {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>
                            Nama Z–A
                        </option>

                        <option value="mulai_asc"
                            {{ request('sort') == 'mulai_asc' ? 'selected' : '' }}>
                            Tanggal Mulai Terdekat
                        </option>

                        <option value="mulai_desc"
                            {{ request('sort') == 'mulai_desc' ? 'selected' : '' }}>
                            Tanggal Mulai Terjauh
                        </option>

                        <option value="selesai_asc"
                            {{ request('sort') == 'selesai_asc' ? 'selected' : '' }}>
                            Tanggal Selesai Terdekat
                        </option>

                        <option value="selesai_desc"
                            {{ request('sort') == 'selesai_desc' ? 'selected' : '' }}>
                            Tanggal Selesai Terjauh
                        </option>

                    </select>

                </div>

            </div>


            <!-- FILTER ACTIONS -->

            <div class="filter-actions">
                <a href="{{ route('admin.data') }}" class="btn-reset">
                    <i class="fa-solid fa-rotate-left"></i>
                    Reset
                </a>

                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-filter"></i>
                    Terapkan Filter
                </button>
            </div>

        </form>


        <!-- ================= TABLE ================= -->

        <section class="table-card">

            <div class="table-header">

                <div class="table-title">
                    <h3>Data Mahasiswa</h3>
                    <p>Menampilkan data mahasiswa yang terdaftar dalam sistem.</p>
                </div>

                <div class="table-actions">

                    <a href="{{ route('admin.create') }}" class="btn-add">
                        <i class="fa-solid fa-user-plus"></i>
                        Tambah Mahasiswa
                    </a>

                    <a href="{{ route('admin.export', request()->query()) }}" class="btn-export">
                        <i class="fa-solid fa-file-export"></i>
                        Ekspor Data
                    </a>

                </div>

            </div>


            <!-- TABLE -->

            <div class="table-wrapper">
                <table class="table-admin">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>Universitas</th>
                            <th>Jurusan</th>
                            <th>Divisi</th>
                            <th>Periode Magang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pengajuans as $index => $row)

                        <tr>

                            <td>
                                {{ $pengajuans->firstItem() + $index }}
                            </td>

                            <td>
                                {{ $row->mahasiswa->nim ?? '-' }}
                            </td>

                            <td>
                                {{ $row->mahasiswa->nama_lengkap ?? '-' }}
                            </td>

                            <td>
                                {{ $row->mahasiswa->jenis_kelamin ?? '-' }}
                            </td>

                            <td>
                                {{ $row->mahasiswa->universitas->nama_universitas ?? '-' }}
                            </td>

                            <td>
                                {{ $row->mahasiswa->jurusan->nama_jurusan ?? '-' }}
                            </td>

                            <td>
                                {{ $row->divisi->nama_divisi ?? '-' }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y') }}
                                s.d.
                                {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y') }}
                            </td>

                            <td>
                                @if($row->status === 'Akan Magang')
                                <span class="status status-upcoming">
                                    Akan Magang
                                </span>
                                @elseif($row->status === 'Sedang Magang')
                                <span class="status status-active">
                                    Sedang Magang
                                </span>
                                @elseif($row->status === 'Selesai Magang')
                                <span class="status status-finished">
                                    Selesai Magang
                                </span>
                                @else
                                <span class="status status-empty">
                                    -
                                </span>
                                @endif
                            </td>

                            <td>
                                <div class="action">

                                    <a
                                        href="{{ route('admin.detail', $row->id_pengajuan) }}"
                                        class="detail"
                                        title="Detail">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>

                                    <form
                                        action="{{ route('admin.destroy', $row->id_pengajuan) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="delete"
                                            title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="10" class="empty-data">
                                <i class="fa-regular fa-folder-open"></i>
                                <span>Belum ada data mahasiswa yang sesuai dengan filter.</span>
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>


            <!-- PAGINATION -->

            @if($pengajuans->hasPages())
            <div class="pagination-wrapper">

                {{-- Previous --}}
                @if ($pengajuans->onFirstPage())
                <span class="pagination-btn disabled">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
                @else
                <a href="{{ $pengajuans->appends(request()->query())->previousPageUrl() }}"
                    class="pagination-btn">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                @endif

                {{-- Nomor Halaman --}}
                @foreach ($pengajuans->getUrlRange(1, $pengajuans->lastPage()) as $page => $url)

                @if ($page == $pengajuans->currentPage())
                <span class="pagination-btn active">
                    {{ $page }}
                </span>
                @else
                <a href="{{ $pengajuans->appends(request()->query())->url($page) }}"
                    class="pagination-btn">
                    {{ $page }}
                </a>
                @endif

                @endforeach

                {{-- Next --}}
                @if ($pengajuans->hasMorePages())
                <a href="{{ $pengajuans->appends(request()->query())->nextPageUrl() }}"
                    class="pagination-btn">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
                @else
                <span class="pagination-btn disabled">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
                @endif

            </div>
            @endif
        </section>

    </main>


    <!-- ================= SCRIPT ================= -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('menuBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            menuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        });
    </script>

</body>

</html>