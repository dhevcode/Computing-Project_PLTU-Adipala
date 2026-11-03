@extends('admin.layouts.app')

@section('title', 'Chatbot FAQ & Log Riwayat')
@section('page_title', 'Kelola Chatbot FAQ & Riwayat Interaksi')
@section('page_subtitle', 'Kelola daftar pertanyaan otomatis (CRUD) dan pantau log riwayat interaksi pengguna')

@push('styles')
<style>
    /* ================= STATS GRID ================= */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
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

    .stat-info { flex: 1; text-align: left; }
    .stat-info .stat-label { font-size: 13px; font-weight: 600; color: var(--muted); display: block; margin-bottom: 4px; }
    .stat-info .stat-value { font-size: 32px; font-weight: 800; color: var(--text); margin: 0 0 2px 0; line-height: 1; }
    .stat-info .stat-desc { font-size: 11.5px; color: var(--muted); display: block; margin-top: 6px; }

    /* ================= TOP FAQ CARD ================= */
    .top-faq-card { background: var(--bg); border-radius: 16px; border: 1px solid var(--border); padding: 20px 24px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); }
    .top-faq-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 10px; }
    .top-faq-header h4 { margin: 0; font-size: 14px; color: var(--primary-dark); display: flex; align-items: center; gap: 8px; font-weight: 700; }
    .top-faq-list { display: flex; flex-wrap: wrap; gap: 10px; }
    .top-faq-item { background: var(--page); border: 1px solid var(--border); border-radius: 10px; padding: 8px 14px; font-size: 12px; color: var(--text); display: flex; align-items: center; gap: 8px; transition: 0.2s; }
    .top-faq-item:hover { border-color: var(--primary); background: var(--primary-soft); }
    .top-faq-item .count-pill { background: var(--primary-dark); color: #fff; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; }

    /* ================= TABS ================= */
    .tab-nav { display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 2px solid var(--page); padding-bottom: 0; flex-wrap: wrap; }
    .tab-btn { background: none; border: none; padding: 12px 20px; font-size: 13.5px; font-weight: 600; color: var(--muted); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; border-bottom: 3px solid transparent; margin-bottom: -2px; text-decoration: none; transition: all 0.2s; font-family: inherit; }
    .tab-btn:hover { color: var(--primary-dark); }
    .tab-btn.active { color: var(--primary-dark); border-bottom-color: var(--primary); background: var(--primary-soft); border-top-left-radius: 8px; border-top-right-radius: 8px; }

    /* ================= CARDS & TABLE ================= */
    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; padding: 20px 24px; }
    .card-header { margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--page); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; }
    .card-title-group h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0 0 4px 0; display: flex; align-items: center; gap: 10px; }
    .card-title-group p { font-size: 12px; color: var(--muted); margin: 0; }

    .badge { padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; white-space: nowrap; border: 1px solid var(--border); display: inline-flex; align-items: center; gap: 5px; }
    .badge-success { background: var(--success-bg); color: var(--success-text); border-color: #A7F3D0; }
    .badge-danger { background: #FEF2F2; color: #DC2626; border-color: #FECACA; }
    .badge-info { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }
    .badge-warning { background: #FFFBEB; color: #D97706; border-color: #FDE68A; }

    .category-badge { background: var(--page); color: var(--muted); padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 600; border: 1px solid var(--border); }

    .btn-sm { padding: 8px 14px; font-size: 12.5px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; transition: 0.2s; white-space: nowrap; }
    .btn-outline { background: #F8FAFC; color: #475569; border: 1px solid var(--border); }
    .btn-outline:hover { background: #E2E8F0; color: var(--text); }
    .btn-primary { background: var(--primary); color: #fff; border: 1.5px solid var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
    .btn-action { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; border: none; cursor: pointer; transition: 0.2s; font-size: 14px; }
    .btn-action.edit { background: #F1F5F9; color: var(--primary); border: 1px solid var(--border); }
    .btn-action.edit:hover { background: var(--primary); color: #fff; border-color: var(--primary); }
    .btn-action.delete { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
    .btn-action.delete:hover { background: #DC2626; color: #fff; }

    /* ================= FILTER GRID ================= */
    .filter-grid { display: grid; grid-template-columns: 2fr 1.5fr 1fr auto; gap: 12px; align-items: end; background: var(--page); padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; border: 1px solid var(--border); }
    .form-group { margin-bottom: 0; }
    .input-box { width: 100%; padding: 10px 14px; font-family: inherit; font-size: 13px; color: var(--text); background: #fff; border: 1.5px solid var(--border); border-radius: 8px; outline: none; transition: 0.2s; box-sizing: border-box; }
    .input-box:focus { border-color: var(--primary); }

    /* ================= MODAL ================= */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 20px; opacity: 0; transition: opacity 0.2s; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .custom-modal { background: #fff; width: 100%; max-width: 650px; max-height: 90vh; overflow-y: auto; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: scale(0.95) translateY(20px); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .modal-overlay.active .custom-modal { transform: scale(1) translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F9FAFB; border-radius: 16px 16px 0 0; }
    .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0; display: flex; align-items: center; gap: 8px;}
    .modal-close { background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer; padding: 4px 8px; border-radius: 6px; transition: 0.2s; }
    .modal-close:hover { background: #FEE2E2; color: var(--error-text); }
    .modal-body { padding: 24px; }

    .modal-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .modal-form-group { margin-bottom: 16px; }
    .modal-form-group label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text); margin-bottom: 8px; }

    .role-selector { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 6px; }
    .role-option { border: 1.5px solid var(--border); border-radius: 10px; padding: 12px; cursor: pointer; display: flex; flex-direction: column; gap: 6px; transition: all 0.2s; background: var(--page); }
    .role-option:hover { border-color: var(--primary); background: var(--primary-soft); }
    .role-option input[type="radio"] { margin-bottom: 4px; accent-color: var(--primary); width: 16px; height: 16px; }
    .role-option strong { font-size: 12.5px; color: var(--text); }
    .role-option small { font-size: 11px; color: var(--muted); line-height: 1.4; }

    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #fafbfc; border-radius: 0 0 16px 16px; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
        .filter-grid .btn-sm { margin-top: 24px; }
    }
    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .stat-card { padding: 16px 20px; }
        .top-faq-header { flex-direction: column; align-items: flex-start; }
        .filter-grid { grid-template-columns: 1fr; }
        .filter-grid .btn-sm { margin-top: 8px; }

        .modal-form-row { grid-template-columns: 1fr; }
        .role-selector { grid-template-columns: 1fr; }

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

        /* Solusi Kolom Teks Panjang di Mobile */
        tbody td.cell-longtext { flex-direction: column; text-align: left; }
        tbody td.cell-longtext::before { margin-bottom: 8px; }

        tbody td.cell-action { display: grid; grid-template-columns: 1fr; gap: 8px; padding-top: 16px; margin-top: 8px; }
        tbody td.cell-action::before { display: none; }
        tbody td.cell-action > div { width: 100%; display: grid !important; grid-template-columns: 1fr 1fr; gap: 8px; }
        tbody td.cell-action .btn-action { width: 100%; justify-content: center; }

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

    @if(session('error'))
        <div class="alert alert-error animate-fade-up" style="background: #FEF2F2; color: #991B1B; border-color: #FECACA;">
            <i class="fa-solid fa-circle-xmark"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="alert alert-error animate-fade-up" style="background: #FEF2F2; color: #991B1B; border-color: #FECACA;">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                <strong>Terdapat kesalahan:</strong>
                <ul style="margin: 4px 0 0 16px; font-size: 12px;">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- STATS GRID -->
    <div class="stats-grid animate-fade-up delay-1">
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total FAQ (Bot)</span>
                <h3 class="stat-value">{{ $totalFaq }}</h3>
                <span class="stat-desc">{{ $totalFaqAktif }} FAQ status aktif</span>
            </div>
            <div class="stat-icon" style="background: #E0F2FE; color: #0284C7;"><i class="fa-solid fa-circle-question"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total Interaksi</span>
                <h3 class="stat-value">{{ $totalInteraksi }}</h3>
                <span class="stat-desc">Pertanyaan diklik pengguna</span>
            </div>
            <div class="stat-icon" style="background: #ECFDF5; color: #059669;"><i class="fa-solid fa-comments"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Interaksi Tamu (Guest)</span>
                <h3 class="stat-value">{{ $interaksiGuest }}</h3>
                <span class="stat-desc">Pengunjung publik / non-login</span>
            </div>
            <div class="stat-icon" style="background: #FFFBEB; color: #D97706;"><i class="fa-solid fa-users"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Interaksi Mahasiswa</span>
                <h3 class="stat-value">{{ $interaksiStudent }}</h3>
                <span class="stat-desc">Peserta magang terdaftar</span>
            </div>
            <div class="stat-icon" style="background: #F3E8FF; color: #9333EA;"><i class="fa-solid fa-user-graduate"></i></div>
        </div>
    </div>

    <!-- FAQ TERPOPULER -->
    @if($topFaqs->count() > 0)
    <div class="top-faq-card animate-fade-up delay-2">
        <div class="top-faq-header">
            <h4><i class="fa-solid fa-fire" style="color:#EF4444;"></i> Top 5 FAQ Paling Sering Ditanyakan Pengguna</h4>
            <small>Berdasarkan frekuensi klik realtime</small>
        </div>
        <div class="top-faq-list">
            @foreach($topFaqs as $idx => $tf)
            <div class="top-faq-item">
                <span style="font-weight:700; color:var(--primary-dark);">#{{ $idx + 1 }}</span>
                <span><strong>[{{ $tf->kode }}]</strong> {{ Str::limit($tf->pertanyaan, 48) }}</span>
                <span class="count-pill">{{ $tf->click_count }}x klik</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- TAB NAVIGASI -->
    <div class="tab-nav animate-fade-up delay-2">
        <a href="{{ route('admin.chatbot.index', ['tab' => 'faq']) }}" class="tab-btn {{ $tab === 'faq' ? 'active' : '' }}">
            <i class="fa-solid fa-list-check"></i> Daftar FAQ Chatbot
        </a>
        <a href="{{ route('admin.chatbot.index', ['tab' => 'logs']) }}" class="tab-btn {{ $tab === 'logs' ? 'active' : '' }}">
            <i class="fa-solid fa-clock-rotate-left"></i> Log Riwayat Interaksi ({{ $totalInteraksi }})
        </a>
    </div>

    @if($tab === 'faq')
    <!-- ================= TAB 1: KELOLA DAFTAR FAQ (CRUD) ================= -->
    <div class="card animate-fade-up delay-3">
        <div class="card-header">
            <div class="card-title-group">
                <h3><i class="fa-solid fa-robot" style="color: var(--primary);"></i> Daftar Pertanyaan FAQ</h3>
                <p>Pertanyaan berbasis menu yang ditampilkan pada quick reply chatbot.</p>
            </div>
            <button type="button" class="btn-sm btn-primary" onclick="openAddModal()">
                <i class="fa-solid fa-plus"></i> Tambah FAQ Baru
            </button>
        </div>

        <form action="{{ route('admin.chatbot.index') }}" method="GET" class="filter-grid">
            <input type="hidden" name="tab" value="faq">

            <div class="form-group">
                <label>Pencarian</label>
                <input type="text" name="search_faq" value="{{ request('search_faq') }}" placeholder="Cari pertanyaan, kode..." class="input-box">
            </div>

            <div class="form-group">
                <label>Hak Akses Role</label>
                <select name="role_access" class="d-none custom-select">
                    <option value="all_filter">Semua Hak Akses Role</option>
                    <option value="all" {{ request('role_access') === 'all' ? 'selected' : '' }}>Guest & Student (Umum)</option>
                    <option value="guest" {{ request('role_access') === 'guest' ? 'selected' : '' }}>Hanya Guest (Publik)</option>
                    <option value="student" {{ request('role_access') === 'student' ? 'selected' : '' }}>Hanya Student (Peserta)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status FAQ</label>
                <select name="status_filter" class="d-none custom-select">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status_filter') === 'active' ? 'selected' : '' }}>Aktif Saja</option>
                    <option value="inactive" {{ request('status_filter') === 'inactive' ? 'selected' : '' }}>Nonaktif Saja</option>
                </select>
            </div>

            <div class="form-group" style="display: flex; gap: 8px;">
                <button type="submit" class="btn-sm btn-primary" style="flex: 1; height: 42px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search_faq', 'role_access', 'status_filter']))
                    <a href="{{ route('admin.chatbot.index', ['tab' => 'faq']) }}" class="btn-sm btn-outline" style="height: 42px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">Urut</th>
                        <th>Kode</th>
                        <th>Pertanyaan & Jawaban</th>
                        <th>Kategori</th>
                        <th>Role Akses</th>
                        <th style="text-align: center;">Klik</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $f)
                    <tr>
                        <td data-label="No. Urut" style="font-weight: 600; color:var(--muted);">{{ $f->urutan }}</td>
                        <td data-label="Kode"><strong style="color: var(--primary-dark);">{{ $f->kode }}</strong></td>

                        <td class="cell-longtext" data-label="Pertanyaan & Jawaban">
                            <div style="font-weight: 600; color: var(--text); margin-bottom: 6px; font-size: 13px;">{{ $f->pertanyaan }}</div>
                            <div style="font-size: 12px; color: var(--muted); line-height: 1.45;">{{ Str::limit($f->jawaban, 130) }}</div>
                        </td>

                        <td data-label="Kategori"><span class="category-badge">{{ $f->kategori }}</span></td>

                        <td data-label="Role Akses">
                            @if($f->role_access === 'all')
                                <span class="badge badge-info"><i class="fa-solid fa-users"></i> Guest & Student</span>
                            @elseif($f->role_access === 'guest')
                                <span class="badge badge-warning"><i class="fa-solid fa-user-shield"></i> Hanya Guest</span>
                            @else
                                <span class="badge badge-success"><i class="fa-solid fa-graduation-cap"></i> Hanya Student</span>
                            @endif
                        </td>

                        <td data-label="Jumlah Klik" style="text-align: center;">
                            <span style="background:var(--page); font-weight:700; color:var(--primary); padding:4px 10px; border-radius:999px; font-size:11.5px; border: 1px solid var(--border);">
                                {{ $f->click_count }}x
                            </span>
                        </td>

                        <td data-label="Status" style="text-align: center;">
                            <form action="{{ route('admin.chatbot.toggle', $f->id_faq) }}" method="POST" style="margin:0;">
                                @csrf
                                @if($f->is_active)
                                    <button type="submit" class="badge badge-success" style="cursor:pointer;" title="Klik untuk nonaktifkan"><i class="fa-solid fa-check"></i> Aktif</button>
                                @else
                                    <button type="submit" class="badge badge-danger" style="cursor:pointer;" title="Klik untuk aktifkan"><i class="fa-solid fa-ban"></i> Nonaktif</button>
                                @endif
                            </form>
                        </td>

                        <td class="cell-action" style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <button type="button" class="btn-action edit" onclick="openEditModal({{ json_encode($f) }})"><i class="fa-regular fa-pen-to-square"></i></button>
                                <form action="{{ route('admin.chatbot.destroy', $f->id_faq) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pertanyaan {{ $f->kode }} ini?')" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action delete"><i class="fa-regular fa-trash-can"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fa-solid fa-robot"></i>
                                <p>Belum ada data FAQ yang cocok dengan filter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($faqs->hasPages())
        <div class="pagination-wrapper" style="margin-top: 20px;">
            {{ $faqs->links() }}
        </div>
        @endif
    </div>

    @else
    <!-- ================= TAB 2: LOG RIWAYAT PERCAKAPAN ================= -->
    <div class="card animate-fade-up delay-3">
        <div class="card-header">
            <div class="card-title-group">
                <h3><i class="fa-solid fa-clock-rotate-left" style="color: var(--primary);"></i> Log Riwayat Percakapan</h3>
                <p>Memantau pertanyaan yang sering diklik oleh Guest dan Peserta.</p>
            </div>
            <form action="{{ route('admin.chatbot.clear-logs') }}" method="POST" onsubmit="return confirm('Yakin ingin membersihkan seluruh riwayat percakapan chatbot?')">
                @csrf
                <button type="submit" class="btn-sm btn-outline" style="color:#DC2626; border-color:#FECACA; background:#FEF2F2;">
                    <i class="fa-regular fa-trash-can"></i> Bersihkan Riwayat
                </button>
            </form>
        </div>

        <form action="{{ route('admin.chatbot.index') }}" method="GET" class="filter-grid" style="grid-template-columns: 2fr 1fr auto;">
            <input type="hidden" name="tab" value="logs">

            <div class="form-group">
                <label>Pencarian</label>
                <input type="text" name="search_log" value="{{ request('search_log') }}" placeholder="Cari pertanyaan, nama user, atau IP address..." class="input-box">
            </div>

            <div class="form-group">
                <label>Tipe Pengguna</label>
                <select name="user_type_filter" class="d-none custom-select">
                    <option value="">Semua Pengguna</option>
                    <option value="guest" {{ request('user_type_filter') === 'guest' ? 'selected' : '' }}>Hanya Tamu (Guest)</option>
                    <option value="student" {{ request('user_type_filter') === 'student' ? 'selected' : '' }}>Hanya Mahasiswa</option>
                </select>
            </div>

            <div class="form-group" style="display: flex; gap: 8px;">
                <button type="submit" class="btn-sm btn-primary" style="height: 42px;"><i class="fa-solid fa-filter"></i> Filter</button>
                @if(request()->hasAny(['search_log', 'user_type_filter']))
                    <a href="{{ route('admin.chatbot.index', ['tab' => 'logs']) }}" class="btn-sm btn-outline" style="height: 42px;"><i class="fa-solid fa-rotate-left"></i> Reset</a>
                @endif
            </div>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 160px;">Waktu Akses</th>
                        <th>Pertanyaan yang Ditanyakan</th>
                        <th style="width: 220px;">Pengguna / Role</th>
                        <th>Alamat IP & Perangkat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr>
                        <td data-label="No" style="font-weight: 600; color:var(--muted);">{{ $logs->firstItem() + $index }}</td>
                        <td data-label="Waktu Akses" style="font-size: 12px; color:var(--muted); font-weight: 500;">
                            <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
                            {{ $log->created_at ? $log->created_at->translatedFormat('d M Y, H:i') : '-' }}
                        </td>
                        <td class="cell-longtext" data-label="Pertanyaan">
                            @if($log->faq)
                                <span class="badge badge-info" style="margin-right:6px;">{{ $log->faq->kode }}</span>
                            @endif
                            <strong style="color: var(--text);">{{ $log->pertanyaan }}</strong>
                        </td>
                        <td data-label="Pengguna">
                            @if($log->user_type === 'student' && $log->user)
                                <div>
                                    <span class="badge badge-success"><i class="fa-solid fa-graduation-cap"></i> Peserta</span>
                                    <div style="font-weight: 600; font-size: 12.5px; margin-top: 4px; color:var(--text);">
                                        {{ $log->user->nama_lengkap }}
                                    </div>
                                </div>
                            @else
                                <span class="badge badge-warning"><i class="fa-solid fa-user-shield"></i> Pengunjung Tamu (Guest)</span>
                            @endif
                        </td>
                        <td class="cell-longtext" data-label="IP & Perangkat" style="font-size: 12px; color:var(--muted);">
                            <div style="background:var(--page); border:1px solid var(--border); padding:4px 8px; border-radius:6px; display:inline-block; margin-bottom:4px; font-family:monospace; color:var(--text); font-weight:600;">{{ $log->ip_address ?: '127.0.0.1' }}</div>
                            <div style="font-size: 11px; line-height:1.4;">{{ Str::limit($log->user_agent, 40) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fa-solid fa-inbox"></i>
                                <p>Belum ada riwayat percakapan yang tercatat.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="pagination-wrapper" style="margin-top: 20px;">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
    @endif

<!-- ================= MODAL TAMBAH FAQ ================= -->
<div class="modal-overlay" id="modalAddFaq">
    <div class="custom-modal">
        <div class="modal-header">
            <h3><i class="fa-solid fa-plus" style="color:var(--primary);"></i> Tambah Pertanyaan FAQ Baru</h3>
            <button type="button" class="modal-close" onclick="closeAddModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.chatbot.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label>Kode FAQ <span class="req">*</span></label>
                        <input type="text" name="kode" placeholder="Contoh: FAQ-11" required value="{{ old('kode') }}">
                    </div>
                    <div class="modal-form-group">
                        <label>Kategori</label>
                        <input type="text" name="kategori" placeholder="Umum, Pelaksanaan, dll" value="{{ old('kategori', 'Umum') }}">
                    </div>
                </div>

                <div class="modal-form-group">
                    <label>Pertanyaan Menu Chatbot <span class="req">*</span></label>
                    <input type="text" name="pertanyaan" placeholder="Tuliskan pertanyaan singkat yang jelas..." required value="{{ old('pertanyaan') }}">
                </div>

                <div class="modal-form-group">
                    <label>Isi Jawaban Bot <span class="req">*</span></label>
                    <textarea name="jawaban" placeholder="Tuliskan jawaban lengkap yang akan ditampilkan oleh bot..." required>{{ old('jawaban') }}</textarea>
                </div>

                <div class="modal-form-group">
                    <label>Hak Akses Role Pertanyaan <span class="req">*</span></label>
                    <div class="role-selector">
                        <label class="role-option">
                            <input type="radio" name="role_access" value="all" checked>
                            <strong>Guest & Student</strong>
                            <small>Tampil untuk semua pengguna</small>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role_access" value="guest">
                            <strong>Hanya Guest</strong>
                            <small>Tampil di halaman publik</small>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role_access" value="student">
                            <strong>Hanya Student</strong>
                            <small>Khusus mahasiswa magang</small>
                        </label>
                    </div>
                </div>

                <div class="modal-form-row" style="align-items: center; margin-bottom: 0;">
                    <div class="modal-form-group" style="margin-bottom: 0;">
                        <label>Nomor Urutan Tampil</label>
                        <input type="number" name="urutan" placeholder="Otomatis" value="{{ old('urutan', $totalFaq + 1) }}">
                    </div>
                    <div class="modal-form-group" style="margin-bottom: 0; padding-top: 14px;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight: 500; text-transform: none; margin: 0;">
                            <input type="checkbox" name="is_active" value="1" checked style="width:20px; height:20px; accent-color:var(--primary); margin:0;">
                            <span>Aktifkan langsung di chatbot</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-sm btn-outline" onclick="closeAddModal()">Batal</button>
                <button type="submit" class="btn-sm btn-primary"><i class="fa-solid fa-check"></i> Simpan FAQ Baru</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL EDIT FAQ ================= -->
<div class="modal-overlay" id="modalEditFaq">
    <div class="custom-modal">
        <div class="modal-header">
            <h3><i class="fa-regular fa-pen-to-square" style="color:var(--primary);"></i> Ubah Data Pertanyaan FAQ</h3>
            <button type="button" class="modal-close" onclick="closeEditModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="formEditFaq" action="" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label>Kode FAQ <span class="req">*</span></label>
                        <input type="text" name="kode" id="edit_kode" required>
                    </div>
                    <div class="modal-form-group">
                        <label>Kategori</label>
                        <input type="text" name="kategori" id="edit_kategori">
                    </div>
                </div>

                <div class="modal-form-group">
                    <label>Pertanyaan Menu <span class="req">*</span></label>
                    <input type="text" name="pertanyaan" id="edit_pertanyaan" required>
                </div>

                <div class="modal-form-group">
                    <label>Isi Jawaban Bot <span class="req">*</span></label>
                    <textarea name="jawaban" id="edit_jawaban" required></textarea>
                </div>

                <div class="modal-form-group">
                    <label>Hak Akses Role Pertanyaan <span class="req">*</span></label>
                    <div class="role-selector">
                        <label class="role-option">
                            <input type="radio" name="role_access" id="role_all" value="all">
                            <strong>Guest & Student</strong>
                            <small>Tampil untuk semua pengguna</small>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role_access" id="role_guest" value="guest">
                            <strong>Hanya Guest</strong>
                            <small>Tampil di halaman publik</small>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role_access" id="role_student" value="student">
                            <strong>Hanya Student</strong>
                            <small>Khusus mahasiswa magang</small>
                        </label>
                    </div>
                </div>

                <div class="modal-form-row" style="align-items: center; margin-bottom: 0;">
                    <div class="modal-form-group" style="margin-bottom: 0;">
                        <label>Nomor Urutan Tampil</label>
                        <input type="number" name="urutan" id="edit_urutan">
                    </div>
                    <div class="modal-form-group" style="margin-bottom: 0; padding-top: 14px;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight: 500; text-transform: none; margin: 0;">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1" style="width:20px; height:20px; accent-color:var(--primary); margin:0;">
                            <span>Status FAQ Aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-sm btn-outline" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn-sm btn-primary"><i class="fa-solid fa-check"></i> Perbarui FAQ</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // 1. CUSTOM DROPDOWN UNTUK FILTER
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
            if (e.key === 'Escape') { closeAddModal(); closeEditModal(); }
        });
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => {
                if(e.target === overlay) { closeAddModal(); closeEditModal(); }
            });
        });
    });

    // ================= FUNGSI MODAL TAMBAH =================
    window.openAddModal = function() {
        const m = document.getElementById('modalAddFaq');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }

    window.closeAddModal = function() {
        const m = document.getElementById('modalAddFaq');
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);
    }

    // ================= FUNGSI MODAL EDIT =================
    window.openEditModal = function(faq) {
        const form = document.getElementById('formEditFaq');
        form.action = `/admin/chatbot/${faq.id_faq}`;

        document.getElementById('edit_kode').value = faq.kode;
        document.getElementById('edit_pertanyaan').value = faq.pertanyaan;
        document.getElementById('edit_jawaban').value = faq.jawaban;
        document.getElementById('edit_kategori').value = faq.kategori;
        document.getElementById('edit_urutan').value = faq.urutan;
        document.getElementById('edit_is_active').checked = Boolean(faq.is_active);

        if (faq.role_access === 'all') document.getElementById('role_all').checked = true;
        else if (faq.role_access === 'guest') document.getElementById('role_guest').checked = true;
        else if (faq.role_access === 'student') document.getElementById('role_student').checked = true;

        const m = document.getElementById('modalEditFaq');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }

    window.closeEditModal = function() {
        const m = document.getElementById('modalEditFaq');
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);
    }
</script>
@endpush
