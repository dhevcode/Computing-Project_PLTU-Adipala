@extends('superadmin.layouts.app')

@section('title', 'Pembimbing Lapangan')
@section('page_title', 'Pembimbing Lapangan')
@section('page_subtitle', 'Pantau beban pembimbingan dan daftar mahasiswa yang magang bersama setiap mentor')

@push('styles')
<style>
    /* =========================================
       BADGE DAN TOMBOL KHUSUS
       ========================================= */
    .mentor-badge {
        background: #E0F2FE;
        color: #0369A1;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-view-mhs {
        background: var(--primary);
        color: #ffffff;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-view-mhs:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    /* =========================================
       MODAL MAHASISWA BIMBINGAN
       ========================================= */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
    }
    .modal-overlay.active { display: flex; }
    .modal-card {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 820px;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        animation: modalFadeIn 0.2s ease;
    }
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-header h3 {
        font-size: 17px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        color: var(--muted);
        cursor: pointer;
        padding: 4px;
        transition: color 0.2s;
    }
    .modal-close:hover { color: var(--error-text); }
    .modal-body { padding: 24px; overflow-y: auto; }

    .sub-table-wrapper { overflow-x: auto; }
    .sub-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .sub-table th {
        background: #F8FAFC;
        padding: 10px 14px;
        text-align: left;
        font-weight: 700;
        color: var(--muted);
        border-bottom: 1.5px solid var(--border);
        white-space: nowrap;
    }
    .sub-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
        white-space: nowrap;
    }

    /* =========================================
       RESPONSIVE (DESKTOP -> TABLET -> MOBILE)
       ========================================= */
    .stats-grid { grid-template-columns: repeat(3, 1fr); }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 760px) {
        /* Box stats turun ke bawah */
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }

        /* Modal disesuaikan untuk HP */
        .modal-overlay { padding: 12px; }
        .modal-card { max-height: 92vh; border-radius: 12px; }
        .modal-header { padding: 16px; }
        .modal-body { padding: 16px; }

        /* PERBAIKAN TABEL UTAMA (PEMBIMBING) */
        #pembimbingTable td[data-label] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: right;
            padding: 10px 0;
            white-space: normal;
        }
        #pembimbingTable td.cell-user {
            background: var(--page);
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        #pembimbingTable td[data-label]::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--muted);
            text-align: left;
            margin-right: 12px;
        }

        /* PERBAIKAN TABEL MODAL (MAHASISWA) MENJADI KARTU */
        .sub-table thead { display: none !important; }
        .sub-table, .sub-table tbody, .sub-table tr, .sub-table td {
            display: block;
            width: 100%;
        }
        .sub-table tr {
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 16px;
            padding: 14px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
        .sub-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #F1F5F9;
            text-align: right;
            white-space: normal;
        }
        .sub-table td:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .sub-table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--muted);
            font-size: 12.5px;
            text-align: left;
            flex-shrink: 0;
            margin-right: 12px;
        }

        /* Desain Khusus untuk Nama Mahasiswa di HP */
        .sub-table td.cell-mhs-name {
            display: block;
            text-align: left;
            background: var(--page);
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 12px;
            border-bottom: none;
        }
        .sub-table td.cell-mhs-name::before { display: none; }
        .sub-table td.cell-number-desktop { display: none; } /* Hilangkan nomor urut samping di HP */
    }
</style>
@endpush

