@extends('peserta.layout')

@section('title', 'Logbook Harian')

@section('content')

<style>
    /* ==========================================================================
       LOGBOOK - PANEL KACA & KARTU (TEMA DASHBOARD & ABSENSI)
       ========================================================================== */
    .lb-page {
        box-sizing: border-box; width: 100%; max-width: 1200px;
        margin-left: auto; margin-right: auto; color: var(--text);
    }
    .lb-page *, .lb-page *::before, .lb-page *::after { box-sizing: border-box; }

    /* ---------- Panel Utama ---------- */
    .lb-panel {
        background: var(--panel);
        backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 40px; padding: 30px;
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.22);
    }
    .lb-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 18px 24px; margin-bottom: 26px; }
    .lb-top-text { flex: 1 1 360px; min-width: 0; }
    .lb-title { margin: 0; font-size: clamp(24px, 3vw, 36px); font-weight: 700; letter-spacing: -0.025em; line-height: 1.15; color: #ffffff; }
    .lb-sub { margin: 10px 0 0; font-size: 13.5px; line-height: 1.6; color: var(--muted); font-weight: 500; }
    .lb-controls { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }

    /* ---------- Tab Navigasi Kaca ---------- */
    .lb-tabs { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 24px; }
    .lb-tab-item {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 18px;
        border-radius: 9999px; font-size: 13px; font-weight: 600; text-decoration: none; white-space: nowrap;
        background: rgba(255, 255, 255, 0.06); border: 1px solid var(--border-strong); color: var(--text-2);
        transition: all 0.2s ease;
    }
    .lb-tab-item:hover { background: rgba(255, 255, 255, 0.12); color: #ffffff; }
    .lb-tab-item.active { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }

    /* ---------- Tombol Dasar ---------- */
    .lb-page .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 40px; padding: 0 20px; border-radius: 9999px; font-family: inherit; font-size: 13.5px; font-weight: 600; line-height: 1;
        border: 1px solid transparent; cursor: pointer; text-decoration: none; white-space: nowrap;
        transition: background .2s ease, border-color .2s ease, transform .2s ease, color .2s ease;
    }
    .lb-page .btn:hover { transform: translateY(-1px); }
    .lb-page .btn:focus-visible, .lb-page .modal-close:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
    .lb-page .btn-sm { height: 32px; padding: 0 14px; font-size: 12px; }
    .lb-page .btn-primary { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }
    .lb-page .btn-primary:hover { background: #d6f55a; border-color: #d6f55a; }
    .lb-page .btn-outline { background: transparent; color: var(--text); border-color: var(--border-strong); }
    .lb-page .btn-outline:hover { background: var(--card-2-hover); color: #ffffff; }
    .lb-page .btn-warning { background: var(--warn-bg); color: var(--warn); border-color: var(--warn-bd); }
    .lb-page .btn-warning:hover { background: var(--warn); color: #2b2100; }

    /* ---------- Kartu Logbook ---------- */
    .lb-list { display: flex; flex-direction: column; gap: 16px; }
    .lb-card {
        background: var(--card); backdrop-filter: blur(16px) saturate(130%); -webkit-backdrop-filter: blur(16px) saturate(130%);
        border: 1px solid var(--border); border-radius: 24px; padding: 22px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05); display: flex; flex-direction: column;
    }
    .lb-card-head { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; }
    .lb-meta { font-size: 12px; color: var(--muted); font-weight: 500; font-family: var(--font-num); margin-bottom: 6px; display: flex; gap: 10px; align-items: center; }
    .lb-meta i { color: var(--accent); }
    .lb-card-title { font-size: 17px; font-weight: 700; color: #ffffff; margin: 0; line-height: 1.3; }

    .lb-badge {
        display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 9999px;
        font-size: 11.5px; font-weight: 700; white-space: nowrap; border: 1px solid transparent;
    }
    .badge-approved { background: var(--ok-bg); color: var(--ok); border-color: var(--ok-bd); }
    .badge-revision_required { background: var(--warn-bg); color: var(--warn); border-color: var(--warn-bd); }
    .badge-submitted { background: var(--info-bg); color: var(--info); border-color: var(--info-bd); }
    .badge-draft { background: var(--card-2); color: var(--text-2); border-color: var(--border-strong); }

    .lb-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 16px; }
    .lb-field strong { display: block; color: var(--muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 4px; font-family: var(--font-num); }
    .lb-field p { margin: 0; font-size: 13.5px; color: var(--text-2); line-height: 1.6; }
    .lb-field.obstacle strong { color: var(--warn); }

    .lb-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 14px; font-size: 12.5px; flex-wrap: wrap; gap: 10px; }
    .lb-attach { color: var(--accent); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; }
    .lb-attach:hover { color: #ffffff; }
    .lb-note { background: var(--warn-bg); border: 1px solid var(--warn-bd); border-radius: 12px; padding: 8px 14px; color: var(--warn); font-size: 12.5px; display: inline-flex; gap: 8px; align-items: center; }

    .lb-empty { text-align: center; padding: 48px 20px; color: var(--muted); background: var(--card-2); border-radius: 24px; border: 1px dashed var(--border-strong); }
    .lb-empty i { font-size: 40px; margin-bottom: 16px; opacity: 0.5; color: var(--accent); }
    .lb-empty h3 { font-size: 16px; color: #ffffff; margin: 0 0 6px 0; }
    .lb-empty p { font-size: 13px; margin: 0; }

    /* ---------- Form & Input Modal ---------- */
    .lb-page .form-group { margin-bottom: 16px; }
    .lb-page .form-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .lb-page .form-group > label { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 12.5px; font-weight: 600; color: var(--text-2); }
    .lb-page .form-control, .lb-page .form-select {
        width: 100%; padding: 11px 16px; font-family: inherit; font-size: 13.5px; color: var(--text);
        background: rgba(255, 255, 255, 0.07); border: 1px solid var(--border-strong); border-radius: 16px; color-scheme: dark;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .lb-page textarea.form-control { resize: vertical; line-height: 1.5; }
    .lb-page .form-control::placeholder { color: var(--muted); }
    .lb-page .form-control:focus, .lb-page .form-select:focus { outline: none; border-color: var(--accent); background: rgba(255, 255, 255, 0.10); box-shadow: 0 0 0 3px var(--accent-soft); }
    .lb-page .form-select option { background: #1f2121; color: #f6f6f4; }
    .lb-page input[type="file"].form-control { padding: 9px 14px; }
    .lb-page input[type="file"]::file-selector-button {
        margin-right: 12px; padding: 6px 14px; border-radius: 9999px; border: 1px solid var(--border-strong);
        background: var(--card-2-hover); color: var(--text); font-family: inherit; font-size: 12px; font-weight: 600; cursor: pointer;
    }
    .lb-page .form-group small { color: var(--muted); font-size: 11.5px; margin-top: 6px; display: block; }

    .lb-auto-badge { font-size: 10px; color: var(--info); background: var(--info-bg); border: 1px solid var(--info-bd); padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; }

    /* ---------- Modal Kaca ---------- */
    .lb-page .modal-overlay {
        position: fixed; inset: 0; z-index: 2000; display: none; align-items: center; justify-content: center; padding: 20px;
        background: rgba(0, 0, 0, 0.58); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    }
    .lb-page .modal-overlay.active { display: flex; }
    .lb-page .modal-card {
        width: 100%; max-width: 680px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden;
        background: rgba(34, 36, 36, 0.94); backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 32px; color: var(--text);
        box-shadow: 0 40px 90px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.18);
    }
    .lb-page .modal-card form { display: flex; flex-direction: column; min-height: 0; flex: 1; }
    .lb-page .modal-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 20px 24px; border-bottom: 1px solid var(--border); }
    .lb-page .modal-header h3 { margin: 0; font-size: 16.5px; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 10px; }
    .lb-page .modal-header h3 i { color: var(--accent); }
    .lb-page .modal-close {
        width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0; cursor: pointer; font-size: 20px; line-height: 1; color: var(--text);
        display: inline-flex; align-items: center; justify-content: center; background: var(--card-2); border: 1px solid var(--border-strong); transition: background .2s;
    }
    .lb-page .modal-close:hover { background: var(--card-2-hover); }
    .lb-page .modal-body { padding: 22px 24px; overflow-y: auto; }
    .lb-page .modal-footer { display: flex; justify-content: flex-end; align-items: center; flex-wrap: wrap; gap: 10px; padding: 18px 24px; border-top: 1px solid var(--border); background: rgba(0,0,0,0.1); }

    /* ---------- Pagination ---------- */
    .lb-pager { margin-top: 24px; }
    .lb-pager nav[role="navigation"] > div:first-child { display: none; }
    .lb-pager nav[role="navigation"] svg { width: 16px; height: 16px; }
    .lb-pager nav[role="navigation"] p { font-size: 12px; color: var(--muted); margin: 0; }
    .lb-pager .pagination { display: flex; flex-wrap: wrap; gap: 6px; list-style: none; padding: 0; margin: 0; }
    .lb-pager a, .lb-pager .page-link, .lb-pager span[aria-current] > span, .lb-pager span[aria-disabled] > span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 12px;
        border-radius: 9999px; font-size: 12.5px; font-weight: 600; text-decoration: none;
        color: var(--text-2); background: var(--card-2); border: 1px solid var(--border);
    }
    .lb-pager .page-item.active .page-link, .lb-pager span[aria-current] > span { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }

    @media (max-width: 768px) {
        .lb-panel { padding: 18px; border-radius: 30px; }
        .lb-card { padding: 18px; border-radius: 20px; }
        .lb-page .form-row { grid-template-columns: 1fr; gap: 0; }
        .lb-page .modal-card { border-radius: 26px; }
        .lb-page .modal-header, .lb-page .modal-body, .lb-page .modal-footer { padding-left: 18px; padding-right: 18px; }
    }
</style>

<div class="lb-page">
<div class="lb-panel">

    <!-- ================= KEPALA PANEL ================= -->
    <header class="lb-top">
        <div class="lb-top-text">
            <h1 class="lb-title">Logbook Kegiatan Harian</h1>
            <p class="lb-sub">Dokumentasikan kegiatan, hasil pencapaian, kendala, dan rencana magang Anda setiap hari kerja.</p>
        </div>
        <div class="lb-controls">
            <button type="button" class="btn btn-primary" onclick="openLogbookModal()">
                <i class="fa-solid fa-plus"></i> Buat Logbook Baru
            </button>
        </div>
    </header>

    <!-- ================= NAVIGASI TABS ================= -->
    <div class="lb-tabs">
        <a href="{{ route('peserta.logbook', ['status' => 'all']) }}" class="lb-tab-item {{ request('status', 'all') === 'all' ? 'active' : '' }}">
            Semua Logbook
        </a>
        <a href="{{ route('peserta.logbook', ['status' => 'draft']) }}" class="lb-tab-item {{ request('status') === 'draft' ? 'active' : '' }}">
            Draft
        </a>
        <a href="{{ route('peserta.logbook', ['status' => 'submitted']) }}" class="lb-tab-item {{ request('status') === 'submitted' ? 'active' : '' }}">
            Menunggu Review
        </a>
        <a href="{{ route('peserta.logbook', ['status' => 'revision_required']) }}" class="lb-tab-item {{ request('status') === 'revision_required' ? 'active' : '' }}">
            Perlu Revisi
        </a>
        <a href="{{ route('peserta.logbook', ['status' => 'approved']) }}" class="lb-tab-item {{ request('status') === 'approved' ? 'active' : '' }}">
            Disetujui
        </a>
    </div>

    <!-- ================= DAFTAR LOGBOOK ================= -->
    <div class="lb-list">
        @forelse($logbooks as $item)
            <div class="lb-card" style="border-left: 4px solid {{ match($item->status) {
                'approved' => 'var(--ok)',
                'revision_required' => 'var(--warn)',
                'submitted' => 'var(--info)',
                default => 'var(--text-2)',
            } }};">

                <div class="lb-card-head">
                    <div>
                        <div class="lb-meta">
                            <i class="fa-regular fa-calendar"></i> {{ $item->logbook_date->translatedFormat('l, d F Y') }} &bull;
                            <i class="fa-regular fa-clock"></i> {{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }} WIB
                        </div>
                        <h2 class="lb-card-title">{{ $item->title }}</h2>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span class="lb-badge badge-{{ $item->status }}">
                            {{ match($item->status) {
                                'draft' => 'Draft Tersimpan',
                                'submitted' => 'Menunggu Review',
                                'revision_required' => 'Perlu Revisi',
                                'approved' => 'Disetujui Pembimbing',
                                'rejected' => 'Ditolak',
                                default => $item->status,
                            } }}
                        </span>

                        @if(in_array($item->status, ['draft', 'revision_required']))
                            <button type="button" class="btn btn-sm btn-outline" onclick='editLogbook(@json($item))'>
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                        @endif
                    </div>
                </div>

                <div class="lb-grid">
                    <div class="lb-field">
                        <strong><i class="fa-solid fa-list-check"></i> Aktivitas:</strong>
                        <p>{{ $item->activity }}</p>
                    </div>

                    @if($item->result)
                    <div class="lb-field">
                        <strong><i class="fa-solid fa-bullseye"></i> Hasil / Output:</strong>
                        <p>{{ $item->result }}</p>
                    </div>
                    @endif

                    @if($item->obstacle)
                    <div class="lb-field obstacle">
                        <strong><i class="fa-solid fa-triangle-exclamation"></i> Kendala & Solusi:</strong>
                        <p>{{ $item->obstacle }} &bull; <em>{{ $item->solution }}</em></p>
                    </div>
                    @endif
                </div>

                <div class="lb-footer">
                    <div>
                        @if($item->attachment_path)
                            <a href="{{ asset($item->attachment_path) }}" target="_blank" class="lb-attach">
                                <i class="fa-solid fa-paperclip"></i> Unduh Lampiran: {{ $item->attachment_name ?: 'Lampiran Kegiatan' }}
                            </a>
                        @else
                            <span style="color: var(--muted);"><i class="fa-solid fa-link-slash"></i> Tidak ada lampiran file</span>
                        @endif
                    </div>

                    @if($item->review_note)
                        <div class="lb-note">
                            <strong><i class="fa-solid fa-comment-dots"></i> Catatan Pembimbing:</strong> {{ $item->review_note }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="lb-empty">
                <i class="fa-regular fa-book"></i>
                <h3>Belum Ada Logbook Pada Kategori Ini</h3>
                <p>Klik tombol "Buat Logbook Baru" untuk mencatat kegiatan harian Anda.</p>
            </div>
        @endforelse
    </div>

    <div class="lb-pager">
        {{ $logbooks->links() }}
    </div>

</div>

    <!-- ================= MODAL TAMBAH / EDIT LOGBOOK ================= -->
    <div class="modal-overlay" id="logbookModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fa-regular fa-book-open"></i> Isi Logbook Harian</h3>
                <button type="button" class="modal-close" onclick="closeLogbookModal()">&times;</button>
            </div>
            <form action="{{ route('peserta.logbook.store') }}" method="POST" enctype="multipart/form-data" id="logbookForm">
                @csrf
                <input type="hidden" name="action_type" id="logbookActionType" value="submit">
                <input type="hidden" name="existing_attachment" id="existingAttachment">
                <input type="hidden" name="existing_attachment_name" id="existingAttachmentName">

                <div class="modal-body">
                    <!-- Sinkronisasi Absensi -->
                    <div class="form-group" style="background: rgba(255,255,255,0.05); padding: 14px; border-radius: 16px; border: 1px solid var(--border-strong); margin-bottom: 20px;">
                        <label for="lb_attendance_select" style="color: #ffffff; font-size: 13.5px;">
                            <span><i class="fa-solid fa-calendar-check" style="color: var(--accent); margin-right: 6px;"></i> Pilih dari Riwayat Absensi Anda:</span>
                        </label>
                        <select id="lb_attendance_select" class="form-select" onchange="onAttendanceSelectChanged(this.value)">
                            <option value="">-- Pilih Tanggal Absensi --</option>
                            @foreach($attendances as $att)
                                <option value="{{ $att->attendance_date->format('Y-m-d') }}" {{ $att->attendance_date->format('Y-m-d') === date('Y-m-d') ? 'selected' : '' }}>
                                    {{ $att->attendance_date->translatedFormat('l, d F Y') }}
                                    [Masuk: {{ $att->check_in_at ? $att->check_in_at->format('H:i') . ' WIB' : 'Belum Check-in' }} | Pulang: {{ $att->check_out_at ? $att->check_out_at->format('H:i') . ' WIB' : 'Belum Check-out' }}]
                                </option>
                            @endforeach
                        </select>
                        <small>Pilih tanggal untuk otomatis mengisi jam masuk dan jam pulang dari data presensi.</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="lb_date">Tanggal Kegiatan *</label>
                            <input type="date" id="lb_date" name="logbook_date" value="{{ date('Y-m-d') }}" required class="form-control" onchange="syncAttendanceWithLogbook(this.value)">
                        </div>
                        <div class="form-group">
                            <label for="lb_title">Judul Kegiatan *</label>
                            <input type="text" id="lb_title" name="title" placeholder="Contoh: Pemeliharaan Server" required class="form-control">
                        </div>
                    </div>

                    <!-- Notifikasi Status Sinkronisasi Presensi -->
                    <div id="attendanceSyncBox" style="margin-bottom: 18px; padding: 12px 16px; border-radius: 16px; font-size: 12.5px; display: flex; align-items: center; gap: 10px; background: var(--ok-bg); border: 1px solid var(--ok-bd); color: var(--ok);">
                        <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
                        <div id="attendanceSyncText" style="color: var(--text-2);">
                            <strong style="color: var(--ok);">Terintegrasi Absensi:</strong> Jam kerja terisi otomatis sesuai riwayat presensi.
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="lb_start">
                                <span><i class="fa-solid fa-clock"></i> Waktu Mulai *</span>
                                <span class="lb-auto-badge"><i class="fa-solid fa-lock"></i> Auto</span>
                            </label>
                            <input type="time" id="lb_start" name="start_time" value="{{ $defaultStartTime }}" required class="form-control" readonly style="background: rgba(0,0,0,0.2);">
                        </div>
                        <div class="form-group">
                            <label for="lb_end">
                                <span><i class="fa-solid fa-clock"></i> Waktu Selesai *</span>
                                <span class="lb-auto-badge"><i class="fa-solid fa-lock"></i> Auto</span>
                            </label>
                            <input type="time" id="lb_end" name="end_time" value="{{ $defaultEndTime }}" required class="form-control" readonly style="background: rgba(0,0,0,0.2);">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lb_activity">Rincian Aktivitas Kegiatan *</label>
                        <textarea id="lb_activity" name="activity" rows="3" class="form-control" placeholder="Jelaskan secara detail pekerjaan yang Anda lakukan hari ini..." required minlength="15"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="lb_result">Hasil Pekerjaan / Output</label>
                        <textarea id="lb_result" name="result" rows="2" class="form-control" placeholder="Apa hasil nyata atau progress yang dicapai hari ini?"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="lb_obstacle">Kendala / Masalah yang Ditemui</label>
                            <textarea id="lb_obstacle" name="obstacle" rows="2" class="form-control" placeholder="Kendala teknis atau operasional jika ada..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="lb_solution">Solusi yang Diterapkan</label>
                            <textarea id="lb_solution" name="solution" rows="2" class="form-control" placeholder="Langkah pemecahan masalah yang dilakukan..."></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lb_next">Rencana Kegiatan Selanjutnya</label>
                        <input type="text" id="lb_next" name="next_plan" class="form-control" placeholder="Rencana aktivitas hari kerja berikutnya...">
                    </div>

                    <div class="form-group">
                        <label for="lb_attachment">Lampiran Pendukung (Foto/PDF/DOCX)</label>
                        <input type="file" id="lb_attachment" name="attachment" accept=".pdf,.docx,.jpg,.jpeg,.png" class="form-control">
                        <small>Maksimal 10 MB. Format: JPG, PNG, PDF, DOCX.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeLogbookModal()">Batal</button>
                    <button type="button" class="btn btn-warning" onclick="submitLogbookAs('draft')">
                        <i class="fa-regular fa-floppy-disk"></i> Simpan Draft
                    </button>
                    <button type="button" class="btn btn-primary" onclick="submitLogbookAs('submit')">
                        <i class="fa-solid fa-paper-plane"></i> Kirim ke Pembimbing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const attendanceMap = @json($attendanceDates);
    const defaultStartTime = '{{ $defaultStartTime }}';
    const defaultEndTime = '{{ $defaultEndTime }}';

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
            syncBox.style.background = 'var(--ok-bg)';
            syncBox.style.border = '1px solid var(--ok-bd)';
            syncBox.style.color = 'var(--ok)';
            syncBox.querySelector('i').className = 'fa-solid fa-circle-check';

            let outMsg = att.check_out ? `${att.check_out} WIB` : `Belum Check-out (Estimasi: ${defaultEndTime} WIB)`;
            syncText.innerHTML = `<strong style="color:var(--ok);">Terintegrasi Absensi (${att.status_label}):</strong> Check-in: <strong style="color:#fff;">${att.check_in ? att.check_in + ' WIB' : 'Belum Check-in'}</strong> &bull; Check-out: <strong style="color:#fff;">${outMsg}</strong>`;
        } else {
            startInput.value = defaultStartTime;
            endInput.value = defaultEndTime;

            syncBox.style.display = 'flex';
            syncBox.style.background = 'var(--warn-bg)';
            syncBox.style.border = '1px solid var(--warn-bd)';
            syncBox.style.color = 'var(--warn)';
            syncBox.querySelector('i').className = 'fa-solid fa-triangle-exclamation';

            syncText.innerHTML = `<strong style="color:var(--warn);">Tidak ada riwayat presensi tercatat pada tanggal ini.</strong> Jam kerja standar (${defaultStartTime} - ${defaultEndTime} WIB) digunakan secara otomatis.`;
        }
    }

    function openLogbookModal() {
        document.getElementById('logbookForm').reset();
        document.getElementById('modalTitle').innerHTML = '<i class="fa-regular fa-book-open"></i> Isi Logbook Harian';
        const todayStr = '{{ date('Y-m-d') }}';
        document.getElementById('lb_date').value = todayStr;
        syncAttendanceWithLogbook(todayStr);
        document.getElementById('logbookModal').classList.add('active');
    }

    function closeLogbookModal() {
        document.getElementById('logbookModal').classList.remove('active');
    }

    function editLogbook(item) {
        document.getElementById('modalTitle').innerHTML = '<i class="fa-regular fa-pen-to-square"></i> Perbaiki Logbook';
        const dateStr = item.logbook_date.split('T')[0];
        document.getElementById('lb_date').value = dateStr;
        syncAttendanceWithLogbook(dateStr);
        document.getElementById('lb_title').value = item.title;
        document.getElementById('lb_start').value = item.start_time.substring(0, 5);
        document.getElementById('lb_end').value = item.end_time.substring(0, 5);
        document.getElementById('lb_activity').value = item.activity;
        document.getElementById('lb_result').value = item.result || '';
        document.getElementById('lb_obstacle').value = item.obstacle || '';
        document.getElementById('lb_solution').value = item.solution || '';
        document.getElementById('lb_next').value = item.next_plan || '';
        document.getElementById('existingAttachment').value = item.attachment_path || '';
        document.getElementById('existingAttachmentName').value = item.attachment_name || '';

        document.getElementById('logbookModal').classList.add('active');
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
