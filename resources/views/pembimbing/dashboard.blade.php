@extends('pembimbing.layouts.app')

@section('title', 'Dashboard Pembimbing')
@section('page_title', 'Dashboard Pembimbing Lapangan')
@section('page_subtitle', 'Selamat datang kembali! Pantau aktivitas dan progres bimbingan mahasiswa PKL.')

@push('styles')
<style>
    /* ================= STATS GRID (KONSISTEN DENGAN ADMIN) ================= */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: var(--bg); padding: 24px; border-radius: 16px; 
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); 
        display: flex; align-items: center; justify-content: space-between; gap: 20px; 
        transition: transform 0.2s, box-shadow 0.2s; 
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
    
    .stat-info { flex: 1; text-align: left; }
    .stat-info span { font-size: 13px; font-weight: 600; color: var(--muted); display: block; margin-bottom: 4px; }
    .stat-info h2 { font-size: 32px; font-weight: 800; color: var(--text); margin: 0 0 2px 0; line-height: 1; }

    /* Ikon Bulat Solid di Kanan */
    .stat-icon { 
        width: 60px; height: 60px; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; 
        font-size: 26px; flex-shrink: 0; 
    }
    
    .icon-teal { background: var(--primary-soft); color: var(--primary); }
    .icon-yellow { background: #FFFBEB; color: #D97706; }
    .icon-blue { background: #EFF6FF; color: #2563EB; }
    .icon-green { background: #ECFDF5; color: #059669; }

    /* ================= CARDS & TABLE ================= */
    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; padding: 24px; }
    
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

    .btn-sm { padding: 8px 14px; font-size: 12.5px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; transition: 0.2s; white-space: nowrap; height: fit-content; }
    .btn-primary { background: var(--primary); color: #fff; border: 1px solid var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
    .btn-outline { background: #F8FAFC; color: #475569; border: 1px solid var(--border); }
    .btn-outline:hover { background: #E2E8F0; color: var(--text); }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        
        /* Tabel Mobile - Fix Teks Terpotong */
        table, tbody, tr, td { display: block; width: 100%; }
        thead { display: none; }
        tbody tr { border: 1px solid var(--border); border-radius: 12px; margin-bottom: 16px; padding: 16px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        tbody td { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #F1F5F9; text-align: right; word-wrap: break-word; overflow-wrap: break-word; }
        tbody td:last-child { border-bottom: none; padding-bottom: 0; }
        tbody td[data-label]::before { content: attr(data-label); font-weight: 600; color: var(--muted); text-align: left; flex-shrink: 0; margin-right: 12px; font-size: 12.5px; }
        
        tbody td.cell-user { display: block; text-align: left; background: var(--page); padding: 14px; border-radius: 8px; margin-bottom: 12px; border-bottom: none; }
        tbody td.cell-user::before { display: none; }
        
        tbody td.cell-action { display: grid; grid-template-columns: 1fr; gap: 8px; padding-top: 16px; margin-top: 8px; }
        tbody td.cell-action::before { display: none; }
        tbody td.cell-action .btn-sm { width: 100%; justify-content: center; }
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

    <!-- NOTIFIKASI PENDING VERIFIKASI SEPERTI ADMIN (OPSIONAL & DINAMIS) -->
    @php
        // Total keseluruhan yang butuh verifikasi
        $totalPending = ($pendingLogbookCount ?? 0) + ($pendingCorrectionCount ?? 0) + ($pendingLeaveCount ?? 0);
    @endphp

    @if($totalPending > 0)
        <div class="card animate-fade-up" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); border: 1.5px solid #F59E0B; margin-bottom: 24px; padding: 20px 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; width: 100%;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 46px; height: 46px; border-radius: 12px; background: #F59E0B; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <strong style="color: #92400E; font-size: 15px; display: block; margin-bottom: 4px;">{{ $totalPending }} Tugas Menunggu Verifikasi Anda!</strong>
                        <span style="color: #B45309; font-size: 13px;">Terdapat pengajuan logbook, absensi, atau cuti dari mahasiswa yang butuh ditinjau.</span>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @if(($pendingLogbookCount ?? 0) > 0)
                        <a href="{{ route('pembimbing.logbook') }}" class="btn-sm btn-outline" style="background: #fff; border-color: #F59E0B; color: #B45309;"><i class="fa-solid fa-book-open"></i> Tinjau Logbook</a>
                    @endif
                    @if(($pendingCorrectionCount ?? 0) > 0)
                        <a href="{{ route('pembimbing.absensi') }}" class="btn-sm btn-outline" style="background: #fff; border-color: #F59E0B; color: #B45309;"><i class="fa-solid fa-clipboard-user"></i> Tinjau Absensi</a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- STATS GRID -->
    <div class="stats-grid animate-fade-up delay-1">
        <div class="stat-card">
            <div class="stat-info">
                <span>Mahasiswa Bimbingan</span>
                <h2>{{ str_pad($totalMahasiswaBimbingan ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-teal"><i class="fa-solid fa-users-rectangle"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Logbook Pending</span>
                <h2>{{ str_pad($pendingLogbookCount ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-yellow"><i class="fa-solid fa-hourglass-half"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Koreksi Absensi</span>
                <h2>{{ str_pad($pendingCorrectionCount ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-blue"><i class="fa-solid fa-clipboard-user"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Pengajuan Cuti</span>
                <h2>{{ str_pad($pendingLeaveCount ?? 0, 2, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="stat-icon icon-green"><i class="fa-solid fa-calendar-check"></i></div>
        </div>
    </div>

    <!-- DAFTAR MAHASISWA BIMBINGAN AKTIF -->
    <div class="card animate-fade-up delay-2">
        <div class="card-header-top">
            <div class="card-title-wrap">
                <div class="icon-circle-box">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div>
                    <h3>Mahasiswa Bimbingan Aktif</h3>
                    <p>Daftar peserta magang yang berada di bawah bimbingan lapangan Anda.</p>
                </div>
            </div>
            <a href="{{ route('pembimbing.mahasiswa') }}" class="btn-sm btn-primary">
                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Mahasiswa & NIM</th>
                        <th>Universitas & Prodi</th>
                        <th>Divisi Penempatan</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas ?? [] as $mhs)
                        <tr>
                            <td class="cell-user">
                                <strong style="color: var(--text); display: block;">{{ $mhs->nama_lengkap ?? '-' }}</strong>
                                <small style="color: var(--muted);">NIM: <span class="code-tag" style="background: transparent; padding: 0;">{{ $mhs->nim ?? '-' }}</span></small>
                            </td>
                            <td data-label="Kampus">
                                <strong style="color: var(--text);">{{ $mhs->universitas->nama_universitas ?? '-' }}</strong><br>
                                <small style="color: var(--muted);">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</small>
                            </td>
                            <td data-label="Divisi" style="font-weight: 600; color: var(--primary);">
                                {{ $mhs->divisi->nama_divisi ?? '-' }}
                            </td>
                            <td class="cell-action" style="text-align: center;">
                                <a href="{{ route('pembimbing.mahasiswa.detail', $mhs->id_mhs ?? 0) }}" class="btn-sm btn-primary">
                                    <i class="fa-regular fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fa-solid fa-user-slash"></i>
                                    <p>Belum ada mahasiswa bimbingan yang terploting untuk Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection