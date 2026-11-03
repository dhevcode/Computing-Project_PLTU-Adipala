@extends('admin.layouts.app')

@section('title', 'Detail & Verifikasi Mahasiswa')
@section('page_title', 'Detail & Verifikasi')
@section('page_subtitle', 'Tinjau informasi calon/peserta, verifikasi, dan ploting pembimbing')

@push('styles')
<style>
    /* ================= STATUS BADGES ================= */
    .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px; border-radius: 999px; font-size: 12.5px; font-weight: 600; white-space: nowrap; }
    .status-pending { background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; }
    .status-akan { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
    .status-sedang { background: var(--success-bg); color: var(--success-text); border: 1px solid #A7F3D0; }
    .status-selesai { background: #F3F4F6; color: #4B5563; border: 1px solid #E5E7EB; }
    .status-ditolak { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }

    /* ================= ALERT BANNERS ================= */
    .alert-banner { padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-size: 13.5px; font-weight: 600; line-height: 1.5; }
    .alert-success { background: var(--success-bg); color: var(--success-text); border: 1px solid #A7F3D0; }
    .alert-warning { background: #FEF9E7; color: #7D6608; border: 1px solid #F9E79F; }
    .alert-danger { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
    .alert-banner i { font-size: 18px; flex-shrink: 0; }

    /* ================= VERIFICATION HERO CARDS ================= */
    .hero-card { border-radius: 16px; padding: 24px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; }
    .hero-pending { background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); border: 1.5px solid #F59E0B; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.12); }
    .hero-rejected { background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%); border: 1.5px solid #EF4444; }

    .hero-left { display: flex; align-items: center; gap: 16px; flex: 1; min-width: 250px; }
    .hero-icon { width: 52px; height: 52px; border-radius: 14px; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
    .hero-pending .hero-icon { background: #F59E0B; }
    .hero-rejected .hero-icon { background: #EF4444; }

    .hero-text h3 { font-size: 16px; font-weight: 700; margin-bottom: 6px; margin-top: 0; }
    .hero-pending .hero-text h3 { color: #78350F; }
    .hero-rejected .hero-text h3 { color: #991B1B; }
    .hero-text p { font-size: 13px; margin: 0; line-height: 1.4; max-width: 700px; }
    .hero-pending .hero-text p { color: #92400E; }
    .hero-rejected .hero-text p { color: #B91C1C; }
    .hero-text small { display: inline-block; margin-top: 8px; font-size: 11px; font-weight: 600; }
    .hero-rejected .hero-text small { color: #7F1D1D; }

    .hero-actions { display: flex; gap: 10px; flex-shrink: 0; flex-wrap: wrap; }

    /* Tombol Aksi */
    .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; text-decoration: none; font-family: inherit; white-space: nowrap; }
    .btn-terima { background: #0284C7; color: #fff; box-shadow: 0 4px 10px rgba(5, 138, 150, 0.25); }
    .btn-terima:hover { background: #0369A1; transform: translateY(-1px); }
    .btn-tolak { background: #DC2626; color: #fff; }
    .btn-tolak:hover { background: #B91C1C; transform: translateY(-1px); }
    .btn-plot { background: var(--primary); color: #fff; padding: 6px 14px; border-radius: 6px; font-size: 11.5px; }
    .btn-plot:hover { background: var(--primary-dark); }
    .btn-edit { background: #F1F5F9; color: #475569; border: 1.5px solid var(--border); }
    .btn-edit:hover { background: #E2E8F0; color: var(--text); }
    .btn-back { background: transparent; color: var(--muted); padding: 10px 0; }
    .btn-back:hover { color: var(--text); }

    /* ================= MAIN CONTENT SECTIONS ================= */
    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; padding: 24px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--page); }
    .page-header h1 { font-size: 20px; font-weight: 700; color: var(--text); margin: 0 0 6px 0; }

    .section { margin-bottom: 32px; }
    .section-title { font-size: 15px; font-weight: 700; color: var(--primary-dark); display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid var(--page); }

    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .info-item { display: flex; flex-direction: column; gap: 4px; }
    .info-label { font-size: 12px; font-weight: 600; color: var(--muted); }
    .info-value { font-size: 14px; font-weight: 500; color: var(--text); }

    /* ================= DOKUMEN CARDS ================= */
    .document-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .document-card { background: var(--page); border: 1.5px solid var(--border); border-radius: 12px; padding: 20px; display: flex; gap: 16px; align-items: center; transition: 0.2s; }
    .document-card:hover { border-color: var(--primary); background: var(--primary-soft); }
    .document-icon { width: 48px; height: 48px; border-radius: 10px; background: #fff; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); flex-shrink: 0; }
    .document-info h4 { font-size: 13.5px; font-weight: 700; color: var(--text); margin: 0 0 6px 0; }
    .document-link { font-size: 12px; font-weight: 600; color: #0284C7; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
    .document-link:hover { text-decoration: underline; color: #0369A1; }
    .document-empty { font-size: 12px; color: var(--muted); margin: 0; font-style: italic; }

    .button-area { display: flex; justify-content: flex-end; align-items: center; gap: 12px; padding-top: 24px; border-top: 1px solid var(--page); margin-top: 32px; flex-wrap: wrap; }
    .button-area .btn-back { margin-right: auto; padding: 10px 16px; border: 1px solid var(--border); border-radius: 8px; }

    /* ================= MODAL STYLES ================= */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 1000; display: none; align-items: center; justify-content: center; padding: 20px; opacity: 0; transition: opacity 0.2s; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .modal-card { background: #fff; border-radius: 16px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.25); transform: scale(0.95) translateY(20px); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .modal-overlay.active .modal-card { transform: scale(1) translateY(0); }

    .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #F9FAFB; border-radius: 16px 16px 0 0; }
    .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 10px; margin: 0; }
    .modal-close { background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer; transition: 0.2s; padding: 4px; border-radius: 6px; }
    .modal-close:hover { color: var(--error-text); background: #FEE2E2; }

    .modal-body { padding: 24px; }
    .modal-candidate-box { background: var(--success-bg); border: 1px solid #BBF7D0; border-radius: 10px; padding: 14px 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 14px; }
    .modal-candidate-box.danger-box { background: #FEF2F2; border-color: #FECACA; }
    .modal-avatar { width: 42px; height: 42px; border-radius: 50%; background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 18px; flex-shrink: 0; }

    .modal-form-group { margin-bottom: 20px; }
    .modal-form-group label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
    .modal-form-group label span.req { color: #DC2626; }
    .modal-form-group select, .modal-form-group input, .modal-form-group textarea { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13.5px; color: var(--text); background: #fff; transition: 0.2s; box-sizing: border-box; font-family: inherit; }
    .modal-form-group select:focus, .modal-form-group input:focus, .modal-form-group textarea:focus { outline: none; border-color: var(--primary); }
    .modal-form-group textarea { resize: vertical; min-height: 100px; }

    .modal-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .preset-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .preset-tag { background: #F1F5F9; border: 1px solid var(--border); color: #475569; font-size: 11.5px; padding: 6px 12px; border-radius: 999px; cursor: pointer; transition: 0.2s; font-weight: 500; }
    .preset-tag:hover { background: #E2E8F0; color: var(--text); border-color: #CBD5E1; }

    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); background: #F9FAFB; display: flex; justify-content: flex-end; gap: 10px; border-radius: 0 0 16px 16px; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 992px) {
        .document-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 760px) {
        .card { padding: 20px; }
        .hero-card { flex-direction: column; align-items: stretch; }
        .hero-left { min-width: 100%; }
        .hero-actions { flex-direction: column; width: 100%; }
        .btn { width: 100%; justify-content: center; }
        .info-grid { grid-template-columns: 1fr; }
        .document-grid { grid-template-columns: 1fr; }
        .button-area { flex-direction: column-reverse; }
        .button-area .btn-back { margin-right: 0; width: 100%; text-align: center; justify-content: center; }
        .modal-grid-2 { grid-template-columns: 1fr; }
        .modal-footer { flex-direction: column-reverse; }
        .modal-footer .btn-cancel, .modal-footer .btn-terima, .modal-footer .btn-tolak { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

    <!-- FLASH ALERTS -->
    @if(session('success'))
        <div class="alert-banner alert-success animate-fade-up">
            <i class="fa-solid fa-circle-check"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert-banner alert-warning animate-fade-up">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div>{{ session('warning') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-banner alert-danger animate-fade-up">
            <i class="fa-solid fa-circle-xmark"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif
    @if(isset($errors) && $errors->any())
        <div class="alert-banner alert-danger animate-fade-up">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                <strong>Terdapat kesalahan pengisian data:</strong>
                <ul style="margin: 4px 0 0 16px; padding: 0; font-size: 12px; font-weight: 500;">
                    @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="card animate-fade-up delay-1">

        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h1>Detail & Verifikasi Mahasiswa Magang</h1>
                <p style="margin:0; font-size:13px; color:var(--muted);">Informasi lengkap calon/peserta, verifikasi berkas, dan ploting pembimbing lapangan.</p>
            </div>

            @if($pengajuan->status === 'Menunggu Verifikasi')
                <span class="status-badge status-pending"><i class="fa-solid fa-hourglass-half"></i> Menunggu Verifikasi</span>
            @elseif($pengajuan->status === 'Akan Magang')
                <span class="status-badge status-akan"><i class="fa-regular fa-clock"></i> Akan Magang</span>
            @elseif($pengajuan->status === 'Sedang Magang')
                <span class="status-badge status-sedang"><i class="fa-regular fa-circle-check"></i> Sedang Magang</span>
            @elseif($pengajuan->status === 'Selesai Magang')
                <span class="status-badge status-selesai"><i class="fa-solid fa-flag-checkered"></i> Selesai Magang</span>
            @elseif($pengajuan->status === 'Ditolak')
                <span class="status-badge status-ditolak"><i class="fa-solid fa-circle-xmark"></i> Ditolak</span>
            @else
                <span class="status-badge status-selesai">{{ $pengajuan->status }}</span>
            @endif
        </div>

        <!-- ================= STATUS ACTION HERO BANNER ================= -->
        @if($pengajuan->status === 'Menunggu Verifikasi')
        <div class="hero-card hero-pending">
            <div class="hero-left">
                <div class="hero-icon"><i class="fa-solid fa-user-clock"></i></div>
                <div class="hero-text">
                    <h3>Calon Peserta Menunggu Keputusan</h3>
                    <p>Tinjau kelengkapan profil & berkas persyaratan di bawah ini, lalu pilih <strong>Terima</strong> untuk memplot Pembimbing Lapangan atau <strong>Tolak</strong> jika tidak memenuhi kualifikasi.</p>
                </div>
            </div>
            <div class="hero-actions">
                <button type="button" class="btn btn-terima" onclick="openModalTerima()">
                    <i class="fa-solid fa-user-check"></i> Terima & Plot
                </button>
                <button type="button" class="btn btn-tolak" onclick="openModalTolak()">
                    <i class="fa-solid fa-user-xmark"></i> Tolak
                </button>
            </div>
        </div>
        @elseif($pengajuan->status === 'Ditolak')
        <div class="hero-card hero-rejected">
            <div class="hero-left">
                <div class="hero-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                <div class="hero-text">
                    <h3>Pendaftaran Telah Ditolak</h3>
                    <p><strong>Alasan Penolakan:</strong> {{ $pengajuan->alasan_penolakan ?? 'Tidak ada alasan khusus yang dicantumkan.' }}</p>
                    @if($pengajuan->verified_at)
                        <small><i class="fa-regular fa-clock"></i> Diverifikasi pada: {{ \Carbon\Carbon::parse($pengajuan->verified_at)->translatedFormat('d F Y, H:i') }} WIB @if($pengajuan->verifiedBy) oleh <strong>{{ $pengajuan->verifiedBy->nama_lengkap }}</strong> @endif</small>
                    @endif
                </div>
            </div>
            <div class="hero-actions">
                <button type="button" class="btn btn-terima" onclick="openModalTerima()">
                    <i class="fa-solid fa-rotate-left"></i> Tinjau Ulang & Terima
                </button>
            </div>
        </div>
        @endif

        <!-- ================= INFORMASI MAHASISWA ================= -->
        <div class="section">
            <div class="section-title">
                <i class="fa-regular fa-user"></i> Informasi Pribadi
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nama Lengkap</span>
                    <span class="info-value"><strong>{{ $pengajuan->mahasiswa->nama_lengkap ?? '-' }}</strong></span>
                </div>
                <div class="info-item">
                    <span class="info-label">NIM / NIS</span>
                    <span class="info-value">{{ $pengajuan->mahasiswa->nim ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jenis Kelamin</span>
                    <span class="info-value">{{ $pengajuan->mahasiswa->jenis_kelamin ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Universitas / Kampus Asal</span>
                    <span class="info-value">{{ $pengajuan->mahasiswa->universitas->nama_universitas ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jurusan / Program Studi</span>
                    <span class="info-value">{{ $pengajuan->mahasiswa->jurusan->nama_jurusan ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value"><a href="mailto:{{ $pengajuan->mahasiswa->email }}" style="color:#0284C7;text-decoration:none;">{{ $pengajuan->mahasiswa->email ?? '-' }}</a></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nomor Telepon / WhatsApp</span>
                    <span class="info-value">{{ $pengajuan->mahasiswa->no_telepon ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kontak Darurat</span>
                    <span class="info-value">{{ $pengajuan->mahasiswa->emergency_contact_name ?? '-' }} @if($pengajuan->mahasiswa->emergency_contact_phone) ({{ $pengajuan->mahasiswa->emergency_contact_phone }}) @endif</span>
                </div>
            </div>
        </div>

        <!-- ================= INFORMASI MAGANG ================= -->
        <div class="section">
            <div class="section-title">
                <i class="fa-solid fa-briefcase"></i> Informasi Magang & Pembimbing
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Divisi Penempatan</span>
                    <span class="info-value"><strong style="color: var(--primary);">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</strong></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Pembimbing Lapangan</span>
                    <span class="info-value">
                        @if($pengajuan->pembimbing)
                            <span style="display:inline-flex;align-items:center;gap:6px;font-weight:600;"><i class="fa-solid fa-user-tie" style="color: var(--primary);"></i> {{ $pengajuan->pembimbing->nama_lengkap }}</span>
                            <button type="button" class="btn-plot" onclick="openModalTerima()" title="Ganti Pembimbing"><i class="fa-solid fa-arrows-rotate"></i> Ganti</button>
                        @elseif($pengajuan->nama_pembimbing)
                            <span>{{ $pengajuan->nama_pembimbing }}</span>
                            <button type="button" class="btn-plot" onclick="openModalTerima()" title="Ploting ke Akun"><i class="fa-solid fa-link"></i> Plot Akun</button>
                        @else
                            <span style="color:var(--error-text);font-weight:600;font-size:13px;"><i class="fa-solid fa-triangle-exclamation"></i> Belum Diplot</span>
                            <button type="button" class="btn-plot" onclick="openModalTerima()" style="margin-left: 6px;"><i class="fa-solid fa-user-plus"></i> Plot Sekarang</button>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Mulai Magang</span>
                    <span class="info-value">{{ $pengajuan->tanggal_mulai ? \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Selesai Magang</span>
                    <span class="info-value">{{ $pengajuan->tanggal_selesai ? \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') : '-' }}</span>
                </div>

                @if($pengajuan->catatan_admin)
                <div class="info-item" style="grid-column: 1 / -1;">
                    <span class="info-label">Catatan Admin / Arahan Magang</span>
                    <span class="info-value" style="color: var(--primary-dark); background: var(--primary-soft); padding: 12px 16px; border-radius: 8px; display: block; border: 1px solid #BBF7D0;">{{ $pengajuan->catatan_admin }}</span>
                </div>
                @endif

                @if($pengajuan->verified_at)
                <div class="info-item" style="grid-column: 1 / -1; background: var(--page); padding: 12px; border-radius: 8px;">
                    <span class="info-label" style="font-size: 11px; margin-bottom: 2px;">Jejak Audit Sistem</span>
                    <span class="info-value" style="font-size:12px; color:var(--muted);">
                        Diverifikasi oleh <strong>{{ $pengajuan->verifiedBy->nama_lengkap ?? 'Administrator' }}</strong> pada {{ \Carbon\Carbon::parse($pengajuan->verified_at)->translatedFormat('d F Y, H:i') }} WIB
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- ================= DOKUMEN PERSYARATAN ================= -->
        <div class="section" style="margin-bottom: 0;">
            <div class="section-title">
                <i class="fa-regular fa-folder-open"></i> Dokumen Persyaratan
            </div>
            <div class="document-grid">
                <!-- Surat -->
                <div class="document-card">
                    <div class="document-icon"><i class="fa-solid fa-file-signature"></i></div>
                    <div class="document-info">
                        <h4>Surat Pengantar Kampus</h4>
                        @if($pengajuan->surat_permohonan)
                            <a href="{{ asset('uploads/surat_permohonan/' . $pengajuan->surat_permohonan) }}" target="_blank" class="document-link"><i class="fa-solid fa-eye"></i> Lihat Dokumen</a>
                        @else
                            <p class="document-empty">Belum dilampirkan</p>
                        @endif
                    </div>
                </div>
                <!-- Proposal -->
                <div class="document-card">
                    <div class="document-icon"><i class="fa-solid fa-file-lines"></i></div>
                    <div class="document-info">
                        <h4>Proposal Magang</h4>
                        @if($pengajuan->proposal)
                            <a href="{{ asset('uploads/proposal/' . $pengajuan->proposal) }}" target="_blank" class="document-link"><i class="fa-solid fa-eye"></i> Lihat Proposal</a>
                        @else
                            <p class="document-empty">Belum dilampirkan</p>
                        @endif
                    </div>
                </div>
                <!-- CV -->
                <div class="document-card">
                    <div class="document-icon"><i class="fa-regular fa-address-card"></i></div>
                    <div class="document-info">
                        <h4>Curriculum Vitae (CV)</h4>
                        @if($pengajuan->cv)
                            <a href="{{ asset('uploads/cv/' . $pengajuan->cv) }}" target="_blank" class="document-link"><i class="fa-solid fa-eye"></i> Lihat Berkas CV</a>
                        @else
                            <p class="document-empty">Belum dilampirkan</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= BUTTON AREA ================= -->
        <div class="button-area">
            <a href="{{ route('admin.data') }}" class="btn btn-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>

            @if($pengajuan->status === 'Menunggu Verifikasi')
                <button type="button" class="btn btn-tolak" onclick="openModalTolak()">
                    <i class="fa-solid fa-user-xmark"></i> Tolak Pendaftaran
                </button>
                <button type="button" class="btn btn-terima" onclick="openModalTerima()">
                    <i class="fa-solid fa-user-check"></i> Terima & Plot
                </button>
            @else
                <button type="button" class="btn btn-terima" onclick="openModalTerima()">
                    <i class="fa-solid fa-user-tie"></i> Plot Pembimbing
                </button>
                <a href="{{ route('admin.edit', $pengajuan->id_pengajuan) }}" class="btn btn-edit">
                    <i class="fa-solid fa-pen"></i> Edit Data
                </a>
            @endif
        </div>
    </div>

@endsection

<!-- ================= MODAL TERIMA & PLOTING ================= -->
<div class="modal-overlay" id="modalTerimaOverlay">
    <div class="modal-card">
        <div class="modal-header">
            <h3><i class="fa-solid fa-circle-check" style="color:var(--success-text);"></i> Terima Pendaftaran & Ploting</h3>
            <button type="button" class="modal-close" onclick="closeModalTerima()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.pengajuan.terima', $pengajuan->id_pengajuan) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-candidate-box">
                    <div class="modal-avatar">{{ strtoupper(substr($pengajuan->mahasiswa->nama_lengkap ?? 'M', 0, 1)) }}</div>
                    <div>
                        <strong style="color:var(--text);font-size:14px;display:block;">{{ $pengajuan->mahasiswa->nama_lengkap ?? 'Peserta' }}</strong>
                        <small style="color:var(--muted);font-size:12px;">NIM: {{ $pengajuan->mahasiswa->nim ?? '-' }} &bull; {{ $pengajuan->mahasiswa->universitas->nama_universitas ?? '-' }}</small>
                    </div>
                </div>

                <div class="modal-form-group">
                    <label>Pilih Pembimbing Lapangan <span class="req">*</span></label>
                    <select name="pembimbing_id" required>
                        <option value="">-- Pilih Pembimbing Lapangan --</option>
                        @forelse($pembimbings as $pmb)
                            <option value="{{ $pmb->id_users }}" {{ (old('pembimbing_id', $pengajuan->pembimbing_id) == $pmb->id_users) ? 'selected' : '' }}>
                                {{ $pmb->nama_lengkap }} ({{ $pmb->email }})
                            </option>
                        @empty
                            <option value="" disabled>Belum ada data akun Pembimbing</option>
                        @endforelse
                    </select>
                    <small style="color:var(--muted);font-size:11px;margin-top:6px;display:block;">Pembimbing akan menerima notifikasi otomatis dan berwenang memonitor presensi serta logbook peserta.</small>
                </div>

                <div class="modal-form-group">
                    <label>Divisi Penempatan Magang <span class="req">*</span></label>
                    <select name="divisi_id" required>
                        @foreach($divisis as $div)
                            <option value="{{ $div->id_divisi }}" {{ (old('divisi_id', $pengajuan->divisi_id) == $div->id_divisi) ? 'selected' : '' }}>{{ $div->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="modal-grid-2">
                    <div class="modal-form-group">
                        <label>Tanggal Mulai Magang <span class="req">*</span></label>
                        <input type="text" name="tanggal_mulai" class="flatpickr-date" value="{{ old('tanggal_mulai', $pengajuan->tanggal_mulai) }}" required>
                    </div>
                    <div class="modal-form-group">
                        <label>Tanggal Selesai Magang <span class="req">*</span></label>
                        <input type="text" name="tanggal_selesai" class="flatpickr-date" value="{{ old('tanggal_selesai', $pengajuan->tanggal_selesai) }}" required>
                    </div>
                </div>

                <div class="modal-form-group" style="margin-bottom: 0;">
                    <label>Catatan / Arahan (Opsional)</label>
                    <textarea name="catatan_admin" placeholder="Contoh: Harap hadir hari pertama pukul 07.30 WIB...">{{ old('catatan_admin', $pengajuan->catatan_admin) }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModalTerima()">Batal</button>
                <button type="submit" class="btn btn-terima"><i class="fa-solid fa-check"></i> Konfirmasi Terima</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL TOLAK PENDAFTARAN ================= -->
<div class="modal-overlay" id="modalTolakOverlay">
    <div class="modal-card">
        <div class="modal-header">
            <h3><i class="fa-solid fa-circle-xmark" style="color:var(--error-text);"></i> Tolak Pendaftaran Calon Peserta</h3>
            <button type="button" class="modal-close" onclick="closeModalTolak()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.pengajuan.tolak', $pengajuan->id_pengajuan) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-candidate-box danger-box">
                    <div class="modal-avatar" style="background:var(--error-text);">{{ strtoupper(substr($pengajuan->mahasiswa->nama_lengkap ?? 'M', 0, 1)) }}</div>
                    <div>
                        <strong style="color:var(--text);font-size:14px;display:block;">{{ $pengajuan->mahasiswa->nama_lengkap ?? 'Peserta' }}</strong>
                        <small style="color:var(--muted);font-size:12px;">Divisi tujuan: {{ $pengajuan->divisi->nama_divisi ?? '-' }}</small>
                    </div>
                </div>

                <div class="modal-form-group" style="margin-bottom: 0;">
                    <label>Alasan Penolakan <span class="req">*</span></label>
                    <textarea name="alasan_penolakan" id="alasan_penolakan" required placeholder="Tuliskan alasan penolakan secara jelas...">{{ old('alasan_penolakan', $pengajuan->alasan_penolakan) }}</textarea>

                    <div style="margin-top: 10px;">
                        <small style="color:var(--muted);font-size:11px;display:block;margin-bottom:6px;font-weight:600;">Pilih alasan cepat:</small>
                        <div class="preset-tags">
                            <span class="preset-tag" onclick="fillReason('Kuota penerimaan magang pada divisi yang dituju saat ini sudah penuh.')">Kuota Penuh</span>
                            <span class="preset-tag" onclick="fillReason('Berkas dokumen persyaratan tidak lengkap atau tidak valid.')">Berkas Tidak Lengkap</span>
                            <span class="preset-tag" onclick="fillReason('Periode magang bertabrakan dengan jadwal operasional PLTU.')">Jadwal Tidak Sesuai</span>
                            <span class="preset-tag" onclick="fillReason('Latar belakang program studi belum sesuai dengan kompetensi divisi ini.')">Kompetensi Belum Sesuai</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModalTolak()">Batal</button>
                <button type="submit" class="btn btn-tolak"><i class="fa-solid fa-ban"></i> Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Fungsi Modal Buka/Tutup
    function openModalTerima() {
        const m = document.getElementById('modalTerimaOverlay');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }
    function closeModalTerima() {
        const m = document.getElementById('modalTerimaOverlay');
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);
    }

    function openModalTolak() {
        const m = document.getElementById('modalTolakOverlay');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }
    function closeModalTolak() {
        const m = document.getElementById('modalTolakOverlay');
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);
    }

    function fillReason(text) {
        const textarea = document.getElementById('alasan_penolakan');
        if (textarea) { textarea.value = text; textarea.focus(); }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Flatpickr Init untuk input tanggal di modal
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-date", { dateFormat: "Y-m-d", locale: "id", disableMobile: "true" });
        }

        // Event Listener Overlay & Escape Key
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => {
                if(e.target === overlay) {
                    closeModalTerima();
                    closeModalTolak();
                }
            });
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeModalTerima();
                closeModalTolak();
            }
        });
    });
</script>
@endpush
