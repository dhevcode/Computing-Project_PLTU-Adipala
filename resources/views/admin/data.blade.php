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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image: url('{{ asset("assets/image/bg-pln.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            color: #333;
        }

        /* ================= HEADER ================= */

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 72px;
            background: #0A5F63;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 9999;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .logo-box {
            width: 230px;
            height: 72px;
            background: white;
            clip-path: polygon(0 0, 100% 0, 82% 100%, 0 100%);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-box img {
            width: 150px;
        }

        .header-title {
            margin-left: 16px;
            color: white;
        }

        .header-title h2 {
            font-size: 18px;
            font-weight: 700;
            line-height: 1;
        }

        .header-title p {
            font-size: 10px;
            margin-top: 3px;
            opacity: .9;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-right: 16px;
        }

        .menu-btn,
        .logout {
            width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            color: white;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: .2s;
        }

        .menu-btn:hover,
        .logout:hover {
            background: rgba(255, 255, 255, .15);
        }

        /* ================= SIDEBAR ================= */

        .sidebar {
            position: fixed;
            top: 82px;
            right: -270px;
            width: 250px;
            height: 420px;
            background: rgba(210, 210, 210, 0.96);
            backdrop-filter: blur(8px);
            border-radius: 25px;
            padding: 25px 18px;
            z-index: 9998;
            box-shadow: -5px 5px 20px rgba(0, 0, 0, .12);
            transition: right .3s ease;
        }

        .sidebar.active {
            right: 10px;
        }

        .sidebar-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 11px 10px;
            margin-bottom: 8px;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 400;
            transition: .2s;
        }

        .sidebar-menu i {
            width: 30px;
            font-size: 23px;
            text-align: center;
        }

        .sidebar-menu:hover,
        .sidebar-menu.active {
            background: rgba(255, 255, 255, .15);
            transform: translateX(3px);
        }

        .sidebar-menu.active {
            font-weight: 500;
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .15);
            z-index: 9997;
            opacity: 0;
            visibility: hidden;
            transition: .3s;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* ================= CONTENT ================= */

        .content {
            margin-top: 25px;
            padding: 90px 18px 30px;
        }

        .page-title {
            margin-bottom: 18px;
        }

        .page-title h1 {
            color: #0A5F63;
            font-size: 22px;
            font-weight: 700;
        }

        .page-title p {
            color: #777;
            font-size: 11px;
            margin-top: 3px;
        }

        /* ================= FILTER ================= */

        .filter-section {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
            border: 1px solid #E5E8EC;
            margin-bottom: 20px;
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 17px;
        }

        .filter-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #E8F5F5;
            color: #0A5F63;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .filter-header h3 {
            font-size: 15px;
            font-weight: 700;
            color: #0A6B70;
        }

        .filter-header p {
            font-size: 10px;
            color: #888;
            margin-top: 2px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .input-group label {
            font-size: 11px;
            font-weight: 600;
            color: #4E4E4E;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            height: 38px;
            border: 1px solid #E4E4E4;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 11px;
            outline: none;
            background: white;
            color: #444;
            transition: .2s;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #0A6D71;
            box-shadow: 0 0 0 3px rgba(10, 109, 113, .12);
        }

        .filter-actions {
            margin-top: 17px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-filter,
        .btn-reset {
            height: 36px;
            padding: 0 16px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-filter {
            border: none;
            background: #0A5F63;
            color: white;
        }

        .btn-reset {
            border: 1px solid #D8D8D8;
            background: white;
            color: #666;
        }

        .btn-filter:hover {
            background: #084C4F;
        }

        .btn-reset:hover {
            background: #F5F5F5;
        }

        /* ================= TABLE ================= */

        .table-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
            border: 1px solid #E5E8EC;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .table-title h3 {
            color: #0A6B70;
            font-size: 17px;
            font-weight: 700;
        }

        .table-title p {
            color: #777;
            font-size: 10px;
            margin-top: 3px;
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add,
        .btn-export {
            height: 34px;
            padding: 0 14px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-add {
            background: #0A5F63;
            color: white;
        }

        .btn-add:hover {
            background: #084C4F;
        }

        .btn-export {
            background: #FFC107;
            color: #333;
        }

        .btn-export:hover {
            background: #E0A800;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .table-admin {
            width: 100%;
            border-collapse: collapse;
            min-width: 1050px;
        }

        .table-admin thead th {
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            color: #4E4E4E;
            padding: 11px 8px;
            background: #F8FAFA;
            border-bottom: 1px solid #D8D8D8;
            white-space: nowrap;
        }

        .table-admin tbody td {
            font-size: 11px;
            color: #2F2F2F;
            padding: 13px 8px;
            border-bottom: 1px solid #E9E9E9;
            white-space: nowrap;
        }

        .table-admin tbody tr:hover {
            background: #FAFEFE;
        }

        /* ================= STATUS ================= */

        .status {
            display: inline-flex;
            min-width: 90px;
            height: 23px;
            padding: 0 10px;
            border-radius: 999px;
            justify-content: center;
            align-items: center;
            font-size: 9px;
            font-weight: 600;
        }

        /* ================= ACTION ================= */

        .action {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail {
            color: #0A6B70;
            font-size: 14px;
            text-decoration: none;
        }

        .delete {
            color: #FF5A5A;
            font-size: 14px;
            text-decoration: none;
            border: none;
            background: none;
            cursor: pointer;
            padding: 0;
        }

        .detail:hover {
            color: #084C4F;
        }

        .delete:hover {
            color: #D93636;
        }

        /* ================= PAGINATION ================= */

        .pagination-wrapper {
            margin-top: 18px;
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper nav {
            display: flex;
            justify-content: center;
        }

        /* ================= RESPONSIVE ================= */

        @media(max-width: 1100px) {
            .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width: 700px) {
            .header-title {
                display: none;
            }

            .logo-box {
                width: 170px;
            }

            .logo-box img {
                width: 120px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .table-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .table-actions {
                width: 100%;
            }

            .btn-add,
            .btn-export {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
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