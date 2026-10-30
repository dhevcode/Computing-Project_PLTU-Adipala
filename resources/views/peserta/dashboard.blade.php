@extends('peserta.layout')

@section('title', 'Dashboard Peserta')

@section('content')

<style>
    /* ==========================================================================
       DASHBOARD - PANEL KACA BESAR + KARTU BENTO (GAYA "FINANCIAL SUMMARY")
       Datar, tanpa glow. Latar foto mengikuti portal.css.
       ========================================================================== */
    .modern-dashboard-wrapper {
        box-sizing: border-box;
        width: 100%;
        max-width: 1200px;
        margin-left: auto !important;
        margin-right: auto !important;
        float: none !important;
        color: var(--text);
    }

    /* ---------- Panel kaca utama ---------- */
    .ap-panel {
        background: var(--panel);
        backdrop-filter: blur(40px) saturate(140%);
        -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border);
        border-radius: 40px;
        padding: 30px;
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.22);
    }

    /* ---------- Kepala panel ---------- */
    .ap-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 18px 24px;
        margin-bottom: 26px;
    }
    .ap-top-text { flex: 1 1 380px; min-width: 0; }

    .ap-title {
        margin: 0;
        font-size: clamp(24px, 3vw, 36px);
        font-weight: 700;
        letter-spacing: -0.025em;
        line-height: 1.15;
        color: #ffffff;
    }
    .ap-sub {
        margin: 10px 0 0;
        font-size: 13.5px;
        color: var(--muted);
        font-weight: 500;
        line-height: 1.6;
    }
    .ap-sub b { color: var(--accent); font-weight: 600; font-family: var(--font-num); font-size: 12.5px; }

    .ap-controls { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
    .ap-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 16px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        background: rgba(255, 255, 255, 0.09);
        border: 1px solid var(--border-strong);
        white-space: nowrap;
    }
    .ap-pill i { color: var(--text-2); font-size: 13px; }
    .ap-pill-accent {
        background: var(--accent);
        border-color: var(--accent);
        color: var(--accent-ink);
    }
    .ap-pill-accent i { color: var(--accent-ink); }

    /* ---------- Grid bento ---------- */
    .ap-bento {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }
    .c-status { grid-column: span 2; }
    .c-aksi, .c-notif { grid-row: span 2; }

    .ap-card {
        background: var(--card);
        backdrop-filter: blur(16px) saturate(130%);
        -webkit-backdrop-filter: blur(16px) saturate(130%);
        border: 1px solid var(--border);
        border-radius: 26px;
        padding: 20px;
        min-width: 0;
        display: flex;
        flex-direction: column;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
        text-decoration: none !important;
        color: var(--text);
    }
    a.ap-card { transition: background .2s ease, border-color .2s ease, transform .2s ease; }
    a.ap-card:hover { background: rgba(38, 39, 39, 0.70); border-color: var(--border-strong); transform: translateY(-2px); }
    a.ap-card:focus-visible, .ap-go:focus-visible, .ap-item:focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; }

    .ap-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 16px;
    }
    .ap-card-title { margin: 0; font-size: 15.5px; font-weight: 700; letter-spacing: -0.01em; color: #ffffff; }
    .ap-card-cap {
        margin-top: 3px;
        font-family: var(--font-num);
        font-size: 11px;
        color: var(--muted);
        letter-spacing: 0.01em;
    }

    .ap-go {
        width: 34px; height: 34px;
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 12px;
        color: var(--text);
        background: var(--card-2);
        border: 1px solid var(--border-strong);
        text-decoration: none !important;
        flex-shrink: 0;
        transition: background .2s ease, color .2s ease;
    }
    .ap-go:hover, a.ap-card:hover .ap-go { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }

    /* ---------- Badge ---------- */
    .ap-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; border-radius: 9999px;
        font-size: 11.5px; font-weight: 600; white-space: nowrap;
        border: 1px solid transparent;
    }
    .ap-badge-ok      { background: var(--ok-bg);     color: var(--ok);     border-color: var(--ok-bd); }
    .ap-badge-warn    { background: var(--warn-bg);   color: var(--warn);   border-color: var(--warn-bd); }
    .ap-badge-danger  { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-bd); }
    .ap-badge-neutral { background: var(--card-2);    color: var(--text-2); border-color: var(--border-strong); }

    /* ---------- Tile status (di dalam kartu Status) ---------- */
    .ap-tiles { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; flex: 1; }
    .ap-tile {
        background: var(--card-2);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 14px 16px;
        min-width: 0;
    }
    .ap-tile-accent { background: var(--accent-soft); border-color: var(--accent-bd); }
    .ap-tile-label { font-size: 10.5px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); }
    .ap-tile-value {
        font-family: var(--font-num);
        font-size: 19px; font-weight: 600; letter-spacing: -0.02em;
        color: #ffffff; margin: 7px 0 5px; line-height: 1.2;
    }
    .ap-tile-value.is-text { font-family: var(--font-ui); font-size: 15.5px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ap-tile-note { font-size: 12px; color: var(--text-2); font-weight: 500; }
    .ap-tile-note.ok   { color: var(--ok); }
    .ap-tile-note.warn { color: var(--warn); }

    /* ---------- Rekap kehadiran (bar vertikal) ---------- */
    .ap-big {
        font-family: var(--font-num);
        font-size: 30px; font-weight: 600; letter-spacing: -0.04em;
        color: #ffffff; line-height: 1; margin: 2px 0 14px;
    }
    .ap-big small { font-size: 13px; font-weight: 500; color: var(--muted); letter-spacing: 0; }
    .ap-bars { display: flex; gap: 10px; flex: 1; min-height: 120px; }
    .ap-bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px; min-width: 0; }
    .ap-bar-track {
        flex: 1; width: 100%; border-radius: 16px;
        background: rgba(255, 255, 255, 0.08);
        position: relative; overflow: hidden;
    }
    .ap-bar-fill { position: absolute; left: 0; right: 0; bottom: 0; border-radius: 16px; background: var(--info); }
    .ap-bar-fill.is-main { background: var(--accent); }
    .ap-bar-fill.is-warn { background: var(--warn); }
    .ap-bar-num { font-family: var(--font-num); font-size: 12.5px; font-weight: 600; color: #ffffff; line-height: 1; }
    .ap-bar-lbl { font-size: 10.5px; color: var(--muted); font-weight: 500; line-height: 1; margin-top: -3px; }

    /* ---------- Kartu angka ---------- */
    .ap-chip {
        display: inline-flex; align-items: center; gap: 5px;
        align-self: flex-start;
        padding: 3px 10px; border-radius: 9999px;
        font-size: 11px; font-weight: 600;
        background: var(--card-2-hover); color: var(--text-2);
    }
    .ap-chip.ok { color: var(--ok); background: var(--ok-bg); }
    .ap-chip.warn { color: var(--warn); background: var(--warn-bg); }
    .ap-chip.danger { color: var(--danger); background: var(--danger-bg); }
    .ap-value {
        margin-top: auto;
        padding-top: 14px;
        font-family: var(--font-num);
        font-size: 28px; font-weight: 600; letter-spacing: -0.04em;
        color: #ffffff; line-height: 1;
    }
    .ap-value small { font-size: 14px; font-weight: 500; color: var(--muted); letter-spacing: 0; }
    .ap-meter { height: 6px; border-radius: 9999px; background: rgba(255, 255, 255, 0.12); overflow: hidden; margin-top: 12px; }
    .ap-meter > span { display: block; height: 100%; border-radius: inherit; background: var(--accent); }

    /* ---------- Daftar (aksi cepat, pengumuman, notifikasi) ---------- */
    .ap-list { display: flex; flex-direction: column; }
    .ap-item {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 8px; margin: 0 -8px;
        border-radius: 16px;
        text-decoration: none !important;
        position: relative;
        transition: background .2s ease;
    }
    .ap-item + .ap-item::before {
        content: ""; position: absolute; top: 0; left: 8px; right: 8px; height: 1px; background: var(--border);
    }
    a.ap-item:hover { background: var(--card-2); }
    a.ap-item:hover::before, a.ap-item:hover + .ap-item::before { opacity: 0; }

    .ap-ico {
        width: 38px; height: 38px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 14px; flex-shrink: 0; border: 1px solid transparent;
    }
    .tone-ok     { background: var(--ok-bg);     color: var(--ok);     border-color: var(--ok-bd); }
    .tone-warn   { background: var(--warn-bg);   color: var(--warn);   border-color: var(--warn-bd); }
    .tone-danger { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-bd); }
    .tone-info   { background: var(--info-bg);   color: var(--info);   border-color: var(--info-bd); }
    .tone-violet { background: var(--violet-bg); color: var(--violet); border-color: var(--violet-bd); }
    .tone-accent { background: var(--accent-soft); color: var(--accent); border-color: var(--accent-bd); }

    .ap-item-body { flex: 1; min-width: 0; }
    .ap-item-title { margin: 0; font-size: 13.5px; font-weight: 600; color: #ffffff; line-height: 1.3; }
    .ap-item-desc  { margin: 2px 0 0; font-size: 11.5px; color: var(--muted); line-height: 1.4; }
    .ap-item-desc.clamp, .ap-item-title.clamp {
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .ap-item-time { margin-top: 4px; font-family: var(--font-num); font-size: 10.5px; color: var(--muted); }
    .ap-item-arrow { font-size: 11px; color: var(--muted); transition: transform .2s ease, color .2s ease; }
    a.ap-item:hover .ap-item-arrow { transform: translateX(3px); color: var(--accent); }

    .ap-feed { padding: 12px 0; border-top: 1px solid var(--border); }
    .ap-head + .ap-feed { border-top: none; padding-top: 0; }
    .ap-feed:last-child { padding-bottom: 0; }

    .ap-empty { text-align: center; padding: 22px 8px; color: var(--muted); margin: auto 0; }
    .ap-empty i { font-size: 26px; margin-bottom: 8px; opacity: .6; }
    .ap-empty p { margin: 0; font-size: 12.5px; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1100px) {
        .ap-bento { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .c-aksi, .c-notif { grid-row: auto; }
    }
    @media (max-width: 768px) {
        .ap-panel { padding: 18px; border-radius: 30px; }
        .ap-top { margin-bottom: 18px; }
        .ap-card { padding: 16px; border-radius: 22px; }
        .ap-pill { height: 36px; padding: 0 13px; font-size: 12px; }
    }
    @media (max-width: 640px) {
        .ap-bento { grid-template-columns: 1fr; }
        .c-status { grid-column: auto; }
    }
    @media (max-width: 420px) {
        .ap-tiles { grid-template-columns: 1fr; }
    }
    @media (prefers-reduced-motion: reduce) {
        a.ap-card, .ap-item, .ap-item-arrow, .ap-go { transition: none; }
    }
</style>

@php
    $isLate    = $attendanceToday && $attendanceToday->late_minutes > 0;
    $logPct    = ($totalLogbook ?? 0) > 0 ? round(($logbookDisetujui / $totalLogbook) * 100) : 0;
    $rekapMax  = max(1, $totalHadir, $totalTerlambat, $totalIzinSakit);
    $hHadir    = max(8, round($totalHadir / $rekapMax * 100));
    $hLambat   = max(8, round($totalTerlambat / $rekapMax * 100));
    $hIzin     = max(8, round($totalIzinSakit / $rekapMax * 100));
@endphp

<div class="modern-dashboard-wrapper">
<div class="ap-panel">

    <!-- ================= KEPALA PANEL ================= -->
    <header class="ap-top">
        <div class="ap-top-text">
            <h1 class="ap-title">Selamat Datang, {{ $mhs->nama_lengkap }}!</h1>
            <p class="ap-sub">
                <b>NIM {{ $mhs->nim }}</b> &nbsp;&bull;&nbsp;
                {{ $mhs->universitas->nama_universitas ?? 'Universitas' }} &nbsp;&bull;&nbsp;
                {{ $penempatan->divisi->nama_divisi ?? 'Divisi Umum' }}
            </p>
        </div>

        <div class="ap-controls">
            <span class="ap-pill"><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            <span class="ap-pill"><i class="fa-regular fa-clock"></i> {{ substr($company->work_start_time ?? '08:00:00', 0, 5) }} - {{ substr($company->work_end_time ?? '16:00:00', 0, 5) }} WIB</span>
            <a href="{{ route('peserta.absensi') }}" class="ap-pill ap-pill-accent" style="text-decoration:none;">
                <i class="fa-solid fa-location-dot"></i> Presensi
            </a>
        </div>
    </header>

    <!-- ================= BENTO GRID ================= -->
    <div class="ap-bento">

        <!-- Status Kegiatan Hari Ini -->
        <section class="ap-card c-status">
            <div class="ap-head">
                <div>
                    <h3 class="ap-card-title">Status Kegiatan Hari Ini</h3>
                    <div class="ap-card-cap">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</div>
                </div>
                <span class="ap-badge {{ $attendanceToday ? ($attendanceToday->check_in_status === 'late' ? 'ap-badge-warn' : 'ap-badge-ok') : 'ap-badge-neutral' }}">
                    <i class="fa-solid {{ $attendanceToday ? ($attendanceToday->check_in_status === 'late' ? 'fa-clock' : 'fa-circle-check') : 'fa-hourglass-half' }}"></i>
                    {{ $attendanceToday ? ($attendanceToday->check_in_status === 'late' ? 'Terlambat Hadir' : 'Sudah Hadir') : 'Belum Presensi' }}
                </span>
            </div>

            <div class="ap-tiles">
                <!-- Check In -->
                <div class="ap-tile ap-tile-accent">
                    <div class="ap-tile-label">Check-in Masuk</div>
                    <div class="ap-tile-value">
                        {{ $attendanceToday && $attendanceToday->check_in_at ? $attendanceToday->check_in_at->format('H:i') . ' WIB' : 'Belum Check-in' }}
                    </div>
                    <div class="ap-tile-note {{ $isLate ? 'warn' : 'ok' }}">
                        @if($attendanceToday && $attendanceToday->check_in_at)
                            @if($isLate)
                                <i class="fa-solid fa-triangle-exclamation"></i> Terlambat {{ $attendanceToday->late_minutes }} menit
                            @else
                                <i class="fa-solid fa-circle-check"></i> Tepat Waktu
                            @endif
                        @else
                            <span style="color: var(--text-2); font-weight: 400;">
                                Toleransi: {{ \Carbon\Carbon::createFromFormat('H:i:s', $company->work_start_time ?? '08:00:00')->addMinutes($policy->late_tolerance_minutes ?? 15)->format('H:i') }} WIB
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Check Out -->
                <div class="ap-tile">
                    <div class="ap-tile-label">Check-out Pulang</div>
                    <div class="ap-tile-value">
                        {{ $attendanceToday && $attendanceToday->check_out_at ? $attendanceToday->check_out_at->format('H:i') . ' WIB' : 'Belum Pulang' }}
                    </div>
                    <div class="ap-tile-note">
                        @if($attendanceToday && $attendanceToday->check_out_at)
                            <span style="color: var(--ok);">
                                <i class="fa-solid fa-circle-check"></i> Kerja: {{ floor($attendanceToday->total_work_minutes / 60) }}j {{ $attendanceToday->total_work_minutes % 60 }}m
                            </span>
                        @else
                            Mulai pukul {{ substr($company->work_end_time ?? '16:00:00', 0, 5) }} WIB
                        @endif
                    </div>
                </div>

                <!-- Logbook -->
                <div class="ap-tile">
                    <div class="ap-tile-label">Logbook Hari Ini</div>
                    <div style="margin: 9px 0 8px;">
                        @if($logbookToday)
                            <span class="ap-badge ap-badge-ok">{{ strtoupper($logbookToday->status) }}</span>
                        @else
                            <span class="ap-badge ap-badge-danger">Belum Diisi</span>
                        @endif
                    </div>
                    <div class="ap-tile-note">
                        {{ $logbookToday ? Str::limit($logbookToday->title, 25) : 'Wajib diisi sblm 17:00' }}
                    </div>
                </div>

                <!-- Pembimbing -->
                <div class="ap-tile">
                    <div class="ap-tile-label">Pembimbing Lapangan</div>
                    <div class="ap-tile-value is-text">{{ $penempatan->nama_pembimbing ?? 'Pembimbing Lapangan' }}</div>
                    <div class="ap-tile-note" style="color: var(--accent);">
                        <i class="fa-solid fa-building-user"></i>
                        {{ $penempatan->divisi->nama_divisi ?? 'PLTU Adipala' }}
                    </div>
                </div>
            </div>
        </section>

        <!-- Rekap Kehadiran -->
        <section class="ap-card">
            <div class="ap-head">
                <div>
                    <h3 class="ap-card-title">Rekap Kehadiran</h3>
                    <div class="ap-card-cap">total hari</div>
                </div>
                <a href="{{ route('peserta.absensi') }}" class="ap-go" aria-label="Buka presensi"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </div>
            <div class="ap-big">{{ $totalHadir }} <small>hadir</small></div>
            <div class="ap-bars">
                <div class="ap-bar-col">
                    <div class="ap-bar-track"><div class="ap-bar-fill is-main" style="height: {{ $hHadir }}%;"></div></div>
                    <span class="ap-bar-num">{{ $totalHadir }}</span>
                    <span class="ap-bar-lbl">Hadir</span>
                </div>
                <div class="ap-bar-col">
                    <div class="ap-bar-track"><div class="ap-bar-fill is-warn" style="height: {{ $hLambat }}%;"></div></div>
                    <span class="ap-bar-num">{{ $totalTerlambat }}</span>
                    <span class="ap-bar-lbl">Telat</span>
                </div>
                <div class="ap-bar-col">
                    <div class="ap-bar-track"><div class="ap-bar-fill" style="height: {{ $hIzin }}%;"></div></div>
                    <span class="ap-bar-num">{{ $totalIzinSakit }}</span>
                    <span class="ap-bar-lbl">Izin</span>
                </div>
            </div>
        </section>

        <!-- Pengumuman -->
        <section class="ap-card">
            <div class="ap-head">
                <div>
                    <h3 class="ap-card-title">Pengumuman</h3>
                    <div class="ap-card-cap">dari perusahaan</div>
                </div>
                <a href="{{ route('peserta.informasi') }}" class="ap-go" aria-label="Lihat semua pengumuman"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </div>

            @forelse($announcements->take(2) as $ann)
                <div class="ap-feed">
                    <div class="ap-item-title clamp">{{ $ann->title }}</div>
                    <p class="ap-item-desc clamp">{{ Str::limit($ann->content, 90) }}</p>
                    <div class="ap-item-time">{{ $ann->created_at->diffForHumans() }}</div>
                </div>
            @empty
                <div class="ap-empty">
                    <i class="fa-regular fa-clipboard"></i>
                    <p>Belum ada pengumuman terbaru.</p>
                </div>
            @endforelse
        </section>

        <!-- Logbook Disetujui -->
        <a href="{{ route('peserta.logbook') }}" class="ap-card">
            <div class="ap-head">
                <div>
                    <h3 class="ap-card-title">Logbook</h3>
                    <div class="ap-card-cap">disetujui</div>
                </div>
                <span class="ap-go"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
            </div>
            <span class="ap-chip ok"><i class="fa-solid fa-check"></i> {{ $logPct }}%</span>
            <div class="ap-value">{{ $logbookDisetujui }} <small>/ {{ $totalLogbook }}</small></div>
            <div class="ap-meter" role="progressbar" aria-valuenow="{{ $logPct }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ $logPct }}%;"></span></div>
        </a>

        <!-- Aduan Aktif -->
        <a href="{{ route('peserta.aduan') }}" class="ap-card">
            <div class="ap-head">
                <div>
                    <h3 class="ap-card-title">Aduan Aktif</h3>
                    <div class="ap-card-cap">sedang diproses</div>
                </div>
                <span class="ap-go"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
            </div>
            <span class="ap-chip {{ $totalAduanAktif > 0 ? 'danger' : 'ok' }}">
                <i class="fa-solid {{ $totalAduanAktif > 0 ? 'fa-circle-exclamation' : 'fa-check' }}"></i>
                {{ $totalAduanAktif > 0 ? 'Perlu perhatian' : 'Tidak ada' }}
            </span>
            <div class="ap-value">{{ $totalAduanAktif }}</div>
        </a>

        <!-- Aksi Cepat -->
        <section class="ap-card c-aksi">
            <div class="ap-head">
                <div>
                    <h3 class="ap-card-title">Aksi Cepat</h3>
                    <div class="ap-card-cap">pintasan menu</div>
                </div>
            </div>
            <div class="ap-list">
                <a href="{{ route('peserta.absensi') }}" class="ap-item">
                    <span class="ap-ico tone-accent"><i class="fa-solid fa-qrcode"></i></span>
                    <span class="ap-item-body"><h4 class="ap-item-title">Presensi Kehadiran</h4><p class="ap-item-desc">Check-in &amp; check-out GPS</p></span>
                    <i class="fa-solid fa-chevron-right ap-item-arrow"></i>
                </a>
                <a href="{{ route('peserta.logbook') }}" class="ap-item">
                    <span class="ap-ico tone-ok"><i class="fa-regular fa-pen-to-square"></i></span>
                    <span class="ap-item-body"><h4 class="ap-item-title">Logbook Harian</h4><p class="ap-item-desc">Catat aktivitas &amp; bukti</p></span>
                    <i class="fa-solid fa-chevron-right ap-item-arrow"></i>
                </a>
                <a href="{{ route('peserta.cuti') }}" class="ap-item">
                    <span class="ap-ico tone-info"><i class="fa-regular fa-calendar-plus"></i></span>
                    <span class="ap-item-body"><h4 class="ap-item-title">Cuti / Izin</h4><p class="ap-item-desc">Sakit, kampus, atau cuti</p></span>
                    <i class="fa-solid fa-chevron-right ap-item-arrow"></i>
                </a>
                <a href="{{ route('peserta.aduan') }}" class="ap-item">
                    <span class="ap-ico tone-danger"><i class="fa-regular fa-comment-dots"></i></span>
                    <span class="ap-item-body"><h4 class="ap-item-title">Aduan Masalah</h4><p class="ap-item-desc">Laporkan kendala lapangan</p></span>
                    <i class="fa-solid fa-chevron-right ap-item-arrow"></i>
                </a>
                <a href="{{ route('peserta.nilai') }}" class="ap-item">
                    <span class="ap-ico tone-violet"><i class="fa-regular fa-star"></i></span>
                    <span class="ap-item-body"><h4 class="ap-item-title">Arsip Nilai</h4><p class="ap-item-desc">Nilai &amp; umpan balik</p></span>
                    <i class="fa-solid fa-chevron-right ap-item-arrow"></i>
                </a>
                <a href="{{ route('peserta.informasi') }}" class="ap-item">
                    <span class="ap-ico tone-warn"><i class="fa-regular fa-building"></i></span>
                    <span class="ap-item-body"><h4 class="ap-item-title">Info &amp; Panduan PLTU</h4><p class="ap-item-desc">Pedoman K3 &amp; tata tertib</p></span>
                    <i class="fa-solid fa-chevron-right ap-item-arrow"></i>
                </a>
            </div>
        </section>

        <!-- Aktivitas & Notifikasi -->
        <section class="ap-card c-notif">
            <div class="ap-head">
                <div>
                    <h3 class="ap-card-title">Aktivitas &amp; Notifikasi</h3>
                    <div class="ap-card-cap">terbaru</div>
                </div>
                <a href="{{ route('peserta.notifikasi') }}" class="ap-go" aria-label="Lihat semua notifikasi"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </div>

            <div class="ap-list">
                @forelse($notifications as $notif)
                    @php $tone = $notif->type === 'success' ? 'ok' : ($notif->type === 'warning' ? 'warn' : 'info'); @endphp
                    <div class="ap-item">
                        <span class="ap-ico tone-{{ $tone }}"><i class="fa-solid fa-circle-info"></i></span>
                        <span class="ap-item-body">
                            <h4 class="ap-item-title">{{ $notif->title }}</h4>
                            <p class="ap-item-desc clamp">{{ $notif->message }}</p>
                            <div class="ap-item-time">{{ $notif->created_at->diffForHumans() }}</div>
                        </span>
                    </div>
                @empty
                    <div class="ap-empty">
                        <i class="fa-regular fa-bell-slash"></i>
                        <p>Belum ada notifikasi aktivitas.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Rata-rata Nilai -->
        <a href="{{ route('peserta.nilai') }}" class="ap-card">
            <div class="ap-head">
                <div>
                    <h3 class="ap-card-title">Rata-rata Nilai</h3>
                    <div class="ap-card-cap">evaluasi</div>
                </div>
                <span class="ap-go"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
            </div>
            <div class="ap-value">{{ $rataRataNilai > 0 ? $rataRataNilai : '-' }}</div>
        </a>

        <!-- Izin / Sakit / Cuti -->
        <a href="{{ route('peserta.cuti') }}" class="ap-card">
            <div class="ap-head">
                <div>
                    <h3 class="ap-card-title">Izin / Sakit / Cuti</h3>
                    <div class="ap-card-cap">total pengajuan</div>
                </div>
                <span class="ap-go"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
            </div>
            <div class="ap-value">{{ $totalIzinSakit }}</div>
        </a>

    </div>
</div>
</div>

@endsection
