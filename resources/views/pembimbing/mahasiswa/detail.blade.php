@extends('pembimbing.layout')

@section('title', 'Detail Mahasiswa - ' . $mahasiswa->nama_lengkap)

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('pembimbing.mahasiswa') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Bimbingan
    </a>
</div>

<!-- Header Detail Profil Mahasiswa -->
<div class="card-box" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px;">
    <div style="display: flex; align-items: center; gap: 20px;">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #0A5F63, #0D8288); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 800; box-shadow: var(--shadow-glow);">
            {{ strtoupper(substr($mahasiswa->nama_lengkap, 0, 1)) }}
        </div>
        <div>
            <h1 style="font-size: 22px; font-weight: 700; color: var(--text-dark);">{{ $mahasiswa->nama_lengkap }}</h1>
            <p style="color: var(--text-muted); font-size: 13.5px; margin-top: 2px;">
                NIM: <strong>{{ $mahasiswa->nim }}</strong> • {{ $mahasiswa->universitas->nama_universitas ?? '-' }} ({{ $mahasiswa->jurusan->nama_jurusan ?? '-' }})
            </p>
            <div style="display: flex; gap: 8px; margin-top: 8px; flex-wrap: wrap;">
                <span class="badge" style="background: #E0F2FE; color: #0284C7;">
                    <i class="fa-solid fa-building"></i> Divisi: {{ $mahasiswa->latestPengajuan->divisi->nama_divisi ?? '-' }}
                </span>
                <span class="badge badge-present">
                    <i class="fa-solid fa-user-check"></i> Dibimbing oleh: {{ Auth::user()->nama_lengkap }}
                </span>
            </div>
        </div>
    </div>

    <div style="text-align: right;">
        <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Rata-rata Skor Evaluasi</div>
        <div style="font-size: 32px; font-weight: 800; color: #0A5F63;">
            {{ $avgScore ? number_format($avgScore, 1) : '-' }} <span style="font-size: 16px; color: var(--text-light); font-weight: 500;">/ 100</span>
        </div>
        <a href="{{ route('pembimbing.nilai', ['mahasiswa_id' => $mahasiswa->id_mhs]) }}" class="btn btn-sm btn-primary" style="margin-top: 6px;">
            <i class="fa-regular fa-star"></i> Beri Penilaian Baru
        </a>
    </div>
</div>

