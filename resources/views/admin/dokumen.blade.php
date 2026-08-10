<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Dokumen Mahasiswa - Monitoring Mahasiswa PKL
    </title>


    <!-- =====================================================
         FONT & FONT AWESOME
    ====================================================== -->

    <link rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


    <style>
        /* =====================================================
           RESET
        ====================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;

            font-family: 'Poppins', sans-serif;
        }


        /* =====================================================
           BODY
        ====================================================== */

        body {

            min-height: 100vh;

            background-image: url('{{ asset("assets/image/bg-pln.png") }}');

            background-size: cover;

            background-position: center;

            background-repeat: no-repeat;

            background-attachment: fixed;

            position: relative;

            color: #222;

        }


        /*
         * Overlay supaya background tetap terlihat
         * tetapi content lebih jelas.
         */

        body::before {

            content: "";

            position: fixed;

            inset: 0;

            background:
                rgba(0, 85, 89, .42);

            z-index: -1;

        }


        /* =====================================================
           HEADER
        ====================================================== */

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

            box-shadow:
                0 4px 15px rgba(0, 0, 0, .08);

        }


        /* =====================================================
           LOGO
        ====================================================== */

        .logo-box {

            width: 230px;

            height: 72px;

            background: white;

            clip-path:
                polygon(0 0,
                    100% 0,
                    82% 100%,
                    0 100%);

            display: flex;

            justify-content: center;

            align-items: center;

        }


        .logo-box img {

            width: 150px;

            display: block;

        }


        /* =====================================================
           HEADER LEFT
        ====================================================== */

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

            margin-top: 4px;

            opacity: .9;

        }


        /* =====================================================
           HEADER RIGHT
        ====================================================== */

        .header-right {

            display: flex;

            align-items: center;

            gap: 10px;

            padding-right: 16px;

        }


        /* =====================================================
           HAMBURGER
        ====================================================== */

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

            background:
                rgba(255, 255, 255, .12);

        }


        /* =====================================================
           LOGOUT
        ====================================================== */

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

            background:
                rgba(255, 255, 255, .15);

        }


        /* =====================================================
           SIDEBAR
        ====================================================== */

        .sidebar {

            position: fixed;

            top: 82px;

            right: -270px;

            width: 250px;

            height: 470px;

            background:
                rgba(210, 210, 210, .96);

            backdrop-filter: blur(8px);

            border-radius: 25px;

            padding: 25px 18px;

            z-index: 9998;

            box-shadow:
                -5px 5px 20px rgba(0, 0, 0, .12);

            transition:
                right .3s ease;

        }


        .sidebar.active {

            right: 10px;

        }


        /* =====================================================
           SIDEBAR MENU
        ====================================================== */

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


        .sidebar-menu:hover {

            background:
                rgba(255, 255, 255, .15);

            transform:
                translateX(3px);

        }


        .sidebar-menu.active {

            background:
                rgba(255, 255, 255, .15);

            font-weight: 500;

        }


        /* =====================================================
           SIDEBAR OVERLAY
        ====================================================== */

        .sidebar-overlay {

            position: fixed;

            inset: 0;

            background:
                rgba(0, 0, 0, .15);

            z-index: 9997;

            opacity: 0;

            visibility: hidden;

            transition: .3s;

        }


        .sidebar-overlay.active {

            opacity: 1;

            visibility: visible;

        }


        /* =====================================================
           MAIN CONTENT
        ====================================================== */

        .content {

            width: 100%;

            max-width: 1320px;

            margin: 0 auto;

            padding:
                98px 24px 45px;

        }


        /* =====================================================
           STATISTIC CARDS
        ====================================================== */

        .cards {

            width: 100%;

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 24px;

            margin-bottom: 26px;

        }


        .card {

            position: relative;

            background: white;

            border-radius: 16px;

            padding: 26px 28px;

            min-height: 120px;

            border: 1px solid #E5E8EC;

            box-shadow:
                0 5px 16px rgba(0, 0, 0, .08);

        }


        .card-title {

            color: #0A6B70;

            font-size: 15px;

            font-weight: 500;

            margin-bottom: 10px;

        }


        .card-value {

            color: #0A6B70;

            font-size: 34px;

            font-weight: 700;

            line-height: 1;

        }


        .icon-circle {

            position: absolute;

            right: 22px;

            top: 20px;

            width: 34px;

            height: 34px;

            border-radius: 50%;

            display: flex;

            justify-content: center;

            align-items: center;

            background: #E8F5F5;

            color: #0A5F63;

            font-size: 14px;

        }


        /* =====================================================
           FILTER SECTION
        ====================================================== */

        .filter-section {

            width: 100%;

            background: white;

            border-radius: 16px;

            padding: 27px 28px 28px;

            margin-bottom: 22px;

            border: 1px solid #E5E8EC;

            box-shadow:
                0 5px 16px rgba(0, 0, 0, .08);

        }


        /* =====================================================
           FILTER HEADER
        ====================================================== */

        .filter-header {

            display: flex;

            justify-content: space-between;

            align-items: flex-start;

            margin-bottom: 20px;

        }


        .filter-title {

            color: #0A6B70;

            font-size: 19px;

            font-weight: 700;

            line-height: 1.2;

        }


        .filter-subtitle {

            color: #777;

            font-size: 11px;

            margin-top: 4px;

        }


        .reset-filter {

            color: #555;

            font-size: 11px;

            text-decoration: none;

            margin-top: 3px;

            transition: .2s;

        }


        .reset-filter:hover {

            color: #0A6B70;

        }


        /* =====================================================
           FILTER GRID
        ====================================================== */

        .filter-grid {

            display: grid;

            grid-template-columns:
                2fr 1.15fr 1.15fr 1.15fr 1.15fr 1.15fr;

            gap: 18px;

            width: 100%;

        }


        .input-group {

            display: flex;

            flex-direction: column;

            gap: 7px;

            min-width: 0;

        }


        .input-group label {

            font-size: 11px;

            font-weight: 500;

            color: #555;

        }


        .input-group input,
        .input-group select {

            width: 100%;

            height: 42px;

            border:
                1px solid #DCDCDC;

            border-radius: 8px;

            padding:
                0 12px;

            font-size: 11px;

            outline: none;

            background: white;

            color: #444;

            transition: .2s;

        }


        .input-group input::placeholder {

            color: #999;

        }


        .input-group input:focus,
        .input-group select:focus {

            border-color: #0A6D71;

            box-shadow:
                0 0 0 3px rgba(10, 109, 113, .10);

        }


        /* =====================================================
           FILTER ROW 2
        ====================================================== */

        .filter-row-2 {

            display: grid;

            grid-template-columns:
                190px 190px;

            gap: 18px;

            margin-top: 17px;

        }


        /* =====================================================
           TABLE CARD
        ====================================================== */

        .table-card {

            width: 100%;

            background: white;

            border-radius: 16px;

            padding: 25px 28px 28px;

            border: 1px solid #E5E8EC;

            box-shadow:
                0 5px 16px rgba(0, 0, 0, .08);

            overflow: hidden;

        }


        .table-wrapper {

            width: 100%;

            overflow-x: auto;

        }


        /* =====================================================
           TABLE
        ====================================================== */

        .table-admin {

            width: 100%;

            border-collapse: collapse;

            min-width: 1000px;

        }


        .table-admin thead th {

            text-align: left;

            font-size: 11px;

            font-weight: 500;

            color: #4E4E4E;

            padding:
                11px 7px;

            border-bottom:
                1px solid #D5D5D5;

            white-space: nowrap;

        }


        .table-admin tbody td {

            font-size: 11px;

            color: #2F2F2F;

            padding:
                15px 7px;

            border-bottom:
                1px solid #E0E0E0;

            white-space: nowrap;

            vertical-align: middle;

        }


        .table-admin tbody tr {

            transition: .15s;

        }


        .table-admin tbody tr:hover {

            background:
                #FAFCFC;

        }


        .table-admin tbody td strong {

            font-size: 11px;

            font-weight: 600;

            color: #222;

        }


        .table-admin tbody td small {

            display: inline-block;

            margin-top: 2px;

            font-size: 9px;

            font-style: italic;

            color: #999;

        }


        /* =====================================================
           STATUS
        ====================================================== */

        .status {

            display: inline-flex;

            justify-content: center;

            align-items: center;

            min-width: 82px;

            height: 27px;

            padding:
                0 14px;

            border-radius: 999px;

            font-size: 10px;

            font-weight: 500;

        }


        .status-verified {

            background: #20C95A;

            color: white;

        }


        .status-notverified {

            background: #B8B8B8;

            color: white;

        }


        /* =====================================================
           ACTION
        ====================================================== */

        .action {

            display: flex;

            align-items: center;

            justify-content: flex-start;

            gap: 7px;

        }


        /* =====================================================
           BUTTON
        ====================================================== */

        .btn-document {

            height: 32px;

            padding:
                0 13px;

            border-radius: 7px;

            font-size: 10px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            cursor: pointer;

            text-decoration: none;

            font-family:
                'Poppins', sans-serif;

            white-space: nowrap;

            transition: .2s;

        }


        .btn-document i {

            font-size: 10px;

        }


        /* =====================================================
           UPLOAD
        ====================================================== */

        .btn-upload {

            background: #4C9FA3;

            color: white;

            border: 1px solid #4C9FA3;

        }


        .btn-upload:hover {

            background: #0A6B70;

            border-color: #0A6B70;

        }


        /* =====================================================
           VIEW
        ====================================================== */

        .btn-view {

            background: white;

            color: #444;

            border:
                1px solid #D5D5D5;

        }


        .btn-view:hover {

            background: #F5F5F5;

        }


        /* =====================================================
           CHANGE
        ====================================================== */

        .btn-change {

            background: #4C9FA3;

            color: white;

            border:
                1px solid #4C9FA3;

        }


        .btn-change:hover {

            background: #0A6B70;

        }


        /* =====================================================
           RESPONSIVE 1200
        ====================================================== */

        @media(max-width: 1200px) {

            .content {

                padding-left: 20px;

                padding-right: 20px;

            }


            .filter-grid {

                grid-template-columns:
                    repeat(3, 1fr);

            }

        }


        /* =====================================================
           RESPONSIVE 900
        ====================================================== */

        @media(max-width: 900px) {

            .cards {

                grid-template-columns:
                    repeat(3, 1fr);

                gap: 15px;

            }


            .card {

                padding: 22px;

                min-height: 110px;

            }


            .card-title {

                font-size: 13px;

            }


            .card-value {

                font-size: 29px;

            }


            .filter-grid {

                grid-template-columns:
                    repeat(2, 1fr);

            }

        }


        /* =====================================================
           RESPONSIVE 700
        ====================================================== */

        @media(max-width: 700px) {

            .logo-box {

                width: 180px;

            }


            .logo-box img {

                width: 120px;

            }


            .header-title h2 {

                font-size: 15px;

            }


            .header-title p {

                font-size: 8px;

            }


            .cards {

                grid-template-columns:
                    1fr;

            }


            .filter-grid {

                grid-template-columns:
                    repeat(2, 1fr);

            }


            .filter-row-2 {

                grid-template-columns:
                    1fr 1fr;

            }

        }


        /* =====================================================
           RESPONSIVE 550
        ====================================================== */

        @media(max-width: 550px) {

            .header-title {

                display: none;

            }


            .logo-box {

                width: 170px;

            }


            .content {

                padding:
                    90px 12px 30px;

            }


            .filter-section {

                padding:
                    22px 18px;

            }


            .table-card {

                padding:
                    20px 18px;

            }


            .filter-grid {

                grid-template-columns:
                    1fr;

            }


            .filter-row-2 {

                grid-template-columns:
                    1fr;

            }


            .filter-title {

                font-size: 17px;

            }

        }

        /* =====================================================
   MODAL UPLOAD SURAT BALASAN
===================================================== */

        .modal-overlay {
            position: fixed;
            inset: 0;

            background: rgba(0, 0, 0, 0.35);

            display: flex;
            align-items: center;
            justify-content: center;

            z-index: 10000;

            opacity: 0;
            visibility: hidden;

            transition: opacity .2s ease, visibility .2s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }


        /* ================= MODAL ================= */

        .upload-modal {
            width: 520px;
            max-width: calc(100% - 40px);

            background: #ffffff;

            border-radius: 16px;

            padding: 24px 26px 20px;

            box-shadow:
                0 15px 45px rgba(0, 0, 0, .20);

            transform: scale(.95) translateY(10px);

            transition: transform .2s ease;
        }

        .modal-overlay.active .upload-modal {
            transform: scale(1) translateY(0);
        }


        /* ================= JUDUL ================= */

        .modal-title {
            color: #1F1F1F;

            font-size: 17px;

            font-weight: 600;

            line-height: 1.3;

            margin-bottom: 5px;
        }


        /* ================= DESKRIPSI ================= */

        .modal-description {
            color: #666;

            font-size: 11px;

            line-height: 1.55;

            margin-bottom: 17px;
        }


        /* ================= LABEL ================= */

        .modal-label {
            display: block;

            color: #333;

            font-size: 11px;

            font-weight: 500;

            margin-bottom: 7px;
        }


        /* ================= FILE INPUT ================= */

        .modal-file {
            width: 100%;

            height: 42px;

            border: 1px solid #999;

            border-radius: 8px;

            padding: 0 10px;

            background: white;

            color: #222;

            font-family: 'Poppins', sans-serif;

            font-size: 11px;

            cursor: pointer;

            outline: none;
        }

        .modal-file:hover {
            border-color: #4C9FA3;
        }

        .modal-file:focus {
            border-color: #4C9FA3;

            box-shadow:
                0 0 0 3px rgba(76, 159, 163, .12);
        }


        /* ================= EMAIL ================= */

        .modal-email {
            margin-top: 8px;

            color: #666;

            font-size: 11px;

            line-height: 1.5;
        }

        .modal-email span {
            color: #555;

            font-weight: 400;
        }


        /* ================= BUTTON AREA ================= */

        .modal-actions {
            display: flex;

            justify-content: flex-end;

            align-items: center;

            gap: 8px;

            margin-top: 16px;
        }


        /* ================= BATAL ================= */

        .btn-modal-cancel {
            height: 32px;

            padding: 0 16px;

            border-radius: 7px;

            background: white;

            border: 1px solid #A8D1D3;

            color: #4C9FA3;

            font-family: 'Poppins', sans-serif;

            font-size: 10px;

            cursor: pointer;

            transition: .2s;
        }

        .btn-modal-cancel:hover {
            background: #F1F9F9;
        }


        /* ================= SIMPAN ================= */

        .btn-modal-submit {
            height: 32px;

            padding: 0 15px;

            border-radius: 7px;

            background: #4C9FA3;

            border: 1px solid #4C9FA3;

            color: white;

            font-family: 'Poppins', sans-serif;

            font-size: 10px;

            cursor: pointer;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            transition: .2s;
        }

        .btn-modal-submit:hover {
            background: #0A6B70;

            border-color: #0A6B70;
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 600px) {

            .upload-modal {
                width: calc(100% - 30px);

                padding: 21px;
            }

            .modal-title {
                font-size: 16px;
            }

            .modal-actions {
                margin-top: 14px;
            }

        }
    </style>

