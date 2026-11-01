@extends('pembimbing.layout')

@section('title', 'Monitoring Aduan Mahasiswa Bimbingan')

@section('content')
<div class="page-header-card">
    <div class="page-header-content">
        <div class="page-header-icon">
            <i class="fa-regular fa-circle-question"></i>
        </div>
        <div>
            <h1>Monitoring Aduan & Kendala Lapangan</h1>
            <p>Tanggapi kendala operasional, fasilitas, maupun pertanyaan teknis yang dilaporkan oleh mahasiswa bimbingan Anda.</p>
        </div>
    </div>
    <span class="badge-mentor-scope">
        <i class="fa-solid fa-headset"></i>
        Bimbingan Saya
    </span>
</div>

<!-- Statistik Aduan -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa-solid fa-inbox"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $pendingCount }}</div>
            <div class="stat-label">Aduan Baru Menunggu</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-spinner"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $inProgressCount }}</div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $resolvedCount }}</div>
            <div class="stat-label">Selesai Ditangani</div>
        </div>
    </div>
</div>

<div class="card-box">
    <!-- Filter Bar -->
    <form method="GET" action="{{ route('pembimbing.aduan') }}" class="filter-bar">
        <div>
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Status Aduan</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="all">-- Semua Status --</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Aduan Masuk (Submitted)</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai (Resolved)</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup (Closed)</option>
            </select>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Tingkat Prioritas</label>
            <select name="priority" class="form-select" onchange="this.form.submit()">
                <option value="all">-- Semua Prioritas --</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah (Low)</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Sedang (Medium)</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi (High)</option>
                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Mendesak (Urgent)</option>
            </select>
        </div>

        @if(request()->hasAny(['status', 'priority']))
            <div style="align-self: flex-end;">
                <a href="{{ route('pembimbing.aduan') }}" class="btn btn-secondary"><i class="fa-solid fa-rotate-left"></i> Reset Filter</a>
            </div>
        @endif
    </form>

    <!-- Tabel Aduan -->
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>No. Tiket</th>
                    <th>Mahasiswa Pengirim</th>
                    <th>Kategori & Prioritas</th>
                    <th>Subjek & Uraian Masalah</th>
                    <th>Status</th>
                    <th>Tanggal Aduan</th>
                    <th style="text-align: center;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $c)
                    <tr>
                        <td>
                            <strong style="color: #0A5F63;">#{{ $c->complaint_number }}</strong>
                        </td>
                        <td>
                            <strong>{{ $c->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                            <small style="color: var(--text-muted);">NIM: {{ $c->mahasiswa->nim ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge" style="background: #F1F5F9; color: #334155;">
                                {{ ucfirst($c->category) }}
                            </span><br>
                            @if($c->priority === 'urgent' || $c->priority === 'high')
                                <span class="badge" style="background: #FEE2E2; color: #991B1B; margin-top: 4px;">
                                    <i class="fa-solid fa-fire"></i> {{ ucfirst($c->priority) }}
                                </span>
                            @else
                                <span class="badge" style="background: #FEF3C7; color: #92400E; margin-top: 4px;">
                                    {{ ucfirst($c->priority) }}
                                </span>
                            @endif
                        </td>
                        <td style="max-width: 300px;">
                            <strong style="color: var(--text-dark);">{{ $c->title }}</strong>
                            <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 3px;">
                                {{ Str::limit($c->description, 70) }}
                            </div>
                            @if($c->attachment_path)
                                <div style="margin-top: 4px;">
                                    <a href="{{ asset(str_starts_with($c->attachment_path, 'uploads/') ? $c->attachment_path : 'uploads/aduan/' . $c->attachment_path) }}" target="_blank" style="font-size: 11.5px; color: #2563EB; font-weight: 600;">
                                        <i class="fa-solid fa-paperclip"></i> Bukti Lampiran
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($c->status === 'resolved' || $c->status === 'closed')
                                <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Selesai</span>
                            @elseif($c->status === 'in_progress')
                                <span class="badge" style="background: #E0F2FE; color: #0284C7;"><i class="fa-solid fa-spinner"></i> Diproses</span>
                            @else
                                <span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Menunggu Tanggapan</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $c->created_at->format('d M Y, H:i') }} WIB</small>
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-sm btn-primary" onclick='openReplyModal(@json($c))'>
                                <i class="fa-solid fa-reply"></i> Tanggapi
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 36px; color: var(--text-muted);">
                            <i class="fa-regular fa-comments" style="font-size: 36px; display: block; margin-bottom: 8px;"></i>
                            Tidak ada aduan kendala yang masuk dari anak bimbingan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $complaints->links() }}
    </div>
