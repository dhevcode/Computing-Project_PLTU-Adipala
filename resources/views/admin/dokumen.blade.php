@extends('admin.layouts.app')

@section('title', 'Dokumen Verifikasi')
@section('page_title', 'Dokumen Verifikasi')
@section('page_subtitle', 'Kelola, verifikasi, dan kirim surat balasan mahasiswa PKL')

@push('styles')
<style>
    /* ================= KARTU STATISTIK (SESUAI GAMBAR BARU) ================= */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: var(--bg); padding: 24px; border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; /* Teks Kiri, Ikon Kanan */
        gap: 20px; transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }

    .stat-info { flex: 1; text-align: left; }
    .stat-info span { font-size: 13px; font-weight: 600; color: var(--muted); display: block; margin-bottom: 4px; }
    .stat-info h2 { font-size: 32px; font-weight: 800; color: var(--text); margin: 0 0 2px 0; line-height: 1; }
    .stat-info small { font-size: 11.5px; color: var(--muted); display: block; margin-top: 4px; }

    /* Ikon Bulat di Kanan */
    .stat-icon {
        width: 60px; height: 60px; border-radius: 50%; /* Membuatnya bulat sempurna */
        display: flex; align-items: center; justify-content: center;
        font-size: 26px; flex-shrink: 0;
    }

    .icon-verified { background: #ECFDF5; color: #059669; }
    .icon-pending { background: #FFFBEB; color: #D97706; }
    .icon-total { background: #E0F2FE; color: #0284C7; }

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

    /* ================= TABEL & AKSI ================= */
    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; }
    .card-header { padding: 20px 24px; border-bottom: 1px solid var(--page); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; }
    .card-title-group h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0 0 4px 0; }
    .card-title-group p { font-size: 12px; color: var(--muted); margin: 0; }

    .status-badge { padding: 4px 12px; border-radius: 999px; font-size: 11.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; }
    .badge-verified { background: var(--success-bg); color: var(--success-text); border: 1px solid #A7F3D0; }
    .badge-pending { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }

    /* Tombol Aksi */
    .action-group { display: flex; gap: 8px; justify-content: center; align-items: center; flex-wrap: nowrap; }
    .btn-action {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 8px 16px; border-radius: 8px; font-size: 12.5px; font-weight: 600;
        cursor: pointer; transition: all 0.2s; text-decoration: none; font-family: inherit;
        border: none; white-space: nowrap; width: auto !important; min-width: max-content;
    }
    .btn-secondary { background: #F1F5F9; color: #475569; border: 1.5px solid var(--border); }
    .btn-secondary:hover { background: #E2E8F0; color: var(--text); }
    .btn-primary { background: var(--primary); color: #fff; border: 1.5px solid var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

    /* Paginasi */
    .pagination-wrapper { display: flex; justify-content: center; gap: 6px; padding: 20px 0 10px; }
    .pagination-btn { width: 34px; height: 34px; display: inline-flex; justify-content: center; align-items: center; border-radius: 8px; background: #fff; border: 1.5px solid var(--border); color: var(--text); font-size: 13px; font-weight: 600; text-decoration: none; transition: 0.2s; }
    .pagination-btn:hover:not(.disabled):not(.active) { border-color: var(--primary); color: var(--primary); }
    .pagination-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; }
    .pagination-btn.disabled { background: #F8FAFC; color: #CBD5E1; cursor: not-allowed; }

    /* ================= MODAL UPLOAD ================= */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 20px; opacity: 0; transition: opacity 0.2s; }
    .modal-overlay.active { display: flex; opacity: 1; }

    .upload-modal { background: #fff; width: 100%; max-width: 450px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: scale(0.95) translateY(20px); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .modal-overlay.active .upload-modal { transform: scale(1) translateY(0); }

    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border); }
    .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0; }
    .modal-close { background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer; transition: 0.2s; }
    .modal-close:hover { color: var(--error-text); }

    .modal-body { padding: 24px; }
    .student-info { background: var(--page); padding: 14px; border-radius: 8px; margin-bottom: 20px; border: 1px solid var(--border); }
    .student-info strong { display: block; font-size: 14px; color: var(--text); margin-bottom: 2px; }
    .student-info span { font-size: 12px; color: var(--muted); }

    .upload-label { font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px; color: var(--text); }
    .upload-area { background: var(--page); border: 1.5px dashed var(--border); border-radius: 12px; padding: 24px; text-align: center; cursor: pointer; transition: 0.2s; position: relative; }
    .upload-area:hover { border-color: var(--primary); background: var(--primary-soft); }
    .upload-area i { font-size: 32px; color: var(--primary); margin-bottom: 12px; }
    .upload-area p { font-size: 13.5px; font-weight: 600; color: var(--text); margin-bottom: 4px; margin-top: 0; }
    .upload-area small { font-size: 11px; color: var(--muted); }
    .selected-file { margin-top: 12px; font-size: 12px; font-weight: 600; color: var(--success-text); display: none; }

    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #fafbfc; border-radius: 0 0 16px 16px; }
    .btn-cancel { background: #fff; border: 1.5px solid var(--border); color: var(--text); padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit; transition: 0.2s; }
    .btn-cancel:hover { background: #F1F5F9; }
    .btn-save { background: var(--primary); border: none; color: #fff; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; font-family: inherit; transition: 0.2s; }
    .btn-save:hover { background: var(--primary-dark); }
    .btn-save:disabled { opacity: 0.7; cursor: not-allowed; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 760px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .stat-card { padding: 16px 20px; }
        .filter-grid { grid-template-columns: 1fr; }
        .filter-actions { flex-direction: column-reverse; }
        .btn-reset, .btn-filter { width: 100%; justify-content: center; }

        /* Tabel Mobile */
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

    @if(session('error'))
        <div class="alert alert-error animate-fade-up" style="background: #FEF2F2; color: #991B1B; border-color: #FECACA;">
            <i class="fa-solid fa-circle-xmark"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- STATS GRID (TAMPILAN DIPERBAIKI SESUAI GAMBAR) -->
    <div class="stats-grid animate-fade-up delay-1">
        <div class="stat-card">
            <!-- Teks di Kiri -->
            <div class="stat-info">
                <span>Dokumen Terverifikasi</span>
                <h2>{{ str_pad($verified, 2, '0', STR_PAD_LEFT) }}</h2>
                <small>Mahasiswa telah diverifikasi</small>
            </div>
            <!-- Ikon Bulat di Kanan -->
            <div class="stat-icon icon-verified"><i class="fa-solid fa-file-circle-check"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Belum Terverifikasi</span>
                <h2>{{ str_pad($notVerified, 2, '0', STR_PAD_LEFT) }}</h2>
                <small>Menunggu surat balasan</small>
            </div>
            <div class="stat-icon icon-pending"><i class="fa-solid fa-file-circle-exclamation"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Total Mahasiswa</span>
                <h2>{{ str_pad($total, 2, '0', STR_PAD_LEFT) }}</h2>
                <small>Data mahasiswa ditampilkan</small>
            </div>
            <div class="stat-icon icon-total"><i class="fa-solid fa-users"></i></div>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <form method="GET" action="{{ route('admin.dokumen') }}" class="filter-section animate-fade-up delay-2">
        <div class="filter-header">
            <i class="fa-solid fa-filter"></i>
            <div>
                <h3>Filter Dokumen</h3>
                <p style="font-size: 12px; color: var(--muted); margin-top: 4px; margin-bottom: 0;">Saring data mahasiswa berdasarkan kriteria verifikasi</p>
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
                <label>Status Verifikasi</label>
                <select name="status_verifikasi" class="d-none custom-filter-select">
                    <option value="">Semua Status</option>
                    <option value="verified" {{ request('status_verifikasi')=='verified' ? 'selected':'' }}>Verified</option>
                    <option value="not_verified" {{ request('status_verifikasi')=='not_verified' ? 'selected':'' }}>Not Verified</option>
                </select>
            </div>

            <div class="input-group">
                <label>Universitas</label>
                <select name="universitas_id" class="d-none custom-filter-select">
                    <option value="">Semua Universitas</option>
                    @foreach($universitas as $u)
                        <option value="{{ $u->id_universitas }}" {{ request('universitas_id')==$u->id_universitas?'selected':'' }}>{{ $u->nama_universitas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label>Jurusan</label>
                <select name="jurusan_id" class="d-none custom-filter-select">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusan as $j)
                        <option value="{{ $j->id_jurusan }}" {{ request('jurusan_id')==$j->id_jurusan?'selected':'' }}>{{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label>Divisi</label>
                <select name="divisi_id" class="d-none custom-filter-select">
                    <option value="">Semua Divisi</option>
                    @foreach($divisi as $d)
                        <option value="{{ $d->id_divisi }}" {{ request('divisi_id')==$d->id_divisi?'selected':'' }}>{{ $d->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="d-none custom-filter-select">
                    <option value="">Semua</option>
                    <option value="Laki-laki" {{ request('jenis_kelamin')=='Laki-laki'?'selected':'' }}>Laki-laki</option>
                    <option value="Perempuan" {{ request('jenis_kelamin')=='Perempuan'?'selected':'' }}>Perempuan</option>
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
            <a href="{{ route('admin.dokumen') }}" class="btn-reset"><i class="fa-solid fa-rotate-left"></i> Reset Filter</a>
            <button type="submit" class="btn-filter"><i class="fa-solid fa-filter"></i> Terapkan Filter</button>
        </div>
    </form>

    <!-- DATA TABLE -->
    <div class="card animate-fade-up delay-3">
        <div class="card-header">
            <div class="card-title-group">
                <h3>Data Dokumen Mahasiswa</h3>
                <p>{{ $total }} mahasiswa ditampilkan</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th>Mahasiswa & NIM</th>
                        <th>Universitas & Prodi</th>
                        <th>Divisi</th>
                        <th>Email</th>
                        <th style="text-align: center;">Status Dokumen</th>
                        <th style="text-align: center;">Aksi Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $index => $p)
                    <tr>
                        <td style="text-align: center; color: var(--muted); font-weight: 600;" data-label="No">
                            {{ $pengajuan->firstItem() + $index }}
                        </td>

                        <td class="cell-user">
                            <strong style="color: var(--text); display: block; margin-bottom: 4px;">{{ $p->mahasiswa->nama_lengkap }}</strong>
                            <small style="color: var(--muted);">NIM: <span class="code-tag" style="background: transparent; padding: 0;">{{ $p->mahasiswa->nim }}</span></small>
                        </td>

                        <td data-label="Kampus">
                            <strong style="color: var(--text); display: block; margin-bottom: 4px;">{{ $p->mahasiswa->universitas->nama_universitas ?? '-' }}</strong>
                            <small style="color: var(--muted);">{{ $p->mahasiswa->jurusan->nama_jurusan ?? '-' }}</small>
                        </td>

                        <td data-label="Divisi" style="font-weight: 600; color: var(--primary);">
                            {{ $p->divisi->nama_divisi ?? '-' }}
                        </td>

                        <td data-label="Email">{{ $p->mahasiswa->email }}</td>

                        <td data-label="Status Dokumen" style="text-align: center;">
                            @if($p->surat_balasan)
                                <span class="status-badge badge-verified"><i class="fa-solid fa-circle-check"></i> Verified</span>
                            @else
                                <span class="status-badge badge-pending"><i class="fa-regular fa-clock"></i> Pending</span>
                            @endif
                        </td>

                        <td class="cell-action" style="text-align: center;">
                            <div class="action-group">
                                @if($p->surat_balasan)
                                    <a href="{{ route('admin.dokumen.lihat',$p->id_pengajuan) }}" target="_blank" class="btn-action btn-secondary" title="Lihat PDF">
                                        <i class="fa-regular fa-eye"></i> Lihat
                                    </a>
                                    <button type="button" class="btn-action btn-primary change-surat-btn" data-action="{{ route('admin.dokumen.upload',$p->id_pengajuan) }}" data-name="{{ $p->mahasiswa->nama_lengkap }}" data-email="{{ $p->mahasiswa->email }}" title="Ganti Surat">
                                        <i class="fa-solid fa-upload"></i> Ganti
                                    </button>
                                @else
                                    <button type="button" class="btn-action btn-primary upload-surat-btn" data-action="{{ route('admin.dokumen.upload',$p->id_pengajuan) }}" data-name="{{ $p->mahasiswa->nama_lengkap }}" data-email="{{ $p->mahasiswa->email }}" title="Upload Surat">
                                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Upload Balasan
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fa-solid fa-folder-open"></i>
                                <p>Belum ada data mahasiswa / dokumen.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuan->hasPages())
        <div class="pagination-wrapper">
            @if($pengajuan->onFirstPage())
                <span class="pagination-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
            @else
                <a href="{{ $pengajuan->previousPageUrl() }}" class="pagination-btn"><i class="fa-solid fa-chevron-left"></i></a>
            @endif

            @foreach($pengajuan->getUrlRange(1, $pengajuan->lastPage()) as $page => $url)
                @if($page == $pengajuan->currentPage())
                    <span class="pagination-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($pengajuan->hasMorePages())
                <a href="{{ $pengajuan->nextPageUrl() }}" class="pagination-btn"><i class="fa-solid fa-chevron-right"></i></a>
            @else
                <span class="pagination-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
        @endif
    </div>

<!-- MODAL UPLOAD SURAT BALASAN -->
<div class="modal-overlay" id="uploadModal">
    <div class="upload-modal">
        <div class="modal-header">
            <h3 id="uploadModalTitle">Upload Surat Balasan</h3>
            <button type="button" class="modal-close" onclick="closeUploadModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="uploadForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="student-info">
                    <strong id="modalStudentName"></strong>
                    <span id="modalStudentEmail"></span>
                </div>

                <label class="upload-label">Surat Balasan (PDF)</label>
                <div class="upload-area" onclick="document.getElementById('suratBalasan').click()">
                    <i class="fa-solid fa-file-pdf"></i>
                    <p id="uploadLabel">Klik untuk memilih file PDF</p>
                    <small>Maksimal ukuran 4 MB</small>
                    <input type="file" id="suratBalasan" name="surat_balasan" accept=".pdf,application/pdf" hidden required>
                    <div id="selectedFile" class="selected-file"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeUploadModal()">Batal</button>
                <button type="submit" class="btn-save" id="submitUpload">
                    <i class="fa-solid fa-paper-plane"></i> <span id="submitUploadText">Simpan & Kirim</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ================= MODAL UPLOAD ================= */
    const uploadModal = document.getElementById('uploadModal');
    const uploadForm = document.getElementById('uploadForm');
    const fileInput = document.getElementById('suratBalasan');
    const selectedFile = document.getElementById('selectedFile');

    document.querySelectorAll('.upload-surat-btn, .change-surat-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            uploadForm.action = btn.dataset.action;
            document.getElementById('modalStudentName').textContent = btn.dataset.name;
            document.getElementById('modalStudentEmail').textContent = btn.dataset.email;

            const isChange = btn.classList.contains('change-surat-btn');
            document.getElementById('uploadModalTitle').textContent = isChange ? 'Ganti Surat Balasan' : 'Upload Surat Balasan';
            document.getElementById('submitUploadText').textContent = isChange ? 'Ganti & Kirim' : 'Simpan & Kirim';

            fileInput.value = '';
            selectedFile.style.display = 'none';
            selectedFile.textContent = '';

            // Tambahkan animasi halus
            uploadModal.style.display = 'flex';
            setTimeout(() => uploadModal.classList.add('active'), 10);
        });
    });

    window.closeUploadModal = function() {
        uploadModal.classList.remove('active');
        setTimeout(() => uploadModal.style.display = 'none', 200);
        fileInput.value = '';
        selectedFile.style.display = 'none';
        selectedFile.textContent = '';
    }

    uploadModal.addEventListener('click', e => { if (e.target === uploadModal) closeUploadModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeUploadModal(); });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            selectedFile.textContent = "File terpilih: " + fileInput.files[0].name;
            selectedFile.style.display = 'block';
            fileInput.parentElement.style.borderColor = 'var(--success-text)';
            fileInput.parentElement.style.background = 'var(--success-bg)';
        } else {
            selectedFile.style.display = 'none';
            fileInput.parentElement.style.borderColor = '';
            fileInput.parentElement.style.background = '';
        }
    });

    uploadForm.addEventListener('submit', e => {
        const file = fileInput.files[0];
        if (!file) { e.preventDefault(); alert('Pilih file PDF terlebih dahulu.'); return; }
        if (file.size > 4 * 1024 * 1024) { e.preventDefault(); alert('Ukuran file maksimal 4 MB.'); return; }

        document.getElementById('submitUpload').disabled = true;
        document.getElementById('submitUploadText').textContent = 'Mengirim...';
    });
});
</script>
@endpush
