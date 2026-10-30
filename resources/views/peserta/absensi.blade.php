@extends('peserta.layout')

@section('title', 'Presensi & Koreksi')

@section('content')

<style>
    /* ==========================================================================
       PRESENSI - PANEL KACA + KARTU BENTO (SAMA DENGAN DASHBOARD)
       Mandiri: semua gaya ada di sini & hanya berlaku di dalam .abs-page.
       Warna dibaca dari variabel tema di layout (--panel, --card, --accent, dst).
       ========================================================================== */
    .abs-page {
        box-sizing: border-box; width: 100%; max-width: 1200px;
        margin-left: auto; margin-right: auto; color: var(--text);
    }
    .abs-page *, .abs-page *::before, .abs-page *::after { box-sizing: border-box; }

    /* ---------- Panel utama ---------- */
    .ab-panel {
        background: var(--panel);
        backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 40px; padding: 30px;
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.22);
    }
    .ab-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 18px 24px; margin-bottom: 26px; }
    .ab-top-text { flex: 1 1 360px; min-width: 0; }
    .ab-title { margin: 0; font-size: clamp(24px, 3vw, 36px); font-weight: 700; letter-spacing: -0.025em; line-height: 1.15; color: #ffffff; }
    .ab-sub { margin: 10px 0 0; font-size: 13.5px; line-height: 1.6; color: var(--muted); font-weight: 500; }
    .ab-controls { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
    .ab-pill {
        display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 16px; border-radius: 9999px;
        font-size: 13px; font-weight: 600; color: var(--text); white-space: nowrap;
        background: rgba(255, 255, 255, 0.09); border: 1px solid var(--border-strong);
    }
    .ab-pill i { color: var(--text-2); font-size: 13px; }
    .ab-pill .num { font-family: var(--font-num); font-size: 12.5px; }

    /* ---------- Grid bento ---------- */
    .ab-bento { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
    .c-span2 { grid-column: span 2; }
    .c-span4 { grid-column: span 4; }

    .ab-card {
        background: var(--card);
        backdrop-filter: blur(16px) saturate(130%); -webkit-backdrop-filter: blur(16px) saturate(130%);
        border: 1px solid var(--border); border-radius: 26px; padding: 22px; min-width: 0;
        display: flex; flex-direction: column; color: var(--text);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
    }
    .ab-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 16px; }
    .ab-card-title { margin: 0; font-size: 15.5px; font-weight: 700; letter-spacing: -0.01em; color: #ffffff; }
    .ab-card-cap { margin-top: 3px; font-family: var(--font-num); font-size: 11px; color: var(--muted); }
    .ab-ico-btn {
        width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center; font-size: 14px;
        color: var(--accent); background: var(--card-2); border: 1px solid var(--border-strong);
    }

    /* ---------- Tombol ---------- */
    .abs-page .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 42px; padding: 0 20px; border-radius: 9999px; font-family: inherit; font-size: 13.5px; font-weight: 600; line-height: 1;
        border: 1px solid transparent; cursor: pointer; text-decoration: none; white-space: nowrap;
        transition: background .2s ease, border-color .2s ease, transform .2s ease, color .2s ease;
    }
    .abs-page .btn:hover { transform: translateY(-1px); }
    .abs-page .btn:focus-visible, .abs-page .modal-close:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
    .abs-page .btn:disabled { opacity: .4; cursor: not-allowed; transform: none; }
    .abs-page .btn-sm { height: 34px; padding: 0 14px; font-size: 12px; }
    .abs-page .btn-block { width: 100%; height: 50px; font-size: 14.5px; }
    .abs-page .btn-primary, .abs-page .btn-success { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }
    .abs-page .btn-primary:hover:not(:disabled), .abs-page .btn-success:hover:not(:disabled) { background: #d6f55a; border-color: #d6f55a; }
    .abs-page .btn-outline { background: transparent; color: var(--text); border-color: var(--border-strong); }
    .abs-page .btn-outline:hover { background: var(--card-2-hover); color: #ffffff; }
    .abs-page .btn-warning { background: var(--warn-bg); color: var(--warn); border-color: var(--warn-bd); }
    .abs-page .btn-warning:hover { background: var(--warn); color: #2b2100; }
    .abs-page .btn-stop { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-bd); }
    .abs-page .btn-stop:hover { background: #ff5a5a; color: #ffffff; }

    /* ---------- Badge ---------- */
    .abs-page .badge {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px;
        font-size: 11.5px; font-weight: 600; line-height: 1.4; white-space: nowrap;
        border: 1px solid var(--border-strong); background: var(--card-2); color: var(--text-2);
    }
    .abs-page .badge-success, .abs-page .badge-approved, .abs-page .badge-present { background: var(--ok-bg); color: var(--ok); border-color: var(--ok-bd); }
    .abs-page .badge-late, .abs-page .badge-sick, .abs-page .badge-warning { background: var(--warn-bg); color: var(--warn); border-color: var(--warn-bd); }
    .abs-page .badge-rejected, .abs-page .badge-absent, .abs-page .badge-danger { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-bd); }
    .abs-page .badge-submitted, .abs-page .badge-leave, .abs-page .badge-info { background: var(--info-bg); color: var(--info); border-color: var(--info-bd); }
    .abs-page .badge-secondary { background: var(--card-2); color: var(--text-2); border-color: var(--border-strong); }

    /* ---------- Radar ---------- */
    .ab-radar-title { margin: 4px 0 8px; font-size: clamp(20px, 2.2vw, 26px); font-weight: 700; letter-spacing: -0.02em; color: #ffffff; line-height: 1.2; }
    .ab-radar-desc { margin: 0; font-size: 13px; line-height: 1.65; color: var(--muted); }
    .ab-actions { margin-top: auto; padding-top: 18px; display: flex; gap: 10px; flex-wrap: wrap; }
    .ab-metrics { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; flex: 1; }
    .ab-metric { background: var(--card-2); border: 1px solid var(--border); border-radius: 20px; padding: 14px 16px; min-width: 0; display: flex; flex-direction: column; justify-content: center; }
    .ab-metric .lbl { font-family: var(--font-num); font-size: 10px; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); }
    .ab-metric .val { margin-top: 6px; font-family: var(--font-num); font-size: 18px; font-weight: 600; color: #ffffff; word-break: break-word; }

    /* ---------- Form presensi ---------- */
    .ab-field-head { display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 10px; }
    .ab-field-label { margin: 0; font-size: 13.5px; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 8px; }
    .ab-field-label i { color: var(--accent); }
    .ab-hint { display: block; text-align: center; margin-top: 8px; font-size: 11.5px; color: var(--muted); line-height: 1.5; }

    .ab-note { border-radius: 20px; padding: 16px 18px; margin-bottom: 18px; background: var(--ok-bg); border: 1px solid var(--ok-bd); }
    .ab-note-title { color: var(--ok); font-weight: 700; font-size: 14px; margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
    .ab-note-text { font-size: 13px; color: var(--text-2); }
    .ab-note-text strong { color: #ffffff; }

    /* kamera: sengaja tetap gelap seperti viewfinder */
    .ab-cam {
        display: none; position: relative; margin-top: 12px; overflow: hidden; border-radius: 24px;
        background: #0a0b0b; border: 1px solid var(--border-strong);
    }
    .ab-cam video { width: 100%; height: auto; min-height: 280px; max-height: 380px; object-fit: cover; display: block; transform: scaleX(-1); }
    .ab-hud {
        position: absolute; inset: 0 0 auto 0; bottom: 56px; pointer-events: none; padding: 14px;
        display: flex; flex-direction: column; justify-content: space-between;
        background: linear-gradient(180deg, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0) 60%, rgba(0,0,0,0.88) 100%);
        text-shadow: 0 1px 4px rgba(0,0,0,0.9);
    }
    .ab-hud-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; }
    .ab-hud-lbl { font-family: var(--font-num); font-size: 10px; font-weight: 500; letter-spacing: 0.06em; text-transform: uppercase; color: var(--accent); }
    .ab-hud-clock { font-family: var(--font-num); font-size: 18px; font-weight: 600; color: #ffffff; }
    .ab-hud-date { font-size: 11px; color: rgba(255,255,255,0.75); }
    .ab-hud-badge {
        display: inline-block; padding: 4px 12px; border-radius: 9999px; font-family: var(--font-num); font-size: 11px; font-weight: 600;
        background: var(--warn-bg); color: var(--warn); border: 1px solid var(--warn-bd); transition: all .3s ease;
    }
    .ab-hud-dist { margin-top: 4px; font-family: var(--font-num); font-size: 10.5px; color: rgba(255,255,255,0.8); text-align: right; }
    .ab-face { align-self: center; width: 140px; height: 180px; border: 2px dashed rgba(255,255,255,0.45); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .ab-face span { color: rgba(255,255,255,0.65); font-family: var(--font-num); font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; }
    .ab-hud-coords { font-family: var(--font-num); font-size: 11.5px; color: #ffffff; display: flex; align-items: center; gap: 6px; }
    .ab-hud-coords i { color: #ff7a7a; }
    .ab-hud-site { font-size: 10px; color: rgba(255,255,255,0.6); margin-top: 2px; }
    .ab-cam-actions { height: 56px; padding: 0 12px; display: flex; gap: 8px; justify-content: center; align-items: center; flex-wrap: wrap; background: rgba(12, 13, 13, 0.92); }

    .ab-preview { display: none; margin-top: 14px; text-align: center; }
    .ab-preview-wrap { position: relative; display: inline-block; max-width: 100%; }
    .ab-preview img { width: 100%; max-height: 280px; border-radius: 22px; border: 2px solid var(--accent); object-fit: cover; display: block; }
    .ab-stamp {
        position: absolute; top: 12px; right: 12px; padding: 4px 12px; border-radius: 9999px;
        background: var(--accent); color: var(--accent-ink); font-size: 11px; font-weight: 700;
    }
    .ab-preview .btn { margin-top: 10px; }

    .ab-done { text-align: center; padding: 18px 6px; }
    .ab-done-ico {
        width: 64px; height: 64px; margin: 0 auto 14px; border-radius: 50%; font-size: 28px;
        display: flex; align-items: center; justify-content: center; color: var(--accent); background: var(--accent-soft); border: 1px solid var(--accent-bd);
    }
    .ab-done h3 { margin: 0; font-size: 18px; font-weight: 700; color: #ffffff; }
    .ab-done p { margin: 6px 0 0; font-size: 13px; color: var(--muted); }
    .ab-done .row { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; margin-top: 14px; }

    /* ---------- Kebijakan ---------- */
    .ab-policy { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; }
    .ab-policy li { display: flex; gap: 14px; align-items: flex-start; padding: 14px 0; border-top: 1px solid var(--border); font-size: 13px; color: var(--text-2); line-height: 1.55; }
    .ab-policy li:first-child { border-top: none; padding-top: 0; }
    .ab-policy li strong { display: block; color: #ffffff; font-size: 13.5px; margin-bottom: 2px; }
    .ab-pico { width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; border: 1px solid transparent; }
    .tone-accent { background: var(--accent-soft); color: var(--accent); border-color: var(--accent-bd); }
    .tone-warn   { background: var(--warn-bg);   color: var(--warn);   border-color: var(--warn-bd); }
    .tone-info   { background: var(--info-bg);   color: var(--info);   border-color: var(--info-bd); }
    .tone-ok     { background: var(--ok-bg);     color: var(--ok);     border-color: var(--ok-bd); }
    .ab-callout {
        margin-top: auto; padding: 14px 16px; border-radius: 20px; font-size: 12.5px; line-height: 1.6; color: var(--text-2);
        background: var(--warn-bg); border: 1px solid var(--warn-bd);
    }
    .ab-callout i { color: var(--warn); margin-right: 4px; }
    .ab-callout strong { color: #ffffff; }
    .ab-policy + .ab-callout { margin-top: 18px; }

    /* ---------- Form umum & input ---------- */
    .abs-page .form-group { margin-bottom: 16px; }
    .abs-page .form-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .abs-page .form-group > label { display: block; margin-bottom: 6px; font-size: 12.5px; font-weight: 600; color: var(--text-2); }
    .abs-page .form-control, .abs-page .form-select {
        width: 100%; padding: 11px 16px; font-family: inherit; font-size: 13.5px; color: var(--text);
        background: rgba(255, 255, 255, 0.07); border: 1px solid var(--border-strong); border-radius: 16px; color-scheme: dark;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }
    .abs-page textarea.form-control { resize: vertical; line-height: 1.5; }
    .abs-page .form-control::placeholder { color: var(--muted); }
    .abs-page .form-control:focus, .abs-page .form-select:focus { outline: none; border-color: var(--accent); background: rgba(255, 255, 255, 0.10); box-shadow: 0 0 0 3px var(--accent-soft); }
    .abs-page .form-select option { background: #1f2121; color: #f6f6f4; }
    .abs-page input[type="file"].form-control { padding: 9px 14px; }
    .abs-page input[type="file"]::file-selector-button {
        margin-right: 12px; padding: 6px 14px; border-radius: 9999px; border: 1px solid var(--border-strong);
        background: var(--card-2-hover); color: var(--text); font-family: inherit; font-size: 12px; font-weight: 600; cursor: pointer;
    }
    .abs-page .form-group small { color: var(--muted); font-size: 11px; }

    /* ---------- Tabel ---------- */
    .ab-table-wrap { overflow-x: auto; border: 1px solid var(--border); border-radius: 20px; }
    .ab-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .ab-table th {
        text-align: left; padding: 13px 16px; white-space: nowrap;
        font-family: var(--font-num); font-size: 10.5px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase;
        color: var(--muted); background: rgba(255, 255, 255, 0.05);
    }
    .ab-table td { padding: 14px 16px; font-size: 13px; color: var(--text-2); vertical-align: top; border-top: 1px solid var(--border); }
    .ab-table tbody tr:hover td { background: var(--card-2); }
    .ab-table td strong { color: #ffffff; font-weight: 600; }
    .ab-table .muted { max-width: 200px; font-size: 12px; color: var(--muted); }
    .ab-table .empty { text-align: center; padding: 28px; color: var(--muted); }
    .ab-pager { margin-top: 16px; }
    .ab-pager nav[role="navigation"] > div:first-child { display: none; }
    .ab-pager nav[role="navigation"] svg { width: 16px; height: 16px; }
    .ab-pager nav[role="navigation"] p { font-size: 12px; color: var(--muted); margin: 0; }
    .ab-pager .pagination { display: flex; flex-wrap: wrap; gap: 6px; list-style: none; padding: 0; margin: 0; }
    .ab-pager a, .ab-pager .page-link, .ab-pager span[aria-current] > span, .ab-pager span[aria-disabled] > span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 12px;
        border-radius: 9999px; font-size: 12.5px; font-weight: 600; text-decoration: none;
        color: var(--text-2); background: var(--card-2); border: 1px solid var(--border);
    }
    .ab-pager .page-item.active .page-link, .ab-pager span[aria-current] > span { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }

    /* ---------- Modal ---------- */
    .abs-page .modal-overlay {
        position: fixed; inset: 0; z-index: 2000; display: none; align-items: center; justify-content: center; padding: 20px;
        background: rgba(0, 0, 0, 0.58); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    }
    .abs-page .modal-overlay.active { display: flex; }
    .abs-page .modal-card {
        width: 100%; max-width: 640px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden;
        background: rgba(34, 36, 36, 0.94); backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 32px; color: var(--text);
        box-shadow: 0 40px 90px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.18);
    }
    .abs-page .modal-card form { display: flex; flex-direction: column; min-height: 0; flex: 1; }
    .abs-page .modal-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 20px 24px; border-bottom: 1px solid var(--border); }
    .abs-page .modal-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 10px; }
    .abs-page .modal-header h3 i { color: var(--accent); }
    .abs-page .modal-close {
        width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0; cursor: pointer; font-size: 20px; line-height: 1; color: var(--text);
        display: inline-flex; align-items: center; justify-content: center; background: var(--card-2); border: 1px solid var(--border-strong);
    }
    .abs-page .modal-close:hover { background: var(--card-2-hover); }
    .abs-page .modal-body { padding: 22px 24px; overflow-y: auto; }
    .abs-page .modal-footer { display: flex; justify-content: flex-end; align-items: center; flex-wrap: wrap; gap: 10px; padding: 16px 24px; border-top: 1px solid var(--border); }
    .ab-viewer-img { width: 100%; max-height: 440px; border-radius: 20px; object-fit: contain; border: 1px solid var(--border-strong); }

    /* ---------- Responsif ---------- */
    @media (max-width: 1100px) {
        .ab-bento { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .c-span4 { grid-column: span 2; }
    }
    @media (max-width: 768px) {
        .ab-panel { padding: 18px; border-radius: 30px; }
        .ab-card { padding: 16px; border-radius: 22px; }
        .ab-pill { height: 36px; padding: 0 13px; font-size: 12px; }
        .abs-page .modal-card { border-radius: 26px; }
        .abs-page .modal-header, .abs-page .modal-body, .abs-page .modal-footer { padding-left: 18px; padding-right: 18px; }
    }
    @media (max-width: 640px) {
        .ab-bento { grid-template-columns: 1fr; }
        .c-span2, .c-span4 { grid-column: auto; }
        .abs-page .form-row { grid-template-columns: 1fr; gap: 0; }
    }
    @media (max-width: 420px) {
        .ab-metrics { grid-template-columns: 1fr; }
    }
    @media (prefers-reduced-motion: reduce) {
        .abs-page .btn { transition: none; }
        .abs-page .btn:hover { transform: none; }
    }
</style>

<div class="abs-page">
<div class="ab-panel">

    <!-- ================= KEPALA PANEL ================= -->
    <header class="ab-top">
        <div class="ab-top-text">
            <h1 class="ab-title">Presensi &amp; Geofencing</h1>
            <p class="ab-sub">Presensi berbasis verifikasi geospasial GPS dan QR Code resmi PLTU Adipala.</p>
        </div>

        <div class="ab-controls">
            <span class="ab-pill"><i class="fa-regular fa-clock"></i> <span class="num" id="liveClock">00:00:00 WIB</span></span>
            <button type="button" class="btn btn-warning" onclick="openKoreksiModal()">
                <i class="fa-solid fa-file-pen"></i> Ajukan Koreksi Absensi
            </button>
        </div>
    </header>

    <div class="ab-bento">

        <!-- ================= STATUS DETEKSI LOKASI ================= -->
        <section class="ab-card c-span2">
            <div class="ab-head">
                <div>
                    <h3 class="ab-card-title">Status Deteksi Lokasi</h3>
                    <div class="ab-card-cap">geofence PLTU Adipala</div>
                </div>
                <span class="ab-ico-btn"><i class="fa-solid fa-satellite-dish"></i></span>
            </div>

            <h2 class="ab-radar-title" id="radarStatusText">Menunggu Izin Lokasi GPS...</h2>
            <p class="ab-radar-desc" id="radarDesc">Sistem memverifikasi posisi Anda terhadap titik koordinat PLTU Adipala (Radius maksimum: {{ $company->attendance_radius_meters ?? 150 }} meter).</p>

            <div class="ab-actions">
                <button type="button" class="btn btn-sm btn-primary" onclick="getCurrentLocation()">
                    <i class="fa-solid fa-crosshairs"></i> Perbarui Lokasi Saya
                </button>
                <button type="button" class="btn btn-sm btn-outline" onclick="simulateInLocation()">
                    <i class="fa-solid fa-building-circle-check"></i> Gunakan Titik PLTU (Simulasi)
                </button>
            </div>
        </section>

        <!-- ================= METRIK LOKASI ================= -->
        <section class="ab-card c-span2">
            <div class="ab-head">
                <div>
                    <h3 class="ab-card-title">Metrik Lokasi</h3>
                    <div class="ab-card-cap">real-time</div>
                </div>
                <span class="ab-ico-btn"><i class="fa-solid fa-location-crosshairs"></i></span>
            </div>

            <div class="ab-metrics">
                <div class="ab-metric">
                    <div class="lbl">Jarak dari PLTU</div>
                    <div class="val" id="radarDistance">- m</div>
                </div>
                <div class="ab-metric">
                    <div class="lbl">Akurasi GPS</div>
                    <div class="val" id="radarAccuracy">- m</div>
                </div>
                <div class="ab-metric">
                    <div class="lbl">Koordinat Anda</div>
                    <div class="val" id="radarCoords" style="font-size: 13px; font-weight: 500;">-</div>
                </div>
                <div class="ab-metric">
                    <div class="lbl">Status Area</div>
                    <div class="val" id="radarZone" style="font-size: 14px; color: var(--warn);">Mengecek...</div>
                </div>
            </div>
        </section>

        <!-- ================= PRESENSI SELFIE ================= -->
        <section class="ab-card c-span2">
            <div class="ab-head">
                <div>
                    <h3 class="ab-card-title">Presensi Selfie &amp; Lokasi</h3>
                    <div class="ab-card-cap">check-in / check-out</div>
                </div>
                <span class="ab-ico-btn"><i class="fa-solid fa-camera"></i></span>
            </div>

            @if(!$attendanceToday || !$attendanceToday->check_in_at)
                <!-- Form Check In Berbasis Selfie & Live Geofence -->
                <form action="{{ route('peserta.absensi.checkin') }}" method="POST" id="checkInForm">
                    @csrf
                    <input type="hidden" name="latitude" id="inputLat" required>
                    <input type="hidden" name="longitude" id="inputLng" required>
                    <input type="hidden" name="accuracy" id="inputAcc">
                    <input type="hidden" name="bypass_geofence" id="bypassGeofence" value="0">
                    <input type="hidden" name="qr_token" id="qrTokenInput" value="{{ $dailyQrToken }}">
                    <input type="hidden" name="selfie_image" id="selfieInput" required>

                    <div style="margin-bottom: 18px;">
                        <div class="ab-field-head">
                            <label class="ab-field-label">
                                <i class="fa-solid fa-camera-retro"></i> Foto Selfie Presensi (Wajib)
                            </label>
                            <span id="selfieStatusBadge" class="badge badge-secondary">
                                <i class="fa-solid fa-circle-exclamation"></i> Menunggu Selfie
                            </span>
                        </div>

                        <!-- Tombol Buka Kamera -->
                        <div id="btnStartCameraBox">
                            <button type="button" class="btn btn-primary btn-block" onclick="startCamera('in')">
                                <i class="fa-solid fa-camera"></i> Buka Kamera &amp; Deteksi Lokasi
                            </button>
                            <small class="ab-hint">Kamera akan mendeteksi waktu HP dan status geofence lokasi secara live.</small>
                        </div>

                        <!-- Kamera + HUD real-time -->
                        <div id="cameraPreviewBox" class="ab-cam">
                            <video id="webcam" autoplay playsinline muted></video>
                            <canvas id="canvas" style="display: none;"></canvas>

                            <div class="ab-hud camera-hud-overlay">
                                <div class="ab-hud-row">
                                    <div>
                                        <div class="ab-hud-lbl"><i class="fa-solid fa-clock"></i> Waktu HP Real-Time</div>
                                        <div id="hudClock" class="ab-hud-clock">00:00:00 WIB</div>
                                        <div id="hudDate" class="ab-hud-date">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div id="hudRadiusBadge" class="ab-hud-badge">RADIUS: CEK...</div>
                                        <div id="hudDistance" class="ab-hud-dist">Jarak: - m</div>
                                    </div>
                                </div>

                                <div class="ab-face"><span>Posisikan Wajah</span></div>

                                <div>
                                    <div class="ab-hud-coords">
                                        <i class="fa-solid fa-location-dot"></i>
                                        <span id="hudCoords">Mencari koordinat GPS HP...</span>
                                    </div>
                                    <div class="ab-hud-site">PT PLN IP UBP Jateng 2 Adipala (Radius Maks: {{ $company->attendance_radius_meters ?? 150 }}m)</div>
                                </div>
                            </div>

                            <div class="ab-cam-actions">
                                <button type="button" class="btn btn-primary btn-sm" onclick="capturePhoto('in')">
                                    <i class="fa-solid fa-circle-dot"></i> Ambil Foto Selfie
                                </button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="switchCamera()">
                                    <i class="fa-solid fa-camera-rotate"></i> Putar Kamera
                                </button>
                                <button type="button" class="btn btn-stop btn-sm" onclick="stopCamera()">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                            </div>
                        </div>

                        <!-- Preview selfie terstempel -->
                        <div id="selfiePreviewBox" class="ab-preview">
                            <div class="ab-preview-wrap">
                                <img id="selfiePreview" src="" alt="Selfie presensi">
                                <span class="ab-stamp"><i class="fa-solid fa-check"></i> Terstempel Resmi</span>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline btn-sm" onclick="retakePhoto('in')">
                                    <i class="fa-solid fa-rotate-left"></i> Ambil Ulang Foto Selfie
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="checkin_notes">Catatan Presensi (Opsional)</label>
                        <input type="text" id="checkin_notes" name="notes" placeholder="Contoh: Bekerja di Ruang Server / Divisi SI" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" id="btnSubmitCheckin" disabled>
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Lakukan Check-In Masuk
                    </button>
                    <small id="submitHint" class="ab-hint">
                        * Harap ambil foto selfie presensi untuk mengaktifkan tombol check-in.
                    </small>
                </form>

            @elseif(!$attendanceToday->check_out_at)
                <!-- Sudah check-in, tinggal check-out -->
                <div class="ab-note">
                    <div class="ab-note-title"><i class="fa-solid fa-circle-check"></i> Anda Sudah Melakukan Check-In Masuk</div>
                    <div class="ab-note-text">
                        Waktu Masuk: <strong>{{ $attendanceToday->check_in_at->format('H:i') }} WIB</strong>
                        ({{ $attendanceToday->check_in_status === 'late' ? "Terlambat {$attendanceToday->late_minutes} menit" : 'Tepat Waktu' }})
                    </div>
                    @if($attendanceToday->selfie_path)
                        <div style="margin-top: 10px;">
                            <button type="button" onclick="viewSelfieModal('{{ asset($attendanceToday->selfie_path) }}', 'Bukti Selfie Masuk ({{ $attendanceToday->check_in_at->format('H:i') }} WIB)')" class="btn btn-outline btn-sm">
                                <i class="fa-solid fa-image"></i> Lihat Foto Selfie Masuk
                            </button>
                        </div>
                    @endif
                </div>

                <form action="{{ route('peserta.absensi.checkout') }}" method="POST" id="checkOutForm">
                    @csrf
                    <input type="hidden" name="latitude" id="inputLatOut">
                    <input type="hidden" name="longitude" id="inputLngOut">
                    <input type="hidden" name="selfie_image" id="selfieInputOut">

                    <div style="margin-bottom: 16px;">
                        <div class="ab-field-head">
                            <label class="ab-field-label">
                                <i class="fa-solid fa-camera-retro"></i> Foto Selfie Kepulangan (Opsional)
                            </label>
                            <span id="selfieStatusOutBadge" class="badge badge-secondary">Belum ada foto</span>
                        </div>

                        <div id="btnStartCameraOutBox">
                            <button type="button" class="btn btn-outline btn-block" onclick="startCamera('out')">
                                <i class="fa-solid fa-camera"></i> Ambil Selfie Kepulangan
                            </button>
                        </div>

                        <!-- Kamera + HUD (Check-Out) -->
                        <div id="cameraPreviewBox" class="ab-cam">
                            <video id="webcam" autoplay playsinline muted></video>
                            <canvas id="canvas" style="display: none;"></canvas>

                            <div class="ab-hud camera-hud-overlay">
                                <div class="ab-hud-row">
                                    <div>
                                        <div class="ab-hud-lbl"><i class="fa-solid fa-clock"></i> Waktu HP Real-Time (Kepulangan)</div>
                                        <div id="hudClock" class="ab-hud-clock">00:00:00 WIB</div>
                                        <div id="hudDate" class="ab-hud-date">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div id="hudRadiusBadge" class="ab-hud-badge">RADIUS: CEK...</div>
                                        <div id="hudDistance" class="ab-hud-dist">Jarak: - m</div>
                                    </div>
                                </div>

                                <div class="ab-face"><span>Posisikan Wajah</span></div>

                                <div>
                                    <div class="ab-hud-coords">
                                        <i class="fa-solid fa-location-dot"></i>
                                        <span id="hudCoords">Mencari koordinat GPS HP...</span>
                                    </div>
                                    <div class="ab-hud-site">PT PLN IP UBP Jateng 2 Adipala (Radius Maks: {{ $company->attendance_radius_meters ?? 150 }}m)</div>
                                </div>
                            </div>

                            <div class="ab-cam-actions">
                                <button type="button" class="btn btn-primary btn-sm" onclick="capturePhoto('out')">
                                    <i class="fa-solid fa-circle-dot"></i> Ambil Foto Selfie Pulang
                                </button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="switchCamera()">
                                    <i class="fa-solid fa-camera-rotate"></i> Putar Kamera
                                </button>
                                <button type="button" class="btn btn-stop btn-sm" onclick="stopCamera()">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                            </div>
                        </div>

                        <div id="selfiePreviewOutBox" class="ab-preview">
                            <div class="ab-preview-wrap">
                                <img id="selfiePreviewOut" src="" alt="Selfie kepulangan">
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline btn-sm" onclick="retakePhoto('out')">
                                    <i class="fa-solid fa-rotate-left"></i> Ambil Ulang Foto Pulang
                                </button>
                            </div>
                        </div>
                    </div>

                    <p class="ab-radar-desc" style="margin-bottom: 16px;">
                        Pastikan seluruh pekerjaan hari ini telah dirangkum dalam logbook sebelum melakukan check-out kepulangan.
                    </p>

                    <button type="submit" class="btn btn-success btn-block" id="btnSubmitCheckOut">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Lakukan Check-Out Pulang
                    </button>
                </form>

            @else
                <!-- Presensi hari ini lengkap -->
                <div class="ab-done">
                    <div class="ab-done-ico"><i class="fa-solid fa-circle-check"></i></div>
                    <h3>Presensi Hari Ini Lengkap</h3>
                    <p>
                        Masuk: {{ $attendanceToday->check_in_at->format('H:i') }} WIB &bull;
                        Pulang: {{ $attendanceToday->check_out_at->format('H:i') }} WIB
                    </p>
                    <div class="row">
                        @if($attendanceToday->selfie_path)
                            <button type="button" class="btn btn-outline btn-sm" onclick="viewSelfieModal('{{ asset($attendanceToday->selfie_path) }}', 'Bukti Selfie Masuk ({{ $attendanceToday->check_in_at->format('H:i') }} WIB)')">
                                <i class="fa-solid fa-image"></i> Foto Selfie Masuk
                            </button>
                        @endif
                        @if($attendanceToday->selfie_out_path)
                            <button type="button" class="btn btn-outline btn-sm" onclick="viewSelfieModal('{{ asset($attendanceToday->selfie_out_path) }}', 'Bukti Selfie Pulang ({{ $attendanceToday->check_out_at->format('H:i') }} WIB)')">
                                <i class="fa-solid fa-image"></i> Foto Selfie Pulang
                            </button>
                        @endif
                    </div>
                    <div style="margin-top: 16px;">
                        <span class="badge badge-success">Total Kerja: {{ floor($attendanceToday->total_work_minutes / 60) }} Jam {{ $attendanceToday->total_work_minutes % 60 }} Menit</span>
                    </div>
                </div>
            @endif
        </section>

        <!-- ================= KEBIJAKAN PRESENSI ================= -->
        <section class="ab-card c-span2">
            <div class="ab-head">
                <div>
                    <h3 class="ab-card-title">Kebijakan Presensi</h3>
                    <div class="ab-card-cap">PLTU Adipala</div>
                </div>
                <span class="ab-ico-btn"><i class="fa-solid fa-circle-info"></i></span>
            </div>

            <ul class="ab-policy">
                <li>
                    <span class="ab-pico tone-accent"><i class="fa-solid fa-clock"></i></span>
                    <div>
                        <strong>Jam Kerja Pokok</strong>
                        {{ substr($company->work_start_time ?? '08:00:00', 0, 5) }} WIB s/d {{ substr($company->work_end_time ?? '16:00:00', 0, 5) }} WIB (Senin - Jumat)
                    </div>
                </li>
                <li>
                    <span class="ab-pico tone-warn"><i class="fa-solid fa-hourglass-half"></i></span>
                    <div>
                        <strong>Toleransi Keterlambatan</strong>
                        Maksimal {{ $policy->late_tolerance_minutes ?? 15 }} menit (pukul {{ \Carbon\Carbon::createFromFormat('H:i:s', $company->work_start_time ?? '08:00:00')->addMinutes($policy->late_tolerance_minutes ?? 15)->format('H:i') }} WIB). Lebih dari itu tercatat status 'Terlambat'.
                    </div>
                </li>
                <li>
                    <span class="ab-pico tone-info"><i class="fa-solid fa-map-location-dot"></i></span>
                    <div>
                        <strong>Radius Geofencing</strong>
                        Maksimum {{ $company->attendance_radius_meters ?? 150 }} meter dari titik pusat PLTU Adipala.
                    </div>
                </li>
                <li>
                    <span class="ab-pico tone-ok"><i class="fa-solid fa-shield-halved"></i></span>
                    <div>
                        <strong>Integritas Presensi</strong>
                        Waktu presensi otomatis divalidasi oleh server, tidak dapat dimanipulasi dengan jam perangkat.
                    </div>
                </li>
            </ul>

            <div class="ab-callout">
                <i class="fa-solid fa-triangle-exclamation"></i> Mengalami kendala GPS atau tugas dinas lapangan? Silakan gunakan tombol <strong>Ajukan Koreksi Absensi</strong> di atas.
            </div>
        </section>

        <!-- ================= RIWAYAT PRESENSI ================= -->
        <section class="ab-card c-span4">
            <div class="ab-head">
                <div>
                    <h3 class="ab-card-title">Riwayat Presensi Magang</h3>
                    <div class="ab-card-cap">catatan kehadiran</div>
                </div>
                <span class="ab-ico-btn"><i class="fa-solid fa-clock-rotate-left"></i></span>
            </div>

            <div class="ab-table-wrap">
                <table class="ab-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Bukti Selfie</th>
                            <th>Status</th>
                            <th>Keterlambatan</th>
                            <th>Total Jam Kerja</th>
                            <th>Jarak Verifikasi</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $row)
                            <tr>
                                <td><strong>{{ $row->attendance_date->translatedFormat('d M Y') }}</strong></td>
                                <td>{{ $row->check_in_at ? $row->check_in_at->format('H:i') . ' WIB' : '-' }}</td>
                                <td>{{ $row->check_out_at ? $row->check_out_at->format('H:i') . ' WIB' : '-' }}</td>
                                <td>
                                    @if($row->selfie_path || $row->selfie_out_path)
                                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                            @if($row->selfie_path)
                                                <button type="button" class="btn btn-outline btn-sm" onclick="viewSelfieModal('{{ asset($row->selfie_path) }}', 'Bukti Selfie Masuk ({{ $row->attendance_date->translatedFormat('d M Y') }})')">
                                                    <i class="fa-solid fa-camera"></i> Masuk
                                                </button>
                                            @endif
                                            @if($row->selfie_out_path)
                                                <button type="button" class="btn btn-outline btn-sm" onclick="viewSelfieModal('{{ asset($row->selfie_out_path) }}', 'Bukti Selfie Pulang ({{ $row->attendance_date->translatedFormat('d M Y') }})')">
                                                    <i class="fa-solid fa-camera"></i> Pulang
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <span style="color: var(--muted);">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $row->check_in_status }}">
                                        {{ match($row->check_in_status) {
                                            'present' => 'Hadir Tepat',
                                            'late' => 'Terlambat',
                                            'leave' => 'Cuti / Izin',
                                            'sick' => 'Sakit',
                                            default => 'Alpa',
                                        } }}
                                    </span>
                                </td>
                                <td>{{ $row->late_minutes > 0 ? "{$row->late_minutes} menit" : '-' }}</td>
                                <td>{{ $row->total_work_minutes > 0 ? floor($row->total_work_minutes / 60) . 'j ' . ($row->total_work_minutes % 60) . 'm' : '-' }}</td>
                                <td>{{ $row->distance_in_meters ? "{$row->distance_in_meters} m" : '-' }}</td>
                                <td class="muted">{{ $row->notes ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="empty">Belum ada riwayat presensi tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="ab-pager">
                {{ $attendances->links() }}
            </div>
        </section>

        <!-- ================= RIWAYAT KOREKSI ================= -->
        <section class="ab-card c-span4">
            <div class="ab-head">
                <div>
                    <h3 class="ab-card-title">Riwayat Pengajuan Koreksi Absensi</h3>
                    <div class="ab-card-cap">status peninjauan pembimbing</div>
                </div>
                <span class="ab-ico-btn"><i class="fa-solid fa-list-check"></i></span>
            </div>

            <div class="ab-table-wrap">
                <table class="ab-table">
                    <thead>
                        <tr>
                            <th>Tgl Pengajuan</th>
                            <th>Tgl Absensi</th>
                            <th>Jenis Koreksi</th>
                            <th>Waktu Diajukan</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Catatan Pembimbing</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($corrections as $corr)
                            <tr>
                                <td>{{ $corr->created_at->format('d/m/Y H:i') }}</td>
                                <td><strong>{{ $corr->correction_date->translatedFormat('d M Y') }}</strong></td>
                                <td>{{ ucwords(str_replace('_', ' ', $corr->correction_type)) }}</td>
                                <td>
                                    @if($corr->requested_check_in_at) Masuk: {{ $corr->requested_check_in_at->format('H:i') }}<br>@endif
                                    @if($corr->requested_check_out_at) Pulang: {{ $corr->requested_check_out_at->format('H:i') }}@endif
                                </td>
                                <td class="muted" style="color: var(--text-2);">{{ $corr->reason }}</td>
                                <td>
                                    <span class="badge badge-{{ $corr->status }}">
                                        {{ match($corr->status) {
                                            'submitted' => 'Menunggu Review',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            default => $corr->status,
                                        } }}
                                    </span>
                                </td>
                                <td class="muted">{{ $corr->review_note ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty">Belum ada pengajuan koreksi absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</div>

    <!-- ================= MODAL KOREKSI ABSENSI ================= -->
    <div class="modal-overlay" id="koreksiModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fa-solid fa-file-pen"></i> Form Pengajuan Koreksi Absensi</h3>
                <button type="button" class="modal-close" onclick="closeKoreksiModal()" aria-label="Tutup">&times;</button>
            </div>
            <form action="{{ route('peserta.absensi.koreksi') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="corr_date">Tanggal Absensi *</label>
                            <input type="date" id="corr_date" name="correction_date" max="{{ date('Y-m-d') }}" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="corr_type">Jenis Koreksi *</label>
                            <select id="corr_type" name="correction_type" class="form-select" required>
                                <option value="check_in">Lupa / Koreksi Check-in Masuk</option>
                                <option value="check_out">Lupa / Koreksi Check-out Pulang</option>
                                <option value="check_in_and_check_out">Koreksi Masuk dan Pulang</option>
                                <option value="attendance_status">Koreksi Status Kehadiran (Dinas/Tugas Luar)</option>
                                <option value="other">Kendala Jaringan / GPS</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="corr_in">Jam Masuk yang Diajukan</label>
                            <input type="time" id="corr_in" name="requested_check_in_at" value="08:00" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="corr_out">Jam Pulang yang Diajukan</label>
                            <input type="time" id="corr_out" name="requested_check_out_at" value="16:00" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="corr_reason">Alasan Koreksi Lengkap *</label>
                        <textarea id="corr_reason" name="reason" rows="3" class="form-control" placeholder="Jelaskan alasan detail mengapa presensi tidak tercatat atau perlu dikoreksi..." required minlength="10"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="corr_file">Lampiran Bukti Pendukung (Foto/Surat Tugas/Screenshot GPS)</label>
                        <input type="file" id="corr_file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="form-control">
                        <small>Format: PDF/JPG/PNG. Maksimal 5 MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeKoreksiModal()">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL VIEWER BUKTI SELFIE ================= -->
    <div class="modal-overlay" id="modalSelfieViewer">
        <div class="modal-card" style="max-width: 480px; text-align: center;">
            <div class="modal-header">
                <h3 id="selfieViewerTitle"><i class="fa-solid fa-camera"></i> Bukti Foto Selfie Presensi</h3>
                <button type="button" class="modal-close" onclick="closeSelfieViewer()" aria-label="Tutup">&times;</button>
            </div>
            <div class="modal-body" style="padding: 16px;">
                <img id="selfieViewerImg" class="ab-viewer-img" src="" alt="Bukti selfie">
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn btn-outline" onclick="closeSelfieViewer()">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const targetLat = {{ $company->latitude ?? -7.680810191412908 }};
    const targetLng = {{ $company->longitude ?? 109.1366921585062 }};
    const allowedRadius = {{ $company->attendance_radius_meters ?? 150 }};

    const userName = "{{ $mhs->nama_lengkap }}";
    const userNim = "{{ $mhs->nim }}";
    const userDivisi = "{{ $penempatan->divisi->nama_divisi ?? 'PLTU Adipala' }}";

    let currentLat = targetLat;
    let currentLng = targetLng;
    let currentAcc = 15;
    let currentDist = 0;
    let isInsideRadius = true;
    let hasCapturedSelfie = false;
    let activeCameraMode = 'in'; // 'in' or 'out'
    let currentFacingMode = 'user'; // 'user' or 'environment'
    let streamObj = null;

    function updateLiveClockAndHud() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
        const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' });

        const elClock = document.getElementById('liveClock');
        if (elClock) elClock.innerText = timeStr;

        const hudClock = document.getElementById('hudClock');
        if (hudClock) hudClock.innerText = timeStr;

        const hudDate = document.getElementById('hudDate');
        if (hudDate) hudDate.innerText = dateStr;
    }
    setInterval(updateLiveClockAndHud, 1000);
    updateLiveClockAndHud();

    function deg2rad(deg) { return deg * (Math.PI / 180); }
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return Math.round(R * c);
    }

    function processLocation(lat, lng, acc) {
        currentLat = lat;
        currentLng = lng;
        currentAcc = acc || 15;
        currentDist = calculateDistance(lat, lng, targetLat, targetLng);
        isInsideRadius = (currentDist <= allowedRadius + 50);

        // Radar stats
        const radarDist = document.getElementById('radarDistance');
        if (radarDist) radarDist.innerText = currentDist + ' m';

        const radarAcc = document.getElementById('radarAccuracy');
        if (radarAcc) radarAcc.innerText = currentAcc ? Math.round(currentAcc) + ' m' : '±15 m';

        const radarCoords = document.getElementById('radarCoords');
        if (radarCoords) radarCoords.innerText = lat.toFixed(5) + ', ' + lng.toFixed(5);

        const zoneEl = document.getElementById('radarZone');
        const statusText = document.getElementById('radarStatusText');

        if (isInsideRadius) {
            if (zoneEl) {
                zoneEl.innerText = 'Di Dalam Area PLTU';
                zoneEl.style.color = 'var(--accent)';
            }
            if (statusText) statusText.innerText = 'Lokasi Terverifikasi (PLTU Adipala)';
        } else {
            if (zoneEl) {
                zoneEl.innerText = 'Di Luar Radius';
                zoneEl.style.color = 'var(--danger)';
            }
            if (statusText) statusText.innerText = 'Di Luar Area PLTU (' + currentDist + ' m)';
            const bypass = document.getElementById('bypassGeofence');
            if (bypass) bypass.value = '1';
        }

        // Form Inputs
        if (document.getElementById('inputLat')) document.getElementById('inputLat').value = lat;
        if (document.getElementById('inputLng')) document.getElementById('inputLng').value = lng;
        if (document.getElementById('inputAcc')) document.getElementById('inputAcc').value = currentAcc;
        if (document.getElementById('inputLatOut')) document.getElementById('inputLatOut').value = lat;
        if (document.getElementById('inputLngOut')) document.getElementById('inputLngOut').value = lng;

        // Update Camera Live HUD
        updateCameraHudUI();
    }

    function updateCameraHudUI() {
        const hudCoords = document.getElementById('hudCoords');
        if (hudCoords) {
            hudCoords.innerText = `${currentLat.toFixed(5)}, ${currentLng.toFixed(5)} (±${Math.round(currentAcc)}m)`;
        }

        const hudRadiusBadge = document.getElementById('hudRadiusBadge');
        const hudDistance = document.getElementById('hudDistance');

        if (hudRadiusBadge) {
            if (isInsideRadius) {
                hudRadiusBadge.innerText = 'DALAM RADIUS: TRUE';
                hudRadiusBadge.style.background = 'var(--ok-bg)';
                hudRadiusBadge.style.color = 'var(--ok)';
                hudRadiusBadge.style.borderColor = 'var(--ok-bd)';
            } else {
                hudRadiusBadge.innerText = 'DALAM RADIUS: FALSE';
                hudRadiusBadge.style.background = 'var(--danger-bg)';
                hudRadiusBadge.style.color = 'var(--danger)';
                hudRadiusBadge.style.borderColor = 'var(--danger-bd)';
            }
        }

        if (hudDistance) {
            hudDistance.innerText = `Jarak: ${currentDist} m (Maks: ${allowedRadius} m)`;
        }
    }

    function getCurrentLocation() {
        if (!navigator.geolocation) {
            console.warn('Geolocation tidak didukung/dibatasi browser (akses HTTP lokal). Menggunakan titik PLTU Adipala.');
            simulateInLocation();
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                processLocation(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy);
            },
            (err) => {
                console.warn('GPS error/timeout:', err);
                simulateInLocation();
            },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    }

    function simulateInLocation() {
        const simLat = targetLat + 0.00015;
        const simLng = targetLng + 0.00012;
        processLocation(simLat, simLng, 10);
    }

    // ==========================================
    // SISTEM KAMERA & SELFIE WATERMARK REAL-TIME
    // ==========================================
    function startCamera(mode = 'in') {
        activeCameraMode = mode;
        const box = document.getElementById('cameraPreviewBox');
        if (box) box.style.display = 'block';

        const btnBox = (mode === 'in') ? document.getElementById('btnStartCameraBox') : document.getElementById('btnStartCameraOutBox');
        if (btnBox) btnBox.style.display = 'none';

        const video = document.getElementById('webcam');
        if (!video) {
            console.error('Komponen video kamera tidak ditemukan di halaman.');
            alert('Komponen kamera tidak ditemukan di halaman. Silakan muat ulang halaman.');
            if (btnBox) btnBox.style.display = 'block';
            if (box) box.style.display = 'none';
            return;
        }

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Browser Anda tidak mendukung akses kamera secara langsung. Pastikan Anda membukanya melalui localhost atau HTTPS.');
            if (btnBox) btnBox.style.display = 'block';
            if (box) box.style.display = 'none';
            return;
        }

        // Hentikan stream lama jika ada
        stopCameraTracks();

        const constraints = {
            video: {
                facingMode: currentFacingMode,
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: false
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                streamObj = stream;
                if (video) {
                    video.srcObject = stream;
                    video.play().catch(e => console.warn('Video playback notice:', e));
                }
                updateCameraHudUI();
            })
            .catch(function(err) {
                console.warn('Camera error:', err);
                alert('Gagal membuka kamera: ' + err.message + '\n\nPastikan izin akses kamera telah diizinkan pada browser Anda.');
                if (btnBox) btnBox.style.display = 'block';
                if (box) box.style.display = 'none';
            });
    }

    function switchCamera() {
        currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user';
        startCamera(activeCameraMode);
    }

    function stopCamera() {
        stopCameraTracks();
        const box = document.getElementById('cameraPreviewBox');
        if (box) box.style.display = 'none';

        const btnBox = (activeCameraMode === 'in') ? document.getElementById('btnStartCameraBox') : document.getElementById('btnStartCameraOutBox');
        if (btnBox && !hasCapturedSelfie) btnBox.style.display = 'block';
    }

    function stopCameraTracks() {
        if (streamObj) {
            streamObj.getTracks().forEach(track => track.stop());
            streamObj = null;
        }
    }

    function drawWatermark(canvas) {
        const ctx = canvas.getContext('2d');
        const w = canvas.width;
        const h = canvas.height;

        const bannerHeight = Math.max(100, Math.round(h * 0.23));
        const bannerY = h - bannerHeight;

        // Background banner gelap
        ctx.fillStyle = "rgba(18, 20, 20, 0.88)";
        ctx.fillRect(0, bannerY, w, bannerHeight);

        // Garis aksen atas (lime jika TRUE, merah jika FALSE)
        ctx.fillStyle = isInsideRadius ? "#c6ec2f" : "#ff5a5a";
        ctx.fillRect(0, bannerY, w, 4);

        // Teks data presensi real-time
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
        const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' });
        const radiusStr = isInsideRadius ? "DALAM RADIUS: TRUE" : "DALAM RADIUS: FALSE";

        const padding = 16;
        const fontSize = Math.max(11, Math.round(w * 0.022));
        const lineSpacing = fontSize + 5;

        // Header Title
        ctx.font = `bold ${fontSize + 1}px "Inter", sans-serif`;
        ctx.fillStyle = "#c6ec2f";
        ctx.fillText("PLTU ADIPALA - PRESENSI MAGANG REAL-TIME", padding, bannerY + fontSize + 8);

        ctx.font = `${fontSize}px monospace`;
        let currY = bannerY + fontSize + 8 + lineSpacing;

        // Baris 1: Waktu Real-Time
        ctx.fillStyle = "#ffffff";
        ctx.fillText(`🕒 WAKTU  : ${timeStr} | ${dateStr}`, padding, currY);
        currY += lineSpacing;

        // Baris 2: Lokasi Real-Time
        const latStr = (typeof currentLat === 'number') ? currentLat.toFixed(6) : currentLat;
        const lngStr = (typeof currentLng === 'number') ? currentLng.toFixed(6) : currentLng;
        const accStr = currentAcc ? `±${Math.round(currentAcc)}m` : '±15m';
        ctx.fillText(`📍 LOKASI : Lat ${latStr}, Lng ${lngStr} (${accStr})`, padding, currY);
        currY += lineSpacing;

        // Baris 3: Status Radius Geofence (TRUE / FALSE)
        ctx.fillStyle = isInsideRadius ? "#c6ec2f" : "#ff9d9d";
        ctx.fillText(`🎯 GEOFENCE: ${radiusStr} (Jarak: ${currentDist}m)`, padding, currY);
        currY += lineSpacing;

        // Baris 4: Identitas Mahasiswa
        ctx.fillStyle = "#e5e7e6";
        ctx.font = `${fontSize - 1}px "Inter", sans-serif`;
        ctx.fillText(`👤 ${userName} (${userNim}) - ${userDivisi}`, padding, currY);
    }

    function capturePhoto(mode = 'in') {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');

        if (!video || !video.videoWidth) {
            alert('Kamera belum siap, silakan tunggu sebentar.');
            return;
        }

        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');

        // Balik horizontal jika kamera depan (mirror)
        if (currentFacingMode === 'user') {
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            ctx.setTransform(1, 0, 0, 1, 0, 0);
        } else {
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        }

        // Cetak watermark stempel jam & lokasi real-time
        drawWatermark(canvas);

        const dataUrl = canvas.toDataURL('image/png', 0.92);

        if (mode === 'in') {
            document.getElementById('selfieInput').value = dataUrl;
            document.getElementById('selfiePreview').src = dataUrl;
            document.getElementById('selfiePreviewBox').style.display = 'block';

            const statusBadge = document.getElementById('selfieStatusBadge');
            if (statusBadge) {
                statusBadge.className = 'badge badge-present';
                statusBadge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Foto Selfie Terverifikasi';
            }

            const btnCheckin = document.getElementById('btnSubmitCheckin');
            if (btnCheckin) btnCheckin.disabled = false;

            const submitHint = document.getElementById('submitHint');
            if (submitHint) {
                submitHint.style.color = 'var(--ok)';
                submitHint.innerHTML = '<i class="fa-solid fa-circle-check"></i> Foto selfie siap terstempel jam & lokasi. Silakan klik tombol di atas untuk Check-In.';
            }
        } else {
            const outInput = document.getElementById('selfieInputOut');
            if (outInput) outInput.value = dataUrl;

            const previewOut = document.getElementById('selfiePreviewOut');
            if (previewOut) previewOut.src = dataUrl;

            const previewOutBox = document.getElementById('selfiePreviewOutBox');
            if (previewOutBox) previewOutBox.style.display = 'block';

            const statusOutBadge = document.getElementById('selfieStatusOutBadge');
            if (statusOutBadge) {
                statusOutBadge.className = 'badge badge-present';
                statusOutBadge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Selfie Pulang Siap';
            }
        }

        hasCapturedSelfie = true;
        stopCameraTracks();
        const camBox = document.getElementById('cameraPreviewBox');
        if (camBox) camBox.style.display = 'none';
    }

    function retakePhoto(mode = 'in') {
        if (mode === 'in') {
            document.getElementById('selfieInput').value = '';
            document.getElementById('selfiePreviewBox').style.display = 'none';
            document.getElementById('btnSubmitCheckin').disabled = true;

            const statusBadge = document.getElementById('selfieStatusBadge');
            if (statusBadge) {
                statusBadge.className = 'badge badge-secondary';
                statusBadge.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Menunggu Selfie';
            }
        } else {
            const outInput = document.getElementById('selfieInputOut');
            if (outInput) outInput.value = '';
            document.getElementById('selfiePreviewOutBox').style.display = 'none';
        }

        hasCapturedSelfie = false;
        startCamera(mode);
    }

    // Modal Viewer Bukti Selfie
    function viewSelfieModal(url, title) {
        document.getElementById('selfieViewerImg').src = url;
        document.getElementById('selfieViewerTitle').innerHTML = '<i class="fa-solid fa-camera"></i> ' + title;
        document.getElementById('modalSelfieViewer').classList.add('active');
    }

    function closeSelfieViewer() {
        document.getElementById('modalSelfieViewer').classList.remove('active');
    }

    // Modal Koreksi
    function openKoreksiModal() {
        document.getElementById('koreksiModal').classList.add('active');
    }
    function closeKoreksiModal() {
        document.getElementById('koreksiModal').classList.remove('active');
    }

    document.addEventListener('DOMContentLoaded', function() {
        getCurrentLocation();
    });
</script>
@endpush
