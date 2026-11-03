@extends('admin.layouts.app')

@section('title', 'Sertifikat Mahasiswa')
@section('page_title', 'Sertifikat Mahasiswa')
@section('page_subtitle', 'Kelola laporan, verifikasi ID Card, dan penerbitan sertifikat PKL')

@push('styles')
<style>
    /* ================= FILTER SECTION ================= */
    .filter-section { background: var(--bg); border-radius: 16px; padding: 20px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); }
    .filter-header { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--page); }
    .filter-header i { color: var(--primary); font-size: 18px; }
    .filter-header h3 { font-size: 15px; font-weight: 700; color: var(--text); margin: 0; }
    .filter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .input-group label { display: block; font-size: 12.5px; font-weight: 600; color: var(--muted); margin-bottom: 6px; }
    .filter-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }

    .btn-reset { background: #F1F5F9; color: #475569; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s; text-decoration: none; }
    .btn-reset:hover { background: #E2E8F0; color: var(--text); }
    .btn-filter { background: var(--primary); color: #fff; border: none; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: 0.2s; font-family: inherit; }
    .btn-filter:hover { background: var(--primary-dark); }

    /* ================= TABEL & KARTU DATA ================= */
    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; }
    .card-header { padding: 20px 24px; border-bottom: 1px solid var(--page); }
    .card-title-group h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0 0 4px 0; }
    .card-title-group p { font-size: 12px; color: var(--muted); margin: 0; }

    .status-badge { padding: 4px 12px; border-radius: 999px; font-size: 11.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; border: 1px solid var(--border); background: var(--page); color: var(--text); }
    .badge-aktif { background: var(--success-bg); color: var(--success-text); border-color: #A7F3D0; }

    /* ID Card Verifikasi Toggle Button */
    .id-card-circle {
        width: 32px; height: 32px; border-radius: 50%; border: 2px solid var(--border);
        background: var(--page); cursor: pointer; display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s; outline: none; position: relative;
    }
    .id-card-circle::after {
        content: '\f00c'; font-family: "Font Awesome 6 Free"; font-weight: 900;
        color: #fff; font-size: 14px; opacity: 0; transform: scale(0.5); transition: all 0.2s;
    }
    .id-card-circle:hover { border-color: var(--primary); }
    .id-card-circle.active { background: var(--success-text); border-color: var(--success-text); }
    .id-card-circle.active::after { opacity: 1; transform: scale(1); }

    .cert-badge { padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-align: center; }
    .cert-pending { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
    .cert-issued { background: var(--success-bg); color: var(--success-text); border: 1px solid #A7F3D0; }

    /* Tombol Aksi */
    .action-group { display: flex; gap: 8px; justify-content: flex-start; align-items: center; flex-wrap: nowrap; }
    .btn-action {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 8px 14px; border-radius: 8px; font-size: 12.5px; font-weight: 600;
        cursor: pointer; transition: all 0.2s; text-decoration: none; font-family: inherit;
        border: none; white-space: nowrap; min-width: max-content; height: 36px;
    }
    .btn-action:disabled { opacity: 0.5; cursor: not-allowed; background: var(--page); color: var(--muted); border-color: var(--border); }
    .btn-secondary { background: #F1F5F9; color: #475569; border: 1.5px solid var(--border); }
    .btn-secondary:hover:not(:disabled) { background: #E2E8F0; color: var(--text); }
    .btn-primary { background: var(--primary); color: #fff; border: 1.5px solid var(--primary); }
    .btn-primary:hover:not(:disabled) { background: var(--primary-dark); border-color: var(--primary-dark); }

    /* Paginasi */
    .pagination-wrapper { display: flex; justify-content: center; gap: 6px; padding: 20px 0 10px; }
    .pagination-btn { width: 34px; height: 34px; display: inline-flex; justify-content: center; align-items: center; border-radius: 8px; background: #fff; border: 1.5px solid var(--border); color: var(--text); font-size: 13px; font-weight: 600; text-decoration: none; transition: 0.2s; }
    .pagination-btn:hover:not(.disabled):not(.active) { border-color: var(--primary); color: var(--primary); }
    .pagination-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; }
    .pagination-btn.disabled { background: #F8FAFC; color: #CBD5E1; cursor: not-allowed; }

    /* ================= MODAL STYLING ================= */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 20px; opacity: 0; transition: opacity 0.2s; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .custom-modal { background: #fff; width: 100%; max-width: 450px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: scale(0.95) translateY(20px); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .modal-overlay.active .custom-modal { transform: scale(1) translateY(0); }

    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border); }
    .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0; }
    .modal-close { background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer; transition: 0.2s; }
    .modal-close:hover { color: var(--error-text); }

    .modal-body { padding: 24px; }
    .student-info-box { background: var(--page); padding: 14px; border-radius: 8px; margin-bottom: 20px; border: 1px solid var(--border); }
    .student-info-box strong { display: block; font-size: 14px; color: var(--text); margin-bottom: 2px; }
    .student-info-box span { font-size: 12px; color: var(--muted); }

    .upload-label { font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px; color: var(--text); }
    .upload-area { background: var(--page); border: 1.5px dashed var(--border); border-radius: 12px; padding: 24px; text-align: center; cursor: pointer; transition: 0.2s; position: relative; }
    .upload-area:hover { border-color: var(--primary); background: var(--primary-soft); }
    .upload-area i { font-size: 32px; color: var(--primary); margin-bottom: 12px; }
    .upload-area p { font-size: 13.5px; font-weight: 600; color: var(--text); margin-bottom: 4px; margin-top: 0; }
    .upload-area small { font-size: 11px; color: var(--muted); }
    .selected-file-name { margin-top: 12px; font-size: 12px; font-weight: 600; color: var(--success-text); display: none; }

    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #fafbfc; border-radius: 0 0 16px 16px; }
    .btn-cancel { background: #fff; border: 1.5px solid var(--border); color: var(--text); padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit; transition: 0.2s; }
    .btn-cancel:hover { background: #F1F5F9; }
    .btn-save { background: var(--primary); border: none; color: #fff; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; font-family: inherit; transition: 0.2s; }
    .btn-save:hover { background: var(--primary-dark); }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1100px) {
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 760px) {
        .filter-grid { grid-template-columns: 1fr; }
        .filter-actions { flex-direction: column-reverse; }
        .btn-reset, .btn-filter { width: 100%; justify-content: center; }

        /* Tabel Mobile - Card Layout */
        table, tbody, tr, td { display: block; width: 100%; }
        thead { display: none; }
        tbody tr { border: 1px solid var(--border); border-radius: 12px; margin-bottom: 16px; padding: 16px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        tbody td { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #F1F5F9; text-align: right; }
        tbody td:last-child { border-bottom: none; padding-bottom: 0; }
        tbody td[data-label]::before { content: attr(data-label); font-weight: 600; color: var(--muted); text-align: left; flex-shrink: 0; margin-right: 12px; }

        tbody td.cell-user { display: block; text-align: left; background: var(--page); padding: 14px; border-radius: 8px; margin-bottom: 12px; border-bottom: none; }
        tbody td.cell-user::before { display: none; }

        tbody td.cell-action { display: grid; grid-template-columns: 1fr; gap: 8px; padding-top: 16px; margin-top: 8px; }
        tbody td.cell-action::before { display: none; }
        tbody td.cell-action .action-group { display: flex; flex-direction: column; width: 100%; gap: 8px; }
        tbody td.cell-action .btn-action { width: 100%; justify-content: center; }
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

    @if(session('error') || $errors->any())
        <div class="alert alert-error animate-fade-up" style="background: #FEF2F2; color: #991B1B; border-color: #FECACA;">
            <i class="fa-solid fa-circle-xmark"></i>
            <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
    @endif

    <!-- FILTER SECTION -->
    <form action="{{ route('admin.sertifikat') }}" method="GET" class="filter-section animate-fade-up delay-1">
        <div class="filter-header">
            <i class="fa-solid fa-filter"></i>
            <div>
                <h3>Filter Sertifikat</h3>
                <p style="font-size: 12px; color: var(--muted); margin-top: 4px; margin-bottom: 0;">Saring data mahasiswa berdasarkan kriteria kelulusan</p>
            </div>
        </div>

        <div class="filter-grid">
            <div class="input-group">
                <label>Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" class="input-box" placeholder="Nama, NIM, Kampus...">
            </div>
            <div class="input-group">
                <label>Tanggal Mulai</label>
                <input type="text" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="flatpickr-date" placeholder="Pilih Tanggal">
            </div>
            <div class="input-group">
                <label>Tanggal Selesai</label>
                <input type="text" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="flatpickr-date" placeholder="Pilih Tanggal">
            </div>
            <div class="input-group">
                <label>Jurusan</label>
                <select name="jurusan" class="d-none custom-filter-select">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusan as $j)
                        <option value="{{ $j->id_jurusan }}" {{ request('jurusan') == $j->id_jurusan ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group">
                <label>Universitas</label>
                <select name="universitas" class="d-none custom-filter-select">
                    <option value="">Semua Universitas</option>
                    @foreach($universitas as $u)
                        <option value="{{ $u->id_universitas }}" {{ request('universitas') == $u->id_universitas ? 'selected' : '' }}>{{ $u->nama_universitas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group">
                <label>Divisi</label>
                <select name="divisi" class="d-none custom-filter-select">
                    <option value="">Semua Divisi</option>
                    @foreach($divisi as $d)
                        <option value="{{ $d->id_divisi }}" {{ request('divisi') == $d->id_divisi ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group">
                <label>Status Magang</label>
                <select name="status" class="d-none custom-filter-select">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Akan Datang" {{ request('status') == 'Akan Datang' ? 'selected' : '' }}>Akan Datang</option>
                </select>
            </div>
            <div class="input-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="d-none custom-filter-select">
                    <option value="">Semua</option>
                    <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="input-group">
                <label>Urutkan Berdasarkan</label>
                <select name="sort" class="d-none custom-filter-select">
                    <option value="terbaru" {{ request('sort', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                    <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama Ditambahkan</option>
                    <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama A–Z</option>
                    <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama Z–A</option>
                </select>
            </div>
        </div>

        <div class="filter-actions">
            <a href="{{ route('admin.sertifikat') }}" class="btn-reset"><i class="fa-solid fa-rotate-left"></i> Reset Filter</a>
            <button type="submit" class="btn-filter"><i class="fa-solid fa-filter"></i> Terapkan Filter</button>
        </div>
    </form>

    <!-- TABLE CARD -->
    <div class="card animate-fade-up delay-2">
        <div class="card-header">
            <div class="card-title-group">
                <h3>Data Sertifikat Mahasiswa</h3>
                <p>Kelola laporan PKL, verifikasi ID Card, dan penerbitan sertifikat digital.</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th>Mahasiswa & NIM</th>
                        <th>Divisi</th>
                        <th style="text-align: center;">Status Magang</th>
                        <th>File Laporan</th>
                        <th style="text-align: center;">ID Card</th>
                        <th style="text-align: center;">Sertifikat</th>
                        <th style="text-align: center; width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $index => $item)
                        @php
                            $pengajuan = $item->pengajuanMagang;
                            if ($pengajuan instanceof \Illuminate\Support\Collection) {
                                $pengajuan = $pengajuan->sortByDesc('id_pengajuan')->first();
                            }

                            $sertifikat = $item->sertifikat;
                            if ($sertifikat instanceof \Illuminate\Support\Collection) {
                                $sertifikat = $sertifikat->sortByDesc('id_sertifikat')->first();
                            }

                            $statusTampilan = match ($pengajuan?->status) {
                                'Akan Magang' => 'Akan Datang',
                                'Sedang Magang' => 'Aktif',
                                'Selesai Magang' => 'Selesai',
                                default => $pengajuan?->status ?? '-'
                            };
                        @endphp
                        <tr>
                            <td style="text-align: center; color: var(--muted); font-weight: 600;" data-label="No">
                                {{ $mahasiswa->firstItem() + $index }}
                            </td>

                            <td class="cell-user">
                                <strong style="color: var(--text); display: block;">{{ $item->nama_lengkap ?? '-' }}</strong>
                                <small style="color: var(--muted);">NIM: <span class="code-tag" style="background: transparent; padding: 0;">{{ $item->nim ?? '-' }}</span></small>
                            </td>

                            <td data-label="Divisi" style="font-weight: 600; color: var(--primary);">
                                {{ $pengajuan?->divisi?->nama_divisi ?? '-' }}
                            </td>

                            <td data-label="Status Magang" style="text-align: center;">
                                @if($statusTampilan == 'Aktif')
                                    <span class="status-badge badge-aktif">{{ $statusTampilan }}</span>
                                @else
                                    <span class="status-badge">{{ $statusTampilan }}</span>
                                @endif
                            </td>

                            <td data-label="File Laporan">
                                <div class="action-group">
                                    @if($item->file_laporan)
                                        <a href="{{ asset($item->file_laporan) }}" target="_blank" class="btn-action btn-secondary" title="Lihat PDF">
                                            <i class="fa-regular fa-file-pdf"></i>
                                        </a>
                                    @endif
                                    <button type="button" class="btn-action btn-primary btn-upload-laporan" data-mahasiswa-id="{{ $item->id_mhs }}" data-nama="{{ $item->nama_lengkap ?? '-' }}" data-email="{{ $item->email ?? '-' }}" data-upload-url="{{ route('admin.laporan.upload', $item->id_mhs) }}">
                                        <i class="fa-solid fa-arrow-up-from-bracket"></i> {{ $item->file_laporan ? 'Ubah' : 'Upload' }}
                                    </button>
                                </div>
                            </td>

                            <td data-label="ID Card" style="text-align: center;">
                                <form action="{{ route('admin.sertifikat.verify-id-card', $item->id_mhs) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="id-card-circle {{ $item->id_card_verified ? 'active' : '' }}" title="{{ $item->id_card_verified ? 'ID Card sudah diverifikasi' : 'Verifikasi ID Card' }}"></button>
                                </form>
                            </td>

                            <td data-label="Status Sertifikat" style="text-align: center;">
                                @if($sertifikat && $sertifikat->status === 'Diterbitkan')
                                    <div class="cert-badge cert-issued">Diterbitkan</div>
                                @elseif($item->id_card_verified)
                                    <div class="cert-badge cert-issued">Memenuhi Syarat</div>
                                @else
                                    <div class="cert-badge cert-pending">Belum Memenuhi</div>
                                @endif
                            </td>

                            <td class="cell-action" style="text-align: center;">
                                <div class="action-group" style="justify-content: center;">
                                    @if($sertifikat && $sertifikat->file_sertifikat && file_exists(public_path($sertifikat->file_sertifikat)))
                                        <a href="{{ asset($sertifikat->file_sertifikat) }}" target="_blank" class="btn-action btn-secondary" title="Lihat Sertifikat">
                                            <i class="fa-regular fa-eye"></i> Lihat
                                        </a>
                                    @endif

                                    @if($item->id_card_verified)
                                        <button type="button" class="btn-action btn-primary btn-open-certificate" data-upload-url="{{ route('admin.sertifikat.upload', $item->id_mhs) }}">
                                            <i class="fa-solid fa-award"></i> {{ ($sertifikat && $sertifikat->file_sertifikat) ? 'Ubah' : 'Terbitkan' }}
                                        </button>
                                    @else
                                        <button type="button" class="btn-action btn-secondary" disabled title="Verifikasi ID Card terlebih dahulu">
                                            <i class="fa-solid fa-lock"></i> Terbitkan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <p>Tidak ada data sertifikat mahasiswa ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mahasiswa->hasPages())
        <div class="pagination-wrapper">
            @if ($mahasiswa->onFirstPage())
                <span class="pagination-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
            @else
                <a href="{{ $mahasiswa->previousPageUrl() }}" class="pagination-btn"><i class="fa-solid fa-chevron-left"></i></a>
            @endif

            @foreach ($mahasiswa->getUrlRange(1, $mahasiswa->lastPage()) as $page => $url)
                @if ($page == $mahasiswa->currentPage())
                    <span class="pagination-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if ($mahasiswa->hasMorePages())
                <a href="{{ $mahasiswa->nextPageUrl() }}" class="pagination-btn"><i class="fa-solid fa-chevron-right"></i></a>
            @else
                <span class="pagination-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
        @endif
    </div>

<!-- ================= MODAL LAPORAN ================= -->
<div class="modal-overlay" id="laporanModal">
    <div class="custom-modal">
        <div class="modal-header">
            <h3>Upload Laporan PKL</h3>
            <button type="button" class="modal-close" onclick="closeModal('laporanModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="laporanForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="student-info-box">
                    <strong id="laporanNama"></strong>
                    <span id="laporanEmail"></span>
                </div>
                <label class="upload-label">Berkas Laporan (PDF)</label>
                <div class="upload-area" onclick="document.getElementById('laporanFile').click()">
                    <i class="fa-solid fa-file-pdf"></i>
                    <p>Klik untuk memilih file PDF</p>
                    <small>Maksimal ukuran 5 MB</small>
                    <input type="file" id="laporanFile" name="laporan" accept=".pdf,application/pdf" hidden required>
                    <div id="laporanSelectedFile" class="selected-file-name"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('laporanModal')">Batal</button>
                <button type="submit" class="btn-save" id="submitLaporan"><i class="fa-solid fa-paper-plane"></i> Simpan Laporan</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL SERTIFIKAT ================= -->
<div class="modal-overlay" id="certificateModal">
    <div class="custom-modal">
        <div class="modal-header">
            <h3>Unggah Sertifikat Digital</h3>
            <button type="button" class="modal-close" onclick="closeModal('certificateModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="certificateForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div style="font-size: 13px; color: var(--muted); margin-bottom: 20px;">
                    Pastikan seluruh persyaratan (seperti Laporan & ID Card) sudah terpenuhi sebelum menerbitkan sertifikat.
                </div>
                <label class="upload-label">Berkas Sertifikat (PDF/Gambar)</label>
                <div class="upload-area" onclick="document.getElementById('certificateFile').click()">
                    <i class="fa-solid fa-award"></i>
                    <p>Klik untuk memilih file</p>
                    <small>PDF, JPG, PNG (Maks 5 MB)</small>
                    <input type="file" id="certificateFile" name="certificate" accept=".pdf,.jpg,.jpeg,.png" hidden required>
                    <div id="certSelectedFile" class="selected-file-name"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('certificateModal')">Batal</button>
                <button type="submit" class="btn-save" id="submitCertificate"><i class="fa-solid fa-paper-plane"></i> Simpan & Terbitkan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ================= MODALS LOGIC ================= */
    window.closeModal = function(modalId) {
        const m = document.getElementById(modalId);
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);

        // Reset File inputs
        if(modalId === 'laporanModal') {
            document.getElementById('laporanFile').value = '';
            document.getElementById('laporanSelectedFile').style.display = 'none';
        } else if(modalId === 'certificateModal') {
            document.getElementById('certificateFile').value = '';
            document.getElementById('certSelectedFile').style.display = 'none';
        }
    }

    // Modal Laporan
    document.querySelectorAll('.btn-upload-laporan').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('laporanForm').action = btn.dataset.uploadUrl;
            document.getElementById('laporanNama').textContent = btn.dataset.nama;
            document.getElementById('laporanEmail').textContent = btn.dataset.email;

            const modal = document.getElementById('laporanModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        });
    });

    document.getElementById('laporanFile').addEventListener('change', function() {
        const display = document.getElementById('laporanSelectedFile');
        if (this.files.length) {
            display.textContent = "File terpilih: " + this.files[0].name;
            display.style.display = 'block';
            this.parentElement.style.borderColor = 'var(--success-text)';
            this.parentElement.style.background = 'var(--success-bg)';
        } else {
            display.style.display = 'none';
            this.parentElement.style.borderColor = '';
            this.parentElement.style.background = '';
        }
    });

    // Modal Sertifikat
    document.querySelectorAll('.btn-open-certificate').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('certificateForm').action = btn.dataset.uploadUrl;
            const modal = document.getElementById('certificateModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        });
    });

    document.getElementById('certificateFile').addEventListener('change', function() {
        const display = document.getElementById('certSelectedFile');
        if (this.files.length) {
            display.textContent = "File terpilih: " + this.files[0].name;
            display.style.display = 'block';
            this.parentElement.style.borderColor = 'var(--success-text)';
            this.parentElement.style.background = 'var(--success-bg)';
        } else {
            display.style.display = 'none';
            this.parentElement.style.borderColor = '';
            this.parentElement.style.background = '';
        }
    });

    // File Validation Interceptors
    document.getElementById('laporanForm').addEventListener('submit', function(e) {
        const file = document.getElementById('laporanFile').files[0];
        if(file && file.size > 5 * 1024 * 1024) { e.preventDefault(); alert('Ukuran maksimal 5 MB.'); return; }
        if(file && file.name.split('.').pop().toLowerCase() !== 'pdf') { e.preventDefault(); alert('File harus PDF.'); return; }
        document.getElementById('submitLaporan').disabled = true;
    });

    document.getElementById('certificateForm').addEventListener('submit', function(e) {
        const file = document.getElementById('certificateFile').files[0];
        if(file && file.size > 5 * 1024 * 1024) { e.preventDefault(); alert('Ukuran maksimal 5 MB.'); return; }
        document.getElementById('submitCertificate').disabled = true;
    });

    // Escape and BG Click to close
    document.addEventListener('keydown', e => {
        if(e.key === 'Escape') { closeModal('laporanModal'); closeModal('certificateModal'); }
    });
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => { if(e.target === overlay) closeModal(overlay.id); });
    });
});
</script>
@endpush
