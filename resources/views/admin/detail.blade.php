<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Mahasiswa - Monitoring Mahasiswa PKL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html,
        body {
            background-image: url('{{ asset("assets/image/bg-pln.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
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


        /* ================= LOGO ================= */

        .logo-box {
            width: 230px;
            height: 72px;

            background: white;

            clip-path: polygon(
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
        }


        /* ================= HEADER LEFT ================= */

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


        /* ================= MENU BUTTON ================= */

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

        .sidebar-menu:hover {
            background: rgba(255, 255, 255, .15);

            transform: translateX(3px);
        }

        .sidebar-menu.active {
            background: rgba(255, 255, 255, .15);

            font-weight: 500;
        }


        /* ================= OVERLAY ================= */

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
            width: 100%;

            max-width: 1000px;

            margin: 100px auto 40px;

            padding: 0 20px;
        }


        /* ================= CARD ================= */

        .card {
            width: 100%;

            background: #FFFFFF;

            border-radius: 24px;

            padding: 28px;

            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);

            border: 1px solid #E8EDF2;
        }


        /* ================= PAGE TITLE ================= */

        .page-header {
            display: flex;

            justify-content: space-between;

            align-items: flex-start;

            gap: 20px;

            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 20px;

            font-weight: 700;

            color: #202020;

            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 13px;

            color: #A4A4A4;
        }


        /* ================= STATUS ================= */

        .status {
            display: inline-flex;

            align-items: center;

            padding: 7px 13px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: 600;

            white-space: nowrap;
        }

        .status-akan {
            background: #FFEED9;

            color: #D9822B;
        }

        .status-sedang {
            background: #E8F4F4;

            color: #2B7C82;
        }

        .status-selesai {
            background: #EAEAEA;

            color: #666;
        }


        /* ================= SECTION ================= */

        .section {
            margin-top: 25px;

            border: 1px solid #E8EDF2;

            border-radius: 15px;

            overflow: hidden;
        }

        .section-title {
            padding: 15px 18px;

            background: #F7FBFB;

            border-bottom: 1px solid #E8EDF2;

            display: flex;

            align-items: center;

            gap: 10px;

            color: #0A5F63;

            font-size: 14px;

            font-weight: 700;
        }

        .section-title i {
            font-size: 16px;
        }


        /* ================= INFO GRID ================= */

        .info-grid {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 0;
        }

        .info-item {
            padding: 15px 18px;

            border-bottom: 1px solid #F0F0F0;
        }

        .info-item:nth-child(odd) {
            border-right: 1px solid #F0F0F0;
        }

        .info-label {
            font-size: 11px;

            color: #999;

            margin-bottom: 5px;
        }

        .info-value {
            font-size: 13px;

            font-weight: 600;

            color: #333;

            word-break: break-word;
        }


        /* ================= DOCUMENT ================= */

        .document-grid {
            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 15px;

            padding: 18px;
        }

        .document-card {
            border: 1px solid #E5E9EC;

            border-radius: 12px;

            padding: 18px;

            display: flex;

            align-items: center;

            gap: 13px;

            transition: .2s;

            background: white;
        }

        .document-card:hover {
            border-color: #0A5F63;

            transform: translateY(-2px);

            box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
        }

        .document-icon {
            width: 45px;
            height: 45px;

            border-radius: 10px;

            background: #E8F4F4;

            color: #0A5F63;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 19px;

            flex-shrink: 0;
        }

        .document-info {
            min-width: 0;
        }

        .document-info h4 {
            font-size: 12px;

            color: #333;

            margin-bottom: 3px;
        }

        .document-info p {
            font-size: 10px;

            color: #999;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

            max-width: 150px;
        }

.document-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    color: #0A5F63;
    text-decoration: none;

    font-size: 11px;
    font-weight: 600;

    padding: 6px 10px;

    background: #F0F8F8;

    border-radius: 7px;

    transition: .2s;
}

.document-link:hover {
    background: #DCEEEE;
    transform: translateY(-1px);
}

