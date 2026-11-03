@extends('admin.layouts.app')

@section('title', 'Cuti & Izin')
@section('page_title', 'Pengajuan Cuti & Izin Mahasiswa')
@section('page_subtitle', 'Verifikasi permohonan izin sakit, akademik, atau cuti pribadi. Izin yang disetujui otomatis memperbarui presensi.')

@push('styles')
<style>
    /* ================= STATS GRID (DIPERBAIKI) ================= */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: var(--bg); padding: 24px; border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; gap: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }

    .stat-info { flex: 1; text-align: left; }
    .stat-info h4 { font-size: 13px; font-weight: 600; color: var(--muted); margin: 0 0 4px 0; text-transform: none; letter-spacing: normal; }
    .stat-info .stat-value { font-size: 32px; font-weight: 800; color: var(--text); line-height: 1; }

    /* Ikon Bulat di Kanan */
    .stat-icon {
        width: 60px; height: 60px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px; flex-shrink: 0;
    }

    /* Warna Background Soft & Ikon Tegas */
    .icon-yellow { background: #FFFBEB; color: #D97706; }
    .icon-green { background: #ECFDF5; color: #059669; }
    .icon-red { background: #FEF2F2; color: #DC2626; }

    /* ================= CARDS & TABLE ================= */
    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; padding: 20px 24px; }
    .card-header { margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--page); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; }
    .card-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0; display: flex; align-items: center; gap: 10px; }

    .status-badge { padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; border: 1px solid var(--border); }
    .badge-submitted { background: #FEF3C7; color: #B45309; border-color: #FDE68A; }
    .badge-approved { background: var(--success-bg); color: var(--success-text); border-color: #A7F3D0; }
    .badge-rejected { background: #FEF2F2; color: #991B1B; border-color: #FECACA; }
    .badge-cancelled { background: #F1F5F9; color: #475569; }

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
    .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0; display: flex; align-items: center; gap: 8px;}
    .modal-close { background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer; padding: 4px 8px; border-radius: 6px; transition: 0.2s; }
    .modal-close:hover { background: #FEE2E2; color: var(--error-text); }
    .modal-body { padding: 24px; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #fafbfc; border-radius: 0 0 16px 16px; }

    /* ================= RESPONSIVE ================= */
    @media (min-width: 761px) {
        .cell-alasan { max-width: 200px; text-align: left; }
    }
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
        .btn-filter-full { margin-top: 0; }
    }
    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .stat-card { padding: 16px 20px; }
        .filter-grid { grid-template-columns: 1fr; }
        .btn-filter-full { margin-top: 8px; }

        /* Tabel Mobile - Menghindari Bug Text Overflow */
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

        /* Solusi Kolom Teks Panjang di Mobile (Alasan) turun ke bawah */
        tbody td.cell-alasan { flex-direction: column; text-align: left; }
        tbody td.cell-alasan::before { margin-bottom: 8px; }

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

    <!-- STATS GRID -->
    <div class="stats-grid animate-fade-up delay-1">
        <div class="stat-card">
            <div class="stat-info">
                <h4>Menunggu Persetujuan</h4>
                <div class="stat-value">{{ str_pad($pendingCount, 2, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="stat-icon icon-yellow"><i class="fa-solid fa-hourglass-half"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h4>Disetujui</h4>
                <div class="stat-value">{{ str_pad($approvedCount, 2, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="stat-icon icon-green"><i class="fa-solid fa-circle-check"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h4>Ditolak</h4>
                <div class="stat-value">{{ str_pad($rejectedCount, 2, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="stat-icon icon-red"><i class="fa-solid fa-circle-xmark"></i></div>
        </div>
    </div>

    <!-- FILTER & DAFTAR PENGAJUAN -->
    <div class="card animate-fade-up delay-2">
        <form action="{{ route('admin.cuti') }}" method="GET" class="filter-grid" style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--page);">

            <div class="form-group">
                <label>Status Pengajuan</label>
                <select name="status" class="d-none custom-select">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Menunggu Review</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="form-group">
                <label>Jenis Cuti / Izin</label>
                <select name="leave_type" class="d-none custom-select">
                    <option value="all" {{ request('leave_type') === 'all' ? 'selected' : '' }}>Semua Jenis</option>
                    <option value="personal_leave" {{ request('leave_type') === 'personal_leave' ? 'selected' : '' }}>Cuti Pribadi</option>
                    <option value="sick_leave" {{ request('leave_type') === 'sick_leave' ? 'selected' : '' }}>Izin Sakit</option>
                    <option value="academic_leave" {{ request('leave_type') === 'academic_leave' ? 'selected' : '' }}>Izin Akademik</option>
                    <option value="official_permission" {{ request('leave_type') === 'official_permission' ? 'selected' : '' }}>Izin Dinas Resmi</option>
                </select>
            </div>

            <div class="form-group">
                <label>Cari Nama / NIM</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama mahasiswa..." class="input-box">
            </div>

            <div class="form-group">
                <button type="submit" class="btn-sm btn-primary btn-filter-full">
                    <i class="fa-solid fa-filter"></i> Filter Pengajuan
                </button>
            </div>
        </form>

        <div class="card-header" style="border: none; padding: 0 0 16px 0;">
            <h3><i class="fa-solid fa-calendar-days" style="color: var(--primary);"></i> Daftar Pengajuan Cuti & Izin</h3>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Mahasiswa & NIM</th>
                        <th>Jenis Izin</th>
                        <th>Periode Tanggal</th>
                        <th style="text-align: center;">Durasi</th>
                        <th>Alasan</th>
                        <th>Kontak Darurat</th>
                        <th style="text-align: center;">Bukti</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $item)
                        @php
                            $durasi = \Carbon\Carbon::parse($item->start_date)->diffInDays(\Carbon\Carbon::parse($item->end_date)) + 1;
                        @endphp
                        <tr>
                            <td class="cell-user">
                                <strong style="color: var(--text); display: block;">{{ $item->mahasiswa->nama_lengkap ?? '-' }}</strong>
                                <small style="color: var(--muted);">NIM: <span class="code-tag" style="background: transparent; padding: 0;">{{ $item->mahasiswa->nim ?? '-' }}</span></small>
                            </td>

                            <td data-label="Jenis Izin">
                                <span style="background: #F1F5F9; padding: 4px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 600;">
                                    {{ \App\Models\LeaveRequest::getLeaveTypeLabel($item->leave_type) }}
                                </span>
                            </td>

                            <td data-label="Periode Tanggal" style="font-size: 12px; font-weight: 500;">
                                {{ $item->start_date->format('d/m/Y') }} <span style="color: var(--muted);">s/d</span> {{ $item->end_date->format('d/m/Y') }}
                            </td>

                            <td data-label="Durasi" style="text-align: center; font-weight: 600;">{{ $durasi }} Hari</td>

                            <!-- Tambahan Class cell-alasan agar tidak terpotong di HP -->
                            <td class="cell-alasan" data-label="Alasan" style="font-size: 12px; color: var(--muted); line-height: 1.4;">{{ $item->reason }}</td>

                            <td data-label="Kontak Darurat" style="font-size: 12px;">{{ $item->emergency_contact ?: '-' }}</td>

                            <td data-label="Bukti" style="text-align: center;">
                                @if($item->attachment_path)
                                    <a href="{{ asset($item->attachment_path) }}" target="_blank" class="btn-sm btn-outline">
                                        <i class="fa-solid fa-paperclip"></i> File
                                    </a>
                                @else
                                    <span style="color: #94a3b8; font-size: 12px;">-</span>
                                @endif
                            </td>

                            <td data-label="Status" style="text-align: center;">
                                @php
                                    $badgeClass = match($item->status) {
                                        'submitted' => 'badge-submitted',
                                        'approved' => 'badge-approved',
                                        'rejected' => 'badge-rejected',
                                        default => 'badge-cancelled',
                                    };
                                    $statusText = match($item->status) {
                                        'submitted' => 'Menunggu Review',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        'cancelled' => 'Dibatalkan',
                                        default => $item->status,
                                    };
                                @endphp
                                <span class="status-badge {{ $badgeClass }}">{{ $statusText }}</span>
                            </td>

                            <td class="cell-action" style="text-align: center;">
                                <button type="button" class="btn-sm btn-primary" onclick='openCutiReviewModal({{ $item->id_leave }}, @json($item))'>
                                    <i class="fa-solid fa-pen-to-square"></i> Review
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <p>Belum ada data pengajuan cuti/izin.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leaves->hasPages())
            <div class="pagination-wrapper" style="margin-top: 20px;">
                {{ $leaves->links() }}
            </div>
        @endif
    </div>

<!-- ================= MODAL REVIEW CUTI ================= -->
<div class="modal-overlay" id="reviewCutiModal">
    <div class="custom-modal">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-to-square" style="color: var(--primary);"></i> Persetujuan Pengajuan Cuti / Izin</h3>
            <button type="button" class="modal-close" onclick="closeCutiReviewModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="" method="POST" id="cutiReviewForm">
            @csrf
            <div class="modal-body">
                <div id="cutiDetailBox" style="background: var(--page); border: 1px solid var(--border); border-radius: 8px; padding: 14px; margin-bottom: 20px; font-size: 12.5px; color: var(--muted); line-height: 1.6;">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Keputusan Persetujuan <span style="color: var(--error-text);">*</span></label>
                    <select id="cuti_status" name="status" class="d-none custom-select" required>
                        <option value="approved">Setujui Cuti / Izin (Update Presensi Mahasiswa)</option>
                        <option value="rejected">Tolak Pengajuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Catatan Alasan Keputusan</label>
                    <input type="text" id="cuti_note" name="review_note" placeholder="Contoh: Bukti surat dokter terverifikasi / Keperluan mendesak disetujui" class="input-box">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-sm btn-outline" onclick="closeCutiReviewModal()">Batal</button>
                <button type="submit" class="btn-sm btn-primary"><i class="fa-solid fa-check"></i> Simpan Keputusan</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // 1. CUSTOM DROPDOWN
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

        // 2. MODAL OVERLAY ESC KEY & CLICK BG
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeCutiReviewModal();
        });
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => {
                if(e.target === overlay) closeCutiReviewModal();
            });
        });
    });

    // ================= FUNGSI MODAL REVIEW =================
    window.openCutiReviewModal = function(id, item) {
        document.getElementById('cutiReviewForm').action = '/admin/cuti/' + id + '/review';

        // Menerjemahkan Tipe Izin
        let typeLabel = item.leave_type;
        if(typeLabel === 'personal_leave') typeLabel = 'Cuti Pribadi';
        if(typeLabel === 'sick_leave') typeLabel = 'Izin Sakit';
        if(typeLabel === 'academic_leave') typeLabel = 'Izin Akademik';
        if(typeLabel === 'official_permission') typeLabel = 'Izin Dinas Resmi';

        document.getElementById('cutiDetailBox').innerHTML =
            '<strong style="color: var(--text);">Mahasiswa:</strong> ' + (item.mahasiswa ? item.mahasiswa.nama_lengkap : '-') + '<br>' +
            '<strong style="color: var(--text);">Jenis:</strong> ' + typeLabel + '<br>' +
            '<strong style="color: var(--text);">Periode:</strong> ' + item.start_date.split('T')[0] + ' s/d ' + item.end_date.split('T')[0] + '<br>' +
            '<strong style="color: var(--text);">Alasan:</strong> ' + item.reason + '<br>' +
            (item.emergency_contact ? '<strong style="color: var(--text);">Kontak Darurat:</strong> ' + item.emergency_contact : '');

        // Set select status value
        const statusSelect = document.getElementById('cuti_status');
        let targetValue = item.status === 'approved' ? 'approved' : (item.status === 'rejected' ? 'rejected' : 'approved');
        statusSelect.value = targetValue;

        // Sync Custom Select UI
        const wrapper = statusSelect.nextElementSibling;
        if(wrapper && wrapper.classList.contains('custom-select-wrapper')) {
            const triggerSpan = wrapper.querySelector('.custom-select-trigger span');
            const targetOption = Array.from(statusSelect.options).find(opt => opt.value === targetValue);
            if(triggerSpan && targetOption) triggerSpan.textContent = targetOption.text;

            wrapper.querySelectorAll('.custom-option').forEach(el => {
                el.classList.remove('selected');
                if(el.textContent === targetOption.text) el.classList.add('selected');
            });
        }

        document.getElementById('cuti_note').value = item.review_note || '';

        const m = document.getElementById('reviewCutiModal');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }

    window.closeCutiReviewModal = function() {
        const m = document.getElementById('reviewCutiModal');
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);
    }
</script>
@endpush