</head>


<body>


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <header class="header">

        <div class="header-left">

            <div class="logo-box">

                <img
                    src="{{ asset('assets/image/logo-pln.png') }}"
                    alt="Logo PLN">

            </div>


            <div class="header-title">

                <h2>
                    Monitoring Mahasiswa PKL
                </h2>

                <p>
                    Kelola dan pantau data mahasiswa PKL dengan mudah dan efisien.
                </p>

            </div>

        </div>


        <!-- HEADER RIGHT -->

        <div class="header-right">

            <!-- HAMBURGER -->

            <button
                type="button"
                class="menu-btn"
                id="menuBtn"
                title="Menu">

                <i class="fa-solid fa-bars"></i>

            </button>


            <!-- LOGOUT -->

            <form
                action="{{ route('logout') }}"
                method="POST"
                style="margin: 0;">

                @csrf

                <button
                    type="submit"
                    class="logout"
                    title="Logout">

                    <i class="fa-solid fa-arrow-right-from-bracket"></i>

                </button>

            </form>

        </div>

    </header>



    <!-- =====================================================
         SIDEBAR OVERLAY
    ====================================================== -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay">
    </div>



    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <div
        class="sidebar"
        id="sidebar">


        <!-- DASHBOARD -->

        <a
            href="{{ route('admin.dashboard') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-chart-bar"></i>

            <span>
                Dashboard
            </span>

        </a>


        <!-- DATA MAHASISWA -->

        <a
            href="#"
            class="sidebar-menu">

            <i class="fa-regular fa-user"></i>

            <span>
                Data Mahasiswa
            </span>

        </a>


        <!-- DOKUMEN -->

        <a
            href="{{ route('admin.dokumen') }}"
            class="sidebar-menu active">

            <i class="fa-regular fa-file-lines"></i>

            <span>
                Dokumen Verification
            </span>

        </a>


        <!-- SERTIFIKAT -->

        <a
            href="#"
            class="sidebar-menu">

            <i class="fa-regular fa-file"></i>

            <span>
                Sertifikat
            </span>

        </a>


        <!-- TAMBAH MAHASISWA -->

        <a
            href="{{ route('admin.create') }}"
            class="sidebar-menu">

            <i class="fa-solid fa-user-plus"></i>

            <span>
                Tambah Mahasiswa
            </span>

        </a>

    </div>



    <!-- =====================================================
         CONTENT
    ====================================================== -->

    <div class="content">


        <!-- =================================================
             STATISTIC CARDS
        ================================================== -->

        <div class="cards">


            <!-- VERIFIED -->

            <div class="card">

                <div class="icon-circle">

                    <i class="fa-regular fa-circle-check"></i>

                </div>

                <div class="card-title">
                    Verified
                </div>

                <div class="card-value">
                    1
                </div>

            </div>



            <!-- NOT VERIFIED -->

            <div class="card">

                <div class="icon-circle">

                    <i class="fa-regular fa-circle-check"></i>

                </div>

                <div class="card-title">
                    Not verified
                </div>

                <div class="card-value">
                    3
                </div>

            </div>



            <!-- TOTAL -->

            <div class="card">

                <div class="icon-circle">

                    <i class="fa-regular fa-circle-check"></i>

                </div>

                <div class="card-title">
                    Total Ditampilkan
                </div>

                <div class="card-value">
                    4
                </div>

            </div>

        </div>



        <!-- =================================================
             FILTER DATA
        ================================================== -->

        <form class="filter-section">


            <div class="filter-header">

                <div>

                    <div class="filter-title">
                        Filter data
                    </div>

                    <div class="filter-subtitle">
                        Menampilkan 4 dari 4 mahasiswa
                    </div>

                </div>


                <a
                    href="#"
                    class="reset-filter">

                    Reset Filter

                </a>

            </div>



            <!-- FILTER ROW 1 -->

            <div class="filter-grid">


                <!-- CARI -->

                <div class="input-group">

                    <label>
                        Cari
                    </label>

                    <input
                        type="text"
                        placeholder="Nama, NIM, kampus...">

                </div>



                <!-- TANGGAL MULAI -->

                <div class="input-group">

                    <label>
                        Tanggal mulai
                    </label>

                    <input type="date">

                </div>



                <!-- TANGGAL SELESAI -->

                <div class="input-group">

                    <label>
                        Tanggal selesai
                    </label>

                    <input type="date">

                </div>



                <!-- JURUSAN -->

                <div class="input-group">

                    <label>
                        Jurusan
                    </label>

                    <select>

                        <option>
                            Semua
                        </option>

                        <option>
                            Informatika
                        </option>

                        <option>
                            Administrasi
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
                            Semua
                        </option>

                        <option>
                            PNC
                        </option>

                        <option>
                            Universitas Jenderal Soedirman
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
                            Semua
                        </option>

                        <option>
                            Umum
                        </option>

                        <option>
                            Keuangan
                        </option>

                    </select>

                </div>

            </div>



            <!-- FILTER ROW 2 -->

            <div class="filter-row-2">


                <!-- STATUS -->

                <div class="input-group">

                    <label>
                        Status
                    </label>

                    <select>

                        <option>
                            Semua
                        </option>

                        <option>
                            Verified
                        </option>

                        <option>
                            Not verified
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
                            Semua
                        </option>

                        <option>
                            Laki-laki
                        </option>

                        <option>
                            Perempuan
                        </option>

                    </select>

                </div>

            </div>

        </form>



        <!-- =================================================
             TABLE DATA
        ================================================== -->

        <div class="table-card">

            <div class="table-wrapper">

                <table class="table-admin">


                    <thead>

                        <tr>

                            <th>
                                Mahasiswa
                            </th>

                            <th>
                                Universitas
                            </th>

                            <th>
                                Prodi
                            </th>

                            <th>
                                Divisi
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                        <!-- =================================================
                             DATA 1
                        ================================================== -->

                        <tr>

                            <td>

                                <strong>
                                    Namira
                                </strong>

                                <br>

                                <small>
                                    202020
                                </small>

                            </td>


                            <td>
                                PNC
                            </td>


                            <td>
                                Administrasi
                            </td>


                            <td>
                                Keuangan
                            </td>


                            <td>
                                namira@gmail.com
                            </td>


                            <td>

                                <span
                                    class="status status-notverified">

                                    Not verified

                                </span>

                            </td>


                            <td>

                                <div class="action">

                                    <button
                                        type="button"
                                        class="btn-document btn-upload upload-surat-btn"
                                        data-nama="Namira"
                                        data-email="namiradavinarm@gmail.com">

                                        <i class="fa-solid fa-upload"></i>

                                        Upload Surat

                                    </button>

                                </div>

                            </td>

                        </tr>



                        <!-- =================================================
                             DATA 2
                        ================================================== -->

                        <tr>

                            <td>

                                <strong>
                                    Tsamara
                                </strong>

                                <br>

                                <small>
                                    202020
                                </small>

                            </td>


                            <td>
                                PNC
                            </td>


                            <td>
                                Informatika
                            </td>


                            <td>
                                Umum
                            </td>


                            <td>
                                tsamara@gmail.com
                            </td>


                            <td>

                                <span
                                    class="status status-notverified">

                                    Not verified

                                </span>

                            </td>


                            <td>

                                <div class="action">

                                    <button
                                        type="button"
                                        class="btn-document btn-upload upload-surat-btn"
                                        data-nama="Tsamara"
                                        data-email="tsamara@gmail.com">

                                        <i class="fa-solid fa-upload"></i>

                                        Upload Surat

                                    </button>

                                </div>

                            </td>

                        </tr>



                        <!-- =================================================
                             DATA 3
                        ================================================== -->

                        <tr>

                            <td>

                                <strong>
                                    Namira
                                </strong>

                                <br>

                                <small>
                                    202020
                                </small>

                            </td>


                            <td>
                                PNC
                            </td>


                            <td>
                                Administrasi
                            </td>


                            <td>
                                Keuangan
                            </td>


                            <td>
                                namira@gmail.com
                            </td>


                            <td>

                                <span
                                    class="status status-verified">

                                    Verified

                                </span>

                            </td>


                            <td>

                                <div class="action">

                                    <!-- LIHAT DOKUMEN -->
                                    <button
                                        type="button"
                                        class="btn-document btn-view btn-lihat-surat"
                                        data-file="">

                                        <i class="fa-regular fa-eye"></i>
                                        Lihat

                                    </button>


                                    <!-- GANTI DOKUMEN -->
                                    <button
                                        type="button"
                                        class="btn-document btn-change upload-surat-btn"
                                        data-nama="Namira"
                                        data-email="namiradavinarm@gmail.com">

                                        <i class="fa-solid fa-upload"></i>
                                        Ganti

                                    </button>

                                </div>

                            </td>

                        </tr>



                        <!-- =================================================
                             DATA 4
                        ================================================== -->

                        <tr>

                            <td>

                                <strong>
                                    Tsamara
                                </strong>

                                <br>

                                <small>
                                    202020
                                </small>

                            </td>


                            <td>
                                PNC
                            </td>


                            <td>
                                Informatika
                            </td>


                            <td>
                                Umum
                            </td>


                            <td>
                                tsamara@gmail.com
                            </td>


                            <td>

                                <span
                                    class="status status-notverified">

                                    Not verified

                                </span>

                            </td>


                            <td>

                                <div class="action">

                                    <button
                                        type="button"
                                        class="btn-document btn-upload upload-surat-btn"
                                        data-nama="Namira"
                                        data-email="namiradavinarm@gmail.com">

                                        <i class="fa-solid fa-upload"></i>

                                        Upload Surat

                                    </button>

                                </div>

                            </td>

                        </tr>


                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- =====================================================
     MODAL UPLOAD SURAT BALASAN
