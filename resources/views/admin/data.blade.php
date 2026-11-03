@extends('admin.layouts.app')

@section('title', 'Data Mahasiswa')
@section('page_title', 'Data Mahasiswa')
@section('page_subtitle', 'Kelola, cari, dan pantau seluruh data mahasiswa magang')

@push('styles')
<style>
    /* Filter Section */
    .filter-section { background: var(--bg); border-radius: 16px; padding: 20px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); }
    .filter-header { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--page); }
    .filter-header i { color: var(--primary); font-size: 18px; }
    .filter-header h3 { font-size: 15px; font-weight: 700; color: var(--text); }
    .filter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .input-group label { display: block; font-size: 12.5px; font-weight: 600; color: var(--muted); margin-bottom: 6px; }
    .filter-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }

    .btn-reset { background: #F1F5F9; color: #475569; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s; text-decoration: none; }
    .btn-reset:hover { background: #E2E8F0; color: var(--text); }
    .btn-filter { background: var(--primary); color: #fff; border: none; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: 0.2s; font-family: inherit; }
    .btn-filter:hover { background: var(--primary-dark); }

    /* Tombol Aksi Tabel */
    .btn-add { background: var(--primary); color: #fff; text-decoration: none; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
    .btn-add:hover { background: var(--primary-dark); }
    .btn-export { background: #F0FDF4; color: #166534; border: 1.5px solid #BBF7D0; text-decoration: none; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
    .btn-export:hover { background: #DCFCE7; border-color: #86EFAC; }

    .status-badge { padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; }
    .status-menunggu { background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; }
    .status-akan { background: #EEF2FF; color: #4338CA; border: 1px solid #C7D2FE; }
    .status-sedang { background: var(--success-bg); color: var(--success-text); border: 1px solid #A7F3D0; }
    .status-selesai { background: #F1F5F9; color: #475569; border: 1px solid var(--border); }
    .status-ditolak { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }

    /* Paginasi */
    .pagination-wrapper { display: flex; justify-content: center; gap: 6px; padding: 20px 0 10px; }
    .pagination-btn { width: 34px; height: 34px; display: inline-flex; justify-content: center; align-items: center; border-radius: 8px; background: #fff; border: 1.5px solid var(--border); color: var(--text); font-size: 13px; font-weight: 600; text-decoration: none; transition: 0.2s; }
    .pagination-btn:hover:not(.disabled):not(.active) { border-color: var(--primary); color: var(--primary); }
    .pagination-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; }
    .pagination-btn.disabled { background: #F8FAFC; color: #CBD5E1; cursor: not-allowed; }

    /* Responsive */
    @media (max-width: 1100px) { .filter-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 760px) {
        .filter-grid { grid-template-columns: 1fr; }
        .filter-actions { flex-direction: column-reverse; }
        .btn-reset, .btn-filter { width: 100%; justify-content: center; }
        .card-header { flex-direction: column; align-items: stretch; gap: 14px; }
        .card-header > div:last-child { display: flex; flex-direction: column; gap: 10px; }
        .btn-add, .btn-export { width: 100%; justify-content: center; }

        /* Tabel Mobile */
        table, tbody, tr, td { display: block; width: 100%; }
        thead { display: none; }
        tbody tr { border: 1px solid var(--border); border-radius: 12px; margin-bottom: 16px; padding: 16px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        tbody td { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #F1F5F9; text-align: right; }
        tbody td:last-child { border-bottom: none; padding-bottom: 0; }
        tbody td[data-label]::before { content: attr(data-label); font-weight: 600; color: var(--muted); text-align: left; flex-shrink: 0; margin-right: 12px; }

        tbody td.cell-user { display: block; text-align: left; background: var(--page); padding: 14px; border-radius: 8px; margin-bottom: 12px; border-bottom: none; }
        tbody td.cell-user::before { display: none; }
        tbody td.cell-action { display: flex; justify-content: flex-end; padding-top: 16px; margin-top: 8px; }
        tbody td.cell-action::before { display: none; }
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

    <form method="GET" action="{{ route('admin.data') }}" class="filter-section animate-fade-up delay-1">
        <div class="filter-header">
            <i class="fa-solid fa-filter"></i>
            <div>
                <h3>Filter Data Mahasiswa</h3>
                <p style="font-size: 12px; color: var(--muted); margin-top: 4px;">Saring data berdasarkan kriteria tertentu</p>
            </div>
        </div>

        <div class="filter-grid">
            <div class="input-group">
                <label>Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" class="input-box" placeholder="Nama / NIM...">
            </div>

            <div class="input-group">
                <label>Jurusan</label>
                <select name="jurusan_id" class="d-none custom-filter-select">
                    <option value="">Semua Jurusan</option>
                    @foreach($listJurusan as $j)
                        <option value="{{ $j->id_jurusan }}" {{ request('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label>Universitas</label>
                <select name="universitas_id" class="d-none custom-filter-select">
                    <option value="">Semua Universitas</option>
                    @foreach($listUniversitas as $u)
                        <option value="{{ $u->id_universitas }}" {{ request('universitas_id') == $u->id_universitas ? 'selected' : '' }}>{{ $u->nama_universitas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label>Divisi</label>
                <select name="divisi_id" class="d-none custom-filter-select">
                    <option value="">Semua Divisi</option>
                    @foreach($listDivisi as $d)
                        <option value="{{ $d->id_divisi }}" {{ request('divisi_id') == $d->id_divisi ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="d-none custom-filter-select">
                    <option value="">Semua</option>
                    <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div class="input-group">
                <label>Tanggal Masuk</label>
                <input type="text" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="flatpickr-date" placeholder="Pilih Tanggal">
            </div>

            <div class="input-group">
                <label>Tanggal Selesai</label>
                <input type="text" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="flatpickr-date" placeholder="Pilih Tanggal">
            </div>

            <div class="input-group">
                <label>Status Magang</label>
                <select name="status" class="d-none custom-filter-select">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="Akan Magang" {{ request('status') == 'Akan Magang' ? 'selected' : '' }}>Akan Magang</option>
                    <option value="Sedang Magang" {{ request('status') == 'Sedang Magang' ? 'selected' : '' }}>Sedang Magang</option>
                    <option value="Selesai Magang" {{ request('status') == 'Selesai Magang' ? 'selected' : '' }}>Selesai Magang</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
        </div>

        <div class="filter-actions">
            <a href="{{ route('admin.data') }}" class="btn-reset"><i class="fa-solid fa-rotate-left"></i> Reset Filter</a>
            <button type="submit" class="btn-filter"><i class="fa-solid fa-filter"></i> Terapkan Filter</button>
        </div>
    </form>

    <div class="card animate-fade-up delay-2">
        <div class="card-header">
            <div class="card-title-group">
                <h3>Daftar Data Peserta Magang</h3>
                <p style="font-size: 12px; color: var(--muted); margin-top: 4px;">Menampilkan semua data yang sesuai dengan pencarian Anda.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.export', request()->query()) }}" class="btn-export">
                    <i class="fa-solid fa-file-export"></i> Ekspor CSV
                </a>
                <a href="{{ route('admin.create') }}" class="btn-add">
                    <i class="fa-solid fa-plus"></i> Tambah Manual
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th>Mahasiswa & NIM</th>
                        <th>Kampus & Jurusan</th>
                        <th>Divisi Penempatan</th>
                        <th>Pembimbing</th>
                        <th>Periode Magang</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $index => $row)
                    <tr>
                        <td style="text-align: center; color: var(--muted); font-weight: 600;" data-label="No">
                            {{ $pengajuans->firstItem() + $index }}
                        </td>

                        <td class="cell-user">
                            <strong style="color: var(--text); display: block;">{{ $row->mahasiswa->nama_lengkap ?? '-' }}</strong>
                            <small style="color: var(--muted);">NIM: <span class="code-tag" style="background: transparent; padding: 0;">{{ $row->mahasiswa->nim ?? '-' }}</span></small>
                        </td>

                        <td data-label="Kampus">
                            <strong style="color: var(--text); display: block;">{{ $row->mahasiswa->universitas->nama_universitas ?? '-' }}</strong>
                            <small style="color: var(--muted);">{{ $row->mahasiswa->jurusan->nama_jurusan ?? '-' }}</small>
                        </td>

                        <td data-label="Divisi" style="font-weight: 600; color: var(--primary);">
                            {{ $row->divisi->nama_divisi ?? '-' }}
                        </td>

                        <td data-label="Pembimbing">
                            @if($row->pembimbing)
                                <span style="font-weight:600; color:var(--text);"><i class="fa-solid fa-user-tie" style="color: var(--primary); margin-right: 4px;"></i> {{ $row->pembimbing->nama_lengkap }}</span>
                            @elseif($row->nama_pembimbing)
                                <span>{{ $row->nama_pembimbing }}</span>
                            @else
                                <span style="color:var(--error-text); font-size:12px; font-weight:600;"><i class="fa-solid fa-triangle-exclamation"></i> Belum Diplot</span>
                            @endif
                        </td>

                        <td data-label="Periode Magang" style="font-size: 12.5px;">
                            {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y') }}<br>s/d {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y') }}
                        </td>

                        <td data-label="Status" style="text-align: center;">
                            @if($row->status === 'Menunggu Verifikasi')
                                <span class="status-badge status-menunggu"><i class="fa-solid fa-hourglass-half"></i> Menunggu</span>
                            @elseif($row->status === 'Akan Magang')
                                <span class="status-badge status-akan">Akan Magang</span>
                            @elseif($row->status === 'Sedang Magang')
                                <span class="status-badge status-sedang">Sedang Magang</span>
                            @elseif($row->status === 'Selesai Magang')
                                <span class="status-badge status-selesai">Selesai Magang</span>
                            @elseif($row->status === 'Ditolak')
                                <span class="status-badge status-ditolak"><i class="fa-solid fa-ban"></i> Ditolak</span>
                            @else
                                <span class="status-badge">{{ $row->status }}</span>
                            @endif
                        </td>

                        <td class="cell-action" style="text-align: center;">
                            <div class="action-group">
                                @if($row->status === 'Menunggu Verifikasi')
                                    <a href="{{ route('admin.detail', $row->id_pengajuan) }}" class="btn-action" style="background: var(--success-bg); color: var(--success-text);" title="Verifikasi Pendaftaran">
                                        <i class="fa-solid fa-user-check"></i>
                                    </a>
                                @else
                                    <a href="{{ route('admin.detail', $row->id_pengajuan) }}" class="btn-action" style="background: #E0F2FE; color: #0284C7;" title="Detail & Edit">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                @endif

                                <form action="{{ route('admin.destroy', $row->id_pengajuan) }}" method="POST" data-confirm="Hapus data pendaftaran {{ $row->mahasiswa->nama_lengkap ?? '' }}?" data-confirm-title="Hapus Data">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Hapus Data">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fa-solid fa-folder-open"></i>
                                <p>Belum ada data mahasiswa yang sesuai dengan filter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuans->hasPages())
        <div class="pagination-wrapper">
            @if ($pengajuans->onFirstPage())
                <span class="pagination-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
            @else
                <a href="{{ $pengajuans->appends(request()->query())->previousPageUrl() }}" class="pagination-btn"><i class="fa-solid fa-chevron-left"></i></a>
            @endif

            @foreach ($pengajuans->getUrlRange(1, $pengajuans->lastPage()) as $page => $url)
                @if ($page == $pengajuans->currentPage())
                    <span class="pagination-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $pengajuans->appends(request()->query())->url($page) }}" class="pagination-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if ($pengajuans->hasMorePages())
                <a href="{{ $pengajuans->appends(request()->query())->nextPageUrl() }}" class="pagination-btn"><i class="fa-solid fa-chevron-right"></i></a>
            @else
                <span class="pagination-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
        @endif
    </div>
@endsection
