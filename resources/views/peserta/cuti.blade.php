@extends('peserta.layouts.app')

@section('title', 'Pengajuan Cuti & Izin')
@section('page_title', 'Pengajuan Cuti & Izin')
@section('page_subtitle', 'Ajukan permohonan ketidakhadiran resmi untuk sakit, kegiatan akademik, atau cuti darurat.')

@push('styles')
<style>
    /* ==========================================================================
       MODUL CUTI & IZIN - TEMA CLEAN DASHBOARD
       ========================================================================== */

    /* ANIMATIONS */
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-20px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* ================= HEADER DASHBOARD ================= */
    .dashboard-header {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 16px; margin-bottom: 24px;
        animation: fadeIn 0.4s ease-out;
    }
    .header-info h2 {
        font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px 0;
    }
    .header-info p {
        font-size: 13.5px; color: var(--muted); margin: 0; line-height: 1.4;
    }

    .btn-pill {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
        background: var(--primary); border: 1px solid var(--primary); border-radius: 8px;
        font-size: 13.5px; font-weight: 700; color: #fff; text-decoration: none; transition: all 0.2s ease; cursor: pointer;
    }
    .btn-pill:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); border-color: var(--primary-dark); }

    /* Layout Kartu Utama */
    .card {
        background: #FFFFFF; border: 1px solid var(--border); border-radius: 16px;
        padding: 24px; display: flex; flex-direction: column;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); color: var(--text);
        animation: fadeInUp 0.4s ease-out both;
    }
    .card-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 20px; border-bottom: 1px dashed var(--page); padding-bottom: 16px; }
    .card-title { margin: 0; font-size: 16px; font-weight: 800; color: var(--text); display: flex; align-items: center; gap: 10px; }
    .card-title i { color: var(--primary); font-size: 18px; }

    /* Action Buttons Mini */
    .btn-custom {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 40px; padding: 0 16px; border-radius: 8px; font-size: 13.5px; font-weight: 700;
        border: 1px solid transparent; cursor: pointer; transition: all 0.2s ease;
    }
    .btn-outline { background: #FFFFFF; color: var(--text); border-color: var(--border); }
    .btn-outline:hover { background: var(--page); }

    .btn-primary { background: var(--primary); color: #fff; border-color: var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); }

    .btn-danger-soft { background: #FEF2F2; color: #DC2626; border-color: #FECACA; }
    .btn-danger-soft:hover { background: #FEE2E2; border-color: #FCA5A5; }

    .btn-sm { height: 32px; padding: 0 12px; font-size: 12px; border-radius: 6px; }
    .btn-view { height: 26px; padding: 0 10px; font-size: 11px; border-radius: 6px; flex-shrink: 0; }

    /* Badges */
    .badge-label {
        display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 9999px;
        font-size: 11.5px; font-weight: 700; border: 1px solid transparent; white-space: nowrap;
    }
    .badge-approved { background: #DCFCE7; color: #166534; border-color: #BBF7D0; }
    .badge-under_review { background: #FEF9C3; color: #854D0E; border-color: #FEF08A; }
    .badge-submitted { background: var(--primary-soft); color: var(--primary-dark); border-color: rgba(0,0,0,0.1); }
    .badge-rejected, .badge-cancelled { background: #FEE2E2; color: #991B1B; border-color: #FECACA; }
    .badge-draft { background: #F1F5F9; color: #475569; border-color: #E2E8F0; }

    /* ==========================================================================
       TABEL RESPONSIVE ADMIN STANDARD
       ========================================================================== */
    .table-container {
        width: 100%;
        overflow-x: auto;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: #FFFFFF;
    }
    .admin-table { width: 100%; border-collapse: collapse; min-width: 1000px; }
    .admin-table th {
        text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700;
        color: var(--muted); background: #F8FAFC; border-bottom: 1px solid var(--border); text-transform: uppercase; letter-spacing: 0.05em;
    }
    .admin-table td {
        padding: 14px 16px; font-size: 13.5px; color: var(--text); border-bottom: 1px solid var(--page);
        vertical-align: middle;
    }
    .admin-table tr:hover td { background: #F8FAFC; }

    /* Wrapper Teks dan Tombol View */
    .td-action-group { display: flex; gap: 8px; align-items: center; justify-content: flex-start; max-width: 100%; overflow: hidden; }
    .note-truncate { flex: 1; min-width: 50px; max-width: 160px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--muted); font-size: 13px; line-height: 1.4; }

    .empty-state { text-align: center; padding: 48px 20px; color: var(--muted); }
    .empty-state i { font-size: 40px; margin-bottom: 16px; color: #CBD5E1; opacity: 0.6; }
    .empty-state p { margin: 0; font-size: 14px; font-weight: 500; }

    /* Pager */
    .pager-container { margin-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }

    /* ---------- Form & Input Modal ---------- */
    .form-group { margin-bottom: 16px; }
    .form-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
    .input-box {
        width: 100%; padding: 12px 16px; font-family: inherit; font-size: 14px; color: var(--text);
        background: #fff; border: 1.5px solid var(--border); border-radius: 12px;
        transition: all 0.2s ease;
    }
    textarea.input-box { resize: vertical; min-height: 80px; }
    .input-box:focus { outline: none; border-color: var(--primary); background: #FFFFFF; box-shadow: 0 0 0 3px var(--primary-soft); }
    .input-box::placeholder { color: #94A3B8; }
    .form-group small { color: var(--muted); font-size: 11.5px; margin-top: 6px; display: block; font-weight: 500; line-height: 1.4; }

    /* Modals Animation */
    .modal-backdrop:not(.d-none) { animation: fadeIn 0.25s ease-out forwards; }
    .modal-backdrop:not(.d-none) .modal-box { animation: slideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    /* ==========================================================================
       TRANSFORM TABEL MENJADI KARTU DI MOBILE
       ========================================================================== */
    @media (max-width: 992px) {
        .table-container { border: none; background: transparent; overflow-x: visible; }
        .admin-table { min-width: auto; }
        .admin-table, .admin-table tbody, .admin-table tr, .admin-table td { display: block; width: 100%; box-sizing: border-box; }
        .admin-table thead { display: none; }

        .admin-table tr {
            background: #FFFFFF; margin-bottom: 16px; border-radius: 12px;
            border: 1px solid var(--border); box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            padding: 8px 16px;
        }

        .admin-table td {
            display: flex; justify-content: space-between; align-items: center; gap: 16px;
            padding: 12px 0; border-bottom: 1px dashed var(--page); text-align: right;
            white-space: normal;
        }
        .admin-table td:last-child { border-bottom: none; }
        .admin-table td::before {
            content: attr(data-label); font-size: 11.5px; font-weight: 800; color: var(--muted);
            text-align: left; flex-shrink: 0; text-transform: uppercase; letter-spacing: 0.05em;
        }
        .admin-table td > * { max-width: 60%; text-align: right; }

        .td-action-group { justify-content: flex-end; max-width: 65%; }
        .note-truncate { max-width: 120px; text-align: right; }

        /* Baris Status Kosong pada Mobile */
        .admin-table tr.empty-row td { text-align: center; justify-content: center; border: none; padding: 24px 10px; }
        .admin-table tr.empty-row td::before { display: none; }
        .admin-table tr.empty-row td > * { max-width: 100%; text-align: center; }
    }

    @media (max-width: 768px) {
        .dashboard-header { flex-direction: column; align-items: flex-start; gap: 16px; }
        .btn-pill { width: 100%; justify-content: center; }
        .form-row { grid-template-columns: 1fr; gap: 0; }
    }
</style>
@endpush

@section('content')

<!-- ================= HEADER DASHBOARD ================= -->
<div class="dashboard-header">
    <div class="header-info">
        <h2>Pengajuan Cuti & Izin</h2>
        <p>Lakukan permohonan izin ketidakhadiran resmi secara digital kepada pembimbing Anda.</p>
    </div>
    <button type="button" class="btn-pill" onclick="openCutiModal()">
        <i class="fa-solid fa-plus"></i> Buat Pengajuan Baru
    </button>
</div>

<!-- ================= KARTU TABEL RIWAYAT ================= -->
<section class="card">
    <div class="card-head">
        <h3 class="card-title"><i class="fa-solid fa-clipboard-list"></i> Riwayat Pengajuan Izin Anda</h3>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>TGL PENGAJUAN</th>
                    <th>JENIS IZIN / CUTI</th>
                    <th>PERIODE TANGGAL</th>
                    <th>DURASI</th>
                    <th>ALASAN</th>
                    <th>LAMPIRAN</th>
                    <th>STATUS</th>
                    <th>CATATAN PEMBIMBING</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves ?? [] as $item)
                    @php
                        $durasi = \Carbon\Carbon::parse($item->start_date)->diffInDays(\Carbon\Carbon::parse($item->end_date)) + 1;
                    @endphp
                    <tr>
                        <td data-label="TGL PENGAJUAN"><strong>{{ $item->created_at->format('d/m/Y') }}</strong> <br><small style="color: var(--muted);">{{ $item->created_at->format('H:i') }} WIB</small></td>

                        <td data-label="JENIS IZIN / CUTI">
                            <strong style="color: var(--text);">{{ \App\Models\LeaveRequest::getLeaveTypeLabel($item->leave_type) }}</strong>
                        </td>

                        <td data-label="PERIODE TANGGAL" style="font-weight: 600;">
                            {{ $item->start_date->translatedFormat('d M Y') }} <span style="color: var(--muted); font-size: 11.5px; margin: 0 4px; font-weight: 500;">s/d</span> {{ $item->end_date->translatedFormat('d M Y') }}
                        </td>

                        <td data-label="DURASI">{{ $durasi }} Hari</td>

                        <td data-label="ALASAN">
                            <div class="td-action-group">
                                <span class="note-truncate">{{ $item->reason }}</span>
                                <button type="button" class="btn-custom btn-outline btn-view" data-note="{{ $item->reason }}" onclick="viewNoteModal(this.getAttribute('data-note'), 'Alasan Lengkap Izin/Cuti')"><i class="fa-solid fa-eye"></i> View</button>
                            </div>
                        </td>

                        <td data-label="LAMPIRAN">
                            @if($item->attachment_path)
                                <a href="{{ asset(str_starts_with($item->attachment_path, 'uploads/') ? $item->attachment_path : 'uploads/leaves/' . $item->attachment_path) }}" target="_blank" style="color: var(--primary); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-paperclip"></i> Dokumen
                                </a>
                            @else
                                <span style="color: var(--muted);"><i class="fa-solid fa-link-slash"></i> -</span>
                            @endif
                        </td>

                        <td data-label="STATUS">
                            @php
                                $stat = $item->status;
                                $bcs = 'badge-draft'; $tcs = 'Draft';
                                if($stat == 'submitted') { $bcs = 'badge-submitted'; $tcs = 'Menunggu Review'; }
                                elseif($stat == 'under_review') { $bcs = 'badge-under_review'; $tcs = 'Sedang Diperiksa'; }
                                elseif($stat == 'approved') { $bcs = 'badge-approved'; $tcs = 'Disetujui'; }
                                elseif($stat == 'rejected') { $bcs = 'badge-rejected'; $tcs = 'Ditolak'; }
                                elseif($stat == 'cancelled') { $bcs = 'badge-cancelled'; $tcs = 'Dibatalkan'; }
                            @endphp
                            <span class="badge-label {{ $bcs }}">{{ $tcs }}</span>
                        </td>

                        <td data-label="CATATAN PEMBIMBING">
                            @if($item->review_note)
                                <div class="td-action-group">
                                    <span class="note-truncate">{{ $item->review_note }}</span>
                                    <button type="button" class="btn-custom btn-outline btn-view" data-note="{{ $item->review_note }}" onclick="viewNoteModal(this.getAttribute('data-note'), 'Catatan & Respon Pembimbing')"><i class="fa-solid fa-eye"></i> View</button>
                                </div>
                            @else
                                <span style="color: var(--muted);">-</span>
                            @endif
                        </td>

                        <td data-label="AKSI">
                            @if($item->status === 'submitted')
                                <form action="{{ route('peserta.cuti.cancel', $item->id_leave) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini secara permanen?')">
                                    @csrf
                                    <button type="submit" class="btn-custom btn-sm btn-danger-soft">
                                        <i class="fa-solid fa-xmark"></i> Batal
                                    </button>
                                </form>
                            @else
                                <span style="color: var(--muted);">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="fa-regular fa-calendar-xmark"></i>
                                <h3>Belum Ada Pengajuan Izin/Cuti</h3>
                                <p>Riwayat pengajuan ketidakhadiran Anda masih kosong.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(($leaves ?? null) && $leaves->hasPages())
    <div class="pager-container">
        {{ $leaves->links() }}
    </div>
    @endif
</section>

<!-- ================= MODAL PENGAJUAN CUTI / IZIN ================= -->
<div class="modal-backdrop d-none" id="cutiModal">
    <div class="modal-box" style="max-width: 640px; text-align: left; padding: 0; border-radius: 16px; background: #fff;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border);">
            <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: var(--text);">
                <i class="fa-solid fa-calendar-plus" style="color: var(--primary); margin-right: 6px;"></i> Form Pengajuan Ketidakhadiran
            </h3>
            <button type="button" onclick="closeCutiModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); transition: 0.2s;">&times;</button>
        </div>

        <form action="{{ route('peserta.cuti.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="padding: 24px; max-height: 70vh; overflow-y: auto;">
                <div class="form-group">
                    <label for="leave_type">Jenis Pengajuan Izin / Cuti *</label>
                    <select id="leave_type" name="leave_type" class="input-box" required>
                        <option value="personal_leave">Cuti Kepentingan Pribadi / Keluarga</option>
                        <option value="sick_leave">Izin Sakit (Wajib melampirkan Surat Dokter)</option>
                        <option value="academic_leave">Izin Kegiatan Akademik (Sidang / Ujian / Kampus)</option>
                        <option value="official_permission">Izin Kegiatan Dinas / Resmi Perusahaan</option>
                        <option value="emergency">Izin Keadaan Darurat</option>
                        <option value="other">Jenis Izin Lainnya</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="leave_start">Tanggal Mulai Izin *</label>
                        <input type="date" id="leave_start" name="start_date" min="{{ date('Y-m-d') }}" required class="input-box">
                    </div>
                    <div class="form-group">
                        <label for="leave_end">Tanggal Selesai Izin *</label>
                        <input type="date" id="leave_end" name="end_date" min="{{ date('Y-m-d') }}" required class="input-box">
                    </div>
                </div>

                <div class="form-group">
                    <label for="leave_reason">Alasan Lengkap Ketidakhadiran *</label>
                    <textarea id="leave_reason" name="reason" rows="3" class="input-box" placeholder="Jelaskan secara rinci keperluan atau keterangan medis sakit..." required minlength="10"></textarea>
                </div>

                <div class="form-group">
                    <label for="leave_emergency">Kontak Darurat Selama Masa Izin</label>
                    <input type="text" id="leave_emergency" name="emergency_contact" value="{{ $mhs->emergency_contact_phone ? $mhs->emergency_contact_name . ' (' . $mhs->emergency_contact_phone . ')' : '' }}" placeholder="Nama Wali & No HP (Contoh: Bapak Supardi - 08123456789)" class="input-box">
                </div>

                <div class="form-group">
                    <label for="leave_file">Lampiran Dokumen Bukti Pendukung</label>
                    <input type="file" id="leave_file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="input-box" style="padding: 8px 16px; background: #fff;">
                    <small>Wajib dilampirkan untuk pengajuan izin sakit (Surat Dokter). Format PDF/JPG/PNG maksimal 5 MB.</small>
                </div>
            </div>

            <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 10px; background: var(--page); border-radius: 0 0 16px 16px;">
                <button type="button" class="btn-custom btn-outline" onclick="closeCutiModal()">Batal</button>
                <button type="submit" class="btn-custom btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Permohonan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Text Viewer (Catatan/Alasan Panjang) -->
<div class="modal-backdrop d-none" id="modalNoteViewer">
    <div class="modal-box" style="max-width: 420px; text-align: left; padding: 0; border-radius: 16px; background: #fff;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-bottom: 1px solid var(--border);">
            <h3 id="noteViewerTitle" style="margin: 0; font-size: 15px; font-weight: 700; color: var(--text);"><i class="fa-solid fa-note-sticky" style="color: var(--primary);"></i> Detail</h3>
            <button type="button" onclick="closeNoteModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); transition: 0.2s;">&times;</button>
        </div>
        <div style="padding: 24px; color: var(--text); font-size: 14px; line-height: 1.6; word-wrap: break-word; overflow-wrap: break-word;" id="noteViewerContent">
        </div>
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: var(--page); border-radius: 0 0 16px 16px;">
            <button type="button" class="btn-custom btn-outline" style="width: 100%;" onclick="closeNoteModal()">Tutup</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openCutiModal() {
        document.getElementById('cutiModal').classList.remove('d-none');
    }
    function closeCutiModal() {
        document.getElementById('cutiModal').classList.add('d-none');
    }

    // Modal untuk membaca text panjang pada tabel (Alasan / Catatan)
    function viewNoteModal(text, title) {
        document.getElementById('noteViewerTitle').innerHTML = '<i class="fa-solid fa-note-sticky" style="color: var(--primary); margin-right: 6px;"></i> ' + title;
        document.getElementById('noteViewerContent').innerText = text;
        document.getElementById('modalNoteViewer').classList.remove('d-none');
    }
    function closeNoteModal() {
        document.getElementById('modalNoteViewer').classList.add('d-none');
    }
</script>
@endpush