</div>

<!-- Modal Tanggapi Aduan -->
<div class="modal-overlay" id="replyModal">
    <div class="modal-card" style="max-width: 650px;">
        <div class="modal-header">
            <h3>Tanggapan Aduan Mahasiswa</h3>
            <button type="button" class="modal-close" onclick="closeReplyModal()">&times;</button>
        </div>
        <form id="replyForm" method="POST" action="">
            @csrf
            <div class="modal-body" style="max-height: 70vh;">
                <!-- Header Info Tiket -->
                <div style="background: #F8FAFC; padding: 12px 16px; border-radius: var(--radius-md); margin-bottom: 16px; border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between;">
                        <div>
                            <strong id="aduanTicketNum" style="color: #0A5F63;">-</strong>
                            <div id="aduanSender" style="font-size: 13px; font-weight: 600; color: var(--text-dark);">-</div>
                        </div>
                        <div id="aduanDate" style="font-size: 12px; color: var(--text-muted);">-</div>
                    </div>
                    <div id="aduanSubject" style="font-weight: 700; margin-top: 8px; color: var(--text-dark);"></div>
                    <div id="aduanBody" style="font-size: 13px; color: var(--text-muted); margin-top: 4px; white-space: pre-wrap; background: #fff; padding: 10px; border-radius: var(--radius-sm); border: 1px solid #E2E8F0;"></div>
                </div>

                <!-- Riwayat Diskusi Tanggapan Sebelumnya -->
                <div style="margin-bottom: 16px;">
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Riwayat Tanggapan:</label>
                    <div id="previousResponses" style="margin-top: 6px; display: flex; flex-direction: column; gap: 8px;"></div>
                </div>

                <!-- Input Balasan Pembimbing -->
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Pesan Balasan Pembimbing *</label>
                    <textarea name="message" rows="3" class="form-control" style="width: 100%;" placeholder="Tuliskan arahan, penjelasan, atau solusi kendala untuk anak bimbingan..." required></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Perbarui Status Aduan *</label>
                    <select name="status" id="aduanStatusSelect" class="form-select" style="width: 100%;" required>
                        <option value="in_progress">Sedang Diproses (In Progress)</option>
                        <option value="resolved">Selesai Ditangani (Resolved)</option>
                        <option value="waiting_for_participant">Menunggu Tanggapan Mahasiswa</option>
                        <option value="closed">Tutup Tiket (Closed)</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeReplyModal()">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Kirim Tanggapan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openReplyModal(c) {
    const modal = document.getElementById('replyModal');
    const form = document.getElementById('replyForm');

    form.action = "{{ url('/pembimbing/aduan') }}/" + c.id_complaint + "/reply";

    document.getElementById('aduanTicketNum').textContent = '#' + c.complaint_number;
    document.getElementById('aduanSender').textContent = c.mahasiswa ? c.mahasiswa.nama_lengkap + ' (' + c.mahasiswa.nim + ')' : '-';
    document.getElementById('aduanDate').textContent = c.created_at ? c.created_at.substring(0, 10) : '';
    document.getElementById('aduanSubject').textContent = c.title || '-';
    document.getElementById('aduanBody').textContent = c.description || '-';

    document.getElementById('aduanStatusSelect').value = c.status === 'submitted' ? 'in_progress' : c.status;

    // Riwayat Balasan
    const respContainer = document.getElementById('previousResponses');
    respContainer.innerHTML = '';
    if (c.responses && c.responses.length > 0) {
        c.responses.forEach(r => {
            const box = document.createElement('div');
            box.style.background = '#F1F5F9';
            box.style.padding = '8px 12px';
            box.style.borderRadius = '8px';
            box.style.fontSize = '12.5px';
            box.innerHTML = `<strong>${r.user ? r.user.nama_lengkap : 'Petugas'}:</strong> ${r.message}`;
            respContainer.appendChild(box);
        });
    } else {
        respContainer.innerHTML = '<span style="font-size: 12px; color: var(--text-light); font-style: italic;">Belum ada tanggapan sebelumnya.</span>';
    }

    modal.classList.add('active');
}

function closeReplyModal() {
    document.getElementById('replyModal').classList.remove('active');
}
</script>
@endpush
@endsection
