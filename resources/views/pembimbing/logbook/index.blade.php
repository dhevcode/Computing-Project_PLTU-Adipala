@extends('pembimbing.layout')

@section('title', 'Logbook PKL Bimbingan')

@section('content')
<div class="page-header-card">
    <div class="page-header-content">
        <div class="page-header-icon">
            <i class="fa-regular fa-book-open"></i>
        </div>
        <div>
            <h1>Monitoring & Validasi Logbook Harian</h1>
            <p>Tinjau dan berikan feedback bimbingan pada laporan aktivitas kerja harian mahasiswa magang yang Anda bimbing.</p>
        </div>
    </div>
    <span class="badge-mentor-scope">
        <i class="fa-solid fa-clipboard-check"></i>
        Bimbingan Saya
    </span>
</div>

<!-- Statistik Logbook -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $pendingCount }}</div>
            <div class="stat-label">Menunggu Validasi</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $approvedCount }}</div>
            <div class="stat-label">Telah Disetujui</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fa-solid fa-arrows-rotate"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $revisionCount }}</div>
            <div class="stat-label">Perlu Revisi Mahasiswa</div>
        </div>
    </div>
</div>

<div class="card-box">
    <!-- Filter Bar -->
    <form method="GET" action="{{ route('pembimbing.logbook') }}" class="filter-bar">
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
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Status Validasi</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="all">-- Semua Status --</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Menunggu Review</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="revision_required" {{ request('status') === 'revision_required' ? 'selected' : '' }}>Perlu Revisi</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Tanggal Kegiatan</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()">
        </div>

        <div style="flex: 1; min-width: 200px;">
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Cari Kegiatan</label>
            <input type="text" name="search" class="form-control" style="width: 100%;" placeholder="Cari judul/aktivitas..." value="{{ request('search') }}">
        </div>

        <div style="align-self: flex-end;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
            @if(request()->hasAny(['mahasiswa_id', 'status', 'date', 'search']))
                <a href="{{ route('pembimbing.logbook') }}" class="btn btn-secondary"><i class="fa-solid fa-rotate-left"></i> Reset</a>
            @endif
        </div>
    </form>

    <!-- Tabel Logbook -->
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Peserta Mahasiswa</th>
                    <th>Tanggal & Waktu</th>
                    <th>Judul & Aktivitas Kegiatan</th>
                    <th>Status</th>
                    <th>Catatan Review</th>
                    <th style="text-align: center;">Aksi Pembimbing</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logbooks as $log)
                    <tr>
                        <td>
                            <strong>{{ $log->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                            <small style="color: var(--text-muted);">NIM: {{ $log->mahasiswa->nim ?? '-' }}</small>
                        </td>
                        <td>
                            <strong>{{ $log->logbook_date->format('d M Y') }}</strong><br>
                            <small style="color: var(--text-muted);">{{ substr($log->start_time, 0, 5) }} - {{ substr($log->end_time, 0, 5) }} WIB</small>
                        </td>
                        <td style="max-width: 320px;">
                            <strong style="color: var(--text-dark);">{{ $log->title }}</strong>
                            <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 3px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $log->activity }}
                            </div>
                            @if($log->attachment_path)
                                <div style="margin-top: 4px;">
                                    <a href="{{ asset(str_starts_with($log->attachment_path, 'uploads/') ? $log->attachment_path : 'uploads/logbook/' . $log->attachment_path) }}" target="_blank" style="font-size: 11.5px; color: #2563EB; font-weight: 600;">
                                        <i class="fa-solid fa-paperclip"></i> {{ $log->attachment_name ?: 'Lampiran Berkas' }}
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($log->status === 'approved')
                                <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Disetujui</span>
                            @elseif($log->status === 'submitted')
                                <span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Menunggu Validasi</span>
                            @elseif($log->status === 'revision_required')
                                <span class="badge badge-revision"><i class="fa-solid fa-pencil"></i> Perlu Revisi</span>
                            @else
                                <span class="badge badge-rejected"><i class="fa-solid fa-xmark"></i> {{ ucfirst($log->status) }}</span>
                            @endif
                        </td>
                        <td style="max-width: 200px; font-size: 12px; color: var(--text-muted);">
                            {{ $log->review_note ?: '-' }}
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-sm btn-primary" onclick='openLogbookModal(@json($log))'>
                                <i class="fa-regular fa-eye"></i> Tinjau Logbook
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 36px; color: var(--text-muted);">
                            <i class="fa-regular fa-book-open" style="font-size: 36px; display: block; margin-bottom: 8px;"></i>
                            Tidak ada logbook yang ditemukan pada filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $logbooks->links() }}
    </div>
</div>

