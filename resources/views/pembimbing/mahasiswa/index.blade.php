@extends('pembimbing.layout')

@section('title', 'Data Peserta Bimbingan')

@section('content')
<div class="page-header-card">
    <div class="page-header-content">
        <div class="page-header-icon">
            <i class="fa-solid fa-users-gear"></i>
        </div>
        <div>
            <h1>Daftar Peserta Magang yang Dibimbing</h1>
            <p>Kelola, monitor perkembangan harian, dan evaluasi mahasiswa magang yang berada di bawah bimbingan lapangan Anda.</p>
        </div>
    </div>
    <span class="badge-mentor-scope">
        <i class="fa-solid fa-user-shield"></i>
        Bimbingan Saya
    </span>
</div>

<div class="card-box">
    <!-- Filter Bar -->
    <form method="GET" action="{{ route('pembimbing.mahasiswa') }}" class="filter-bar">
        <div style="flex: 1; min-width: 240px;">
            <input type="text" name="search" class="form-control" style="width: 100%;" placeholder="Cari nama mahasiswa atau NIM..." value="{{ request('search') }}">
        </div>

        <div>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Status Magang --</option>
                <option value="Sedang Magang" {{ request('status') === 'Sedang Magang' ? 'selected' : '' }}>Sedang Magang</option>
                <option value="Akan Magang" {{ request('status') === 'Akan Magang' ? 'selected' : '' }}>Akan Magang</option>
                <option value="Selesai Magang" {{ request('status') === 'Selesai Magang' ? 'selected' : '' }}>Selesai Magang</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-magnifying-glass"></i> Cari
        </button>

        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('pembimbing.mahasiswa') }}" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-left"></i> Reset
            </a>
        @endif
    </form>

    <!-- Tabel Data Mahasiswa Bimbingan -->
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Peserta Mahasiswa</th>
                    <th>Institusi / Universitas</th>
                    <th>Kontak & Darurat</th>
                    <th>Periode Magang</th>
                    <th>Status</th>
                    <th style="text-align: center;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $mhs)
                    @php
                        $pengajuan = $mhs->latestPengajuan;
                    @endphp
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #0A5F63, #0D8288); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">
                                    {{ strtoupper(substr($mhs->nama_lengkap, 0, 1)) }}
                                </div>
                                <div>
                                    <strong style="font-size: 14px; color: var(--text-dark);">{{ $mhs->nama_lengkap }}</strong><br>
                                    <small style="color: var(--text-muted); font-weight: 500;">NIM: {{ $mhs->nim }} • {{ $mhs->jenis_kelamin }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong>{{ $mhs->universitas->nama_universitas ?? '-' }}</strong><br>
                            <small style="color: var(--text-muted);">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</small><br>
                            <span class="badge" style="background: #E0F2FE; color: #0284C7; margin-top: 4px;">
                                Divisi: {{ $pengajuan->divisi->nama_divisi ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 12.5px;">
                                <i class="fa-solid fa-phone" style="color: #0A5F63; width: 14px;"></i> {{ $mhs->no_telepon ?: '-' }}<br>
                                <i class="fa-regular fa-envelope" style="color: #0A5F63; width: 14px;"></i> {{ $mhs->email ?: '-' }}<br>
                                @if($mhs->emergency_contact_phone)
                                    <small style="color: #DC2626;">
                                        <i class="fa-solid fa-truck-medical"></i> {{ $mhs->emergency_contact_name }}: {{ $mhs->emergency_contact_phone }}
                                    </small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($pengajuan)
                                <strong>{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }}</strong><br>
                                <small style="color: var(--text-muted);">s/d {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}</small>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($pengajuan && $pengajuan->status === 'Sedang Magang')
                                <span class="badge badge-present">Sedang Magang</span>
                            @elseif($pengajuan && $pengajuan->status === 'Akan Magang')
                                <span class="badge badge-pending">Akan Magang</span>
                            @else
                                <span class="badge badge-draft">Selesai Magang</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('pembimbing.mahasiswa.detail', $mhs->id_mhs) }}" class="btn btn-sm btn-secondary" title="Lihat Profil & Riwayat">
                                    <i class="fa-regular fa-id-card"></i> Detail
                                </a>
                                <a href="{{ route('pembimbing.absensi', ['mahasiswa_id' => $mhs->id_mhs]) }}" class="btn btn-sm btn-secondary" title="Cek Presensi">
                                    <i class="fa-solid fa-location-dot"></i>
                                </a>
                                <a href="{{ route('pembimbing.logbook', ['mahasiswa_id' => $mhs->id_mhs]) }}" class="btn btn-sm btn-secondary" title="Lihat Logbook">
                                    <i class="fa-regular fa-book-open"></i>
                                </a>
                                <a href="{{ route('pembimbing.nilai', ['mahasiswa_id' => $mhs->id_mhs]) }}" class="btn btn-sm btn-primary" title="Beri Nilai Evaluasi">
                                    <i class="fa-regular fa-star"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            <i class="fa-regular fa-folder-open" style="font-size: 36px; display: block; margin-bottom: 10px;"></i>
                            Tidak ditemukan data mahasiswa bimbingan yang sesuai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $mahasiswas->links() }}
    </div>
</div>
@endsection
