@extends('pembimbing.layouts.app')

@section('title', 'Monitoring Aduan Mahasiswa')
@section('page_title', 'Monitoring Aduan & Kendala Lapangan')
@section('page_subtitle', 'Tanggapi kendala operasional, fasilitas, maupun pertanyaan teknis yang dilaporkan oleh mahasiswa bimbingan Anda.')

@push('styles')
<style>
    /* ================= STATS GRID ================= */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: var(--bg, #ffffff); padding: 24px; border-radius: 16px; 
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border, #e2e8f0); 
        display: flex; align-items: center; justify-content: space-between; gap: 20px; 
        transition: transform 0.2s, box-shadow 0.2s; 
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
    
    .stat-info { flex: 1; text-align: left; }
    .stat-info span { font-size: 13px; font-weight: 600; color: var(--muted, #64748b); display: block; margin-bottom: 4px; }
    .stat-info h2 { font-size: 32px; font-weight: 800; color: var(--text, #0f172a); margin: 0 0 2px 0; line-height: 1; }

    .stat-icon { 
        width: 60px; height: 60px; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; 
        font-size: 26px; flex-shrink: 0; 
    }
    
    .icon-yellow { background: #FFFBEB; color: #D97706; }
    .icon-blue { background: #EFF6FF; color: #2563EB; }
    .icon-green { background: #ECFDF5; color: #059669; }

    /* ================= CARDS & TABLE ================= */
    .card { background: var(--bg, #ffffff); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border, #e2e8f0); overflow: hidden; padding: 24px; }
    
    .btn-sm { padding: 8px 14px; font-size: 12.5px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; transition: 0.2s; white-space: nowrap; height: fit-content; }
    .btn-primary { background: var(--primary, #0A5F63); color: #fff; border: 1px solid var(--primary, #0A5F63); }
    .btn-primary:hover { background: #07474a; border-color: #07474a; }
    .btn-outline { background: #F8FAFC; color: #475569; border: 1px solid var(--border, #e2e8f0); }
    .btn-outline:hover { background: #E2E8F0; color: var(--text, #0f172a); }

    /* Badges */
    .badge { padding: 4px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .badge-approved { background: #ECFDF5; color: #059669; }
    .badge-pending { background: #FFFBEB; color: #D97706; }
    .badge-process { background: #EFF6FF; color: #2563EB; }
    .badge-urgent { background: #FEF2F2; color: #DC2626; }
    .badge-normal { background: #F8FAFC; color: #475569; }

    /* Filter Form */
    .filter-wrapper { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; background: #F8FAFC; padding: 16px; border-radius: 12px; border: 1px solid var(--border, #e2e8f0); align-items: flex-end; }
    .filter-group { flex: 1; min-width: 180px; }
    .filter-group label { font-size: 11.5px; font-weight: 600; color: var(--muted, #64748b); display: block; margin-bottom: 6px; }
    .filter-actions { display: flex; gap: 10px; align-items: flex-end; }

    /* Sembunyikan Select Asli */
    select.custom-filter-select { display: none !important; }

    /* ================= REVIEW MODAL ================= */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; visibility: hidden; transition: 0.3s; backdrop-filter: blur(4px); }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-card { background: #fff; width: 100%; max-width: 650px; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); transform: translateY(20px); transition: 0.3s; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; }
    .modal-overlay.active .modal-card { transform: translateY(0); }
    .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border, #e2e8f0); display: flex; justify-content: space-between; align-items: center; }
    .modal-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: var(--text, #0f172a); }
    .modal-close { background: none; border: none; font-size: 24px; color: var(--muted, #64748b); cursor: pointer; line-height: 1; }
    .modal-body { padding: 24px; overflow-y: auto; flex: 1; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border, #e2e8f0); display: flex; justify-content: flex-end; gap: 12px; background: #F8FAFC; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .filter-wrapper { flex-direction: column; }
        .filter-group { width: 100%; }
        .filter-actions { width: 100%; justify-content: stretch; }
        .filter-actions button, .filter-actions a { flex: 1; justify-content: center; }
        
        /* Tabel Mobile */
        table, tbody, tr, td { display: block; width: 100%; }
        thead { display: none; }
        tbody tr { border: 1px solid var(--border, #e2e8f0); border-radius: 12px; margin-bottom: 16px; padding: 16px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        tbody td { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #F1F5F9; text-align: right; word-wrap: break-word; overflow-wrap: break-word; }
        tbody td:last-child { border-bottom: none; padding-bottom: 0; }
        tbody td[data-label]::before { content: attr(data-label); font-weight: 600; color: var(--muted, #64748b); text-align: left; flex-shrink: 0; margin-right: 12px; font-size: 12.5px; }
        
        tbody td.cell-user { display: block; text-align: left; background: #F8FAFC; padding: 14px; border-radius: 8px; margin-bottom: 12px; border-bottom: none; }
        tbody td.cell-user::before { display: none; }
        
        tbody td.cell-action { display: grid; grid-template-columns: 1fr; gap: 8px; padding-top: 16px; margin-top: 8px; }
        tbody td.cell-action::before { display: none; }
        tbody td.cell-action .btn-sm { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

    <!-- STATS GRID -->
    <div class="stats-grid animate-fade-up">
        <div class="stat-card">
            <div class="stat-info">
                <span>Aduan Baru Masuk</span>
                <h2>{{ str_pad($pendingCount ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-yellow"><i class="fa-solid fa-inbox"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Sedang Diproses</span>
                <h2>{{ str_pad($inProgressCount ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-blue"><i class="fa-solid fa-spinner"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Selesai Ditangani</span>
                <h2>{{ str_pad($resolvedCount ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-green"><i class="fa-solid fa-circle-check"></i></div>
        </div>
    </div>

    <!-- MAIN CARD -->
    <div class="card animate-fade-up delay-1">
        
        <!-- Filter Bar -->
        <form id="filterForm" method="GET" action="{{ route('pembimbing.aduan') }}" class="filter-wrapper">
            <div class="filter-group">
                <label>Status Aduan</label>
                <select name="status" class="custom-filter-select">
                    <option value="all">-- Semua Status --</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Masuk (Submitted)</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Diproses (In Progress)</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai (Resolved)</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup (Closed)</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Tingkat Prioritas</label>
                <select name="priority" class="custom-filter-select">
                    <option value="all">-- Semua Prioritas --</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah (Low)</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Sedang (Medium)</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi (High)</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Mendesak (Urgent)</option>
                </select>
            </div>

            <div class="filter-actions">
                @if(request()->hasAny(['status', 'priority']))
                    <a href="{{ route('pembimbing.aduan') }}" class="btn-sm btn-outline"><i class="fa-solid fa-rotate-left"></i> Reset Filter</a>
                @endif
            </div>
        </form>

        <!-- Tabel Aduan -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No. Tiket & Pengirim</th>
                        <th>Kategori & Prioritas</th>
                        <th>Subjek & Uraian Masalah</th>
                        <th>Status</th>
                        <th>Waktu Aduan</th>
                        <th style="text-align: center;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $c)
                        <tr>
                            <td class="cell-user">
                                <strong style="color: var(--primary, #0A5F63); display: block; font-size: 13.5px; margin-bottom: 4px;">#{{ $c->complaint_number }}</strong>
                                <strong style="color: var(--text, #0f172a); display: block;">{{ $c->mahasiswa->nama_lengkap ?? '-' }}</strong>
                                <small style="color: var(--muted, #64748b);">NIM: {{ $c->mahasiswa->nim ?? '-' }}</small>
                            </td>
                            <td data-label="Kategori">
                                <span class="badge badge-normal" style="margin-bottom: 4px;">
                                    {{ ucfirst($c->category) }}
                                </span><br>
                                @if($c->priority === 'urgent' || $c->priority === 'high')
                                    <span class="badge badge-urgent">
                                        <i class="fa-solid fa-fire"></i> {{ ucfirst($c->priority) }}
                                    </span>
                                @else
                                    <span class="badge" style="background: #FEF3C7; color: #92400E;">
                                        {{ ucfirst($c->priority) }}
                                    </span>
                                @endif
                            </td>
                            <td data-label="Masalah" style="max-width: 320px; text-align: left;">
                                <strong style="color: var(--text, #0f172a); font-size: 13.5px;">{{ $c->title }}</strong>
                                <div style="font-size: 12.5px; color: var(--muted, #64748b); margin-top: 3px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $c->description }}
                                </div>
                                @if($c->attachment_path)
                                    <div style="margin-top: 6px;">
                                        <a href="{{ asset(str_starts_with($c->attachment_path, 'uploads/') ? $c->attachment_path : 'uploads/aduan/' . $c->attachment_path) }}" target="_blank" style="font-size: 11.5px; color: #2563EB; font-weight: 600; text-decoration: none;">
                                            <i class="fa-solid fa-paperclip"></i> Bukti Lampiran
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td data-label="Status">
                                @if($c->status === 'resolved' || $c->status === 'closed')
                                    <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Selesai</span>
                                @elseif($c->status === 'in_progress')
                                    <span class="badge badge-process"><i class="fa-solid fa-spinner"></i> Diproses</span>
                                @else
                                    <span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Menunggu</span>
                                @endif
                            </td>
                            <td data-label="Waktu">
                                <small style="font-weight: 600; color: var(--muted, #64748b);">{{ $c->created_at->format('d M Y') }}</small><br>
                                <small style="color: var(--muted, #64748b);">{{ $c->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td class="cell-action">
                                <button type="button" class="btn-sm btn-primary" onclick='openReplyModal(@json($c))'>
                                    <i class="fa-solid fa-reply"></i> Tanggapi
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div style="text-align: center; padding: 36px; color: var(--muted, #64748b);">
                                    <i class="fa-regular fa-comments" style="font-size: 36px; display: block; margin-bottom: 12px; opacity: 0.5;"></i>
                                    Tidak ada aduan kendala yang masuk dari mahasiswa.
                                </div>
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

    <!-- MODAL TANGGAPI ADUAN -->
    <div class="modal-overlay" id="replyModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tanggapan Aduan & Kendala</h3>
                <button type="button" class="modal-close" onclick="closeReplyModal()">&times;</button>
            </div>
            
            <form id="replyForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <!-- Info Tiket -->
                    <div style="background: #F8FAFC; padding: 16px; border-radius: 8px; margin-bottom: 16px; border: 1px solid var(--border, #e2e8f0);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <div>
                                <strong id="aduanTicketNum" style="color: var(--primary, #0A5F63); font-size: 14px; margin-bottom: 4px; display: block;">-</strong>
                                <div id="aduanSender" style="font-size: 13.5px; font-weight: 700; color: var(--text, #0f172a);">-</div>
                            </div>
                            <div id="aduanDate" style="font-size: 12px; font-weight: 600; color: var(--muted, #64748b); text-align: right;">-</div>
                        </div>
                        <div id="aduanSubject" style="font-size: 14px; font-weight: 700; color: var(--text, #0f172a); margin-bottom: 6px;">-</div>
                        <div id="aduanBody" style="font-size: 13px; color: var(--text, #0f172a); background: #fff; padding: 12px; border-radius: 6px; border: 1px solid var(--border, #e2e8f0); white-space: pre-wrap; line-height: 1.5;">-</div>
                    </div>

                    <!-- Riwayat Diskusi -->
                    <div style="margin-bottom: 20px;">
                        <label style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase;">Riwayat Tanggapan Sebelumnya</label>
                        <div id="previousResponses" style="margin-top: 8px; display: flex; flex-direction: column; gap: 10px; max-height: 250px; overflow-y: auto; padding-right: 4px;"></div>
                    </div>
                    
                    <hr style="border: none; border-top: 1px dashed var(--border, #e2e8f0); margin: 20px 0;">

                    <!-- Form Tanggapan Baru -->
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12.5px; font-weight: 600; margin-bottom: 8px; color: var(--text, #0f172a);">Pesan Balasan Pembimbing *</label>
                        <textarea name="message" rows="3" style="width: 100%; padding: 12px; border-radius: 8px; border: 1.5px solid var(--border, #e2e8f0); outline: none; font-size: 13px; font-family: inherit; resize: vertical;" placeholder="Tuliskan arahan, penjelasan, atau solusi kendala..." required></textarea>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label style="display: block; font-size: 12.5px; font-weight: 600; margin-bottom: 8px; color: var(--text, #0f172a);">Perbarui Status Aduan *</label>
                        <select name="status" id="aduanStatusSelect" class="custom-filter-select" required>
                            <option value="in_progress">Sedang Diproses (In Progress)</option>
                            <option value="waiting_for_participant">Tunggu Respon Mahasiswa</option>
                            <option value="resolved">Selesai Ditangani (Resolved)</option>
                            <option value="closed">Tutup Tiket (Closed)</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-outline" onclick="closeReplyModal()">Batal</button>
                    <button type="submit" class="btn-sm btn-primary"><i class="fa-solid fa-paper-plane"></i> Kirim Tanggapan</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Otomatis Submit pada Custom Dropdown Filter
    document.querySelectorAll('#filterForm .custom-filter-select').forEach(select => {
        const wrapper = select.nextElementSibling;
        if(wrapper && wrapper.classList.contains('custom-select-wrapper')) {
            wrapper.querySelectorAll('.custom-option').forEach(opt => {
                opt.addEventListener('click', () => {
                    document.getElementById('filterForm').submit();
                });
            });
        }
    });
});

function updateCustomDropdownValue(selectId, value) {
    const select = document.getElementById(selectId);
    if (!select) return;
    
    select.value = value;
    const wrapper = select.nextElementSibling;
    if (wrapper && wrapper.classList.contains('custom-select-wrapper')) {
        const activeOptionText = select.options[select.selectedIndex] ? select.options[select.selectedIndex].text : '';
        const triggerSpan = wrapper.querySelector('.custom-select-trigger span');
        if (triggerSpan) triggerSpan.textContent = activeOptionText;
        
        wrapper.querySelectorAll('.custom-option').forEach(opt => {
            if(opt.textContent === activeOptionText) {
                opt.classList.add('selected');
            } else {
                opt.classList.remove('selected');
            }
        });
    }
}

function openReplyModal(c) {
    const modal = document.getElementById('replyModal');
    const form = document.getElementById('replyForm');

    form.action = "{{ url('/pembimbing/aduan') }}/" + c.id_complaint + "/reply";

    document.getElementById('aduanTicketNum').textContent = '#' + c.complaint_number;
    document.getElementById('aduanSender').textContent = c.mahasiswa ? c.mahasiswa.nama_lengkap + ' (' + c.mahasiswa.nim + ')' : '-';
    document.getElementById('aduanDate').innerHTML = c.created_at ? c.created_at.substring(0, 10) + '<br>' + c.created_at.substring(11, 16) + ' WIB' : '-';
    document.getElementById('aduanSubject').textContent = c.title || '-';
    document.getElementById('aduanBody').textContent = c.description || '-';

    // Perbarui Dropdown Status di Modal
    const initialStatus = c.status === 'submitted' ? 'in_progress' : c.status;
    updateCustomDropdownValue('aduanStatusSelect', initialStatus);

    // Memuat Riwayat Balasan
    const respContainer = document.getElementById('previousResponses');
    respContainer.innerHTML = '';
    if (c.responses && c.responses.length > 0) {
        c.responses.forEach(r => {
            const isUser = r.user_id !== null; // Anda bisa menyesuaikan logika pengecekan role jika perlu
            const box = document.createElement('div');
            box.style.background = isUser ? '#F1F5F9' : '#ECFDF5';
            box.style.padding = '12px';
            box.style.borderRadius = '8px';
            box.style.border = '1px solid ' + (isUser ? '#e2e8f0' : '#D1FAE5');
            box.style.fontSize = '12.5px';
            box.style.lineHeight = '1.5';
            
            const senderName = r.user ? r.user.nama_lengkap : (r.mahasiswa ? r.mahasiswa.nama_lengkap : 'Sistem');
            
            box.innerHTML = `<strong style="display: block; margin-bottom: 4px; color: var(--text, #0f172a);">${senderName}:</strong> ${r.message}`;
            respContainer.appendChild(box);
        });
    } else {
        respContainer.innerHTML = '<div style="font-size: 12.5px; color: var(--muted, #64748b); font-style: italic; background: #F8FAFC; padding: 12px; border-radius: 8px; border: 1px dashed var(--border, #e2e8f0); text-align: center;">Belum ada tanggapan sebelumnya pada tiket ini.</div>';
    }

    modal.classList.add('active');
}

function closeReplyModal() {
    document.getElementById('replyModal').classList.remove('active');
}
</script>
@endpush