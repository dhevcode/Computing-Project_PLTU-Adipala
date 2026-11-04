@extends('peserta.layouts.app')

@section('title', 'Aduan Masalah')
@section('page_title', 'Aduan Masalah & Kendala')
@section('page_subtitle', 'Saluran resmi pelaporan kendala teknis, lingkungan kerja, K3, atau administrasi secara rahasia dan aman.')

@push('styles')
<style>
    /* ==========================================================================
       MODUL ADUAN MASALAH - TEMA CLEAN DASHBOARD & WHATSAPP STYLE READ MORE
       ========================================================================== */

    /* ANIMATIONS */
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-20px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* ================= HEADER DASHBOARD ================= */
    .dashboard-header {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 16px; margin-bottom: 24px;
        animation: fadeIn 0.4s ease-out;
    }
    .header-info h2 {
        font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px 0;
    }
    .header-info p {
        font-size: 13.5px; color: var(--muted); margin: 0; line-height: 1.4;
    }

    .btn-pill {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
        background: var(--primary); border: 1px solid var(--primary); border-radius: 8px;
        font-size: 13.5px; font-weight: 700; color: #fff; text-decoration: none; transition: all 0.2s ease; cursor: pointer;
    }
    .btn-pill:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); border-color: var(--primary-dark); }

    /* Action Buttons Mini */
    .btn-custom {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 40px; padding: 0 16px; border-radius: 8px; font-size: 13.5px; font-weight: 700;
        border: 1px solid transparent; cursor: pointer; transition: all 0.2s ease;
    }
    .btn-outline { background: #fff; color: var(--text); border-color: var(--border); }
    .btn-outline:hover { background: var(--page); }

    .btn-primary { background: var(--primary); color: #fff; border-color: var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); }

    /* ================= LAYOUT KARTU ADUAN ================= */
    .ad-list { display: flex; flex-direction: column; gap: 20px; }

    .ad-card {
        background: var(--bg); border: 1px solid var(--border); border-radius: 16px;
        padding: 24px; display: flex; flex-direction: column;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); color: var(--text);
        animation: fadeInUp 0.4s ease-out both; position: relative; overflow: hidden;
        transition: all 0.3s ease;
    }
    .ad-card:hover { border-color: var(--border); box-shadow: 0 6px 20px rgba(0,0,0,0.05); }

    /* Indikator Garis Samping Prioritas */
    .ad-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 5px; }
    .ad-card.priority-urgent::before { background: #EF4444; }
    .ad-card.priority-high::before { background: #F59E0B; }
    .ad-card.priority-medium::before { background: #3B82F6; }
    .ad-card.priority-low::before { background: #94A3B8; }

    .ad-card-head { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 16px; border-bottom: 1px dashed var(--page); padding-bottom: 16px; }
    .ad-meta-wrap { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px; }

    .ad-number { font-size: 13px; font-weight: 800; color: var(--primary); letter-spacing: 0.05em; margin-right: 4px; }
    .ad-card-title { font-size: 18px; font-weight: 800; color: var(--text); margin: 0; line-height: 1.4; }

    /* ==========================================================================
       WHATSAPP STYLE BACA SELENGKAPNYA (Untuk Aduan & Balasan)
       ========================================================================== */
    .ad-desc-wrapper, .reply-desc-wrapper { position: relative; }
    .ad-desc-wrapper { margin-bottom: 16px; }

    .ad-desc, .reply-desc {
        color: var(--text); margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 3; /* Maksimal 3 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 0.3s ease;
        word-wrap: break-word; overflow-wrap: break-word;
    }
    .ad-desc { font-size: 14px; line-height: 1.6; }
    .reply-desc { font-size: 13.5px; line-height: 1.5; }

    .ad-desc.expanded, .reply-desc.expanded { display: block; -webkit-line-clamp: unset; overflow: visible; }

    .btn-read-more {
        background: none; border: none; color: var(--primary);
        font-weight: 700; font-size: 13.5px; padding: 0; margin-top: 6px;
        cursor: pointer; display: none; align-items: center; gap: 4px;
        transition: color 0.2s;
    }
    .btn-read-more.show { display: inline-flex; }
    .btn-read-more:hover { color: var(--primary-dark); text-decoration: underline; }

    /* Badge Label Aduan */
    .badge-label {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px;
        font-size: 11px; font-weight: 700; border: 1px solid transparent; white-space: nowrap;
    }

    .badge-info { background: var(--page); color: var(--muted); border-color: var(--border); }
    .badge-priority-urgent { background: #FEF2F2; color: #DC2626; border-color: #FECACA; }
    .badge-priority-high { background: #FFFBEB; color: #D97706; border-color: #FDE68A; }
    .badge-priority-medium { background: #EFF6FF; color: #2563EB; border-color: #BFDBFE; }
    .badge-priority-low { background: #F8FAFC; color: #64748B; border-color: #CBD5E1; }

    .status-submitted { background: var(--primary-soft); color: var(--primary-dark); border-color: rgba(0,0,0,0.05); }
    .status-verified { background: #F5F3FF; color: #7C3AED; border-color: #DDD6FE; }
    .status-in_progress { background: #FEF9C3; color: #A16207; border-color: #FEF08A; }
    .status-waiting_for_participant { background: #FFF7ED; color: #C2410C; border-color: #FFEDD5; }
    .status-resolved, .status-closed { background: var(--success-bg); color: var(--success-text); border-color: #A7F3D0; }
    .status-rejected { background: #FEF2F2; color: #991B1B; border-color: #FECACA; }

    /* ================= TIMELINE TANGGAPAN ================= */
    .ad-reply-box {
        background: var(--page); border-radius: 12px; padding: 16px 20px;
        border: 1px solid var(--border); margin-top: 10px;
    }
    .ad-reply-head { font-size: 13.5px; font-weight: 700; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .ad-reply-head i { color: var(--primary); }

    .ad-reply-list { display: flex; flex-direction: column; gap: 12px; }
    .ad-reply-item { background: #fff; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--border); border-left: 4px solid var(--primary); }
    .ad-reply-item.is-me { border-left-color: #3B82F6; background: #F4F8FF; }

    .ad-reply-item-top { display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: var(--muted); margin-bottom: 8px; flex-wrap: wrap; gap: 10px; }
    .ad-reply-item-top strong { color: var(--text); font-weight: 700; font-size: 13px; }

    .ad-reply-empty { font-size: 13px; color: var(--muted); font-style: italic; text-align: center; padding: 10px 0; }

    .ad-reply-form { margin-top: 16px; display: flex; gap: 10px; align-items: flex-start; }
    .ad-reply-form .input-box { flex: 1; }

    .ad-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px dashed var(--border); font-size: 12px; font-weight: 600; color: var(--muted); flex-wrap: wrap; gap: 10px; }

    .ad-empty { text-align: center; padding: 60px 20px; color: var(--muted); background: var(--page); border-radius: 16px; border: 2px dashed var(--border); }
    .ad-empty i { font-size: 48px; margin-bottom: 16px; color: var(--muted); opacity: 0.5; }
    .ad-empty h3 { font-size: 18px; font-weight: 800; color: var(--text); margin: 0 0 8px 0; }
    .ad-empty p { font-size: 14px; margin: 0; }

    /* ================= FORM & MODAL ================= */
    .form-group { margin-bottom: 16px; }
    .form-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
    .input-box {
        width: 100%; padding: 12px 16px; font-family: inherit; font-size: 14px; color: var(--text);
        background: #fff; border: 1.5px solid var(--border); border-radius: 12px;
        transition: all 0.2s ease;
    }
    textarea.input-box { resize: vertical; min-height: 80px; }
    .input-box:focus { outline: none; border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px var(--primary-soft); }
    .input-box::placeholder { color: var(--muted); }
    .form-group small { color: var(--muted); font-size: 11.5px; margin-top: 6px; display: block; font-weight: 500; line-height: 1.4; }

    .modal-backdrop:not(.d-none) { animation: fadeIn 0.25s ease-out forwards; }
    .modal-backdrop:not(.d-none) .modal-box { animation: slideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    .pager-container { margin-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }

    @media (max-width: 768px) {
        .dashboard-header { flex-direction: column; align-items: flex-start; gap: 16px; }
        .btn-pill { width: 100%; justify-content: center; }
        .form-row { grid-template-columns: 1fr; gap: 0; }
        .ad-card-head { flex-direction: column; gap: 12px; }
        .ad-reply-form { flex-direction: column; align-items: stretch; }
        .ad-reply-form .btn-primary { width: 100%; }
    }
</style>
@endpush

@section('content')

<!-- ================= HEADER DASHBOARD ================= -->
<div class="dashboard-header">
    <div class="header-info">
        <h2>Aduan Masalah & Kendala</h2>
        <p>Laporkan kendala teknis, lingkungan kerja, K3, atau administrasi secara rahasia dan aman.</p>
    </div>
    <button type="button" class="btn-pill" onclick="openAduanModal()">
        <i class="fa-solid fa-plus"></i> Buat Aduan Baru
    </button>
</div>

<!-- ================= DAFTAR ADUAN ================= -->
<div class="ad-list">
    @forelse($complaints ?? [] as $c)
        <div class="ad-card priority-{{ $c->priority ?? 'low' }}">
            <div class="ad-card-head">
                <div>
                    <div class="ad-meta-wrap">
                        <span class="ad-number">#{{ $c->complaint_number ?? '0000' }}</span>
                        <span class="badge-label badge-info">{{ match($c->category ?? '') {
                            'technical' => 'Kendala Teknis/Sistem',
                            'work_environment' => 'Lingkungan Kerja',
                            'schedule' => 'Jadwal Kerja',
                            'supervision' => 'Proses Pembimbingan',
                            'administration' => 'Administrasi/Dokumen',
                            'safety' => 'Keselamatan Kerja (K3)',
                            'harassment' => 'Perilaku Tidak Pantas',
                            default => 'Lainnya',
                        } }}</span>

                        <span class="badge-label badge-priority-{{ $c->priority ?? 'low' }}">
                            <i class="fa-solid fa-flag"></i> Prioritas: {{ strtoupper($c->priority ?? 'LOW') }}
                        </span>
                    </div>
                    <h2 class="ad-card-title">{{ $c->title ?? 'Tidak Ada Judul' }}</h2>
                </div>

                <div>
                    <span class="badge-label status-{{ $c->status ?? 'submitted' }}">
                        {{ match($c->status ?? 'submitted') {
                            'submitted' => 'Aduan Diterima',
                            'verified' => 'Terverifikasi',
                            'in_progress' => 'Sedang Ditangani',
                            'waiting_for_participant' => 'Menunggu Respon Anda',
                            'resolved' => 'Selesai Ditangani',
                            'closed' => 'Aduan Ditutup',
                            'rejected' => 'Ditolak',
                            default => $c->status,
                        } }}
                    </span>
                </div>
            </div>

            <!-- TEKS DESKRIPSI (WHATSAPP STYLE READ-MORE) -->
            <div class="ad-desc-wrapper">
                <p class="ad-desc" id="desc-{{ $c->id_complaint }}">{{ $c->description ?? '-' }}</p>
                <button type="button" class="btn-read-more" id="btn-desc-{{ $c->id_complaint }}" onclick="toggleReadMore('desc', '{{ $c->id_complaint }}')">
                    Baca Selengkapnya...
                </button>
            </div>

            @if($c->attachment_path ?? false)
                <div style="margin-bottom: 20px;">
                    <a href="{{ asset(str_starts_with($c->attachment_path, 'uploads/') ? $c->attachment_path : 'uploads/aduan/' . $c->attachment_path) }}" target="_blank" style="font-size: 13px; color: var(--primary); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-paperclip"></i> Lihat Bukti Lampiran Aduan
                    </a>
                </div>
            @endif

            <!-- ================= TIMELINE TANGGAPAN ================= -->
            <div class="ad-reply-box">
                <div class="ad-reply-head">
                    <i class="fa-regular fa-comments"></i> Riwayat Tanggapan & Penanganan ({{ ($c->responses ?? collect())->where('is_internal_note', false)->count() }})
                </div>

                <div class="ad-reply-list">
                    @forelse(($c->responses ?? collect())->where('is_internal_note', false) as $resp)
                        <div class="ad-reply-item {{ $resp->user_id === Auth::id() ? 'is-me' : '' }}">
                            <div class="ad-reply-item-top">
                                <strong>{{ $resp->user->nama_lengkap ?? 'Petugas' }} ({{ $resp->user->role ?? 'Petugas' }})</strong>
                                <span>{{ $resp->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- TEKS BALASAN (WHATSAPP STYLE READ-MORE) -->
                            <div class="reply-desc-wrapper">
                                <p class="reply-desc" id="reply-{{ $resp->id_response }}">{{ $resp->message }}</p>
                                <button type="button" class="btn-read-more" id="btn-reply-{{ $resp->id_response }}" onclick="toggleReadMore('reply', '{{ $resp->id_response }}')">
                                    Baca Selengkapnya...
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="ad-reply-empty">
                            Belum ada tanggapan resmi dari pembimbing atau administrator.
                        </div>
                    @endforelse
                </div>

                @if(!in_array($c->status ?? '', ['closed', 'resolved']))
                    <!-- Form Balas Tanggapan -->
                    <form action="{{ route('peserta.aduan.reply', $c->id_complaint ?? 0) }}" method="POST" class="ad-reply-form">
                        @csrf
                        <input type="text" name="message" placeholder="Tulis balasan atau informasi tambahan..." required class="input-box" style="height: 42px; padding: 0 16px;">
                        <button type="submit" class="btn-custom btn-primary" style="white-space: nowrap; height: 42px;">
                            <i class="fa-solid fa-paper-plane"></i> Kirim
                        </button>
                    </form>
                @endif
            </div>

            <div class="ad-footer">
                <span>Akses Kerahasiaan: {{ ucwords(str_replace('_', ' ', $c->confidentiality ?? 'visible_to_both')) }}</span>
                <span>Diajukan: {{ $c->created_at ? $c->created_at->format('d/m/Y H:i') : '-' }} WIB</span>
            </div>
        </div>
    @empty
        <div class="ad-empty">
            <i class="fa-regular fa-comment-dots"></i>
            <h3>Belum Ada Aduan yang Diajukan</h3>
            <p>Jika Anda menghadapi kendala teknis atau masalah selama magang, jangan ragu untuk menyampaikan aduan.</p>
        </div>
    @endforelse
</div>

@if(($complaints ?? null) && $complaints->hasPages())
<div class="pager-container">
    {{ $complaints->links() }}
</div>
@endif

<!-- ================= MODAL BUAT ADUAN ================= -->
<div class="modal-backdrop d-none" id="aduanModal">
    <div class="modal-box" style="max-width: 680px; text-align: left; padding: 0; border-radius: 16px; background: #fff;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border);">
            <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: var(--text);"><i class="fa-regular fa-comment-dots" style="color: var(--primary);"></i> Buat Aduan Masalah Magang</h3>
            <button type="button" onclick="closeAduanModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); transition: 0.2s;">&times;</button>
        </div>

        <form action="{{ route('peserta.aduan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="padding: 24px; max-height: 70vh; overflow-y: auto;">
                <div class="form-row">
                    <div class="form-group">
                        <label for="ad_cat">Kategori Aduan *</label>
                        <select id="ad_cat" name="category" class="input-box" required>
                            <option value="technical">Kendala Perangkat / Sistem</option>
                            <option value="work_environment">Masalah Lingkungan Kerja</option>
                            <option value="schedule">Masalah Jadwal Kerja</option>
                            <option value="supervision">Masalah Proses Pembimbingan</option>
                            <option value="administration">Masalah Dokumen / Administrasi</option>
                            <option value="safety">Keselamatan Kerja (K3)</option>
                            <option value="harassment">Perilaku Tidak Pantas / Pelecehan</option>
                            <option value="other">Masalah Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ad_priority">Tingkat Prioritas *</label>
                        <select id="ad_priority" name="priority" class="input-box" required>
                            <option value="low">Rendah (Tidak mengganggu kegiatan)</option>
                            <option value="medium" selected>Sedang (Perlu penanganan, kegiatan jalan)</option>
                            <option value="high">Tinggi (Menghambat kegiatan magang)</option>
                            <option value="urgent">Mendesak (Darurat, Butuh respon cepat)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ad_title">Judul Aduan *</label>
                    <input type="text" id="ad_title" name="title" placeholder="Contoh: Akun jaringan lokal tidak dapat mengakses server PLC" required class="input-box">
                </div>

                <div class="form-group">
                    <label for="ad_desc">Deskripsi Masalah Lengkap *</label>
                    <textarea id="ad_desc" name="description" rows="4" class="input-box" placeholder="Jelaskan detail waktu kejadian, kronologi, dampak terhadap pekerjaan Anda..." required minlength="20"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ad_conf">Tingkat Kerahasiaan Akses *</label>
                        <select id="ad_conf" name="confidentiality" class="input-box" required>
                            <option value="visible_to_company">Hanya Pihak Perusahaan (PLTU)</option>
                            <option value="visible_to_campus">Hanya Pihak Kampus / Dosen</option>
                            <option value="visible_to_both" selected>Perusahaan & Kampus</option>
                            <option value="restricted">Akses Terbatas (Hanya Petugas Khusus)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ad_file">Lampiran Bukti (Foto / Dokumen)</label>
                        <input type="file" id="ad_file" name="attachment" accept=".pdf,.docx,.jpg,.jpeg,.png" class="input-box" style="padding: 9px 14px; background: #fff;">
                    </div>
                </div>
            </div>

            <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 10px; background: var(--page); border-radius: 0 0 16px 16px;">
                <button type="button" class="btn-custom btn-outline" onclick="closeAduanModal()">Batal</button>
                <button type="submit" class="btn-custom btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Aduan Resmi
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openAduanModal() {
        const m = document.getElementById('aduanModal');
        m.classList.remove('d-none');
    }
    function closeAduanModal() {
        const m = document.getElementById('aduanModal');
        m.classList.add('d-none');
    }

    // ================= FUNGSI BACA SELENGKAPNYA (WHATSAPP STYLE) =================
    document.addEventListener("DOMContentLoaded", function() {
        // Cek Teks Aduan Utama
        document.querySelectorAll('.ad-desc').forEach(function(descEl) {
            if (descEl.scrollHeight > descEl.clientHeight) {
                const id = descEl.id.replace('desc-', '');
                const btn = document.getElementById('btn-desc-' + id);
                if (btn) btn.classList.add('show');
            }
        });

        // Cek Teks Balasan (Tanggapan)
        document.querySelectorAll('.reply-desc').forEach(function(descEl) {
            if (descEl.scrollHeight > descEl.clientHeight) {
                const id = descEl.id.replace('reply-', '');
                const btn = document.getElementById('btn-reply-' + id);
                if (btn) btn.classList.add('show');
            }
        });
    });

    // Fungsi Toggle untuk Deskripsi Utama & Balasan
    function toggleReadMore(type, id) {
        let descEl, btnEl;

        if (type === 'desc') {
            descEl = document.getElementById('desc-' + id);
            btnEl = document.getElementById('btn-desc-' + id);
        } else if (type === 'reply') {
            descEl = document.getElementById('reply-' + id);
            btnEl = document.getElementById('btn-reply-' + id);
        }

        if (descEl.classList.contains('expanded')) {
            // Collapse
            descEl.classList.remove('expanded');
            btnEl.innerHTML = 'Baca Selengkapnya...';
        } else {
            // Expand
            descEl.classList.add('expanded');
            btnEl.innerHTML = 'Lebih Sedikit';
        }
    }
</script>
@endpush
