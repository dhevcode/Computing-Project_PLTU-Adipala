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
        /* =========================================================
           GLOBAL
        ========================================================= */

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

        .logout {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;
            cursor: pointer;

            border-radius: 8px;

            background: none;
            border: none;

            font-size: 17px;

            transition: .2s;
        }

        .logout:hover {
            background: rgba(255, 255, 255, .15);
        }

        /* ================= LOGO ================= */
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

        /* ================= TITLE ================= */
        .header-left {
            display: flex;
            align-items: center;
            gap: 18px;
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

        /* ================= HEADER RIGHT ================= */
        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-right: 16px;
        }

        /* ================= HAMBURGER BUTTON ================= */
        .menu-btn {
            width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            color: white;
            font-size: 21px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: .2s;
        }

        .menu-btn:hover {
            background: rgba(255, 255, 255, .12);
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            position: fixed;
            top: 82px;
            right: -270px;

            width: 250px;
            height: 470px;

            background: rgba(210, 210, 210, 0.96);
            backdrop-filter: blur(8px);

            border-radius: 25px;

            padding: 25px 18px;

            z-index: 9998;

            box-shadow: -5px 5px 20px rgba(0, 0, 0, .12);

            transition: right .3s ease;
        }

        /* Sidebar ketika dibuka */
        .sidebar.active {
            right: 10px;
        }


        /* ================= SIDEBAR MENU ================= */
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


        /* Hover */
        .sidebar-menu:hover {
            background: rgba(255, 255, 255, .15);
            transform: translateX(3px);
        }


        /* Menu aktif */
        .sidebar-menu.active {
            background: rgba(255, 255, 255, .15);
            font-weight: 500;
        }


        /* ================= SIDEBAR OVERLAY ================= */
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


        /* =========================================================
           CONTENT
        ========================================================= */

        .content {
            margin-top: 25px;

            padding: 90px 18px 30px;
        }


        /* =========================================================
           PAGE TITLE
        ========================================================= */

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


        /* =========================================================
           FILTER CARD
        ========================================================= */

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


        /* FILTER GRID */

        .filter-grid {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 15px;
        }


        /* INPUT GROUP */

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


        /* INPUT & SELECT */

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

            box-shadow:
                0 0 0 3px rgba(10, 109, 113, .12);
        }


        /* FILTER BUTTON */

        .filter-actions {
            margin-top: 17px;

            display: flex;

            justify-content: flex-end;

            gap: 8px;
        }

        .btn-filter {
            height: 36px;

            padding: 0 16px;

            border: none;

            border-radius: 7px;

            background: #0A5F63;

            color: white;

            font-size: 11px;

            font-weight: 600;

            cursor: pointer;
        }

        .btn-reset {
            height: 36px;

            padding: 0 16px;

            border: 1px solid #D8D8D8;

            border-radius: 7px;

            background: white;

            color: #666;

            font-size: 11px;

            font-weight: 600;

            cursor: pointer;
        }


        /* =========================================================
           TABLE CARD
        ========================================================= */

        .table-card {
            background: white;

            border-radius: 14px;

            padding: 20px;

            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);

            border: 1px solid #E5E8EC;
        }


        /* TABLE HEADER */

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


        /* EXPORT BUTTON */

        .btn-export {
            height: 34px;

            padding: 0 14px;

            background: #FFC107;

            color: #333;

            border: none;

            border-radius: 7px;

            font-size: 11px;

            font-weight: 600;

            display: flex;

            align-items: center;

            gap: 7px;

            cursor: pointer;

            text-decoration: none;
        }

        .btn-export:hover {
            transform: translateY(-1px);
        }


        /* =========================================================
           TABLE
        ========================================================= */

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


        /* =========================================================
           STATUS
        ========================================================= */

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


        /* =========================================================
           ACTION
        ========================================================= */

        .action {
            display: flex;

            align-items: center;

            gap: 9px;
        }

        .edit {
            color: #444;

            font-size: 13px;

            text-decoration: none;
        }

        .delete {
            color: #FF5A5A;

            font-size: 13px;

            text-decoration: none;
        }

        .email-btn {
            color: #0A6B70;

            font-size: 13px;

            text-decoration: none;
        }


        /* =========================================================
           PAGINATION
        ========================================================= */

        .pagination {
            margin-top: 18px;

            display: flex;

            justify-content: center;

            align-items: center;

            gap: 5px;
        }

        .page-number {
            width: 30px;
            height: 30px;

            border-radius: 7px;

            border: 1px solid #E0E0E0;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 10px;

            color: #666;

            background: white;
        }

        .page-number.active {
            background: #0A5F63;

            color: white;

            border-color: #0A5F63;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

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

            .btn-add {
                padding: 0 10px;
            }

            .btn-add span {
                display: none;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .table-header {
                align-items: flex-start;

                flex-direction: column;

                gap: 12px;
            }

        }
    </style>
</head>


<body>


    <!-- =========================================================
         HEADER
    ========================================================= -->

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

        <!-- HEADER RIGHT -->
        <div class="header-right">

            <!-- Hamburger -->
            <button type="button" class="menu-btn" id="menuBtn" title="Menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Logout -->
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
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-user"></i>

            <span>Data Mahasiswa</span>

        </a>


        <!-- Dokumen Verification -->
        <a href="#"
            class="sidebar-menu">

            <i class="fa-regular fa-file-lines"></i>

            <span>Dokumen Verification</span>

        </a>


        <!-- Sertifikat -->
        <a href="#"
            class="sidebar-menu">

            <i class="fa-regular fa-file"></i>

            <span>Sertifikat</span>

        </a>


        <!-- Tambah Mahasiswa -->
        <a href="{{ route('admin.create') }}"
            class="sidebar-menu">

            <i class="fa-solid fa-user-plus"></i>

            <span>Tambah Mahasiswa</span>

        </a>

    </div>



    <!-- =========================================================
         CONTENT
    ========================================================= -->

    <div class="content">


        <!-- =====================================================
             FILTER
        ===================================================== -->

        <div class="filter-section">

            <div class="filter-header">

                <div class="filter-icon">
                    <i class="fa-solid fa-filter"></i>
                </div>

                <div>

                    <h3>
                        Filter Data Mahasiswa
                    </h3>

                    <p>
                        Gunakan filter berikut untuk mencari data mahasiswa.
                    </p>

                </div>

            </div>


            <div class="filter-grid">


                <!-- PENCARIAN -->

                <div class="input-group">

                    <label>
                        Pencarian
                    </label>

                    <input
                        type="text"
                        placeholder="Nama / NIM...">

                </div>


                <!-- JURUSAN -->

                <div class="input-group">

                    <label>
                        Jurusan
                    </label>

                    <select>

                        <option>
                            Semua Jurusan
                        </option>

                        <option>
                            Teknik Informatika
                        </option>

                        <option>
                            Teknik Elektro
                        </option>

                        <option>
                            Teknik Mesin
                        </option>

                    </select>

                </div>


                <!-- UNIVERSITAS -->

                <div class="input-group">

                    <label>
                        Universitas
                    </label>

                    <select>

                        <option>
                            Semua Universitas
                        </option>

                        <option>
                            Politeknik Negeri Cilacap
                        </option>

                        <option>
                            Universitas Jenderal Soedirman
                        </option>

                        <option>
                            Universitas Muhammadiyah Purwokerto
                        </option>

                    </select>

                </div>


                <!-- DIVISI -->

                <div class="input-group">

                    <label>
                        Divisi
                    </label>

                    <select>

                        <option>
                            Semua Divisi
                        </option>

                        <option>
                            Operasi
                        </option>

                        <option>
                            Keuangan
                        </option>

                        <option>
                            SDM
                        </option>

                        <option>
                            IT
                        </option>

                    </select>

                </div>


                <!-- JENIS KELAMIN -->

                <div class="input-group">

                    <label>
                        Jenis Kelamin
                    </label>

                    <select>

                        <option>
                            Semua Jenis Kelamin
                        </option>

                        <option>
                            Laki-laki
                        </option>

                        <option>
                            Perempuan
                        </option>

                    </select>

                </div>


                <!-- TANGGAL MASUK -->

                <div class="input-group">

                    <label>
                        Tanggal Masuk
                    </label>

                    <input
                        type="date">

                </div>


                <!-- TANGGAL KELUAR -->

                <div class="input-group">

                    <label>
                        Tanggal Keluar
                    </label>

                    <input
                        type="date">

                </div>


                <!-- TAHUN -->

                <div class="input-group">

                    <label>
                        Tahun
                    </label>

                    <select>

                        <option>
                            Semua Tahun
                        </option>

                        <option>
                            2026
                        </option>

                        <option>
                            2025
                        </option>

                        <option>
                            2024
                        </option>

                        <option>
                            2023
                        </option>

                    </select>

                </div>


                <!-- STATUS -->

                <div class="input-group">

                    <label>
                        Status Magang
                    </label>

                    <select>

                        <option>
                            Semua Status
                        </option>

                        <option>
                            Akan Magang
                        </option>

                        <option>
                            Sedang Magang
                        </option>

                        <option>
                            Selesai Magang
                        </option>

                    </select>

                </div>

            </div>


            <!-- BUTTON FILTER -->

            <div class="filter-actions">

                <button
                    type="button"
                    class="btn-reset">

                    <i class="fa-solid fa-rotate-left"></i>
                    Reset

                </button>


                <button
                    type="button"
                    class="btn-filter">

                    <i class="fa-solid fa-filter"></i>
                    Terapkan Filter

                </button>

            </div>

        </div>



        <!-- =====================================================
             TABLE
        ===================================================== -->

        <div class="table-card">


            <!-- TABLE HEADER -->

            <div class="table-header">

                <div class="table-title">

                    <h3>
                        Data Monitoring Mahasiswa
                    </h3>

                    <p>
                        Menampilkan data monitoring mahasiswa PKL
                    </p>

                </div>


                <!-- EXPORT -->

                <a
                    href="#"
                    class="btn-export">

                    <i class="fa-solid fa-file-export"></i>

                    Ekspor Data

                </a>

            </div>



            <!-- TABLE -->

            <div class="table-wrapper">

                <table class="table-admin">


                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                NIM
                            </th>

                            <th>
                                Nama Lengkap
                            </th>

                            <th>
                                Jenis Kelamin
                            </th>

                            <th>
                                Universitas
                            </th>

                            <th>
                                Jurusan
                            </th>

                            <th>
                                Divisi
                            </th>

                            <th>
                                Periode Magang
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Surat
                            </th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <!-- DATA 1 -->

                        <tr>

                            <td>
                                1
                            </td>

                            <td>
                                220301001
                            </td>

                            <td>
                                Ahmad Fauzan
                            </td>

                            <td>
                                Laki-laki
                            </td>

                            <td>
                                Politeknik Negeri Cilacap
                            </td>

                            <td>
                                Teknik Informatika
                            </td>

                            <td>
                                IT
                            </td>

                            <td>
                                01 Jul 2026 - 30 Sep 2026
                            </td>

                            <td>

                                <span
                                    class="status"
                                    style="
                                        background:#E8F4F4;
                                        color:#2B7C82;
                                    ">

                                    Sedang Magang

                                </span>

                            </td>

                            <td>

                                <a
                                    href="#"
                                    title="Lihat Surat"
                                    style="
                                        color:#0A6B70;
                                        font-size:14px;
                                    ">

                                    <i
                                        class="fa-regular fa-file-pdf">
                                    </i>

                                </a>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="email-btn"
                                        title="Kirim Surat Balasan">

                                        <i
                                            class="fa-regular fa-envelope">
                                        </i>

                                    </a>


                                    <a
                                        href="#"
                                        class="edit"
                                        title="Ubah">

                                        <i
                                            class="fa-regular fa-pen-to-square">
                                        </i>

                                    </a>


                                    <a
                                        href="#"
                                        class="delete"
                                        title="Hapus">

                                        <i
                                            class="fa-regular fa-trash-can">
                                        </i>

                                    </a>

                                </div>

                            </td>

                        </tr>



                        <!-- DATA 2 -->

                        <tr>

                            <td>
                                2
                            </td>

                            <td>
                                220301002
                            </td>

                            <td>
                                Siti Aisyah
                            </td>

                            <td>
                                Perempuan
                            </td>

                            <td>
                                Universitas Jenderal Soedirman
                            </td>

                            <td>
                                Teknik Elektro
                            </td>

                            <td>
                                Operasi
                            </td>

                            <td>
                                15 Jul 2026 - 15 Okt 2026
                            </td>

                            <td>

                                <span
                                    class="status"
                                    style="
                                        background:#E8F4F4;
                                        color:#2B7C82;
                                    ">

                                    Sedang Magang

                                </span>

                            </td>

                            <td>

                                <a
                                    href="#"
                                    style="
                                        color:#0A6B70;
                                        font-size:14px;
                                    ">

                                    <i
                                        class="fa-regular fa-file-pdf">
                                    </i>

                                </a>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="email-btn"
                                        title="Kirim Surat Balasan">

                                        <i
                                            class="fa-regular fa-envelope">
                                        </i>

                                    </a>

                                    <a
                                        href="#"
                                        class="edit"
                                        title="Ubah">

                                        <i
                                            class="fa-regular fa-pen-to-square">
                                        </i>

                                    </a>

                                    <a
                                        href="#"
                                        class="delete"
                                        title="Hapus">

                                        <i
                                            class="fa-regular fa-trash-can">
                                        </i>

                                    </a>

                                </div>

                            </td>

                        </tr>



                        <!-- DATA 3 -->

                        <tr>

                            <td>
                                3
                            </td>

                            <td>
                                220301003
                            </td>

                            <td>
                                Budi Santoso
                            </td>

                            <td>
                                Laki-laki
                            </td>

                            <td>
                                Universitas Muhammadiyah Purwokerto
                            </td>

                            <td>
                                Teknik Mesin
                            </td>

                            <td>
                                Keuangan
                            </td>

                            <td>
                                01 Mei 2026 - 30 Jun 2026
                            </td>

                            <td>

                                <span
                                    class="status"
                                    style="
                                        background:#EAEAEA;
                                        color:#666;
                                    ">

                                    Selesai Magang

                                </span>

                            </td>

                            <td>

                                <a
                                    href="#"
                                    style="
                                        color:#0A6B70;
                                        font-size:14px;
                                    ">

                                    <i
                                        class="fa-regular fa-file-pdf">
                                    </i>

                                </a>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="email-btn"
                                        title="Kirim Surat Balasan">

                                        <i
                                            class="fa-regular fa-envelope">
                                        </i>

                                    </a>

                                    <a
                                        href="#"
                                        class="edit"
                                        title="Ubah">

                                        <i
                                            class="fa-regular fa-pen-to-square">
                                        </i>

                                    </a>

                                    <a
                                        href="#"
                                        class="delete"
                                        title="Hapus">

                                        <i
                                            class="fa-regular fa-trash-can">
                                        </i>

                                    </a>

                                </div>

                            </td>

                        </tr>



                        <!-- DATA 4 -->

                        <tr>

                            <td>
                                4
                            </td>

                            <td>
                                220301004
                            </td>

                            <td>
                                Dinda Maharani
                            </td>

                            <td>
                                Perempuan
                            </td>

                            <td>
                                Politeknik Negeri Cilacap
                            </td>

                            <td>
                                Teknik Informatika
                            </td>

                            <td>
                                SDM
                            </td>

                            <td>
                                01 Agu 2026 - 31 Okt 2026
                            </td>

                            <td>

                                <span
                                    class="status"
                                    style="
                                        background:#FFEED9;
                                        color:#D9822B;
                                    ">

                                    Akan Magang

                                </span>

                            </td>

                            <td>
                                -
                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="email-btn"
                                        title="Kirim Surat Balasan">

                                        <i
                                            class="fa-regular fa-envelope">
                                        </i>

                                    </a>

                                    <a
                                        href="#"
                                        class="edit"
                                        title="Ubah">

                                        <i
                                            class="fa-regular fa-pen-to-square">
                                        </i>

                                    </a>

                                    <a
                                        href="#"
                                        class="delete"
                                        title="Hapus">

                                        <i
                                            class="fa-regular fa-trash-can">
                                        </i>

                                    </a>

                                </div>

                            </td>

                        </tr>


                    </tbody>

                </table>

            </div>



            <!-- PAGINATION -->

            <div class="pagination">

                <div class="page-number">
                    <i class="fa-solid fa-chevron-left"></i>
                </div>

                <div class="page-number active">
                    1
                </div>

                <div class="page-number">
                    2
                </div>

                <div class="page-number">
                    3
                </div>

                <div class="page-number">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>

            </div>


        </div>


    </div>

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