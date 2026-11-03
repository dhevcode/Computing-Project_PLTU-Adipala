@extends('pembimbing.layouts.app')

@section('title', 'Data Peserta Bimbingan')
@section('page_title', 'Daftar Peserta Magang yang Dibimbing')
@section('page_subtitle', 'Kelola, monitor perkembangan harian, dan evaluasi mahasiswa magang bimbingan Anda.')

@push('styles')
<style>
    /* ================= CARDS & LAYOUT ================= */
    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); padding: 24px; }
    
    .card-header-top { 
        display: flex; justify-content: space-between; align-items: center; 
        flex-wrap: wrap; gap: 16px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--page);
    }
    .card-title-wrap { display: flex; align-items: center; gap: 16px; flex: 1; }
    .icon-circle-box { 
        background: var(--primary-soft); color: var(--primary); font-size: 20px; 
        width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; 
    }
    .card-title-wrap h3 { font-size: 16.5px; font-weight: 700; color: var(--text); margin: 0 0 4px 0; line-height: 1.2; }
    .card-title-wrap p { font-size: 12.5px; color: var(--muted); margin: 0; line-height: 1.4; }

    /* ================= FILTER GRID ================= */
    .filter-grid { display: grid; grid-template-columns: 2fr 1.5fr auto auto; gap: 16px; align-items: end; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--page); }
    .form-group { margin-bottom: 0; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; }
    .input-box { width: 100%; padding: 10px 0; font-family: inherit; font-size: 13.5px; color: var(--text); background: transparent; border: none; border-bottom: 1.5px solid var(--border); outline: none; transition: border-color .2s; border-radius: 0; }
    .input-box:focus { border-bottom: 2px solid var(--primary); }

    /* ================= BADGES & BUTTONS ================= */
    .status-badge { padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; border: 1px solid var(--border); display: inline-flex; align-items: center; justify-content: center; }
    .badge-present { background: var(--success-bg); color: var(--success-text); border-color: #A7F3D0; }
    .badge-pending { background: #FEF3C7; color: #B45309; border-color: #FDE68A; }
    .badge-draft { background: #F1F5F9; color: #475569; }

    .btn-sm { padding: 8px 14px; font-size: 12.5px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; transition: 0.2s; white-space: nowrap; height: fit-content; }
    .btn-outline { background: #F8FAFC; color: #475569; border: 1px solid var(--border); }
    .btn-outline:hover { background: #E2E8F0; color: var(--text); }
    .btn-primary { background: var(--primary); color: #fff; border: 1px solid var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
    
    .btn-action { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; border: none; cursor: pointer; transition: 0.2s; font-size: 14px; text-decoration: none; }
    .btn-action.secondary { background: #F1F5F9; color: #475569; border: 1px solid var(--border); }
    .btn-action.secondary:hover { background: #E2E8F0; color: var(--text); }
    .btn-action.primary { background: var(--primary-soft); color: var(--primary); border: 1px solid var(--border); }
    .btn-action.primary:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

    /* ================= TABLE DESKTOP ================= */
    .table-wrapper { width: 100%; overflow-x: auto; border-radius: 12px; border: 1px solid var(--page); }
    .custom-table { width: 100%; border-collapse: collapse; min-width: 900px; }
    .custom-table th, .custom-table td { padding: 16px 20px; text-align: left; vertical-align: top; border-bottom: 1px solid var(--page); }
    .custom-table th { background: #F8FAFC; font-size: 12.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid var(--border); }
    .custom-table td { font-size: 13px; color: var(--text); line-height: 1.5; }
    .custom-table tbody tr:hover { background: #F9FAFB; }

    /* ================= RESPONSIVE MOBILE (CARD VIEW) ================= */
    @media (max-width: 992px) {
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .filter-grid { grid-template-columns: 1fr; }
        
        .table-wrapper { border: none; overflow-x: visible; }
        .custom-table { min-width: 100%; border: none; }
        .custom-table thead { display: none; }
        
        /* Merubah setiap baris tabel menjadi kartu */
        .custom-table tbody tr { 
            display: block; 
            border: 1px solid var(--border); 
            border-radius: 16px; 
            margin-bottom: 20px; 
            padding: 0; /* Padding diatur di dalam sel */
            background: #fff; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.04); 
            position: relative;
        }
        .custom-table tbody tr:hover { background: #fff; }

        /* Sel Nomor (Opsional, ditambahkan sebagai pseudo-element counter) */
        .custom-table { counter-reset: rowNumber; }
        .custom-table tbody tr { counter-increment: rowNumber; }
        
        /* Sel Profil Mahasiswa (Menjadi Header Kartu) */
        .custom-table tbody td.cell-user { 
            display: block; 
            padding: 16px 20px; 
            background: #F8FAFC; 
            border-radius: 16px 16px 0 0; 
            border-bottom: 1px solid var(--border); 
        }
        .custom-table tbody td.cell-user::before { display: none; }
        .custom-table tbody td.cell-user .user-header { display: flex; align-items: center; justify-content: space-between; }
        .custom-table tbody td.cell-user .user-number { font-size: 14px; font-weight: 700; color: var(--muted); }
        .custom-table tbody td.cell-user .user-number::before { content: "No. " counter(rowNumber); }

        /* Pengaturan semua sel lainnya (Data List) */
        .custom-table tbody td:not(.cell-user):not(.cell-action) { 
            display: flex; 
            align-items: flex-start; /* Sejajarkan ke atas */
            padding: 12px 20px; 
            border-bottom: 1px dashed var(--page); 
        }
        .custom-table tbody td:not(.cell-user):not(.cell-action)::before { 
            content: attr(data-label); 
            font-weight: 600; 
            color: var(--muted); 
            width: 130px; /* Lebar tetap untuk label di kiri */
            flex-shrink: 0;
            font-size: 12.5px;
        }
        .custom-table tbody td:not(.cell-user):not(.cell-action) .cell-content {
            flex: 1;
            text-align: right; /* Teks data rata kanan atau kiri tergantung selera */
            word-wrap: break-word; 
            overflow-wrap: break-word;
        }

        /* Khusus Kontak agar rata kanan dan turun baris dengan rapi */
        .custom-table tbody td.cell-kontak .cell-content { text-align: right; }
        .custom-table tbody td.cell-kampus .cell-content { text-align: right; }

        /* Sel Aksi (Tombol di bagian bawah kartu) */
        .custom-table tbody td.cell-action { 
            display: flex; 
            justify-content: flex-end; 
            gap: 10px; 
            padding: 16px 20px; 
            background: #fff; 
            border-radius: 0 0 16px 16px; 
            border-bottom: none;
        }
        .custom-table tbody td.cell-action::before { display: none; }
        
        .btn-sm { width: 100%; justify-content: center; margin-top: 8px; }
    }
</style>
@endpush

@section('content')

<div class="card animate-fade-up">
    
    <!-- HEADER -->
    <div class="card-header-top">
        <div class="card-title-wrap">
            <div class="icon-circle-box">
                <i class="fa-solid fa-users-gear"></i>
            </div>
            <div>
                <h3>Database Mahasiswa Bimbingan</h3>
                <p>Seluruh daftar mahasiswa yang terploting pada pembimbingan Anda.</p>
            </div>
        </div>
    </div>

    <!-- FILTER -->
    <form method="GET" action="{{ route('pembimbing.mahasiswa') }}" class="filter-grid">
        <div class="form-group">
            <label>Pencarian</label>
            <input type="text" name="search" class="input-box" placeholder="Cari nama mahasiswa atau NIM..." value="{{ request('search') }}">
        </div>

        <div class="form-group">
            <label>Status Magang</label>
            <select name="status" class="d-none custom-filter-select">
                <option value="">Semua Status Magang</option>
                <option value="Sedang Magang" {{ request('status') === 'Sedang Magang' ? 'selected' : '' }}>Sedang Magang</option>
                <option value="Akan Magang" {{ request('status') === 'Akan Magang' ? 'selected' : '' }}>Akan Magang</option>
                <option value="Selesai Magang" {{ request('status') === 'Selesai Magang' ? 'selected' : '' }}>Selesai Magang</option>
            </select>
        </div>

        <div class="form-group">
            <button type="submit" class="btn-sm btn-primary" style="height: 42px; width: 100%;">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </div>

        <div class="form-group">
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('pembimbing.mahasiswa') }}" class="btn-sm btn-outline" style="height: 42px; width: 100%; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            @endif
        </div>
    </form>

    <!-- TABLE -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Peserta Mahasiswa</th>
                    <th style="width: 20%;">Institusi & Penempatan</th>
                    <th style="width: 20%;">Kontak & Darurat</th>
                    <th style="width: 15%;">Periode Magang</th>
                    <th style="width: 10%; text-align: center;">Status</th>
                    <th style="width: 10%; text-align: center;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas ?? [] as $mhs)
                    @php
                        $pengajuan = $mhs->latestPengajuan ?? null;
                    @endphp
                    <tr>
                        <!-- HEADER KARTU (MOBILE) / KOLOM NAMA (DESKTOP) -->
                        <td class="cell-user" data-label="Mahasiswa">
                            <div class="user-header">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--primary-soft); color: var(--primary-dark); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px; flex-shrink: 0;">
                                        {{ strtoupper(substr($mhs->nama_lengkap ?? 'M', 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong style="font-size: 14px; color: var(--text); display: block;">{{ $mhs->nama_lengkap ?? '-' }}</strong>
                                        <small style="color: var(--muted); font-weight: 500;">NIM: <span style="background: transparent; padding: 0;" class="code-tag">{{ $mhs->nim ?? '-' }}</span></small>
                                    </div>
                                </div>
                                <div class="user-number" style="display: none;"></div> <!-- Muncul hanya di mobile via CSS content -->
                            </div>
                        </td>
                        
                        <td class="cell-kampus" data-label="Kampus">
                            <div class="cell-content">
                                <strong style="color: var(--text); display: block; margin-bottom: 2px;">{{ $mhs->universitas->nama_universitas ?? '-' }}</strong>
                                <span style="color: var(--muted); display: block; margin-bottom: 8px;">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</span>
                                <span style="display: inline-block; background: #E0F2FE; color: #0284C7; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;">
                                    Divisi: {{ $pengajuan->divisi->nama_divisi ?? '-' }}
                                </span>
                            </div>
                        </td>
                        
                        <td class="cell-kontak" data-label="Kontak">
                            <div class="cell-content" style="color: var(--text);">
                                <div style="margin-bottom: 4px;"><i class="fa-solid fa-phone" style="color: var(--primary); width: 16px;"></i> {{ $mhs->no_telepon ?: '-' }}</div>
                                <div style="margin-bottom: 8px;"><i class="fa-regular fa-envelope" style="color: var(--primary); width: 16px;"></i> {{ $mhs->email ?: '-' }}</div>
                                @if($mhs->emergency_contact_phone)
                                    <div style="color: var(--error-text); font-weight: 600; font-size: 12px;">
                                        <i class="fa-solid fa-truck-medical"></i> Wali ({{ $mhs->emergency_contact_name ?? 'Darurat' }}): <br>{{ $mhs->emergency_contact_phone }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        
                        <td data-label="Periode">
                            <div class="cell-content">
                                @if($pengajuan && $pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai)
                                    <strong style="color: var(--text); display: block;">{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }}</strong>
                                    <span style="color: var(--muted); font-size: 12px;">s/d {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}</span>
                                @else
                                    <span style="color: var(--muted);">-</span>
                                @endif
                            </div>
                        </td>
                        
                        <td data-label="Status" style="text-align: center;">
                            <div class="cell-content" style="text-align: inherit;">
                                @if($pengajuan && $pengajuan->status === 'Sedang Magang')
                                    <span class="status-badge badge-present">Sedang Magang</span>
                                @elseif($pengajuan && $pengajuan->status === 'Akan Magang')
                                    <span class="status-badge badge-pending">Akan Magang</span>
                                @else
                                    <span class="status-badge badge-draft">Selesai Magang</span>
                                @endif
                            </div>
                        </td>
                        
                        <td class="cell-action" data-label="Aksi" style="text-align: center;">
                            <div class="cell-content" style="display: inline-flex; gap: 6px; justify-content: center;">
                                <a href="{{ route('pembimbing.mahasiswa.detail', $mhs->id_mhs ?? 0) }}" class="btn-action secondary" title="Profil Lengkap">
                                    <i class="fa-regular fa-id-card"></i>
                                </a>
                                <a href="{{ route('pembimbing.absensi', ['search' => $mhs->nim ?? '']) }}" class="btn-action secondary" title="Cek Presensi">
                                    <i class="fa-solid fa-location-dot"></i>
                                </a>
                                <a href="{{ route('pembimbing.logbook', ['search' => $mhs->nim ?? '']) }}" class="btn-action secondary" title="Lihat Logbook">
                                    <i class="fa-solid fa-book-open"></i>
                                </a>
                                <a href="{{ route('pembimbing.nilai', ['search' => $mhs->nim ?? '']) }}" class="btn-action primary" title="Beri Nilai Evaluasi">
                                    <i class="fa-solid fa-star"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state" style="text-align: center; padding: 40px;">
                                <i class="fa-solid fa-users-slash" style="font-size: 36px; color: var(--muted); margin-bottom: 12px;"></i>
                                <p style="color: var(--muted);">Tidak ditemukan data mahasiswa bimbingan yang sesuai.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(($mahasiswas ?? null) && $mahasiswas->hasPages())
        <div class="pagination-wrapper" style="margin-top: 20px;">
            {{ $mahasiswas->links() }}
        </div>
    @endif
</div>
@endsection