@extends('pembimbing.layout')

@section('title', 'Monitoring Cuti & Izin Bimbingan')

@section('content')
<div class="page-header-card">
    <div class="page-header-content">
        <div class="page-header-icon">
            <i class="fa-regular fa-calendar-check"></i>
        </div>
        <div>
            <h1>Pengajuan Cuti & Izin Bimbingan</h1>
            <p>Verifikasi dan tindak lanjuti permohonan ketidakhadiran, sakit, maupun izin akademik mahasiswa bimbingan Anda.</p>
        </div>
    </div>
    <span class="badge-mentor-scope">
        <i class="fa-solid fa-clipboard-user"></i>
        Bimbingan Saya
    </span>
</div>

<!-- Statistik Pengajuan Cuti -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $pendingCount }}</div>
            <div class="stat-label">Menunggu Persetujuan</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $approvedCount }}</div>
            <div class="stat-label">Pengajuan Disetujui</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fa-solid fa-circle-xmark"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $rejectedCount }}</div>
            <div class="stat-label">Pengajuan Ditolak</div>
        </div>
    </div>
</div>

<div class="card-box">
    <!-- Filter Bar -->
    <form method="GET" action="{{ route('pembimbing.cuti') }}" class="filter-bar">
        <div>
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Pilih Mahasiswa</label>
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
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Status Permohonan</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="all">-- Semua Status --</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Menunggu Review</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Jenis Izin / Cuti</label>
            <select name="leave_type" class="form-select" onchange="this.form.submit()">
                <option value="all">-- Semua Jenis --</option>
                <option value="sick_leave" {{ request('leave_type') === 'sick_leave' ? 'selected' : '' }}>Sakit</option>
                <option value="academic_leave" {{ request('leave_type') === 'academic_leave' ? 'selected' : '' }}>Izin Kampus / Akademik</option>
                <option value="personal_leave" {{ request('leave_type') === 'personal_leave' ? 'selected' : '' }}>Urusan Pribadi / Keluarga</option>
                <option value="emergency" {{ request('leave_type') === 'emergency' ? 'selected' : '' }}>Keadaan Darurat</option>
            </select>
        </div>

        @if(request()->hasAny(['status', 'leave_type', 'mahasiswa_id']))
            <div style="align-self: flex-end;">
                <a href="{{ route('pembimbing.cuti') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate-left"></i> Reset Filter
                </a>
            </div>
        @endif
    </form>

    <!-- Tabel Pengajuan Cuti -->
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Peserta Mahasiswa</th>
                    <th>Jenis Izin / Cuti</th>
                    <th>Periode & Durasi</th>
                    <th>Alasan & Dokumen</th>
                    <th>Status</th>
                    <th>Catatan Pembimbing</th>
                    <th style="text-align: center;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                    @php
                        $start = \Carbon\Carbon::parse($leave->start_date);
                        $end = \Carbon\Carbon::parse($leave->end_date);
                        $duration = $start->diffInDays($end) + 1;
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $leave->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                            <small style="color: var(--text-muted);">NIM: {{ $leave->mahasiswa->nim ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge" style="background: #EEF2FF; color: #4338CA;">
                                {{ \App\Models\LeaveRequest::getLeaveTypeLabel($leave->leave_type) }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $start->format('d M Y') }}</strong> s/d <strong>{{ $end->format('d M Y') }}</strong><br>
                            <small style="color: var(--text-muted);">Durasi: {{ $duration }} Hari</small>
                        </td>
                        <td style="max-width: 250px;">
                            <div style="font-size: 13px;">{{ $leave->reason }}</div>
                            @if($leave->attachment_path)
                                <div style="margin-top: 4px;">
                                    <a href="{{ asset(str_starts_with($leave->attachment_path, 'uploads/') ? $leave->attachment_path : 'uploads/cuti/' . $leave->attachment_path) }}" target="_blank" style="font-size: 11.5px; color: #2563EB; font-weight: 600;">
                                        <i class="fa-solid fa-paperclip"></i> Surat Dokter / Bukti Izin
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($leave->status === 'approved')
                                <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Disetujui</span>
                            @elseif($leave->status === 'submitted')
                                <span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Menunggu</span>
                            @elseif($leave->status === 'rejected')
                                <span class="badge badge-rejected"><i class="fa-solid fa-xmark"></i> Ditolak</span>
                            @else
                                <span class="badge badge-draft">{{ ucfirst($leave->status) }}</span>
                            @endif
                        </td>
                        <td style="max-width: 180px; font-size: 12px; color: var(--text-muted);">
                            {{ $leave->review_note ?: '-' }}
                        </td>
                        <td style="text-align: center;">
                            @if($leave->status === 'submitted')
                                <div style="display: inline-flex; gap: 6px;">
                                    <button type="button" class="btn btn-sm btn-success" onclick="openReviewCutiModal({{ $leave->id_leave }}, '{{ $leave->mahasiswa->nama_lengkap }}', '{{ \App\Models\LeaveRequest::getLeaveTypeLabel($leave->leave_type) }}', 'approved')">
                                        <i class="fa-solid fa-check"></i> Setujui
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="openReviewCutiModal({{ $leave->id_leave }}, '{{ $leave->mahasiswa->nama_lengkap }}', '{{ \App\Models\LeaveRequest::getLeaveTypeLabel($leave->leave_type) }}', 'rejected')">
                                        <i class="fa-solid fa-xmark"></i> Tolak
                                    </button>
                                </div>
                            @else
                                <span style="font-size: 12px; color: var(--text-light);">Telah ditinjau</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 36px; color: var(--text-muted);">
                            <i class="fa-regular fa-calendar-xmark" style="font-size: 36px; display: block; margin-bottom: 8px;"></i>
                            Tidak ada pengajuan cuti atau izin pada filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $leaves->links() }}
    </div>
