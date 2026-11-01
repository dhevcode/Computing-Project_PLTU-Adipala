<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot FAQ & Log Riwayat - Admin Monitoring PKL</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/features.css') }}">

    <style>
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }
        .role-badge.all {
            background: #EFF6FF;
            color: #1D4ED8;
            border: 1px solid #BFDBFE;
        }
        .role-badge.guest {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FCD34D;
        }
        .role-badge.student {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .category-badge {
            background: #F1F5F9;
            color: #475569;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
        }

        .top-faq-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            padding: 16px 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .top-faq-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .top-faq-header h4 {
            margin: 0;
            font-size: 14px;
            color: #0A5F63;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .top-faq-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .top-faq-item {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 11.5px;
            color: #1E293B;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .top-faq-item .count-pill {
            background: #0A5F63;
            color: #fff;
            padding: 1px 6px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-dialog {
            background: #fff;
            border-radius: 16px;
            max-width: 650px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
            animation: modalFadeIn 0.22s ease-out;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #F8FAFC;
            border-radius: 16px 16px 0 0;
        }
        .modal-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: #0F172A;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 18px;
            color: #94A3B8;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
        }
        .modal-close:hover {
            color: #0F172A;
            background: #E2E8F0;
        }
        .modal-body {
            padding: 24px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        .form-group label span.req {
            color: #DC2626;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            font-size: 13px;
            color: #1E293B;
            background: #ffffff;
            box-sizing: border-box;
            font-family: inherit;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #0A5F63;
            box-shadow: 0 0 0 3px rgba(10, 95, 99, 0.15);
        }
        .form-group textarea {
            min-height: 110px;
            resize: vertical;
            line-height: 1.5;
        }
        .role-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 4px;
        }
        .role-option {
            border: 1.5px solid #CBD5E1;
            border-radius: 10px;
            padding: 10px 12px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 4px;
            transition: all 0.2s;
        }
        .role-option:hover {
            border-color: #0A5F63;
            background: #F0FDF4;
        }
        .role-option input[type="radio"] {
            margin-bottom: 4px;
            accent-color: #0A5F63;
        }
        .role-option strong {
            font-size: 12px;
            color: #1E293B;
        }
        .role-option small {
            font-size: 10.5px;
            color: #64748B;
            line-height: 1.3;
        }
        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #E5E7EB;
            background: #F8FAFC;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-radius: 0 0 16px 16px;
        }
        .btn-cancel {
            background: #E2E8F0;
            color: #334155;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-cancel:hover {
            background: #CBD5E1;
        }
        .btn-submit {
            background: #0A5F63;
            color: #ffffff;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background: #084C4F;
        }

        .tab-nav {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            border-bottom: 2px solid #E2E8F0;
            padding-bottom: 0;
        }
        .tab-btn {
            background: none;
            border: none;
            padding: 12px 20px;
            font-size: 13px;
            font-weight: 600;
            color: #64748B;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .tab-btn:hover {
            color: #0A5F63;
        }
        .tab-btn.active {
            color: #0A5F63;
            border-bottom-color: #0A5F63;
            background: rgba(10, 95, 99, 0.05);
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div class="logo-box">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
            </div>
            <div class="header-title">
                <h2>Monitoring Mahasiswa PKL</h2>
                <p>Kelola dan pantau data mahasiswa PKL dengan mudah dan efisien.</p>
            </div>
        </div>
        <div class="header-right">
            <button type="button" class="menu-btn" id="menuBtn"><i class="fa-solid fa-bars"></i></button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
            </form>
        </div>
    </header>

    <!-- Sidebar -->
    @include('admin.sidebar')

    <!-- Content -->
    <main class="content">

        <!-- Page Header Card -->
        <div class="page-header-card">
            <div class="page-header-content">
                <div class="page-icon" style="background:#0A5F63;">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div>
                    <h1>Kelola Chatbot FAQ & Riwayat Interaksi</h1>
                    <p>Kelola daftar pertanyaan menu otomatis (CRUD) untuk Guest & Mahasiswa serta pantau riwayat pertanyaan yang sering diajukan.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <strong>Terdapat kesalahan:</strong>
                    <ul style="margin: 4px 0 0 16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Statistik Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #E0F2FE; color: #0284C7;">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total FAQ</span>
                    <h3 class="stat-value">{{ $totalFaq }}</h3>
                    <small class="stat-desc">{{ $totalFaqAktif }} FAQ status aktif</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #ECFDF5; color: #059669;">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total Interaksi</span>
                    <h3 class="stat-value">{{ $totalInteraksi }}</h3>
                    <small class="stat-desc">Pertanyaan diklik pengguna</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Interaksi Tamu (Guest)</span>
                    <h3 class="stat-value">{{ $interaksiGuest }}</h3>
                    <small class="stat-desc">Pengunjung publik / login page</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #F3E8FF; color: #9333EA;">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Interaksi Mahasiswa</span>
                    <h3 class="stat-value">{{ $interaksiStudent }}</h3>
                    <small class="stat-desc">Peserta magang terdaftar</small>
                </div>
            </div>
        </div>

        <!-- FAQ Terpopuler Card -->
        @if($topFaqs->count() > 0)
        <div class="top-faq-card">
            <div class="top-faq-header">
                <h4>
                    <i class="fa-solid fa-fire" style="color:#EF4444;"></i>
                    Top 5 FAQ Paling Sering Ditanyakan Pengguna
                </h4>
                <small style="color:#64748B;">Berdasarkan frekuensi klik realtime</small>
            </div>
            <div class="top-faq-list">
                @foreach($topFaqs as $idx => $tf)
                <div class="top-faq-item">
                    <span style="font-weight:700; color:#0A5F63;">#{{ $idx + 1 }}</span>
                    <span><strong>[{{ $tf->kode }}]</strong> {{ Str::limit($tf->pertanyaan, 48) }}</span>
                    <span class="count-pill">{{ $tf->click_count }}x klik</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Tab Navigasi -->
        <div class="tab-nav">
            <a href="{{ route('admin.chatbot.index', ['tab' => 'faq']) }}" class="tab-btn {{ $tab === 'faq' ? 'active' : '' }}">
                <i class="fa-solid fa-list-check"></i>
                <span>Daftar FAQ Chatbot (CRUD)</span>
            </a>
            <a href="{{ route('admin.chatbot.index', ['tab' => 'logs']) }}" class="tab-btn {{ $tab === 'logs' ? 'active' : '' }}">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Log Riwayat Percakapan ({{ $totalInteraksi }})</span>
            </a>
        </div>

        @if($tab === 'faq')
        <!-- ================= TAB 1: KELOLA DAFTAR FAQ (CRUD) ================= -->
        <div class="table-card">
            <div class="table-header">
                <div>
                    <h3 class="table-title">Daftar Pertanyaan FAQ</h3>
                    <p class="table-subtitle">Pertanyaan berbasis menu yang ditampilkan pada tombol quick reply chatbot.</p>
                </div>

                <div class="table-actions">
                    <button type="button" class="btn btn-primary" onclick="openAddModal()">
                        <i class="fa-solid fa-plus"></i>
                        Tambah FAQ Baru
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <form action="{{ route('admin.chatbot.index') }}" method="GET" class="filter-box" style="padding: 16px 20px; background:#F8FAFC; border-bottom:1px solid #E2E8F0; display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                <input type="hidden" name="tab" value="faq">

                <div style="flex: 1; min-width: 220px;">
                    <input type="text" name="search_faq" value="{{ request('search_faq') }}" placeholder="Cari pertanyaan, kode, atau jawaban..." style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 12.5px;">
                </div>

                <div style="min-width: 170px;">
                    <select name="role_access" style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 12.5px;">
                        <option value="all_filter">Semua Hak Akses Role</option>
                        <option value="all" {{ request('role_access') === 'all' ? 'selected' : '' }}>Guest & Student (Umum)</option>
                        <option value="guest" {{ request('role_access') === 'guest' ? 'selected' : '' }}>Hanya Guest (Publik)</option>
                        <option value="student" {{ request('role_access') === 'student' ? 'selected' : '' }}>Hanya Student (Peserta)</option>
                    </select>
                </div>

                <div style="min-width: 140px;">
                    <select name="status_filter" style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 12.5px;">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status_filter') === 'active' ? 'selected' : '' }}>Aktif Saja</option>
                        <option value="inactive" {{ request('status_filter') === 'inactive' ? 'selected' : '' }}>Nonaktif Saja</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary" style="padding: 8px 16px; font-size: 12.5px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>

                @if(request()->hasAny(['search_faq', 'role_access', 'status_filter']))
                <a href="{{ route('admin.chatbot.index', ['tab' => 'faq']) }}" class="btn" style="background:#E2E8F0; color:#475569; padding: 8px 14px; font-size: 12.5px; text-decoration:none; border-radius:8px;">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Urut</th>
                            <th style="width: 90px;">Kode</th>
                            <th>Pertanyaan & Jawaban</th>
                            <th style="width: 110px;">Kategori</th>
                            <th style="width: 160px;">Role Akses</th>
                            <th style="width: 70px; text-align: center;">Klik</th>
                            <th style="width: 90px; text-align: center;">Status</th>
                            <th style="width: 120px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $f)
                        <tr>
                            <td style="font-weight: 600; color:#64748B;">{{ $f->urutan }}</td>
                            <td>
                                <strong style="color: #0A5F63;">{{ $f->kode }}</strong>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #1E293B; margin-bottom: 4px; font-size: 13px;">
                                    {{ $f->pertanyaan }}
                                </div>
                                <div style="font-size: 12px; color: #64748B; line-height: 1.45;">
                                    {{ Str::limit($f->jawaban, 130) }}
                                </div>
                            </td>
                            <td>
                                <span class="category-badge">{{ $f->kategori }}</span>
                            </td>
                            <td>
                                @if($f->role_access === 'all')
                                    <span class="role-badge all"><i class="fa-solid fa-users"></i> Guest & Student</span>
                                @elseif($f->role_access === 'guest')
                                    <span class="role-badge guest"><i class="fa-solid fa-user-shield"></i> Hanya Guest</span>
                                @else
                                    <span class="role-badge student"><i class="fa-solid fa-graduation-cap"></i> Hanya Student</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <span style="background:#F1F5F9; font-weight:700; color:#0A5F63; padding:2px 8px; border-radius:999px; font-size:11px;">
                                    {{ $f->click_count }}x
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <form action="{{ route('admin.chatbot.toggle', $f->id_faq) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @if($f->is_active)
                                    <button type="submit" class="badge badge-success" style="border:none; cursor:pointer;" title="Klik untuk menonaktifkan">
                                        <i class="fa-solid fa-check"></i> Aktif
                                    </button>
                                    @else
                                    <button type="submit" class="badge badge-danger" style="border:none; cursor:pointer;" title="Klik untuk mengaktifkan">
                                        <i class="fa-solid fa-ban"></i> Nonaktif
                                    </button>
                                    @endif
                                </form>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    <button type="button" class="btn-action edit" title="Edit FAQ"
                                        onclick="openEditModal({{ json_encode($f) }})">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    <form action="{{ route('admin.chatbot.destroy', $f->id_faq) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan {{ $f->kode }} ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Hapus FAQ">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 40px 20px; color: #94A3B8;">
                                <i class="fa-solid fa-robot" style="font-size: 36px; margin-bottom: 10px; display: block;"></i>
                                <span>Belum ada data FAQ yang cocok dengan filter.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($faqs->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #E2E8F0;">
                {{ $faqs->links() }}
            </div>
            @endif
        </div>

        @else
        <!-- ================= TAB 2: LOG RIWAYAT PERCAKAPAN ================= -->
        <div class="table-card">
            <div class="table-header">
                <div>
                    <h3 class="table-title">Log Riwayat Percakapan & Pertanyaan</h3>
                    <p class="table-subtitle">Memantau pertanyaan apa saja yang sering diklik oleh calon peserta (Guest) dan mahasiswa magang.</p>
                </div>

                <div class="table-actions">
                    <form action="{{ route('admin.chatbot.clear-logs') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membersihkan seluruh riwayat log percakapan chatbot?')">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="color:#DC2626; border-color:#FECACA; background:#FEF2F2;">
                            <i class="fa-regular fa-trash-can"></i>
                            Bersihkan Riwayat
                        </button>
                    </form>
                </div>
            </div>

            <!-- Filter Section -->
            <form action="{{ route('admin.chatbot.index') }}" method="GET" class="filter-box" style="padding: 16px 20px; background:#F8FAFC; border-bottom:1px solid #E2E8F0; display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                <input type="hidden" name="tab" value="logs">

                <div style="flex: 1; min-width: 240px;">
                    <input type="text" name="search_log" value="{{ request('search_log') }}" placeholder="Cari pertanyaan, nama user, atau IP address..." style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 12.5px;">
                </div>

                <div style="min-width: 180px;">
                    <select name="user_type_filter" style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 12.5px;">
                        <option value="">Semua Tipe Pengguna</option>
                        <option value="guest" {{ request('user_type_filter') === 'guest' ? 'selected' : '' }}>Hanya Tamu (Guest)</option>
                        <option value="student" {{ request('user_type_filter') === 'student' ? 'selected' : '' }}>Hanya Mahasiswa (Student)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary" style="padding: 8px 16px; font-size: 12.5px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>

                @if(request()->hasAny(['search_log', 'user_type_filter']))
                <a href="{{ route('admin.chatbot.index', ['tab' => 'logs']) }}" class="btn" style="background:#E2E8F0; color:#475569; padding: 8px 14px; font-size: 12.5px; text-decoration:none; border-radius:8px;">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 160px;">Waktu</th>
                            <th>Pertanyaan yang Ditanyakan</th>
                            <th style="width: 200px;">Pengguna / Role</th>
                            <th style="width: 180px;">Alamat IP & Perangkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $index }}</td>
                            <td style="font-size: 11.5px; color:#475569;">
                                <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
                                {{ $log->created_at ? $log->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td>
                                @if($log->faq)
                                <span class="badge" style="background:#E0F2FE; color:#0369A1; font-weight:700; margin-right:4px;">
                                    {{ $log->faq->kode }}
                                </span>
                                @endif
                                <strong style="color: #1E293B;">{{ $log->pertanyaan }}</strong>
                            </td>
                            <td>
                                @if($log->user_type === 'student' && $log->user)
                                    <div>
                                        <span class="role-badge student"><i class="fa-solid fa-graduation-cap"></i> Peserta</span>
                                        <div style="font-weight: 600; font-size: 12px; margin-top: 3px; color:#1E293B;">
                                            {{ $log->user->nama_lengkap }}
                                        </div>
                                    </div>
                                @else
                                    <span class="role-badge guest"><i class="fa-solid fa-user-shield"></i> Pengunjung Tamu</span>
                                @endif
                            </td>
                            <td style="font-size: 11.5px; color:#64748B;">
                                <div><code>{{ $log->ip_address ?: '127.0.0.1' }}</code></div>
                                <div style="font-size: 10.5px; color:#94A3B8; margin-top: 2px;">
                                    {{ Str::limit($log->user_agent, 28) }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 40px 20px; color: #94A3B8;">
                                <i class="fa-solid fa-inbox" style="font-size: 36px; margin-bottom: 10px; display: block;"></i>
                                <span>Belum ada riwayat percakapan yang tercatat.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #E2E8F0;">
                {{ $logs->links() }}
            </div>
            @endif
        </div>
        @endif

    </main>

    <!-- ================= MODAL TAMBAH FAQ ================= -->
    <div class="modal-overlay" id="modalAddFaq">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3><i class="fa-solid fa-plus" style="color:#0A5F63;"></i> Tambah Pertanyaan FAQ Baru</h3>
                <button type="button" class="modal-close" onclick="closeAddModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.chatbot.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="add_kode">Kode FAQ <span class="req">*</span></label>
                            <input type="text" name="kode" id="add_kode" placeholder="Contoh: FAQ-11" required value="{{ old('kode') }}">
                        </div>
                        <div class="form-group">
                            <label for="add_kategori">Kategori</label>
                            <input type="text" name="kategori" id="add_kategori" placeholder="Umum, Pendaftaran, Pelaksanaan, dll" value="{{ old('kategori', 'Umum') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="add_pertanyaan">Pertanyaan Menu <span class="req">*</span></label>
                        <input type="text" name="pertanyaan" id="add_pertanyaan" placeholder="Tuliskan pertanyaan singkat yang jelas..." required value="{{ old('pertanyaan') }}">
                    </div>

                    <div class="form-group">
                        <label for="add_jawaban">Isi Jawaban Bot <span class="req">*</span></label>
                        <textarea name="jawaban" id="add_jawaban" placeholder="Tuliskan jawaban lengkap yang akan ditampilkan oleh asisten virtual..." required>{{ old('jawaban') }}</textarea>
                    </div>

                    <!-- Role Access Selector -->
                    <div class="form-group">
                        <label>Hak Akses Role Pertanyaan <span class="req">*</span></label>
                        <div class="role-selector">
                            <label class="role-option">
                                <input type="radio" name="role_access" value="all" checked>
                                <strong>Guest & Student</strong>
                                <small>Tampil untuk semua pengguna publik & peserta magang</small>
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role_access" value="guest">
                                <strong>Hanya Guest</strong>
                                <small>Hanya tampil di halaman publik (Login/Pendaftaran)</small>
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role_access" value="student">
                                <strong>Hanya Student</strong>
                                <small>Khusus mahasiswa magang yang telah login portal</small>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="add_urutan">Nomor Urutan Tampil</label>
                            <input type="number" name="urutan" id="add_urutan" placeholder="Otomatis" value="{{ old('urutan', $totalFaq + 1) }}">
                        </div>
                        <div class="form-group" style="display:flex; align-items:center; padding-top:24px;">
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                <input type="checkbox" name="is_active" value="1" checked style="width:18px; height:18px; accent-color:#0A5F63;">
                                <span>Aktifkan langsung di chatbot</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-check"></i> Simpan FAQ Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL EDIT FAQ ================= -->
    <div class="modal-overlay" id="modalEditFaq">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3><i class="fa-regular fa-pen-to-square" style="color:#0A5F63;"></i> Ubah Data Pertanyaan FAQ</h3>
                <button type="button" class="modal-close" onclick="closeEditModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="formEditFaq" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_kode">Kode FAQ <span class="req">*</span></label>
                            <input type="text" name="kode" id="edit_kode" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_kategori">Kategori</label>
                            <input type="text" name="kategori" id="edit_kategori">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_pertanyaan">Pertanyaan Menu <span class="req">*</span></label>
                        <input type="text" name="pertanyaan" id="edit_pertanyaan" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_jawaban">Isi Jawaban Bot <span class="req">*</span></label>
                        <textarea name="jawaban" id="edit_jawaban" required></textarea>
                    </div>

                    <!-- Role Access Selector -->
                    <div class="form-group">
                        <label>Hak Akses Role Pertanyaan <span class="req">*</span></label>
                        <div class="role-selector">
                            <label class="role-option">
                                <input type="radio" name="role_access" id="role_all" value="all">
                                <strong>Guest & Student</strong>
                                <small>Tampil untuk semua pengguna publik & peserta magang</small>
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role_access" id="role_guest" value="guest">
                                <strong>Hanya Guest</strong>
                                <small>Hanya tampil di halaman publik (Login/Pendaftaran)</small>
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role_access" id="role_student" value="student">
                                <strong>Hanya Student</strong>
                                <small>Khusus mahasiswa magang yang telah login portal</small>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_urutan">Nomor Urutan Tampil</label>
                            <input type="number" name="urutan" id="edit_urutan">
                        </div>
                        <div class="form-group" style="display:flex; align-items:center; padding-top:24px;">
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                <input type="checkbox" name="is_active" id="edit_is_active" value="1" style="width:18px; height:18px; accent-color:#0A5F63;">
                                <span>Status FAQ Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-check"></i> Perbarui FAQ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Javascript Handlers -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuBtn = document.getElementById('menuBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            menuBtn?.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
            overlay?.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });

            // Escape key closes modals
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeAddModal();
                    closeEditModal();
                }
            });
        });

        function openAddModal() {
            document.getElementById('modalAddFaq').classList.add('active');
        }

        function closeAddModal() {
            document.getElementById('modalAddFaq').classList.remove('active');
        }

        function openEditModal(faq) {
            const form = document.getElementById('formEditFaq');
            form.action = `/admin/chatbot/${faq.id_faq}`;

            document.getElementById('edit_kode').value = faq.kode;
            document.getElementById('edit_pertanyaan').value = faq.pertanyaan;
            document.getElementById('edit_jawaban').value = faq.jawaban;
            document.getElementById('edit_kategori').value = faq.kategori;
            document.getElementById('edit_urutan').value = faq.urutan;
            document.getElementById('edit_is_active').checked = Boolean(faq.is_active);

            // Set radio button role
            if (faq.role_access === 'all') {
                document.getElementById('role_all').checked = true;
            } else if (faq.role_access === 'guest') {
                document.getElementById('role_guest').checked = true;
            } else if (faq.role_access === 'student') {
                document.getElementById('role_student').checked = true;
            }

            document.getElementById('modalEditFaq').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('modalEditFaq').classList.remove('active');
        }
    </script>
</body>
</html>
