<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mahasiswa - Monitoring Mahasiswa PKL</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/detail.css') }}">
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

        <a href="{{ route('admin.dashboard') }}" class="sidebar-menu">
            <i class="fa-regular fa-chart-bar"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.data') }}" class="sidebar-menu active">
            <i class="fa-regular fa-user"></i>
            <span>Data Mahasiswa</span>
        </a>

        <a href="{{ route('admin.dokumen') }}" class="sidebar-menu">
            <i class="fa-regular fa-file-lines"></i>
            <span>Dokumen Verification</span>
        </a>

        <a href="{{ route('admin.sertifikat') }}" class="sidebar-menu">
            <i class="fa-regular fa-file"></i>
            <span>Sertifikat</span>
        </a>
    </aside>

    <!-- ================= CONTENT ================= -->
    <main class="content">

        <section class="card">

            <!-- PAGE HEADER -->
            <div class="page-header">

                <div>
                    <h1>Detail Mahasiswa Magang</h1>
                    <p class="subtitle">
                        Informasi lengkap mahasiswa dan pelaksanaan Praktik Kerja Lapangan.
                    </p>
                </div>

                @if($pengajuan->status === 'Akan Magang')
                <span class="status status-akan">Akan Magang</span>
                @elseif($pengajuan->status === 'Sedang Magang')
                <span class="status status-sedang">Sedang Magang</span>
                @else
                <span class="status status-selesai">Selesai Magang</span>
                @endif

            </div>

            <!-- ================= INFORMASI MAHASISWA ================= -->
            <div class="section">

                <div class="section-title">
                    <i class="fa-regular fa-user"></i>
                    <span>Informasi Mahasiswa</span>
                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value">
                            {{ $pengajuan->mahasiswa->nama_lengkap ?? '-' }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">NIM</span>
                        <span class="info-value">
                            {{ $pengajuan->mahasiswa->nim ?? '-' }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Jenis Kelamin</span>
                        <span class="info-value">
                            {{ $pengajuan->mahasiswa->jenis_kelamin ?? '-' }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Universitas</span>
                        <span class="info-value">
                            {{ $pengajuan->mahasiswa->universitas->nama_universitas ?? '-' }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Jurusan / Program Studi</span>
                        <span class="info-value">
                            {{ $pengajuan->mahasiswa->jurusan->nama_jurusan ?? '-' }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">
                            {{ $pengajuan->mahasiswa->email ?? '-' }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Nomor Telepon</span>
                        <span class="info-value">
                            {{ $pengajuan->mahasiswa->no_telepon ?? '-' }}
                        </span>
                    </div>

                </div>

            </div>

            <!-- ================= INFORMASI MAGANG ================= -->
            <div class="section">

                <div class="section-title">
                    <i class="fa-solid fa-briefcase"></i>
                    <span>Informasi Magang</span>
                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span class="info-label">Divisi Penempatan</span>
                        <span class="info-value">
                            {{ $pengajuan->divisi->nama_divisi ?? '-' }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Pembimbing Lapangan</span>
                        <span class="info-value">
                            {{ $pengajuan->nama_pembimbing ?? '-' }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Tanggal Mulai</span>
                        <span class="info-value">
                            @if($pengajuan->tanggal_mulai)
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d F Y') }}
                            @else
                            -
                            @endif
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Tanggal Selesai</span>
                        <span class="info-value">
                            @if($pengajuan->tanggal_selesai)
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}
                            @else
                            -
                            @endif
                        </span>
                    </div>

                </div>

            </div>

            <!-- ================= DOKUMEN MAGANG ================= -->
            <div class="section">

                <div class="section-title">
                    <i class="fa-regular fa-folder-open"></i>
                    <span>Dokumen Magang</span>
                </div>

                <div class="document-grid">

                    <!-- Surat Pengantar -->
                    <div class="document-card">
                        <div class="document-icon">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>

                        <div class="document-info">
                            <h4>Surat Pengantar Kampus</h4>

                            @if($pengajuan->surat_permohonan)
                            <a href="{{ asset('uploads/surat_permohonan/' . $pengajuan->surat_permohonan) }}"
                                target="_blank"
                                class="document-link">
                                <i class="fa-solid fa-eye"></i>
                                Lihat Dokumen
                            </a>
                            @else
                            <p class="document-empty">Belum ada dokumen</p>
                            @endif
                        </div>
                    </div>

                    <!-- Proposal -->
                    <div class="document-card">
                        <div class="document-icon">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>

                        <div class="document-info">
                            <h4>Proposal Magang</h4>

                            @if($pengajuan->proposal)
                            <a href="{{ asset('uploads/proposal/' . $pengajuan->proposal) }}"
                                target="_blank"
                                class="document-link">
                                <i class="fa-solid fa-eye"></i>
                                Lihat Dokumen
                            </a>
                            @else
                            <p class="document-empty">Belum ada dokumen</p>
                            @endif
                        </div>
                    </div>

                    <!-- CV -->
                    <div class="document-card">
                        <div class="document-icon">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>

                        <div class="document-info">
                            <h4>CV / Curriculum Vitae</h4>

                            @if($pengajuan->cv)
                            <a href="{{ asset('uploads/cv/' . $pengajuan->cv) }}"
                                target="_blank"
                                class="document-link">
                                <i class="fa-solid fa-eye"></i>
                                Lihat CV
                            </a>
                            @else
                            <p class="document-empty">Belum ada dokumen</p>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

            <!-- ================= BUTTON ================= -->
            <div class="button-area">

                <a href="{{ route('admin.data') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>

                <a href="{{ route('admin.edit', $pengajuan->id_pengajuan) }}" class="btn-edit">
                    <i class="fa-solid fa-pen"></i>
                    Edit Data
                </a>

            </div>

        </section>

    </main>

    <!-- ================= JAVASCRIPT ================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuBtn = document.getElementById('menuBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            menuBtn?.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });

            overlay?.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        });
    </script>

</body>

</html>

<!--  -->