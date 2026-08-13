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
    
    <link rel="stylesheet" href="{{ asset('css/admin/detail.css') }}">
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
