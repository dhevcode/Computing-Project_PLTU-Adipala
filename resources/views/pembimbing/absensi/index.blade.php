@extends('pembimbing.layout')

@section('title', 'Monitoring Presensi & Koreksi Bimbingan')

@section('content')
<div class="page-header-card">
    <div class="page-header-content">
        <div class="page-header-icon">
            <i class="fa-solid fa-location-dot"></i>
        </div>
        <div>
            <h1>Monitoring Presensi & Validasi Koreksi</h1>
            <p>Pantau kehadiran geofencing harian dan setujui permohonan koreksi absensi khusus untuk mahasiswa bimbingan Anda.</p>
        </div>
    </div>
    <span class="badge-mentor-scope">
        <i class="fa-solid fa-shield-halved"></i>
        Bimbingan Saya
    </span>
</div>

<!-- Statistik Ringkas Tanggal Terpilih -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalMhs }}</div>
            <div class="stat-label">Total Anak Bimbingan</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalHadirHariIni }}</div>
            <div class="stat-label">Hadir Tepat Waktu</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa-solid fa-user-clock"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalTerlambatHariIni }}</div>
            <div class="stat-label">Terlambat</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-notes-medical"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalIzinHariIni }}</div>
            <div class="stat-label">Izin / Cuti Sakit</div>
        </div>
    </div>
</div>

<!-- Permohonan Koreksi Absensi Menunggu Persetujuan Pembimbing -->
@if($pendingCorrections->count() > 0)
    <div class="card-box" style="border: 2px solid #F59E0B;">
        <div class="card-header" style="background: #FFFBEB; margin: -24px -24px 20px; padding: 16px 24px; border-bottom: 1px solid #FDE68A;">
            <div class="card-title" style="color: #92400E;">
                <i class="fa-solid fa-bell" style="color: #D97706;"></i>
                Permohonan Koreksi Absensi Menunggu Review Anda ({{ $pendingCorrections->count() }})
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Tanggal Koreksi</th>
                        <th>Waktu yang Diajukan</th>
                        <th>Alasan & Bukti</th>
                        <th style="text-align: center;">Tindakan Pembimbing</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingCorrections as $corr)
                        <tr>
                            <td>
                                <strong>{{ $corr->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                                <small style="color: var(--text-muted);">NIM: {{ $corr->mahasiswa->nim ?? '-' }}</small>
                            </td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($corr->correction_date)->format('d M Y') }}</strong><br>
                                <span class="badge" style="background: #E0E7FF; color: #3730A3;">
                                    Tipe: {{ str_replace('_', ' ', ucfirst($corr->correction_type)) }}
                                </span>
                            </td>
                            <td>
                                <strong>Masuk:</strong> {{ $corr->requested_check_in_at ? \Carbon\Carbon::parse($corr->requested_check_in_at)->format('H:i') : '-' }}<br>
                                <strong>Pulang:</strong> {{ $corr->requested_check_out_at ? \Carbon\Carbon::parse($corr->requested_check_out_at)->format('H:i') : '-' }}
                            </td>
                            <td style="max-width: 250px;">
                                <div>{{ $corr->reason }}</div>
                                @if($corr->attachment_path)
                                    <a href="{{ asset(str_starts_with($corr->attachment_path, 'uploads/') ? $corr->attachment_path : 'uploads/koreksi/' . $corr->attachment_path) }}" target="_blank" style="font-size: 11px; color: #2563EB; font-weight: 600;">
                                        <i class="fa-solid fa-paperclip"></i> Lihat Lampiran Bukti
                                    </a>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <button type="button" class="btn btn-sm btn-success" onclick="openReviewModal({{ $corr->id_correction }}, '{{ $corr->mahasiswa->nama_lengkap }}', '{{ \Carbon\Carbon::parse($corr->correction_date)->format('d M Y') }}', 'approved')">
                                        <i class="fa-solid fa-check"></i> Setujui
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="openReviewModal({{ $corr->id_correction }}, '{{ $corr->mahasiswa->nama_lengkap }}', '{{ \Carbon\Carbon::parse($corr->correction_date)->format('d M Y') }}', 'rejected')">
                                        <i class="fa-solid fa-xmark"></i> Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Daftar Presensi Harian Bimbingan -->
