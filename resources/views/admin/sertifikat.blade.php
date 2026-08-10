<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sertifikat - Monitoring Mahasiswa PKL</title>


    <!-- =====================================================
         FONT
    ====================================================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">


    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->

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
        }


        html,
        body {
            width: 100%;
            min-height: 100%;
        }


        /* =====================================================
           BODY / BACKGROUND
        ====================================================== */

        body {

            font-family: 'Poppins', sans-serif;

            min-height: 100vh;

            color: #202020;

            background: linear-gradient(rgba(4, 66, 67, .72),
                rgba(4, 66, 67, .72)),
            url("{{ asset('assets/image/bg-pln.png') }}") center center / cover fixed no-repeat;

            overflow-x: hidden;
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
           HEADER LEFT
        ====================================================== */

        .header-left {

            display: flex;

            align-items: center;

            gap: 18px;

            height: 72px;
        }


        /* =====================================================
           LOGO
        ====================================================== */

        .logo-box {

            width: 230px;

            height: 72px;

            flex-shrink: 0;

            background: #fff;

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
           HEADER TITLE
        ====================================================== */

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
           MENU BUTTON
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

        .main-content {

            width: 100%;

            min-height: 100vh;

            padding:
                92px 30px 45px;

            display: flex;

            justify-content: center;

            align-items: flex-start;
        }


        /* =====================================================
           MAIN CARD
        ====================================================== */

        .data-card {

            width: 100%;

            max-width: 1100px;

            background: #ffffff;

            border-radius: 14px;

            padding:
                28px 30px 34px;

            box-shadow:
                0 10px 35px rgba(0, 0, 0, .18);
        }


        /* =====================================================
           CARD HEADER
        ====================================================== */

        .card-header {

            display: flex;

            justify-content: space-between;

            align-items: flex-start;

            margin-bottom: 20px;
        }


        .card-title h1,
        .card-header h1 {

            color: #0A5F63;

            font-size: 22px;

            font-weight: 600;

            line-height: 1.2;
        }


        .card-title p,
        .card-header p {

            color: #555;

            font-size: 12px;

            margin-top: 5px;
        }


        /* =====================================================
           RESET FILTER
        ====================================================== */

        .reset-filter {

            border: none;

            background: transparent;

            color: #222;

            font-family: 'Poppins', sans-serif;

            font-size: 12px;

            cursor: pointer;

            padding: 4px 0;

            margin-top: 8px;
        }


        .reset-filter:hover {

            color: #0A5F63;

            text-decoration: underline;
        }


        /* =====================================================
           FILTER FORM
        ====================================================== */

        .filter-form {

            width: 100%;

            margin-top: 5px;
        }


        /* =====================================================
           FILTER ROW 1
        ====================================================== */

        .filter-grid {

            display: grid;

            grid-template-columns:
                1.8fr 1fr 1fr 1fr 1fr 1fr;

            gap: 12px;

            width: 100%;
        }


        /* =====================================================
           FILTER ITEM
        ====================================================== */

        .filter-item {

            display: flex;

            flex-direction: column;

            gap: 6px;

            min-width: 0;
        }


        .filter-item label {

            color: #555;

            font-size: 11px;

            font-weight: 400;

            line-height: 1.2;
        }


        .filter-item input,
        .filter-item select {

            width: 100%;

            height: 38px;

            padding:
                0 11px;

            border:
                1px solid #dedede;

            border-radius: 6px;

            outline: none;

            background: #fff;

            color: #666;

            font-family:
                'Poppins',
                sans-serif;

            font-size: 10px;

            box-shadow:
                0 2px 7px rgba(0, 0, 0, .08);
        }


        .filter-item input::placeholder {

            color: #aaa;

            font-style: italic;
        }


        .filter-item input:focus,
        .filter-item select:focus {

            border-color: #4d9da0;

            box-shadow:
                0 0 0 2px rgba(10, 95, 99, .08);
        }


        /* =====================================================
           FILTER ROW 2
        ====================================================== */

        .filter-row-two {

            display: flex;

            gap: 12px;

            margin-top: 13px;

            align-items: flex-end;
        }


        .filter-row-two .filter-item {

            width: 120px;
        }


        /* =====================================================
           TABLE
        ====================================================== */

        .table-wrapper {

            width: 100%;

            margin-top: 32px;

            overflow-x: auto;
        }


        .student-table {

            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;
        }


        /* =====================================================
           TABLE HEADER
        ====================================================== */

        .student-table thead th {

            height: 38px;

            padding:
                0 8px 10px;

            border-bottom:
                1px solid #cfcfcf;

            text-align: left;

            color: #444;

            font-size: 11px;

            font-weight: 400;

            white-space: nowrap;
        }


        /* =====================================================
           TABLE BODY
        ====================================================== */

        .student-table tbody td {

            height: 52px;

            padding:
                7px 8px;

            border-bottom:
                1px solid #dedede;

            vertical-align: middle;

            color: #222;

            font-size: 11px;
        }


        /* =====================================================
           COLUMN WIDTH
        ====================================================== */

        .student-table th:nth-child(1),
        .student-table td:nth-child(1) {

            width: 14%;
        }


        .student-table th:nth-child(2),
        .student-table td:nth-child(2) {

            width: 10%;
        }


        .student-table th:nth-child(3),
        .student-table td:nth-child(3) {

            width: 14%;
        }


        .student-table th:nth-child(4),
        .student-table td:nth-child(4) {

            width: 12%;
        }


        .student-table th:nth-child(5),
        .student-table td:nth-child(5) {

            width: 9%;
        }


        .student-table th:nth-child(6),
        .student-table td:nth-child(6) {

            width: 17%;
        }


        .student-table th:nth-child(7),
        .student-table td:nth-child(7) {

            width: 24%;

            text-align: right;
        }


        /* =====================================================
           STUDENT NAME
        ====================================================== */

        .student-name {

            display: flex;

            flex-direction: column;

            line-height: 1.2;
        }


        .student-name .name {

            font-size: 11px;

            font-weight: 400;
        }


        .student-name .year {

            color: #999;

            font-size: 9px;

            font-style: italic;

            margin-top: 3px;
        }


        /* =====================================================
           STATUS
        ====================================================== */

        .status {

            font-size: 11px;

            white-space: nowrap;
        }


        /* =====================================================
           BUTTON
        ====================================================== */

        .btn {

            height: 30px;

            padding:
                0 12px;

            border: none;

            border-radius: 7px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            font-family:
                'Poppins',
                sans-serif;

            font-size: 9px;

            color: white;

            cursor: pointer;

            white-space: nowrap;

            transition: .18s;
        }


        .btn i {

            font-size: 10px;
        }


        .btn:hover {

            transform:
                translateY(-1px);

            opacity: .9;
        }


        /* =====================================================
           UPLOAD
        ====================================================== */

        .btn-upload {

            background: #999999;
        }


        .btn-upload.available {

            background: #4e969b;
        }


        /* =====================================================
           VIEW
        ====================================================== */

        .btn-view {

            background: #f5f5f5;

            color: #333;

            border:
                1px solid #d5d5d5;
        }


        /* =====================================================
           EDIT
        ====================================================== */

        .btn-edit {

            background: #4e969b;
        }


        /* =====================================================
           ID CARD
        ====================================================== */

        .id-card-circle {
            width: 18px;
            height: 18px;

            padding: 0;
            margin: 0;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: 1.5px solid #222;
            border-radius: 50%;

            background: white;

            cursor: pointer;

            transition: all .2s ease;
        }


        /* ketika aktif */

        .id-card-circle.active {
            background: #24c95a;
            border-color: #24c95a;
        }


        /* tanda centang */

        .id-card-circle.active::after {
            content: "✓";

            color: white;

            font-size: 11px;
            font-weight: 700;

            line-height: 1;
        }

        /* =====================================================
           CERTIFICATE
        ====================================================== */

        .certificate {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-width: 110px;

            height: 30px;

            padding:
                0 12px;

            border-radius: 15px;

            font-size: 9px;

            white-space: nowrap;
        }


        .certificate.pending {

            background: #b1b1b1;

            color: white;
        }


        .certificate.issued {

            background: #24c95a;

            color: white;
        }


        /* =====================================================
           ACTIONS
        ====================================================== */

        .actions {

            display: flex;

            justify-content: flex-end;

            align-items: center;

            gap: 8px;
        }


        /* =====================================================
           RESPONSIVE - 1000PX
        ====================================================== */

        @media (max-width: 1100px) {

            .data-card {

                max-width: 100%;
            }

            .filter-grid {

                grid-template-columns:
                    repeat(3, 1fr);
            }

        }


        /* =====================================================
           RESPONSIVE - 700PX
        ====================================================== */

        @media (max-width: 700px) {

            .main-content {

                padding:
                    88px 15px 30px;
            }


            .data-card {

                padding:
                    22px 18px 28px;
            }


            .header-title {

                display: none;
            }


            .logo-box {

                width: 170px;
            }


            .logo-box img {

                width: 125px;
            }


            .filter-grid {

                grid-template-columns:
                    repeat(2, 1fr);
            }


            .student-table {

                min-width: 850px;
            }

        }


        /* =====================================================
           RESPONSIVE - 450PX
        ====================================================== */

        @media (max-width: 450px) {

            .filter-grid {

                grid-template-columns: 1fr;
            }


            .filter-row-two {

                flex-wrap: wrap;
            }


            .filter-row-two .filter-item {

                width:
                    calc(50% - 6px);
            }


            .card-header {

                flex-direction: column;

                gap: 5px;
            }


            .reset-filter {

                align-self: flex-end;
            }

        }

        /* =====================================================
   MODAL UPLOAD SERTIFIKAT
====================================================== */

        .certificate-modal-overlay {
            position: fixed;
            inset: 0;

            z-index: 10000;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(0, 0, 0, .20);

            backdrop-filter: blur(1px);

            opacity: 0;
            visibility: hidden;
            pointer-events: none;

            transition:
                opacity .2s ease,
                visibility .2s ease;
        }


        /* =====================================================
   MODAL ACTIVE
====================================================== */

        .certificate-modal-overlay.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }


        /* =====================================================
   MODAL BOX
====================================================== */

        .certificate-modal {
            width: 520px;
            min-height: 260px;

            background: #ffffff;

            border-radius: 16px;

            padding: 28px 30px 24px;

            box-shadow:
                0 15px 45px rgba(0, 0, 0, .22);

            transform:
                translateY(8px) scale(.98);

            transition:
                transform .2s ease;
        }


        .certificate-modal-overlay.active .certificate-modal {
            transform:
                translateY(0) scale(1);
        }


        /* =====================================================
   JUDUL
====================================================== */

        .certificate-modal h3 {
            margin: 0;

            color: #202020;

            font-family:
                'Poppins',
                sans-serif;

            font-size: 17px;

            font-weight: 500;

            line-height: 1.4;
        }


        /* =====================================================
   DESKRIPSI
====================================================== */

        .modal-description {
            margin-top: 6px;
            margin-bottom: 22px;

            color: #555;

            font-family:
                'Poppins',
                sans-serif;

            font-size: 13px;

            line-height: 1.5;
        }


        /* =====================================================
   FILE GROUP
====================================================== */

        .modal-file-group {
            width: 100%;

            display: flex;
            flex-direction: column;

            gap: 8px;
        }


        .modal-file-group label {
            color: #222;

            font-family:
                'Poppins',
                sans-serif;

            font-size: 13px;

            font-weight: 400;
        }


        /* =====================================================
   FILE INPUT
====================================================== */

        .modal-file-group input[type="file"] {
            width: 100%;

            height: 42px;

            padding: 0;

            border:
                1px solid #888;

            border-radius: 6px;

            background: #fff;

            color: #222;

            font-family:
                'Poppins',
                sans-serif;

            font-size: 12px;

            cursor: pointer;
        }


        /* Tombol Choose File */

        .modal-file-group input[type="file"]::file-selector-button {

            height: 40px;

            padding: 0 13px;

            margin-right: 10px;

            border: none;

            border-right:
                1px solid #888;

            background: #f5f5f5;

            color: #222;

            font-family:
                'Poppins',
                sans-serif;

            font-size: 12px;

            cursor: pointer;
        }


        /* =====================================================
   ACTIONS
====================================================== */

        .modal-actions {

            display: flex;

            justify-content: flex-end;

            align-items: center;

            gap: 10px;

            margin-top: 20px;
        }


        /* =====================================================
   BUTTON
====================================================== */

        .modal-btn {

            height: 38px;

            padding:
                0 18px;

            border-radius: 7px;

            font-family:
                'Poppins',
                sans-serif;

            font-size: 12px;

            cursor: pointer;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            transition: .2s;
        }


        /* =====================================================
   BATAL
====================================================== */

        .modal-cancel {
            background: #fff;

            color: #4e969b;

            border:
                1px solid #8fc1c4;
        }


        .modal-cancel:hover {
            background: #f1f8f8;
        }


        /* =====================================================
   SIMPAN & KIRIM
====================================================== */

        .modal-save {
            background: #4e969b;

            color: white;

            border:
                1px solid #4e969b;
        }


        .modal-save:hover {
            background: #3d8589;
        }


        /* =====================================================
   MOBILE
====================================================== */

        @media (max-width: 600px) {

            .certificate-modal {

                width:
                    calc(100% - 30px);

                min-height: auto;

                padding:
                    24px 22px 22px;
            }

        }

        /* =========================================================
   MODAL UPLOAD LAPORAN
========================================================= */

        .laporan-modal-overlay {
            position: fixed;
            inset: 0;

            background: rgba(0, 0, 0, 0.35);

            display: flex;
            align-items: center;
            justify-content: center;

            z-index: 10000;

            opacity: 0;
            visibility: hidden;

            transition:
                opacity .2s ease,
                visibility .2s ease;
        }

        .laporan-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }


        /* ================= MODAL ================= */

        .laporan-modal {
            width: 520px;
            max-width: calc(100% - 40px);

            background: #ffffff;

            border-radius: 16px;

            padding: 25px 27px 21px;

            box-shadow:
                0 15px 45px rgba(0, 0, 0, .20);

            transform:
                scale(.95) translateY(10px);

            transition:
                transform .2s ease;
        }

        .laporan-modal-overlay.active .laporan-modal {
            transform:
                scale(1) translateY(0);
        }


        /* ================= TITLE ================= */

        .laporan-modal-title {
            color: #1F1F1F;

            font-size: 17px;

            font-weight: 600;

            line-height: 1.3;

            margin-bottom: 5px;
        }


        /* ================= DESCRIPTION ================= */

        .laporan-modal-description {
            color: #666;

            font-size: 11px;

            line-height: 1.55;

            margin-bottom: 18px;
        }


        /* ================= LABEL ================= */

        .laporan-modal-label {
            display: block;

            color: #333;

            font-size: 11px;

            font-weight: 500;

            margin-bottom: 7px;
        }


        /* ================= FILE ================= */

        .laporan-file {
            width: 100%;

            height: 42px;

            border: 1px solid #999;

            border-radius: 8px;

            padding: 0 10px;

            background: #fff;

            color: #222;

            font-family: 'Poppins', sans-serif;

            font-size: 11px;

            cursor: pointer;

            outline: none;
        }

        .laporan-file:hover {
            border-color: #4C9FA3;
        }

        .laporan-file:focus {
            border-color: #4C9FA3;

            box-shadow:
                0 0 0 3px rgba(76, 159, 163, .12);
        }


        /* ================= EMAIL ================= */

        .laporan-email {
            margin-top: 8px;

            color: #666;

            font-size: 11px;

            line-height: 1.5;
        }


        /* ================= ACTION ================= */

        .laporan-modal-actions {
            display: flex;

            justify-content: flex-end;

            align-items: center;

            gap: 8px;

            margin-top: 17px;
        }


        /* ================= BATAL ================= */

        .btn-laporan-cancel {
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

        .btn-laporan-cancel:hover {
            background: #F1F9F9;
        }


        /* ================= SIMPAN ================= */

        .btn-laporan-submit {
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

        .btn-laporan-submit:hover {
            background: #0A6B70;

            border-color: #0A6B70;
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 600px) {

            .laporan-modal {
                width: calc(100% - 30px);

                padding: 21px;
            }

            .laporan-modal-title {
                font-size: 16px;
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
                style="margin:0;">

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



    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main class="main-content">


        <section class="data-card">


            <!-- =================================================
                 CARD HEADER
            ================================================== -->

            <div class="card-header">


                <div>

                    <h1>
                        Filter data
                    </h1>

                    <p>

                        Menampilkan
                        {{ isset($mahasiswa) ? $mahasiswa->count() : 4 }}
                        dari
                        {{ isset($mahasiswa) ? $mahasiswa->count() : 4 }}
                        mahasiswa

                    </p>

                </div>


                <!-- <button
                    type="button"
                    class="reset-filter"
                    onclick="window.location='{{ route('admin.sertifikat') }}'">

                    Reset Filter

                </button> -->


            </div>



            <!-- =================================================
                 FILTER FORM
            ================================================== -->

            <form
                class="filter-form"
                method="GET"
                action="{{ route('admin.sertifikat') }}">


                <!-- ROW 1 -->

                <div class="filter-grid">


                    <!-- CARI -->

                    <div class="filter-item">

                        <label for="search">
                            Cari
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            placeholder="Nama, NIM, Kampus..."
                            value="{{ request('search') }}">

                    </div>


                    <!-- TANGGAL MULAI -->

                    <div class="filter-item">

                        <label for="tanggal_mulai">
                            Tanggal mulai
                        </label>

                        <input
                            type="date"
                            id="tanggal_mulai"
                            name="tanggal_mulai"
                            value="{{ request('tanggal_mulai') }}">

                    </div>


                    <!-- TANGGAL SELESAI -->

                    <div class="filter-item">

                        <label for="tanggal_selesai">
                            Tanggal selesai
                        </label>

                        <input
                            type="date"
                            id="tanggal_selesai"
                            name="tanggal_selesai"
                            value="{{ request('tanggal_selesai') }}">

                    </div>


                    <!-- JURUSAN -->

                    <div class="filter-item">

                        <label for="jurusan">
                            Jurusan
                        </label>

                        <select
                            id="jurusan"
                            name="jurusan">

                            <option value="">
                                Semua
                            </option>

                            @foreach ($jurusan ?? [] as $item)

                            <option
                                value="{{ $item->id_jurusan ?? $item->id }}"
                                {{ request('jurusan') == ($item->id_jurusan ?? $item->id) ? 'selected' : '' }}>

                                {{ $item->nama_jurusan ?? $item->nama }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- UNIVERSITAS -->

                    <div class="filter-item">

                        <label for="universitas">
                            Universitas
                        </label>

                        <select
                            id="universitas"
                            name="universitas">

                            <option value="">
                                Semua
                            </option>

                            @foreach ($universitas ?? [] as $item)

                            <option
                                value="{{ $item->id_universitas ?? $item->id }}"
                                {{ request('universitas') == ($item->id_universitas ?? $item->id) ? 'selected' : '' }}>

                                {{ $item->nama_universitas ?? $item->nama }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- DIVISI -->

                    <div class="filter-item">

                        <label for="divisi">
                            Divisi
                        </label>

                        <select
                            id="divisi"
                            name="divisi">

                            <option value="">
                                Semua
                            </option>

                            @foreach ($divisi ?? [] as $item)

                            <option
                                value="{{ $item->id_divisi ?? $item->id }}"
                                {{ request('divisi') == ($item->id_divisi ?? $item->id) ? 'selected' : '' }}>

                                {{ $item->nama_divisi ?? $item->nama }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                </div>



                <!-- ROW 2 -->

                <div class="filter-row-two">


                    <!-- STATUS -->

                    <div class="filter-item">

                        <label for="status">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status">

                            <option value="">
                                Semua
                            </option>

                            <option
                                value="Akan Datang"
                                {{ request('status') == 'Akan Datang' ? 'selected' : '' }}>

                                Akan Datang

                            </option>

                            <option
                                value="Aktif"
                                {{ request('status') == 'Aktif' ? 'selected' : '' }}>

                                Aktif

                            </option>

                            <option
                                value="Selesai"
                                {{ request('status') == 'Selesai' ? 'selected' : '' }}>

                                Selesai

                            </option>

                        </select>

                    </div>


                    <!-- JENIS KELAMIN -->

                    <div class="filter-item">

                        <label for="jenis_kelamin">
                            Jenis Kelamin
                        </label>

                        <select
                            id="jenis_kelamin"
                            name="jenis_kelamin">

                            <option value="">
                                Semua
                            </option>

                            <option
                                value="L"
                                {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>

                                Laki-laki

                            </option>

                            <option
                                value="P"
                                {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>

                                Perempuan

                            </option>

                        </select>

                    </div>


                </div>


            </form>



            <!-- =================================================
                 TABLE
            ================================================== -->

            <div class="table-wrapper">


                <table class="student-table">


                    <thead>

                        <tr>

                            <th>
                                Mahasiswa
                            </th>

                            <th>
                                Divisi
                            </th>

                            <th>
                                Status Magang
                            </th>

                            <th>
                                Laporan
                            </th>

                            <th>
                                ID Card
                            </th>

                            <th>
                                Sertifikat
                            </th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                        {{-- =================================================
                             DATA DATABASE
                        ================================================== --}}

                        @if(isset($mahasiswa) && $mahasiswa->count() > 0)


                        @foreach($mahasiswa as $item)

                        <tr>


                            <!-- MAHASISWA -->

                            <td>

                                <div class="student-name">

                                    <span class="name">

                                        {{ $item->nama_mahasiswa
                                                    ?? $item->nama
                                                    ?? '-' }}

                                    </span>

                                    <span class="year">

                                        {{ $item->nim ?? '-' }}

                                    </span>

                                </div>

                            </td>


                            <!-- DIVISI -->

                            <td>

                                {{ $item->divisi->nama_divisi
                                            ?? $item->divisi->nama
                                            ?? '-' }}

                            </td>


                            <!-- STATUS -->

                            <td>

                                <span class="status">

                                    {{ $item->status
                                                ?? 'Akan Datang' }}

                                </span>

                            </td>


                            <!-- LAPORAN -->

                            <td>

                                <button
                                    type="button"
                                    class="btn btn-upload btn-upload-laporan"

                                    data-nama="{{ $item->nama ?? $item->mahasiswa->nama ?? '-' }}"

                                    data-email="{{ $item->email ?? $item->mahasiswa->email ?? '-' }}">

                                    <i class="fa-solid fa-arrow-up"></i>

                                    Upload

                                </button>

                            </td>


                            <!-- ID CARD -->

                            <td>

                                <span
                                    class="id-card-circle">
                                </span>

                            </td>


                            <!-- SERTIFIKAT -->

                            <td>

                                <span
                                    class="certificate pending">

                                    Belum Memenuhi

                                </span>

                            </td>


                            <!-- AKSI -->

                            <td>

                                <div class="actions">

                                    <button
                                        type="button"
                                        class="btn btn-upload"
                                        onclick="openCertificateModal()">

                                        <i class="fa-solid fa-arrow-up"></i>

                                        Upload Sertifikat

                                    </button>

                                </div>

                            </td>


                        </tr>

                        @endforeach


                        @else


                        {{-- =================================================
                                 DATA CONTOH
                            ================================================== --}}


                        <!-- ROW 1 -->

                        <tr>

                            <td>

                                <div class="student-name">

                                    <span class="name">
                                        Namira
                                    </span>

                                    <span class="year">
                                        202020
                                    </span>

                                </div>

                            </td>

                            <td>
                                Umum
                            </td>

                            <td>
                                <span class="status">
                                    Akan Datang
                                </span>
                            </td>

                            <td>

                                <button
                                    type="button"
                                    class="btn btn-upload btn-upload-laporan"

                                    data-nama="{{ $item->nama ?? $item->mahasiswa->nama ?? '-' }}"

                                    data-email="{{ $item->email ?? $item->mahasiswa->email ?? '-' }}">

                                    <i class="fa-solid fa-arrow-up"></i>

                                    Upload

                                </button>

                            </td>

                            <td>

                                <button
                                    type="button"
                                    class="id-card-circle"
                                    onclick="toggleIdCard(this)"
                                    title="Status ID Card">
                                </button>

                            </td>

                            <td>

                                <span class="certificate pending">
                                    Belum Memenuhi
                                </span>

                            </td>

                            <td>

                                <div class="actions">

                                    <button
                                        type="button"
                                        class="btn btn-upload"
                                        onclick="openCertificateModal()">

                                        <i class="fa-solid fa-arrow-up"></i>

                                        Upload Sertifikat

                                    </button>

                                </div>

                            </td>

                        </tr>



                        <!-- ROW 2 -->

                        <tr>

                            <td>

                                <div class="student-name">

                                    <span class="name">
                                        Tsamara
                                    </span>

                                    <span class="year">
                                        202020
                                    </span>

                                </div>

                            </td>

                            <td>
                                SDM
                            </td>

                            <td>
                                <span class="status">
                                    Aktif
                                </span>
                            </td>

                            <td>

                                <button
                                    type="button"
                                    class="btn btn-upload">

                                    <i class="fa-solid fa-arrow-up"></i>

                                    Upload

                                </button>

                            </td>

                            <td>

                                <button
                                    type="button"
                                    class="id-card-circle"
                                    onclick="toggleIdCard(this)"
                                    title="Klik untuk mengubah status ID Card">
                                </button>

                            </td>

                            <td>

                                <span class="certificate pending">
                                    Belum Memenuhi
                                </span>

                            </td>

                            <td>

                                <div class="actions">

                                    <button
                                        type="button"
                                        class="btn btn-upload"
                                        onclick="openCertificateModal()">

                                        <i class="fa-solid fa-arrow-up"></i>

                                        Upload Sertifikat

                                    </button>

                                </div>

                            </td>

                        </tr>



                        <!-- ROW 3 -->

                        <tr>

                            <td>

                                <div class="student-name">

                                    <span class="name">
                                        Namira
                                    </span>

                                    <span class="year">
                                        202020
                                    </span>

                                </div>

                            </td>

                            <td>
                                SIS
                            </td>

                            <td>
                                <span class="status">
                                    Selesai
                                </span>
                            </td>

                            <td>

                                <button
                                    type="button"
                                    class="btn btn-upload btn-upload-laporan"

                                    data-nama="{{ $item->nama ?? $item->mahasiswa->nama ?? '-' }}"

                                    data-email="{{ $item->email ?? $item->mahasiswa->email ?? '-' }}">

                                    <i class="fa-solid fa-arrow-up"></i>

                                    Upload

                                </button>

                            </td>

                            <td>

                                <button
                                    type="button"
                                    class="id-card-circle"
                                    onclick="toggleIdCard(this)"
                                    title="Klik untuk mengubah status ID Card">
                                </button>

                            </td>

                            <td>

                                <span class="certificate issued">
                                    Diterbitkan
                                </span>

                            </td>

                            <td>

                                <div class="actions">

                                    <button
                                        type="button"
                                        class="btn btn-view">

                                        <i class="fa-regular fa-file-lines"></i>

                                        Lihat

                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-edit">

                                        <i class="fa-solid fa-user-pen"></i>

                                        Ubah

                                    </button>

                                </div>

                            </td>

                        </tr>



                        <!-- ROW 4 -->

                        <tr>

                            <td>

                                <div class="student-name">

                                    <span class="name">
                                        Tsamara
                                    </span>

                                    <span class="year">
                                        202020
                                    </span>

                                </div>

                            </td>

                            <td>
                                Umum
                            </td>

                            <td>
                                <span class="status">
                                    Selesai
                                </span>
                            </td>

                            <td>

                                <button
                                    type="button"
                                    class="btn btn-upload btn-upload-laporan"

                                    data-nama="{{ $item->nama ?? $item->mahasiswa->nama ?? '-' }}"

                                    data-email="{{ $item->email ?? $item->mahasiswa->email ?? '-' }}">

                                    <i class="fa-solid fa-arrow-up"></i>

                                    Upload

                                </button>
                            </td>

                            <td>

                                <button
                                    type="button"
                                    class="id-card-circle"
                                    onclick="toggleIdCard(this)"
                                    title="Klik untuk mengubah status ID Card">
                                </button>

                            </td>

                            <td>

                                <span class="certificate pending">
                                    Belum Memenuhi
                                </span>

                            </td>

                            <td>

                                <div class="actions">

                                    <button
                                        type="button"
                                        class="btn btn-upload"
                                        onclick="openCertificateModal()">

                                        <i class="fa-solid fa-arrow-up"></i>

                                        Upload Sertifikat

                                    </button>

                                </div>

                            </td>

                        </tr>


                        @endif


                    </tbody>


                </table>


            </div>


        </section>


    </main>

    <!-- =========================================================
     MODAL UPLOAD LAPORAN
========================================================= -->

    <div
        class="laporan-modal-overlay"
        id="laporanModal">


        <div
            class="laporan-modal">


            <!-- ================= TITLE ================= -->

            <div class="laporan-modal-title">

                Tambah laporan mahasiswa

            </div>


            <!-- ================= DESCRIPTION ================= -->

            <div class="laporan-modal-description">

                Laporan diunggah untuk
                <strong id="laporanNama">
                    mahasiswa
                </strong>.
                Pastikan laporan yang diunggah merupakan
                laporan PKL yang sesuai.

            </div>


            <!-- ================= FILE ================= -->

            <label
                for="laporanFile"
                class="laporan-modal-label">

                Berkas laporan (PDF)

            </label>


            <input
                type="file"
                id="laporanFile"
                class="laporan-file"

                accept=".pdf,application/pdf">


            <!-- ================= EMAIL ================= -->

            <div class="laporan-email">

                Email mahasiswa:
                <span id="laporanEmail">
                    -
                </span>

            </div>


            <!-- ================= BUTTON ================= -->

            <div class="laporan-modal-actions">


                <!-- BATAL -->

                <button
                    type="button"
                    class="btn-laporan-cancel"
                    id="cancelLaporan">

                    Batal

                </button>


                <!-- SIMPAN -->

                <button
                    type="button"
                    class="btn-laporan-submit"
                    id="submitLaporan">

                    <i class="fa-regular fa-paper-plane"></i>

                    Simpan & kirim

                </button>

            </div>


        </div>

    </div>

    <!-- =====================================================
     MODAL UPLOAD SERTIFIKAT
====================================================== -->

    <div class="certificate-modal-overlay" id="certificateModal">

        <div class="certificate-modal">


            <!-- JUDUL -->

            <h3>
                Unggah sertifikat digital
            </h3>


            <!-- DESKRIPSI -->

            <p class="modal-description">
                Pastikan seluruh persyaratan sudah terpenuhi.
            </p>


            <!-- FILE -->

            <div class="modal-file-group">

                <label for="certificateFile">
                    Berkas sertifikat (PDF/Gambar)
                </label>

                <input
                    type="file"
                    id="certificateFile"
                    name="certificate"
                    accept=".pdf,.jpg,.jpeg,.png">

            </div>


            <!-- BUTTON -->

            <div class="modal-actions">

                <button
                    type="button"
                    class="modal-btn modal-cancel"
                    onclick="closeCertificateModal()">

                    Batal

                </button>


                <button
                    type="button"
                    class="modal-btn modal-save"
                    onclick="saveCertificate()">

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



                /* ================================
                   BUKA / TUTUP SIDEBAR
                ================================= */

                menuBtn.addEventListener(
                    'click',
                    function() {

                        sidebar.classList.toggle('active');

                        overlay.classList.toggle('active');

                    }
                );



                /* ================================
                   KLIK AREA LUAR
                ================================= */

                overlay.addEventListener(
                    'click',
                    function() {

                        sidebar.classList.remove('active');

                        overlay.classList.remove('active');

                    }
                );



                /* ================================
                   ESC
                ================================= */

                document.addEventListener(
                    'keydown',
                    function(event) {

                        if (event.key === 'Escape') {

                            sidebar.classList.remove('active');

                            overlay.classList.remove('active');

                        }

                    }
                );


            }
        );



        function toggleIdCard(button) {

            button.classList.toggle('active');

        }

        /* =====================================================
   MODAL SERTIFIKAT
====================================================== */

        const certificateModal =
            document.getElementById('certificateModal');


        function openCertificateModal() {

            certificateModal.classList.add('active');

        }


        function closeCertificateModal() {

            certificateModal.classList.remove('active');

        }


        /* =====================================================
           KLIK AREA LUAR MODAL
        ====================================================== */

        certificateModal.addEventListener(
            'click',
            function(event) {

                if (event.target === certificateModal) {

                    closeCertificateModal();

                }

            }
        );


        /* =====================================================
           ESC UNTUK MENUTUP
        ====================================================== */

        document.addEventListener(
            'keydown',
            function(event) {

                if (event.key === 'Escape') {

                    closeCertificateModal();

                }

            }
        );


        /* =====================================================
           SIMPAN & KIRIM
        ====================================================== */

        function saveCertificate() {

            const file =
                document.getElementById('certificateFile');


            if (!file.files.length) {

                alert('Silakan pilih berkas sertifikat terlebih dahulu.');

                return;

            }


            /*
             * Untuk sementara popup ditutup.
             *
             * Nanti bagian ini bisa disambungkan
             * ke route/controller upload sertifikat.
             */

            closeCertificateModal();

        }

        /* =========================================================
   MODAL UPLOAD LAPORAN
========================================================= */

        const laporanModal =
            document.getElementById('laporanModal');

        const laporanButtons =
            document.querySelectorAll('.btn-upload-laporan');

        const cancelLaporan =
            document.getElementById('cancelLaporan');

        const submitLaporan =
            document.getElementById('submitLaporan');

        const laporanNama =
            document.getElementById('laporanNama');

        const laporanEmail =
            document.getElementById('laporanEmail');

        const laporanFile =
            document.getElementById('laporanFile');


        /* =========================================================
           BUKA POPUP
        ========================================================= */

        laporanButtons.forEach(function(button) {

            button.addEventListener('click', function() {

                const nama =
                    this.dataset.nama;

                const email =
                    this.dataset.email;


                /*
                 * Masukkan data mahasiswa
                 * ke dalam popup
                 */

                laporanNama.textContent =
                    nama;

                laporanEmail.textContent =
                    email;


                /*
                 * Reset file sebelumnya
                 */

                laporanFile.value = '';


                /*
                 * Buka modal
                 */

                laporanModal.classList.add('active');

            });

        });


        /* =========================================================
           TUTUP - BATAL
        ========================================================= */

        cancelLaporan.addEventListener('click', function() {

            laporanModal.classList.remove('active');

            laporanFile.value = '';

        });


        /* =========================================================
           KLIK AREA LUAR POPUP
        ========================================================= */

        laporanModal.addEventListener('click', function(event) {

            if (event.target === laporanModal) {

                laporanModal.classList.remove('active');

                laporanFile.value = '';

            }

        });


        /* =========================================================
           ESC
        ========================================================= */

        document.addEventListener('keydown', function(event) {

            if (
                event.key === 'Escape' &&
                laporanModal.classList.contains('active')
            ) {

                laporanModal.classList.remove('active');

                laporanFile.value = '';

            }

        });


        /* =========================================================
           SIMPAN & KIRIM
        ========================================================= */

        submitLaporan.addEventListener('click', function() {

            const file =
                laporanFile.files[0];


            /*
             * Belum memilih file
             */

            if (!file) {

                alert(
                    'Silakan pilih berkas laporan terlebih dahulu.'
                );

                return;

            }


            /*
             * Validasi PDF
             */

            const isPDF =
                file.type === 'application/pdf' ||
                file.name.toLowerCase().endsWith('.pdf');


            if (!isPDF) {

                alert(
                    'Berkas laporan harus berupa PDF.'
                );

                laporanFile.value = '';

                return;

            }


            /*
             * Untuk sementara popup ditutup.
             *
             * Nanti bagian ini bisa disambungkan
             * ke controller Laravel untuk upload
             * laporan ke storage/database.
             */

            laporanModal.classList.remove('active');

            laporanFile.value = '';

        });
    </script>


</body>

</html>