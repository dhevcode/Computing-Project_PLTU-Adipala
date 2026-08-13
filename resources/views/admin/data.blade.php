<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Mahasiswa - Sistem Magang</title>

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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

            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf

                <button type="submit" class="logout" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>

        </div>

    </header>


    <!-- ================= SIDEBAR ================= -->

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

            <i class="fa-regular fa-chart-bar"></i>

            <span>Dashboard</span>

        </a>


        <!-- Data Mahasiswa -->
        <a href="{{ route('admin.data') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-user"></i>

            <span>Data Mahasiswa</span>

        </a>


        <!-- Dokumen Verification -->
        <a href="{{ route('admin.dokumen') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-file-lines"></i>

            <span>Dokumen Verification</span>

        </a>


        <!-- Sertifikat -->
        <a href="{{ route('admin.sertifikat') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-file"></i>

            <span>Sertifikat</span>

        </a>
    </div>


    <!-- ================= CONTENT ================= -->

    <div class="content">

        <!-- PAGE TITLE -->

        <div class="page-title">

            <h1>Data Mahasiswa</h1>

            <p>
                Kelola, cari, dan pantau seluruh data mahasiswa magang.
            </p>

        </div>


        <!-- ================= FILTER ================= -->

        <form method="GET"
            action="{{ route('admin.data') }}"
            class="filter-section">

            <div class="filter-header">

                <div class="filter-icon">
                    <i class="fa-solid fa-filter"></i>
                </div>

                <div>

                    <h3>
                        Filter Data Mahasiswa
                    </h3>

                    <p>
                        Gunakan filter untuk mencari data mahasiswa berdasarkan kriteria tertentu.
                    </p>

                </div>

            </div>


            <div class="filter-grid">

                <!-- PENCARIAN -->

                <div class="input-group">

                    <label>Pencarian</label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nama / NIM...">

                </div>


                <!-- JURUSAN -->

                <div class="input-group">

                    <label>Jurusan</label>

                    <select name="jurusan_id">

                        <option value="">
                            Semua Jurusan
                        </option>

                        @foreach($listJurusan as $j)

                            <option
                                value="{{ $j->id_jurusan }}"
                                {{ request('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>

                                {{ $j->nama_jurusan }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <!-- UNIVERSITAS -->

                <div class="input-group">

                    <label>Universitas</label>

                    <select name="universitas_id">

                        <option value="">
                            Semua Universitas
                        </option>

                        @foreach($listUniversitas as $u)

                            <option
                                value="{{ $u->id_universitas }}"
                                {{ request('universitas_id') == $u->id_universitas ? 'selected' : '' }}>

                                {{ $u->nama_universitas }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <!-- DIVISI -->

                <div class="input-group">

                    <label>Divisi</label>

                    <select name="divisi_id">

                        <option value="">
                            Semua Divisi
                        </option>

                        @foreach($listDivisi as $d)

                            <option
                                value="{{ $d->id_divisi }}"
                                {{ request('divisi_id') == $d->id_divisi ? 'selected' : '' }}>

                                {{ $d->nama_divisi }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <!-- JENIS KELAMIN -->

                <div class="input-group">

                    <label>Jenis Kelamin</label>

                    <select name="jenis_kelamin">

                        <option value="">
                            Semua Jenis Kelamin
                        </option>

                        <option
                            value="Laki-laki"
                            {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>

                            Laki-laki

                        </option>

                        <option
                            value="Perempuan"
                            {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>

                            Perempuan

                        </option>

                    </select>

                </div>


                <!-- TANGGAL MULAI -->

                <div class="input-group">

                    <label>Tanggal Masuk</label>

                    <input
                        type="date"
                        name="tanggal_mulai"
                        value="{{ request('tanggal_mulai') }}">

                </div>


                <!-- TANGGAL SELESAI -->

                <div class="input-group">

                    <label>Tanggal Selesai</label>

                    <input
                        type="date"
                        name="tanggal_selesai"
                        value="{{ request('tanggal_selesai') }}">

                </div>


                <!-- TAHUN -->

                <div class="input-group">

                    <label>Tahun</label>

                    <select name="tahun">

                        <option value="">
                            Semua Tahun
                        </option>

                        @for($i = date('Y') + 2; $i >= 2020; $i--)

                            <option
                                value="{{ $i }}"
                                {{ request('tahun') == $i ? 'selected' : '' }}>

                                {{ $i }}

                            </option>

                        @endfor

                    </select>

                </div>


                <!-- STATUS -->

                <div class="input-group">

                    <label>Status Magang</label>

                    <select name="status">

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="Akan Magang"
                            {{ request('status') == 'Akan Magang' ? 'selected' : '' }}>

                            Akan Magang

                        </option>

                        <option
                            value="Sedang Magang"
                            {{ request('status') == 'Sedang Magang' ? 'selected' : '' }}>

                            Sedang Magang

                        </option>

                        <option
                            value="Selesai Magang"
                            {{ request('status') == 'Selesai Magang' ? 'selected' : '' }}>

                            Selesai Magang

                        </option>

                    </select>

                </div>

            </div>


            <!-- FILTER BUTTON -->

            <div class="filter-actions">

                <a
                    href="{{ route('admin.data') }}"
                    class="btn-reset">

                    <i class="fa-solid fa-rotate-left"></i>

                    Reset

                </a>


                <button
                    type="submit"
                    class="btn-filter">

                    <i class="fa-solid fa-filter"></i>

                    Terapkan Filter

                </button>

            </div>

        </form>


        <!-- ================= TABLE ================= -->

        <div class="table-card">

            <div class="table-header">

                <div class="table-title">

                    <h3>
                        Data Mahasiswa
                    </h3>

                    <p>
                        Menampilkan data mahasiswa yang terdaftar dalam sistem.
                    </p>

                </div>


                <div class="table-actions">

                    <!-- TAMBAH MAHASISWA -->

                    <a
                        href="{{ route('admin.create') }}"
                        class="btn-add">

                        <i class="fa-solid fa-user-plus"></i>

                        Tambah Mahasiswa

                    </a>


                    <!-- EXPORT -->

                    <a
                        href="{{ route('admin.export', request()->query()) }}"
                        class="btn-export">

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

                                <!-- NO -->

                                <td>
                                    {{ $pengajuans->firstItem() + $index }}
                                </td>


                                <!-- NIM -->

                                <td>
                                    {{ $row->mahasiswa->nim ?? '-' }}
                                </td>


                                <!-- NAMA -->

                                <td>
                                    {{ $row->mahasiswa->nama_lengkap ?? '-' }}
                                </td>


                                <!-- JENIS KELAMIN -->

                                <td>
                                    {{ $row->mahasiswa->jenis_kelamin ?? '-' }}
                                </td>


                                <!-- UNIVERSITAS -->

                                <td>
                                    {{ $row->mahasiswa->universitas->nama_universitas ?? '-' }}
                                </td>


                                <!-- JURUSAN -->

                                <td>
                                    {{ $row->mahasiswa->jurusan->nama_jurusan ?? '-' }}
                                </td>


                                <!-- DIVISI -->

                                <td>
                                    {{ $row->divisi->nama_divisi ?? '-' }}
                                </td>


                                <!-- PERIODE -->

                                <td>

                                    {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y') }}

                                    s.d.

                                    {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y') }}

                                </td>


                                <!-- STATUS -->

                                <td>

                                    @if($row->status === 'Akan Magang')

                                        <span
                                            class="status"
                                            style="background:#FFEED9; color:#D9822B;">

                                            Akan Magang

                                        </span>

                                    @elseif($row->status === 'Sedang Magang')

                                        <span
                                            class="status"
                                            style="background:#E8F4F4; color:#2B7C82;">

                                            Sedang Magang

                                        </span>

                                    @elseif($row->status === 'Selesai Magang')

                                        <span
                                            class="status"
                                            style="background:#EAEAEA; color:#666;">

                                            Selesai Magang

                                        </span>

                                    @else

                                        <span
                                            class="status"
                                            style="background:#F5F5F5; color:#888;">

                                            -

                                        </span>

                                    @endif

                                </td>


                                <!-- AKSI -->

                                <td>

                                    <div class="action">

                                        <!-- DETAIL -->

                                        <a href="{{ route('admin.detail', $row->id_pengajuan) }}" class="detail" title="Detail">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>


                                        <!-- HAPUS -->

                                        <form
                                            action="{{ route('admin.destroy', $row->id_pengajuan) }}"
                                            method="POST"
                                            style="display:inline; margin:0;"
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

                                <td
                                    colspan="10"
                                    style="text-align:center; color:#888; padding:30px;">

                                    <i
                                        class="fa-regular fa-folder-open"
                                        style="font-size:25px; margin-bottom:8px; display:block;">

                                    </i>

                                    Belum ada data mahasiswa yang sesuai dengan filter.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <!-- PAGINATION -->

            <div class="pagination-wrapper">

                {{ $pengajuans->appends(request()->query())->links() }}

            </div>

        </div>

    </div>


    <!-- ================= SCRIPT ================= -->

    <script>

        document.addEventListener('DOMContentLoaded', function() {

            const menuBtn = document.getElementById('menuBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');


            // Buka / tutup sidebar

            menuBtn.addEventListener('click', function() {

                sidebar.classList.toggle('active');

                overlay.classList.toggle('active');

            });


            // Tutup sidebar ketika klik area luar

            overlay.addEventListener('click', function() {

                sidebar.classList.remove('active');

                overlay.classList.remove('active');

            });

        });

    </script>

</body>

</html>