<!-- Statistik Kehadiran Bimbingan -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-calendar-day"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalPresensi }}</div>
            <div class="stat-label">Total Hari Tercatat</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalHadir }}</div>
            <div class="stat-label">Hadir Tepat Waktu</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalTerlambat }}</div>
            <div class="stat-label">Terlambat Masuk</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-notes-medical"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalIzin }}</div>
            <div class="stat-label">Izin / Cuti Sakit</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Info Kontak & Dokumen Magang -->
    <div>
        <div class="card-box">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-regular fa-address-card"></i> Informasi Kontak
                </div>
            </div>

            <div style="font-size: 13.5px; line-height: 1.8;">
                <p><strong>Email:</strong> {{ $mahasiswa->email ?: '-' }}</p>
                <p><strong>No. WhatsApp / HP:</strong> {{ $mahasiswa->no_telepon ?: '-' }}</p>
                <p><strong>Jenis Kelamin:</strong> {{ $mahasiswa->jenis_kelamin }}</p>
                <hr style="border: none; border-top: 1px solid var(--border-color); margin: 12px 0;">
                <p style="font-weight: 700; color: #DC2626;"><i class="fa-solid fa-triangle-exclamation"></i> Kontak Darurat</p>
                <p><strong>Nama:</strong> {{ $mahasiswa->emergency_contact_name ?: '-' }}</p>
                <p><strong>Telepon:</strong> {{ $mahasiswa->emergency_contact_phone ?: '-' }}</p>
            </div>
        </div>

        <div class="card-box">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-regular fa-folder-open"></i> Berkas Persyaratan
                </div>
            </div>

            @php
                $pengajuan = $mahasiswa->latestPengajuan;
            @endphp

            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                @if($pengajuan && $pengajuan->surat_permohonan)
                    <a href="{{ asset('uploads/surat_permohonan/' . $pengajuan->surat_permohonan) }}" target="_blank" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fa-regular fa-file-pdf" style="color: #DC2626; font-size: 16px;"></i> Surat Pengantar Kampus
                    </a>
                @endif

                @if($pengajuan && $pengajuan->proposal)
                    <a href="{{ asset('uploads/proposal/' . $pengajuan->proposal) }}" target="_blank" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fa-regular fa-file-lines" style="color: #2563EB; font-size: 16px;"></i> Proposal Magang
                    </a>
                @endif

                @if($pengajuan && $pengajuan->cv)
                    <a href="{{ asset('uploads/cv/' . $pengajuan->cv) }}" target="_blank" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fa-regular fa-file-user" style="color: #059669; font-size: 16px;"></i> Curriculum Vitae (CV)
                    </a>
                @endif

                @if($mahasiswa->file_laporan)
                    <a href="{{ asset('uploads/laporan/' . $mahasiswa->file_laporan) }}" target="_blank" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fa-regular fa-file-zipper" style="color: #7C3AED; font-size: 16px;"></i> Laporan Akhir PKL
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Riwayat Aktivitas & Evaluasi -->
    <div class="card-box">
        <div class="tabs-nav">
            <button type="button" class="tab-btn active" onclick="switchTab(event, 'tab-presensi')">
                <i class="fa-solid fa-location-dot"></i> Presensi Terbaru
            </button>
            <button type="button" class="tab-btn" onclick="switchTab(event, 'tab-logbook')">
                <i class="fa-regular fa-book-open"></i> Logbook Harian
            </button>
            <button type="button" class="tab-btn" onclick="switchTab(event, 'tab-nilai')">
                <i class="fa-regular fa-star"></i> Arsip Penilaian
            </button>
        </div>

        <!-- Tab 1: Presensi -->
        <div id="tab-presensi" class="tab-pane active">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status Kehadiran</th>
                            <th>Radius Geofence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAttendances as $att)
                            <tr>
                                <td><strong>{{ \Carbon\Carbon::parse($att->attendance_date)->format('d/m/Y') }}</strong></td>
                                <td>{{ $att->check_in_at ? \Carbon\Carbon::parse($att->check_in_at)->format('H:i:s') : '-' }}</td>
                                <td>{{ $att->check_out_at ? \Carbon\Carbon::parse($att->check_out_at)->format('H:i:s') : '-' }}</td>
                                <td>
                                    @if($att->check_in_status === 'present')
                                        <span class="badge badge-present">Hadir Tepat Waktu</span>
                                    @elseif($att->check_in_status === 'late')
                                        <span class="badge badge-late">Terlambat ({{ $att->late_minutes }}m)</span>
                                    @elseif(in_array($att->check_in_status, ['leave', 'sick']))
                                        <span class="badge badge-leave">{{ ucfirst($att->check_in_status) }}</span>
                                    @else
                                        <span class="badge badge-absent">{{ ucfirst($att->check_in_status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att->distance_in_meters)
                                        <span style="font-size: 12px; color: var(--text-muted);">{{ round($att->distance_in_meters, 1) }} meter</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                    Belum ada data presensi yang tercatat untuk mahasiswa ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 12px; text-align: right;">
                <a href="{{ route('pembimbing.absensi', ['mahasiswa_id' => $mahasiswa->id_mhs]) }}" class="btn btn-sm btn-secondary">
                    Buka Monitoring Presensi Lengkap &rarr;
                </a>
            </div>
        </div>

        <!-- Tab 2: Logbook -->
        <div id="tab-logbook" class="tab-pane">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Judul Kegiatan</th>
                            <th>Status Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogbooks as $log)
                            <tr>
                                <td><strong>{{ $log->logbook_date->format('d/m/Y') }}</strong></td>
                                <td>
                                    <strong>{{ $log->title }}</strong><br>
                                    <small style="color: var(--text-muted);">{{ Str::limit($log->activity, 80) }}</small>
                                </td>
                                <td>
                                    @if($log->status === 'approved')
                                        <span class="badge badge-approved">Disetujui</span>
                                    @elseif($log->status === 'submitted')
                                        <span class="badge badge-pending">Menunggu Review</span>
                                    @elseif($log->status === 'revision_required')
                                        <span class="badge badge-revision">Perlu Revisi</span>
                                    @else
                                        <span class="badge badge-rejected">{{ ucfirst($log->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                    Belum ada logbook harian yang diisi oleh mahasiswa ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 12px; text-align: right;">
                <a href="{{ route('pembimbing.logbook', ['mahasiswa_id' => $mahasiswa->id_mhs]) }}" class="btn btn-sm btn-secondary">
                    Buka Monitoring Logbook Lengkap &rarr;
                </a>
            </div>
        </div>

        <!-- Tab 3: Nilai Evaluasi -->
        <div id="tab-nilai" class="tab-pane">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Tanggal Penilaian</th>
                            <th>Penilai</th>
                            <th>Skor Akhir</th>
                            <th>Status Publikasi</th>
                            <th>Catatan Peserta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $ev)
                            <tr>
                                <td><strong>{{ $ev->evaluation_date->format('d/m/Y') }}</strong></td>
                                <td>{{ $ev->evaluator->nama_lengkap ?? 'Pembimbing' }}</td>
                                <td>
                                    <strong style="color: #0A5F63; font-size: 16px;">{{ $ev->total_score }}</strong> / 100
                                </td>
                                <td>
                                    <span class="badge {{ $ev->status === 'published' ? 'badge-published' : 'badge-draft' }}">
                                        {{ $ev->status === 'published' ? 'Dipublikasikan' : 'Draft' }}
                                    </span>
                                </td>
                                <td style="font-size: 12.5px; color: var(--text-muted);">
                                    {{ $ev->participant_note ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                    Belum ada penilaian evaluasi yang diberikan kepada mahasiswa ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(evt, tabId) {
    const tabPanes = document.querySelectorAll('.tab-pane');
    tabPanes.forEach(pane => pane.classList.remove('active'));

    const tabBtns = document.querySelectorAll('.tab-btn');
    tabBtns.forEach(btn => btn.classList.remove('active'));

    document.getElementById(tabId).classList.add('active');
    evt.currentTarget.classList.add('active');
}
</script>
@endpush
@endsection
