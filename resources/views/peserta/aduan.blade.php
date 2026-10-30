@extends('peserta.layout')

@section('title', 'Aduan Masalah')

@section('content')

<style>
    /* ==========================================================================
       ADUAN MASALAH - PANEL KACA & KARTU (TEMA DASHBOARD & ABSENSI)
       ========================================================================== */
    .ad-page {
        box-sizing: border-box; width: 100%; max-width: 1200px;
        margin-left: auto; margin-right: auto; color: var(--text);
    }
    .ad-page *, .ad-page *::before, .ad-page *::after { box-sizing: border-box; }

    /* ---------- Panel Utama ---------- */
    .ad-panel {
        background: var(--panel);
        backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 40px; padding: 30px;
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.22);
    }
    .ad-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 18px 24px; margin-bottom: 26px; }
    .ad-top-text { flex: 1 1 360px; min-width: 0; }
    .ad-title { margin: 0; font-size: clamp(24px, 3vw, 36px); font-weight: 700; letter-spacing: -0.025em; line-height: 1.15; color: #ffffff; }
    .ad-sub { margin: 10px 0 0; font-size: 13.5px; line-height: 1.6; color: var(--muted); font-weight: 500; }
    .ad-controls { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }

    /* ---------- Tombol Dasar ---------- */
    .ad-page .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 40px; padding: 0 20px; border-radius: 9999px; font-family: inherit; font-size: 13.5px; font-weight: 600; line-height: 1;
        border: 1px solid transparent; cursor: pointer; text-decoration: none; white-space: nowrap;
        transition: background .2s ease, border-color .2s ease, transform .2s ease, color .2s ease;
    }
    .ad-page .btn:hover { transform: translateY(-1px); }
    .ad-page .btn:focus-visible, .ad-page .modal-close:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
    .ad-page .btn-sm { height: 32px; padding: 0 14px; font-size: 12px; }
    .ad-page .btn-primary { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }
    .ad-page .btn-primary:hover { background: #d6f55a; border-color: #d6f55a; }
    .ad-page .btn-outline { background: transparent; color: var(--text); border-color: var(--border-strong); }
    .ad-page .btn-outline:hover { background: var(--card-2-hover); color: #ffffff; }

    /* ---------- Kartu Aduan ---------- */
    .ad-list { display: flex; flex-direction: column; gap: 18px; }
    .ad-card {
        background: var(--card); backdrop-filter: blur(16px) saturate(130%); -webkit-backdrop-filter: blur(16px) saturate(130%);
        border: 1px solid var(--border); border-radius: 24px; padding: 22px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05); display: flex; flex-direction: column;
    }
    .ad-card-head { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px; margin-bottom: 14px; }

    .ad-meta-wrap { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px; }
    .ad-number { font-size: 12px; font-weight: 800; color: var(--accent); font-family: var(--font-num); letter-spacing: 0.05em; }
    .ad-card-title { font-size: 17px; font-weight: 700; color: #ffffff; margin: 0; line-height: 1.3; }
    .ad-desc { font-size: 13.5px; color: var(--text-2); line-height: 1.6; margin-bottom: 16px; }

    /* ---------- Badge ---------- */
    .ad-badge {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 9999px;
        font-size: 11px; font-weight: 700; white-space: nowrap; border: 1px solid transparent;
    }
    .badge-info { background: var(--info-bg); color: var(--info); border-color: var(--info-bd); }
    .badge-danger { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-bd); }
    .badge-warning { background: var(--warn-bg); color: var(--warn); border-color: var(--warn-bd); }
    .badge-secondary { background: var(--card-2); color: var(--text-2); border-color: var(--border-strong); }
    .badge-success, .badge-resolved { background: var(--ok-bg); color: var(--ok); border-color: var(--ok-bd); }

    /* Mapping status dari DB ke CSS khusus */
    .status-submitted { background: var(--info-bg); color: var(--info); border-color: var(--info-bd); }
    .status-verified { background: var(--violet-bg); color: var(--violet); border-color: var(--violet-bd); }
    .status-in_progress { background: var(--warn-bg); color: var(--warn); border-color: var(--warn-bd); }
    .status-waiting_for_participant { background: rgba(255,140,0,0.15); color: #ff9838; border-color: rgba(255,140,0,0.35); }
    .status-resolved, .status-closed { background: var(--ok-bg); color: var(--ok); border-color: var(--ok-bd); }
    .status-rejected { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-bd); }

    /* ---------- Timeline Tanggapan ---------- */
    .ad-reply-box {
        background: rgba(255, 255, 255, 0.04); border-radius: 16px; padding: 16px;
        border: 1px solid var(--border-strong); margin-top: 10px;
    }
    .ad-reply-head { font-size: 12.5px; font-weight: 700; color: #ffffff; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .ad-reply-head i { color: var(--accent); }

    .ad-reply-list { display: flex; flex-direction: column; gap: 12px; }
    .ad-reply-item {
        background: var(--card-2); padding: 12px 16px; border-radius: 12px;
        border-left: 3px solid var(--accent);
    }
    .ad-reply-item.is-me { border-left-color: var(--info); background: rgba(90, 150, 255, 0.06); }
    .ad-reply-item-top { display: flex; justify-content: space-between; font-size: 11.5px; color: var(--muted); margin-bottom: 6px; }
    .ad-reply-item-top strong { color: #ffffff; }
    .ad-reply-item-msg { font-size: 13px; color: var(--text-2); line-height: 1.5; }
    .ad-reply-empty { font-size: 12.5px; color: var(--muted); font-style: italic; }

    .ad-reply-form { margin-top: 16px; display: flex; gap: 10px; align-items: center; }
    .ad-reply-form input { flex: 1; }

    /* ---------- Footer Aduan ---------- */
    .ad-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--border); font-size: 11.5px; color: var(--muted); }

    .ad-empty { text-align: center; padding: 48px 20px; color: var(--muted); background: var(--card-2); border-radius: 24px; border: 1px dashed var(--border-strong); }
    .ad-empty i { font-size: 40px; margin-bottom: 16px; opacity: 0.5; color: var(--accent); }
    .ad-empty h3 { font-size: 16px; color: #ffffff; margin: 0 0 6px 0; }
    .ad-empty p { font-size: 13px; margin: 0; }

    /* ---------- Form & Modal ---------- */
    .ad-page .form-group { margin-bottom: 16px; }
    .ad-page .form-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .ad-page .form-group > label { display: block; margin-bottom: 8px; font-size: 12.5px; font-weight: 600; color: var(--text-2); }
    .ad-page .form-control, .ad-page .form-select {
        width: 100%; padding: 11px 16px; font-family: inherit; font-size: 13.5px; color: var(--text);
        background: rgba(255, 255, 255, 0.07); border: 1px solid var(--border-strong); border-radius: 16px; color-scheme: dark;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .ad-page textarea.form-control { resize: vertical; line-height: 1.5; }
    .ad-page .form-control::placeholder { color: var(--muted); }
    .ad-page .form-control:focus, .ad-page .form-select:focus { outline: none; border-color: var(--accent); background: rgba(255, 255, 255, 0.10); box-shadow: 0 0 0 3px var(--accent-soft); }
    .ad-page .form-select option { background: #1f2121; color: #f6f6f4; }
    .ad-page input[type="file"].form-control { padding: 9px 14px; }
    .ad-page input[type="file"]::file-selector-button {
        margin-right: 12px; padding: 6px 14px; border-radius: 9999px; border: 1px solid var(--border-strong);
        background: var(--card-2-hover); color: var(--text); font-family: inherit; font-size: 12px; font-weight: 600; cursor: pointer;
    }

    .ad-page .modal-overlay {
        position: fixed; inset: 0; z-index: 2000; display: none; align-items: center; justify-content: center; padding: 20px;
        background: rgba(0, 0, 0, 0.58); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    }
    .ad-page .modal-overlay.active { display: flex; }
    .ad-page .modal-card {
        width: 100%; max-width: 680px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden;
        background: rgba(34, 36, 36, 0.94); backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 32px; color: var(--text);
        box-shadow: 0 40px 90px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.18);
    }
    .ad-page .modal-card form { display: flex; flex-direction: column; min-height: 0; flex: 1; }
    .ad-page .modal-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 20px 24px; border-bottom: 1px solid var(--border); }
    .ad-page .modal-header h3 { margin: 0; font-size: 16.5px; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 10px; }
    .ad-page .modal-header h3 i { color: var(--accent); }
    .ad-page .modal-close {
        width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0; cursor: pointer; font-size: 20px; line-height: 1; color: var(--text);
        display: inline-flex; align-items: center; justify-content: center; background: var(--card-2); border: 1px solid var(--border-strong); transition: background .2s;
    }
    .ad-page .modal-close:hover { background: var(--card-2-hover); }
    .ad-page .modal-body { padding: 22px 24px; overflow-y: auto; }
    .ad-page .modal-footer { display: flex; justify-content: flex-end; align-items: center; flex-wrap: wrap; gap: 10px; padding: 18px 24px; border-top: 1px solid var(--border); background: rgba(0,0,0,0.1); }

    /* ---------- Pagination ---------- */
    .ad-pager { margin-top: 24px; }
    .ad-pager nav[role="navigation"] > div:first-child { display: none; }
    .ad-pager nav[role="navigation"] svg { width: 16px; height: 16px; }
    .ad-pager nav[role="navigation"] p { font-size: 12px; color: var(--muted); margin: 0; }
    .ad-pager .pagination { display: flex; flex-wrap: wrap; gap: 6px; list-style: none; padding: 0; margin: 0; }
    .ad-pager a, .ad-pager .page-link, .ad-pager span[aria-current] > span, .ad-pager span[aria-disabled] > span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 12px;
        border-radius: 9999px; font-size: 12.5px; font-weight: 600; text-decoration: none;
        color: var(--text-2); background: var(--card-2); border: 1px solid var(--border);
    }
    .ad-pager .page-item.active .page-link, .ad-pager span[aria-current] > span { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }

    @media (max-width: 768px) {
        .ad-panel { padding: 18px; border-radius: 30px; }
        .ad-card { padding: 18px; border-radius: 20px; }
        .ad-page .form-row { grid-template-columns: 1fr; gap: 0; }
        .ad-page .modal-card { border-radius: 26px; }
        .ad-page .modal-header, .ad-page .modal-body, .ad-page .modal-footer { padding-left: 18px; padding-right: 18px; }
    }
</style>

<div class="ad-page">
<div class="ad-panel">

    <!-- ================= KEPALA PANEL ================= -->
    <header class="ad-top">
        <div class="ad-top-text">
            <h1 class="ad-title">Aduan Masalah & Kendala</h1>
            <p class="ad-sub">Saluran resmi bagi peserta untuk melaporkan kendala teknis, lingkungan kerja, K3, atau administrasi secara rahasia dan aman.</p>
        </div>
        <div class="ad-controls">
            <button type="button" class="btn btn-primary" onclick="openAduanModal()">
                <i class="fa-solid fa-plus"></i> Buat Aduan Baru
            </button>
        </div>
    </header>

    <!-- ================= DAFTAR ADUAN ================= -->
    <div class="ad-list">
        @forelse($complaints as $c)
            <div class="ad-card">
                <div class="ad-card-head">
                    <div>
                        <div class="ad-meta-wrap">
                            <span class="ad-number">#{{ $c->complaint_number }}</span>
                            <span class="ad-badge badge-info">{{ match($c->category) {
                                'technical' => 'Kendala Teknis/Sistem',
                                'work_environment' => 'Lingkungan Kerja',
                                'schedule' => 'Jadwal Kerja',
                                'supervision' => 'Proses Pembimbingan',
                                'administration' => 'Administrasi/Dokumen',
                                'safety' => 'Keselamatan Kerja (K3)',
                                'harassment' => 'Perilaku Tidak Pantas',
                                default => 'Lainnya',
                            } }}</span>

                            <span class="ad-badge {{ $c->priority === 'urgent' ? 'badge-danger' : ($c->priority === 'high' ? 'badge-warning' : 'badge-secondary') }}">
                                <i class="fa-solid fa-flag"></i> Prioritas: {{ strtoupper($c->priority) }}
                            </span>
                        </div>
                        <h2 class="ad-card-title">{{ $c->title }}</h2>
                    </div>

                    <div>
                        <span class="ad-badge status-{{ $c->status }}">
                            {{ match($c->status) {
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

                <p class="ad-desc">{{ $c->description }}</p>

                @if($c->attachment_path)
                    <div style="margin-bottom: 16px;">
                        <a href="{{ asset($c->attachment_path) }}" target="_blank" style="font-size: 12.5px; color: var(--accent); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-paperclip"></i> Lihat Bukti Lampiran Aduan
                        </a>
                    </div>
                @endif

                <!-- ================= TIMELINE TANGGAPAN ================= -->
                <div class="ad-reply-box">
                    <div class="ad-reply-head">
                        <i class="fa-regular fa-comments"></i> Riwayat Tanggapan & Penanganan ({{ $c->responses->where('is_internal_note', false)->count() }})
                    </div>

                    <div class="ad-reply-list">
                        @forelse($c->responses->where('is_internal_note', false) as $resp)
                            <div class="ad-reply-item {{ $resp->user_id === Auth::id() ? 'is-me' : '' }}">
                                <div class="ad-reply-item-top">
                                    <strong>{{ $resp->user->nama_lengkap ?? 'Petugas' }} ({{ $resp->user->role ?? 'Petugas' }})</strong>
                                    <span>{{ $resp->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="ad-reply-item-msg">
                                    {{ $resp->message }}
                                </div>
                            </div>
                        @empty
                            <div class="ad-reply-empty">
                                Belum ada tanggapan resmi dari pembimbing atau administrator.
                            </div>
                        @endforelse
                    </div>

                    @if(!in_array($c->status, ['closed', 'resolved']))
                        <!-- Form Balas Tanggapan -->
                        <form action="{{ route('peserta.aduan.reply', $c->id_complaint) }}" method="POST" class="ad-reply-form">
                            @csrf
                            <input type="text" name="message" placeholder="Tulis balasan atau informasi tambahan untuk petugas..." required class="form-control">
                            <button type="submit" class="btn btn-primary" style="white-space: nowrap;">
                                <i class="fa-solid fa-paper-plane"></i> Kirim
                            </button>
                        </form>
                    @endif
                </div>

                <div class="ad-footer">
                    <span>Akses Kerahasiaan: {{ ucwords(str_replace('_', ' ', $c->confidentiality)) }}</span>
                    <span>Diajukan: {{ $c->created_at->format('d/m/Y H:i') }} WIB</span>
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

    <div class="ad-pager">
        {{ $complaints->links() }}
    </div>

</div>

    <!-- ================= MODAL BUAT ADUAN ================= -->
    <div class="modal-overlay" id="aduanModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fa-regular fa-comment-dots"></i> Buat Aduan Masalah Magang</h3>
                <button type="button" class="modal-close" onclick="closeAduanModal()">&times;</button>
            </div>
            <form action="{{ route('peserta.aduan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ad_cat">Kategori Aduan *</label>
                            <select id="ad_cat" name="category" class="form-select" required>
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
                            <select id="ad_priority" name="priority" class="form-select" required>
                                <option value="low">Rendah (Tidak mengganggu kegiatan)</option>
                                <option value="medium" selected>Sedang (Perlu penanganan, kegiatan jalan)</option>
                                <option value="high">Tinggi (Menghambat kegiatan magang)</option>
                                <option value="urgent">Mendesak (Butuh perhatian segera / Darurat)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ad_title">Judul Aduan *</label>
                        <input type="text" id="ad_title" name="title" placeholder="Contoh: Akun jaringan lokal tidak dapat mengakses server PLC" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="ad_desc">Deskripsi Masalah Lengkap *</label>
                        <textarea id="ad_desc" name="description" rows="4" class="form-control" placeholder="Jelaskan detail waktu kejadian, kronologi, dampak terhadap pekerjaan Anda..." required minlength="20"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ad_conf">Tingkat Kerahasiaan Akses *</label>
                            <select id="ad_conf" name="confidentiality" class="form-select" required>
                                <option value="visible_to_company">Hanya Pihak Perusahaan (PLTU)</option>
                                <option value="visible_to_campus">Hanya Pihak Kampus / Dosen</option>
                                <option value="visible_to_both" selected>Perusahaan & Kampus</option>
                                <option value="restricted">Akses Terbatas (Hanya Petugas Khusus)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ad_file">Lampiran Bukti (Screenshot / Foto / Dokumen)</label>
                            <input type="file" id="ad_file" name="attachment" accept=".pdf,.docx,.jpg,.jpeg,.png" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeAduanModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Aduan Resmi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function openAduanModal() {
        document.getElementById('aduanModal').classList.add('active');
    }
    function closeAduanModal() {
        document.getElementById('aduanModal').classList.remove('active');
    }
</script>
@endpush