.document-link i {
    font-size: 14px;
}


        .document-empty {
            padding: 18px;

            color: #999;

            font-size: 12px;

            text-align: center;
        }


        /* ================= BUTTON ================= */

        .button-area {
            margin-top: 25px;

            display: flex;

            justify-content: flex-end;

            gap: 12px;
        }
        .btn-edit {
    height: 42px;

    padding: 0 24px;

    border-radius: 8px;

    background: #0A5F63;

    color: white;

    text-decoration: none;

    display: flex;

    justify-content: center;

    align-items: center;

    gap: 7px;

    font-size: 13px;

    font-weight: 600;

    transition: .2s;
}

.btn-edit:hover {
    background: #084D50;
}

        .btn-back {
            height: 42px;

            padding: 0 24px;

            border-radius: 8px;

            background: #A4A4A4;

            color: white;

            text-decoration: none;

            display: flex;

            justify-content: center;

            align-items: center;

            gap: 7px;

            font-size: 13px;

            font-weight: 600;

            transition: .2s;
        }

        .btn-back:hover {
            background: #8E8E8E;
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 768px) {

            .logo-box {
                width: 150px;
            }

            .logo-box img {
                width: 110px;
            }

            .header-title {
                display: none;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .info-item:nth-child(odd) {
                border-right: none;
            }

            .document-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
            }
        }

    </style>
</head>

<body>


    <!-- ================= HEADER ================= -->

    <header class="header">

        <div class="header-left">

            <div class="logo-box">
                <img src="{{ asset('assets/image/logo-pln.png') }}"
                    alt="Logo PLN">
            </div>

            <div class="header-title">

                <h2>Monitoring Mahasiswa PKL</h2>

                <p>
                    Kelola dan pantau data mahasiswa PKL dengan mudah dan efisien.
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



    <!-- ================= SIDEBAR ================= -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay">
    </div>


    <div
        class="sidebar"
        id="sidebar">


        <!-- Dashboard -->

        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-chart-bar"></i>

            <span>Dashboard</span>

        </a>


        <!-- Data Mahasiswa -->

        <a href="{{ route('admin.data') }}"
            class="sidebar-menu active">

            <i class="fa-regular fa-user"></i>

            <span>Data Mahasiswa</span>

        </a>


        <!-- Dokumen -->

        <a href="{{ route('admin.dokumen') }}"
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



    <!-- ================= CONTENT ================= -->

    <div class="content">

        <div class="card">


            <!-- PAGE HEADER -->

            <div class="page-header">

                <div>

                    <h1>
                        Detail Mahasiswa Magang
                    </h1>

                    <p class="subtitle">
                        Informasi lengkap mahasiswa dan data pelaksanaan PKL.
                    </p>

                </div>


                @if($pengajuan->status === 'Akan Magang')

                    <div class="status status-akan">
                        Akan Magang
                    </div>

                @elseif($pengajuan->status === 'Sedang Magang')

                    <div class="status status-sedang">
                        Sedang Magang
                    </div>

                @else

                    <div class="status status-selesai">
                        Selesai Magang
                    </div>

                @endif

            </div>



            <!-- ================= INFORMASI MAHASISWA ================= -->

            <div class="section">

                <div class="section-title">

                    <i class="fa-regular fa-user"></i>

                    Informasi Mahasiswa

                </div>


                <div class="info-grid">


                    <div class="info-item">

                        <div class="info-label">
                            Nama Lengkap
                        </div>

                        <div class="info-value">
                            {{ $pengajuan->mahasiswa->nama_lengkap ?? '-' }}
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            NIM
                        </div>

                        <div class="info-value">
                            {{ $pengajuan->mahasiswa->nim ?? '-' }}
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Jenis Kelamin
                        </div>

                        <div class="info-value">
                            {{ $pengajuan->mahasiswa->jenis_kelamin ?? '-' }}
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Universitas
                        </div>

                        <div class="info-value">
                            {{ $pengajuan->mahasiswa->universitas->nama_universitas ?? '-' }}
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Jurusan / Program Studi
                        </div>

                        <div class="info-value">
                            {{ $pengajuan->mahasiswa->jurusan->nama_jurusan ?? '-' }}
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Email
                        </div>

                        <div class="info-value">
                            {{ $pengajuan->mahasiswa->email ?? '-' }}
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Nomor Telepon
                        </div>

                        <div class="info-value">
                            {{ $pengajuan->mahasiswa->no_telepon ?? '-' }}
                        </div>

                    </div>


                </div>

            </div>



            <!-- ================= INFORMASI MAGANG ================= -->

            <div class="section">

                <div class="section-title">

                    <i class="fa-solid fa-briefcase"></i>

                    Informasi Magang

                </div>


                <div class="info-grid">


                    <div class="info-item">

                        <div class="info-label">
                            Divisi Penempatan
                        </div>

                        <div class="info-value">
                            {{ $pengajuan->divisi->nama_divisi ?? '-' }}
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Nama Pembimbing Lapangan
                        </div>

                        <div class="info-value">
                            {{ $pengajuan->nama_pembimbing ?? '-' }}
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Tanggal Mulai
                        </div>

                        <div class="info-value">

                            @if($pengajuan->tanggal_mulai)

                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d F Y') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Tanggal Selesai
                        </div>

                        <div class="info-value">

                            @if($pengajuan->tanggal_selesai)

                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>


                </div>

            </div>