===================================================== -->

    <div
        class="modal-overlay"
        id="uploadModal">

        <div
            class="upload-modal"
            id="uploadModalContent">


            <!-- ================= TITLE ================= -->

            <div class="modal-title">
                Tambah surat balasan perusahaan
            </div>


            <!-- ================= DESCRIPTION ================= -->

            <div class="modal-description">

                Surat diunggah untuk
                <span id="modalNama">
                    mahasiswa
                </span>.
                Setelah tersimpan, statusnya otomatis menjadi
                Verified dan surat dikirim ke email mahasiswa.

            </div>


            <!-- ================= FILE ================= -->

            <label
                class="modal-label"
                for="suratBalasan">

                Berkas surat balasan (PDF)

            </label>


            <input
                type="file"
                id="suratBalasan"
                class="modal-file"
                accept=".pdf,application/pdf">


            <!-- ================= EMAIL ================= -->

            <div class="modal-email">

                Email tujuan
                <span id="modalEmail">
                    -
                </span>

            </div>


            <!-- ================= BUTTON ================= -->

            <div class="modal-actions">


                <!-- BATAL -->

                <button
                    type="button"
                    class="btn-modal-cancel"
                    id="cancelUpload">

                    Batal

                </button>


                <!-- SIMPAN & KIRIM -->

                <button
                    type="button"
                    class="btn-modal-submit"
                    id="submitUpload">

                    <i class="fa-regular fa-paper-plane"></i>

                    Simpan & kirim

                </button>

            </div>


        </div>

    </div>


    <!-- =====================================================
         JAVASCRIPT SIDEBAR
    ====================================================== -->

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {


                const menuBtn =
                    document.getElementById('menuBtn');


                const sidebar =
                    document.getElementById('sidebar');


                const overlay =
                    document.getElementById('sidebarOverlay');



                /* ============================================
                   BUKA / TUTUP SIDEBAR
                ============================================ */

                menuBtn.addEventListener(
                    'click',
                    function() {

                        sidebar.classList.toggle(
                            'active'
                        );

                        overlay.classList.toggle(
                            'active'
                        );

                    }
                );



                /* ============================================
                   KLIK AREA LUAR SIDEBAR
                ============================================ */

                overlay.addEventListener(
                    'click',
                    function() {

                        sidebar.classList.remove(
                            'active'
                        );

                        overlay.classList.remove(
                            'active'
                        );

                    }
                );


            }
        );

        /* =====================================================
   MODAL UPLOAD SURAT BALASAN
===================================================== */

        const uploadModal =
            document.getElementById('uploadModal');

        const cancelUpload =
            document.getElementById('cancelUpload');

        const uploadButtons =
            document.querySelectorAll('.upload-surat-btn');

        const modalNama =
            document.getElementById('modalNama');

        const modalEmail =
            document.getElementById('modalEmail');

        const suratBalasan =
            document.getElementById('suratBalasan');


        /* =====================================================
           BUKA MODAL
        ===================================================== */

        uploadButtons.forEach(function(button) {

            button.addEventListener('click', function() {

                const nama =
                    this.getAttribute('data-nama');

                const email =
                    this.getAttribute('data-email');


                // Masukkan data mahasiswa ke popup

                modalNama.textContent =
                    nama;

                modalEmail.textContent =
                    email;


                // Reset file

                suratBalasan.value = '';


                // Tampilkan popup

                uploadModal.classList.add('active');

            });

        });


        /* =====================================================
           TUTUP MODAL - BATAL
        ===================================================== */

        cancelUpload.addEventListener('click', function() {

            uploadModal.classList.remove('active');

            suratBalasan.value = '';

        });


        /* =====================================================
           TUTUP KETIKA KLIK BACKGROUND
        ===================================================== */

        uploadModal.addEventListener('click', function(event) {

            if (event.target === uploadModal) {

                uploadModal.classList.remove('active');

                suratBalasan.value = '';

            }

        });


        /* =====================================================
           TUTUP DENGAN ESC
        ===================================================== */

        document.addEventListener('keydown', function(event) {

            if (
                event.key === 'Escape' &&
                uploadModal.classList.contains('active')
            ) {

                uploadModal.classList.remove('active');

                suratBalasan.value = '';

            }

        });


        /* =====================================================
           SIMPAN & KIRIM
        ===================================================== */

        document
            .getElementById('submitUpload')
            .addEventListener('click', function() {

                const file =
                    suratBalasan.files[0];


                if (!file) {

                    alert(
                        'Silakan pilih berkas surat balasan terlebih dahulu.'
                    );

                    return;

                }


                /* Hanya PDF */

                if (
                    file.type !== 'application/pdf' &&
                    !file.name.toLowerCase().endsWith('.pdf')
                ) {

                    alert(
                        'Berkas surat balasan harus berupa PDF.'
                    );

                    suratBalasan.value = '';

                    return;

                }


                /*
                 * Untuk sementara popup ditutup.
                 *
                 * Proses upload ke database/storage
                 * bisa disambungkan ke route/controller
                 * yang kamu punya nanti.
                 */

                uploadModal.classList.remove('active');

            });

        /* =====================================================
   TOMBOL LIHAT SURAT
===================================================== */

        const lihatButtons =
            document.querySelectorAll('.btn-lihat-surat');

        lihatButtons.forEach(function(button) {

            button.addEventListener('click', function() {

                const file =
                    this.getAttribute('data-file');


                // Kalau file belum tersedia
                if (!file) {

                    alert(
                        'Dokumen surat belum tersedia.'
                    );

                    return;

                }


                // Buka dokumen di tab baru
                window.open(
                    file,
                    '_blank'
                );

            });

        });

        uploadButtons.forEach(function(button) {

            button.addEventListener('click', function() {

                const nama =
                    this.getAttribute('data-nama');

                const email =
                    this.getAttribute('data-email');

                modalNama.textContent = nama;

                modalEmail.textContent = email;

                suratBalasan.value = '';

                uploadModal.classList.add('active');

            });

        });
    </script>


</body>

</html>