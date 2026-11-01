@extends('pembimbing.layout')

@section('title', 'Dashboard Pembimbing Lapangan')

@section('content')
<!-- Header Dashboard -->
<div class="page-header-card">
    <div class="page-header-content">
        <div class="page-header-icon">
            <i class="fa-solid fa-chalkboard-user"></i>
        </div>
        <div>
            <h1>Selamat Datang, {{ Auth::user()->nama_lengkap }}!</h1>
            <p>Portal Khusus Pembimbing Lapangan — Pantau absensi geofence, validasi logbook harian, dan berikan evaluasi nilai kepada mahasiswa bimbingan Anda.</p>
        </div>
    </div>
    <div>
        <span class="badge-mentor-scope">
            <i class="fa-solid fa-shield-halved"></i>
            Monitoring Bimbingan Terisolasi
        </span>
    </div>
</div>

<!-- Statistik Cepat Bimbingan -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-user-graduate"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalMahasiswa }}</div>
            <div class="stat-label">Total Peserta Bimbingan</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $hadirHariIni }} <span style="font-size: 14px; color: var(--text-muted); font-weight: 500;">/ {{ $totalMahasiswa }}</span></div>
            <div class="stat-label">Presensi Hadir Hari Ini</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $pendingLogbookCount }}</div>
            <div class="stat-label">Logbook Menunggu Validasi</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-clipboard-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $pendingCorrectionsCount + $pendingCutiCount }}</div>
            <div class="stat-label">Koreksi & Cuti Menunggu</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $activeAduanCount }}</div>
            <div class="stat-label">Aduan Masalah Aktif</div>
        </div>
    </div>
</div>

@if($pendingLogbookCount > 0 || $pendingCorrectionsCount > 0)
    <div class="alert alert-error" style="background: #FFFBEB; border-color: #FDE68A; color: #92400E;">
        <i class="fa-solid fa-bell" style="font-size: 20px; color: #D97706;"></i>
        <div>
            <strong>Perhatian Pembimbing:</strong> Terdapat
            @if($pendingLogbookCount > 0)
                <strong>{{ $pendingLogbookCount }} logbook harian</strong>
            @endif
            @if($pendingLogbookCount > 0 && $pendingCorrectionsCount > 0) dan @endif
            @if($pendingCorrectionsCount > 0)
                <strong>{{ $pendingCorrectionsCount }} pengajuan koreksi presensi</strong>
            @endif
            dari anak bimbingan Anda yang membutuhkan verifikasi hari ini.
            <div style="margin-top: 6px; display: flex; gap: 8px;">
                @if($pendingLogbookCount > 0)
                    <a href="{{ route('pembimbing.logbook', ['status' => 'submitted']) }}" class="btn btn-sm btn-primary">
                        <i class="fa-regular fa-book-open"></i> Review Logbook
                    </a>
                @endif
                @if($pendingCorrectionsCount > 0)
                    <a href="{{ route('pembimbing.absensi') }}" class="btn btn-sm btn-secondary">
                        <i class="fa-solid fa-location-dot"></i> Review Koreksi
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Daftar Peserta Bimbingan Anda -->
<div class="card-box">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-users"></i>
            Daftar Mahasiswa yang Anda Bimbing
        </div>
        <a href="{{ route('pembimbing.mahasiswa') }}" class="btn btn-sm btn-secondary">
            Lihat Semua ({{ $totalMahasiswa }}) &rarr;
        </a>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Institusi / Universitas</th>
                    <th>Divisi Penempatan</th>
                    <th>Periode Magang</th>
                    <th>Status Magang</th>
                    <th style="text-align: center;">Aksi Pembimbing</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswaBimbingan as $mhs)
                    @php
                        $pengajuan = $mhs->latestPengajuan;
                    @endphp
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 38px; height: 38px; border-radius: 50%; background: #0A5F63; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                    {{ strtoupper(substr($mhs->nama_lengkap, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $mhs->nama_lengkap }}</strong><br>
                                    <small style="color: var(--text-muted);">NIM: {{ $mhs->nim }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong>{{ $mhs->universitas->nama_universitas ?? '-' }}</strong><br>
                            <small style="color: var(--text-muted);">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge" style="background: #E0F2FE; color: #0369A1;">
                                {{ $pengajuan->divisi->nama_divisi ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($pengajuan)
                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }} s/d<br>
                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($pengajuan && $pengajuan->status === 'Sedang Magang')
                                <span class="badge badge-present">Sedang Magang</span>
                            @elseif($pengajuan && $pengajuan->status === 'Akan Magang')
                                <span class="badge badge-pending">Akan Magang</span>
                            @else
                                <span class="badge badge-draft">Selesai Magang</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('pembimbing.mahasiswa.detail', $mhs->id_mhs) }}" class="btn btn-sm btn-secondary" title="Detail Mahasiswa">
                                    <i class="fa-regular fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('pembimbing.nilai', ['mahasiswa_id' => $mhs->id_mhs]) }}" class="btn btn-sm btn-primary" title="Beri Evaluasi Nilai">
                                    <i class="fa-regular fa-star"></i> Nilai
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px; color: var(--text-muted);">
                            <i class="fa-regular fa-folder-open" style="font-size: 32px; display: block; margin-bottom: 8px;"></i>
                            Belum ada mahasiswa yang ditugaskan kepada Anda sebagai pembimbing lapangan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">
    <!-- Logbook Terbaru Butuh Validasi -->
    <div class="card-box">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-regular fa-book-open"></i>
                Logbook Harian Terbaru
            </div>
            <a href="{{ route('pembimbing.logbook') }}" class="btn btn-sm btn-secondary">Semua Logbook &rarr;</a>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Mahasiswa & Tanggal</th>
                        <th>Aktivitas Kegiatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogbooks as $log)
                        <tr>
                            <td>
                                <strong>{{ $log->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                                <small style="color: var(--text-muted);">{{ $log->logbook_date->format('d M Y') }}</small>
                            </td>
                            <td>
                                <strong>{{ Str::limit($log->title, 35) }}</strong><br>
                                <small style="color: var(--text-muted);">{{ Str::limit($log->activity, 45) }}</small>
                            </td>
                            <td>
                                @if($log->status === 'approved')
                                    <span class="badge badge-approved">Disetujui</span>
                                @elseif($log->status === 'submitted')
                                    <span class="badge badge-pending">Menunggu</span>
                                @elseif($log->status === 'revision_required')
                                    <span class="badge badge-revision">Revisi</span>
                                @else
                                    <span class="badge badge-rejected">{{ ucfirst($log->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 20px; color: var(--text-muted);">
                                Belum ada logbook yang diisi oleh peserta bimbingan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Koreksi Absensi Terbaru -->
    <div class="card-box">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-location-dot"></i>
                Permohonan Koreksi Presensi
            </div>
            <a href="{{ route('pembimbing.absensi') }}" class="btn btn-sm btn-secondary">Semua Presensi &rarr;</a>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Mahasiswa & Tanggal</th>
                        <th>Alasan Koreksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCorrections as $corr)
                        <tr>
                            <td>
                                <strong>{{ $corr->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                                <small style="color: var(--text-muted);">Tanggal: {{ \Carbon\Carbon::parse($corr->correction_date)->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <span style="font-size: 12.5px;">{{ Str::limit($corr->reason, 45) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('pembimbing.absensi') }}" class="btn btn-sm btn-primary">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 20px; color: var(--text-muted);">
                                Tidak ada permohonan koreksi absensi yang menunggu review.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
