@extends('peserta.layout')

@section('title', 'Dashboard Peserta')

@section('content')

    <!-- Header Dashboard -->
    <div class="page-header-card">
        <div class="page-header-content">
            <div class="page-icon">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
            <div>
                <h1>Selamat Datang, {{ $mhs->nama_lengkap }}!</h1>
                <p>NIM: {{ $mhs->nim }} &bull; {{ $mhs->universitas->nama_universitas ?? 'Universitas' }} &bull; {{ $penempatan->divisi->nama_divisi ?? 'Divisi Umum' }}</p>
            </div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 13px; font-weight: 700; color: var(--primary-color);">
                <i class="fa-regular fa-calendar-days"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
            <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                Jam Kerja: {{ substr($company->work_start_time ?? '08:00:00', 0, 5) }} - {{ substr($company->work_end_time ?? '16:00:00', 0, 5) }} WIB (PLTU Adipala)
            </div>
        </div>
    </div>

    <!-- Live Status Hari Ini -->
    <div class="card" style="border-top: 4px solid var(--primary-color);">
        <div class="card-title-box">
            <h3><i class="fa-solid fa-clock"></i> Status Kegiatan Hari Ini</h3>
            <span class="badge {{ $attendanceToday ? ($attendanceToday->check_in_status === 'late' ? 'badge-late' : 'badge-present') : 'badge-secondary' }}">
                {{ $attendanceToday ? ($attendanceToday->check_in_status === 'late' ? 'Terlambat Hadir' : 'Sudah Hadir') : 'Belum Presensi' }}
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <!-- Check In -->
            <div style="background: #f8fafc; padding: 14px 18px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <div style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase;">Check-in Masuk</div>
                <div style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 4px 0;">
                    {{ $attendanceToday && $attendanceToday->check_in_at ? $attendanceToday->check_in_at->format('H:i') . ' WIB' : 'Belum Check-in' }}
                </div>
                <div style="font-size: 11.5px; color: {{ $attendanceToday && $attendanceToday->late_minutes > 0 ? '#b45309' : '#059669' }};">
                    @if($attendanceToday && $attendanceToday->check_in_at)
                        @if($attendanceToday->late_minutes > 0)
                            <i class="fa-solid fa-triangle-exclamation"></i> Terlambat {{ $attendanceToday->late_minutes }} menit
                        @else
                            <i class="fa-solid fa-circle-check"></i> Tepat Waktu
                        @endif
                    @else
                        Batas toleransi: {{ \Carbon\Carbon::createFromFormat('H:i:s', $company->work_start_time ?? '08:00:00')->addMinutes($policy->late_tolerance_minutes ?? 15)->format('H:i') }} WIB
                    @endif
                </div>
            </div>

            <!-- Check Out -->
            <div style="background: #f8fafc; padding: 14px 18px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <div style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase;">Check-out Pulang</div>
                <div style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 4px 0;">
                    {{ $attendanceToday && $attendanceToday->check_out_at ? $attendanceToday->check_out_at->format('H:i') . ' WIB' : 'Belum Pulang' }}
                </div>
                <div style="font-size: 11.5px; color: #64748b;">
                    @if($attendanceToday && $attendanceToday->check_out_at)
                        <i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Kerja: {{ floor($attendanceToday->total_work_minutes / 60) }}j {{ $attendanceToday->total_work_minutes % 60 }}m
                    @else
                        Mulai pukul {{ substr($company->work_end_time ?? '16:00:00', 0, 5) }} WIB
                    @endif
                </div>
            </div>

            <!-- Logbook Hari Ini -->
            <div style="background: #f8fafc; padding: 14px 18px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <div style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase;">Logbook Hari Ini</div>
                <div style="font-size: 16px; font-weight: 800; color: #1e293b; margin: 5px 0;">
                    @if($logbookToday)
                        <span class="badge badge-{{ $logbookToday->status }}">
                            {{ strtoupper($logbookToday->status) }}
                        </span>
                    @else
                        <span style="color: #ef4444; font-size: 14px;">Belum Diisi</span>
                    @endif
                </div>
                <div style="font-size: 11.5px; color: #64748b;">
                    {{ $logbookToday ? Str::limit($logbookToday->title, 25) : 'Wajib diisi sebelum jam 17:00' }}
                </div>
            </div>

            <!-- Status Cuti / Pembimbing -->
            <div style="background: #f8fafc; padding: 14px 18px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <div style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase;">Pembimbing Lapangan</div>
                <div style="font-size: 15px; font-weight: 700; color: #1e293b; margin: 5px 0;">
                    {{ $penempatan->nama_pembimbing ?? 'Pembimbing Lapangan' }}
                </div>
                <div style="font-size: 11.5px; color: #0284c7;">
                    {{ $penempatan->divisi->nama_divisi ?? 'PLTU Adipala' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi Cepat (README Section 4.5) -->
    <div class="card-title-box" style="margin-top: 10px;">
        <h3><i class="fa-solid fa-bolt"></i> Aksi Cepat</h3>
    </div>
    <div class="quick-action-grid">
        <a href="{{ route('peserta.absensi') }}" class="quick-action-btn">
            <i class="fa-solid fa-qrcode"></i>
            <span>Presensi Masuk / Pulang</span>
        </a>

        <a href="{{ route('peserta.logbook') }}" class="quick-action-btn">
            <i class="fa-regular fa-pen-to-square"></i>
            <span>Isi Logbook Hari Ini</span>
        </a>

        <a href="{{ route('peserta.cuti') }}" class="quick-action-btn">
            <i class="fa-regular fa-calendar-plus"></i>
            <span>Ajukan Cuti / Izin</span>
        </a>

        <a href="{{ route('peserta.aduan') }}" class="quick-action-btn">
            <i class="fa-regular fa-comment-dots"></i>
            <span>Buat Aduan Masalah</span>
        </a>

        <a href="{{ route('peserta.nilai') }}" class="quick-action-btn">
            <i class="fa-regular fa-star"></i>
            <span>Lihat Arsip Nilai</span>
        </a>

        <a href="{{ route('peserta.informasi') }}" class="quick-action-btn">
            <i class="fa-regular fa-building"></i>
            <span>Info & Panduan PLTU</span>
        </a>
    </div>

    <!-- Statistik Peserta (README Section 4.3) -->
    <div class="card-title-box" style="margin-top: 10px;">
        <h3><i class="fa-solid fa-chart-pie"></i> Ringkasan Statistik Magang</h3>
    </div>
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-calendar-check"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $totalHadir }}</div>
                <div class="stat-label">Total Kehadiran</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fa-solid fa-clock-rotate-left"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $totalTerlambat }}</div>
                <div class="stat-label">Keterlambatan</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue"><i class="fa-solid fa-envelope-open-text"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $totalIzinSakit }}</div>
                <div class="stat-label">Izin / Sakit / Cuti</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon teal"><i class="fa-solid fa-book"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $logbookDisetujui }} <span style="font-size: 13px; font-weight: 500; color: #64748b;">/ {{ $totalLogbook }}</span></div>
                <div class="stat-label">Logbook Disetujui</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple"><i class="fa-solid fa-medal"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $rataRataNilai > 0 ? $rataRataNilai : '-' }}</div>
                <div class="stat-label">Rata-rata Nilai</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red"><i class="fa-solid fa-circle-exclamation"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $totalAduanAktif }}</div>
                <div class="stat-label">Aduan Aktif</div>
            </div>
        </div>
    </div>

    <!-- Grid Pengumuman & Aktivitas Terbaru -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-top: 10px;">
        <!-- Pengumuman Perusahaan -->
        <div class="card">
            <div class="card-title-box">
                <h3><i class="fa-solid fa-bullhorn"></i> Pengumuman Perusahaan</h3>
                <a href="{{ route('peserta.informasi') }}" class="btn btn-sm btn-outline">Lihat Semua</a>
            </div>

            @forelse($announcements as $ann)
                <div style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                    <div style="font-size: 11px; color: #64748b;">
                        <i class="fa-regular fa-clock"></i> {{ $ann->created_at->diffForHumans() }}
                    </div>
                    <div style="font-size: 14px; font-weight: 700; color: var(--dark-color); margin: 3px 0;">
                        {{ $ann->title }}
                    </div>
                    <p style="font-size: 12.5px; color: #475569; line-height: 1.4;">
                        {{ Str::limit($ann->content, 110) }}
                    </p>
                </div>
            @empty
                <div style="text-align: center; padding: 24px 0; color: #94a3b8;">
                    <i class="fa-regular fa-clipboard" style="font-size: 32px; margin-bottom: 8px;"></i>
                    <p>Belum ada pengumuman terbaru dari perusahaan.</p>
                </div>
            @endforelse
        </div>

        <!-- Notifikasi & Aktivitas -->
        <div class="card">
            <div class="card-title-box">
                <h3><i class="fa-regular fa-bell"></i> Aktivitas & Notifikasi</h3>
                <a href="{{ route('peserta.notifikasi') }}" class="btn btn-sm btn-outline">Lihat Semua</a>
            </div>

            @forelse($notifications as $notif)
                <div style="padding: 12px 0; border-bottom: 1px solid #f1f5f9; display: flex; gap: 12px; align-items: flex-start;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: {{ $notif->type === 'success' ? '#ecfdf5' : ($notif->type === 'warning' ? '#fffbeb' : '#e0f2fe') }}; color: {{ $notif->type === 'success' ? '#059669' : ($notif->type === 'warning' ? '#d97706' : '#0284c7') }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <div style="font-size: 13px; font-weight: 600; color: #1e293b;">{{ $notif->title }}</div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 2px;">{{ $notif->message }}</div>
                        <div style="font-size: 10.5px; color: #94a3b8; margin-top: 4px;">{{ $notif->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 24px 0; color: #94a3b8;">
                    <i class="fa-regular fa-bell-slash" style="font-size: 32px; margin-bottom: 8px;"></i>
                    <p>Belum ada notifikasi aktivitas.</p>
                </div>
            @endforelse
        </div>
    </div>

@endsection
