@extends('superadmin.layouts.app')

@section('title', 'Pembimbing Lapangan')
@section('page_title', 'Pembimbing Lapangan')
@section('page_subtitle', 'Pantau data pembimbing lapangan dan daftar mahasiswa magang yang mereka bina')

@push('styles')
<style>
    /* =========================================
       ANIMASI & TRANSISI (MENGHILANGKAN KAKU)
       ========================================= */
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* Animasi Grid Statistik & Hover Efek */
    .stats-grid { animation: fadeIn 0.4s ease-out; }

    .stat-card {
        animation: fadeInUp 0.5s ease-out both;
        transition: transform 0.3s ease, box-shadow 0.3s ease !important;
        will-change: transform, opacity;
    }
    .stats-grid .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stats-grid .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stats-grid .stat-card:nth-child(3) { animation-delay: 0.3s; }

    .stat-card:hover {
        transform: translateY(-6px) !important;
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
    }

    /* Animasi Kartu Tabel Utama */
    .card {
        animation: fadeInUp 0.5s ease-out both;
    }
    .card:nth-of-type(1) { animation-delay: 0.3s; }
    .card:nth-of-type(2) { animation-delay: 0.4s; }

    /* Animasi Baris Tabel Muncul Bergantian */
    .data-row-pembimbing, .data-row-mahasiswa {
        animation: fadeIn 0.4s ease-out both;
        transition: all 0.2s ease;
    }

    /* Delay untuk 5 baris pertama agar terlihat efek kaskade */
    tbody tr:nth-child(1) { animation-delay: 0.4s; }
    tbody tr:nth-child(2) { animation-delay: 0.45s; }
    tbody tr:nth-child(3) { animation-delay: 0.5s; }
    tbody tr:nth-child(4) { animation-delay: 0.55s; }
    tbody tr:nth-child(5) { animation-delay: 0.6s; }

    .data-row-pembimbing:hover, .data-row-mahasiswa:hover {
        background-color: #F8FAFC !important;
        transform: scale(1.002);
    }

    /* Animasi Tombol Aksi */
    .btn-action { transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .btn-action:hover { transform: translateY(-3px) scale(1.1); }

    /* Animasi Search Box */
    .search-box input { transition: all 0.3s ease; }
    .search-box input:focus {
        box-shadow: 0 0 0 3px var(--primary-soft, rgba(10, 95, 99, 0.15));
        transform: translateY(-1px);
    }

    /* =========================================
       BADGE KHUSUS MENTOR
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

    /* =========================================
       RESPONSIVE (DESKTOP -> TABLET -> MOBILE)
       ========================================= */
    .stats-grid { grid-template-columns: repeat(3, 1fr); }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }

        /* Responsive Tabel Pembimbing & Mahasiswa */
        #pembimbingTable td[data-label],
        #mahasiswaTable td[data-label] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: right;
            padding: 10px 0;
            white-space: normal;
        }
        #pembimbingTable td.cell-user,
        #mahasiswaTable td.cell-user {
            background: var(--page);
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        #pembimbingTable td[data-label]::before,
        #mahasiswaTable td[data-label]::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--muted);
            text-align: left;
            margin-right: 12px;
        }
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

    <!-- TABEL 1: DATA PEMBIMBING LAPANGAN -->
    <div class="card">
        <div class="card-header">
            <div class="card-title-group">
                <h3>Data Pembimbing Lapangan</h3>
                <p>Daftar lengkap pembimbing lapangan beserta divisi penugasan dan beban bimbingan.</p>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchPembimbing" placeholder="Cari pembimbing, NIP, divisi..." autocomplete="off">
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
                        <th class="text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembimbingData as $index => $item)
                        @php
                            $mentor = $item['user'];
                        @endphp
                        <tr class="data-row-pembimbing">
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
                            <td class="cell-action text-center">
                                <div class="action-group">
                                    <a href="{{ route('superadmin.edit', $mentor->id_users) }}" class="btn-action btn-edit" title="Edit Akun Pembimbing">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fa-solid fa-user-slash"></i>
                                    <p>Belum ada data pembimbing lapangan terdaftar dalam sistem.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="noResultPembimbing" class="d-none">
                        <td colspan="7">
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

    <!-- TABEL 2: DATA MAHASISWA BIMBINGAN (TERPISAH DI BAWAH) -->
    <div class="card" style="margin-top: 24px;">
        <div class="card-header">
            <div class="card-title-group">
                <h3>Data Mahasiswa Bimbingan</h3>
                <p>Daftar seluruh mahasiswa magang beserta pembimbing lapangan yang membina.</p>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchMahasiswa" placeholder="Cari nama, NIM, kampus, pembimbing..." autocomplete="off">
            </div>
        </div>

        <div class="table-wrapper">
            <table id="mahasiswaTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Asal Kampus / Sekolah</th>
                        <th>Divisi / Bagian</th>
                        <th>Pembimbing Lapangan</th>
                        <th>Periode Magang</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarMahasiswa as $idx => $p)
                        @php
                            $m = $p->mahasiswa;
                            $mentorName = $p->pembimbing->nama_lengkap ?? ($p->nama_pembimbing ?? 'Belum Ditentukan');
                        @endphp
                        <tr class="data-row-mahasiswa">
                            <td class="text-center row-number cell-number">{{ $idx + 1 }}</td>
                            <td class="cell-user">
                                <div class="user-cell">
                                    <div class="avatar-circle" style="background: linear-gradient(135deg, #10B981, #059669);">
                                        {{ strtoupper(substr($m->nama_lengkap ?? 'M', 0, 1)) }}
                                    </div>
                                    <div class="user-info">
                                        <span class="name">{{ $m->nama_lengkap ?? '-' }}</span>
                                        <span class="email">{{ $m->jurusan->nama_jurusan ?? ($m->email ?? '-') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="NIM"><span class="code-tag">{{ $m->nim ?? '-' }}</span></td>
                            <td data-label="Asal Kampus">{{ $m->universitas->nama_universitas ?? '-' }}</td>
                            <td data-label="Divisi / Bagian">
                                <span style="font-weight: 600;">{{ $p->divisi->nama_divisi ?? '-' }}</span>
                            </td>
                            <td data-label="Pembimbing Lapangan">
                                <span class="mentor-badge">
                                    <i class="fa-solid fa-user-tie"></i> {{ $mentorName }}
                                </span>
                            </td>
                            <td data-label="Periode Magang" style="font-size: 12.5px;">
                                @if($p->tanggal_mulai && $p->tanggal_selesai)
                                    {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }}
                                    <br><span style="color: var(--muted); font-size: 11px;">s/d</span>
                                    {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}
                                @else
                                    <span style="color: var(--muted);">-</span>
                                @endif
                            </td>
                            <td data-label="Status" class="text-center">
                                @if($p->status === 'Sedang Magang')
                                    <span class="badge badge-active"><span class="badge-dot"></span> Sedang Magang</span>
                                @elseif($p->status === 'Akan Magang')
                                    <span class="badge" style="background: #EEF2FF; color: #4338CA;"><span class="badge-dot" style="background: #4338CA;"></span> Akan Magang</span>
                                @else
                                    <span class="badge badge-inactive"><span class="badge-dot"></span> Selesai</span>
                                @endif
                            </td>
                            <td class="cell-action text-center">
                                <div class="action-group">
                                    @if($m && $m->id_mhs)
                                        <a href="{{ route('superadmin.peserta.show', $m->id_mhs) }}" class="btn-action" style="background: #E0F2FE; color: #0284C7;" title="Lihat Detail Mahasiswa">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    @else
                                        <span style="color: var(--muted); font-size: 11px;">-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fa-solid fa-user-graduate"></i>
                                    <p>Belum ada mahasiswa bimbingan yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="noResultMahasiswa" class="d-none">
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <p>Tidak ada data mahasiswa yang cocok dengan pencarian.</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Search Filter untuk Tabel Pembimbing Lapangan
        const inputPembimbing = document.getElementById('searchPembimbing');
        const rowsPembimbing = document.querySelectorAll('#pembimbingTable tr.data-row-pembimbing');
        const noResultPembimbing = document.getElementById('noResultPembimbing');

        if (inputPembimbing) {
            inputPembimbing.addEventListener('input', function () {
                const q = inputPembimbing.value.trim().toLowerCase();
                let visibleCount = 0;
                rowsPembimbing.forEach(function (row) {
                    const match = row.textContent.toLowerCase().includes(q);
                    row.classList.toggle('d-none', !match);
                    if (match) visibleCount++;
                });
                if (noResultPembimbing) noResultPembimbing.classList.toggle('d-none', !(rowsPembimbing.length > 0 && visibleCount === 0));
            });
        }

        // Search Filter untuk Tabel Mahasiswa Bimbingan
        const inputMahasiswa = document.getElementById('searchMahasiswa');
        const rowsMahasiswa = document.querySelectorAll('#mahasiswaTable tr.data-row-mahasiswa');
        const noResultMahasiswa = document.getElementById('noResultMahasiswa');

        if (inputMahasiswa) {
            inputMahasiswa.addEventListener('input', function () {
                const q = inputMahasiswa.value.trim().toLowerCase();
                let visibleCount = 0;
                rowsMahasiswa.forEach(function (row) {
                    const match = row.textContent.toLowerCase().includes(q);
                    row.classList.toggle('d-none', !match);
                    if (match) visibleCount++;
                });
                if (noResultMahasiswa) noResultMahasiswa.classList.toggle('d-none', !(rowsMahasiswa.length > 0 && visibleCount === 0));
            });
        }
    });
</script>
@endpush