<!-- ================= DOKUMEN MAGANG ================= -->

<div class="section">

    <div class="section-title">

        <i class="fa-regular fa-folder-open"></i>

        Dokumen Magang

    </div>


    <div class="document-grid">


        <!-- ================= SURAT PENGANTAR ================= -->

        <div class="document-card">

            <div class="document-icon">
                <i class="fa-solid fa-file-pdf"></i>
            </div>

            <div class="document-info">

                <h4>Surat Pengantar Kampus</h4>

                @if($pengajuan->surat_permohonan)

                    <a
                        href="{{ asset('uploads/surat_permohonan/' . $pengajuan->surat_permohonan) }}"
                        target="_blank"
                        class="document-link">

                        <i class="fa-solid fa-eye"></i>

                        Lihat Surat

                    </a>

                @else

                    <p>Belum ada dokumen</p>

                @endif

            </div>

        </div>



        <!-- ================= PROPOSAL ================= -->

        <div class="document-card">

            <div class="document-icon">
                <i class="fa-solid fa-file-lines"></i>
            </div>

            <div class="document-info">

                <h4>Proposal</h4>

                @if($pengajuan->proposal)

                    <a
                        href="{{ asset('uploads/proposal/' . $pengajuan->proposal) }}"
                        target="_blank"
                        class="document-link">

                        <i class="fa-solid fa-eye"></i>

                        Lihat Proposal

                    </a>

                @else

                    <p>Belum ada dokumen</p>

                @endif

            </div>

        </div>



        <!-- ================= CV ================= -->

        <div class="document-card">

            <div class="document-icon">

                <i class="fa-solid fa-file-user"></i>

            </div>

            <div class="document-info">

                <h4>CV / Curriculum Vitae</h4>

                @if($pengajuan->cv)

                    <a
                        href="{{ asset('uploads/cv/' . $pengajuan->cv) }}"
                        target="_blank"
                        class="document-link">

                        <i class="fa-solid fa-eye"></i>

                        Lihat CV

                    </a>

                @else

                    <p>Belum ada dokumen</p>

                @endif

            </div>

        </div>


    </div>

</div>




            <!-- ================= BUTTON ================= -->

            <div class="button-area">

    <a
        href="{{ route('admin.data') }}"
        class="btn-back">

        <i class="fa-solid fa-arrow-left"></i>

        Kembali

    </a>


    <a
        href="{{ route('admin.edit', $pengajuan->id_pengajuan) }}"
        class="btn-edit">

        <i class="fa-solid fa-pen"></i>

        Edit Data

    </a>

</div>


        </div>

    </div>



    <!-- ================= JAVASCRIPT ================= -->

    <script>

        const menuBtn = document.getElementById('menuBtn');

        const sidebar = document.getElementById('sidebar');

        const sidebarOverlay =
            document.getElementById('sidebarOverlay');


        menuBtn.addEventListener('click', function () {

            sidebar.classList.toggle('active');

            sidebarOverlay.classList.toggle('active');

        });


        sidebarOverlay.addEventListener('click', function () {

            sidebar.classList.remove('active');

            sidebarOverlay.classList.remove('active');

        });

    </script>


</body>

</html>
