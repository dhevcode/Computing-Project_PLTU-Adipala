@extends('peserta.layout')

@section('title', 'Pengajuan Cuti & Izin')

@section('content')

<style>
    /* ==========================================================================
       CUTI & IZIN - PANEL KACA & KARTU TABEL (TEMA DASHBOARD & ABSENSI)
       ========================================================================== */
    .ct-page {
        box-sizing: border-box; width: 100%; max-width: 1200px;
        margin-left: auto; margin-right: auto; color: var(--text);
    }
    .ct-page *, .ct-page *::before, .ct-page *::after { box-sizing: border-box; }

    /* ---------- Panel Utama ---------- */
    .ct-panel {
        background: var(--panel);
        backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 40px; padding: 30px;
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.22);
    }
    .ct-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 18px 24px; margin-bottom: 26px; }
    .ct-top-text { flex: 1 1 360px; min-width: 0; }
    .ct-title { margin: 0; font-size: clamp(24px, 3vw, 36px); font-weight: 700; letter-spacing: -0.025em; line-height: 1.15; color: #ffffff; }
    .ct-sub { margin: 10px 0 0; font-size: 13.5px; line-height: 1.6; color: var(--muted); font-weight: 500; }
    .ct-controls { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }

    /* ---------- Tombol Dasar ---------- */
    .ct-page .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 40px; padding: 0 20px; border-radius: 9999px; font-family: inherit; font-size: 13.5px; font-weight: 600; line-height: 1;
        border: 1px solid transparent; cursor: pointer; text-decoration: none; white-space: nowrap;
        transition: background .2s ease, border-color .2s ease, transform .2s ease, color .2s ease;
    }
    .ct-page .btn:hover { transform: translateY(-1px); }
    .ct-page .btn:focus-visible, .ct-page .modal-close:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
    .ct-page .btn-sm { height: 32px; padding: 0 14px; font-size: 12px; }
    .ct-page .btn-primary { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }
    .ct-page .btn-primary:hover { background: #d6f55a; border-color: #d6f55a; }
    .ct-page .btn-outline { background: transparent; color: var(--text); border-color: var(--border-strong); }
    .ct-page .btn-outline:hover { background: var(--card-2-hover); color: #ffffff; }

    /* ---------- Kartu & Tabel ---------- */
    .ct-card {
        background: var(--card); backdrop-filter: blur(16px) saturate(130%); -webkit-backdrop-filter: blur(16px) saturate(130%);
        border: 1px solid var(--border); border-radius: 26px; padding: 22px; min-width: 0;
        display: flex; flex-direction: column; color: var(--text);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
    }
    .ct-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 20px; }
    .ct-card-title { margin: 0; font-size: 16px; font-weight: 700; letter-spacing: -0.01em; color: #ffffff; display: flex; align-items: center; gap: 10px; }
    .ct-card-title i { color: var(--accent); }

.ct-table-wrap { width: 100%; border: 1px solid var(--border); border-radius: 20px; }
    .ct-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .ct-table th {
        text-align: left; padding: 14px 12px;
        font-family: var(--font-num); font-size: 10.5px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase;
        color: var(--muted); background: rgba(255, 255, 255, 0.05); border-bottom: 1px solid var(--border);
    }
    .ct-table td { padding: 14px 12px; font-size: 13px; color: var(--text-2); vertical-align: top; border-bottom: 1px solid var(--border); word-break: break-word; }
    .ct-table tr:last-child td { border-bottom: none; }
    .ct-table tbody tr:hover td { background: var(--card-2); }
    .ct-table td strong { color: #ffffff; font-weight: 600; }
    .ct-empty { text-align: center; padding: 36px 20px !important; color: var(--muted) !important; }

    /* ---------- Badge Status ---------- */
    .ct-badge {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px;
        font-size: 11.5px; font-weight: 600; line-height: 1.4; white-space: nowrap;
        border: 1px solid var(--border-strong); background: var(--card-2); color: var(--text-2);
    }
    .badge-approved { background: var(--ok-bg); color: var(--ok); border-color: var(--ok-bd); }
    .badge-under_review { background: var(--warn-bg); color: var(--warn); border-color: var(--warn-bd); }
    .badge-submitted { background: var(--info-bg); color: var(--info); border-color: var(--info-bd); }
    .badge-rejected, .badge-cancelled { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-bd); }

    /* ---------- Paginasi ---------- */
    .ct-pager { margin-top: 20px; }
    .ct-pager nav[role="navigation"] > div:first-child { display: none; }
    .ct-pager nav[role="navigation"] svg { width: 16px; height: 16px; }
    .ct-pager nav[role="navigation"] p { font-size: 12px; color: var(--muted); margin: 0; }
    .ct-pager .pagination { display: flex; flex-wrap: wrap; gap: 6px; list-style: none; padding: 0; margin: 0; }
    .ct-pager a, .ct-pager .page-link, .ct-pager span[aria-current] > span, .ct-pager span[aria-disabled] > span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 12px;
        border-radius: 9999px; font-size: 12.5px; font-weight: 600; text-decoration: none;
        color: var(--text-2); background: var(--card-2); border: 1px solid var(--border);
    }
    .ct-pager .page-item.active .page-link, .ct-pager span[aria-current] > span { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }

    /* ---------- Modal & Form ---------- */
    .ct-page .form-group { margin-bottom: 16px; }
    .ct-page .form-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .ct-page .form-group > label { display: block; margin-bottom: 8px; font-size: 12.5px; font-weight: 600; color: var(--text-2); }
    .ct-page .form-control, .ct-page .form-select {
        width: 100%; padding: 11px 16px; font-family: inherit; font-size: 13.5px; color: var(--text);
        background: rgba(255, 255, 255, 0.07); border: 1px solid var(--border-strong); border-radius: 16px; color-scheme: dark;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .ct-page textarea.form-control { resize: vertical; line-height: 1.5; }
    .ct-page .form-control::placeholder { color: var(--muted); }
    .ct-page .form-control:focus, .ct-page .form-select:focus { outline: none; border-color: var(--accent); background: rgba(255, 255, 255, 0.10); box-shadow: 0 0 0 3px var(--accent-soft); }
    .ct-page .form-select option { background: #1f2121; color: #f6f6f4; }
    .ct-page input[type="file"].form-control { padding: 9px 14px; }
    .ct-page input[type="file"]::file-selector-button {
        margin-right: 12px; padding: 6px 14px; border-radius: 9999px; border: 1px solid var(--border-strong);
        background: var(--card-2-hover); color: var(--text); font-family: inherit; font-size: 12px; font-weight: 600; cursor: pointer;
    }
    .ct-page .form-group small { color: var(--muted); font-size: 11.5px; margin-top: 6px; display: block; }

    .ct-page .modal-overlay {
        position: fixed; inset: 0; z-index: 2000; display: none; align-items: center; justify-content: center; padding: 20px;
        background: rgba(0, 0, 0, 0.58); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    }
    .ct-page .modal-overlay.active { display: flex; }
    .ct-page .modal-card {
        width: 100%; max-width: 640px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden;
        background: rgba(34, 36, 36, 0.94); backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 32px; color: var(--text);
        box-shadow: 0 40px 90px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.18);
    }
    .ct-page .modal-card form { display: flex; flex-direction: column; min-height: 0; flex: 1; }
    .ct-page .modal-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 20px 24px; border-bottom: 1px solid var(--border); }
    .ct-page .modal-header h3 { margin: 0; font-size: 16.5px; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 10px; }
    .ct-page .modal-header h3 i { color: var(--accent); }
    .ct-page .modal-close {
        width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0; cursor: pointer; font-size: 20px; line-height: 1; color: var(--text);
        display: inline-flex; align-items: center; justify-content: center; background: var(--card-2); border: 1px solid var(--border-strong); transition: background .2s;
    }
    .ct-page .modal-close:hover { background: var(--card-2-hover); }
    .ct-page .modal-body { padding: 22px 24px; overflow-y: auto; }
    .ct-page .modal-footer { display: flex; justify-content: flex-end; align-items: center; flex-wrap: wrap; gap: 10px; padding: 18px 24px; border-top: 1px solid var(--border); background: rgba(0,0,0,0.1); }

    @media (max-width: 768px) {
        .ct-panel { padding: 18px; border-radius: 30px; }
        .ct-card { padding: 18px; border-radius: 20px; }
        .ct-page .form-row { grid-template-columns: 1fr; gap: 0; }
        .ct-page .modal-card { border-radius: 26px; }
        .ct-page .modal-header, .ct-page .modal-body, .ct-page .modal-footer { padding-left: 18px; padding-right: 18px; }
    }
</style>

<div class="ct-page">
<div class="ct-panel">

    <!-- ================= KEPALA PANEL ================= -->
    <header class="ct-top">
        <div class="ct-top-text">
            <h1 class="ct-title">Pengajuan Cuti & Izin Magang</h1>
            <p class="ct-sub">Ajukan permohonan ketidakhadiran resmi untuk sakit, kegiatan akademik, atau cuti darurat.</p>
        </div>
        <div class="ct-controls">
            <button type="button" class="btn btn-primary" onclick="openCutiModal()">
                <i class="fa-solid fa-plus"></i> Buat Pengajuan Baru
            </button>
        </div>
    </header>

    <!-- ================= KARTU TABEL RIWAYAT ================= -->
    <section class="ct-card">
        <div class="ct-head">
            <h3 class="ct-card-title"><i class="fa-solid fa-clipboard-list"></i> Riwayat Pengajuan Cuti & Izin</h3>
        </div>

        <div class="ct-table-wrap">
            <table class="ct-table">
                <thead>
                    <tr>
                        <th>Tgl Pengajuan</th>
                        <th>Jenis Izin / Cuti</th>
                        <th>Periode Tanggal</th>
                        <th>Durasi</th>
                        <th>Alasan</th>
                        <th>Lampiran Bukti</th>
                        <th>Status</th>
                        <th>Catatan Pembimbing</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $item)
                        @php
                            $durasi = \Carbon\Carbon::parse($item->start_date)->diffInDays(\Carbon\Carbon::parse($item->end_date)) + 1;
                        @endphp
                        <tr>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ \App\Models\LeaveRequest::getLeaveTypeLabel($item->leave_type) }}</strong>
                            </td>
                            <td>
                                {{ $item->start_date->translatedFormat('d M Y') }} <span style="color: var(--muted); font-size: 11px; margin: 0 4px;">s/d</span> {{ $item->end_date->translatedFormat('d M Y') }}
                            </td>
                            <td>{{ $durasi }} Hari</td>
                            <td style="max-width: 220px; font-size: 12.5px; line-height: 1.5;">{{ $item->reason }}</td>
                            <td>
                                @if($item->attachment_path)
                                    <a href="{{ asset($item->attachment_path) }}" target="_blank" style="color: var(--accent); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-paperclip"></i> Dokumen
                                    </a>
                                @else
                                    <span style="color: var(--muted);"><i class="fa-solid fa-link-slash"></i> -</span>
                                @endif
                            </td>
                            <td>
                                <span class="ct-badge badge-{{ $item->status }}">
                                    {{ match($item->status) {
                                        'submitted' => 'Menunggu Review',
                                        'under_review' => 'Sedang Diperiksa',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        'cancelled' => 'Dibatalkan',
                                        default => $item->status,
                                    } }}
                                </span>
                            </td>
                            <td style="max-width: 180px; font-size: 12px; color: var(--muted); line-height: 1.4;">
                                {{ $item->review_note ?: '-' }}
                            </td>
                            <td>
                                @if($item->status === 'submitted')
                                    <form action="{{ route('peserta.cuti.cancel', $item->id_leave) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline" style="color: var(--danger); border-color: var(--danger-bd); background: var(--danger-bg);">
                                            <i class="fa-solid fa-xmark"></i> Batal
                                        </button>
                                    </form>
                                @else
                                    <span style="color: var(--border-strong);">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="ct-empty">
                                <i class="fa-regular fa-calendar-xmark" style="font-size: 32px; margin-bottom: 12px; color: var(--accent); opacity: 0.6; display: block;"></i>
                                <p style="margin: 0; font-size: 13.5px;">Belum ada pengajuan cuti atau izin.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ct-pager">
            {{ $leaves->links() }}
        </div>
    </section>
