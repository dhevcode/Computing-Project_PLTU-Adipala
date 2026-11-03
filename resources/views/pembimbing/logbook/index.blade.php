@extends('pembimbing.layouts.app')

@section('title', 'Logbook PKL Bimbingan')
@section('page_title', 'Monitoring & Validasi Logbook Harian')
@section('page_subtitle', 'Tinjau dan berikan feedback pada laporan aktivitas kerja harian mahasiswa magang Anda.')

@push('styles')
<style>
    /* ================= STATS GRID ================= */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: var(--bg, #ffffff); padding: 24px; border-radius: 16px; 
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border, #e2e8f0); 
        display: flex; align-items: center; justify-content: space-between; gap: 20px; 
        transition: transform 0.2s, box-shadow 0.2s; 
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
    
    .stat-info { flex: 1; text-align: left; }
    .stat-info span { font-size: 13px; font-weight: 600; color: var(--muted, #64748b); display: block; margin-bottom: 4px; }
    .stat-info h2 { font-size: 32px; font-weight: 800; color: var(--text, #0f172a); margin: 0 0 2px 0; line-height: 1; }

    .stat-icon { 
        width: 60px; height: 60px; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; 
        font-size: 26px; flex-shrink: 0; 
    }
    
    .icon-yellow { background: #FFFBEB; color: #D97706; }
    .icon-green { background: #ECFDF5; color: #059669; }
    .icon-red { background: #FEF2F2; color: #EF4444; }

    /* ================= CARDS & TABLE ================= */
    .card { background: var(--bg, #ffffff); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border, #e2e8f0); overflow: hidden; padding: 24px; }
    
    .btn-sm { padding: 8px 14px; font-size: 12.5px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; transition: 0.2s; white-space: nowrap; height: fit-content; }
    .btn-primary { background: var(--primary, #0A5F63); color: #fff; border: 1px solid var(--primary, #0A5F63); }
    .btn-primary:hover { background: #07474a; border-color: #07474a; }
    .btn-outline { background: #F8FAFC; color: #475569; border: 1px solid var(--border, #e2e8f0); }
    .btn-outline:hover { background: #E2E8F0; color: var(--text, #0f172a); }

    /* Badges */
    .badge { padding: 4px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .badge-approved { background: #ECFDF5; color: #059669; }
    .badge-pending { background: #FFFBEB; color: #D97706; }
    .badge-revision { background: #FEF2F2; color: #EF4444; }
    .badge-rejected { background: #F1F5F9; color: #475569; }

    /* Filter Form */
    .filter-wrapper { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; background: #F8FAFC; padding: 16px; border-radius: 12px; border: 1px solid var(--border, #e2e8f0); align-items: flex-end; }
    .filter-group { flex: 1; min-width: 180px; }
    .filter-group label { font-size: 11.5px; font-weight: 600; color: var(--muted, #64748b); display: block; margin-bottom: 6px; }
    .filter-group input { width: 100%; height: 38px; padding: 0 12px; border-radius: 8px; border: 1.5px solid var(--border, #e2e8f0); font-size: 13px; outline: none; transition: 0.2s; background: #fff; }
    .filter-group input:focus { border-color: var(--primary, #0A5F63); }
    .filter-actions { display: flex; gap: 10px; align-items: flex-end; }

    /* Sembunyikan Select Asli karena digantikan custom div */
    select.custom-filter-select { display: none !important; }

    /* ================= REVIEW MODAL ================= */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; visibility: hidden; transition: 0.3s; backdrop-filter: blur(4px); }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-card { background: #fff; width: 100%; max-width: 700px; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); transform: translateY(20px); transition: 0.3s; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; }
    .modal-overlay.active .modal-card { transform: translateY(0); }
    .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border, #e2e8f0); display: flex; justify-content: space-between; align-items: center; }
    .modal-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: var(--text, #0f172a); }
    .modal-close { background: none; border: none; font-size: 24px; color: var(--muted, #64748b); cursor: pointer; line-height: 1; }
    .modal-body { padding: 24px; overflow-y: auto; flex: 1; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border, #e2e8f0); display: flex; justify-content: flex-end; gap: 12px; background: #F8FAFC; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .filter-wrapper { flex-direction: column; }
        .filter-group { width: 100%; }
        .filter-actions { width: 100%; justify-content: stretch; }
        .filter-actions button, .filter-actions a { flex: 1; justify-content: center; }
        
        /* Tabel Mobile */
        table, tbody, tr, td { display: block; width: 100%; }
        thead { display: none; }
        tbody tr { border: 1px solid var(--border, #e2e8f0); border-radius: 12px; margin-bottom: 16px; padding: 16px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        tbody td { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #F1F5F9; text-align: right; word-wrap: break-word; overflow-wrap: break-word; }
        tbody td:last-child { border-bottom: none; padding-bottom: 0; }
        tbody td[data-label]::before { content: attr(data-label); font-weight: 600; color: var(--muted, #64748b); text-align: left; flex-shrink: 0; margin-right: 12px; font-size: 12.5px; }
        
        tbody td.cell-user { display: block; text-align: left; background: #F8FAFC; padding: 14px; border-radius: 8px; margin-bottom: 12px; border-bottom: none; }
        tbody td.cell-user::before { display: none; }
        
        tbody td.cell-action { display: grid; grid-template-columns: 1fr; gap: 8px; padding-top: 16px; margin-top: 8px; }
        tbody td.cell-action::before { display: none; }
        tbody td.cell-action .btn-sm { width: 100%; justify-content: center; }

        .modal-body .grid-2 { grid-template-columns: 1fr !important; }
    }
</style>
@endpush

@section('content')

    <!-- STATS GRID -->
    <div class="stats-grid animate-fade-up">
        <div class="stat-card">
            <div class="stat-info">
                <span>Menunggu Validasi</span>
                <h2>{{ str_pad($pendingCount ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-yellow"><i class="fa-solid fa-hourglass-half"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Telah Disetujui</span>
                <h2>{{ str_pad($approvedCount ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-green"><i class="fa-solid fa-circle-check"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Perlu Revisi</span>
                <h2>{{ str_pad($revisionCount ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-red"><i class="fa-solid fa-arrows-rotate"></i></div>
        </div>
    </div>

    <!-- MAIN CARD -->
    <div class="card animate-fade-up delay-1">
        
        <!-- Filter Bar -->
        <form id="filterForm" method="GET" action="{{ route('pembimbing.logbook') }}" class="filter-wrapper">
            <div class="filter-group">
                <label>Pilih Mahasiswa</label>
                <!-- Tambah class 'custom-filter-select' untuk memicu styling dari app.blade.php -->
                <select name="mahasiswa_id" class="custom-filter-select">
                    <option value="all">-- Semua Mahasiswa --</option>
                    @foreach($mahasiswaList as $m)
                        <option value="{{ $m->id_mhs }}" {{ request('mahasiswa_id') == $m->id_mhs ? 'selected' : '' }}>
                            {{ $m->nama_lengkap }} ({{ $m->nim }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Status Validasi</label>
                <select name="status" class="custom-filter-select">
                    <option value="all">-- Semua Status --</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Menunggu Review</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="revision_required" {{ request('status') === 'revision_required' ? 'selected' : '' }}>Perlu Revisi</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Tanggal Kegiatan</label>
                <input type="date" name="date" class="flatpickr-date" value="{{ request('date') }}" onchange="this.form.submit()">
            </div>

            <div class="filter-group" style="flex: 2;">
                <label>Cari Kegiatan</label>
                <input type="text" name="search" placeholder="Cari judul/aktivitas..." value="{{ request('search') }}">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-sm btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                @if(request()->hasAny(['mahasiswa_id', 'status', 'date', 'search']))
                    <a href="{{ route('pembimbing.logbook') }}" class="btn-sm btn-outline"><i class="fa-solid fa-rotate-left"></i> Reset</a>
                @endif
            </div>
        </form>

        <!-- Tabel Logbook -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Peserta Mahasiswa</th>
                        <th>Waktu Kegiatan</th>
                        <th>Judul & Aktivitas</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logbooks as $log)
                        <tr>
                            <td class="cell-user">
                                <strong style="color: var(--text, #0f172a); display: block;">{{ $log->mahasiswa->nama_lengkap ?? '-' }}</strong>
                                <small style="color: var(--muted, #64748b);">NIM: {{ $log->mahasiswa->nim ?? '-' }}</small>
                            </td>
                            <td data-label="Waktu">
                                <strong>{{ \Carbon\Carbon::parse($log->logbook_date)->format('d M Y') }}</strong><br>
                                <small style="color: var(--muted, #64748b);">{{ substr($log->start_time, 0, 5) }} - {{ substr($log->end_time, 0, 5) }} WIB</small>
                            </td>
                            <td data-label="Kegiatan" style="max-width: 300px; text-align: left;">
                                <strong style="color: var(--text, #0f172a);">{{ $log->title }}</strong>
                                <div style="font-size: 12.5px; color: var(--muted, #64748b); margin-top: 3px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-align: left;">
                                    {{ $log->activity }}
                                </div>
                                @if($log->attachment_path)
                                    <div style="margin-top: 6px;">
                                        <a href="{{ asset(str_starts_with($log->attachment_path, 'uploads/') ? $log->attachment_path : 'uploads/logbook/' . $log->attachment_path) }}" target="_blank" style="font-size: 11.5px; color: #2563EB; font-weight: 600; text-decoration: none;">
                                            <i class="fa-solid fa-paperclip"></i> {{ $log->attachment_name ?: 'Lampiran Berkas' }}
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td data-label="Status">
                                @if($log->status === 'approved')
                                    <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Disetujui</span>
                                @elseif($log->status === 'submitted')
                                    <span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Menunggu</span>
                                @elseif($log->status === 'revision_required')
                                    <span class="badge badge-revision"><i class="fa-solid fa-pencil"></i> Perlu Revisi</span>
                                @else
                                    <span class="badge badge-rejected"><i class="fa-solid fa-xmark"></i> {{ ucfirst($log->status) }}</span>
                                @endif
                            </td>
                            <td data-label="Catatan" style="font-size: 12px; color: var(--muted, #64748b); text-align: left;">
                                {{ $log->review_note ?: '-' }}
                            </td>
                            <td class="cell-action">
                                <button type="button" class="btn-sm btn-primary" onclick='openLogbookModal(@json($log))'>
                                    <i class="fa-regular fa-eye"></i> Tinjau
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div style="text-align: center; padding: 36px; color: var(--muted, #64748b);">
                                    <i class="fa-regular fa-book-open" style="font-size: 36px; display: block; margin-bottom: 12px; opacity: 0.5;"></i>
                                    Tidak ada logbook yang ditemukan pada filter ini.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $logbooks->links() }}
        </div>
    </div>

    <!-- MODAL TINJAU & REVIEW LOGBOOK -->
    <div class="modal-overlay" id="logbookModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tinjau & Validasi Logbook</h3>
                <button type="button" class="modal-close" onclick="closeLogbookModal()">&times;</button>
            </div>

            <form id="logbookReviewForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <!-- Ringkasan Info Mahasiswa -->
                    <div style="background: #F8FAFC; padding: 14px 18px; border-radius: 8px; margin-bottom: 16px; border: 1px solid var(--border, #e2e8f0); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <strong id="lbMhsName" style="font-size: 15px; color: var(--text, #0f172a);">-</strong>
                            <div id="lbMhsNim" style="font-size: 12px; color: var(--muted, #64748b);">-</div>
                        </div>
                        <div style="text-align: right;">
                            <div id="lbDate" style="font-weight: 700; color: var(--primary, #0A5F63);">-</div>
                            <div id="lbTime" style="font-size: 12px; color: var(--muted, #64748b);">-</div>
                        </div>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase;">Judul Aktivitas</label>
                        <div id="lbTitle" style="font-size: 14px; font-weight: 700; color: var(--text, #0f172a); margin-top: 4px;"></div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase;">Uraian Aktivitas Kerja</label>
                        <div id="lbActivity" style="font-size: 13px; background: #FAFAFA; padding: 12px; border-radius: 8px; border: 1px solid var(--border, #e2e8f0); white-space: pre-wrap; margin-top: 4px; line-height: 1.5;"></div>
                    </div>

                    <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                        <div>
                            <label style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase;">Hasil Kegiatan (Output)</label>
                            <div id="lbResult" style="font-size: 13px; background: #FAFAFA; padding: 12px; border-radius: 8px; border: 1px solid var(--border, #e2e8f0); min-height: 60px; margin-top: 4px;"></div>
                        </div>
                        <div>
                            <label style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase;">Kendala & Hambatan</label>
                            <div id="lbObstacle" style="font-size: 13px; background: #FAFAFA; padding: 12px; border-radius: 8px; border: 1px solid var(--border, #e2e8f0); min-height: 60px; margin-top: 4px;"></div>
                        </div>
                    </div>

                    <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                        <div>
                            <label style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase;">Solusi Diterapkan</label>
                            <div id="lbSolution" style="font-size: 13px; background: #FAFAFA; padding: 12px; border-radius: 8px; border: 1px solid var(--border, #e2e8f0); min-height: 60px; margin-top: 4px;"></div>
                        </div>
                        <div>
                            <label style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase;">Rencana Selanjutnya</label>
                            <div id="lbNextPlan" style="font-size: 13px; background: #FAFAFA; padding: 12px; border-radius: 8px; border: 1px solid var(--border, #e2e8f0); min-height: 60px; margin-top: 4px;"></div>
                        </div>
                    </div>

                    <div id="lbAttachmentBox" style="margin-bottom: 24px; display: none;">
                        <label style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase;">Lampiran File Pendukung</label>
                        <div style="margin-top: 6px;">
                            <a id="lbAttachmentLink" href="" target="_blank" class="btn-sm btn-outline">
                                <i class="fa-solid fa-paperclip"></i> <span>Unduh Lampiran</span>
                            </a>
                        </div>
                    </div>

                    <hr style="border: none; border-top: 1px dashed var(--border, #e2e8f0); margin: 24px 0;">

                    <h4 style="font-size: 14px; font-weight: 700; color: var(--primary, #0A5F63); margin-bottom: 16px;">
                        <i class="fa-solid fa-stamp"></i> Keputusan Validasi Pembimbing
                    </h4>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12.5px; font-weight: 600; margin-bottom: 8px;">Status Verifikasi *</label>
                        <!-- Form juga memakai custom dropdown -->
                        <select name="status" id="lbReviewStatus" class="custom-filter-select" required>
                            <option value="approved">Disetujui (Approved)</option>
                            <option value="revision_required">Perlu Revisi (Revision Required)</option>
                            <option value="rejected">Ditolak (Rejected)</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12.5px; font-weight: 600; margin-bottom: 8px;">Catatan Review / Alasan Revisi</label>
                        <textarea name="review_note" id="lbReviewNote" rows="2" style="width: 100%; padding: 12px; border-radius: 8px; border: 1.5px solid var(--border, #e2e8f0); outline: none; font-size: 13px; font-family: inherit; resize: vertical;" placeholder="Tuliskan catatan evaluasi atau alasan perbaikan..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-outline" onclick="closeLogbookModal()">Batal</button>
                    <button type="submit" class="btn-sm btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Validasi</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Memastikan form otomatis submit jika opsi custom dropdown di filter diklik.
    document.querySelectorAll('#filterForm .custom-filter-select').forEach(select => {
        const wrapper = select.nextElementSibling;
        if(wrapper && wrapper.classList.contains('custom-select-wrapper')) {
            wrapper.querySelectorAll('.custom-option').forEach(opt => {
                opt.addEventListener('click', () => {
                    document.getElementById('filterForm').submit();
                });
            });
        }
    });
});

// Fungsi bantuan untuk men-sinkronisasi nilai pada custom dropdown modal
function updateCustomDropdownValue(selectId, value) {
    const select = document.getElementById(selectId);
    if (!select) return;
    
    // Set value asli
    select.value = value;
    
    const wrapper = select.nextElementSibling;
    if (wrapper && wrapper.classList.contains('custom-select-wrapper')) {
        const activeOptionText = select.options[select.selectedIndex].text;
        // Update label
        const triggerSpan = wrapper.querySelector('.custom-select-trigger span');
        if (triggerSpan) triggerSpan.textContent = activeOptionText;
        
        // Update state 'selected'
        wrapper.querySelectorAll('.custom-option').forEach(opt => {
            if(opt.textContent === activeOptionText) {
                opt.classList.add('selected');
            } else {
                opt.classList.remove('selected');
            }
        });
    }
}

function openLogbookModal(log) {
    const modal = document.getElementById('logbookModal');
    const form = document.getElementById('logbookReviewForm');

    form.action = "{{ url('/pembimbing/logbook') }}/" + log.id_logbook + "/review";

    document.getElementById('lbMhsName').textContent = log.mahasiswa ? log.mahasiswa.nama_lengkap : '-';
    document.getElementById('lbMhsNim').textContent = log.mahasiswa ? 'NIM: ' + log.mahasiswa.nim : '-';
    document.getElementById('lbDate').textContent = log.logbook_date ? log.logbook_date.split('T')[0] : '-';
    document.getElementById('lbTime').textContent = (log.start_time || '08:00') + ' - ' + (log.end_time || '16:00') + ' WIB';

    document.getElementById('lbTitle').textContent = log.title || '-';
    document.getElementById('lbActivity').textContent = log.activity || '-';
    document.getElementById('lbResult').textContent = log.result || '-';
    document.getElementById('lbObstacle').textContent = log.obstacle || '-';
    document.getElementById('lbSolution').textContent = log.solution || '-';
    document.getElementById('lbNextPlan').textContent = log.next_plan || '-';

    const attachBox = document.getElementById('lbAttachmentBox');
    const attachLink = document.getElementById('lbAttachmentLink');
    if (log.attachment_path) {
        attachBox.style.display = 'block';
        const cleanPath = log.attachment_path.replace(/^\//, '');
        attachLink.href = cleanPath.startsWith('uploads/') ? ("{{ url('/') }}/" + cleanPath) : ("{{ url('/uploads/logbook') }}/" + cleanPath);
        attachLink.querySelector('span').textContent = log.attachment_name || 'Lihat Lampiran';
    } else {
        attachBox.style.display = 'none';
    }

    // Set nilai pada select status & jalankan update pada custom dropdown interface
    const initialStatus = log.status === 'submitted' ? 'approved' : log.status;
    updateCustomDropdownValue('lbReviewStatus', initialStatus);
    
    document.getElementById('lbReviewNote').value = log.review_note || '';

    modal.classList.add('active');
}

function closeLogbookModal() {
    document.getElementById('logbookModal').classList.remove('active');
}
</script>
@endpush