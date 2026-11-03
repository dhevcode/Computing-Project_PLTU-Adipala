@extends('pembimbing.layouts.app')

@section('title', 'Monitoring Presensi Bimbingan')
@section('page_title', 'Monitoring Presensi & Validasi')
@section('page_subtitle', 'Pantau kehadiran harian dan setujui permohonan koreksi absensi anak bimbingan Anda.')

@push('styles')
<style>
    /* ================= STATS GRID ================= */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: #fff; padding: 24px; border-radius: 16px; 
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); 
        display: flex; align-items: center; justify-content: space-between; gap: 20px; 
        transition: transform 0.2s, box-shadow 0.2s; 
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
    
    .stat-info { flex: 1; text-align: left; }
    .stat-info span { font-size: 13px; font-weight: 600; color: var(--muted); display: block; margin-bottom: 4px; }
    .stat-info h2 { font-size: 32px; font-weight: 800; color: var(--text); margin: 0; line-height: 1; }

    .stat-icon { 
        width: 60px; height: 60px; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; font-size: 26px; flex-shrink: 0; 
    }
    
    .icon-teal { background: var(--primary-soft); color: var(--primary); }
    .icon-green { background: #ECFDF5; color: #059669; }
    .icon-yellow { background: #FFFBEB; color: #D97706; }
    .icon-blue { background: #EFF6FF; color: #2563EB; }

    /* ================= CARDS & LAYOUT ================= */
    .card { background: #fff; border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); padding: 24px; overflow: hidden; }
    
    .card-header-top { 
        display: flex; justify-content: space-between; align-items: center; 
        flex-wrap: wrap; gap: 16px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--page);
    }
    .card-title-wrap { display: flex; align-items: center; gap: 16px; flex: 1; }
    .icon-circle-box { 
        background: var(--primary-soft); color: var(--primary); font-size: 20px; 
        width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; 
    }
    .card-title-wrap h3 { font-size: 16.5px; font-weight: 700; color: var(--text); margin: 0 0 4px 0; line-height: 1.2; }
    .card-title-wrap p { font-size: 12.5px; color: var(--muted); margin: 0; line-height: 1.4; }

    /* ================= FILTER GRID ================= */
    .filter-grid { 
        display: grid; grid-template-columns: 1fr 1.5fr 1fr auto; gap: 16px; 
        align-items: end; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--page); 
    }
    .form-group { margin-bottom: 0; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 8px; }
    
    .input-box, .flatpickr-date { 
        width: 100%; padding: 10px 0; font-family: inherit; font-size: 13.5px; color: var(--text); 
        background: transparent; border: none; border-bottom: 1.5px solid var(--border); 
        outline: none; transition: border-color .2s; border-radius: 0; 
    }
    .input-box:focus, .flatpickr-date:focus { border-bottom: 2px solid var(--primary); }

    /* ================= BADGES & BUTTONS ================= */
    .status-badge { padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; border: 1px solid var(--border); display: inline-flex; align-items: center; gap: 4px; }
    .badge-present { background: var(--success-bg); color: var(--success-text); border-color: #A7F3D0; }
    .badge-late { background: #FEF3C7; color: #B45309; border-color: #FDE68A; }
    .badge-leave { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }
    .badge-sick { background: #F5F3FF; color: #6D28D9; border-color: #DDD6FE; }
    .badge-absent { background: #FEF2F2; color: #991B1B; border-color: #FECACA; }

    .btn-sm { padding: 8px 14px; font-size: 12.5px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; transition: 0.2s; white-space: nowrap; height: fit-content; }
    .btn-outline { background: #F8FAFC; color: #475569; border: 1px solid var(--border); }
    .btn-outline:hover { background: #E2E8F0; color: var(--text); }
    .btn-primary { background: var(--primary); color: #fff; border: 1px solid var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
    .btn-success { background: #10B981; color: #fff; border: 1px solid #10B981; }
    .btn-danger { background: #EF4444; color: #fff; border: 1px solid #EF4444; }

    /* ================= MODAL ================= */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 20px; opacity: 0; transition: opacity 0.2s; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .custom-modal { background: #fff; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: scale(0.95) translateY(20px); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .modal-overlay.active .custom-modal { transform: scale(1) translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F9FAFB; border-radius: 16px 16px 0 0; }
    .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0; display: flex; align-items: center; gap: 8px; }
    .modal-close { background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer; padding: 4px 8px; border-radius: 6px; transition: 0.2s; }
    .modal-close:hover { background: #FEE2E2; color: var(--error-text); }
    .modal-body { padding: 24px; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #fafbfc; border-radius: 0 0 16px 16px; }

    /* ================= RESPONSIVE DESKTOP & MOBILE ================= */
    @media (max-width: 1200px) {
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .filter-grid { grid-template-columns: 1fr; border-bottom: none; padding-bottom: 0; margin-bottom: 16px; }
        
        .btn-sm { width: 100%; justify-content: center; height: 42px; margin-top: 8px; }
        
        /* Tabel Mobile - Stacking & Word-Wrap */
        table, tbody, tr, td { display: block; width: 100%; }
        thead { display: none; }
        tbody tr { border: 1px solid var(--border); border-radius: 12px; margin-bottom: 16px; padding: 16px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        tbody td { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #F1F5F9; text-align: right; word-wrap: break-word; overflow-wrap: break-word; }
        tbody td:last-child { border-bottom: none; padding-bottom: 0; }
        tbody td[data-label]::before { content: attr(data-label); font-weight: 600; color: var(--muted); text-align: left; flex-shrink: 0; margin-right: 12px; font-size: 12.5px; }

        /* Sel Khusus Mobile */
        tbody td.cell-user { display: block; text-align: left; background: var(--page); padding: 14px; border-radius: 8px; margin-bottom: 12px; border-bottom: none; }
        tbody td.cell-user::before { display: none; }

        tbody td.cell-long { flex-direction: column; text-align: left; }
        tbody td.cell-long::before { margin-bottom: 8px; }
        
        tbody td.cell-action { display: flex; flex-direction: column; gap: 8px; padding-top: 16px; margin-top: 8px; }
        tbody td.cell-action::before { display: none; }

        .modal-footer { flex-direction: column-reverse; }
        .modal-footer .btn-sm { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

    @if(session('success'))
        <div class="alert alert-success animate-fade-up">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- 1. STATS GRID -->
    <div class="stats-grid animate-fade-up delay-1">
        <div class="stat-card">
            <div class="stat-info">
                <span>Total Anak Bimbingan</span>
                <h2>{{ str_pad($totalMhs ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-teal"><i class="fa-solid fa-users"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Hadir Tepat Hari Ini</span>
                <h2>{{ str_pad($totalHadirHariIni ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-green"><i class="fa-solid fa-user-check"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Terlambat Hari Ini</span>
                <h2>{{ str_pad($totalTerlambatHariIni ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-yellow"><i class="fa-solid fa-clock-rotate-left"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Izin / Cuti Sakit Hari Ini</span>
                <h2>{{ str_pad($totalIzinHariIni ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-blue"><i class="fa-solid fa-notes-medical"></i></div>
        </div>
    </div>

    <!-- 2. PENGAJUAN KOREKSI (MENUNGGU VERIFIKASI) -->
    @if(isset($pendingCorrections) && $pendingCorrections->count() > 0)
        <div class="card animate-fade-up delay-2" style="border: 1.5px solid #F59E0B; background: #FFFBEB;">
            <div class="card-header-top" style="border-bottom: 1px solid #FDE68A; margin-bottom: 16px; padding-bottom: 16px;">
                <div class="card-title-wrap">
                    <div class="icon-circle-box" style="background: #F59E0B; color: #fff;">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <h3 style="color: #92400E;">Koreksi Absensi Menunggu Review ({{ $pendingCorrections->count() }})</h3>
                        <p style="color: #B45309;">Segera periksa dan setujui atau tolak pengajuan absensi susulan ini.</p>
                    </div>
                </div>
            </div>

            <div class="table-wrapper" style="border: none;">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 25%;">Mahasiswa & NIM</th>
                            <th style="width: 15%;">Tgl Koreksi & Tipe</th>
                            <th style="width: 15%;">Waktu Diajukan</th>
                            <th style="width: 30%;">Alasan & Bukti</th>
                            <th style="width: 15%; text-align: center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingCorrections as $corr)
                            <tr style="background: #fff;">
                                <td class="cell-user" data-label="Mahasiswa">
                                    <strong style="color: var(--text); display: block;">{{ $corr->mahasiswa->nama_lengkap ?? '-' }}</strong>
                                    <small style="color: var(--muted);">NIM: <span class="code-tag" style="background: transparent; padding: 0;">{{ $corr->mahasiswa->nim ?? '-' }}</span></small>
                                </td>
                                
                                <td data-label="Tgl & Tipe">
                                    <strong style="color: var(--text);">{{ \Carbon\Carbon::parse($corr->correction_date)->format('d M Y') }}</strong><br>
                                    <span style="background: #E0E7FF; color: #3730A3; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                        Tipe: {{ str_replace('_', ' ', ucfirst($corr->correction_type ?? '')) }}
                                    </span>
                                </td>
                                
                                <td data-label="Waktu Diajukan" style="font-size: 12.5px;">
                                    <strong style="color:var(--text);">Masuk:</strong> {{ $corr->requested_check_in_at ? \Carbon\Carbon::parse($corr->requested_check_in_at)->format('H:i') : '-' }}<br>
                                    <strong style="color:var(--text);">Pulang:</strong> {{ $corr->requested_check_out_at ? \Carbon\Carbon::parse($corr->requested_check_out_at)->format('H:i') : '-' }}
                                </td>
                                
                                <td class="cell-long" data-label="Alasan" style="font-size: 12.5px; color: var(--muted);">
                                    <div style="margin-bottom: 6px; line-height: 1.4;">{{ $corr->reason ?? '-' }}</div>
                                    @if($corr->attachment_path)
                                        <a href="{{ asset(str_starts_with($corr->attachment_path, 'uploads/') ? $corr->attachment_path : 'uploads/koreksi/' . $corr->attachment_path) }}" target="_blank" class="btn-sm btn-outline" style="padding: 4px 8px; font-size: 11px; background: #fff;">
                                            <i class="fa-solid fa-paperclip"></i> Lampiran Bukti
                                        </a>
                                    @endif
                                </td>
                                
                                <td class="cell-action" data-label="Aksi" style="text-align: center;">
                                    <div style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: center;">
                                        <button type="button" class="btn-sm btn-success" onclick="openReviewModal({{ $corr->id_correction ?? 0 }}, '{{ $corr->mahasiswa->nama_lengkap ?? '' }}', '{{ \Carbon\Carbon::parse($corr->correction_date)->format('d M Y') }}', 'approved')" style="flex:1;">
                                            <i class="fa-solid fa-check"></i> Setuju
                                        </button>
                                        <button type="button" class="btn-sm btn-danger" onclick="openReviewModal({{ $corr->id_correction ?? 0 }}, '{{ $corr->mahasiswa->nama_lengkap ?? '' }}', '{{ \Carbon\Carbon::parse($corr->correction_date)->format('d M Y') }}', 'rejected')" style="flex:1;">
                                            <i class="fa-solid fa-xmark"></i> Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- 3. FILTER & DAFTAR PRESENSI HARIAN -->
    <div class="card animate-fade-up delay-3">
        
        <!-- CARD HEADER (BERADA DI ATAS AGAR RAPI) -->
        <div class="card-header-top">
            <div class="card-title-wrap">
                <div class="icon-circle-box">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <h3>Daftar Kehadiran Harian ({{ \Carbon\Carbon::parse($date ?? date('Y-m-d'))->translatedFormat('d F Y') }})</h3>
                    <p>Memantau presensi dan geofencing anak bimbingan pada tanggal yang Anda pilih.</p>
                </div>
            </div>
        </div>

        <!-- FILTER GRID -->
        <form method="GET" action="{{ route('pembimbing.absensi') }}" class="filter-grid">
            <div class="form-group">
                <label>Pilih Tanggal</label>
                <div style="position: relative;">
                    <input type="text" name="date" class="flatpickr-date input-box" value="{{ $date ?? date('Y-m-d') }}" style="padding-right: 30px;">
                    <i class="fa-solid fa-calendar-days" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: var(--primary); pointer-events: none;"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Filter Mahasiswa</label>
                <select name="mahasiswa_id" class="d-none custom-select">
                    <option value="all">Semua Bimbingan</option>
                    @foreach($mahasiswaList ?? [] as $m)
                        <option value="{{ $m->id_mhs }}" {{ request('mahasiswa_id') == $m->id_mhs ? 'selected' : '' }}>
                            {{ $m->nama_lengkap }} ({{ $m->nim }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status Kehadiran</label>
                <select name="status" class="d-none custom-select">
                    <option value="all">Semua Status</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Hadir Tepat Waktu</option>
                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                    <option value="leave" {{ request('status') === 'leave' ? 'selected' : '' }}>Izin Cuti</option>
                    <option value="sick" {{ request('status') === 'sick' ? 'selected' : '' }}>Sakit</option>
                </select>
            </div>

            <div class="form-group" style="display: flex; gap: 8px;">
                <button type="submit" class="btn-sm btn-primary" style="flex: 1; height: 42px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['status', 'mahasiswa_id']) || ($date ?? '') !== \Carbon\Carbon::today()->toDateString())
                    <a href="{{ route('pembimbing.absensi') }}" class="btn-sm btn-outline" style="height: 42px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 22%;">Mahasiswa & NIM</th>
                        <th style="width: 12%;">Tanggal</th>
                        <th style="width: 10%;">Jam Masuk</th>
                        <th style="width: 10%;">Jam Pulang</th>
                        <th style="width: 13%;">Status Presensi</th>
                        <th style="width: 15%;">Bukti & Jarak</th>
                        <th style="width: 18%;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances ?? [] as $att)
                        <tr>
                            <td class="cell-user" data-label="Mahasiswa">
                                <strong style="color: var(--text); display: block;">{{ $att->mahasiswa->nama_lengkap ?? '-' }}</strong>
                                <small style="color: var(--muted);">NIM: <span class="code-tag" style="background: transparent; padding: 0;">{{ $att->mahasiswa->nim ?? '-' }}</span></small>
                            </td>
                            
                            <td data-label="Tanggal">
                                <strong style="color: var(--text);">{{ \Carbon\Carbon::parse($att->attendance_date)->format('d M Y') }}</strong>
                            </td>
                            
                            <td data-label="Jam Masuk">
                                @if($att->check_in_at)
                                    <span style="font-weight: 700; color: #059669;">{{ \Carbon\Carbon::parse($att->check_in_at)->format('H:i') }}</span>
                                @else
                                    <span style="color: var(--muted);">-</span>
                                @endif
                            </td>
                            
                            <td data-label="Jam Pulang">
                                @if($att->check_out_at)
                                    <span style="font-weight: 700; color: #2563EB;">{{ \Carbon\Carbon::parse($att->check_out_at)->format('H:i') }}</span>
                                @else
                                    <span style="color: var(--muted);">-</span>
                                @endif
                            </td>
                            
                            <td data-label="Status">
                                @if($att->check_in_status === 'present')
                                    <span class="status-badge badge-present"><i class="fa-solid fa-check"></i> Hadir Tepat</span>
                                @elseif($att->check_in_status === 'late')
                                    <span class="status-badge badge-late"><i class="fa-solid fa-clock"></i> Terlambat ({{ $att->late_minutes ?? 0 }}m)</span>
                                @elseif($att->check_in_status === 'sick')
                                    <span class="status-badge badge-sick"><i class="fa-solid fa-heart-pulse"></i> Sakit</span>
                                @elseif($att->check_in_status === 'leave')
                                    <span class="status-badge badge-leave"><i class="fa-solid fa-calendar-xmark"></i> Cuti / Izin</span>
                                @else
                                    <span class="status-badge badge-absent">{{ ucfirst($att->check_in_status ?? 'alpa') }}</span>
                                @endif
                            </td>
                            
                            <td data-label="Bukti/Jarak">
                                <div style="font-size: 11.5px; color: var(--muted);">
                                    @if($att->distance_in_meters)
                                        <div style="margin-bottom: 6px;"><i class="fa-solid fa-route" style="color: var(--primary);"></i> {{ round($att->distance_in_meters, 1) }}m dari area</div>
                                    @endif
                                    
                                    @php
                                        $selfieInUrl = $att->selfie_path ? asset(str_starts_with($att->selfie_path, 'uploads/') ? $att->selfie_path : 'uploads/selfie/' . $att->selfie_path) : null;
                                        $selfieOutUrl = $att->selfie_out_path ? asset(str_starts_with($att->selfie_out_path, 'uploads/') ? $att->selfie_out_path : 'uploads/selfie/' . $att->selfie_out_path) : null;
                                    @endphp
                                    
                                    @if($selfieInUrl || $selfieOutUrl)
                                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                            @if($selfieInUrl)
                                                <button type="button" class="btn-sm btn-outline" style="padding: 4px 8px; font-size: 11px;" onclick="viewSelfiePembimbing('{{ $selfieInUrl }}', 'Selfie Masuk - {{ $att->mahasiswa->nama_lengkap ?? '' }}')">
                                                    <i class="fa-solid fa-camera"></i> Masuk
                                                </button>
                                            @endif
                                            @if($selfieOutUrl)
                                                <button type="button" class="btn-sm btn-outline" style="padding: 4px 8px; font-size: 11px;" onclick="viewSelfiePembimbing('{{ $selfieOutUrl }}', 'Selfie Pulang - {{ $att->mahasiswa->nama_lengkap ?? '' }}')">
                                                    <i class="fa-solid fa-camera"></i> Pulang
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                            
                            <td class="cell-long" data-label="Catatan" style="font-size: 12px; color: var(--muted); line-height: 1.4;">
                                {{ $att->notes ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <p>Tidak ada data presensi pada tanggal atau filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(($attendances ?? null) && $attendances->hasPages())
            <div class="pagination-wrapper" style="margin-top: 20px;">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

    <!-- SEMUA MODAL DILETAKKAN DI DALAM SECTION CONTENT AGAR LAYOUT TIDAK HANCUR -->

    <!-- ================= MODAL REVIEW KOREKSI ================= -->
    <div class="modal-overlay" id="reviewCorrectionModal">
        <div class="custom-modal">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fa-solid fa-clipboard-check" style="color: var(--primary);"></i> Review Koreksi Absensi</h3>
                <button type="button" class="modal-close" onclick="closeReviewModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="reviewCorrectionForm" method="POST" action="">
                @csrf
                <input type="hidden" name="status" id="correctionStatusInput" value="">

                <div class="modal-body">
                    <div style="background: var(--page); padding: 14px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 20px; font-size: 13px; line-height: 1.6;">
                        <p style="margin: 0 0 4px 0; color: var(--muted);">Mahasiswa: <strong id="modalMhsName" style="color: var(--text);">-</strong></p>
                        <p style="margin: 0 0 4px 0; color: var(--muted);">Tanggal: <strong id="modalDate" style="color: var(--text);">-</strong></p>
                        <p style="margin: 0; color: var(--muted);">Keputusan: <strong id="modalDecisionText" style="text-transform: uppercase;">-</strong></p>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">Catatan Pembimbing (Opsional)</label>
                        <textarea name="review_note" id="review_note" rows="3" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13.5px; color: var(--text); background: transparent; outline: none; font-family: inherit; height: auto; resize: vertical;" placeholder="Tuliskan catatan arahan pembimbing untuk mahasiswa..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-outline" onclick="closeReviewModal()">Batal</button>
                    <button type="submit" class="btn-sm btn-primary" id="modalSubmitBtn" style="border: none;">
                        <i class="fa-solid fa-check"></i> Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL VIEWER FOTO SELFIE ================= -->
    <div class="modal-overlay" id="modalSelfiePembimbing">
        <div class="custom-modal" style="max-width: 450px;">
            <div class="modal-header">
                <h3 id="pembimbingSelfieTitle"><i class="fa-solid fa-camera" style="color: var(--primary);"></i> Bukti Selfie Presensi</h3>
                <button type="button" class="modal-close" onclick="closeSelfiePembimbing()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" style="text-align: center; padding: 24px;">
                <img id="pembimbingSelfieImg" src="" style="width: 100%; max-height: 400px; border-radius: 12px; object-fit: contain; background: var(--page); border: 1px solid var(--border);">
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn-sm btn-outline" onclick="closeSelfiePembimbing()" style="width: 100%; justify-content: center;">Tutup Penampil</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Init Custom Select
        const nativeSelects = document.querySelectorAll('select.custom-select');
        nativeSelects.forEach(select => {
            if (select.nextElementSibling && select.nextElementSibling.classList.contains('custom-select-wrapper')) return;

            const wrapper = document.createElement('div');
            wrapper.className = 'custom-select-wrapper';
            
            const activeOption = select.options[select.selectedIndex];
            const trigger = document.createElement('div');
            trigger.className = 'custom-select-trigger';
            trigger.innerHTML = `<span>${activeOption ? activeOption.text : 'Pilih...'}</span> <i class="fa-solid fa-chevron-down"></i>`;
            
            const optionsPanel = document.createElement('div');
            optionsPanel.className = 'custom-select-options';
            
            Array.from(select.options).forEach(option => {
                const optDiv = document.createElement('div');
                optDiv.className = 'custom-option';
                optDiv.textContent = option.text;
                if (option.selected) optDiv.classList.add('selected');
                
                optDiv.addEventListener('click', (e) => {
                    e.stopPropagation();
                    select.value = option.value;
                    trigger.querySelector('span').textContent = option.text;
                    wrapper.classList.remove('open');
                    
                    optionsPanel.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
                    optDiv.classList.add('selected');
                    // Jika elemen ini di dalam form, submit form secara otomatis
                    if(select.form && select.getAttribute('onchange')) {
                        select.form.submit();
                    }
                });
                optionsPanel.appendChild(optDiv);
            });
            
            wrapper.appendChild(trigger);
            wrapper.appendChild(optionsPanel);
            select.parentNode.insertBefore(wrapper, select.nextSibling);
            
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.custom-select-wrapper').forEach(w => {
                    if(w !== wrapper) w.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });
        });
        
        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-select-wrapper').forEach(w => w.classList.remove('open'));
        });

        // ESC Key & Outside Click untuk Modals
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') { closeReviewModal(); closeSelfiePembimbing(); }
        });
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => {
                if(e.target === overlay) { closeReviewModal(); closeSelfiePembimbing(); }
            });
        });
    });

    // ================= FUNGSI MODAL KOREKSI =================
    window.openReviewModal = function(correctionId, mhsName, dateStr, status) {
        const form = document.getElementById('reviewCorrectionForm');
        const statusInput = document.getElementById('correctionStatusInput');
        const decisionText = document.getElementById('modalDecisionText');
        const submitBtn = document.getElementById('modalSubmitBtn');

        form.action = "{{ url('/pembimbing/absensi/koreksi') }}/" + correctionId + "/review";
        statusInput.value = status;

        document.getElementById('modalMhsName').textContent = mhsName;
        document.getElementById('modalDate').textContent = dateStr;

        if (status === 'approved') {
            decisionText.textContent = 'SETUJUI KOREKSI (Update Presensi)';
            decisionText.style.color = 'var(--success-text)';
            submitBtn.className = 'btn-sm btn-success';
            submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Setujui Koreksi';
        } else {
            decisionText.textContent = 'TOLAK KOREKSI';
            decisionText.style.color = '#DC2626';
            submitBtn.className = 'btn-sm btn-danger';
            submitBtn.innerHTML = '<i class="fa-solid fa-xmark"></i> Tolak Koreksi';
        }

        const m = document.getElementById('reviewCorrectionModal');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }

    window.closeReviewModal = function() {
        const m = document.getElementById('reviewCorrectionModal');
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);
    }

    // ================= FUNGSI MODAL SELFIE =================
    window.viewSelfiePembimbing = function(url, title) {
        document.getElementById('pembimbingSelfieImg').src = url;
        document.getElementById('pembimbingSelfieTitle').innerHTML = '<i class="fa-solid fa-camera" style="color: var(--primary);"></i> ' + title;
        
        const m = document.getElementById('modalSelfiePembimbing');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }

    window.closeSelfiePembimbing = function() {
        const m = document.getElementById('modalSelfiePembimbing');
        m.classList.remove('active');
        setTimeout(() => {
            m.style.display = 'none';
            document.getElementById('pembimbingSelfieImg').src = '';
        }, 200);
    }
</script>
@endpush