</div>

    <!-- ================= MODAL PENGAJUAN CUTI / IZIN ================= -->
    <div class="modal-overlay" id="cutiModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fa-regular fa-calendar-plus"></i> Form Pengajuan Cuti & Izin</h3>
                <button type="button" class="modal-close" onclick="closeCutiModal()">&times;</button>
            </div>
            <form action="{{ route('peserta.cuti.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="leave_type">Jenis Pengajuan Ketidakhadiran *</label>
                        <select id="leave_type" name="leave_type" class="form-select" required>
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
                            <label for="leave_start">Tanggal Mulai Izin / Cuti *</label>
                            <input type="date" id="leave_start" name="start_date" min="{{ date('Y-m-d') }}" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="leave_end">Tanggal Selesai Izin / Cuti *</label>
                            <input type="date" id="leave_end" name="end_date" min="{{ date('Y-m-d') }}" required class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="leave_reason">Alasan Lengkap *</label>
                        <textarea id="leave_reason" name="reason" rows="3" class="form-control" placeholder="Jelaskan keperluan atau keterangan medis sakit..." required minlength="10"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="leave_emergency">Kontak yang Dapat Dihubungi Selama Izin</label>
                        <input type="text" id="leave_emergency" name="emergency_contact" value="{{ $mhs->emergency_contact_phone ? $mhs->emergency_contact_name . ' (' . $mhs->emergency_contact_phone . ')' : '' }}" placeholder="Nama & No HP (Contoh: Bapak Joko - 08123456789)" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="leave_file">Lampiran Bukti (Surat Dokter / Surat Izin Kampus)</label>
                        <input type="file" id="leave_file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="form-control">
                        <small>Wajib untuk izin sakit. Format PDF/JPG/PNG. Maksimal 5 MB.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeCutiModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane"></i> Ajukan Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function openCutiModal() {
        document.getElementById('cutiModal').classList.add('active');
    }
    function closeCutiModal() {
        document.getElementById('cutiModal').classList.remove('active');
    }
</script>
@endpush