</div>

<!-- Modal Review Cuti -->
<div class="modal-overlay" id="reviewCutiModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Persetujuan Pengajuan Cuti / Izin</h3>
            <button type="button" class="modal-close" onclick="closeReviewCutiModal()">&times;</button>
        </div>
        <form id="reviewCutiForm" method="POST" action="">
            @csrf
            <input type="hidden" name="status" id="cutiStatusInput" value="">

            <div class="modal-body">
                <div style="background: #F8FAFC; padding: 14px; border-radius: var(--radius-md); margin-bottom: 16px;">
                    <p>Mahasiswa: <strong id="modalCutiMhs">-</strong></p>
                    <p>Jenis Izin: <strong id="modalCutiType">-</strong></p>
                    <p>Tindakan: <strong id="modalCutiDecision" style="text-transform: uppercase;">-</strong></p>
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Catatan Pembimbing Lapangan (Opsional)</label>
                    <textarea name="review_note" rows="3" class="form-control" style="width: 100%;" placeholder="Tuliskan catatan alasan persetujuan atau penolakan cuti..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeReviewCutiModal()">Batal</button>
                <button type="submit" class="btn" id="modalCutiSubmitBtn">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openReviewCutiModal(leaveId, mhsName, leaveType, status) {
    const modal = document.getElementById('reviewCutiModal');
    const form = document.getElementById('reviewCutiForm');
    const statusInput = document.getElementById('cutiStatusInput');
    const decisionEl = document.getElementById('modalCutiDecision');
    const submitBtn = document.getElementById('modalCutiSubmitBtn');

    form.action = "{{ url('/pembimbing/cuti') }}/" + leaveId + "/review";
    statusInput.value = status;

    document.getElementById('modalCutiMhs').textContent = mhsName;
    document.getElementById('modalCutiType').textContent = leaveType;

    if (status === 'approved') {
        decisionEl.textContent = 'SETUJUI CUTI / IZIN';
        decisionEl.style.color = '#059669';
        submitBtn.className = 'btn btn-success';
        submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Setujui Pengajuan';
    } else {
        decisionEl.textContent = 'TOLAK CUTI / IZIN';
        decisionEl.style.color = '#DC2626';
        submitBtn.className = 'btn btn-danger';
        submitBtn.innerHTML = '<i class="fa-solid fa-xmark"></i> Tolak Pengajuan';
    }

    modal.classList.add('active');
}

function closeReviewCutiModal() {
    document.getElementById('reviewCutiModal').classList.remove('active');
}
</script>
@endpush
@endsection
