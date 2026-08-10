<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Sertifikat - Monitoring Mahasiswa PKL
    </title>


    <!-- =====================================================
         FONT
    ====================================================== -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
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


        body {

            font-family: 'Poppins', sans-serif;

            min-height: 100vh;

            color: #202020;

            background:
                linear-gradient(
                    rgba(4, 66, 67, .72),
                    rgba(4, 66, 67, .72)
                ),
                url("{{ asset('assets/image/bg-pln.png') }}")
                center center / cover fixed no-repeat;

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


        .header-left {

            display: flex;

            align-items: center;

            gap: 18px;

            height: 72px;
        }


        .logo-box {

            width: 230px;

            height: 72px;

            flex-shrink: 0;

            background: #fff;

            clip-path:
                polygon(
                    0 0,
                    100% 0,
                    82% 100%,
                    0 100%
                );

            display: flex;

            justify-content: center;

            align-items: center;
        }


        .logo-box img {

            width: 150px;

            display: block;
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

            display: flex;

            align-items: center;

            justify-content: center;

            color: white;

            cursor: pointer;

            border-radius: 8px;

            background: transparent;

            border: none;

            transition: .2s;
        }


        .menu-btn {

            font-size: 21px;
        }


        .logout {

            font-size: 17px;
        }


        .menu-btn:hover,
        .logout:hover {

            background:
                rgba(255, 255, 255, .12);
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

            background:
                rgba(255, 255, 255, .15);

            transform:
                translateX(3px);
        }


        .sidebar-menu.active {

            font-weight: 500;
        }


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
           MAIN
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


        .data-card {

            width: 100%;

            max-width: 1100px;

            background: #fff;

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


        .card-header h1 {

            color: #0A5F63;

            font-size: 22px;

            font-weight: 600;

            line-height: 1.2;
        }


        .card-header p {

            color: #555;

            font-size: 12px;

            margin-top: 5px;
        }


        /* =====================================================
           FILTER
        ====================================================== */

        .filter-form {

            width: 100%;

            margin-top: 5px;
        }


        .filter-grid {

            display: grid;

            grid-template-columns:
                1.8fr 1fr 1fr 1fr 1fr 1fr;

            gap: 12px;

            width: 100%;
        }


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


        .filter-row-two {

            display: flex;

            gap: 12px;

            margin-top: 13px;

            align-items: flex-end;
        }


        .filter-row-two .filter-item {

            width: 120px;
        }


        .filter-actions {

            display: flex;

            align-items: center;

            gap: 10px;

            margin-top: 18px;
        }


        .btn-filter,
        .btn-reset-filter {

            height: 34px;

            padding:
                0 15px;

            border-radius: 7px;

            font-family:
                'Poppins',
                sans-serif;

            font-size: 10px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            cursor: pointer;

            transition: .2s;
        }


        .btn-filter {

            border:
                1px solid #4e969b;

            background: #4e969b;

            color: white;
        }


        .btn-filter:hover {

            background: #3d8589;

            border-color: #3d8589;
        }


        .btn-reset-filter {

            border:
                1px solid #d0d0d0;

            background: white;

            color: #555;

            text-decoration: none;
        }


        .btn-reset-filter:hover {

            border-color: #4e969b;

            color: #4e969b;
        }


        /* =====================================================
           ALERT
        ====================================================== */

        .alert {

            margin-bottom: 18px;

            padding: 10px 14px;

            border-radius: 7px;

            font-size: 11px;
        }


        .alert-success {

            background: #e9f8ee;

            color: #23753c;

            border:
                1px solid #bce8c9;
        }


        .alert-error {

            background: #fff0f0;

            color: #a33131;

            border:
                1px solid #f0c1c1;
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
            width: 10%;
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

            width: 26%;

            text-align: right;
        }


        /* =====================================================
           STUDENT
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

            text-decoration: none;
        }


        .btn i {

            font-size: 10px;
        }


        .btn:hover {

            transform:
                translateY(-1px);

            opacity: .9;
        }


        .btn-upload {

            background: #999999;
        }


        .btn-upload:hover {

            background: #888;
        }


        .btn-view {

            background: #f5f5f5;

            color: #333;

            border:
                1px solid #d5d5d5;
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

            border:
                1.5px solid #222;

            border-radius: 50%;

            background: white;

            cursor: pointer;

            transition: all .2s ease;
        }


        .id-card-circle.active {

            background: #24c95a;

            border-color: #24c95a;
        }


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
   EFEK VERIFIKASI ID CARD
====================================================== */

.certificate.pending.id-verified {

    background: #24c95a;
}

.btn-upload.id-verified {

    background: #24c95a;
}

.btn-upload.id-verified:hover {

    background: #1eac4b;
}


        /* =====================================================
           ACTION
        ====================================================== */

        .actions {

            display: flex;

            justify-content: flex-end;

            align-items: center;

            gap: 8px;

            flex-wrap: wrap;
        }


        /* =====================================================
           MODAL SERTIFIKAT
        ====================================================== */

        .certificate-modal-overlay {

            position: fixed;

            inset: 0;

            z-index: 10000;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                rgba(0, 0, 0, .20);

            backdrop-filter:
                blur(1px);

            opacity: 0;

            visibility: hidden;

            pointer-events: none;

            transition:
                opacity .2s ease,
                visibility .2s ease;
        }


        .certificate-modal-overlay.active {

            opacity: 1;

            visibility: visible;

            pointer-events: auto;
        }


        .certificate-modal {

            width: 520px;

            min-height: 260px;

            max-width:
                calc(100% - 30px);

            background: #ffffff;

            border-radius: 16px;

            padding:
                28px 30px 24px;

            box-shadow:
                0 15px 45px rgba(0, 0, 0, .22);

            transform:
                translateY(8px) scale(.98);

            transition:
                transform .2s ease;
        }


        .certificate-modal-overlay.active
        .certificate-modal {

            transform:
                translateY(0) scale(1);
        }


        .certificate-modal h3 {

            margin: 0;

            color: #202020;

            font-size: 17px;

            font-weight: 500;

            line-height: 1.4;
        }


        .modal-description {

            margin-top: 6px;

            margin-bottom: 22px;

            color: #555;

            font-size: 13px;

            line-height: 1.5;
        }


        .modal-file-group {

            width: 100%;

            display: flex;

            flex-direction: column;

            gap: 8px;
        }


        .modal-file-group label {

            color: #222;

            font-size: 13px;

            font-weight: 400;
        }


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


        .modal-file-group input[type="file"]::file-selector-button {

            height: 40px;

            padding:
                0 13px;

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


        .modal-actions {

            display: flex;

            justify-content: flex-end;

            align-items: center;

            gap: 10px;

            margin-top: 20px;
        }


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


        .modal-cancel {

            background: #fff;

            color: #4e969b;

            border:
                1px solid #8fc1c4;
        }


        .modal-cancel:hover {

            background: #f1f8f8;
        }


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
           MODAL LAPORAN
        ====================================================== */

        .laporan-modal-overlay {

            position: fixed;

            inset: 0;

            background:
                rgba(0, 0, 0, .35);

            display: flex;

            align-items: center;

            justify-content: center;

            z-index: 10000;

            opacity: 0;

            visibility: hidden;

            pointer-events: none;

            transition:
                opacity .2s ease,
                visibility .2s ease;
        }


        .laporan-modal-overlay.active {

            opacity: 1;

            visibility: visible;

            pointer-events: auto;
        }


        .laporan-modal {

            width: 520px;

            max-width:
                calc(100% - 40px);

            background: #ffffff;

            border-radius: 16px;

            padding:
                25px 27px 21px;

            box-shadow:
                0 15px 45px rgba(0, 0, 0, .20);

            transform:
                scale(.95) translateY(10px);

            transition:
                transform .2s ease;
        }


        .laporan-modal-overlay.active
        .laporan-modal {

            transform:
                scale(1) translateY(0);
        }


        .laporan-modal-title {

            color: #1F1F1F;

            font-size: 17px;

            font-weight: 600;

            line-height: 1.3;

            margin-bottom: 5px;
        }


        .laporan-modal-description {

            color: #666;

            font-size: 11px;

            line-height: 1.55;

            margin-bottom: 18px;
        }


        .laporan-modal-label {

            display: block;

            color: #333;

            font-size: 11px;

            font-weight: 500;

            margin-bottom: 7px;
        }


        .laporan-file {

            width: 100%;

            height: 42px;

            border:
                1px solid #999;

            border-radius: 8px;

            padding:
                0 10px;

            background: #fff;

            color: #222;

            font-family:
                'Poppins',
                sans-serif;

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


        .laporan-email {

            margin-top: 8px;

            color: #666;

            font-size: 11px;

            line-height: 1.5;
        }


        .laporan-modal-actions {

            display: flex;

            justify-content: flex-end;

            align-items: center;

            gap: 8px;

            margin-top: 17px;
        }


        .btn-laporan-cancel {

            height: 32px;

            padding:
                0 16px;

            border-radius: 7px;

            background: white;

            border:
                1px solid #A8D1D3;

            color: #4C9FA3;

            font-family:
                'Poppins',
                sans-serif;

            font-size: 10px;

            cursor: pointer;
        }


        .btn-laporan-submit {

            height: 32px;

            padding:
                0 15px;

            border-radius: 7px;

            background: #4C9FA3;

            border:
                1px solid #4C9FA3;

            color: white;

            font-family:
                'Poppins',
                sans-serif;

            font-size: 10px;

            cursor: pointer;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 7px;
        }

        .btn-upload:disabled {

    background: #b1b1b1;

    color: white;

    cursor: not-allowed;

    opacity: .75;

    transform: none;
}


.btn-upload:disabled:hover {

    background: #b1b1b1;

    opacity: .75;

    transform: none;
}

        /* =====================================================
           RESPONSIVE
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

                min-width: 900px;
            }
        }


        @media (max-width: 600px) {

            .certificate-modal {

                width:
                    calc(100% - 30px);

                min-height: auto;

                padding:
                    24px 22px 22px;
            }


            .laporan-modal {

                width:
                    calc(100% - 30px);

                padding: 21px;
            }
        }


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


            .filter-actions {

                flex-wrap: wrap;
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
                Kelola dan pantau data mahasiswa PKL
                dengan mudah dan efisien.
            </p>

        </div>

    </div>


    <div class="header-right">

        <button
            type="button"
            class="menu-btn"
            id="menuBtn"
            title="Menu">

            <i class="fa-solid fa-bars"></i>

        </button>


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


    <a
        href="{{ route('admin.dashboard') }}"
        class="sidebar-menu">

        <i class="fa-regular fa-chart-bar"></i>

        <span>
            Dashboard
        </span>

    </a>


    <a
        href="{{ route('admin.data') }}"
        class="sidebar-menu">

        <i class="fa-regular fa-user"></i>

        <span>
            Data Mahasiswa
        </span>

    </a>


    <a
        href="{{ route('admin.dokumen') }}"
        class="sidebar-menu">

        <i class="fa-regular fa-file-lines"></i>

        <span>
            Dokumen Verification
        </span>

    </a>


    <a
        href="{{ route('admin.sertifikat') }}"
        class="sidebar-menu active">

        <i class="fa-regular fa-file"></i>

        <span>
            Sertifikat
        </span>

    </a>

</div>


<!-- =====================================================
     MAIN
====================================================== -->

<main class="main-content">

    <section class="data-card">


        <!-- =================================================
             FLASH MESSAGE
        ================================================== -->

        @if(session('success'))

            <div class="alert alert-success">

                <i class="fa-solid fa-circle-check"></i>

                {{ session('success') }}

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-error">

                <i class="fa-solid fa-circle-exclamation"></i>

                {{ session('error') }}

            </div>

        @endif


        <!-- =================================================
             VALIDATION ERROR
        ================================================== -->

        @if($errors->any())

            <div class="alert alert-error">

                @foreach($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif


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
                    {{ $mahasiswa->count() }}
                    mahasiswa

                </p>

            </div>

        </div>


        <!-- =================================================
             FILTER FORM
        ================================================== -->

        <form
            class="filter-form"
            method="GET"
            action="{{ route('admin.sertifikat') }}">


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
                        placeholder="Nama, NIM, Email..."
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

                        @foreach($jurusan as $itemJurusan)

                            <option
                                value="{{ $itemJurusan->id_jurusan }}"
                                @selected(request('jurusan') == $itemJurusan->id_jurusan)>

                                {{ $itemJurusan->nama_jurusan }}

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

                        @foreach($universitas as $itemUniversitas)

                            <option
                                value="{{ $itemUniversitas->id_universitas }}"
                                @selected(request('universitas') == $itemUniversitas->id_universitas)>

                                {{ $itemUniversitas->nama_universitas }}

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

                        @foreach($divisi as $itemDivisi)

                            <option
                                value="{{ $itemDivisi->id_divisi }}"
                                @selected(request('divisi') == $itemDivisi->id_divisi)>

                                {{ $itemDivisi->nama_divisi }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            <!-- ================= ROW 2 ================= -->

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
                            @selected(request('status') == 'Akan Datang')>

                            Akan Datang

                        </option>

                        <option
                            value="Aktif"
                            @selected(request('status') == 'Aktif')>

                            Aktif

                        </option>

                        <option
                            value="Selesai"
                            @selected(request('status') == 'Selesai')>

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
                            @selected(request('jenis_kelamin') == 'L')>

                            Laki-laki

                        </option>

                        <option
                            value="P"
                            @selected(request('jenis_kelamin') == 'P')>

                            Perempuan

                        </option>

                    </select>

                </div>

            </div>


            <!-- ================= ACTION FILTER ================= -->

            <div class="filter-actions">

                <button
                    type="submit"
                    class="btn-filter">

                    <i class="fa-solid fa-filter"></i>

                    Terapkan Filter

                </button>


                <a
                    href="{{ route('admin.sertifikat') }}"
                    class="btn-reset-filter">

                    <i class="fa-solid fa-rotate-left"></i>

                    Reset Filter

                </a>

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


                    @forelse($mahasiswa as $item)

                        @php

    /*
    |--------------------------------------------------------------------------
    | PENGAJUAN MAGANG
    |--------------------------------------------------------------------------
    */

    $pengajuan = $item->pengajuanMagang;

    if ($pengajuan instanceof \Illuminate\Support\Collection) {

        $pengajuan = $pengajuan
            ->sortByDesc('id_pengajuan')
            ->first();

    }


    /*
    |--------------------------------------------------------------------------
    | SERTIFIKAT
    |--------------------------------------------------------------------------
    */

    $sertifikat = $item->sertifikat;

    if ($sertifikat instanceof \Illuminate\Support\Collection) {

        $sertifikat = $sertifikat
            ->sortByDesc('id_sertifikat')
            ->first();

    }


    /*
    |--------------------------------------------------------------------------
    | STATUS TAMPILAN
    |--------------------------------------------------------------------------
    */

    $statusTampilan = match ($pengajuan?->status) {

        'Akan Magang' =>
            'Akan Datang',

        'Sedang Magang' =>
            'Aktif',

        'Selesai Magang' =>
            'Selesai',

        default =>
            $pengajuan?->status ?? '-'

    };

@endphp


                        <tr>


                            <!-- =================================
                                 MAHASISWA
                            ================================== -->

                            <td>

                                <div class="student-name">

                                    <span class="name">

                                        {{ $item->nama_lengkap ?? '-' }}

                                    </span>

                                    <span class="year">

                                        {{ $item->nim ?? '-' }}

                                    </span>

                                </div>

                            </td>


                            <!-- =================================
                                 DIVISI
                            ================================== -->

                            <td>

                                {{ $pengajuan?->divisi?->nama_divisi ?? '-' }}

                            </td>


                            <!-- =================================
                                 STATUS
                            ================================== -->

                            <td>

                                <span class="status">

                                    {{ $statusTampilan }}

                                </span>

                            </td>


                            <!-- =================================
                                 LAPORAN
                            ================================== -->

                            <td>

    <div class="actions">

        @if($item->file_laporan)

    <a
        href="{{ asset($item->file_laporan) }}"
        target="_blank"
        rel="noopener noreferrer"
        class="btn btn-view">

        <i class="fa-regular fa-file-lines"></i>

        Lihat

    </a>

@endif


        <button
            type="button"
            class="btn btn-upload btn-upload-laporan"
            data-mahasiswa-id="{{ $item->id_mhs }}"
            data-nama="{{ $item->nama_lengkap ?? '-' }}"
            data-email="{{ $item->email ?? '-' }}"
            data-upload-url="{{ route('admin.laporan.upload', $item->id_mhs) }}">

            <i class="fa-solid fa-arrow-up"></i>

            @if($item->file_laporan)
                Ubah
            @else
                Upload
            @endif

        </button>

    </div>

</td>


                            <!-- =================================
                                 ID CARD
                            ================================== -->

                            <td>

    @if($item->id_card_verified)

        <form
            action="{{ route('admin.sertifikat.verify-id-card', $item->id_mhs) }}"
            method="POST"
            style="display:inline;">

            @csrf

            <button
                type="submit"
                class="id-card-circle active"
                title="ID Card sudah diverifikasi">

            </button>

        </form>

    @else

        <form
            action="{{ route('admin.sertifikat.verify-id-card', $item->id_mhs) }}"
            method="POST"
            style="display:inline;">

            @csrf

            <button
                type="submit"
                class="id-card-circle"
                title="Verifikasi ID Card">

            </button>

        </form>

    @endif

</td>


                            <!-- =================================
                                 SERTIFIKAT
                            ================================== -->

                            <td>

    @if(
    $sertifikat &&
    $sertifikat->status === 'Diterbitkan'
)

    <span class="certificate issued">
        Diterbitkan
    </span>

@elseif($item->id_card_verified)

    <span class="certificate issued">
        Sudah Memenuhi
    </span>

@else

    <span class="certificate pending">
        Belum Memenuhi
    </span>

@endif

</td>


                            <!-- =================================
                                 AKSI
                            ================================== -->

                            <td>

                                <div class="actions">


                                    @if(
                                        $sertifikat &&
                                        $sertifikat->file_sertifikat &&
                                        file_exists(public_path($sertifikat->file_sertifikat))
                                    )

                                        <a
    href="{{ asset($sertifikat->file_sertifikat) }}"
    target="_blank"
    rel="noopener noreferrer"
    class="btn btn-view">

    <i class="fa-regular fa-file-lines"></i>

    Lihat

</a>

                                    @endif


                                    <!--
    PENTING:
    Route dibuat di HTML menggunakan
    data-upload-url.

    Jadi JavaScript tidak lagi
    membuat URL secara langsung.
-->

                                    @if(
    $item->id_card_verified
)

    <button
        type="button"
        class="btn btn-upload btn-open-certificate id-verified"
        data-mahasiswa-id="{{ $item->id_mhs }}"
        data-upload-url="{{ route('admin.sertifikat.upload', $item->id_mhs) }}">

        <i class="fa-solid fa-arrow-up"></i>

        @if(
            $sertifikat &&
            $sertifikat->file_sertifikat
        )

            Ubah Sertifikat

        @else

            Upload Sertifikat

        @endif

    </button>

@else

    <button
        type="button"
        class="btn btn-upload"
        disabled
        title="Verifikasi ID Card terlebih dahulu">

        <i class="fa-solid fa-lock"></i>

        Upload Sertifikat

    </button>

@endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="7"
                                style="
                                    text-align:center;
                                    padding:35px;
                                    color:#888;
                                ">

                                <i
                                    class="fa-regular fa-folder-open"
                                    style="
                                        font-size:25px;
                                        display:block;
                                        margin-bottom:8px;
                                    ">
                                </i>

                                Tidak ada data mahasiswa
                                yang ditemukan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</main>


<!-- =====================================================
     MODAL UPLOAD LAPORAN
====================================================== -->

<div
    class="laporan-modal-overlay"
    id="laporanModal">

    <div class="laporan-modal">

    <form
        id="laporanForm"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        <div class="laporan-modal-title">

            Tambah laporan mahasiswa

        </div>


        <div class="laporan-modal-description">

            Laporan diunggah untuk

            <strong id="laporanNama">
                mahasiswa
            </strong>.

            Pastikan laporan yang diunggah merupakan
            laporan PKL yang sesuai.

        </div>


        <label
            for="laporanFile"
            class="laporan-modal-label">

            Berkas laporan (PDF)

        </label>


        <input
            type="file"
            id="laporanFile"
            name="laporan"
            class="laporan-file"
            accept=".pdf,application/pdf"
            required>


        <div class="laporan-email">

            Email mahasiswa:

            <span id="laporanEmail">
                -
            </span>

        </div>


        <div class="laporan-modal-actions">

            <button
                type="button"
                class="btn-laporan-cancel"
                id="cancelLaporan">

                Batal

            </button>


            <button
                type="submit"
                class="btn-laporan-submit"
                id="submitLaporan">

                <i class="fa-regular fa-paper-plane"></i>

                Simpan & kirim

            </button>

        </div>

    </form>

</div>

</div>


<!-- =====================================================
     MODAL UPLOAD SERTIFIKAT
====================================================== -->

<div
    class="certificate-modal-overlay"
    id="certificateModal">

    <div class="certificate-modal">

        <form
            id="certificateForm"
            method="POST"
            enctype="multipart/form-data">

            @csrf


            <h3>
                Unggah sertifikat digital
            </h3>


            <p class="modal-description">

                Pastikan seluruh persyaratan
                sudah terpenuhi.

            </p>


            <div class="modal-file-group">

                <label for="certificateFile">

                    Berkas sertifikat
                    (PDF/Gambar)

                </label>


                <input
                    type="file"
                    id="certificateFile"
                    name="certificate"
                    accept=".pdf,.jpg,.jpeg,.png"
                    required>

            </div>


            <div class="modal-actions">

                <button
                    type="button"
                    class="modal-btn modal-cancel"
                    id="closeCertificateButton">

                    Batal

                </button>


                <button
                    type="submit"
                    class="modal-btn modal-save">

                    <i class="fa-regular fa-paper-plane"></i>

                    Simpan & kirim

                </button>

            </div>

        </form>

    </div>

</div>


<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =====================================================
       SIDEBAR
    ====================================================== */

    const menuBtn =
        document.getElementById('menuBtn');

    const sidebar =
        document.getElementById('sidebar');

    const sidebarOverlay =
        document.getElementById('sidebarOverlay');


    if (menuBtn && sidebar && sidebarOverlay) {

        menuBtn.addEventListener('click', function () {

            sidebar.classList.toggle('active');

            sidebarOverlay.classList.toggle('active');

        });


        sidebarOverlay.addEventListener('click', function () {

            sidebar.classList.remove('active');

            sidebarOverlay.classList.remove('active');

        });

    }


    /* =====================================================
       ID CARD
    ====================================================== */

    const idCardButtons =
        document.querySelectorAll('.id-card-circle');


    idCardButtons.forEach(function (button) {

    button.addEventListener('click', function () {

        this.classList.toggle('active');


        const row =
            this.closest('tr');

        if (!row) {

            return;

        }


        const certificateBadge =
            row.querySelector('.certificate');

        const uploadSertifikatBtn =
            row.querySelector('.btn-open-certificate');


        if (this.classList.contains('active')) {

            if (certificateBadge) {

                certificateBadge.classList.add(
                    'id-verified'
                );

            }


            if (uploadSertifikatBtn) {

                uploadSertifikatBtn.classList.add(
                    'id-verified'
                );

            }

        } else {

            if (certificateBadge) {

                certificateBadge.classList.remove(
                    'id-verified'
                );

            }


            if (uploadSertifikatBtn) {

                uploadSertifikatBtn.classList.remove(
                    'id-verified'
                );

            }

        }

    });

});


    /* =====================================================
       MODAL SERTIFIKAT
    ====================================================== */

    const certificateModal =
        document.getElementById('certificateModal');

    const certificateForm =
        document.getElementById('certificateForm');

    const certificateFile =
        document.getElementById('certificateFile');

    const closeCertificateButton =
        document.getElementById('closeCertificateButton');


    const certificateButtons =
        document.querySelectorAll('.btn-open-certificate');


    /*
     * Buka modal sertifikat.
     */

    certificateButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const uploadUrl =
                this.dataset.uploadUrl;


            const mahasiswaId =
                this.dataset.mahasiswaId;


            if (!uploadUrl) {

                alert(
                    'URL upload sertifikat tidak ditemukan.'
                );

                return;
            }


            if (!mahasiswaId) {

                alert(
                    'ID mahasiswa tidak ditemukan.'
                );

                return;
            }


            /*
             * URL sudah dibuat oleh Blade
             * melalui data-upload-url.
             *
             * JavaScript hanya mengambilnya.
             */

            certificateForm.action =
                uploadUrl;


            certificateFile.value = '';


            certificateModal.classList.add(
                'active'
            );


            setTimeout(function () {

                certificateFile.focus();

            }, 100);

        });

    });


    /*
     * Tutup modal sertifikat.
     */

    function closeCertificateModal() {

        certificateModal.classList.remove(
            'active'
        );

        certificateFile.value = '';

        certificateForm.action = '';

    }


    if (closeCertificateButton) {

        closeCertificateButton.addEventListener(
            'click',
            function () {

                closeCertificateModal();

            }
        );

    }


    /*
     * Klik area luar modal.
     */

    certificateModal.addEventListener(
        'click',
        function (event) {

            if (
                event.target ===
                certificateModal
            ) {

                closeCertificateModal();

            }

        }
    );


    /* =====================================================
       VALIDASI SERTIFIKAT
    ====================================================== */

    certificateForm.addEventListener(
        'submit',
        function (event) {

            const file =
                certificateFile.files[0];


            /*
             * Pastikan file dipilih.
             */

            if (!file) {

                event.preventDefault();

                alert(
                    'Silakan pilih berkas sertifikat terlebih dahulu.'
                );

                return;

            }


            /*
             * Validasi ekstensi.
             */

            const fileName =
                file.name || '';


            const extension =
                fileName
                    .split('.')
                    .pop()
                    .toLowerCase();


            const allowedExtensions = [
                'pdf',
                'jpg',
                'jpeg',
                'png'
            ];


            if (
                !allowedExtensions.includes(
                    extension
                )
            ) {

                event.preventDefault();

                alert(
                    'Sertifikat harus berupa PDF, JPG, JPEG, atau PNG.'
                );

                certificateFile.value = '';

                return;

            }


            /*
             * Validasi ukuran.
             *
             * 5 MB = 5 * 1024 * 1024 bytes
             */

            const maxSize =
                5 * 1024 * 1024;


            if (file.size > maxSize) {

                event.preventDefault();

                alert(
                    'Ukuran sertifikat maksimal 5 MB.'
                );

                certificateFile.value = '';

                return;

            }


            /*
             * Pastikan form memiliki action.
             */

            if (!certificateForm.action) {

                event.preventDefault();

                alert(
                    'URL upload sertifikat tidak ditemukan.'
                );

                return;

            }

        }
    );


    /* =====================================================
       MODAL LAPORAN
    ====================================================== */
    const laporanForm =
    document.getElementById('laporanForm');

    const laporanModal =
        document.getElementById('laporanModal');
        

    const laporanButtons =
        document.querySelectorAll(
            '.btn-upload-laporan'
        );

    const cancelLaporan =
        document.getElementById(
            'cancelLaporan'
        );

    const submitLaporan =
        document.getElementById(
            'submitLaporan'
        );

    const laporanNama =
        document.getElementById(
            'laporanNama'
        );

    const laporanEmail =
        document.getElementById(
            'laporanEmail'
        );

    const laporanFile =
        document.getElementById(
            'laporanFile'
        );


    /*
     * Buka modal laporan.
     */

    laporanButtons.forEach(
    function (button) {

        button.addEventListener(
            'click',
            function () {

                const uploadUrl =
                    this.dataset.uploadUrl;


                const nama =
                    this.dataset.nama ||
                    'mahasiswa';


                const email =
                    this.dataset.email ||
                    '-';


                if (!uploadUrl) {

                    alert(
                        'URL upload laporan tidak ditemukan.'
                    );

                    return;

                }


                laporanForm.action =
                    uploadUrl;


                laporanNama.textContent =
                    nama;


                laporanEmail.textContent =
                    email;


                laporanFile.value = '';


                laporanModal.classList.add(
                    'active'
                );

            }
        );

    }
);


    /*
     * Tutup modal laporan.
     */

    function closeLaporanModal() {

        laporanModal.classList.remove(
            'active'
        );

        laporanFile.value = '';
        laporanForm.action = '';
    }


    cancelLaporan.addEventListener(
        'click',
        function () {

            closeLaporanModal();

        }
    );


    laporanModal.addEventListener(
        'click',
        function (event) {

            if (
                event.target ===
                laporanModal
            ) {

                closeLaporanModal();

            }

        }
    );


    /*
     * Submit laporan.
     *
     * Saat ini bagian laporan masih UI karena
     * route/controller upload laporan belum diberikan.
     */

    submitLaporan.addEventListener(
        'click',
        function () {

            const file =
                laporanFile.files[0];


            if (!file) {

                alert(
                    'Silakan pilih berkas laporan terlebih dahulu.'
                );

                return;

            }


            const extension =
                file.name
                    .split('.')
                    .pop()
                    .toLowerCase();


            if (extension !== 'pdf') {

                alert(
                    'Berkas laporan harus berupa PDF.'
                );

                laporanFile.value = '';

                return;

            }


            if (
                file.size >
                5 * 1024 * 1024
            ) {

                alert(
                    'Ukuran laporan maksimal 5 MB.'
                );

                laporanFile.value = '';

                return;

            };

        }
    );


    /* =====================================================
       ESCAPE
    ====================================================== */

    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key !== 'Escape') {

                return;

            }


            if (
                certificateModal.classList.contains(
                    'active'
                )
            ) {

                closeCertificateModal();

            }


            if (
                laporanModal.classList.contains(
                    'active'
                )
            ) {

                closeLaporanModal();

            }


            if (
                sidebar &&
                sidebar.classList.contains(
                    'active'
                )
            ) {

                sidebar.classList.remove(
                    'active'
                );


                sidebarOverlay.classList.remove(
                    'active'
                );

            }

        }
    );

});

</script>

</body>

</html>