<div class="card-box">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-calendar-check"></i>
            Daftar Kehadiran Harian Mahasiswa Bimbingan
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('pembimbing.absensi') }}" class="filter-bar">
        <div>
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Pilih Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Filter Mahasiswa</label>
            <select name="mahasiswa_id" class="form-select" onchange="this.form.submit()">
                <option value="all">-- Semua Mahasiswa Bimbingan --</option>
                @foreach($mahasiswaList as $m)
                    <option value="{{ $m->id_mhs }}" {{ request('mahasiswa_id') == $m->id_mhs ? 'selected' : '' }}>
                        {{ $m->nama_lengkap }} ({{ $m->nim }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Status Kehadiran</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="all">-- Semua Status --</option>
                <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Hadir Tepat Waktu</option>
                <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                <option value="leave" {{ request('status') === 'leave' ? 'selected' : '' }}>Izin Cuti</option>
                <option value="sick" {{ request('status') === 'sick' ? 'selected' : '' }}>Sakit</option>
            </select>
        </div>

        @if(request()->hasAny(['status', 'mahasiswa_id']) || $date !== \Carbon\Carbon::today()->toDateString())
            <div style="align-self: flex-end;">
                <a href="{{ route('pembimbing.absensi') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate-left"></i> Reset Filter
                </a>
            </div>
        @endif
    </form>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Peserta Mahasiswa</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status Presensi</th>
                    <th>Geofence & Validasi</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #0A5F63; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                    {{ strtoupper(substr($att->mahasiswa->nama_lengkap ?? 'M', 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $att->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                                    <small style="color: var(--text-muted);">NIM: {{ $att->mahasiswa->nim ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td><strong>{{ \Carbon\Carbon::parse($att->attendance_date)->format('d M Y') }}</strong></td>
                        <td>
                            @if($att->check_in_at)
                                <span style="font-weight: 600; color: #047857;">{{ \Carbon\Carbon::parse($att->check_in_at)->format('H:i:s') }}</span>
                            @else
                                <span style="color: var(--text-light);">-</span>
                            @endif
                        </td>
                        <td>
                            @if($att->check_out_at)
                                <span style="font-weight: 600; color: #1D4ED8;">{{ \Carbon\Carbon::parse($att->check_out_at)->format('H:i:s') }}</span>
                            @else
                                <span style="color: var(--text-light);">-</span>
                            @endif
                        </td>
                        <td>
                            @if($att->check_in_status === 'present')
                                <span class="badge badge-present"><i class="fa-solid fa-check"></i> Hadir Tepat Waktu</span>
                            @elseif($att->check_in_status === 'late')
                                <span class="badge badge-late"><i class="fa-solid fa-clock"></i> Terlambat ({{ $att->late_minutes }}m)</span>
                            @elseif($att->check_in_status === 'sick')
                                <span class="badge badge-sick"><i class="fa-solid fa-heart-pulse"></i> Sakit</span>
                            @elseif($att->check_in_status === 'leave')
                                <span class="badge badge-leave"><i class="fa-solid fa-calendar-xmark"></i> Cuti/Izin</span>
                            @else
                                <span class="badge badge-absent">{{ ucfirst($att->check_in_status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 12px; color: var(--text-muted);">
                                @if($att->distance_in_meters)
                                    <div><i class="fa-solid fa-route"></i> {{ round($att->distance_in_meters, 1) }}m dari kantor</div>
                                @endif
                                @php
                                    $selfieInUrl = $att->selfie_path ? asset(str_starts_with($att->selfie_path, 'uploads/') ? $att->selfie_path : 'uploads/selfie/' . $att->selfie_path) : null;
                                    $selfieOutUrl = $att->selfie_out_path ? asset(str_starts_with($att->selfie_out_path, 'uploads/') ? $att->selfie_out_path : 'uploads/selfie/' . $att->selfie_out_path) : null;
                                @endphp
                                @if($selfieInUrl || $selfieOutUrl)
                                    <div style="display: flex; gap: 4px; margin-top: 4px; flex-wrap: wrap;">
                                        @if($selfieInUrl)
                                            <button type="button" class="btn btn-sm btn-secondary" style="padding: 2px 8px; font-size: 11px;" onclick="viewSelfiePembimbing('{{ $selfieInUrl }}', 'Bukti Selfie Masuk - {{ $att->mahasiswa->nama_lengkap ?? '' }} ({{ $att->check_in_at ? \Carbon\Carbon::parse($att->check_in_at)->format('H:i') : '' }} WIB)')">
                                                <i class="fa-solid fa-camera"></i> Masuk
                                            </button>
                                        @endif
                                        @if($selfieOutUrl)
                                            <button type="button" class="btn btn-sm btn-secondary" style="padding: 2px 8px; font-size: 11px;" onclick="viewSelfiePembimbing('{{ $selfieOutUrl }}', 'Bukti Selfie Pulang - {{ $att->mahasiswa->nama_lengkap ?? '' }} ({{ $att->check_out_at ? \Carbon\Carbon::parse($att->check_out_at)->format('H:i') : '' }} WIB)')">
                                                <i class="fa-solid fa-camera"></i> Pulang
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td style="max-width: 200px; font-size: 12px; color: var(--text-muted);">
                            {{ $att->notes ?: '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">
                            Tidak ada data presensi pada tanggal / filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $attendances->links() }}
    </div>
</div>

<!-- Modal Review Koreksi Absensi -->
<div class="modal-overlay" id="reviewCorrectionModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="modalTitle">Review Permohonan Koreksi Absensi</h3>
            <button type="button" class="modal-close" onclick="closeReviewModal()">&times;</button>
        </div>
        <form id="reviewCorrectionForm" method="POST" action="">
            @csrf
            <input type="hidden" name="status" id="correctionStatusInput" value="">

            <div class="modal-body">
                <div style="margin-bottom: 16px; background: #F8FAFC; padding: 12px; border-radius: var(--radius-md);">
                    <p>Mahasiswa: <strong id="modalMhsName">-</strong></p>
                    <p>Tanggal Presensi: <strong id="modalDate">-</strong></p>
                    <p>Keputusan: <strong id="modalDecisionText" style="text-transform: uppercase;">-</strong></p>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="review_note" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                        Catatan Pembimbing Lapangan (Opsional)
                    </label>
                    <textarea name="review_note" id="review_note" rows="3" class="form-control" style="width: 100%;" placeholder="Tuliskan catatan arahan pembimbing untuk mahasiswa..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeReviewModal()">Batal</button>
                <button type="submit" class="btn" id="modalSubmitBtn">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Viewer Foto Selfie Presensi Pembimbing -->
<div class="modal-overlay" id="modalSelfiePembimbing">
    <div class="modal-card" style="max-width: 500px; text-align: center;">
        <div class="modal-header">
            <h3 id="pembimbingSelfieTitle"><i class="fa-solid fa-camera"></i> Bukti Selfie Presensi</h3>
            <button type="button" class="modal-close" onclick="closeSelfiePembimbing()">&times;</button>
        </div>
        <div class="modal-body" style="padding: 16px;">
            <img id="pembimbingSelfieImg" src="" style="width: 100%; max-height: 480px; border-radius: 10px; object-fit: contain; border: 1px solid #cbd5e1; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        </div>
        <div class="modal-footer" style="justify-content: center;">
            <button type="button" class="btn btn-secondary" onclick="closeSelfiePembimbing()">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openReviewModal(correctionId, mhsName, dateStr, status) {
    const modal = document.getElementById('reviewCorrectionModal');
    const form = document.getElementById('reviewCorrectionForm');
    const statusInput = document.getElementById('correctionStatusInput');
    const decisionText = document.getElementById('modalDecisionText');
    const submitBtn = document.getElementById('modalSubmitBtn');

    form.action = "{{ url('/pembimbing/absensi/koreksi') }}/" + correctionId + "/review";
    statusInput.value = status;

    document.getElementById('modalMhsName').textContent = mhsName;
    document.getElementById('modalDate').textContent = dateStr;

    if (status === 'approved') {
        decisionText.textContent = 'SETUJUI KOREKSI';
        decisionText.style.color = '#059669';
        submitBtn.className = 'btn btn-success';
        submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Setujui Koreksi';
    } else {
        decisionText.textContent = 'TOLAK KOREKSI';
        decisionText.style.color = '#DC2626';
        submitBtn.className = 'btn btn-danger';
        submitBtn.innerHTML = '<i class="fa-solid fa-xmark"></i> Tolak Koreksi';
    }

    modal.classList.add('active');
}

function closeReviewModal() {
    document.getElementById('reviewCorrectionModal').classList.remove('active');
}

function viewSelfiePembimbing(url, title) {
    document.getElementById('pembimbingSelfieImg').src = url;
    document.getElementById('pembimbingSelfieTitle').innerHTML = '<i class="fa-solid fa-camera"></i> ' + title;
    document.getElementById('modalSelfiePembimbing').classList.add('active');
}

function closeSelfiePembimbing() {
    document.getElementById('modalSelfiePembimbing').classList.remove('active');
}
</script>
@endpush
@endsection
