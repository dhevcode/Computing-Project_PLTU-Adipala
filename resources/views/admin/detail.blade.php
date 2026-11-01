<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mahasiswa & Verifikasi - Monitoring PKL PLTU</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/detail.css') }}">

    <style>
        /* ================= EXTENDED STATUS BADGES ================= */
        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pending {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FCD34D;
        }
        .status-akan {
            background: #EFF6FF;
            color: #1D4ED8;
            border: 1px solid #BFDBFE;
        }
        .status-sedang {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }
        .status-selesai {
            background: #F3F4F6;
            color: #4B5563;
            border: 1px solid #E5E7EB;
        }
        .status-ditolak {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        /* ================= ALERT BANNERS ================= */
        .alert-banner {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            line-height: 1.5;
        }
        .alert-banner.success {
            background: #E8F8F5;
            color: #0E6251;
            border: 1px solid #A3E4D7;
        }
        .alert-banner.warning {
            background: #FEF9E7;
            color: #7D6608;
            border: 1px solid #F9E79F;
        }
        .alert-banner.danger {
            background: #FDEDEC;
            color: #78281F;
            border: 1px solid #F5B7B1;
        }
        .alert-banner i {
            font-size: 18px;
            flex-shrink: 0;
        }

        /* ================= VERIFICATION ACTION HERO CARDS ================= */
        .verification-hero-card {
            background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
            border: 1.5px solid #F59E0B;
            border-radius: 14px;
            padding: 20px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.12);
        }
        .verification-rejected-card {
            background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
            border: 1.5px solid #EF4444;
            border-radius: 14px;
            padding: 20px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .hero-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .hero-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #F59E0B;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .hero-icon.danger {
            background: #EF4444;
        }
        .hero-left h3 {
            font-size: 16px;
            color: #78350F;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .verification-rejected-card .hero-left h3 {
            color: #991B1B;
        }
        .hero-left p {
            font-size: 12px;
            color: #92400E;
            margin: 0;
            max-width: 650px;
        }
        .verification-rejected-card .hero-left p {
            color: #B91C1C;
        }
        .hero-left small {
            display: inline-block;
            margin-top: 6px;
            font-size: 11px;
            color: #7F1D1D;
        }
        .hero-actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }
        .btn-action-terima {
            background: #059669;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 10px rgba(5, 150, 105, 0.25);
        }
        .btn-action-terima:hover {
            background: #047857;
            transform: translateY(-1px);
        }
        .btn-action-tolak {
            background: #DC2626;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-action-tolak:hover {
            background: #B91C1C;
            transform: translateY(-1px);
        }
        .btn-action-plot {
            background: #0A5F63;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-left: 8px;
            vertical-align: middle;
            transition: 0.2s;
        }
        .btn-action-plot:hover {
            background: #084C4F;
        }

        /* ================= MODAL STYLES ================= */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-card {
            background: #fff;
            border-radius: 16px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
            animation: modalFadeIn 0.25s ease-out;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #F9FAFB;
            border-radius: 16px 16px 0 0;
        }
        .modal-header h3 {
            font-size: 17px;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 18px;
            color: #9CA3AF;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: 0.2s;
        }
        .modal-close:hover {
            color: #111827;
            background: #E5E7EB;
        }
        .modal-body {
            padding: 24px;
        }
        .modal-candidate-box {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .modal-candidate-box.danger-box {
            background: #FEF2F2;
            border-color: #FECACA;
        }
        .modal-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #0A5F63;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }
        .modal-form-group {
            margin-bottom: 18px;
        }
        .modal-form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .modal-form-group label span.req {
            color: #DC2626;
        }
        .modal-form-group select,
        .modal-form-group input,
        .modal-form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 13px;
            color: #1F2937;
            background: #fff;
            transition: 0.2s;
            box-sizing: border-box;
        }
        .modal-form-group select:focus,
        .modal-form-group input:focus,
        .modal-form-group textarea:focus {
            outline: none;
            border-color: #0A5F63;
            box-shadow: 0 0 0 3px rgba(10, 95, 99, 0.15);
        }
        .modal-form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .modal-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .preset-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }
        .preset-tag {
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
            color: #4B5563;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 999px;
            cursor: pointer;
            transition: 0.2s;
        }
        .preset-tag:hover {
            background: #E5E7EB;
            color: #111827;
            border-color: #D1D5DB;
        }
        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #E5E7EB;
            background: #F9FAFB;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-radius: 0 0 16px 16px;
        }
        .btn-cancel {
            background: #E5E7EB;
            color: #374151;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-cancel:hover {
            background: #D1D5DB;
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

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- ================= SIDEBAR ================= -->
    @include('admin.sidebar')

    <!-- ================= CONTENT ================= -->
    <main class="content">

        <section class="card">

            <!-- PAGE HEADER -->
            <div class="page-header">

                <div>
                    <h1>Detail & Verifikasi Mahasiswa Magang</h1>
                    <p class="subtitle">
                        Informasi lengkap calon/peserta, verifikasi berkas, dan ploting pembimbing lapangan.
                    </p>
                </div>

                @if($pengajuan->status === 'Menunggu Verifikasi')
                <span class="status status-pending">
                    <i class="fa-solid fa-hourglass-half"></i> Menunggu Verifikasi
                </span>
                @elseif($pengajuan->status === 'Akan Magang')
                <span class="status status-akan">
                    <i class="fa-regular fa-clock"></i> Akan Magang
                </span>
                @elseif($pengajuan->status === 'Sedang Magang')
                <span class="status status-sedang">
                    <i class="fa-regular fa-circle-check"></i> Sedang Magang
                </span>
                @elseif($pengajuan->status === 'Selesai Magang')
                <span class="status status-selesai">
                    <i class="fa-solid fa-flag-checkered"></i> Selesai Magang
                </span>
                @elseif($pengajuan->status === 'Ditolak')
                <span class="status status-ditolak">
                    <i class="fa-solid fa-circle-xmark"></i> Pendaftaran Ditolak
                </span>
                @else
                <span class="status status-selesai">{{ $pengajuan->status }}</span>
                @endif

            </div>

            <!-- FLASH ALERTS -->
            @if(session('success'))
            <div class="alert-banner success">
                <i class="fa-solid fa-circle-check"></i>
                <div>{{ session('success') }}</div>
            </div>
            @endif

            @if(session('warning'))
            <div class="alert-banner warning">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>{{ session('warning') }}</div>
            </div>
            @endif

            @if(session('error'))
            <div class="alert-banner danger">
                <i class="fa-solid fa-circle-xmark"></i>
                <div>{{ session('error') }}</div>
            </div>
            @endif

            @if(isset($errors) && $errors->any())
            <div class="alert-banner danger">
                <i class="fa-solid fa-circle-exmark"></i>
                <div>
                    <strong>Terdapat kesalahan pengisian data:</strong>
                    <ul style="margin: 4px 0 0 16px; padding: 0;">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- ================= STATUS ACTION HERO BANNER ================= -->
            @if($pengajuan->status === 'Menunggu Verifikasi')
            <div class="verification-hero-card">
                <div class="hero-left">
                    <div class="hero-icon">
                        <i class="fa-solid fa-user-clock"></i>
                    </div>
                    <div>
                        <h3>Calon Peserta Magang Menunggu Keputusan Admin</h3>
                        <p>
                            Calon peserta telah mengajukan pendaftaran magang. Tinjau kelengkapan profil & berkas persyaratan di bawah ini, lalu pilih <strong>Terima</strong> untuk memplot Pembimbing Lapangan atau <strong>Tolak</strong> jika tidak memenuhi kualifikasi.
                        </p>
                    </div>
                </div>

                <div class="hero-actions">
                    <button type="button" class="btn-action-terima" onclick="openModalTerima()">
                        <i class="fa-solid fa-user-check"></i>
                        Terima & Plot Pembimbing
                    </button>
                    <button type="button" class="btn-action-tolak" onclick="openModalTolak()">
                        <i class="fa-solid fa-user-xmark"></i>
                        Tolak Pendaftaran
                    </button>
                </div>
            </div>
            @elseif($pengajuan->status === 'Ditolak')
            <div class="verification-rejected-card">
                <div class="hero-left">
                    <div class="hero-icon danger">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div>
                        <h3>Pendaftaran Telah Ditolak</h3>
                        <p>
                            <strong>Alasan Penolakan:</strong> {{ $pengajuan->alasan_penolakan ?? 'Tidak ada alasan khusus yang dicantumkan.' }}
                        </p>
                        @if($pengajuan->verified_at)
                        <small>
                            <i class="fa-regular fa-clock"></i> Diverifikasi pada: {{ \Carbon\Carbon::parse($pengajuan->verified_at)->translatedFormat('d F Y, H:i') }} WIB
                            @if($pengajuan->verifiedBy)
                            oleh <strong>{{ $pengajuan->verifiedBy->nama_lengkap }}</strong>
                            @endif
                        </small>
                        @endif
                    </div>
                </div>

                <div class="hero-actions">
                    <button type="button" class="btn-action-terima" onclick="openModalTerima()">
                        <i class="fa-solid fa-rotate-left"></i>
                        Tinjau Ulang & Terima
                    </button>
                </div>
            </div>
            @endif

            <!-- ================= INFORMASI MAHASISWA ================= -->
            <div class="section">

                <div class="section-title">
                    <i class="fa-regular fa-user"></i>
                    <span>Informasi Mahasiswa / Calon Peserta</span>
                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value">
                            <strong>{{ $pengajuan->mahasiswa->nama_lengkap ?? '-' }}</strong>
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">NIM / NIS</span>
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
                        <span class="info-label">Universitas / Kampus Asal</span>
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
                            <a href="mailto:{{ $pengajuan->mahasiswa->email }}" style="color:#0A5F63;text-decoration:none;">
                                {{ $pengajuan->mahasiswa->email ?? '-' }}
                            </a>
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Nomor Telepon / WhatsApp</span>
                        <span class="info-value">
                            {{ $pengajuan->mahasiswa->no_telepon ?? '-' }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Kontak Darurat</span>
                        <span class="info-value">
                            {{ $pengajuan->mahasiswa->emergency_contact_name ?? '-' }}
                            @if($pengajuan->mahasiswa->emergency_contact_phone)
                            ({{ $pengajuan->mahasiswa->emergency_contact_phone }})
                            @endif
                        </span>
                    </div>

                </div>

            </div>

            <!-- ================= INFORMASI MAGANG & PEMBIMBING ================= -->
            <div class="section">

                <div class="section-title">
                    <i class="fa-solid fa-briefcase"></i>
                    <span>Informasi Magang & Ploting Pembimbing Lapangan</span>
                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span class="info-label">Divisi Penempatan</span>
                        <span class="info-value">
                            <strong>{{ $pengajuan->divisi->nama_divisi ?? '-' }}</strong>
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Pembimbing Lapangan</span>
                        <span class="info-value">
                            @if($pengajuan->pembimbing)
                                <span style="display:inline-flex;align-items:center;gap:6px;color:#0A5F63;font-weight:600;">
                                    <i class="fa-solid fa-user-tie"></i>
                                    {{ $pengajuan->pembimbing->nama_lengkap }}
                                </span>
                                <button type="button" class="btn-action-plot" onclick="openModalTerima()" title="Ganti Pembimbing Lapangan">
                                    <i class="fa-solid fa-arrows-rotate"></i> Ganti
                                </button>
                            @elseif($pengajuan->nama_pembimbing)
                                <span>{{ $pengajuan->nama_pembimbing }}</span>
                                <button type="button" class="btn-action-plot" onclick="openModalTerima()" title="Ploting ke Akun Pembimbing">
                                    <i class="fa-solid fa-link"></i> Hubungkan
                                </button>
                            @else
                                <span style="color:#DC2626;font-weight:600;">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Belum Diploting
                                </span>
                                <button type="button" class="btn-action-plot" onclick="openModalTerima()">
                                    <i class="fa-solid fa-user-plus"></i> Plot Sekarang
                                </button>
                            @endif
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Tanggal Mulai Magang</span>
                        <span class="info-value">
                            @if($pengajuan->tanggal_mulai)
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d F Y') }}
                            @else
                            -
                            @endif
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Tanggal Selesai Magang</span>
                        <span class="info-value">
                            @if($pengajuan->tanggal_selesai)
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}
                            @else
                            -
                            @endif
                        </span>
                    </div>

                    @if($pengajuan->catatan_admin)
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <span class="info-label">Catatan Admin / Arahan Magang</span>
                        <span class="info-value" style="color:#0A5F63;background:#F0FDF4;padding:8px 12px;border-radius:6px;display:block;">
                            {{ $pengajuan->catatan_admin }}
                        </span>
                    </div>
                    @endif

                    @if($pengajuan->verified_at)
                    <div class="info-item" style="grid-column: 1 / -1; background:#FBFBFB;">
                        <span class="info-label">Audit Verifikasi Admin</span>
                        <span class="info-value" style="font-size:11px;color:#6B7280;">
                            Diputuskan oleh <strong>{{ $pengajuan->verifiedBy->nama_lengkap ?? 'Administrator' }}</strong> pada {{ \Carbon\Carbon::parse($pengajuan->verified_at)->translatedFormat('d F Y, H:i') }} WIB
                        </span>
                    </div>
                    @endif

                </div>

            </div>

            <!-- ================= DOKUMEN PERSYARATAN MAGANG ================= -->
            <div class="section">

                <div class="section-title">
                    <i class="fa-regular fa-folder-open"></i>
                    <span>Dokumen Persyaratan Magang</span>
                </div>

                <div class="document-grid">

                    <!-- Surat Pengantar Kampus -->
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

                    <!-- Proposal Magang -->
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
                                Lihat Proposal
                            </a>
                            @else
                            <p class="document-empty">Belum ada dokumen</p>
                            @endif
                        </div>
                    </div>

                    <!-- CV / Curriculum Vitae -->
                    <div class="document-card">
                        <div class="document-icon">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>

                        <div class="document-info">
                            <h4>Curriculum Vitae (CV)</h4>

                            @if($pengajuan->cv)
                            <a href="{{ asset('uploads/cv/' . $pengajuan->cv) }}"
                                target="_blank"
                                class="document-link">
                                <i class="fa-solid fa-eye"></i>
                                Lihat Berkas CV
                            </a>
                            @else
                            <p class="document-empty">Belum ada dokumen</p>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

            <!-- ================= BUTTON AREA ================= -->
            <div class="button-area">

                <a href="{{ route('admin.data') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Data
                </a>

                @if($pengajuan->status === 'Menunggu Verifikasi')
                <button type="button" class="btn-action-tolak" onclick="openModalTolak()">
                    <i class="fa-solid fa-user-xmark"></i>
                    Tolak Pendaftaran
                </button>

                <button type="button" class="btn-action-terima" onclick="openModalTerima()">
                    <i class="fa-solid fa-user-check"></i>
                    Terima & Plot Pembimbing
                </button>
                @else
                <button type="button" class="btn-action-plot" style="height:40px;padding:0 16px;font-size:12px;" onclick="openModalTerima()">
                    <i class="fa-solid fa-user-tie"></i>
                    Ploting Pembimbing Lapangan
                </button>

                <a href="{{ route('admin.edit', $pengajuan->id_pengajuan) }}" class="btn-edit">
                    <i class="fa-solid fa-pen"></i>
                    Edit Data Lengkap
                </a>
                @endif

            </div>

        </section>

    </main>

    <!-- ================= MODAL TERIMA & PLOTING PEMBIMBING ================= -->
    <div class="modal-overlay" id="modalTerimaOverlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3>
                    <i class="fa-solid fa-circle-check" style="color:#059669;"></i>
                    Terima Pendaftaran & Ploting Pembimbing Lapangan
                </h3>
                <button type="button" class="modal-close" onclick="closeModalTerima()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.pengajuan.terima', $pengajuan->id_pengajuan) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Snapshot Calon Peserta -->
                    <div class="modal-candidate-box">
                        <div class="modal-avatar">
                            {{ strtoupper(substr($pengajuan->mahasiswa->nama_lengkap ?? 'M', 0, 1)) }}
                        </div>
                        <div>
                            <strong style="color:#111827;font-size:14px;display:block;">
                                {{ $pengajuan->mahasiswa->nama_lengkap ?? 'Peserta' }}
                            </strong>
                            <small style="color:#4B5563;">
                                NIM: {{ $pengajuan->mahasiswa->nim ?? '-' }} &bull; {{ $pengajuan->mahasiswa->universitas->nama_universitas ?? '-' }}
                            </small>
                        </div>
                    </div>

                    <!-- 1. Dropdown Pembimbing Lapangan -->
                    <div class="modal-form-group">
                        <label for="pembimbing_id">
                            Pilih Pembimbing Lapangan <span class="req">*</span>
                        </label>
                        <select name="pembimbing_id" id="pembimbing_id" required>
                            <option value="">-- Pilih Pembimbing Lapangan --</option>
                            @forelse($pembimbings as $pmb)
                            <option value="{{ $pmb->id_users }}"
                                {{ (old('pembimbing_id', $pengajuan->pembimbing_id) == $pmb->id_users) ? 'selected' : '' }}>
                                {{ $pmb->nama_lengkap }} ({{ $pmb->email }})
                            </option>
                            @empty
                            <option value="" disabled>Belum ada data akun Pembimbing Lapangan</option>
                            @endforelse
                        </select>
                        <small style="color:#6B7280;font-size:11px;margin-top:4px;display:block;">
                            Pembimbing lapangan yang dipilih akan menerima notifikasi otomatis dan berwenang memonitor presensi, logbook, & evaluasi peserta ini.
                        </small>
                    </div>

                    <!-- 2. Divisi Penempatan -->
                    <div class="modal-form-group">
                        <label for="divisi_id">
                            Divisi Penempatan Magang <span class="req">*</span>
                        </label>
                        <select name="divisi_id" id="divisi_id" required>
                            @foreach($divisis as $div)
                            <option value="{{ $div->id_divisi }}"
                                {{ (old('divisi_id', $pengajuan->divisi_id) == $div->id_divisi) ? 'selected' : '' }}>
                                {{ $div->nama_divisi }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. Periode Magang -->
                    <div class="modal-grid-2">
                        <div class="modal-form-group">
                            <label for="tanggal_mulai">Tanggal Mulai Magang <span class="req">*</span></label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                value="{{ old('tanggal_mulai', $pengajuan->tanggal_mulai) }}" required>
                        </div>

                        <div class="modal-form-group">
                            <label for="tanggal_selesai">Tanggal Selesai Magang <span class="req">*</span></label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                value="{{ old('tanggal_selesai', $pengajuan->tanggal_selesai) }}" required>
                        </div>
                    </div>

                    <!-- 4. Catatan / Arahan Magang -->
                    <div class="modal-form-group">
                        <label for="catatan_admin">
                            Catatan / Arahan untuk Mahasiswa (Opsional)
                        </label>
                        <textarea name="catatan_admin" id="catatan_admin"
                            placeholder="Contoh: Harap hadir hari pertama pukul 07.30 WIB dengan kemeja putih dan membawa berkas hardcopy asli...">{{ old('catatan_admin', $pengajuan->catatan_admin) }}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModalTerima()">Batal</button>
                    <button type="submit" class="btn-action-terima">
                        <i class="fa-solid fa-check"></i>
                        Konfirmasi Terima & Plot Pembimbing
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL TOLAK PENDAFTARAN ================= -->
    <div class="modal-overlay" id="modalTolakOverlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3>
                    <i class="fa-solid fa-circle-xmark" style="color:#DC2626;"></i>
                    Tolak Pendaftaran Calon Peserta Magang
                </h3>
                <button type="button" class="modal-close" onclick="closeModalTolak()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.pengajuan.tolak', $pengajuan->id_pengajuan) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Snapshot Calon Peserta -->
                    <div class="modal-candidate-box danger-box">
                        <div class="modal-avatar" style="background:#DC2626;">
                            {{ strtoupper(substr($pengajuan->mahasiswa->nama_lengkap ?? 'M', 0, 1)) }}
                        </div>
                        <div>
                            <strong style="color:#111827;font-size:14px;display:block;">
                                {{ $pengajuan->mahasiswa->nama_lengkap ?? 'Peserta' }}
                            </strong>
                            <small style="color:#4B5563;">
                                Divisi yang dituju: {{ $pengajuan->divisi->nama_divisi ?? '-' }}
                            </small>
                        </div>
                    </div>

                    <!-- Input Alasan Penolakan -->
                    <div class="modal-form-group">
                        <label for="alasan_penolakan">
                            Alasan Penolakan Pendaftaran <span class="req">*</span>
                        </label>
                        <textarea name="alasan_penolakan" id="alasan_penolakan" required
                            placeholder="Tuliskan alasan penolakan secara jelas agar dapat diinformasikan kepada peserta...">{{ old('alasan_penolakan', $pengajuan->alasan_penolakan) }}</textarea>

                        <!-- Preset Quick Reasons -->
                        <div style="margin-top: 8px;">
                            <small style="color:#6B7280;font-size:11px;display:block;margin-bottom:4px;">Klik untuk mengisi alasan cepat:</small>
                            <div class="preset-tags">
                                <span class="preset-tag" onclick="fillReason('Kuota penerimaan magang pada divisi yang dituju saat ini sudah penuh.')">
                                    Kuota Divisi Penuh
                                </span>
                                <span class="preset-tag" onclick="fillReason('Berkas dokumen persyaratan (Surat Pengantar / Proposal / CV) tidak lengkap atau tidak valid.')">
                                    Berkas Tidak Lengkap
                                </span>
                                <span class="preset-tag" onclick="fillReason('Periode pelaksanaan magang yang diajukan bertabrakan dengan jadwal operasional & pemeliharaan PLTU.')">
                                    Jadwal Tidak Sesuai
                                </span>
                                <span class="preset-tag" onclick="fillReason('Latar belakang program studi belum sesuai dengan kompetensi teknis yang dibutuhkan pada divisi ini.')">
                                    Kompetensi Belum Sesuai
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModalTolak()">Batal</button>
                    <button type="submit" class="btn-action-tolak">
                        <i class="fa-solid fa-ban"></i>
                        Konfirmasi Tolak Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>

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

            // Close modals on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeModalTerima();
                    closeModalTolak();
                }
            });
        });

        function openModalTerima() {
            document.getElementById('modalTerimaOverlay').classList.add('active');
        }

        function closeModalTerima() {
            document.getElementById('modalTerimaOverlay').classList.remove('active');
        }

        function openModalTolak() {
            document.getElementById('modalTolakOverlay').classList.add('active');
        }

        function closeModalTolak() {
            document.getElementById('modalTolakOverlay').classList.remove('active');
        }

        function fillReason(text) {
            const textarea = document.getElementById('alasan_penolakan');
            if (textarea) {
                textarea.value = text;
                textarea.focus();
            }
        }
    </script>

</body>

</html>