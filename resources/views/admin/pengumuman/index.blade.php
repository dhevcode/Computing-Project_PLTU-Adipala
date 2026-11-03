@extends('admin.layouts.app')

@section('title', 'Informasi & Pengumuman')
@section('page_title', 'Informasi & Pengumuman Perusahaan')
@section('page_subtitle', 'Kelola profil PLTU Adipala, koordinat geofencing absensi, tata tertib, dan prosedur K3')

@push('styles')
<style>
    /* ================= CARDS & TABLE ================= */
    .card { background: var(--bg); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; padding: 24px; }
    
    /* Perbaikan Tata Letak Judul & Ikon (Rata Kiri Sempurna) */
    .card-header-top { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        flex-wrap: wrap; 
        gap: 16px; 
        margin-bottom: 20px;
    }
    .card-title-wrap { 
        display: flex; 
        align-items: center; 
        gap: 16px; 
        flex: 1; /* Memastikan ruang tersisa diisi */
    }
    .icon-circle-box { 
        background: var(--primary-soft); 
        color: var(--primary); 
        font-size: 20px; 
        width: 48px; 
        height: 48px; 
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        flex-shrink: 0; 
    }
    .card-title-wrap h3 { font-size: 16.5px; font-weight: 700; color: var(--text); margin: 0 0 4px 0; line-height: 1.2; }
    .card-title-wrap p { font-size: 12.5px; color: var(--muted); margin: 0; line-height: 1.4; }

    .status-badge { padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; border: 1px solid var(--border); }
    .badge-info { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }

    .btn-sm { padding: 8px 14px; font-size: 12.5px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; transition: 0.2s; white-space: nowrap; height: fit-content; }
    .btn-outline { background: #F8FAFC; color: #475569; border: 1px solid var(--border); }
    .btn-outline:hover { background: #E2E8F0; color: var(--text); }
    .btn-primary { background: var(--primary); color: #fff; border: 1px solid var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
    .btn-danger-outline { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
    .btn-danger-outline:hover { background: #FEE2E2; color: #991B1B; }

    /* ================= FORM GRID COMPANY ================= */
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--page); }
    .form-group { margin-bottom: 0; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; }
    .input-box { width: 100%; padding: 10px 0; font-family: inherit; font-size: 13.5px; color: var(--text); background: transparent; border: none; border-bottom: 1.5px solid var(--border); outline: none; transition: border-color .2s; border-radius: 0; }
    .input-box:focus { border-bottom: 2px solid var(--primary); }

    /* ================= MODAL OVERLAY ================= */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 20px; opacity: 0; transition: opacity 0.2s; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .custom-modal { background: #fff; width: 100%; max-width: 550px; max-height: 90vh; overflow-y: auto; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: scale(0.95) translateY(20px); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .modal-overlay.active .custom-modal { transform: scale(1) translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F9FAFB; border-radius: 16px 16px 0 0; }
    .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0; display: flex; align-items: center; gap: 8px;}
    .modal-close { background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer; padding: 4px 8px; border-radius: 6px; transition: 0.2s; }
    .modal-close:hover { background: #FEE2E2; color: var(--error-text); }
    .modal-body { padding: 24px; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #fafbfc; border-radius: 0 0 16px 16px; }

    /* Custom File Input */
    .file-input-wrapper { display: block; position: relative; overflow: hidden; background: var(--page); border: 1.5px dashed var(--border); border-radius: 8px; padding: 14px; text-align: center; cursor: pointer; transition: 0.2s; }
    .file-input-wrapper:hover { border-color: var(--primary); background: var(--primary-soft); }
    .file-input-wrapper input[type=file] { position: absolute; left: 0; top: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .file-input-wrapper span { font-size: 13px; font-weight: 600; color: var(--text); }
    .file-input-wrapper small { display: block; font-size: 11px; color: var(--muted); margin-top: 4px; }

    /* ================= RESPONSIVE ================= */
    @media (min-width: 761px) {
        .cell-konten { max-width: 320px; text-align: left; }
    }
    @media (max-width: 760px) {
        .form-grid { grid-template-columns: 1fr; }

        /* Tabel Mobile - Menghindari Bug Text Overflow */
        table, tbody, tr, td { display: block; width: 100%; }
        thead { display: none; }
        tbody tr { border: 1px solid var(--border); border-radius: 12px; margin-bottom: 16px; padding: 16px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        tbody td {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 10px 0; border-bottom: 1px solid #F1F5F9; text-align: right;
            word-wrap: break-word; overflow-wrap: break-word;
        }
        tbody td:last-child { border-bottom: none; padding-bottom: 0; }
        tbody td[data-label]::before { content: attr(data-label); font-weight: 600; color: var(--muted); text-align: left; flex-shrink: 0; margin-right: 12px; font-size: 12.5px; }

        /* Solusi Kolom Teks Panjang di Mobile */
        tbody td.cell-konten { flex-direction: column; text-align: left; }
        tbody td.cell-konten::before { margin-bottom: 8px; }

        tbody td.cell-action { display: grid; grid-template-columns: 1fr; gap: 8px; padding-top: 16px; margin-top: 8px; }
        tbody td.cell-action::before { display: none; }
        tbody td.cell-action .btn-sm { width: 100%; justify-content: center; }

        .modal-footer { flex-direction: column-reverse; }
        .modal-footer .btn-sm { width: 100%; justify-content: center; height: 38px; }
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

    <!-- PENGATURAN PERUSAHAAN (GEOFENCE) -->
    <div class="card animate-fade-up delay-1">
        <div class="card-header-top">
            <div class="card-title-wrap">
                <div class="icon-circle-box">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div>
                    <h3>Pengaturan Profil & Radius Presensi</h3>
                    <p>Konfigurasi lokasi pusat perusahaan untuk fitur Geofencing.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.pengumuman.company') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Unit Perusahaan</label>
                    <input type="text" name="name" value="{{ $company->name ?? 'PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala' }}" required class="input-box">
                </div>

                <div class="form-group">
                    <label>Bidang Industri</label>
                    <input type="text" name="industry" value="{{ $company->industry ?? 'Pembangkit Tenaga Listrik' }}" required class="input-box">
                </div>

                <div class="form-group">
                    <label>Radius Presensi (Meter) <span style="color: var(--error-text);">*</span></label>
                    <input type="number" name="attendance_radius_meters" value="{{ $company->attendance_radius_meters ?? 150 }}" min="20" required class="input-box" style="font-weight: 700;">
                </div>

                <div class="form-group">
                    <label>Latitude Pusat PLTU <span style="color: var(--error-text);">*</span></label>
                    <input type="text" name="latitude" value="{{ $company->latitude ?? -7.680810191412908 }}" required class="input-box">
                </div>

                <div class="form-group">
                    <label>Longitude Pusat PLTU <span style="color: var(--error-text);">*</span></label>
                    <input type="text" name="longitude" value="{{ $company->longitude ?? 109.1366921585062 }}" required class="input-box">
                </div>

                <div class="form-group">
                    <label>Jam Masuk Kerja</label>
                    <input type="time" name="work_start_time" value="{{ substr($company->work_start_time ?? '08:00:00', 0, 5) }}" required class="input-box">
                </div>

                <div class="form-group">
                    <label>Jam Pulang Kerja</label>
                    <input type="time" name="work_end_time" value="{{ substr($company->work_end_time ?? '16:00:00', 0, 5) }}" required class="input-box">
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Alamat Lengkap Perusahaan</label>
                    <input type="text" name="address" value="{{ $company->address ?? 'Jl. Laut, Desa Bunton, Kec. Adipala, Kab. Cilacap, Jawa Tengah' }}" required class="input-box">
                </div>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn-sm btn-primary" style="padding: 10px 20px;">
                    <i class="fa-regular fa-floppy-disk"></i> Simpan Pengaturan Perusahaan
                </button>
            </div>
        </form>
    </div>

    <!-- DAFTAR PENGUMUMAN & INFORMASI -->
    <div class="card animate-fade-up delay-2">
        <div class="card-header-top" style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--page);">
            <div class="card-title-wrap">
                <div class="icon-circle-box">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div>
                    <h3>Daftar Informasi & Pengumuman</h3>
                    <p>Kelola pemberitahuan resmi untuk dashboard peserta magang.</p>
                </div>
            </div>
            <button type="button" class="btn-sm btn-primary" onclick="openTambahInfoModal()">
                <i class="fa-solid fa-plus"></i> Tambah Informasi
            </button>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Judul Informasi</th>
                        <th>Ringkasan Konten</th>
                        <th style="text-align: center;">Lampiran</th>
                        <th>Tanggal Publikasi</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($informations as $info)
                        <tr>
                            <td data-label="Kategori">
                                <span class="status-badge badge-info">
                                    {{ \App\Models\CompanyInformation::getCategoryLabel($info->category) }}
                                </span>
                            </td>

                            <td data-label="Judul Informasi">
                                <strong style="color: var(--text);">{{ $info->title }}</strong>
                            </td>

                            <td class="cell-konten" data-label="Ringkasan Konten" style="font-size: 12px; color: var(--muted); line-height: 1.4;">
                                {{ Str::limit($info->content, 90) }}
                            </td>

                            <td data-label="Lampiran" style="text-align: center;">
                                @if($info->attachment_path)
                                    <a href="{{ asset($info->attachment_path) }}" target="_blank" class="btn-sm btn-outline">
                                        <i class="fa-solid fa-paperclip"></i> File
                                    </a>
                                @else
                                    <span style="color: #94a3b8; font-size: 12px;">-</span>
                                @endif
                            </td>

                            <td data-label="Tgl Publikasi">
                                <span style="font-weight: 600; color: var(--text);">{{ $info->created_at->format('d/m/Y') }}</span>
                            </td>

                            <td class="cell-action" style="text-align: center;">
                                <form action="{{ route('admin.pengumuman.destroy', $info->id_info) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus informasi ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-danger-outline">
                                        <i class="fa-regular fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <p>Belum ada informasi atau tata tertib yang dipublikasikan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($informations->hasPages())
            <div class="pagination-wrapper" style="margin-top: 20px;">
                {{ $informations->links() }}
            </div>
        @endif
    </div>

@endsection

<!-- ================= MODAL TAMBAH INFORMASI ================= -->
<div class="modal-overlay" id="tambahInfoModal">
    <div class="custom-modal">
        <div class="modal-header">
            <h3><i class="fa-solid fa-bullhorn" style="color: var(--primary);"></i> Tambah Pengumuman Baru</h3>
            <button type="button" class="modal-close" onclick="closeTambahInfoModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Kategori Informasi <span style="color: var(--error-text);">*</span></label>
                    <select name="category" class="d-none custom-select" required>
                        <option value="announcement">Pengumuman Resmi (Broadcast)</option>
                        <option value="work_rule">Tata Tertib Magang</option>
                        <option value="safety">Prosedur Keselamatan Kerja (K3)</option>
                        <option value="contact">Kontak Penting Perusahaan</option>
                        <option value="document">Dokumen / Panduan Unduhan</option>
                        <option value="general">Informasi Umum</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Judul Informasi <span style="color: var(--error-text);">*</span></label>
                    <input type="text" name="title" class="input-box" style="border: 1.5px solid var(--border); border-radius: 8px; padding: 10px 14px;" placeholder="Contoh: Jadwal Pembekalan K3 Pekan Pertama" required>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Isi Informasi Lengkap <span style="color: var(--error-text);">*</span></label>
                    <textarea name="content" rows="5" class="input-box" style="border: 1.5px solid var(--border); border-radius: 8px; padding: 10px 14px; height: auto; resize: vertical; line-height: 1.5;" placeholder="Tuliskan detail pengumuman atau peraturan..." required></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>Lampiran File (Opsional)</label>
                    <label class="file-input-wrapper">
                        <input type="file" id="info_file" name="attachment" accept=".pdf,.docx,.jpg,.jpeg,.png">
                        <span><i class="fa-solid fa-upload"></i> Pilih File Lampiran</span>
                        <small id="info_file_name">PDF, DOCX, JPG, PNG (Maks 4MB)</small>
                    </label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-sm btn-outline" onclick="closeTambahInfoModal()">Batal</button>
                <button type="submit" class="btn-sm btn-primary"><i class="fa-solid fa-paper-plane"></i> Publikasikan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // 1. CUSTOM DROPDOWN
        const nativeSelects = document.querySelectorAll('select.custom-select');
        nativeSelects.forEach(select => {
            if (select.nextElementSibling && select.nextElementSibling.classList.contains('custom-select-wrapper')) return;

            const wrapper = document.createElement('div');
            wrapper.className = 'custom-select-wrapper';

            const activeOption = select.options[select.selectedIndex];
            const trigger = document.createElement('div');
            trigger.className = 'custom-select-trigger';
            trigger.innerHTML = `<span>${activeOption ? activeOption.text : 'Pilih...'}</span> <i class="fa-solid fa-chevron-down"></i>`;

            const optionsPanel = document.createElement('div');
            optionsPanel.className = 'custom-select-options';

            Array.from(select.options).forEach(option => {
                const optDiv = document.createElement('div');
                optDiv.className = 'custom-option';
                optDiv.textContent = option.text;
                if (option.selected) optDiv.classList.add('selected');

                optDiv.addEventListener('click', (e) => {
                    e.stopPropagation();
                    select.value = option.value;
                    trigger.querySelector('span').textContent = option.text;
                    wrapper.classList.remove('open');

                    optionsPanel.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
                    optDiv.classList.add('selected');
                });
                optionsPanel.appendChild(optDiv);
            });

            wrapper.appendChild(trigger);
            wrapper.appendChild(optionsPanel);
            select.parentNode.insertBefore(wrapper, select.nextSibling);

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.custom-select-wrapper').forEach(w => {
                    if(w !== wrapper) w.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });
        });

        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-select-wrapper').forEach(w => w.classList.remove('open'));
        });

        // 2. TAMPILKAN NAMA FILE YANG DIPILIH
        const fileInput = document.getElementById('info_file');
        const fileNameDisplay = document.getElementById('info_file_name');
        if(fileInput && fileNameDisplay) {
            fileInput.addEventListener('change', function() {
                if(this.files && this.files.length > 0) {
                    fileNameDisplay.textContent = this.files[0].name;
                    fileNameDisplay.style.color = "var(--success-text)";
                } else {
                    fileNameDisplay.textContent = "PDF, DOCX, JPG, PNG (Maks 4MB)";
                    fileNameDisplay.style.color = "var(--muted)";
                }
            });
        }

        // 3. MODAL OVERLAY ESC KEY & CLICK BG
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeTambahInfoModal();
        });
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => {
                if(e.target === overlay) closeTambahInfoModal();
            });
        });
    });

    // ================= FUNGSI MODAL TAMBAH INFO =================
    window.openTambahInfoModal = function() {
        const m = document.getElementById('tambahInfoModal');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }

    window.closeTambahInfoModal = function() {
        const m = document.getElementById('tambahInfoModal');
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);
    }
</script>
@endpush