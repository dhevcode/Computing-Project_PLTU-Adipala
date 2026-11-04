@extends('peserta.layouts.app')

@section('title', 'Logbook Kegiatan')
@section('page_title', 'Logbook Harian')
@section('page_subtitle', 'Dokumentasikan aktivitas, pencapaian, dan kendala magang Anda di PLTU Adipala.')

@push('styles')
<style>
    /* ==========================================================================
       MODUL LOGBOOK - TEMA CLEAN DASHBOARD (WARNA SINKRON DENGAN TEMA UTAMA)
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

    /* ================= TABS NAVIGASI ================= */
    .filter-tabs {
        display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 24px;
        animation: fadeIn 0.5s ease-out;
        border-bottom: 2px solid var(--page); padding-bottom: 12px;
    }
    .filter-tab {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 18px;
        border-radius: 8px; font-size: 13.5px; font-weight: 700; text-decoration: none; white-space: nowrap;
        background: transparent; color: var(--muted);
        transition: all 0.2s ease;
    }
    .filter-tab:hover { background: var(--page); color: var(--text); }
    .filter-tab.active { background: var(--primary-soft); color: var(--primary); }

    /* ================= KARTU LOGBOOK ================= */
    .logbook-grid { display: flex; flex-direction: column; gap: 16px; }

    .lb-card {
        background: #fff; border: 1px solid var(--border); border-radius: 16px;
        padding: 24px; display: flex; flex-direction: column;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); color: var(--text); position: relative; overflow: hidden;
        animation: fadeInUp 0.4s ease-out both;
        transition: 0.3s;
    }
    .lb-card:hover { border-color: #CBD5E1; box-shadow: 0 6px 20px rgba(0,0,0,0.05); }

    /* Staggered Animation */
    .logbook-grid > .lb-card:nth-child(1) { animation-delay: 0.1s; }
    .logbook-grid > .lb-card:nth-child(2) { animation-delay: 0.2s; }
    .logbook-grid > .lb-card:nth-child(3) { animation-delay: 0.3s; }

    /* Label & Garis Indikator Kiri */
    .lb-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; }
    .lb-card.status-approved::before { background: #16A34A; }
    .lb-card.status-revision_required::before { background: #EAB308; }
    .lb-card.status-submitted::before { background: var(--primary); }
    .lb-card.status-draft::before { background: #94A3B8; }

    .lb-card-head { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 20px; border-bottom: 1px dashed var(--page); padding-bottom: 16px; }

    .lb-meta { font-size: 12.5px; color: var(--muted); font-weight: 600; margin-bottom: 8px; display: flex; gap: 12px; align-items: center; }
    .lb-meta i { color: var(--primary); }
    .lb-card-title { font-size: 17px; font-weight: 800; color: var(--text); margin: 0; line-height: 1.4; }

    .badge-label { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; font-size: 11.5px; font-weight: 700; border: 1px solid transparent; white-space: nowrap; }
    .badge-approved { background: #DCFCE7; color: #166534; border-color: #BBF7D0; }
    .badge-revision_required { background: #FEF9C3; color: #854D0E; border-color: #FEF08A; }
    .badge-submitted { background: var(--primary-soft); color: var(--primary-dark); border-color: rgba(0,0,0,0.1); }
    .badge-draft { background: #F1F5F9; color: #475569; border-color: #E2E8F0; }

    /* Action Buttons Mini */
    .btn-custom { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 38px; padding: 0 16px; border-radius: 8px; font-size: 13.5px; font-weight: 700; border: 1px solid transparent; cursor: pointer; transition: 0.2s; text-decoration: none; }
    .btn-outline { background: #fff; color: var(--text); border-color: var(--border); }
    .btn-outline:hover { background: var(--page); }

    .btn-outline-primary { background: #fff; color: var(--primary); border-color: var(--primary); }
    .btn-outline-primary:hover { background: var(--primary-soft); }

    .btn-primary-solid { background: var(--primary); color: #fff; border-color: var(--primary); }
    .btn-primary-solid:hover { background: var(--primary-dark); border-color: var(--primary-dark); transform: translateY(-1px); }

    .btn-warning { background: #FFFBEB; color: #D97706; border-color: #FDE68A; }
    .btn-warning:hover { background: #FEF08A; }

    /* Konten Grid Logbook */
    .lb-content-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 20px; }
    .lb-field strong { display: block; color: var(--muted); font-size: 11.5px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 6px; }
    .lb-field p { margin: 0; font-size: 14px; color: var(--text); line-height: 1.6; }
    .lb-field.obstacle strong { color: #D97706; }

    /* Footer & Note Logbook */
    .lb-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--page); padding-top: 16px; flex-wrap: wrap; gap: 12px; }
    .lb-attach { color: var(--primary); text-decoration: none; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; }
    .lb-attach:hover { color: var(--primary-dark); text-decoration: underline; }

    .lb-note { background: #F8FAFC; border: 1px solid var(--border); border-left: 4px solid var(--primary); border-radius: 8px; padding: 12px 16px; color: var(--text); font-size: 13.5px; width: 100%; }

    /* Empty State */
    .lb-empty { text-align: center; padding: 60px 20px; color: var(--muted); background: var(--page); border-radius: 16px; border: 2px dashed var(--border); }
    .lb-empty i { font-size: 48px; margin-bottom: 16px; color: #94A3B8; opacity: 0.5; }
    .lb-empty h3 { font-size: 18px; font-weight: 800; color: var(--text); margin: 0 0 8px 0; }
    .lb-empty p { font-size: 14px; margin: 0; }

    /* ================= FORM MODAL ================= */
    .form-group { margin-bottom: 16px; }
    .form-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
    .input-box { width: 100%; padding: 12px 16px; font-family: inherit; font-size: 14px; color: var(--text); background: #fff; border: 1.5px solid var(--border); border-radius: 12px; transition: all 0.2s ease; }
    textarea.input-box { resize: vertical; min-height: 80px; }
    .input-box:focus { outline: none; border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px var(--primary-soft); }
    .input-box::placeholder { color: #94A3B8; }
    .form-group small { color: var(--muted); font-size: 11.5px; margin-top: 6px; display: block; font-weight: 500; }

    .lb-auto-badge { font-size: 10.5px; color: var(--primary-dark); background: var(--primary-soft); border: 1px solid rgba(0,0,0,0.05); padding: 2px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; float: right; font-weight: 700; }

    .pager-container { margin-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }

    .modal-backdrop:not(.d-none) { animation: fadeIn 0.25s ease-out forwards; }
    .modal-backdrop:not(.d-none) .modal-box { animation: slideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    /* RESPONSIVE MOBILE */
    @media (max-width: 768px) {
        .dashboard-header { flex-direction: column; align-items: flex-start; gap: 16px; }
        .btn-pill { width: 100%; justify-content: center; }

        .form-row { grid-template-columns: 1fr; gap: 0; }
        .lb-card-head { flex-direction: column; gap: 12px; }
        .filter-tabs { border-bottom: none; gap: 8px; }
        .filter-tab { width: calc(50% - 4px); padding: 0 10px; font-size: 12.5px; }
        .lb-content-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<!-- ================= HEADER DASHBOARD ================= -->
<div class="dashboard-header">
    <div class="header-info">
        <h2>Pencatatan Logbook Kegiatan</h2>
        <p>Rekam seluruh progres, aktivitas harian, serta hasil pekerjaan Anda selama masa magang berlangsung.</p>
    </div>
    <button type="button" class="btn-pill" onclick="openLogbookModal()">
        <i class="fa-solid fa-plus"></i> Tulis Logbook Baru
    </button>
</div>

<!-- ================= NAVIGASI TABS ================= -->
<div class="filter-tabs">
    <a href="{{ route('peserta.logbook', ['status' => 'all']) }}" class="filter-tab {{ request('status', 'all') === 'all' ? 'active' : '' }}">Semua Berkas</a>
    <a href="{{ route('peserta.logbook', ['status' => 'draft']) }}" class="filter-tab {{ request('status') === 'draft' ? 'active' : '' }}">Draft Tersimpan</a>
    <a href="{{ route('peserta.logbook', ['status' => 'submitted']) }}" class="filter-tab {{ request('status') === 'submitted' ? 'active' : '' }}">Menunggu Review</a>
    <a href="{{ route('peserta.logbook', ['status' => 'revision_required']) }}" class="filter-tab {{ request('status') === 'revision_required' ? 'active' : '' }}">Perlu Perbaikan</a>
    <a href="{{ route('peserta.logbook', ['status' => 'approved']) }}" class="filter-tab {{ request('status') === 'approved' ? 'active' : '' }}">Telah Disetujui</a>
</div>

<!-- ================= DAFTAR LOGBOOK ================= -->
<div class="logbook-grid">
    @forelse($logbooks ?? [] as $item)
        <div class="lb-card status-{{ $item->status ?? 'draft' }}">

            <div class="lb-card-head">
                <div>
                    <div class="lb-meta">
                        <span><i class="fa-regular fa-calendar-check"></i> {{ $item->logbook_date ? \Carbon\Carbon::parse($item->logbook_date)->translatedFormat('l, d F Y') : '-' }}</span>
                        <span><i class="fa-regular fa-clock"></i> {{ substr($item->start_time ?? '00:00', 0, 5) }} - {{ substr($item->end_time ?? '00:00', 0, 5) }} WIB</span>
                    </div>
                    <h2 class="lb-card-title">{{ $item->title ?? 'Judul Tidak Tersedia' }}</h2>
                </div>

                <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
                    @php
                        $statClass = 'badge-draft'; $statText = 'Draft';
                        if(($item->status ?? '') == 'submitted') { $statClass = 'badge-submitted'; $statText = 'Menunggu Review'; }
                        elseif(($item->status ?? '') == 'approved') { $statClass = 'badge-approved'; $statText = 'Disetujui'; }
                        elseif(($item->status ?? '') == 'revision_required') { $statClass = 'badge-revision_required'; $statText = 'Perlu Revisi'; }
                    @endphp

                    <span class="badge-label {{ $statClass }}">
                        <i class="fa-solid {{ ($item->status ?? '') == 'approved' ? 'fa-circle-check' : (($item->status ?? '') == 'revision_required' ? 'fa-triangle-exclamation' : 'fa-hourglass-half') }}"></i>
                        {{ $statText }}
                    </span>

                    @if(in_array($item->status ?? '', ['draft', 'revision_required']))
                        <button type="button" class="btn-custom btn-outline-primary" onclick='editLogbook(@json($item))'>
                            <i class="fa-solid fa-pen"></i> Edit Logbook
                        </button>
                    @endif
                </div>
            </div>

            <div class="lb-content-grid">
                <div class="lb-field">
                    <strong><i class="fa-solid fa-list-check" style="margin-right: 4px;"></i> Rincian Aktivitas / Tugas</strong>
                    <p>{{ $item->activity ?? '-' }}</p>
                </div>

                @if($item->result ?? false)
                <div class="lb-field">
                    <strong><i class="fa-solid fa-bullseye" style="margin-right: 4px;"></i> Hasil Output / Capaian</strong>
                    <p>{{ $item->result }}</p>
                </div>
                @endif

                @if($item->obstacle ?? false)
                <div class="lb-field obstacle">
                    <strong><i class="fa-solid fa-triangle-exclamation" style="margin-right: 4px;"></i> Kendala & Solusi</strong>
                    <p>{{ $item->obstacle }} <br> <em>Solusi: {{ $item->solution ?? '-' }}</em></p>
                </div>
                @endif
            </div>

            <div class="lb-footer">
                <div>
                    @if($item->attachment_path ?? false)
                        <a href="{{ asset(str_starts_with($item->attachment_path, 'uploads/') ? $item->attachment_path : 'uploads/logbook/' . $item->attachment_path) }}" target="_blank" class="lb-attach">
                            <i class="fa-solid fa-paperclip"></i> Unduh Lampiran Berkas Kegiatan
                        </a>
                    @else
                        <span style="color: #94A3B8; font-size: 13px; font-weight: 600;"><i class="fa-solid fa-link-slash"></i> Tidak ada lampiran berkas</span>
                    @endif
                </div>

                @if($item->review_note ?? false)
                    <div class="lb-note">
                        <strong><i class="fa-solid fa-comment-dots" style="color: var(--primary); margin-right: 4px;"></i> Catatan / Umpan Balik Pembimbing:</strong>
                        <br> {{ $item->review_note }}
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="lb-empty">
            <i class="fa-regular fa-folder-open"></i>
            <h3>Belum Ada Catatan Logbook</h3>
            <p>Data aktivitas harian pada kategori ini masih kosong atau belum diisi.</p>
        </div>
    @endforelse
</div>

@if(($logbooks ?? null) && $logbooks->hasPages())
<div class="pager-container">
    {{ $logbooks->links() }}
</div>
@endif

<!-- ================= MODAL TAMBAH / EDIT LOGBOOK ================= -->
<div class="modal-backdrop d-none" id="logbookModal">
    <div class="modal-box" style="max-width: 680px; text-align: left; padding: 0; border-radius: 16px; background: #fff;">

        <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border);">
            <h3 id="modalTitle" style="margin: 0; font-size: 17px; font-weight: 800; color: var(--text);">
                <i class="fa-solid fa-book-open" style="color: var(--primary); margin-right: 6px;"></i> Isi Laporan Logbook
            </h3>
            <button type="button" onclick="closeLogbookModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); transition: 0.2s;">&times;</button>
        </div>

        <form action="{{ route('peserta.logbook.store') }}" method="POST" enctype="multipart/form-data" id="logbookForm">
            @csrf
            <input type="hidden" name="action_type" id="logbookActionType" value="submit">
            <input type="hidden" name="existing_attachment" id="existingAttachment">
            <input type="hidden" name="existing_attachment_name" id="existingAttachmentName">

            <div style="padding: 24px; max-height: 70vh; overflow-y: auto;">

                <!-- Sinkronisasi Absensi -->
                <div class="form-group" style="background: var(--page); padding: 16px; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 24px;">
                    <label for="lb_attendance_select" style="color: var(--text);">
                        <i class="fa-solid fa-calendar-check" style="color: var(--primary); margin-right: 6px;"></i> Integrasi Data Presensi Harian:
                    </label>
                    <select id="lb_attendance_select" class="input-box" onchange="onAttendanceSelectChanged(this.value)" style="background: #fff;">
                        <option value="">-- Pilih Tanggal Absensi --</option>
                        @foreach($attendances ?? [] as $att)
                            <option value="{{ $att->attendance_date->format('Y-m-d') }}" {{ $att->attendance_date->format('Y-m-d') === date('Y-m-d') ? 'selected' : '' }}>
                                {{ $att->attendance_date->translatedFormat('l, d F Y') }}
                                [Masuk: {{ $att->check_in_at ? $att->check_in_at->format('H:i') . ' WIB' : 'Belum Check-in' }} | Pulang: {{ $att->check_out_at ? $att->check_out_at->format('H:i') . ' WIB' : 'Belum Check-out' }}]
                            </option>
                        @endforeach
                    </select>
                    <small>Memilih tanggal akan otomatis mengisi jam mulai dan selesai berdasarkan riwayat presensi Anda.</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="lb_date">Tanggal Kegiatan *</label>
                        <input type="date" id="lb_date" name="logbook_date" value="{{ date('Y-m-d') }}" required class="input-box" onchange="syncAttendanceWithLogbook(this.value)">
                    </div>
                    <div class="form-group">
                        <label for="lb_title">Judul Kegiatan / Topik *</label>
                        <input type="text" id="lb_title" name="title" placeholder="Contoh: Pemeliharaan Server Database" required class="input-box">
                    </div>
                </div>

                <!-- Notifikasi Status Sinkronisasi Presensi -->
                <div id="attendanceSyncBox" style="margin-bottom: 20px; padding: 14px; border-radius: 12px; font-size: 13.5px; display: flex; align-items: center; gap: 10px; background: var(--primary-soft); border: 1px solid rgba(0,0,0,0.05); color: var(--primary-dark);">
                    <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
                    <div id="attendanceSyncText">
                        <strong>Sinkronisasi Sukses:</strong> Jam kerja terisi otomatis sesuai riwayat presensi.
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="lb_start">Waktu Mulai * <span class="lb-auto-badge"><i class="fa-solid fa-lock"></i> Auto Lock</span></label>
                        <input type="time" id="lb_start" name="start_time" value="{{ $defaultStartTime ?? '08:00' }}" required class="input-box" readonly style="background: var(--page); color: var(--muted); pointer-events: none;">
                    </div>
                    <div class="form-group">
                        <label for="lb_end">Waktu Selesai * <span class="lb-auto-badge"><i class="fa-solid fa-lock"></i> Auto Lock</span></label>
                        <input type="time" id="lb_end" name="end_time" value="{{ $defaultEndTime ?? '16:00' }}" required class="input-box" readonly style="background: var(--page); color: var(--muted); pointer-events: none;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="lb_activity">Rincian Aktivitas & Tugas Yang Dikerjakan *</label>
                    <textarea id="lb_activity" name="activity" rows="4" class="input-box" placeholder="Jelaskan secara detail dan deskriptif seluruh proses pekerjaan Anda hari ini..." required minlength="15"></textarea>
                </div>

                <div class="form-group">
                    <label for="lb_result">Hasil Pekerjaan / Output Capaian</label>
                    <textarea id="lb_result" name="result" rows="2" class="input-box" placeholder="Apa hasil nyata atau persentase progress yang dicapai hari ini?"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="lb_obstacle">Kendala Teknis (Opsional)</label>
                        <textarea id="lb_obstacle" name="obstacle" rows="2" class="input-box" placeholder="Masalah yang dihadapi saat bertugas..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="lb_solution">Solusi yang Diterapkan (Opsional)</label>
                        <textarea id="lb_solution" name="solution" rows="2" class="input-box" placeholder="Langkah pemecahan masalah yang diambil..."></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="lb_next">Rencana Kegiatan Hari Berikutnya (Opsional)</label>
                    <input type="text" id="lb_next" name="next_plan" class="input-box" placeholder="Apa rencana prioritas aktivitas esok hari?">
                </div>

                <div class="form-group">
                    <label for="lb_attachment">Lampiran Pendukung Berkas (Opsional)</label>
                    <input type="file" id="lb_attachment" name="attachment" accept=".pdf,.docx,.jpg,.jpeg,.png" class="input-box" style="padding: 8px 16px; background: #fff;">
                    <small>Maksimal ukuran file 10 MB. Mendukung format: JPG, PNG, PDF, atau DOCX.</small>
                </div>
            </div>

            <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 12px; background: var(--page); border-radius: 0 0 16px 16px;">
                <button type="button" class="btn-custom btn-outline" onclick="closeLogbookModal()">Batalkan</button>
                <button type="button" class="btn-custom btn-warning" onclick="submitLogbookAs('draft')">
                    <i class="fa-regular fa-floppy-disk"></i> Simpan Sebagai Draft
                </button>
                <button type="button" class="btn-custom btn-primary-solid" onclick="submitLogbookAs('submit')">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Untuk Di-review
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const attendanceMap = @json($attendanceDates ?? []);
    const defaultStartTime = '{{ $defaultStartTime ?? '08:00' }}';
    const defaultEndTime = '{{ $defaultEndTime ?? '16:00' }}';

    function onAttendanceSelectChanged(dateVal) {
        if (!dateVal) return;
        document.getElementById('lb_date').value = dateVal;
        syncAttendanceWithLogbook(dateVal);
    }

    function syncAttendanceWithLogbook(dateVal) {
        const syncBox = document.getElementById('attendanceSyncBox');
        const syncText = document.getElementById('attendanceSyncText');
        const startInput = document.getElementById('lb_start');
        const endInput = document.getElementById('lb_end');
        const selectEl = document.getElementById('lb_attendance_select');

        if (selectEl && selectEl.value !== dateVal) {
            selectEl.value = dateVal;
        }

        if (attendanceMap && attendanceMap[dateVal]) {
            const att = attendanceMap[dateVal];
            const checkIn = att.check_in || defaultStartTime;
            const checkOut = att.check_out || defaultEndTime;

            startInput.value = checkIn;
            endInput.value = checkOut;

            syncBox.style.display = 'flex';
            syncBox.style.background = 'var(--primary-soft)';
            syncBox.style.border = '1px solid rgba(0,0,0,0.05)';
            syncBox.style.color = 'var(--primary-dark)';
            syncBox.querySelector('i').className = 'fa-solid fa-circle-check';

            let outMsg = att.check_out ? `${att.check_out} WIB` : `Belum Check-out (Estimasi: ${defaultEndTime} WIB)`;
            syncText.innerHTML = `<strong>Terintegrasi Absensi (${att.status_label}):</strong> Masuk: <strong>${att.check_in ? att.check_in + ' WIB' : '-'}</strong> &bull; Pulang: <strong>${outMsg}</strong>`;
        } else {
            startInput.value = defaultStartTime;
            endInput.value = defaultEndTime;

            syncBox.style.display = 'flex';
            syncBox.style.background = '#FEF9C3';
            syncBox.style.border = '1px solid #FEF08A';
            syncBox.style.color = '#854D0E';
            syncBox.querySelector('i').className = 'fa-solid fa-triangle-exclamation';

            syncText.innerHTML = `<strong>Tidak ada riwayat presensi pada tanggal ini.</strong> Jam kerja standar perusahaan (${defaultStartTime} - ${defaultEndTime} WIB) digunakan secara otomatis.`;
        }
    }

    function openLogbookModal() {
        document.getElementById('logbookForm').reset();
        document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-book-open" style="color: var(--primary); margin-right: 6px;"></i> Isi Laporan Logbook Baru';
        const todayStr = '{{ date('Y-m-d') }}';
        document.getElementById('lb_date').value = todayStr;
        syncAttendanceWithLogbook(todayStr);
        document.getElementById('logbookModal').classList.remove('d-none');
    }

    function closeLogbookModal() {
        document.getElementById('logbookModal').classList.add('d-none');
    }

    function editLogbook(item) {
        document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-pen" style="color: var(--primary); margin-right: 6px;"></i> Perbaiki Laporan Logbook';
        const dateStr = item.logbook_date.split('T')[0];
        document.getElementById('lb_date').value = dateStr;
        syncAttendanceWithLogbook(dateStr);

        document.getElementById('lb_title').value = item.title;
        document.getElementById('lb_start').value = item.start_time ? item.start_time.substring(0, 5) : defaultStartTime;
        document.getElementById('lb_end').value = item.end_time ? item.end_time.substring(0, 5) : defaultEndTime;
        document.getElementById('lb_activity').value = item.activity;
        document.getElementById('lb_result').value = item.result || '';
        document.getElementById('lb_obstacle').value = item.obstacle || '';
        document.getElementById('lb_solution').value = item.solution || '';
        document.getElementById('lb_next').value = item.next_plan || '';
        document.getElementById('existingAttachment').value = item.attachment_path || '';
        document.getElementById('existingAttachmentName').value = item.attachment_name || '';

        document.getElementById('logbookModal').classList.remove('d-none');
    }

    function submitLogbookAs(type) {
        document.getElementById('logbookActionType').value = type;
        const form = document.getElementById('logbookForm');
        if (form.checkValidity()) {
            form.submit();
        } else {
            form.reportValidity();
        }
    }
</script>
@endpush