@section('content')

    <!-- STATS WIDGETS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h4>Total Pembimbing Lapangan</h4>
                <div class="stat-value">{{ $totalPembimbing ?? 0 }}</div>
            </div>
            <div class="stat-icon primary">
                <i class="fa-solid fa-user-tie"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h4>Total Mahasiswa Dibimbing</h4>
                <div class="stat-value">{{ $totalMahasiswaDibimbing ?? 0 }}</div>
            </div>
            <div class="stat-icon success">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h4>Pembimbing Aktif</h4>
                <div class="stat-value">{{ $pembimbingAktif ?? 0 }}</div>
            </div>
            <div class="stat-icon warning">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="card">
        <div class="card-header">
            <div class="card-title-group">
                <h3>Data Pembimbing Lapangan & Mahasiswa</h3>
                <p>Daftar lengkap pembimbing beserta mahasiswa magang yang mereka bina.</p>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Cari pembimbing, divisi..." autocomplete="off">
            </div>
        </div>

        <div class="table-wrapper">
            <table id="pembimbingTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Pembimbing Lapangan</th>
                        <th>Username / NIP</th>
                        <th>Divisi / Bagian</th>
                        <th class="text-center">Beban Bimbingan</th>
                        <th>Status Akun</th>
                        <th class="text-center">Mahasiswa</th>
                        <th class="text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembimbingData as $index => $item)
                        @php
                            $mentor = $item['user'];
                            $mhsList = $item['daftar_bimbingan'];
                        @endphp
                        <tr class="data-row">
                            <td class="text-center row-number cell-number">{{ $index + 1 }}</td>
                            <td class="cell-user">
                                <div class="user-cell">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($mentor->nama_lengkap ?? 'P', 0, 1)) }}
                                    </div>
                                    <div class="user-info">
                                        <span class="name">{{ $mentor->nama_lengkap }}</span>
                                        <span class="email">{{ $mentor->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Username / NIP"><span class="code-tag">{{ $mentor->username }}</span></td>
                            <td data-label="Divisi / Bagian">
                                <span style="font-weight: 600;">{{ $mentor->divisi->nama_divisi ?? 'Belum Diatur' }}</span>
                            </td>
                            <td data-label="Beban Bimbingan" class="text-center">
                                <span class="mentor-badge">
                                    <i class="fa-solid fa-users"></i> {{ $item['total_mahasiswa'] }} Mahasiswa
                                </span>
                                @if($item['sedang_magang'] > 0)
                                    <div style="font-size: 11px; color: var(--success-text); font-weight: 600; margin-top: 4px;">
                                        {{ $item['sedang_magang'] }} Aktif Magang
                                    </div>
                                @endif
                            </td>
                            <td data-label="Status Akun">
                                @if($mentor->is_active)
                                    <span class="badge badge-active"><span class="badge-dot"></span> Aktif</span>
                                @else
                                    <span class="badge badge-inactive"><span class="badge-dot"></span> Nonaktif</span>
                                @endif
                            </td>
                            <td data-label="Mahasiswa" class="text-center">
                                @if($item['total_mahasiswa'] > 0)
                                    <button type="button" class="btn-view-mhs" onclick="showMhsModal({{ $mentor->id_users }})">
                                        <i class="fa-solid fa-list-check"></i>
                                        <span>Lihat Daftar ({{ $item['total_mahasiswa'] }})</span>
                                    </button>
                                @else
                                    <span style="font-size: 12px; color: var(--muted); font-style: italic;">Belum ada bimbingan</span>
                                @endif
                            </td>
                            <td class="cell-action text-center">
                                <div class="action-group">
                                    <a href="{{ route('superadmin.edit', $mentor->id_users) }}" class="btn-action btn-edit" title="Edit Akun Pembimbing">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- MODAL DAFTAR MAHASISWA -->
                        @if($item['total_mahasiswa'] > 0)
                            <div id="modal_mentor_{{ $mentor->id_users }}" class="modal-overlay" onclick="closeModalOnBg(event, {{ $mentor->id_users }})">
                                <div class="modal-card">
                                    <div class="modal-header">
                                        <h3>
                                            <i class="fa-solid fa-user-graduate" style="color: var(--primary);"></i>
                                            Mahasiswa Bimbingan: {{ $mentor->nama_lengkap }}
                                        </h3>
                                        <button type="button" class="modal-close" onclick="closeMhsModal({{ $mentor->id_users }})">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div style="margin-bottom: 16px; font-size: 13.5px; color: var(--text);">
                                            Divisi Penugasan: <strong>{{ $mentor->divisi->nama_divisi ?? '-' }}</strong> <br>
                                            Total Anak Bimbingan: <strong>{{ $item['total_mahasiswa'] }} Orang</strong>
                                        </div>

                                        <div class="sub-table-wrapper">
                                            <table class="sub-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 40px;">No</th>
                                                        <th>Nama Mahasiswa</th>
                                                        <th>NIM</th>
                                                        <th>Asal Kampus / Sekolah</th>
                                                        <th>Periode Magang</th>
                                                        <th class="text-center">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($mhsList as $idx => $p)
                                                        @php $m = $p->mahasiswa; @endphp
                                                        <tr>
                                                            <td class="cell-number-desktop" style="color: var(--muted); font-weight: 600;">{{ $idx + 1 }}</td>

                                                            <td class="cell-mhs-name">
                                                                <div style="font-size: 11px; font-weight: 700; color: var(--primary); margin-bottom: 4px; display: inline-block;" class="d-none d-md-block">MAHASISWA #{{ $idx + 1 }}</div>
                                                                <strong style="display:block; color: var(--text); font-size: 14px;">{{ $m->nama_lengkap ?? '-' }}</strong>
                                                                <small style="color: var(--muted); display:block; margin-top: 2px;">{{ $m->jurusan->nama_jurusan ?? '-' }}</small>
                                                            </td>

                                                            <td data-label="NIM / Username"><span class="code-tag">{{ $m->nim ?? '-' }}</span></td>

                                                            <td data-label="Asal Kampus">{{ $m->universitas->nama_universitas ?? '-' }}</td>

                                                            <td data-label="Periode Magang" style="font-size: 12.5px;">
                                                                {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }} <br> s/d {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}
                                                            </td>

                                                            <td data-label="Status Magang" class="text-center">
                                                                @if($p->status === 'Sedang Magang')
                                                                    <span class="badge badge-active">Sedang Magang</span>
                                                                @elseif($p->status === 'Akan Magang')
                                                                    <span class="badge" style="background: #EEF2FF; color: #4338CA;">Akan Magang</span>
                                                                @else
                                                                    <span class="badge badge-inactive">Selesai</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fa-solid fa-user-slash"></i>
                                    <p>Belum ada data pembimbing lapangan terdaftar dalam sistem.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="noResultRow" class="d-none">
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <p>Tidak ada data pembimbing yang cocok dengan pencarian.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function showMhsModal(id) {
        const modal = document.getElementById('modal_mentor_' + id);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Kunci scroll di background
        }
    }

    function closeMhsModal(id) {
        const modal = document.getElementById('modal_mentor_' + id);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = ''; // Kembalikan scroll
        }
    }

    function closeModalOnBg(e, id) {
        if (e.target.classList.contains('modal-overlay')) closeMhsModal(id);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchInput');
        const rows = document.querySelectorAll('#pembimbingTable tr.data-row');
        const noResult = document.getElementById('noResultRow');

        if (input) {
            input.addEventListener('input', function () {
                const q = input.value.trim().toLowerCase();
                let visibleCount = 0;
                rows.forEach(function (row) {
                    const match = row.textContent.toLowerCase().includes(q);
                    row.classList.toggle('d-none', !match);
                    if (match) visibleCount++;
                });
                if (noResult) noResult.classList.toggle('d-none', !(rows.length > 0 && visibleCount === 0));
            });
        }
    });
</script>
@endpush
