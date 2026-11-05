@extends('superadmin.layouts.app')

@section('title', 'Kelola Peserta Magang')
@section('page_title', 'Peserta Magang')
@section('page_subtitle', 'Kelola data mahasiswa (CRUD), penempatan divisi, pembimbing, dan akun portal.')

@push('styles')
<style>
    /* ==========================================================================
       ANIMASI DASHBOARD SUPERADMIN (SMOOTH & CLEAN)
       ========================================================================== */

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

    /* Animasi Grid & Header */
    .stats-grid { animation: fadeIn 0.4s ease-out; }

    /* Efek Kartu Statistik Muncul Bergantian (Staggered) & Interaktif */
    .stat-card {
        animation: fadeInUp 0.5s ease-out both;
        transition: transform 0.3s ease, box-shadow 0.3s ease !important;
        will-change: transform, opacity;
    }
    .stats-grid .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stats-grid .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stats-grid .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stats-grid .stat-card:nth-child(4) { animation-delay: 0.4s; }

    .stat-card:hover {
        transform: translateY(-6px) !important;
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
    }

    /* =========================================
       FILTER BAR & TOMBOL
       ========================================= */
    .filter-bar {
        background: #ffffff;
        border-radius: var(--radius);
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(226, 232, 240, 0.8);
        animation: scaleIn 0.4s ease-out;
        transition: box-shadow 0.3s ease;
    }
    .filter-bar:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    }
    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* =========================================
       CUSTOM DROPDOWN KHUSUS FILTER
       ========================================= */
    .custom-select-wrapper { position: relative; width: auto; min-width: 210px; user-select: none; }
    .custom-select-trigger {
        height: 38px;
        padding: 0 14px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        background: #ffffff;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.1s;
    }
    .custom-select-trigger:active {
        transform: scale(0.98);
    }
    .custom-select-trigger:hover, .custom-select-wrapper.open .custom-select-trigger {
        border-color: var(--primary);
    }
    .custom-select-wrapper.open .custom-select-trigger {
        box-shadow: 0 0 0 3px var(--primary-soft);
    }
    .custom-select-trigger i { color: var(--muted); font-size: 12px; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin-left: 10px; }
    .custom-select-wrapper.open .custom-select-trigger i { transform: rotate(180deg); color: var(--primary); }

    .custom-select-options {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        width: 100%;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 10px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        z-index: 99;
        padding: 8px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px) scale(0.98);
        transform-origin: top;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        max-height: 250px;
        overflow-y: auto;
    }
    .custom-select-wrapper.open .custom-select-options {
        opacity: 1; visibility: visible; transform: translateY(0) scale(1);
    }
    .custom-option {
        padding: 10px 14px;
        font-size: 13px;
        color: var(--text);
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
        margin-bottom: 2px;
    }
    .custom-option:hover, .custom-option.selected {
        background: var(--primary-soft);
        color: var(--primary-dark);
        font-weight: 700;
    }

    /* Animasi Search Box */
    .search-box {
        position: relative;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .search-box input {
        transition: all 0.3s ease;
    }
    .search-box:focus-within {
        transform: translateY(-1px);
    }
    .search-box:focus-within input {
        box-shadow: 0 0 0 3px var(--primary-soft);
        border-color: var(--primary);
    }

    /* =========================================
       BADGE & TOMBOL AKSI
       ========================================= */
    .btn-add-peserta {
        background: var(--primary);
        color: #fff;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        white-space: nowrap;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-add-peserta i { font-size: 14px; transition: transform 0.3s; }
    .btn-add-peserta:hover {
        background: var(--primary-dark);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }
    .btn-add-peserta:hover i { transform: rotate(90deg); }
    .btn-add-peserta:active { transform: translateY(0); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }

    .account-badge-yes {
        background: var(--success-bg);
        color: var(--success-text);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .account-badge-no {
        background: #FEF3C7;
        color: #B45309;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-create-acc, .btn-reset-acc {
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        font-family: inherit;
    }
    .btn-create-acc { background: var(--primary); color: #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .btn-create-acc:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }

    .btn-reset-acc { background: #F1F5F9; color: #475569; border: 1px solid var(--border); }
    .btn-reset-acc:hover { background: #E2E8F0; color: var(--text); transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.05); }

    .btn-search {
        background: var(--primary);
        color: #ffffff;
        border: none;
        height: 38px;
        padding: 0 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: inherit;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .btn-search:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
    .btn-search:active { transform: translateY(0); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }

    /* =========================================
       ANIMASI KARTU TABEL UTAMA
       ========================================= */
    .card {
        animation: fadeInUp 0.5s ease-out both;
        animation-delay: 0.3s;
    }

    /* Animasi Baris Tabel Muncul Bergantian */
    .data-row {
        animation: fadeIn 0.4s ease-out both;
        transition: all 0.2s ease;
    }
    .data-row:nth-child(1) { animation-delay: 0.4s; }
    .data-row:nth-child(2) { animation-delay: 0.45s; }
    .data-row:nth-child(3) { animation-delay: 0.5s; }
    .data-row:nth-child(4) { animation-delay: 0.55s; }
    .data-row:nth-child(5) { animation-delay: 0.6s; }
    .data-row:nth-child(n+6) { animation-delay: 0.65s; }

    #pesertaTable tbody tr:hover { background-color: #F8FAFC !important; transform: scale(1.002); }

    /* Animasi Avatar */
    .avatar-circle { transition: transform 0.3s ease; }
    #pesertaTable tbody tr:hover .avatar-circle { transform: scale(1.1) rotate(5deg); }

    /* Tombol Aksi Tabel (View, Edit, Delete) */
    .btn-action { transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .btn-action:hover { transform: translateY(-3px) scale(1.1); }
    .btn-action:active { transform: translateY(0) scale(0.95); }

    /* =========================================
       MODAL
       ========================================= */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(6px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .modal-overlay.active { display: flex; opacity: 1; }

    .modal-card {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 24px 50px rgba(0, 0, 0, 0.3);
        padding: 24px;
        transform: scale(0.95) translateY(20px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .modal-overlay.active .modal-card {
        transform: scale(1) translateY(0);
        opacity: 1;
    }

    .modal-card h3 { font-size: 17px; font-weight: 800; color: var(--text); margin-bottom: 8px; }
    .modal-card p { font-size: 13.5px; color: var(--muted); margin-bottom: 24px; line-height: 1.5; }
    .modal-input-label { font-size: 13px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text); }

    .btn-modal-action {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: inherit;
        transition: all 0.2s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-modal-action:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 6px 14px rgba(0,0,0,0.15); }
    .btn-modal-action:active { transform: translateY(0); }

    .btn-cancel {
        background: #fff;
        color: var(--text);
        border: 1px solid var(--border);
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: 0.2s;
    }
    .btn-cancel:hover { background: var(--page); border-color: #CBD5E1; }

    .modal-input {
        height: 42px;
        border-radius: 8px;
        border: 1.5px solid var(--border);
        padding: 0 14px;
        font-family: inherit;
        font-size: 13.5px;
        color: var(--text);
        background: #ffffff;
        outline: none;
        transition: all 0.2s;
    }
    .modal-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }

    /* =========================================
       RESPONSIVE
       ========================================= */
    .stats-grid { grid-template-columns: repeat(4, 1fr); margin-bottom: 24px; }

    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .filter-bar { flex-direction: column; align-items: stretch; }
        .filter-group { flex-direction: column; align-items: stretch; }
        .custom-select-wrapper { width: 100%; min-width: 100%; }
        .search-box { width: 100%; margin-bottom: 10px; }
        .btn-search { width: 100%; }
        .card-header { flex-direction: column; align-items: stretch; gap: 16px; }
        .btn-add-peserta { width: 100%; justify-content: center; }

        /* PERBAIKAN TABEL PESERTA MENJADI KARTU DI HP */
        #pesertaTable thead { display: none !important; }
        #pesertaTable, #pesertaTable tbody, #pesertaTable tr, #pesertaTable td {
            display: block; width: 100%; box-sizing: border-box;
        }
        #pesertaTable tr.data-row {
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 16px;
            padding: 16px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            position: relative;
            transition: transform 0.2s ease;
        }
        #pesertaTable tr.data-row:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }

        #pesertaTable td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px dashed var(--page);
            text-align: right;
            white-space: normal;
        }
        #pesertaTable td:last-child { border-bottom: none; padding-bottom: 0; }

        #pesertaTable td::before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--muted);
            font-size: 11.5px;
            text-align: left;
            flex-shrink: 0;
            margin-right: 12px;
            text-transform: uppercase;
        }
        #pesertaTable td > * { max-width: 65%; text-align: right; }

        #pesertaTable td.cell-user {
            display: block;
            text-align: left;
            background: var(--page);
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 12px;
            border-bottom: none;
        }
        #pesertaTable td.cell-user > * { max-width: 100%; text-align: left; }
        #pesertaTable td.cell-user::before { display: none; }
        #pesertaTable td.cell-number { display: none; }
        #pesertaTable td.cell-action { display: flex; justify-content: flex-end; padding-top: 16px; margin-top: 8px;}
        #pesertaTable td.cell-action::before { display: none; }
    }
</style>
@endpush

@section('content')

    <!-- STATS WIDGETS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h4>Total Peserta Magang</h4>
                <div class="stat-value">{{ $totalPeserta ?? 0 }}</div>
            </div>
            <div class="stat-icon primary">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h4>Sedang Magang Aktif</h4>
                <div class="stat-value">{{ $sedangMagang ?? 0 }}</div>
            </div>
            <div class="stat-icon success">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h4>Akan Magang</h4>
                <div class="stat-value">{{ $akanMagang ?? 0 }}</div>
            </div>
            <div class="stat-icon warning">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h4>Belum Memiliki Akun</h4>
                <div class="stat-value" style="color: {{ ($belumPunyaAkun ?? 0) > 0 ? 'var(--error-text)' : 'var(--success-text)' }};">
                    {{ $belumPunyaAkun ?? 0 }}
                </div>
            </div>
            <div class="stat-icon primary" style="background: var(--error-bg); color: var(--error-text);">
                <i class="fa-solid fa-key"></i>
            </div>
        </div>
    </div>

    <!-- FILTER BAR -->
    <form method="GET" action="{{ route('superadmin.peserta') }}" class="filter-bar">
        <div class="filter-group">

            {{-- Dropdown Divisi (Disembunyikan & Diganti JS) --}}
            <select name="divisi_id" class="d-none custom-filter-select">
                <option value="all" {{ request('divisi_id') == 'all' ? 'selected' : '' }}>-- Semua Divisi --</option>
                @foreach($divisiList as $d)
                    <option value="{{ $d->id_divisi }}" {{ request('divisi_id') == $d->id_divisi ? 'selected' : '' }}>
                        {{ $d->nama_divisi }}
                    </option>
                @endforeach
            </select>

            {{-- Dropdown Status Magang (Disembunyikan & Diganti JS) --}}
            <select name="status" class="d-none custom-filter-select">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>-- Semua Status Magang --</option>
                <option value="Sedang Magang" {{ request('status') == 'Sedang Magang' ? 'selected' : '' }}>Sedang Magang</option>
                <option value="Akan Magang" {{ request('status') == 'Akan Magang' ? 'selected' : '' }}>Akan Magang</option>
                <option value="Selesai Magang" {{ request('status') == 'Selesai Magang' ? 'selected' : '' }}>Selesai Magang</option>
            </select>

            {{-- Dropdown Status Akun (Disembunyikan & Diganti JS) --}}
            <select name="status_akun" class="d-none custom-filter-select">
                <option value="all" {{ request('status_akun') == 'all' ? 'selected' : '' }}>-- Status Akun Portal --</option>
                <option value="ada_akun" {{ request('status_akun') == 'ada_akun' ? 'selected' : '' }}>Sudah Memiliki Akun</option>
                <option value="belum_akun" {{ request('status_akun') == 'belum_akun' ? 'selected' : '' }}>Belum Ada Akun</option>
            </select>

        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <div class="search-box" style="margin: 0; width: 220px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIM...">
            </div>
            <button type="submit" class="btn-search"><i class="fa-solid fa-filter" style="margin-right: 6px;"></i> Filter</button>

            @if(request()->anyFilled(['divisi_id', 'status', 'status_akun', 'q']) &&
               (request('divisi_id') != 'all' || request('status') != 'all' || request('status_akun') != 'all' || request('q') != ''))
                <a href="{{ route('superadmin.peserta') }}" style="font-size: 12px; color: var(--error-text); text-decoration: none; font-weight: 700; margin-left: 4px; padding: 6px 10px; border-radius: 6px; background: var(--error-bg); transition: 0.2s;">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
            @endif
        </div>
    </form>

    <!-- MAIN TABLE CARD -->
    <div class="card">
        <div class="card-header">
            <div class="card-title-group">
                <h3>Daftar Data Peserta Magang / PKL</h3>
                <p>Kelola data mahasiswa magang, penempatan divisi, pembimbing lapangan, dan akun portal.</p>
            </div>
            <div>
                <a href="{{ route('superadmin.peserta.create') }}" class="btn-add-peserta">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Peserta Baru</span>
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table id="pesertaTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 40px;">No</th>
                        <th>Mahasiswa & NIM</th>
                        <th>Kampus & Jurusan</th>
                        <th>Divisi Penempatan</th>
                        <th>Pembimbing Lapangan</th>
                        <th>Periode Magang</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Akun Portal</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $index => $mhs)
                        @php
                            $pengajuan = $mhs->latestPengajuan;
                            $userAcc = $mhs->user;
                        @endphp
                        <tr class="data-row">
                            <td class="text-center row-number cell-number">
                                {{ $mahasiswas->firstItem() + $index }}
                            </td>
                            <td class="cell-user">
                                <div class="user-cell">
                                    <div class="avatar-circle" style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">
                                        {{ strtoupper(substr($mhs->nama_lengkap ?? 'M', 0, 1)) }}
                                    </div>
                                    <div class="user-info">
                                        <span class="name">{{ $mhs->nama_lengkap }}</span>
                                        <span class="email">NIM: <strong><span class="code-tag" style="background: transparent; padding: 0; color: inherit;">{{ $mhs->nim }}</span></strong></span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Kampus & Jurusan">
                                <strong style="display:block; color: var(--text);">{{ $mhs->universitas->nama_universitas ?? '-' }}</strong>
                                <small style="color: var(--muted); font-weight: 500;">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</small>
                            </td>
                            <td data-label="Divisi Penempatan">
                                <span style="font-weight: 700; color: var(--primary);">
                                    {{ $pengajuan->divisi->nama_divisi ?? 'Belum Ditentukan' }}
                                </span>
                            </td>
                            <td data-label="Pembimbing Lapangan">
                                @if($pengajuan && ($pengajuan->pembimbing || $pengajuan->nama_pembimbing))
                                    <div style="font-size: 13.5px; font-weight: 700; color: var(--text);">
                                        <i class="fa-solid fa-user-tie" style="color: var(--primary); margin-right: 6px;"></i>
                                        {{ $pengajuan->pembimbing->nama_lengkap ?? $pengajuan->nama_pembimbing }}
                                    </div>
                                @else
                                    <span style="color: var(--muted); font-style: italic; font-size: 12.5px;">Belum Ditugaskan</span>
                                @endif
                            </td>
                            <td data-label="Periode Magang" style="font-size: 12.5px; font-weight: 600;">
                                @if($pengajuan)
                                    {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d/m/Y') }}<br>
                                    <span style="color: var(--muted); font-size: 11px;">s/d</span>
                                    {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td data-label="Status Magang" class="text-center">
                                @if($pengajuan)
                                    @if($pengajuan->status === 'Sedang Magang')
                                        <span class="badge badge-active"><i class="fa-solid fa-circle" style="font-size: 8px; margin-right: 4px;"></i> Sedang Magang</span>
                                    @elseif($pengajuan->status === 'Akan Magang')
                                        <span class="badge" style="background: #EEF2FF; color: #4338CA; border: 1px solid #C7D2FE;">
                                            <i class="fa-solid fa-clock" style="font-size: 10px; margin-right: 4px;"></i> Akan Magang
                                        </span>
                                    @else
                                        <span class="badge badge-inactive"><i class="fa-solid fa-check" style="font-size: 10px; margin-right: 4px;"></i> Selesai Magang</span>
                                    @endif
                                @else
                                    <span class="badge badge-inactive">-</span>
                                @endif
                            </td>
                            <td data-label="Akun Portal" class="text-center">
                                @if($userAcc)
                                    <div>
                                        <span class="account-badge-yes">
                                            <i class="fa-solid fa-check"></i> Aktif
                                        </span>
                                        <div style="font-size: 10.5px; color: var(--muted); font-weight: 600; margin-top: 4px;">{{ $userAcc->username }}</div>
                                    </div>
                                    <button type="button" class="btn-reset-acc" onclick="openResetModal({{ $mhs->id_mhs }}, '{{ addslashes($mhs->nama_lengkap) }}')">
                                        <i class="fa-solid fa-key"></i> Reset Pass
                                    </button>
                                @else
                                    <div>
                                        <span class="account-badge-no">
                                            <i class="fa-solid fa-xmark"></i> Belum Ada
                                        </span>
                                    </div>
                                    <button type="button" class="btn-create-acc" onclick="openCreateAccModal({{ $mhs->id_mhs }}, '{{ addslashes($mhs->nama_lengkap) }}', '{{ addslashes($mhs->nim) }}')">
                                        <i class="fa-solid fa-user-plus"></i> Buatkan Akun
                                    </button>
                                @endif
                            </td>
                            <td class="cell-action text-center">
                                <div class="action-group">
                                    <!-- Detail -->
                                    <a href="{{ route('superadmin.peserta.show', $mhs->id_mhs) }}" class="btn-action" style="background: #E0F2FE; color: #0284C7; border-color: #BAE6FD;" title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('superadmin.peserta.edit', $mhs->id_mhs) }}" class="btn-action btn-edit" title="Edit Peserta">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('superadmin.peserta.destroy', $mhs->id_mhs) }}" method="POST" data-confirm="Apakah Anda yakin ingin menghapus data peserta magang {{ $mhs->nama_lengkap }} beserta seluruh riwayat absensi dan logbooknya?" data-confirm-title="Hapus Data Permanen?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus Peserta">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fa-solid fa-users-slash" style="font-size: 40px; color: #CBD5E1; margin-bottom: 16px;"></i>
                                    <h4 style="margin: 0 0 8px 0; color: var(--text); font-weight: 700;">Data Peserta Tidak Ditemukan</h4>
                                    <p style="margin: 0; color: var(--muted); font-size: 13.5px;">Cobalah menyesuaikan filter pencarian atau hapus kata kunci pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 16px 24px;">
            {{ $mahasiswas->links() }}
        </div>
    </div>

    <!-- MODAL BUAT AKUN PESERTA -->
    <div id="modalBuatAkun" class="modal-overlay" onclick="closeModalOnBg(event, 'modalBuatAkun')">
        <div class="modal-card">
            <h3><i class="fa-solid fa-user-plus" style="color: var(--primary); margin-right: 6px;"></i> Buatkan Akun Portal</h3>
            <p>Akun ini akan langsung aktif dan dapat digunakan mahasiswa untuk login portal absensi & logbook harian.</p>

            <form id="formBuatAkun" method="POST" action="">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label class="modal-input-label">Nama Lengkap Mahasiswa</label>
                    <input type="text" id="modalMhsNama" class="modal-input" style="width: 100%; background: #F8FAFC; color: var(--muted); cursor: not-allowed;" readonly>
                </div>

                <div style="margin-bottom: 16px;">
                    <label class="modal-input-label">Username / Login ID</label>
                    <input type="text" id="modalMhsNim" class="modal-input" style="width: 100%; background: #F8FAFC; color: var(--muted); cursor: not-allowed;" readonly>
                    <small style="color: var(--muted); font-size: 11px; margin-top: 6px; display: block;"><i class="fa-solid fa-circle-info"></i> Username otomatis di-generate menggunakan NIM peserta.</small>
                </div>

                <div style="margin-bottom: 24px;">
                    <label class="modal-input-label">Tentukan Password Awal *</label>
                    <input type="text" name="password" class="modal-input" style="width: 100%; font-family: monospace; font-size: 15px; font-weight: 700; letter-spacing: 1px;" value="mhs123" placeholder="Minimal 6 karakter..." required minlength="6">
                    <small style="color: var(--success-text); font-size: 11px; margin-top: 6px; display: block; font-weight: 600;"><i class="fa-solid fa-check"></i> Saran password default: mhs123</small>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px dashed var(--page); padding-top: 20px;">
                    <button type="button" class="btn-cancel" onclick="closeModal('modalBuatAkun')">Batalkan</button>
                    <button type="submit" class="btn-modal-action">
                        <i class="fa-solid fa-paper-plane"></i> Daftarkan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL RESET PASSWORD -->
    <div id="modalResetPass" class="modal-overlay" onclick="closeModalOnBg(event, 'modalResetPass')">
        <div class="modal-card">
            <h3><i class="fa-solid fa-key" style="color: var(--primary); margin-right: 6px;"></i> Reset Password Akun</h3>
            <p>Masukkan password baru. Aksi ini akan menimpa password peserta yang sebelumnya.</p>

            <form id="formResetPass" method="POST" action="">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label class="modal-input-label">Nama Mahasiswa Pemilik Akun</label>
                    <input type="text" id="modalResetNama" class="modal-input" style="width: 100%; background: #F8FAFC; color: var(--muted); cursor: not-allowed;" readonly>
                </div>

                <div style="margin-bottom: 24px;">
                    <label class="modal-input-label">Password Baru (Pengganti) *</label>
                    <input type="text" name="new_password" class="modal-input" style="width: 100%; font-family: monospace; font-size: 15px; font-weight: 700; letter-spacing: 1px;" value="mhs123" placeholder="Minimal 6 karakter..." required minlength="6">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px dashed var(--page); padding-top: 20px;">
                    <button type="button" class="btn-cancel" onclick="closeModal('modalResetPass')">Batalkan</button>
                    <button type="submit" class="btn-modal-action" style="background: #D97706;">
                        <i class="fa-solid fa-rotate"></i> Terapkan Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // ==========================================
    // 1. GENERATOR CUSTOM DROPDOWN (UNTUK FILTER BAR)
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelects = document.querySelectorAll('select.custom-filter-select');

        filterSelects.forEach(select => {
            const wrapper = document.createElement('div');
            wrapper.className = 'custom-select-wrapper';

            // Ambil opsi yang saat ini aktif/selected
            const activeOption = select.options[select.selectedIndex];

            const trigger = document.createElement('div');
            trigger.className = `custom-select-trigger`;
            trigger.innerHTML = `<span>${activeOption.text}</span> <i class="fa-solid fa-chevron-down"></i>`;

            const optionsPanel = document.createElement('div');
            optionsPanel.className = 'custom-select-options';

            Array.from(select.options).forEach(option => {
                const optDiv = document.createElement('div');
                optDiv.className = 'custom-option';
                optDiv.textContent = option.text;
                if (option.selected) optDiv.classList.add('selected');

                // Ketika opsi diklik
                optDiv.addEventListener('click', (e) => {
                    e.stopPropagation();

                    // Update nilai select asli
                    select.value = option.value;

                    // Update UI Trigger
                    trigger.querySelector('span').textContent = option.text;
                    wrapper.classList.remove('open');

                    optionsPanel.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
                    optDiv.classList.add('selected');

                    // Submit otomatis saat filter berubah
                    if (select.form) {
                        // Sedikit delay agar user melihat animasi tombol dropdown terganti sebelum reload
                        setTimeout(() => select.form.submit(), 150);
                    }
                });

                optionsPanel.appendChild(optDiv);
            });

            wrapper.appendChild(trigger);
            wrapper.appendChild(optionsPanel);

            // Masukkan custom UI tepat setelah <select> asli
            select.parentNode.insertBefore(wrapper, select.nextSibling);

            // Buka/tutup dropdown
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.custom-select-wrapper').forEach(w => {
                    if(w !== wrapper) w.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });
        });

        // Tutup dropdown jika klik di luar area
        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-select-wrapper').forEach(w => w.classList.remove('open'));
        });
    });

    // ==========================================
    // 2. FUNGSI MODAL MANAJEMEN AKUN
    // ==========================================
    function openCreateAccModal(id, nama, nim) {
        document.getElementById('modalMhsNama').value = nama;
        document.getElementById('modalMhsNim').value = nim;
        document.getElementById('formBuatAkun').action = '/superadmin/peserta/' + id + '/buat-akun';
        const modal = document.getElementById('modalBuatAkun');
        modal.classList.add('active');
        // Kunci scrolling body saat modal terbuka
        document.body.style.overflow = 'hidden';
    }

    function openResetModal(id, nama) {
        document.getElementById('modalResetNama').value = nama;
        document.getElementById('formResetPass').action = '/superadmin/peserta/' + id + '/reset-password';
        const modal = document.getElementById('modalResetPass');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if(modal) {
            modal.classList.remove('active');
            // Kembalikan scrolling
            document.body.style.overflow = '';
        }
    }

    function closeModalOnBg(e, id) {
        // Jika klik tepat pada layer gelap (overlay), maka tutup
        if (e.target.classList.contains('modal-overlay')) {
            closeModal(id);
        }
    }
</script>
@endpush
