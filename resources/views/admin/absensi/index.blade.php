@extends('admin.layouts.app')

@section('title', 'Presensi & Koreksi')
@section('page_title', 'Monitoring Presensi & Koreksi')
@section('page_subtitle', 'Pantau kehadiran harian dan validasi pengajuan koreksi absensi mahasiswa')

@push('styles')
<style>
    /* ================= STATS GRID (DIPERBAIKI SEPERTI DOKUMEN.BLADE.PHP) ================= */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: var(--bg); padding: 24px; border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; gap: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }

    .stat-info { flex: 1; text-align: left; }
    .stat-info span { font-size: 13px; font-weight: 600; color: var(--muted); display: block; margin-bottom: 4px; }
    .stat-info h2 { font-size: 32px; font-weight: 800; color: var(--text); margin: 0 0 2px 0; line-height: 1; }

    /* Ikon Bulat Solid di Kanan */
    .stat-icon {
        width: 60px; height: 60px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px; flex-shrink: 0;
    }

    .icon-green { background: #ECFDF5; color: #059669; }
    .icon-yellow { background: #FFFBEB; color: #D97706; }
    .icon-blue { background: #EFF6FF; color: #2563EB; }
    .icon-teal { background: #f3f5f6; color: #3ab4ed; } /* Diubah sedikit warnanya agar seimbang */

    /* ================= CARDS & TABLE ================= */
    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; padding: 20px 24px; }
    .card-header { margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--page); }
    .card-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0; display: flex; align-items: center; gap: 10px; }

    .status-badge { padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; border: 1px solid var(--border); }
    .badge-present { background: var(--success-bg); color: var(--success-text); border-color: #A7F3D0; }
    .badge-late { background: #FEF3C7; color: #B45309; border-color: #FDE68A; }
    .badge-leave { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }
    .badge-sick { background: #F5F3FF; color: #6D28D9; border-color: #DDD6FE; }
    .badge-absent { background: #FEF2F2; color: #991B1B; border-color: #FECACA; }

    .btn-sm { padding: 6px 12px; font-size: 11.5px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; transition: 0.2s; white-space: nowrap; }
    .btn-outline { background: #F8FAFC; color: #475569; border: 1px solid var(--border); }
    .btn-outline:hover { background: #E2E8F0; color: var(--text); }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-primary:hover { background: var(--primary-dark); }
    .btn-filter-full { width: 100%; height: 38px; justify-content: center; font-size: 13px; border-radius: 8px; margin-top: 24px; }

    /* ================= FILTER GRID ================= */
    .filter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; align-items: end; }
    .form-group { margin-bottom: 0; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; }
    .input-box { width: 100%; padding: 10px 0; font-family: inherit; font-size: 13.5px; color: var(--text); background: transparent; border: none; border-bottom: 1.5px solid var(--primary); outline: none; transition: border-color .2s; }
    .input-box:focus { border-bottom: 2px solid var(--primary-dark); }

    /* ================= MODAL ================= */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 20px; opacity: 0; transition: opacity 0.2s; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .custom-modal { background: #fff; width: 100%; max-width: 500px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: scale(0.95) translateY(20px); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .modal-overlay.active .custom-modal { transform: scale(1) translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F9FAFB; border-radius: 16px 16px 0 0; }
    .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0; display: flex; align-items: center; gap: 8px; }
    .modal-close { background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer; padding: 4px 8px; border-radius: 6px; transition: 0.2s; }
    .modal-close:hover { background: #FEE2E2; color: var(--error-text); }
    .modal-body { padding: 24px; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #fafbfc; border-radius: 0 0 16px 16px; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
        .btn-filter-full { margin-top: 0; }
    }
    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .stat-card { padding: 16px 20px; }
        .filter-grid { grid-template-columns: 1fr; }
        .btn-filter-full { margin-top: 8px; }

        /* Tabel Mobile - Memperbaiki Bug Text Overflow */
        table, tbody, tr, td { display: block; width: 100%; }
        thead { display: none; }
        tbody tr { border: 1px solid var(--border); border-radius: 12px; margin-bottom: 16px; padding: 16px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        tbody td {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 10px 0; border-bottom: 1px solid #F1F5F9; text-align: right;
            word-wrap: break-word; overflow-wrap: break-word;
        }
        tbody td:last-child { border-bottom: none; padding-bottom: 0; }
        tbody td[data-label]::before { content: attr(data-label); font-weight: 600; color: var(--muted); text-align: left; flex-shrink: 0; margin-right: 12px; font-size: 12.5px; }

        tbody td.cell-user { display: block; text-align: left; background: var(--page); padding: 14px; border-radius: 8px; margin-bottom: 12px; border-bottom: none; }
        tbody td.cell-user::before { display: none; }

        /* Solusi Kolom Teks Panjang di Mobile (Catatan/Alasan) turun ke bawah */
        tbody td[data-label="Catatan"], tbody td[data-label="Alasan"] { flex-direction: column; text-align: left; }
        tbody td[data-label="Catatan"]::before, tbody td[data-label="Alasan"]::before { margin-bottom: 8px; }

        tbody td.cell-action { display: grid; grid-template-columns: 1fr; gap: 8px; padding-top: 16px; margin-top: 8px; }
        tbody td.cell-action::before { display: none; }
        tbody td.cell-action .btn-sm { width: 100%; justify-content: center; }

        .modal-footer { flex-direction: column-reverse; }
        .modal-footer .btn-sm { width: 100%; justify-content: center; height: 38px; }
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

    <!-- STATS GRID (TAMPILAN DIPERBAIKI SESUAI DOKUMEN.BLADE.PHP) -->
    <div class="stats-grid animate-fade-up delay-1">
        <div class="stat-card">
            <div class="stat-info">
                <span>Hadir Tepat Hari Ini</span>
                <h2>{{ str_pad($totalHadirHariIni, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-green"><i class="fa-solid fa-user-check"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Terlambat Hari Ini</span>
                <h2>{{ str_pad($totalTerlambatHariIni, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-yellow"><i class="fa-solid fa-clock-rotate-left"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Izin / Sakit / Cuti</span>
                <h2>{{ str_pad($totalIzinHariIni, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-blue"><i class="fa-solid fa-envelope-open-text"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Total Mahasiswa PKL</span>
                <h2>{{ str_pad($totalMhs, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-teal"><i class="fa-solid fa-users"></i></div>
        </div>
    </div>

    <!-- PENGAJUAN KOREKSI (MENUNGGU VERIFIKASI) -->
    @if($pendingCorrections->count() > 0)
        <div class="card animate-fade-up delay-2" style="border-left: 5px solid #f59e0b;">
            <div class="card-header">
                <h3><i class="fa-solid fa-triangle-exclamation" style="color: #f59e0b;"></i> Pengajuan Koreksi Menunggu Verifikasi ({{ $pendingCorrections->count() }})</h3>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Mahasiswa & NIM</th>
                            <th>Tgl Absensi</th>
                            <th>Jenis Koreksi</th>
                            <th>Waktu Diajukan</th>
                            <th>Alasan</th>
                            <th style="text-align: center;">Bukti</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingCorrections as $corr)
                            <tr>
                                <td class="cell-user">
                                    <strong style="color: var(--text); display: block;">{{ $corr->mahasiswa->nama_lengkap ?? '-' }}</strong>
                                    <small style="color: var(--muted);">NIM: <span class="code-tag" style="background: transparent; padding: 0;">{{ $corr->mahasiswa->nim ?? '-' }}</span></small>
                                </td>
                                <td data-label="Tgl Absensi">{{ $corr->correction_date->format('d/m/Y') }}</td>
                                <td data-label="Jenis Koreksi">
                                    <span style="background: #F1F5F9; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;">
                                        {{ ucwords(str_replace('_', ' ', $corr->correction_type)) }}
                                    </span>
                                </td>
                                <td data-label="Waktu Diajukan" style="font-size: 12.5px;">
                                    @if($corr->requested_check_in_at) Masuk: <strong>{{ $corr->requested_check_in_at->format('H:i') }}</strong><br>@endif
                                    @if($corr->requested_check_out_at) Pulang: <strong>{{ $corr->requested_check_out_at->format('H:i') }}</strong>@endif
                                </td>
                                <td data-label="Alasan" style="max-width: 200px; font-size: 12px; color: var(--muted);">{{ $corr->reason }}</td>
                                <td data-label="Bukti" style="text-align: center;">
                                    @if($corr->attachment_path)
                                        <a href="{{ asset($corr->attachment_path) }}" target="_blank" class="btn-sm btn-outline">
                                            <i class="fa-solid fa-paperclip"></i> Lihat
                                        </a>
                                    @else
                                        <span style="color: #94a3b8; font-size: 12px;">-</span>
                                    @endif
                                </td>
                                <td class="cell-action" style="text-align: center;">
                                    <button type="button" class="btn-sm btn-primary" onclick='openReviewModal({{ $corr->id_correction }}, @json($corr))'>
                                        <i class="fa-solid fa-pen-to-square"></i> Review
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- FILTER & DAFTAR PRESENSI -->
    <div class="card animate-fade-up delay-3">
        <form action="{{ route('admin.absensi') }}" method="GET" class="filter-grid" style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--page);">
            <div class="form-group">
                <label>Pilih Tanggal</label>
                <div class="date-wrapper" style="position: relative;">
                    <input type="text" name="date" value="{{ $date }}" class="flatpickr-date input-box" style="padding-right: 30px;">
                    <i class="fa-solid fa-calendar-days" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: var(--primary); pointer-events: none;"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Status Kehadiran</label>
                <select name="status" class="d-none custom-select">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Hadir Tepat</option>
                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                    <option value="leave" {{ request('status') === 'leave' ? 'selected' : '' }}>Cuti / Izin</option>
                    <option value="sick" {{ request('status') === 'sick' ? 'selected' : '' }}>Sakit</option>
                </select>
            </div>

            <div class="form-group">
                <label>Cari Nama / NIM</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIM..." class="input-box">
            </div>

            <div class="form-group">
                <button type="submit" class="btn-sm btn-primary btn-filter-full">
                    <i class="fa-solid fa-filter"></i> Terapkan Filter
                </button>
            </div>
        </form>

        <div class="card-header" style="border: none; padding: 0 0 16px 0;">
            <h3><i class="fa-solid fa-list-check" style="color: var(--primary);"></i> Daftar Kehadiran ({{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }})</h3>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Mahasiswa & NIM</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Bukti Selfie</th>
                        <th style="text-align: center;">Status</th>
                        <th>Keterlambatan</th>
                        <th>Jam Kerja</th>
                        <th>Jarak Geofence</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $row)
                        <tr>
                            <td class="cell-user">
                                <strong style="color: var(--text); display: block;">{{ $row->mahasiswa->nama_lengkap ?? '-' }}</strong>
                                <small style="color: var(--muted);">NIM: <span class="code-tag" style="background: transparent; padding: 0;">{{ $row->mahasiswa->nim ?? '-' }}</span></small>
                            </td>
                            <td data-label="Jam Masuk">{{ $row->check_in_at ? $row->check_in_at->format('H:i') . ' WIB' : '-' }}</td>
                            <td data-label="Jam Pulang">{{ $row->check_out_at ? $row->check_out_at->format('H:i') . ' WIB' : '-' }}</td>

                            <td data-label="Bukti Selfie">
                                @if($row->selfie_path || $row->selfie_out_path)
                                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                        @if($row->selfie_path)
                                            <button type="button" class="btn-sm btn-outline" style="padding: 4px 8px; font-size: 11px;" onclick="viewSelfieAdmin('{{ asset($row->selfie_path) }}', 'Selfie Masuk - {{ $row->mahasiswa->nama_lengkap ?? '' }} ({{ $row->check_in_at ? $row->check_in_at->format('H:i') : '' }} WIB)')">
                                                <i class="fa-solid fa-camera"></i> Masuk
                                            </button>
                                        @endif
                                        @if($row->selfie_out_path)
                                            <button type="button" class="btn-sm btn-outline" style="padding: 4px 8px; font-size: 11px;" onclick="viewSelfieAdmin('{{ asset($row->selfie_out_path) }}', 'Selfie Pulang - {{ $row->mahasiswa->nama_lengkap ?? '' }} ({{ $row->check_out_at ? $row->check_out_at->format('H:i') : '' }} WIB)')">
                                                <i class="fa-solid fa-camera"></i> Pulang
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-size: 12px;">-</span>
                                @endif
                            </td>

                            <td data-label="Status" style="text-align: center;">
                                @php
                                    $badgeClass = match($row->check_in_status) {
                                        'present' => 'badge-present',
                                        'late' => 'badge-late',
                                        'leave' => 'badge-leave',
                                        'sick' => 'badge-sick',
                                        default => 'badge-absent',
                                    };
                                    $statusText = match($row->check_in_status) {
                                        'present' => 'Hadir Tepat',
                                        'late' => 'Terlambat',
                                        'leave' => 'Cuti / Izin',
                                        'sick' => 'Sakit',
                                        default => 'Alpa',
                                    };
                                @endphp
                                <span class="status-badge {{ $badgeClass }}">{{ $statusText }}</span>
                            </td>

                            <td data-label="Keterlambatan">{{ $row->late_minutes > 0 ? "{$row->late_minutes} menit" : '-' }}</td>
                            <td data-label="Jam Kerja">{{ $row->total_work_minutes > 0 ? floor($row->total_work_minutes / 60) . 'j ' . ($row->total_work_minutes % 60) . 'm' : '-' }}</td>
                            <td data-label="Geofence">{{ $row->distance_in_meters ? "{$row->distance_in_meters} m" : '-' }}</td>
                            <td data-label="Catatan" style="max-width: 180px; font-size: 12px; color: var(--muted); line-height: 1.4;">{{ $row->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <p>Belum ada data presensi pada tanggal ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
            <div class="pagination-wrapper" style="margin-top: 20px;">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

<!-- ================= MODAL REVIEW KOREKSI ================= -->
<div class="modal-overlay" id="reviewKoreksiModal">
    <div class="custom-modal">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-to-square" style="color: var(--primary);"></i> Review Koreksi Absensi</h3>
            <button type="button" class="modal-close" onclick="closeReviewModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="" method="POST" id="reviewForm">
            @csrf
            <div class="modal-body">
                <div style="background: var(--page); padding: 14px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 20px; font-size: 13px; line-height: 1.6;" id="reviewStudentInfo"></div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Keputusan Persetujuan <span style="color: var(--error-text);">*</span></label>
                    <select name="status" class="d-none custom-select" required>
                        <option value="approved">Setujui Koreksi (Perbarui Presensi)</option>
                        <option value="rejected">Tolak Pengajuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Catatan / Alasan Verifikasi</label>
                    <input type="text" name="review_note" placeholder="Contoh: Bukti surat dinas valid / Lokasi disetujui" class="input-box" style="width: 100%; border: 1.5px solid var(--border); border-radius: 8px; padding: 10px 14px;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-sm btn-outline" onclick="closeReviewModal()">Batal</button>
                <button type="submit" class="btn-sm btn-primary"><i class="fa-solid fa-check"></i> Simpan Keputusan</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL BUKTI SELFIE ================= -->
<div class="modal-overlay" id="modalSelfieAdmin">
    <div class="custom-modal" style="max-width: 450px;">
        <div class="modal-header">
            <h3 id="adminSelfieTitle"><i class="fa-solid fa-camera" style="color: var(--primary);"></i> Bukti Selfie Presensi</h3>
            <button type="button" class="modal-close" onclick="closeSelfieAdmin()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 24px;">
            <img id="adminSelfieImg" src="" style="width: 100%; max-height: 400px; border-radius: 12px; object-fit: contain; background: var(--page); border: 1px solid var(--border);">
        </div>
        <div class="modal-footer" style="justify-content: center;">
            <button type="button" class="btn-sm btn-outline" onclick="closeSelfieAdmin()" style="width: 100%; justify-content: center;">Tutup Penampil</button>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // 1. INIT FLATPICKR
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-date", { dateFormat: "Y-m-d", locale: "id", disableMobile: "true" });
        }

        // 2. CUSTOM DROPDOWN
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

        // 3. MODAL OVERLAY ESC KEY & CLICK BG
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') { closeReviewModal(); closeSelfieAdmin(); }
        });
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => {
                if(e.target === overlay) { closeReviewModal(); closeSelfieAdmin(); }
            });
        });
    });

    // ================= FUNGSI MODAL REVIEW =================
    window.openReviewModal = function(id, data) {
        document.getElementById('reviewForm').action = '/admin/absensi/koreksi/' + id + '/review';
        document.getElementById('reviewStudentInfo').innerHTML =
            '<span style="color: var(--muted); display: block; margin-bottom: 4px;">Informasi Pengajuan:</span>' +
            'Mahasiswa: <strong style="color: var(--text);">' + (data.mahasiswa ? data.mahasiswa.nama_lengkap : '-') + '</strong><br>' +
            'Tanggal Absensi: <strong style="color: var(--text);">' + data.correction_date + '</strong><br>' +
            'Alasan: <em style="color: var(--text);">' + data.reason + '</em>';

        const m = document.getElementById('reviewKoreksiModal');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }

    window.closeReviewModal = function() {
        const m = document.getElementById('reviewKoreksiModal');
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);
    }

    // ================= FUNGSI MODAL SELFIE =================
    window.viewSelfieAdmin = function(url, title) {
        document.getElementById('adminSelfieImg').src = url;
        document.getElementById('adminSelfieTitle').innerHTML = '<i class="fa-solid fa-camera" style="color: var(--primary);"></i> ' + title;

        const m = document.getElementById('modalSelfieAdmin');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }

    window.closeSelfieAdmin = function() {
        const m = document.getElementById('modalSelfieAdmin');
        m.classList.remove('active');
        setTimeout(() => {
            m.style.display = 'none';
            document.getElementById('adminSelfieImg').src = '';
        }, 200);
    }
</script>
@endpush