<!-- Modal Tinjau & Review Logbook -->
<div class="modal-overlay" id="logbookModal">
    <div class="modal-card" style="max-width: 700px;">
        <div class="modal-header">
            <h3>Tinjau & Validasi Logbook Kegiatan</h3>
            <button type="button" class="modal-close" onclick="closeLogbookModal()">&times;</button>
        </div>

        <form id="logbookReviewForm" method="POST" action="">
            @csrf
            <div class="modal-body" style="max-height: 70vh;">
                <!-- Ringkasan Info Mahasiswa -->
                <div style="background: #F8FAFC; padding: 14px 18px; border-radius: var(--radius-md); margin-bottom: 16px; border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong id="lbMhsName" style="font-size: 15px;">-</strong>
                            <div id="lbMhsNim" style="font-size: 12px; color: var(--text-muted);">-</div>
                        </div>
                        <div style="text-align: right;">
                            <div id="lbDate" style="font-weight: 700; color: #0A5F63;">-</div>
                            <div id="lbTime" style="font-size: 12px; color: var(--text-muted);">-</div>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Judul Aktivitas</label>
                    <div id="lbTitle" style="font-size: 15px; font-weight: 700; color: var(--text-dark); margin-top: 2px;"></div>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Uraian Aktivitas Kerja</label>
                    <div id="lbActivity" style="font-size: 13.5px; background: #FAFAFA; padding: 12px; border-radius: var(--radius-sm); border: 1px solid #E2E8F0; white-space: pre-wrap; margin-top: 4px;"></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Hasil Kegiatan (Output)</label>
                        <div id="lbResult" style="font-size: 13px; background: #FAFAFA; padding: 10px; border-radius: var(--radius-sm); border: 1px solid #E2E8F0; min-height: 50px; margin-top: 4px;"></div>
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Kendala & Hambatan</label>
                        <div id="lbObstacle" style="font-size: 13px; background: #FAFAFA; padding: 10px; border-radius: var(--radius-sm); border: 1px solid #E2E8F0; min-height: 50px; margin-top: 4px;"></div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Solusi Diterapkan</label>
                        <div id="lbSolution" style="font-size: 13px; background: #FAFAFA; padding: 10px; border-radius: var(--radius-sm); border: 1px solid #E2E8F0; min-height: 50px; margin-top: 4px;"></div>
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Rencana Kerja Selanjutnya</label>
                        <div id="lbNextPlan" style="font-size: 13px; background: #FAFAFA; padding: 10px; border-radius: var(--radius-sm); border: 1px solid #E2E8F0; min-height: 50px; margin-top: 4px;"></div>
                    </div>
                </div>

                <div id="lbAttachmentBox" style="margin-bottom: 20px; display: none;">
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Lampiran File Pendukung</label>
                    <div style="margin-top: 4px;">
                        <a id="lbAttachmentLink" href="" target="_blank" class="btn btn-sm btn-secondary">
                            <i class="fa-solid fa-paperclip"></i> Unduh / Lihat Lampiran
                        </a>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid var(--border-color); margin: 20px 0;">

                <!-- Form Keputusan Pembimbing -->
                <h4 style="font-size: 14px; font-weight: 700; color: #0A5F63; margin-bottom: 12px;">
                    <i class="fa-solid fa-stamp"></i> Keputusan Validasi Pembimbing
                </h4>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Status Verifikasi *</label>
                    <select name="status" id="lbReviewStatus" class="form-select" style="width: 100%;" required>
                        <option value="approved">Disetujui (Approved)</option>
                        <option value="revision_required">Perlu Revisi (Revision Required)</option>
                        <option value="rejected">Ditolak (Rejected)</option>
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Catatan Review Pembimbing (Wajib jika revisi/tolak)</label>
                    <textarea name="review_note" id="lbReviewNote" rows="2" class="form-control" style="width: 100%;" placeholder="Tuliskan catatan evaluasi atau alasan perbaikan logbook..."></textarea>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Komentar / Arahan Bimbingan (Opsional)</label>
                    <textarea name="comment" rows="2" class="form-control" style="width: 100%;" placeholder="Berikan saran atau arahan tugas untuk logbook ini..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeLogbookModal()">Tutup</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Validasi</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openLogbookModal(log) {
    const modal = document.getElementById('logbookModal');
    const form = document.getElementById('logbookReviewForm');

    form.action = "{{ url('/pembimbing/logbook') }}/" + log.id_logbook + "/review";

    document.getElementById('lbMhsName').textContent = log.mahasiswa ? log.mahasiswa.nama_lengkap : '-';
    document.getElementById('lbMhsNim').textContent = log.mahasiswa ? 'NIM: ' + log.mahasiswa.nim : '-';
    document.getElementById('lbDate').textContent = log.logbook_date ? log.logbook_date.split('T')[0] : '-';
    document.getElementById('lbTime').textContent = (log.start_time || '08:00') + ' - ' + (log.end_time || '16:00') + ' WIB';

    document.getElementById('lbTitle').textContent = log.title || '-';
    document.getElementById('lbActivity').textContent = log.activity || '-';
    document.getElementById('lbResult').textContent = log.result || '-';
    document.getElementById('lbObstacle').textContent = log.obstacle || '-';
    document.getElementById('lbSolution').textContent = log.solution || '-';
    document.getElementById('lbNextPlan').textContent = log.next_plan || '-';

    const attachBox = document.getElementById('lbAttachmentBox');
    const attachLink = document.getElementById('lbAttachmentLink');
    if (log.attachment_path) {
        attachBox.style.display = 'block';
        const cleanPath = log.attachment_path.replace(/^\//, '');
        attachLink.href = cleanPath.startsWith('uploads/') ? ("{{ url('/') }}/" + cleanPath) : ("{{ url('/uploads/logbook') }}/" + cleanPath);
        attachLink.textContent = log.attachment_name ? 'Unduh ' + log.attachment_name : 'Lihat Lampiran Berkas';
    } else {
        attachBox.style.display = 'none';
    }

    document.getElementById('lbReviewStatus').value = log.status === 'submitted' ? 'approved' : log.status;
    document.getElementById('lbReviewNote').value = log.review_note || '';

    modal.classList.add('active');
}

function closeLogbookModal() {
    document.getElementById('logbookModal').classList.remove('active');
}
</script>
@endpush
@endsection
