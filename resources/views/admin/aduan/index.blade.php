@extends('admin.layouts.app')

@section('title', 'Aduan Masalah')
@section('page_title', 'Penanganan Aduan Masalah')
@section('page_subtitle', 'Tindak lanjuti kendala teknis, lingkungan kerja, K3, dan administrasi dari peserta')

@push('styles')
<style>
    /* ================= STATS GRID ================= */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: var(--bg); padding: 24px; border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; gap: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }

    .stat-icon {
        width: 60px; height: 60px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px; flex-shrink: 0;
    }

    /* Background Soft / Transparan */
    .icon-yellow { background: #FFFBEB; color: #D97706; }
    .icon-blue { background: #EFF6FF; color: #2563EB; }
    .icon-green { background: #ECFDF5; color: #059669; }

    .stat-info { flex: 1; text-align: left; }
    .stat-info span { font-size: 13px; font-weight: 600; color: var(--muted); display: block; margin-bottom: 4px; }
    .stat-info h2 { font-size: 32px; font-weight: 800; color: var(--text); margin: 0 0 2px 0; line-height: 1; }

    /* ================= CARDS & TABLE ================= */
    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; padding: 20px 24px; }
    .card-header { margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--page); }
    .card-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0; display: flex; align-items: center; gap: 10px; }

    .badge { padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; border: 1px solid var(--border); }
    .badge-info { background: #F8FAFC; color: #334155; border-color: #E2E8F0; }

    /* Status Badges */
    .badge-submitted { background: #FEF3C7; color: #B45309; border-color: #FDE68A; }
    .badge-in_progress { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }
    .badge-resolved { background: var(--success-bg); color: var(--success-text); border-color: #A7F3D0; }
    .badge-closed { background: #F1F5F9; color: #475569; }

    /* Priority Badges */
    .badge-danger { background: #FEF2F2; color: #991B1B; border-color: #FECACA; }
    .badge-warning { background: #FFF7ED; color: #C2410C; border-color: #FFEDD5; }
    .badge-secondary { background: #F1F5F9; color: #64748B; border-color: #E2E8F0; }

    .btn-sm { padding: 6px 12px; font-size: 11.5px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; transition: 0.2s; white-space: nowrap; }
    .btn-outline { background: #F8FAFC; color: #475569; border: 1px solid var(--border); }
    .btn-outline:hover { background: #E2E8F0; color: var(--text); }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-primary:hover { background: var(--primary-dark); }
    .btn-filter-full { width: 100%; height: 38px; justify-content: center; font-size: 13px; border-radius: 8px; margin-top: 24px; }

    /* ================= FILTER GRID ================= */
    .filter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; align-items: end; }
    .form-group { margin-bottom: 0; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; }

    /* ================= MODAL ================= */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 20px; opacity: 0; transition: opacity 0.2s; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .custom-modal { background: #fff; width: 100%; max-width: 500px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: scale(0.95) translateY(20px); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .modal-overlay.active .custom-modal { transform: scale(1) translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F9FAFB; border-radius: 16px 16px 0 0; }
    .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0; display: flex; align-items: center; gap: 8px;}
    .modal-close { background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer; padding: 4px 8px; border-radius: 6px; transition: 0.2s; }
    .modal-close:hover { background: #FEE2E2; color: var(--error-text); }
    .modal-body { padding: 24px; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #fafbfc; border-radius: 0 0 16px 16px; }

    /* ================= RESPONSIVE ================= */
    @media (min-width: 761px) {
        .cell-deskripsi { max-width: 250px; text-align: left; }
    }
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
        .btn-filter-full { margin-top: 0; }
    }
    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .stat-card { padding: 16px 20px; }
        .filter-grid { grid-template-columns: 1fr; }
        .btn-filter-full { margin-top: 8px; }

        /* Tabel Mobile - Menghindari Bug Text Overflow */
        table, tbody, tr, td { display: block; width: 100%; }
        thead { display: none; }
        tbody tr { border: 1px solid var(--border); border-radius: 12px; margin-bottom: 16px; padding: 16px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        tbody td {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 10px 0; border-bottom: 1px solid #F1F5F9; text-align: right;
            word-wrap: break-word; overflow-wrap: break-word;
        }
        tbody td:last-child { border-bottom: none; padding-bottom: 0; }
        tbody td[data-label]::before { content: attr(data-label); font-weight: 600; color: var(--muted); text-align: left; flex-shrink: 0; margin-right: 12px; font-size: 12.5px; }

        tbody td.cell-user { display: block; text-align: left; background: var(--page); padding: 14px; border-radius: 8px; margin-bottom: 12px; border-bottom: none; }
        tbody td.cell-user::before { display: none; }

        /* Solusi Kolom Teks Panjang di Mobile turun ke bawah */
        tbody td.cell-deskripsi { flex-direction: column; text-align: left; }
        tbody td.cell-deskripsi::before { margin-bottom: 8px; }

        tbody td.cell-action { display: grid; grid-template-columns: 1fr; gap: 8px; padding-top: 16px; margin-top: 8px; }
        tbody td.cell-action::before { display: none; }
        tbody td.cell-action .btn-sm { width: 100%; justify-content: center; }

        .modal-footer { flex-direction: column-reverse; }
        .modal-footer .btn-sm { width: 100%; justify-content: center; height: 38px; }
    }
</style>
@endpush

@section('content')

    @if(session('success'))
        <div class="alert alert-success animate-fade-up">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- STATS GRID -->
    <div class="stats-grid animate-fade-up delay-1">
        <div class="stat-card">
            <div class="stat-info">
                <span>Aduan Baru Masuk</span>
                <h2>{{ str_pad($pendingCount, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-yellow"><i class="fa-solid fa-inbox"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span>Sedang Ditangani</span>
                <h2>{{ str_pad($inProgressCount, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-blue"><i class="fa-solid fa-arrows-rotate"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span>Aduan Selesai</span>
                <h2>{{ str_pad($resolvedCount, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-green"><i class="fa-solid fa-circle-check"></i></div>
        </div>
    </div>

    <!-- FILTER & DAFTAR ADUAN -->
    <div class="card animate-fade-up delay-2">
        <form action="{{ route('admin.aduan') }}" method="GET" class="filter-grid" style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--page);">

            <div class="form-group">
                <label>Status Aduan</label>
                <select name="status" class="d-none custom-select">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Aduan Masuk</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Ditangani</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tingkat Prioritas</label>
                <select name="priority" class="d-none custom-select">
                    <option value="all" {{ request('priority') === 'all' ? 'selected' : '' }}>Semua Prioritas</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Sedang</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                </select>
            </div>

            <div class="form-group">
                <label>Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="No Aduan / Judul..." class="input-box">
            </div>

            <div class="form-group">
                <button type="submit" class="btn-sm btn-primary btn-filter-full">
                    <i class="fa-solid fa-filter"></i> Filter Aduan
                </button>
            </div>
        </form>

        <div class="card-header" style="border: none; padding: 0 0 16px 0;">
            <h3><i class="fa-solid fa-list-check" style="color: var(--primary);"></i> Daftar Laporan Aduan Masalah</h3>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No Aduan</th>
                        <th>Mahasiswa & NIM</th>
                        <th>Kategori</th>
                        <th>Judul & Deskripsi</th>
                        <th style="text-align: center;">Prioritas</th>
                        <th style="text-align: center;">Bukti</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $c)
                        <tr>
                            <td data-label="No Aduan">
                                <strong style="color: var(--text);">{{ $c->complaint_number }}</strong><br>
                                <small style="color: var(--muted);">{{ $c->created_at->format('d/m/Y') }}</small>
                            </td>

                            <td class="cell-user">
                                <strong style="color: var(--text); display: block;">{{ $c->mahasiswa->nama_lengkap ?? '-' }}</strong>
                                <small style="color: var(--muted);">NIM: <span class="code-tag" style="background: transparent; padding: 0;">{{ $c->mahasiswa->nim ?? '-' }}</span></small>
                            </td>

                            <td data-label="Kategori">
                                <span class="badge badge-info">{{ match($c->category) {
                                    'technical' => 'Teknis / Sistem',
                                    'work_environment' => 'Lingkungan Kerja',
                                    'safety' => 'Keselamatan (K3)',
                                    'schedule' => 'Jadwal',
                                    'supervision' => 'Pembimbingan',
                                    'administration' => 'Administrasi',
                                    default => 'Lainnya',
                                } }}</span>
                            </td>

                            <td class="cell-deskripsi" data-label="Judul & Deskripsi">
                                <strong style="color: var(--text); display: block;">{{ $c->title }}</strong>
                                <p style="font-size: 12px; color: var(--muted); margin-top: 4px; line-height: 1.4; margin-bottom: 0;">{{ Str::limit($c->description, 90) }}</p>
                            </td>

                            <td data-label="Prioritas" style="text-align: center;">
                                <span class="badge {{ $c->priority === 'urgent' ? 'badge-danger' : ($c->priority === 'high' ? 'badge-warning' : 'badge-secondary') }}">
                                    {{ strtoupper($c->priority) }}
                                </span>
                            </td>

                            <td data-label="Bukti" style="text-align: center;">
                                @if($c->attachment_path)
                                    <a href="{{ asset($c->attachment_path) }}" target="_blank" class="btn-sm btn-outline">
                                        <i class="fa-solid fa-paperclip"></i> Lihat
                                    </a>
                                @else
                                    <span style="color: #94a3b8; font-size: 12px;">-</span>
                                @endif
                            </td>

                            <td data-label="Status" style="text-align: center;">
                                @php
                                    $badgeClass = match($c->status) {
                                        'submitted' => 'badge-submitted',
                                        'in_progress' => 'badge-in_progress',
                                        'resolved' => 'badge-resolved',
                                        default => 'badge-closed',
                                    };
                                    $statusText = match($c->status) {
                                        'submitted' => 'Aduan Masuk',
                                        'in_progress' => 'Ditangani',
                                        'resolved' => 'Selesai',
                                        'closed' => 'Ditutup',
                                        default => $c->status,
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                            </td>

                            <td class="cell-action" style="text-align: center;">
                                <button type="button" class="btn-sm btn-primary" onclick='openReplyModal({{ $c->id_complaint }}, @json($c))'>
                                    <i class="fa-regular fa-comment-dots"></i> Tanggapi
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <p>Tidak ada laporan aduan masalah yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($complaints->hasPages())
            <div class="pagination-wrapper" style="margin-top: 20px;">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>

<!-- ================= MODAL BALAS TANGGAPAN ADUAN ================= -->
<div class="modal-overlay" id="replyAduanModal">
    <div class="custom-modal">
        <div class="modal-header">
            <h3><i class="fa-regular fa-comment-dots" style="color: var(--primary);"></i> Tanggapan & Penanganan Aduan</h3>
            <button type="button" class="modal-close" onclick="closeReplyModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="" method="POST" id="replyForm">
            @csrf
            <div class="modal-body">
                <div id="aduanDetailBox" style="background: var(--page); border: 1px solid var(--border); border-radius: 8px; padding: 14px; margin-bottom: 20px; font-size: 12.5px; color: var(--muted); line-height: 1.6;">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Perbarui Status Aduan <span style="color: var(--error-text);">*</span></label>
                    <select id="rep_status" name="status" class="d-none custom-select" required>
                        <option value="in_progress">Sedang Ditangani (In Progress)</option>
                        <option value="waiting_for_participant">Menunggu Info Tambahan (Peserta)</option>
                        <option value="resolved">Telah Diselesaikan (Resolved)</option>
                        <option value="closed">Tutup Aduan (Closed)</option>
                        <option value="rejected">Tolak Aduan</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Pesan Tanggapan Resmi <span style="color: var(--error-text);">*</span></label>
                    <textarea id="rep_message" name="message" rows="3" class="input-box" style="height: auto; resize: vertical;" placeholder="Tuliskan tindak lanjut penanganan atau solusi untuk mahasiswa..." required minlength="3"></textarea>
                </div>

                <div class="form-group" style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 0;">
                    <input type="checkbox" id="is_internal" name="is_internal_note" value="1" style="margin-top: 2px;">
                    <label for="is_internal" style="margin: 0; font-size: 11.5px; color: var(--muted); line-height: 1.4; font-weight: 500;">
                        Catatan internal saja (Hanya terlihat oleh pembimbing & admin, tidak dikirimkan ke dashboard mahasiswa)
                    </label>
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

        // 1. CUSTOM DROPDOWN
        const nativeSelects = document.querySelectorAll('select.custom-select');
        nativeSelects.forEach(select => {
            if (select.nextElementSibling && select.nextElementSibling.classList.contains('custom-select-wrapper')) return;

            const wrapper = document.createElement('div');
            wrapper.className = 'custom-select-wrapper';

            const activeOption = select.options[select.selectedIndex];
            const trigger = document.createElement('div');
            trigger.className = 'custom-select-trigger';
            trigger.innerHTML = `<span>${activeOption ? activeOption.text : 'Pilih...'}</span> <i class="fa-solid fa-chevron-down"></i>`;

            const optionsPanel = document.createElement('div');
            optionsPanel.className = 'custom-select-options';

            Array.from(select.options).forEach(option => {
                const optDiv = document.createElement('div');
                optDiv.className = 'custom-option';
                optDiv.textContent = option.text;
                if (option.selected) optDiv.classList.add('selected');

                optDiv.addEventListener('click', (e) => {
                    e.stopPropagation();
                    select.value = option.value;
                    trigger.querySelector('span').textContent = option.text;
                    wrapper.classList.remove('open');

                    optionsPanel.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
                    optDiv.classList.add('selected');
                });
                optionsPanel.appendChild(optDiv);
            });

            wrapper.appendChild(trigger);
            wrapper.appendChild(optionsPanel);
            select.parentNode.insertBefore(wrapper, select.nextSibling);

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.custom-select-wrapper').forEach(w => {
                    if(w !== wrapper) w.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });
        });

        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-select-wrapper').forEach(w => w.classList.remove('open'));
        });

        // 2. MODAL OVERLAY ESC KEY & CLICK BG
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeReplyModal();
        });
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => {
                if(e.target === overlay) closeReplyModal();
            });
        });
    });

    // ================= FUNGSI MODAL REPLY ADUAN =================
    window.openReplyModal = function(id, item) {
        document.getElementById('replyForm').action = '/admin/aduan/' + id + '/reply';

        document.getElementById('aduanDetailBox').innerHTML =
            '<strong style="color: var(--text);">No Aduan:</strong> #' + item.complaint_number + '<br>' +
            '<strong style="color: var(--text);">Pelapor:</strong> ' + (item.mahasiswa ? item.mahasiswa.nama_lengkap : '-') + '<br>' +
            '<strong style="color: var(--text);">Judul:</strong> ' + item.title + '<br>' +
            '<strong style="color: var(--text);">Deskripsi:</strong> ' + item.description;

        // Set select status value
        const statusSelect = document.getElementById('rep_status');
        let targetValue = item.status === 'submitted' ? 'in_progress' : item.status;
        statusSelect.value = targetValue;

        // Sync Custom Select UI
        const wrapper = statusSelect.nextElementSibling;
        if(wrapper && wrapper.classList.contains('custom-select-wrapper')) {
            const triggerSpan = wrapper.querySelector('.custom-select-trigger span');
            const targetOption = Array.from(statusSelect.options).find(opt => opt.value === targetValue);
            if(triggerSpan && targetOption) triggerSpan.textContent = targetOption.text;

            wrapper.querySelectorAll('.custom-option').forEach(el => {
                el.classList.remove('selected');
                if(el.textContent === targetOption.text) el.classList.add('selected');
            });
        }

        document.getElementById('rep_message').value = '';
        document.getElementById('is_internal').checked = false;

        const m = document.getElementById('replyAduanModal');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }

    window.closeReplyModal = function() {
        const m = document.getElementById('replyAduanModal');
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);
    }
</script>
@endpush
