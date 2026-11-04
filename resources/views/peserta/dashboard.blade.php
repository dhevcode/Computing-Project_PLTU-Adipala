@extends('peserta.layouts.app')

@section('title', 'Dashboard Peserta')
@section('page_title', 'Dashboard Peserta')
@section('page_subtitle', 'Ringkasan aktivitas magang dan notifikasi harian Anda')

@push('styles')
<style>
    /* ==========================================================================
       Status Widget (Banner Atas) - Dikembalikan ke gaya asli (Clean Gradient)
       ========================================================================== */
    .status-widget {
        background: linear-gradient(135deg, var(--primary-dark, #084C4F) 0%, var(--primary, #0A5F63) 100%);
        border-radius: 16px;
        padding: 24px;
        color: #ffffff;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .status-widget::before {
        content: "";
        position: absolute;
        right: -50px;
        top: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        pointer-events: none;
    }
    .status-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
        border-radius: 99px;
        padding: 6px 14px;
        font-size: 12.5px;
        font-weight: 700;
    }
    .status-badge.late {
        background: rgba(254,240,138,0.25);
        color: #FEF08A;
    }
    .status-metrics {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        position: relative;
        z-index: 1;
    }
    .metric-box {
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 16px;
    }
    .metric-label {
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: rgba(255,255,255,0.75);
        margin-bottom: 8px;
    }
    .metric-val {
        font-size: 20px;
        font-weight: 800;
    }
    .metric-sub {
        font-size: 12px;
        margin-top: 6px;
        color: rgba(255,255,255,0.85);
    }

    /* ==========================================================================
       Stats Grid Kartu Admin
       ========================================================================== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: var(--bg, #ffffff);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03);
        border: 1px solid var(--border, #E2E8F0);
        transition: 0.3s;
        text-decoration: none;
        color: inherit;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        border-color: #CBD5E1;
    }
    .stat-info h4 {
        font-size: 13px;
        font-weight: 600;
        color: var(--muted, #64748B);
        margin: 0 0 6px 0;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--text, #0F172A);
        line-height: 1;
    }

    /* Icon dibuat menjadi lingkaran sempurna (Bulat) */
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .stat-icon.primary { background: #ECFDF5; color: #059669; }
    .stat-icon.info { background: #EFF6FF; color: #2563EB; }
    .stat-icon.warning { background: #FFFBEB; color: #D97706; }
    .stat-icon.danger { background: #FEF2F2; color: #DC2626; }
    .stat-icon.purple { background: #F5F3FF; color: #7C3AED; }

    /* Bento Grid */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    .span-2 { grid-column: span 2; }

    .card {
        background: var(--bg, #ffffff);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03);
        border: 1px solid var(--border, #E2E8F0);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .card-header h3 {
        font-size: 15px;
        font-weight: 700;
        color: var(--text, #0F172A);
        margin: 0;
    }
    .btn-action-sm {
        background: #F1F5F9;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--muted, #64748B);
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-action-sm:hover {
        background: var(--primary-soft, #ECFDF5);
        color: var(--primary-dark, #084C4F);
    }

    /* List Feed */
    .feed-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
    }
    .feed-item {
        background: #F8FAFC;
        border: 1px solid #F1F5F9;
        padding: 14px;
        border-radius: 12px;
        transition: 0.2s;
        text-decoration: none;
    }
    .feed-item:hover {
        background: #ffffff;
        border-color: #CBD5E1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .feed-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        gap: 10px;
    }
    .feed-title {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text, #0F172A);
        margin: 0;
    }
    .feed-time {
        font-size: 11px;
        font-weight: 600;
        color: var(--muted, #64748B);
        flex-shrink: 0;
    }
    .feed-desc {
        font-size: 12px;
        color: var(--muted, #64748B);
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .empty-state {
        text-align: center;
        padding: 24px;
        color: var(--muted, #64748B);
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .empty-state i {
        font-size: 32px;
        margin-bottom: 12px;
        opacity: 0.4;
    }
    .empty-state p {
        margin: 0;
        font-size: 13px;
        font-weight: 500;
    }

    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .bento-grid { grid-template-columns: 1fr; }
        .span-2 { grid-column: auto; }
    }
    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; }
        .status-metrics { grid-template-columns: 1fr; }
    }
</style>
@endpush

@php
    // 1. Pengecekan Aman Presensi & Logbook
    $hasAttendance = isset($attendanceToday) && !is_null($attendanceToday);
    $isLate = $hasAttendance && isset($attendanceToday->late_minutes) && $attendanceToday->late_minutes > 0;

    $totalLog = $totalLogbook ?? 0;
    $approvedLog = $logbookDisetujui ?? 0;
    $logPct = $totalLog > 0 ? round(($approvedLog / $totalLog) * 100) : 0;

    // 2. Format Jam Toleransi Aman
    $workStart = $company->work_start_time ?? '08:00:00';
    $lateTolerance = $policy->late_tolerance_minutes ?? 15;
    $toleranceTimeStr = '08:15 WIB';
    try {
        $formattedTime = strlen($workStart) === 5 ? $workStart . ':00' : $workStart;
        $toleranceTimeStr = \Carbon\Carbon::createFromFormat('H:i:s', $formattedTime)
            ->addMinutes((int)$lateTolerance)
            ->format('H:i') . ' WIB';
    } catch (\Exception $e) {
        // Fallback jika format string waktu bermasalah
    }

    // 3. Pemotongan Data Koleksi di Blok PHP (Mencegah Compiler Error Blade)
    $limitedAnnouncements = collect();
    if (isset($announcements)) {
        $limitedAnnouncements = is_array($announcements)
            ? collect($announcements)->take(3)
            : $announcements->take(3);
    }

    $safeNotifications = collect();
    if (isset($notifications)) {
        $safeNotifications = is_array($notifications)
            ? collect($notifications)->take(4)
            : $notifications->take(4);
    }
@endphp

@section('content')

<!-- WIDGET STATUS HARI INI -->
<div class="status-widget animate-fade-up">
    <div class="status-top">
        <div>
            <h2 style="font-size: 20px; font-weight: 800; margin: 0 0 6px 0;">Kegiatan Hari Ini</h2>
            <div style="font-size: 14px; color: rgba(255,255,255,0.85);">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
        </div>

        <div class="status-badge {{ $isLate ? 'late' : '' }}">
            <i class="fa-solid {{ $hasAttendance ? ($isLate ? 'fa-clock' : 'fa-circle-check') : 'fa-hourglass-half' }}"></i>
            {{ $hasAttendance ? ($isLate ? 'Terlambat Hadir' : 'Sudah Hadir') : 'Belum Presensi' }}
        </div>
    </div>

    <div class="status-metrics">
        <!-- Metric Presensi -->
        <div class="metric-box">
            <div class="metric-label">Check-in Masuk</div>
            <div class="metric-val">
                {{ $hasAttendance && $attendanceToday->check_in_at ? $attendanceToday->check_in_at->format('H:i') . ' WIB' : '--:-- WIB' }}
            </div>
            <div class="metric-sub">
                @if($hasAttendance && $attendanceToday->check_in_at)
                    {!! $isLate ? '<i class="fa-solid fa-triangle-exclamation"></i> Telat '.$attendanceToday->late_minutes.' menit' : '<i class="fa-solid fa-check"></i> Tepat Waktu' !!}
                @else
                    Toleransi: {{ $toleranceTimeStr }}
                @endif
            </div>
        </div>

        <!-- Metric Logbook -->
        <div class="metric-box">
            <div class="metric-label">Status Logbook</div>
            <div class="metric-val">
                {{ isset($logbookToday) && $logbookToday ? 'Disimpan' : 'Kosong' }}
            </div>
            <div class="metric-sub">
                @if(isset($logbookToday) && $logbookToday)
                    Status: {{ strtoupper($logbookToday->status ?? 'DRAFT') }}
                @else
                    Wajib diisi sebelum jam pulang
                @endif
            </div>
        </div>

        <!-- Metric Pembimbing -->
        <div class="metric-box">
            <div class="metric-label">Pembimbing Lapangan</div>
            <div class="metric-val" style="font-size: 16px; margin-bottom: 2px;">
                {{ $penempatan->nama_pembimbing ?? 'Pembimbing Lapangan' }}
            </div>
            <div class="metric-sub">
                <i class="fa-solid fa-building-user"></i> {{ $penempatan->divisi->nama_divisi ?? '-' }}
            </div>
        </div>
    </div>
</div>

<!-- STATISTIK GRID KARTU -->
<div class="stats-grid animate-fade-up delay-1">
    <a href="{{ route('peserta.absensi') }}" class="stat-card">
        <div class="stat-info">
            <h4>Total Kehadiran</h4>
            <!-- Menghapus padding nol, langsung menampilkan angka murni (0, 1, 5, 12, dst) -->
            <div class="stat-value">{{ $totalHadir ?? 0 }}</div>
        </div>
        <div class="stat-icon primary"><i class="fa-solid fa-calendar-check"></i></div>
    </a>

    <a href="{{ route('peserta.logbook') }}" class="stat-card">
        <div class="stat-info">
            <h4>Logbook Disetujui</h4>
            <div class="stat-value">{{ $approvedLog ?? 0 }} <span style="font-size: 14px; font-weight: 600; color: var(--muted);">/ {{ $totalLog ?? 0 }}</span></div>
        </div>
        <div class="stat-icon info"><i class="fa-solid fa-book-open"></i></div>
    </a>

    <a href="{{ route('peserta.aduan') }}" class="stat-card">
        <div class="stat-info">
            <h4>Aduan Diproses</h4>
            <div class="stat-value">{{ $totalAduanAktif ?? 0 }}</div>
        </div>
        <div class="stat-icon {{ ($totalAduanAktif ?? 0) > 0 ? 'danger' : 'warning' }}"><i class="fa-solid fa-circle-exclamation"></i></div>
    </a>

    <a href="{{ route('peserta.nilai') }}" class="stat-card">
        <div class="stat-info">
            <h4>Rata-rata Nilai</h4>
            <div class="stat-value">{{ ($rataRataNilai ?? 0) > 0 ? $rataRataNilai : '-' }}</div>
        </div>
        <div class="stat-icon purple"><i class="fa-solid fa-star"></i></div>
    </a>
</div>

<!-- BENTO GRID -->
<div class="bento-grid animate-fade-up delay-2">

    <!-- PENGUMUMAN (2 Kolom) -->
    <div class="card span-2">
        <div class="card-header">
            <h3>Pengumuman Perusahaan</h3>
            <a href="{{ route('peserta.informasi') }}" class="btn-action-sm" title="Lihat semua pengumuman"><i class="fa-solid fa-arrow-right"></i></a>
        </div>

        @forelse($limitedAnnouncements as $ann)
            <div class="feed-list" style="margin-bottom: 8px;">
                <div class="feed-item">
                    <div class="feed-top">
                        <h4 class="feed-title">{{ $ann->title }}</h4>
                        <span class="feed-time">{{ isset($ann->created_at) ? $ann->created_at->diffForHumans() : '' }}</span>
                    </div>
                    <p class="feed-desc">{{ Str::limit(strip_tags($ann->content ?? ''), 120) }}</p>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fa-regular fa-clipboard"></i>
                <p>Belum ada pengumuman terbaru.</p>
            </div>
        @endforelse
    </div>

    <!-- NOTIFIKASI AKTIVITAS (1 Kolom) -->
    <div class="card">
        <div class="card-header">
            <h3>Notifikasi Terbaru</h3>
            <a href="{{ route('peserta.notifikasi') }}" class="btn-action-sm" title="Lihat semua notifikasi"><i class="fa-solid fa-arrow-right"></i></a>
        </div>

        @forelse($safeNotifications as $notif)
            @php
                $type = $notif->type ?? 'info';
                $colors = [
                    'success' => ['bg' => '#ECFDF5', 'border' => '#A7F3D0', 'text' => '#059669'],
                    'warning' => ['bg' => '#FFFBEB', 'border' => '#FDE68A', 'text' => '#D97706'],
                    'info'    => ['bg' => '#F8FAFC', 'border' => '#F1F5F9', 'text' => 'var(--text, #0F172A)'],
                ];
                $style = $colors[$type] ?? $colors['info'];
            @endphp
            <div class="feed-list" style="margin-bottom: 8px;">
                <div class="feed-item" style="background: {{ $style['bg'] }}; border-color: {{ $style['border'] }};">
                    <div class="feed-top">
                        <h4 class="feed-title" style="color: {{ $style['text'] }};">{{ $notif->title ?? 'Pemberitahuan' }}</h4>
                    </div>
                    <p class="feed-desc">{{ Str::limit($notif->message ?? '-', 60) }}</p>
                    <div style="margin-top: 6px; font-size: 10.5px; color: var(--muted, #64748B); font-weight: 600;">
                        {{ isset($notif->created_at) ? $notif->created_at->diffForHumans() : '' }}
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fa-regular fa-bell-slash"></i>
                <p>Belum ada notifikasi.</p>
            </div>
        @endforelse
    </div>

</div>

@endsection
