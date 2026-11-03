@extends('pembimbing.layouts.app')

@section('title', 'Detail Mahasiswa - ' . ($mahasiswa->nama_lengkap ?? ''))
@section('page_title', 'Detail & Rekam Jejak Mahasiswa')
@section('page_subtitle', 'Pantau profil, presensi, logbook harian, dan berkas evaluasi.')

@push('styles')
<style>
    /* ================= TOMBOL KEMBALI & CARD UTAMA ================= */
    .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--muted); font-weight: 600; font-size: 13.5px; text-decoration: none; margin-bottom: 20px; transition: 0.2s; }
    .btn-back:hover { color: var(--primary-dark); }

    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; padding: 24px; }
    
    .profile-header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px; }
    .profile-info { display: flex; align-items: center; gap: 20px; }
    .profile-avatar { width: 72px; height: 72px; border-radius: 50%; background: var(--primary-soft); color: var(--primary-dark); display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 800; flex-shrink: 0; }
    .profile-name { font-size: 20px; font-weight: 700; color: var(--text); margin: 0 0 4px 0; }
    .profile-sub { color: var(--muted); font-size: 13px; margin: 0 0 8px 0; }
    .profile-badges { display: flex; gap: 8px; flex-wrap: wrap; }
    .badge-label { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; border: 1px solid var(--border); background: var(--page); color: var(--text); }
    .badge-primary { background: #E0F2FE; color: #0284C7; border-color: #BAE6FD; }
    
    .score-box { text-align: right; }
    .score-box small { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px; }
    .score-box .score-val { font-size: 34px; font-weight: 800; color: var(--primary); line-height: 1.1; margin-bottom: 8px; }
    .score-box .score-val span { font-size: 15px; color: var(--muted); font-weight: 600; }

    .btn-sm { padding: 8px 14px; font-size: 12.5px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s; white-space: nowrap; }
    .btn-primary { background: var(--primary); color: #fff; border: 1px solid var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); }
    .btn-outline { background: #F8FAFC; color: #475569; border: 1px solid var(--border); }
    .btn-outline:hover { background: #E2E8F0; color: var(--text); }

    /* ================= STATS GRID ================= */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card { background: var(--bg); padding: 20px 24px; border-radius: 16px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 16px; }
    .stat-info { flex: 1; text-align: left; }
    .stat-info span { font-size: 12.5px; font-weight: 600; color: var(--muted); display: block; margin-bottom: 4px; }
    .stat-info h2 { font-size: 28px; font-weight: 800; color: var(--text); margin: 0; line-height: 1; }
    .stat-icon { width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
    
    .icon-teal { background: var(--primary-soft); color: var(--primary); }
    .icon-green { background: #ECFDF5; color: #059669; }
    .icon-yellow { background: #FFFBEB; color: #D97706; }
    .icon-red { background: #FEF2F2; color: #DC2626; }

    /* ================= LAYOUT BAWAH (GRID) ================= */
    .content-grid { display: grid; grid-template-columns: 340px minmax(0, 1fr); gap: 24px; align-items: start; }
    .side-card { background: var(--bg); border-radius: 16px; border: 1px solid var(--border); padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); overflow: hidden; }
    .side-title { font-size: 14.5px; font-weight: 700; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid var(--page); padding-bottom: 12px; }
    
    /* Info List Kontak */
    .info-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; padding: 10px 0; border-bottom: 1px dashed var(--page); font-size: 13px; }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .info-label { color: var(--muted); font-weight: 600; flex-shrink: 0; }
    .info-val { color: var(--text); font-weight: 500; text-align: right; word-break: break-word; }

    /* Tombol Berkas */
    .file-btn { display: flex; align-items: center; gap: 10px; background: var(--page); border: 1px solid var(--border); padding: 12px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--text); text-decoration: none; margin-bottom: 10px; transition: 0.2s; }
    .file-btn:last-child { margin-bottom: 0; }
    .file-btn:hover { border-color: var(--primary); background: var(--primary-soft); }
    .file-btn i { font-size: 18px; flex-shrink: 0; }

    /* ================= TABS & TABEL RIWAYAT ================= */
    .tabs-nav { display: flex; gap: 6px; border-bottom: 2px solid var(--page); margin-bottom: 20px; overflow-x: auto; white-space: nowrap; scrollbar-width: none; }
    .tabs-nav::-webkit-scrollbar { display: none; }
    .tab-btn { background: none; border: none; padding: 12px 18px; font-size: 13.5px; font-weight: 600; color: var(--muted); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; border-bottom: 3px solid transparent; margin-bottom: -2px; font-family: inherit; transition: 0.2s; flex-shrink: 0; }
    .tab-btn:hover { color: var(--primary-dark); }
    .tab-btn.active { color: var(--primary-dark); border-bottom-color: var(--primary); background: var(--primary-soft); border-radius: 8px 8px 0 0; }
    
    .tab-pane { display: none; }
    .tab-pane.active { display: block; animation: fadeIn 0.25s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

    .status-badge { padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; }
    .badge-present { background: var(--success-bg); color: var(--success-text); border: 1px solid #A7F3D0; }
    .badge-late { background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; }
    .badge-leave { background: #EEF2FF; color: #4338CA; border: 1px solid #C7D2FE; }
    .badge-absent { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
    .badge-draft { background: #F1F5F9; color: #475569; border: 1px solid var(--border); }

    /* Tabel Desktop di dalam Tab */
    .tab-table-wrap { width: 100%; overflow-x: auto; }
    .tab-table { width: 100%; border-collapse: collapse; }
    .tab-table th { background: #F8FAFC; padding: 12px 14px; font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.4px; border-bottom: 2px solid var(--border); text-align: left; }
    .tab-table td { padding: 14px; font-size: 13px; color: var(--text); border-bottom: 1px solid var(--page); vertical-align: middle; text-align: left; }
    .tab-table tbody tr:hover { background: #F8FAFC; }
    .tab-table .col-center { text-align: center; }

    .tab-footer { margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--page); display: flex; justify-content: flex-end; }

    /* ================= RESPONSIVE MOBILE & TABLET ================= */
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .content-grid { grid-template-columns: 1fr; }
    }
    
    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .card, .side-card { padding: 18px; }
        
        .profile-header { flex-direction: column; align-items: flex-start; gap: 16px; }
        .profile-info { flex-direction: column; align-items: flex-start; gap: 12px; width: 100%; }
        .score-box { text-align: left; width: 100%; border-top: 1px solid var(--page); padding-top: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .score-box .score-val { margin-bottom: 0; }

        /* Pola Card Tabel Mobile Persis seperti data.blade.php */
        .tab-table, .tab-table tbody, .tab-table tr, .tab-table td { display: block; width: 100% !important; min-width: 0 !important; box-sizing: border-box; }
        .tab-table thead { display: none !important; }
        
        .tab-table tbody tr { 
            border: 1px solid var(--border); 
            border-radius: 12px; 
            margin-bottom: 14px; 
            padding: 14px 16px; 
            background: #fff; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.02); 
        }
        .tab-table tbody tr:last-child { margin-bottom: 0; }
        
        .tab-table tbody td,
        .tab-table tbody td.col-center { 
            display: flex !important; 
            justify-content: space-between !important; 
            align-items: center !important; 
            padding: 10px 0 !important; 
            border-bottom: 1px solid #F1F5F9 !important; 
            text-align: right !important; 
            gap: 12px;
        }
        .tab-table tbody td:first-child { padding-top: 2px !important; }
        .tab-table tbody td:last-child { border-bottom: none !important; padding-bottom: 2px !important; }
        
        .tab-table tbody td[data-label]::before { 
            content: attr(data-label); 
            font-weight: 600; 
            color: var(--muted); 
            text-align: left; 
            flex-shrink: 0; 
            font-size: 12.5px;
        }

        /* Khusus sel dengan rincian kegiatan atau catatan panjang */
        .tab-table tbody td.cell-stack { 
            flex-direction: column !important; 
            align-items: flex-start !important; 
            text-align: left !important; 
            gap: 6px;
        }
        .tab-table tbody td.cell-stack .stack-body {
            width: 100%;
            background: var(--page);
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 12.5px;
            line-height: 1.5;
            text-align: left;
        }

        .tab-footer .btn-sm { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

    <a href="{{ route('pembimbing.mahasiswa') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Bimbingan
    </a>

    <!-- PROFILE CARD -->
    <div class="card animate-fade-up delay-1">
        <div class="profile-header">
            <div class="profile-info">
                <div class="profile-avatar">
                    {{ strtoupper(substr($mahasiswa->nama_lengkap ?? 'M', 0, 1)) }}
                </div>
                <div>
                    <h1 class="profile-name">{{ $mahasiswa->nama_lengkap ?? 'Nama Tidak Tersedia' }}</h1>
                    <p class="profile-sub">
                        NIM: <strong>{{ $mahasiswa->nim ?? '-' }}</strong> • {{ $mahasiswa->universitas->nama_universitas ?? '-' }} ({{ $mahasiswa->jurusan->nama_jurusan ?? '-' }})
                    </p>
                    <div class="profile-badges">
                        <span class="badge-label badge-primary">
                            <i class="fa-solid fa-building"></i> Divisi: {{ $mahasiswa->latestPengajuan->divisi->nama_divisi ?? '-' }}
                        </span>
                        <span class="badge-label">
                            <i class="fa-solid fa-user-check"></i> Dibimbing oleh: {{ auth()->user()->nama_lengkap ?? 'Pembimbing' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="score-box">
                <div>
                    <small>Rata-rata Skor Evaluasi</small>
                    <div class="score-val">
                        {{ $avgScore ? number_format($avgScore, 1) : '-' }} <span>/ 100</span>
                    </div>
                </div>
                <a href="{{ route('pembimbing.nilai', ['mahasiswa_id' => $mahasiswa->id_mhs ?? 0]) }}" class="btn-sm btn-primary">
                    <i class="fa-regular fa-star"></i> Beri Penilaian Baru
                </a>
            </div>
        </div>
    </div>

    <!-- STATS GRID -->
    <div class="stats-grid animate-fade-up delay-2">
        <div class="stat-card">
            <div class="stat-info">
                <span>Total Hari Tercatat</span>
                <h2>{{ str_pad($totalPresensi ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-teal"><i class="fa-solid fa-calendar-day"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span>Hadir Tepat Waktu</span>
                <h2>{{ str_pad($totalHadir ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-green"><i class="fa-solid fa-user-check"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span>Terlambat Masuk</span>
                <h2>{{ str_pad($totalTerlambat ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-yellow"><i class="fa-solid fa-clock-rotate-left"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span>Izin / Cuti Sakit</span>
                <h2>{{ str_pad($totalIzin ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-red"><i class="fa-solid fa-notes-medical"></i></div>
        </div>
    </div>

    <!-- CONTENT GRID (INFO KONTAK & RIWAYAT TABS) -->
    <div class="content-grid animate-fade-up delay-3">
        
        <!-- KOLOM KIRI: Info Kontak & Berkas -->
        <div>
            <div class="side-card">
                <div class="side-title">
                    <i class="fa-regular fa-address-card" style="color: var(--primary);"></i> Informasi Kontak Pribadi
                </div>
                <div>
                    <div class="info-row">
                        <span class="info-label">Email Aktif</span>
                        <span class="info-val">{{ $mahasiswa->email ?: '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">No. WhatsApp / HP</span>
                        <span class="info-val">{{ $mahasiswa->no_telepon ?: '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jenis Kelamin</span>
                        <span class="info-val">{{ $mahasiswa->jenis_kelamin ?: '-' }}</span>
                    </div>

                    <div style="border-top: 1px solid var(--page); margin: 14px 0 10px;"></div>
                    <div style="color: #DC2626; font-weight: 700; font-size: 12.5px; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-triangle-exclamation"></i> Kontak Darurat
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nama Wali</span>
                        <span class="info-val">{{ $mahasiswa->emergency_contact_name ?: '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Telepon Darurat</span>
                        <span class="info-val">{{ $mahasiswa->emergency_contact_phone ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="side-card">
                <div class="side-title">
                    <i class="fa-regular fa-folder-open" style="color: var(--primary);"></i> Berkas Persyaratan
                </div>
                @php $pengajuan = $mahasiswa->latestPengajuan ?? null; @endphp
                <div>
                    @if($pengajuan && $pengajuan->surat_permohonan)
                        <a href="{{ asset('uploads/surat_permohonan/' . $pengajuan->surat_permohonan) }}" target="_blank" class="file-btn">
                            <i class="fa-regular fa-file-pdf" style="color: #DC2626;"></i>
                            <span>Surat Pengantar Kampus</span>
                        </a>
                    @endif
                    @if($pengajuan && $pengajuan->proposal)
                        <a href="{{ asset('uploads/proposal/' . $pengajuan->proposal) }}" target="_blank" class="file-btn">
                            <i class="fa-regular fa-file-lines" style="color: #2563EB;"></i>
                            <span>Proposal Magang</span>
                        </a>
                    @endif
                    @if($pengajuan && $pengajuan->cv)
                        <a href="{{ asset('uploads/cv/' . $pengajuan->cv) }}" target="_blank" class="file-btn">
                            <i class="fa-regular fa-file-user" style="color: #059669;"></i>
                            <span>Curriculum Vitae (CV)</span>
                        </a>
                    @endif
                    @if($mahasiswa->file_laporan ?? false)
                        <a href="{{ asset('uploads/laporan/' . $mahasiswa->file_laporan) }}" target="_blank" class="file-btn">
                            <i class="fa-regular fa-file-zipper" style="color: #7C3AED;"></i>
                            <span>Laporan Akhir PKL</span>
                        </a>
                    @endif

                    @if(!($pengajuan && ($pengajuan->surat_permohonan || $pengajuan->proposal || $pengajuan->cv)) && !($mahasiswa->file_laporan ?? false))
                        <p style="font-size: 13px; color: var(--muted); text-align: center; margin: 12px 0;">Belum ada berkas yang diunggah.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: TABS RIWAYAT -->
        <div class="side-card">
            <div class="tabs-nav">
                <button type="button" class="tab-btn active" onclick="switchTab(event, 'tab-presensi')">
                    <i class="fa-solid fa-location-dot"></i> Presensi Terbaru
                </button>
                <button type="button" class="tab-btn" onclick="switchTab(event, 'tab-logbook')">
                    <i class="fa-solid fa-book-open"></i> Riwayat Logbook
                </button>
                <button type="button" class="tab-btn" onclick="switchTab(event, 'tab-nilai')">
                    <i class="fa-solid fa-star-half-stroke"></i> Arsip Penilaian
                </button>
            </div>

            <!-- TAB 1: PRESENSI TERBARU -->
            <div id="tab-presensi" class="tab-pane active">
                <div class="tab-table-wrap">
                    <table class="tab-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th class="col-center">Status Kehadiran</th>
                                <th>Radius Geofence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAttendances ?? [] as $att)
                                <tr>
                                    <td data-label="Tanggal">
                                        <strong>{{ \Carbon\Carbon::parse($att->attendance_date)->format('d/m/Y') }}</strong>
                                    </td>
                                    <td data-label="Jam Masuk">
                                        <span style="font-weight: 600; color: #059669;">
                                            {{ $att->check_in_at ? \Carbon\Carbon::parse($att->check_in_at)->format('H:i:s') : '-' }}
                                        </span>
                                    </td>
                                    <td data-label="Jam Keluar">
                                        <span style="font-weight: 600; color: #2563EB;">
                                            {{ $att->check_out_at ? \Carbon\Carbon::parse($att->check_out_at)->format('H:i:s') : '-' }}
                                        </span>
                                    </td>
                                    <td data-label="Status Kehadiran" class="col-center">
                                        @if($att->check_in_status === 'present')
                                            <span class="status-badge badge-present"><i class="fa-solid fa-check"></i> Hadir Tepat Waktu</span>
                                        @elseif($att->check_in_status === 'late')
                                            <span class="status-badge badge-late"><i class="fa-solid fa-clock"></i> Terlambat ({{ $att->late_minutes ?? 0 }}m)</span>
                                        @elseif(in_array($att->check_in_status, ['leave', 'sick']))
                                            <span class="status-badge badge-leave">{{ ucfirst($att->check_in_status) }}</span>
                                        @else
                                            <span class="status-badge badge-absent">{{ ucfirst($att->check_in_status ?? 'Alpha') }}</span>
                                        @endif
                                    </td>
                                    <td data-label="Radius Geofence">
                                        <span style="color: var(--muted);">
                                            {{ $att->distance_in_meters ? round($att->distance_in_meters, 1) . ' meter' : '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center !important; justify-content: center !important; padding: 30px !important; color: var(--muted);">
                                        Belum ada data presensi yang tercatat untuk mahasiswa ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="tab-footer">
                    <a href="{{ route('pembimbing.absensi', ['mahasiswa_id' => $mahasiswa->id_mhs ?? 0]) }}" class="btn-sm btn-outline">
                        Buka Monitoring Presensi Lengkap <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- TAB 2: RIWAYAT LOGBOOK -->
            <div id="tab-logbook" class="tab-pane">
                <div class="tab-table-wrap">
                    <table class="tab-table">
                        <thead>
                            <tr>
                                <th style="width: 120px;">Tanggal</th>
                                <th>Judul & Ringkasan Kegiatan</th>
                                <th class="col-center" style="width: 140px;">Status Validasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogbooks ?? [] as $log)
                                <tr>
                                    <td data-label="Tanggal">
                                        <strong>{{ \Carbon\Carbon::parse($log->logbook_date)->format('d/m/Y') }}</strong>
                                    </td>
                                    <td class="cell-stack" data-label="Judul & Kegiatan">
                                        <div class="stack-body">
                                            <strong style="display: block; color: var(--text); margin-bottom: 2px;">{{ $log->title ?? '-' }}</strong>
                                            <span style="color: var(--muted);">{{ Str::limit($log->activity ?? '-', 90) }}</span>
                                        </div>
                                    </td>
                                    <td data-label="Status Validasi" class="col-center">
                                        @if($log->status === 'approved')
                                            <span class="status-badge badge-present"><i class="fa-solid fa-check"></i> Disetujui</span>
                                        @elseif($log->status === 'submitted')
                                            <span class="status-badge badge-late"><i class="fa-solid fa-hourglass-half"></i> Menunggu Review</span>
                                        @elseif($log->status === 'revision_required')
                                            <span class="status-badge badge-absent"><i class="fa-solid fa-rotate"></i> Perlu Revisi</span>
                                        @else
                                            <span class="status-badge badge-draft">{{ ucfirst($log->status ?? 'Draft') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center !important; justify-content: center !important; padding: 30px !important; color: var(--muted);">
                                        Belum ada logbook harian yang diisi oleh mahasiswa ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="tab-footer">
                    <a href="{{ route('pembimbing.logbook', ['mahasiswa_id' => $mahasiswa->id_mhs ?? 0]) }}" class="btn-sm btn-outline">
                        Buka Monitoring Logbook Lengkap <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- TAB 3: ARSIP PENILAIAN -->
            <div id="tab-nilai" class="tab-pane">
                <div class="tab-table-wrap">
                    <table class="tab-table">
                        <thead>
                            <tr>
                                <th>Tanggal Penilaian</th>
                                <th>Penilai</th>
                                <th class="col-center">Skor Akhir</th>
                                <th class="col-center">Status Publikasi</th>
                                <th>Catatan Peserta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evaluations ?? [] as $ev)
                                <tr>
                                    <td data-label="Tanggal Penilaian">
                                        <strong>{{ \Carbon\Carbon::parse($ev->evaluation_date)->format('d/m/Y') }}</strong>
                                    </td>
                                    <td data-label="Penilai">
                                        <span>{{ $ev->evaluator->nama_lengkap ?? 'Pembimbing' }}</span>
                                    </td>
                                    <td data-label="Skor Akhir" class="col-center">
                                        <span>
                                            <strong style="color: var(--primary-dark); font-size: 15px;">{{ $ev->total_score ?? 0 }}</strong>
                                            <small style="color: var(--muted);">/ 100</small>
                                        </span>
                                    </td>
                                    <td data-label="Status Publikasi" class="col-center">
                                        @if(($ev->status ?? '') === 'published')
                                            <span class="status-badge badge-present">Dipublikasikan</span>
                                        @else
                                            <span class="status-badge badge-draft">Draft</span>
                                        @endif
                                    </td>
                                    <td class="cell-stack" data-label="Catatan Peserta">
                                        <div class="stack-body" style="color: var(--muted);">
                                            {{ $ev->participant_note ?: '-' }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center !important; justify-content: center !important; padding: 30px !important; color: var(--muted);">
                                        Belum ada penilaian evaluasi yang diberikan kepada mahasiswa ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
function switchTab(evt, tabId) {
    const tabPanes = document.querySelectorAll('.tab-pane');
    tabPanes.forEach(pane => pane.classList.remove('active'));

    const tabBtns = document.querySelectorAll('.tab-btn');
    tabBtns.forEach(btn => btn.classList.remove('active'));

    document.getElementById(tabId).classList.add('active');
    evt.currentTarget.classList.add('active');
}
</script>
@endpush