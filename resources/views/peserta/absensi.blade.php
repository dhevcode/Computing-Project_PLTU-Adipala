@extends('peserta.layouts.app')

@section('title', 'Presensi & Koreksi')
@section('page_title', 'Presensi & Geofencing')
@section('page_subtitle', 'Sistem absensi berbasis verifikasi geospasial GPS dan stempel waktu live PLTU Adipala.')

@push('styles')
<style>
    /* ==========================================================================
       MODUL PRESENSI - TEMA CLEAN DASHBOARD & RESPONSIVE
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
    .header-info h2 { font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px 0; }
    .header-info p { font-size: 13.5px; color: var(--muted); margin: 0; line-height: 1.4; }
    .header-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

    .btn-pill {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
        background: #fff; border: 1px solid var(--border); border-radius: 8px;
        font-size: 13.5px; font-weight: 700; color: var(--text); text-decoration: none; transition: 0.2s; cursor: default;
    }
    .btn-pill i { color: var(--muted); }
    .btn-pill.warning { background: #FFFBEB; color: #D97706; border-color: #FDE68A; cursor: pointer; }
    .btn-pill.warning:hover { background: #FEF08A; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(217, 119, 6, 0.15); }

    /* ================= GRID & KARTU ================= */
    .bento-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    .span-2 { grid-column: span 2; }
    .span-3 { grid-column: span 3; }

    .card {
        background: #fff; border: 1px solid var(--border); border-radius: 16px;
        padding: 24px; display: flex; flex-direction: column;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); color: var(--text); height: 100%;
        animation: fadeInUp 0.4s ease-out both; transition: 0.3s;
    }
    .card:hover { border-color: #CBD5E1; box-shadow: 0 6px 20px rgba(0,0,0,0.05); }

    /* Staggered Card Animations */
    .bento-grid > section:nth-child(1) { animation-delay: 0.1s; }
    .bento-grid > section:nth-child(2) { animation-delay: 0.2s; }
    .bento-grid > section:nth-child(3) { animation-delay: 0.3s; }
    .bento-grid > section:nth-child(4) { animation-delay: 0.4s; }
    .bento-grid > section:nth-child(5) { animation-delay: 0.5s; }

    .card-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 20px; border-bottom: 1px dashed var(--page); padding-bottom: 16px; }
    .card-title { margin: 0; font-size: 16px; font-weight: 800; color: var(--text); }
    .card-subtitle { font-size: 12.5px; color: var(--muted); margin-top: 4px; }

    .icon-box {
        width: 48px; height: 48px; border-radius: 50%;
        flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 20px;
        color: var(--primary); background: var(--primary-soft);
    }

    /* Action Buttons */
    .btn-action-group { margin-top: auto; padding-top: 20px; display: flex; gap: 12px; flex-wrap: wrap; }
    .btn-custom {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 42px; padding: 0 20px; border-radius: 8px; font-size: 13.5px; font-weight: 700;
        border: 1px solid transparent; cursor: pointer; transition: 0.2s;
    }
    .btn-custom:disabled { opacity: 0.6; cursor: not-allowed; }
    .btn-block { width: 100%; height: 46px; font-size: 14.5px; }

    .btn-primary { background: var(--primary); color: #fff; border-color: var(--primary); }
    .btn-primary:hover:not(:disabled) { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); border-color: var(--primary-dark); }

    .btn-outline { background: #fff; color: var(--text); border-color: var(--border); }
    .btn-outline:hover { background: var(--page); border-color: #CBD5E1; }

    .btn-stop { background: #FEF2F2; color: #DC2626; border-color: #FECACA; }
    .btn-stop:hover { background: #FCA5A5; color: #991B1B; }

    /* Badges */
    .badge-label {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px;
        font-size: 11.5px; font-weight: 700; border: 1px solid transparent; white-space: nowrap;
    }
    .badge-ok { background: #DCFCE7; color: #166534; border-color: #BBF7D0; }
    .badge-warn { background: #FEF9C3; color: #854D0E; border-color: #FEF08A; }
    .badge-danger { background: #FEE2E2; color: #991B1B; border-color: #FECACA; }
    .badge-info { background: var(--page); color: var(--muted); border-color: var(--border); }

    /* Mini Grid Info Lokasi */
    .metric-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-top: 16px; }
    .metric-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
    .metric-card .lbl { font-size: 11.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px; }
    .metric-card .val { font-size: 18px; font-weight: 800; color: var(--text); word-break: break-word; }

    /* Kamera */
    .cam-wrapper {
        display: none; position: relative; margin: 16px 0; overflow: hidden; border-radius: 12px;
        background: #0F172A; border: 1px solid var(--border); box-shadow: 0 4px 14px rgba(0,0,0,0.1);
        animation: fadeIn 0.4s ease-out;
    }
    .cam-wrapper video { width: 100%; height: auto; min-height: 280px; max-height: 400px; object-fit: cover; display: block; transform: scaleX(-1); }
    .cam-hud {
        position: absolute; inset: 0 0 auto 0; bottom: 60px; pointer-events: none; padding: 16px;
        display: flex; flex-direction: column; justify-content: space-between;
        background: linear-gradient(180deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 25%, rgba(0,0,0,0) 65%, rgba(0,0,0,0.8) 100%);
    }
    .hud-header { display: flex; justify-content: space-between; align-items: flex-start; }
    .hud-title { font-size: 10.5px; font-weight: 800; color: #34D399; letter-spacing: 0.05em; margin-bottom: 4px; }
    .hud-time { font-size: 18px; font-weight: 800; color: #ffffff; text-shadow: 0 2px 6px rgba(0,0,0,0.8); }
    .hud-date { font-size: 11.5px; font-weight: 600; color: #F8FAFC; text-shadow: 0 1px 3px rgba(0,0,0,0.8); }
    .hud-badge-status { display: inline-block; padding: 6px 14px; border-radius: 6px; font-size: 10.5px; font-weight: 700; background: #EF4444; color: #ffffff; transition: 0.3s; }
    .hud-face-guide { align-self: center; width: 160px; height: 190px; border: 2px dashed rgba(255,255,255,0.7); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .hud-face-guide span { font-size: 11.5px; font-weight: 700; color: rgba(255,255,255,0.9); text-transform: uppercase; text-shadow: 0 1px 4px rgba(0,0,0,0.8); }
    .hud-footer-coords { font-size: 12.5px; font-weight: 600; color: #ffffff; display: flex; align-items: center; gap: 6px; text-shadow: 0 1px 4px rgba(0,0,0,0.8); }
    .hud-footer-coords i { color: #EF4444; }
    .cam-actions-bar { height: 60px; padding: 0 16px; display: flex; gap: 12px; justify-content: center; align-items: center; background: #0F172A; }
    .preview-box { display: none; margin-top: 16px; text-align: center; animation: fadeIn 0.4s; }
    .preview-box img { width: 100%; max-height: 320px; border-radius: 12px; border: 4px solid var(--primary); object-fit: cover; }

    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 13.5px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
    .input-box { width: 100%; padding: 12px 16px; font-family: inherit; font-size: 14px; color: var(--text); background: #fff; border: 1.5px solid var(--border); border-radius: 12px; transition: 0.2s; }
    .input-box:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }

    .notice-box { background: var(--primary-soft); border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; padding: 16px; margin-bottom: 24px; }
    .notice-box h4 { margin: 0 0 8px 0; font-size: 14.5px; font-weight: 800; color: var(--primary-dark); display: flex; align-items: center; gap: 8px; }
    .notice-box p { margin: 0; font-size: 13.5px; color: var(--text); font-weight: 500; }

    .policy-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 16px; }
    .policy-item { display: flex; gap: 16px; align-items: flex-start; padding-bottom: 16px; border-bottom: 1px dashed var(--page); }
    .policy-item:last-child { border-bottom: none; padding-bottom: 0; }
    .policy-item h4 { margin: 0 0 4px 0; font-size: 14.5px; font-weight: 800; color: var(--text); }
    .policy-item p { margin: 0; font-size: 13.5px; color: var(--muted); line-height: 1.5; }

    /* ==========================================================================
       TABEL RESPONSIVE ADMIN STANDARD
       ========================================================================== */
    .table-container { width: 100%; overflow: hidden; border: 1px solid var(--border); border-radius: 12px; background: #fff; }
    .admin-table { width: 100%; border-collapse: collapse; }
    .admin-table th { text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; color: var(--muted); background: var(--page); border-bottom: 1px solid var(--border); text-transform: uppercase; }
    .admin-table td { padding: 14px 16px; font-size: 13.5px; color: var(--text); border-bottom: 1px solid var(--page); vertical-align: middle; }
    .admin-table tr:hover td { background: #F8FAFC; }

    .td-action-group { display: flex; gap: 8px; align-items: center; justify-content: flex-start; }
    .note-truncate { max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--muted); }

    /* ================= PAGINATION FIX ================= */
    .pager-container { margin-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; width: 100%; }
    .pager-container nav { display: flex; flex-direction: row; gap: 16px; align-items: center; justify-content: space-between; width: 100%; }
    .pager-container p { margin: 0; font-size: 13.5px !important; color: var(--muted) !important; font-weight: 500; }

    .pager-container svg { width: 18px !important; height: 18px !important; }
    .pager-container .relative.z-0.inline-flex, .pager-container .pagination { display: inline-flex; flex-wrap: wrap; gap: 6px; box-shadow: none !important; margin: 0; padding: 0; }
    .pager-container a, .pager-container span[aria-disabled] > span, .pager-container span[aria-current] > span, .pager-container .page-link {
        display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 10px; font-size: 13.5px; font-weight: 700; text-decoration: none; line-height: 1; color: var(--text); background: #fff; border: 1px solid var(--border); border-radius: 8px !important; transition: 0.2s; margin: 0 !important;
    }
    .pager-container a:hover, .pager-container .page-link:hover { background: var(--page); border-color: #CBD5E1; z-index: 2; }
    .pager-container span[aria-current] > span, .pager-container .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); z-index: 3; }
    .pager-container span[aria-disabled] > span, .pager-container .page-item.disabled .page-link { background: var(--page); color: #CBD5E1; cursor: not-allowed; border-color: var(--page); }
    .pager-container nav > div.flex.justify-between.flex-1.sm\:hidden { display: none; }

    /* Modals Animation */
    .modal-backdrop:not(.d-none) { animation: fadeIn 0.25s ease-out forwards; }
    .modal-backdrop:not(.d-none) .modal-box { animation: slideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    /* Transform Tabel Menjadi Kartu di Layar Mobile */
    @media (max-width: 992px) {
        .bento-grid { grid-template-columns: 1fr; }
        .span-2, .span-3 { grid-column: auto; }
    }
    @media (max-width: 768px) {
        .dashboard-header { flex-direction: column; align-items: flex-start; gap: 16px; }
        .header-actions { width: 100%; justify-content: space-between; }
        .btn-pill { width: 100%; justify-content: center; }

        .table-container { border: none; background: transparent; overflow-x: hidden; }
        .admin-table, .admin-table tbody, .admin-table tr, .admin-table td { display: block; width: 100%; }
        .admin-table thead { display: none; }
        .admin-table tr { background: #fff; margin-bottom: 16px; border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 2px 8px rgba(0,0,0,0.02); padding: 8px 16px; }
        .admin-table td { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 12px 0; border-bottom: 1px dashed var(--page); text-align: right; white-space: normal; }
        .admin-table td:last-child { border-bottom: none; }
        .admin-table td::before { content: attr(data-label); font-size: 11.5px; font-weight: 800; color: var(--muted); text-align: left; flex-shrink: 0; text-transform: uppercase; }
        .admin-table td > * { max-width: 60%; text-align: right; }

        .td-action-group { justify-content: flex-end; }
        .note-truncate { max-width: 100px; text-align: right; }
        .admin-table tr.empty-row td { text-align: center; justify-content: center; border: none; padding: 24px 10px; }
        .admin-table tr.empty-row td::before { display: none; }
        .admin-table tr.empty-row td > * { max-width: 100%; text-align: center; }
    }
</style>
@endpush

@section('content')

<!-- ================= HEADER DASHBOARD ================= -->
<div class="dashboard-header">
    <div class="header-info">
        <h2>Presensi & Geofencing</h2>
        <p>Lakukan absen harian secara akurat dengan validasi titik kordinat area perusahaan.</p>
    </div>
    <div class="header-actions">
        <span class="btn-pill" style="cursor: default;"><i class="fa-regular fa-clock"></i> <span id="liveClock" style="font-family: monospace; font-size: 14.5px;">00:00:00 WIB</span></span>
        <button type="button" class="btn-pill warning" onclick="openKoreksiModal()">
            <i class="fa-solid fa-file-pen"></i> Ajukan Koreksi
        </button>
    </div>
</div>

<div class="bento-grid">

    <!-- ================= 1. STATUS DETEKSI LOKASI ================= -->
    <section class="card span-2">
        <div class="card-head">
            <div>
                <h3 class="card-title">Status Deteksi Lokasi GPS</h3>
                <div class="card-subtitle">Geofence Sistem PLTU Adipala</div>
            </div>
            <div class="icon-box"><i class="fa-solid fa-satellite-dish"></i></div>
        </div>

        <h2 id="radarStatusText" style="margin: 0 0 6px 0; font-size: 22px; font-weight: 800; color: var(--text);">Menunggu Izin GPS...</h2>
        <p id="radarDesc" style="margin: 0; font-size: 13.5px; color: var(--muted); line-height: 1.6;">
            Sistem memverifikasi posisi koordinat perangkat Anda terhadap titik PLTU Adipala (Radius Maksimal: {{ $company->attendance_radius_meters ?? 150 }} m).
        </p>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="lbl">Jarak Saat Ini</div>
                <div class="val" id="radarDistance" style="color: var(--primary);">- m</div>
            </div>
            <div class="metric-card">
                <div class="lbl">Status Area Geofence</div>
                <div class="val" id="radarZone" style="color: #D97706;">Sedang Mengecek...</div>
            </div>
        </div>

        <div class="btn-action-group">
            <button type="button" class="btn-custom btn-primary" onclick="getCurrentLocation()">
                <i class="fa-solid fa-crosshairs"></i> Perbarui Lokasi GPS
            </button>
            <button type="button" class="btn-custom btn-outline" onclick="simulateInLocation()">
                <i class="fa-solid fa-building-circle-check"></i> Simulasi Area PLTU
            </button>
        </div>
    </section>

    <!-- ================= 2. KEBIJAKAN PRESENSI ================= -->
    <section class="card">
        <div class="card-head">
            <div>
                <h3 class="card-title">Kebijakan Presensi</h3>
                <div class="card-subtitle">Aturan resmi operasional magang</div>
            </div>
            <div class="icon-box" style="background: #FFFBEB; color: #D97706;"><i class="fa-solid fa-circle-info"></i></div>
        </div>

        <ul class="policy-list">
            <li class="policy-item">
                <div style="font-size: 20px; color: var(--primary); margin-top: 2px;"><i class="fa-solid fa-business-time"></i></div>
                <div>
                    <h4>Jam Kerja Pokok</h4>
                    <p>{{ substr($company->work_start_time ?? '08:00', 0, 5) }} - {{ substr($company->work_end_time ?? '16:00', 0, 5) }} WIB (Senin-Jumat)</p>
                </div>
            </li>
            <li class="policy-item">
                <div style="font-size: 20px; color: #D97706; margin-top: 2px;"><i class="fa-solid fa-hourglass-half"></i></div>
                <div>
                    <h4>Batas Toleransi</h4>
                    <p>Maksimal keterlambatan check-in adalah {{ $policy->late_tolerance_minutes ?? 15 }} menit.</p>
                </div>
            </li>
            <li class="policy-item">
                <div style="font-size: 20px; color: #10B981; margin-top: 2px;"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <h4>Integritas Sistem</h4>
                    <p>Waktu presensi terkunci pada stempel server dan watermark kamera.</p>
                </div>
            </li>
        </ul>
    </section>

    <!-- ================= 3. PRESENSI SELFIE LIVE ================= -->
    <section class="card span-3">
        <div class="card-head">
            <div>
                <h3 class="card-title">Presensi Selfie & Verifikasi Kehadiran</h3>
                <div class="card-subtitle">Formulir harian untuk check-in dan check-out</div>
            </div>
            <div class="icon-box" style="background: #ECFDF5; color: #10B981;"><i class="fa-solid fa-camera"></i></div>
        </div>

        @if(!$attendanceToday || !$attendanceToday->check_in_at)
            <!-- ======== CHECK IN PENDING ======== -->
            <form action="{{ route('peserta.absensi.checkin') }}" method="POST" id="checkInForm">
                @csrf
                <input type="hidden" name="latitude" id="inputLat" required>
                <input type="hidden" name="longitude" id="inputLng" required>
                <input type="hidden" name="accuracy" id="inputAcc">
                <input type="hidden" name="bypass_geofence" id="bypassGeofence" value="0">
                <input type="hidden" name="qr_token" id="qrTokenInput" value="{{ $dailyQrToken ?? '' }}">
                <input type="hidden" name="selfie_image" id="selfieInput" required>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <label style="font-size: 14px; font-weight: 800; color: var(--text); margin: 0;"><i class="fa-solid fa-camera-retro" style="color: var(--primary);"></i> Wajib Mengambil Foto Selfie & Lokasi</label>
                    <span id="selfieStatusBadge" class="badge-label badge-info"><i class="fa-solid fa-circle-exclamation"></i> Kamera Belum Aktif</span>
                </div>

                <div id="btnStartCameraBox" style="margin-bottom: 24px;">
                    <button type="button" class="btn-custom btn-primary btn-block" onclick="startCamera('in')">
                        <i class="fa-solid fa-camera"></i> Buka Kamera Presensi
                    </button>
                </div>

                <!-- Viewfinder -->
                <div id="cameraPreviewBox" class="cam-wrapper">
                    <video id="webcam" autoplay playsinline muted></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                    <div class="cam-hud">
                        <div class="hud-header">
                            <div>
                                <div class="hud-title">SISTEM GEOFENCE AKTIF</div>
                                <div id="hudClock" class="hud-time">00:00:00 WIB</div>
                                <div id="hudDate" class="hud-date">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d M Y') }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div id="hudRadiusBadge" class="hud-badge-status">Radius: CEK...</div>
                                <div id="hudDistance" style="font-size: 11.5px; color: #fff; margin-top: 4px; font-weight: 600;">Jarak: - m</div>
                            </div>
                        </div>
                        <div class="hud-face-guide"><span>Arahkan Wajah</span></div>
                        <div>
                            <div class="hud-footer-coords"><i class="fa-solid fa-location-dot"></i> <span id="hudCoords">Mencari kordinat GPS...</span></div>
                        </div>
                    </div>
                    <div class="cam-actions-bar">
                        <button type="button" class="btn-custom btn-primary" style="height: 38px; border-radius: 50px;" onclick="capturePhoto('in')"><i class="fa-solid fa-circle-dot"></i> Potret</button>
                        <button type="button" class="btn-custom btn-outline" style="height: 38px; border-radius: 50px;" onclick="switchCamera()"><i class="fa-solid fa-camera-rotate"></i> Putar</button>
                        <button type="button" class="btn-custom btn-stop" style="height: 38px; border-radius: 50px;" onclick="stopCamera()">Tutup</button>
                    </div>
                </div>

                <div id="selfiePreviewBox" class="preview-box">
                    <img id="selfiePreview" src="" alt="Selfie preview">
                    <div style="margin-top: 16px;">
                        <button type="button" class="btn-custom btn-outline" style="height: 38px;" onclick="retakePhoto('in')"><i class="fa-solid fa-rotate-left"></i> Ambil Ulang Foto</button>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 24px;">
                    <label for="checkin_notes">Keterangan / Catatan Tugas (Opsional)</label>
                    <input type="text" id="checkin_notes" name="notes" placeholder="Cth: Penugasan langsung di area unit boiler..." class="input-box">
                </div>

                <button type="submit" class="btn-custom btn-primary btn-block" id="btnSubmitCheckin" style="margin-top: 24px; font-size: 15px;" disabled>
                    <i class="fa-solid fa-right-to-bracket"></i> Konfirmasi Check-In Masuk
                </button>
            </form>

        @elseif(!$attendanceToday->check_out_at)
            <!-- ======== CHECK OUT PENDING ======== -->
            <div class="notice-box">
                <h4><i class="fa-solid fa-circle-check"></i> Proses Check-In Berhasil!</h4>
                <p>Waktu Masuk Anda: <strong>{{ $attendanceToday->check_in_at->format('H:i') }} WIB</strong>
                ({{ $attendanceToday->check_in_status === 'late' ? "Terlambat {$attendanceToday->late_minutes} Menit" : 'Tepat Waktu' }})</p>
            </div>

            <form action="{{ route('peserta.absensi.checkout') }}" method="POST" id="checkOutForm">
                @csrf
                <input type="hidden" name="latitude" id="inputLatOut">
                <input type="hidden" name="longitude" id="inputLngOut">
                <input type="hidden" name="selfie_image" id="selfieInputOut">

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <label style="font-size: 14px; font-weight: 800; color: var(--text); margin: 0;"><i class="fa-solid fa-camera-retro" style="color: var(--primary);"></i> Verifikasi Selfie Kepulangan (Opsional)</label>
                    <span id="selfieStatusOutBadge" class="badge-label badge-info">Belum Ada Foto Terlampir</span>
                </div>

                <div id="btnStartCameraOutBox" style="margin-bottom: 24px;">
                    <button type="button" class="btn-custom btn-outline btn-block" onclick="startCamera('out')">
                        <i class="fa-solid fa-camera"></i> Buka Kamera Untuk Bukti Pulang
                    </button>
                </div>

                <div id="cameraPreviewBox" class="cam-wrapper">
                    <video id="webcam" autoplay playsinline muted></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                    <div class="cam-hud">
                        <div class="hud-header">
                            <div>
                                <div class="hud-title">SISTEM GEOFENCE (OUT)</div>
                                <div id="hudClock" class="hud-time">00:00:00 WIB</div>
                                <div id="hudDate" class="hud-date">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d M Y') }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div id="hudRadiusBadge" class="hud-badge-status">Radius: CEK...</div>
                                <div id="hudDistance" style="font-size: 11.5px; color: #fff; margin-top: 4px; font-weight: 600;">Jarak: - m</div>
                            </div>
                        </div>
                        <div class="hud-face-guide"><span>Arahkan Wajah</span></div>
                        <div>
                            <div class="hud-footer-coords"><i class="fa-solid fa-location-dot"></i> <span id="hudCoords">Mencari kordinat...</span></div>
                        </div>
                    </div>
                    <div class="cam-actions-bar">
                        <button type="button" class="btn-custom btn-primary" style="height: 38px; border-radius: 50px;" onclick="capturePhoto('out')"><i class="fa-solid fa-circle-dot"></i> Potret</button>
                        <button type="button" class="btn-custom btn-outline" style="height: 38px; border-radius: 50px;" onclick="switchCamera()"><i class="fa-solid fa-camera-rotate"></i> Putar</button>
                        <button type="button" class="btn-custom btn-stop" style="height: 38px; border-radius: 50px;" onclick="stopCamera()">Tutup</button>
                    </div>
                </div>

                <div id="selfiePreviewOutBox" class="preview-box">
                    <img id="selfiePreviewOut" src="" alt="Selfie preview pulang">
                    <div style="margin-top: 16px;">
                        <button type="button" class="btn-custom btn-outline" style="height: 38px;" onclick="retakePhoto('out')"><i class="fa-solid fa-rotate-left"></i> Ulangi Foto</button>
                    </div>
                </div>

                <div style="background: var(--page); border: 1px dashed var(--border); padding: 16px; border-radius: 12px; margin: 24px 0; font-size: 13.5px; color: var(--text);">
                    <i class="fa-solid fa-circle-info" style="color: var(--primary); margin-right: 6px;"></i> Mohon dipastikan bahwa <strong style="color: var(--primary-dark);">laporan logbook harian</strong> Anda sudah dilengkapi sebelum melakukan konfirmasi Check-Out.
                </div>

                <button type="submit" class="btn-custom btn-primary btn-block" id="btnSubmitCheckOut" style="font-size: 15px;">
                    <i class="fa-solid fa-right-from-bracket"></i> Konfirmasi Check-Out Kepulangan
                </button>
            </form>

        @else
            <!-- ======== SELESAI ======== -->
            <div style="text-align: center; padding: 48px 0;">
                <div style="width: 80px; height: 80px; background: #ECFDF5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; color: #10B981; margin: 0 auto 16px; border: 2px solid #A7F3D0;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h3 style="font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 8px;">Sesi Presensi Hari Ini Selesai</h3>
                <p style="font-size: 14px; color: var(--muted); margin: 0 0 24px;">Jam Masuk: <strong>{{ $attendanceToday->check_in_at->format('H:i') }} WIB</strong> &nbsp;&bull;&nbsp; Jam Pulang: <strong>{{ $attendanceToday->check_out_at->format('H:i') }} WIB</strong></p>

                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    @if($attendanceToday->selfie_path)
                        <button type="button" class="btn-custom btn-outline" onclick="viewSelfieModal('{{ asset($attendanceToday->selfie_path) }}', 'Bukti Selfie Masuk')">
                            <i class="fa-solid fa-image" style="color: var(--primary);"></i> Lihat Foto Masuk
                        </button>
                    @endif
                    @if($attendanceToday->selfie_out_path)
                        <button type="button" class="btn-custom btn-outline" onclick="viewSelfieModal('{{ asset($attendanceToday->selfie_out_path) }}', 'Bukti Selfie Pulang')">
                            <i class="fa-solid fa-image" style="color: var(--primary);"></i> Lihat Foto Pulang
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </section>

    <!-- ================= 4. TABEL RIWAYAT PRESENSI ================= -->
    <section class="card span-3">
        <div class="card-head">
            <div>
                <h3 class="card-title">Riwayat Log Kehadiran</h3>
                <div class="card-subtitle">Data rekapitulasi waktu absen Anda</div>
            </div>
            <div class="icon-box" style="background: var(--page); color: var(--muted);"><i class="fa-solid fa-table"></i></div>
        </div>

        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>TANGGAL</th>
                        <th>IN</th>
                        <th>OUT</th>
                        <th>BUKTI FOTO</th>
                        <th>STATUS</th>
                        <th>KETERLAMBATAN</th>
                        <th>DURASI KERJA</th>
                        <th>CATATAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances ?? [] as $row)
                        <tr>
                            <td data-label="TANGGAL" style="font-weight: 800;">{{ $row->attendance_date->translatedFormat('d M Y') }}</td>
                            <td data-label="IN">{{ $row->check_in_at ? $row->check_in_at->format('H:i') . ' WIB' : '-' }}</td>
                            <td data-label="OUT">{{ $row->check_out_at ? $row->check_out_at->format('H:i') . ' WIB' : '-' }}</td>
                            <td data-label="BUKTI FOTO">
                                @if($row->selfie_path || $row->selfie_out_path)
                                    <div class="td-action-group">
                                        @if($row->selfie_path) <button type="button" class="btn-custom btn-outline" style="height: 28px; padding: 0 10px; font-size: 11.5px; border-radius: 6px;" onclick="viewSelfieModal('{{ asset($row->selfie_path) }}', 'Selfie Bukti Masuk')"><i class="fa-solid fa-image"></i> IN</button> @endif
                                        @if($row->selfie_out_path) <button type="button" class="btn-custom btn-outline" style="height: 28px; padding: 0 10px; font-size: 11.5px; border-radius: 6px;" onclick="viewSelfieModal('{{ asset($row->selfie_out_path) }}', 'Selfie Bukti Pulang')"><i class="fa-solid fa-image"></i> OUT</button> @endif
                                    </div>
                                @else
                                    <span style="color: var(--muted);">-</span>
                                @endif
                            </td>
                            <td data-label="STATUS">
                                @php
                                    $st = $row->check_in_status;
                                    $bClass = 'badge-info'; $tStatus = 'Alpa';
                                    if($st == 'present') { $bClass = 'badge-ok'; $tStatus = 'Hadir'; }
                                    elseif($st == 'late') { $bClass = 'badge-warn'; $tStatus = 'Telat'; }
                                    elseif($st == 'leave') { $bClass = 'badge-ok'; $tStatus = 'Izin'; }
                                    elseif($st == 'sick') { $bClass = 'badge-warn'; $tStatus = 'Sakit'; }
                                @endphp
                                <span class="badge-label {{ $bClass }}">{{ $tStatus }}</span>
                            </td>
                            <td data-label="KETERLAMBATAN" style="color: {{ $row->late_minutes > 0 ? '#EF4444' : 'inherit' }}; font-weight: {{ $row->late_minutes > 0 ? '800' : '500' }}">{{ $row->late_minutes > 0 ? "{$row->late_minutes} Mnt" : '-' }}</td>
                            <td data-label="DURASI KERJA">{{ $row->total_work_minutes > 0 ? floor($row->total_work_minutes / 60) . 'j ' . ($row->total_work_minutes % 60) . 'm' : '-' }}</td>
                            <td data-label="CATATAN">
                                @if($row->notes)
                                    <div class="td-action-group">
                                        <span class="note-truncate">{{ $row->notes }}</span>
                                        <button type="button" class="btn-custom btn-outline" style="height: 26px; padding: 0 8px; font-size: 11px; border-radius: 6px;" data-note="{{ $row->notes }}" onclick="viewNoteModal(this.getAttribute('data-note'), 'Keterangan Absensi')"><i class="fa-solid fa-eye"></i> Baca</button>
                                    </div>
                                @else
                                    <span style="color: var(--muted);">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="8" style="text-align: center; color: var(--muted); padding: 40px;">Belum ada log riwayat presensi harian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(($attendances ?? null) && $attendances->hasPages())
        <div class="pager-container">
            {{ $attendances->links() }}
        </div>
        @endif
    </section>

    <!-- ================= 5. TABEL RIWAYAT KOREKSI ================= -->
    <section class="card span-3" style="margin-bottom: 24px;">
        <div class="card-head">
            <div>
                <h3 class="card-title">Riwayat Pengajuan Koreksi</h3>
                <div class="card-subtitle">Status permintaan perbaikan absen ke pembimbing</div>
            </div>
            <div class="icon-box" style="background: #FFFBEB; color: #D97706;"><i class="fa-solid fa-file-pen"></i></div>
        </div>

        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>TGL DIBUAT</th>
                        <th>TGL ABSEN</th>
                        <th>JENIS KOREKSI</th>
                        <th>JAM PENGAJUAN</th>
                        <th>ALASAN KOREKSI</th>
                        <th>STATUS</th>
                        <th>TANGGAPAN PEMBIMBING</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($corrections ?? [] as $corr)
                        <tr>
                            <td data-label="TGL DIBUAT">{{ $corr->created_at->format('d/m/Y') }} <br><small style="color: var(--muted);">{{ $corr->created_at->format('H:i') }}</small></td>
                            <td data-label="TGL ABSEN" style="font-weight: 800;">{{ $corr->correction_date->translatedFormat('d M Y') }}</td>
                            <td data-label="JENIS KOREKSI">{{ ucwords(str_replace('_', ' ', $corr->correction_type)) }}</td>
                            <td data-label="JAM PENGAJUAN" style="font-family: monospace; font-size: 13px;">
                                @if($corr->requested_check_in_at) IN: {{ $corr->requested_check_in_at->format('H:i') }}<br>@endif
                                @if($corr->requested_check_out_at) OUT: {{ $corr->requested_check_out_at->format('H:i') }}@endif
                            </td>
                            <td data-label="ALASAN KOREKSI">
                                @if($corr->reason)
                                    <div class="td-action-group">
                                        <span class="note-truncate">{{ $corr->reason }}</span>
                                        <button type="button" class="btn-custom btn-outline" style="height: 26px; padding: 0 8px; font-size: 11px; border-radius: 6px;" data-note="{{ $corr->reason }}" onclick="viewNoteModal(this.getAttribute('data-note'), 'Alasan Pengajuan Koreksi')"><i class="fa-solid fa-eye"></i> Baca</button>
                                    </div>
                                @else
                                    <span style="color: var(--muted);">-</span>
                                @endif
                            </td>
                            <td data-label="STATUS">
                                @php
                                    $stat = $corr->status;
                                    $bcs = 'badge-info'; $tcs = 'Menunggu';
                                    if($stat == 'approved') { $bcs = 'badge-ok'; $tcs = 'Disetujui'; }
                                    elseif($stat == 'rejected') { $bcs = 'badge-danger'; $tcs = 'Ditolak'; }
                                @endphp
                                <span class="badge-label {{ $bcs }}">{{ $tcs }}</span>
                            </td>
                            <td data-label="TANGGAPAN PEMBIMBING">
                                @if($corr->review_note)
                                    <div class="td-action-group">
                                        <span class="note-truncate">{{ $corr->review_note }}</span>
                                        <button type="button" class="btn-custom btn-outline" style="height: 26px; padding: 0 8px; font-size: 11px; border-radius: 6px;" data-note="{{ $corr->review_note }}" onclick="viewNoteModal(this.getAttribute('data-note'), 'Respon Koreksi')"><i class="fa-solid fa-eye"></i> Baca</button>
                                    </div>
                                @else
                                    <span style="color: var(--muted);">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="7" style="text-align: center; color: var(--muted); padding: 40px;">Belum ada pengajuan perbaikan data absen.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>

<!-- ================= MODALS KOREKSI ================= -->
<div class="modal-backdrop d-none" id="koreksiModal">
    <div class="modal-box" style="max-width: 600px; text-align: left; padding: 0; border-radius: 16px; background: #fff;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border);">
            <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: var(--text);"><i class="fa-solid fa-file-pen" style="color: var(--primary); margin-right: 6px;"></i> Form Permohonan Koreksi Absensi</h3>
            <button type="button" onclick="closeKoreksiModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); transition: 0.2s;">&times;</button>
        </div>

        <form action="{{ route('peserta.absensi.koreksi') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Pilih Tanggal Absensi *</label>
                        <input type="date" name="correction_date" max="{{ date('Y-m-d') }}" required class="input-box">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Kategori Masalah *</label>
                        <select name="correction_type" class="input-box" required>
                            <option value="check_in">Lupa / Error Check-in Masuk</option>
                            <option value="check_out">Lupa / Error Check-out Pulang</option>
                            <option value="check_in_and_check_out">Tidak Bisa Masuk & Pulang</option>
                            <option value="attendance_status">Penugasan Dinas Luar</option>
                            <option value="other">Gangguan Server / Jaringan</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Jam Masuk (Usulan)</label>
                        <input type="time" name="requested_check_in_at" value="08:00" class="input-box">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Jam Pulang (Usulan)</label>
                        <input type="time" name="requested_check_out_at" value="16:00" class="input-box">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Penjelasan Alasan / Kronologi Lengkap *</label>
                    <textarea name="reason" rows="3" class="input-box" placeholder="Ceritakan secara detail mengapa absen Anda gagal direkam otomatis oleh sistem..." required minlength="15"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>Lampiran Bukti Pendukung (Opsional)</label>
                    <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="input-box" style="padding: 8px 16px;">
                    <small>Dapat berupa tangkapan layar (screenshot) error sistem atau surat keterangan terkait. Format: PDF/JPG/PNG max 5MB.</small>
                </div>
            </div>
            <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px; background: var(--page); border-radius: 0 0 16px 16px;">
                <button type="button" class="btn-custom btn-outline" onclick="closeKoreksiModal()">Batalkan</button>
                <button type="submit" class="btn-custom btn-primary"><i class="fa-solid fa-paper-plane"></i> Ajukan Koreksi</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL FOTO VIEWER ================= -->
<div class="modal-backdrop d-none" id="modalSelfieViewer">
    <div class="modal-box" style="max-width: 480px; text-align: center; padding: 0; border-radius: 16px; background: #fff;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border);">
            <h3 id="selfieViewerTitle" style="margin: 0; font-size: 15px; font-weight: 800; color: var(--text);"><i class="fa-solid fa-camera"></i> Bukti Lampiran</h3>
            <button type="button" onclick="closeSelfieViewer()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); transition: 0.2s;">&times;</button>
        </div>
        <div style="padding: 24px; background: var(--page);">
            <img id="selfieViewerImg" src="" style="width: 100%; max-height: 420px; object-fit: contain; border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 4px 14px rgba(0,0,0,0.05);" alt="Bukti Foto">
        </div>
        <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
            <button type="button" class="btn-custom btn-outline btn-block" onclick="closeSelfieViewer()">Tutup Pratinjau</button>
        </div>
    </div>
</div>

<!-- ================= MODAL TEKS VIEWER ================= -->
<div class="modal-backdrop d-none" id="modalNoteViewer">
    <div class="modal-box" style="max-width: 420px; text-align: left; padding: 0; border-radius: 16px; background: #fff;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-bottom: 1px solid var(--border);">
            <h3 id="noteViewerTitle" style="margin: 0; font-size: 15px; font-weight: 800; color: var(--text);"><i class="fa-solid fa-note-sticky" style="color: var(--primary);"></i> Baca Rincian</h3>
            <button type="button" onclick="closeNoteModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); transition: 0.2s;">&times;</button>
        </div>
        <div style="padding: 24px; color: var(--text); font-size: 14px; line-height: 1.6; word-wrap: break-word;" id="noteViewerContent">
        </div>
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: var(--page); border-radius: 0 0 16px 16px;">
            <button type="button" class="btn-custom btn-outline btn-block" onclick="closeNoteModal()">Tutup</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const targetLat = {{ $company->latitude ?? -7.680810191412908 }};
    const targetLng = {{ $company->longitude ?? 109.1366921585062 }};
    const allowedRadius = {{ $company->attendance_radius_meters ?? 150 }};
    const userName = "{{ $mhs->nama_lengkap ?? (Auth::user()->nama_lengkap ?? 'Peserta') }}";
    const userNim = "{{ $mhs->nim ?? '-' }}";
    const userDivisi = "{{ $penempatan->divisi->nama_divisi ?? 'PLTU Adipala' }}";

    let currentLat = targetLat, currentLng = targetLng, currentAcc = 15, currentDist = 0;
    let isInsideRadius = true, hasCapturedSelfie = false, activeCameraMode = 'in', currentFacingMode = 'user', streamObj = null;

    function updateLiveClockAndHud() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
        if(document.getElementById('liveClock')) document.getElementById('liveClock').innerText = timeStr;
        if(document.getElementById('hudClock')) document.getElementById('hudClock').innerText = timeStr;
    }
    setInterval(updateLiveClockAndHud, 1000); updateLiveClockAndHud();

    function deg2rad(deg) { return deg * (Math.PI / 180); }
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = deg2rad(lat2 - lat1); const dLon = deg2rad(lon2 - lon1);
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2);
        return Math.round(R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a))));
    }

    function processLocation(lat, lng, acc) {
        currentLat = lat; currentLng = lng; currentAcc = acc || 15;
        currentDist = calculateDistance(lat, lng, targetLat, targetLng);
        isInsideRadius = (currentDist <= allowedRadius + 50);

        document.getElementById('radarDistance').innerText = currentDist + ' m';
        const zoneEl = document.getElementById('radarZone');
        const statusText = document.getElementById('radarStatusText');

        if (isInsideRadius) {
            zoneEl.innerText = 'Di Dalam Titik Pabrik'; zoneEl.style.color = '#10B981';
            statusText.innerText = 'Lokasi Kordinat Terverifikasi';
        } else {
            zoneEl.innerText = 'Di Luar Area PLTU ('+currentDist+'m)'; zoneEl.style.color = '#EF4444';
            statusText.innerText = 'Akses Terblokir Jarak Jauh';
            if(document.getElementById('bypassGeofence')) document.getElementById('bypassGeofence').value = '1';
        }

        ['inputLat', 'inputLng', 'inputAcc', 'inputLatOut', 'inputLngOut'].forEach(id => {
            const el = document.getElementById(id);
            if(el) { if(id.includes('Lat')) el.value = lat; if(id.includes('Lng')) el.value = lng; if(id === 'inputAcc') el.value = currentAcc; }
        });
        updateCameraHudUI();
    }

    function updateCameraHudUI() {
        const hudCoords = document.getElementById('hudCoords');
        if (hudCoords) hudCoords.innerText = `${currentLat.toFixed(5)}, ${currentLng.toFixed(5)}`;
        const hudRadiusBadge = document.getElementById('hudRadiusBadge');
        if (hudRadiusBadge) {
            hudRadiusBadge.innerText = isInsideRadius ? 'STATUS: VALID' : 'STATUS: INVALID';
            hudRadiusBadge.style.background = isInsideRadius ? '#10B981' : '#EF4444';
        }
        const hudDistance = document.getElementById('hudDistance');
        if(hudDistance) hudDistance.innerText = `Posisi Jarak: ${currentDist} meter`;
    }

    function getCurrentLocation() {
        if (!navigator.geolocation) { simulateInLocation(); return; }
        navigator.geolocation.getCurrentPosition(
            (pos) => processLocation(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy),
            (err) => simulateInLocation(), { enableHighAccuracy: true, timeout: 8000 }
        );
    }
    function simulateInLocation() { processLocation(targetLat + 0.00015, targetLng + 0.00012, 10); }

    function startCamera(mode = 'in') {
        activeCameraMode = mode;
        const box = document.getElementById('cameraPreviewBox');
        if (box) box.style.display = 'block';
        const btnBox = (mode === 'in') ? document.getElementById('btnStartCameraBox') : document.getElementById('btnStartCameraOutBox');
        if (btnBox) btnBox.style.display = 'none';

        const video = document.getElementById('webcam');
        if (!video) return;

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) { alert('Sistem browser Anda memblokir atau tidak mendukung akses kamera.'); return; }
        stopCameraTracks();
        navigator.mediaDevices.getUserMedia({ video: { facingMode: currentFacingMode, width: { ideal: 1280 }, height: { ideal: 720 } }, audio: false })
            .then(function(stream) { streamObj = stream; video.srcObject = stream; video.play(); updateCameraHudUI(); })
            .catch(function(err) { alert('Gagal memuat perangkat kamera: ' + err.message); });
    }

    function switchCamera() { currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user'; startCamera(activeCameraMode); }
    function stopCamera() {
        stopCameraTracks();
        const box = document.getElementById('cameraPreviewBox');
        if (box) box.style.display = 'none';
        const btnBox = (activeCameraMode === 'in') ? document.getElementById('btnStartCameraBox') : document.getElementById('btnStartCameraOutBox');
        if (btnBox && !hasCapturedSelfie) btnBox.style.display = 'block';
    }
    function stopCameraTracks() { if (streamObj) { streamObj.getTracks().forEach(track => track.stop()); streamObj = null; } }

    function drawWatermark(canvas) {
        const ctx = canvas.getContext('2d');
        const w = canvas.width; const h = canvas.height;
        const bannerHeight = Math.max(100, Math.round(h * 0.23));
        const bannerY = h - bannerHeight;

        ctx.fillStyle = "rgba(15, 23, 42, 0.9)";
        ctx.fillRect(0, bannerY, w, bannerHeight);
        ctx.fillStyle = isInsideRadius ? "#10B981" : "#EF4444";
        ctx.fillRect(0, bannerY, w, 4);

        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID') + ' WIB';
        const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' });

        const padding = 16;
        const fontSize = Math.max(11, Math.round(w * 0.022));
        const lineSpacing = fontSize + 6;

        ctx.font = `bold ${fontSize + 1}px sans-serif`;
        ctx.fillStyle = "#A7F3D0";
        ctx.fillText("PLTU ADIPALA - DIGITAL ATTENDANCE LOG", padding, bannerY + fontSize + 8);
        ctx.font = `${fontSize}px monospace`;
        let currY = bannerY + fontSize + 8 + lineSpacing;

        ctx.fillStyle = "#ffffff";
        ctx.fillText(`WAKTU  : ${timeStr} | ${dateStr}`, padding, currY); currY += lineSpacing;
        ctx.fillText(`LOKASI : GPS Kordinat ${currentLat.toFixed(5)}, ${currentLng.toFixed(5)}`, padding, currY); currY += lineSpacing;

        ctx.fillStyle = isInsideRadius ? "#34D399" : "#FCA5A5";
        ctx.fillText(`STATUS : ${isInsideRadius ? 'AREA VALID' : 'AREA TERBLOKIR'} (Geofence: ${currentDist}m)`, padding, currY); currY += lineSpacing;

        ctx.fillStyle = "#CBD5E1";
        ctx.font = `${fontSize - 1}px sans-serif`;
        ctx.fillText(`AKUN   : ${userName} (${userNim}) - ${userDivisi}`, padding, currY);
    }

    function capturePhoto(mode = 'in') {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        if (!video.videoWidth) return;

        canvas.width = video.videoWidth; canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        if (currentFacingMode === 'user') { ctx.translate(canvas.width, 0); ctx.scale(-1, 1); ctx.drawImage(video, 0, 0, canvas.width, canvas.height); ctx.setTransform(1, 0, 0, 1, 0, 0); }
        else { ctx.drawImage(video, 0, 0, canvas.width, canvas.height); }
        drawWatermark(canvas);
        const dataUrl = canvas.toDataURL('image/png', 0.9);

        if (mode === 'in') {
            document.getElementById('selfieInput').value = dataUrl;
            document.getElementById('selfiePreview').src = dataUrl;
            document.getElementById('selfiePreviewBox').style.display = 'block';
            document.getElementById('selfieStatusBadge').className = 'badge-label badge-ok';
            document.getElementById('selfieStatusBadge').innerHTML = '<i class="fa-solid fa-circle-check"></i> Bukti Masuk Terekam';
            document.getElementById('btnSubmitCheckin').disabled = false;
        } else {
            document.getElementById('selfieInputOut').value = dataUrl;
            document.getElementById('selfiePreviewOut').src = dataUrl;
            document.getElementById('selfiePreviewOutBox').style.display = 'block';
            document.getElementById('selfieStatusOutBadge').className = 'badge-label badge-ok';
            document.getElementById('selfieStatusOutBadge').innerHTML = '<i class="fa-solid fa-circle-check"></i> Bukti Pulang Terekam';
        }
        hasCapturedSelfie = true; stopCameraTracks(); document.getElementById('cameraPreviewBox').style.display = 'none';
    }

    function retakePhoto(mode = 'in') {
        if (mode === 'in') {
            document.getElementById('selfieInput').value = '';
            document.getElementById('selfiePreviewBox').style.display = 'none';
            document.getElementById('btnSubmitCheckin').disabled = true;
            document.getElementById('selfieStatusBadge').className = 'badge-label badge-info';
            document.getElementById('selfieStatusBadge').innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Menunggu Lensa Terbuka';
        } else {
            document.getElementById('selfieInputOut').value = '';
            document.getElementById('selfiePreviewOutBox').style.display = 'none';
        }
        hasCapturedSelfie = false; startCamera(mode);
    }

    function openKoreksiModal() { document.getElementById('koreksiModal').classList.remove('d-none'); }
    function closeKoreksiModal() { document.getElementById('koreksiModal').classList.add('d-none'); }

    function viewSelfieModal(url, title) {
        document.getElementById('selfieViewerImg').src = url;
        document.getElementById('selfieViewerTitle').innerHTML = '<i class="fa-solid fa-camera" style="color: var(--primary); margin-right:6px;"></i> ' + title;
        document.getElementById('modalSelfieViewer').classList.remove('d-none');
    }
    function closeSelfieViewer() { document.getElementById('modalSelfieViewer').classList.add('d-none'); }

    // Modal untuk membaca Catatan panjang
    function viewNoteModal(text, title) {
        document.getElementById('noteViewerTitle').innerHTML = '<i class="fa-solid fa-note-sticky" style="color: var(--primary); margin-right:6px;"></i> ' + title;
        document.getElementById('noteViewerContent').innerText = text;
        document.getElementById('modalNoteViewer').classList.remove('d-none');
    }
    function closeNoteModal() { document.getElementById('modalNoteViewer').classList.add('d-none'); }

    document.addEventListener('DOMContentLoaded', () => getCurrentLocation());
</script>
@endpush
