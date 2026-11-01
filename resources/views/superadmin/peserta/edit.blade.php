@extends('superadmin.layouts.app')

@section('title', 'Edit Peserta Magang')
@section('page_title', 'Edit Peserta Magang')
@section('page_subtitle', 'Perbarui informasi biodata, penempatan, dan status peserta.')

@push('styles')
<style>
    /* =========================================
       STYLING FORM KHUSUS
       ========================================= */
    .form-group { margin-bottom: 24px; }
    .form-group label {
        display: block;
        font-size: 13.5px;
        font-weight: 500;
        color: var(--text);
        margin-bottom: 6px;
    }
    .form-group input[type="text"],
    .form-group input[type="email"] {
        width: 100%;
        padding: 10px 0;
        font-family: inherit;
        font-size: 14px;
        color: var(--text);
        background: transparent;
        border: none;
        border-bottom: 1.5px solid var(--primary);
        outline: none;
        transition: border-color .2s;
    }
    .form-group input::placeholder { color: #a6afb8; }
    .form-group input:focus { border-bottom: 2px solid var(--primary-dark); }
    .error-text { font-size: 12px; color: var(--error-text); margin-top: 6px; }

    .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }

    .section-divider {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 32px 0 20px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--border);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* =========================================
       CUSTOM DROPDOWN SELECT
       ========================================= */
    .custom-select-wrapper { position: relative; width: 100%; user-select: none; }
    .custom-select-trigger {
        width: 100%;
        padding: 10px 0;
        font-size: 14px;
        color: var(--text);
        background: transparent;
        border: none;
        border-bottom: 1.5px solid var(--primary);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: border-bottom 0.2s;
    }
    .custom-select-trigger.is-empty { color: #a6afb8; }
    .custom-select-trigger i { color: var(--primary); font-size: 12px; transition: transform 0.3s; }
    .custom-select-wrapper.open .custom-select-trigger i { transform: rotate(180deg); }

    .custom-select-options {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 100%;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        z-index: 99;
        padding: 8px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease;
        max-height: 250px;
        overflow-y: auto;
    }
    .custom-select-wrapper.open .custom-select-options {
        opacity: 1; visibility: visible; transform: translateY(0);
    }
    .custom-option {
        padding: 10px 14px;
        font-size: 13.5px;
        color: var(--text);
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
        margin-bottom: 2px;
    }
    .custom-option:hover, .custom-option.selected {
        background: var(--primary-soft); color: var(--primary-dark); font-weight: 500;
    }
    .custom-option.is-new {
        border-top: 1px dashed var(--border);
        border-radius: 0 0 8px 8px;
        margin-top: 6px;
        padding-top: 12px;
        color: var(--primary-dark);
        font-weight: 600;
    }

    /* =========================================
       CUSTOM DATE PICKER (FLATPICKR OVERRIDE)
       ========================================= */
    .flatpickr-input {
        width: 100% !important;
        padding: 10px 0 !important;
        font-family: inherit !important;
        font-size: 14px !important;
        color: var(--text) !important;
        background: transparent !important;
        border: none !important;
        border-bottom: 1.5px solid var(--primary) !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        outline: none !important;
        cursor: pointer;
    }
    .flatpickr-input:focus { border-bottom: 2px solid var(--primary-dark) !important; }

    .date-wrapper { position: relative; }
    .date-wrapper::after {
        content: '\f133';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary);
        pointer-events: none;
    }

    /* =========================================
       CUSTOM FILE UPLOAD
       ========================================= */
    .upload-box {
        background: var(--page);
        border: 1.5px dashed var(--border);
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        position: relative;
        transition: all 0.2s;
        cursor: pointer;
    }
    .upload-box:hover { border-color: var(--primary); background: var(--primary-soft); }
    .upload-box input[type="file"] {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .upload-box-icon { font-size: 24px; color: var(--primary); margin-bottom: 8px; }
    .upload-box-text { font-size: 13px; font-weight: 600; color: var(--text); }
    .upload-box-sub { font-size: 11px; color: var(--muted); margin-top: 4px; }

    .file-name-display {
        font-size: 12px;
        color: var(--success-text);
        font-weight: 600;
        margin-top: 8px;
        display: none;
    }
    .current-file {
        font-size: 12px;
        color: var(--success-text);
        font-weight: 600;
        margin-top: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    /* =========================================
       BUTTONS & BOXES
       ========================================= */
    .box-dinamis {
        background: var(--page);
        padding: 16px;
        border-radius: 8px;
        margin-top: 14px;
        border: 1.5px dashed var(--border);
    }

    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }
    .btn-cancel, .btn-save {
        padding: 12px 20px;
        font-size: 13.5px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all .2s;
    }
    .btn-cancel { background: #fff; color: var(--text); border: 1.5px solid var(--border); }
    .btn-cancel:hover { background: #f4f7f9; }
    .btn-save { background: var(--primary); color: #fff; border: 1.5px solid var(--primary); }
    .btn-save:hover { background: var(--primary-dark); }

    @media (max-width: 760px) {
        .grid { grid-template-columns: 1fr; gap: 0; }
        .button-group { flex-direction: column-reverse; }
        .btn-cancel, .btn-save { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

    @php
        $pengajuan = $mahasiswa->latestPengajuan;
    @endphp

    <div class="card" style="max-width: 900px; margin: 0 auto;">
        <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
            <div class="card-title-group" style="display: flex; gap: 14px; align-items: center;">
                <div class="avatar-circle" style="background: var(--primary-soft); color: var(--primary); font-size: 16px; width: 42px; height: 42px;">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>
                    <h3 style="font-size: 18px;">Edit Data Peserta Magang</h3>
                    <p style="font-size: 13px;">Perbarui biodata, divisi penempatan, pembimbing lapangan, atau status.</p>
                </div>
            </div>
        </div>

        <div style="padding: 24px;">
            <form action="{{ route('superadmin.peserta.update', $mahasiswa->id_mhs) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- SEKSI 1: BIODATA MAHASISWA -->
                <div class="section-divider">
                    <i class="fa-solid fa-user"></i> 1. Biodata Pribadi Mahasiswa
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color: var(--error-text);">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" required>
                        @error('nama_lengkap') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>NIM / NISN <span style="color: var(--error-text);">*</span></label>
                        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required>
                        @error('nim') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Jenis Kelamin <span style="color: var(--error-text);">*</span></label>
                        <select name="jenis_kelamin" class="d-none" required>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Alamat Email <span style="color: var(--error-text);">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $mahasiswa->email) }}" required>
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>No. HP / WhatsApp <span style="color: var(--error-text);">*</span></label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $mahasiswa->no_telepon) }}" required>
                        @error('no_telepon') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Kontak Darurat (Orang Tua / Wali)</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $mahasiswa->emergency_contact_name) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>No. HP Kontak Darurat</label>
                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $mahasiswa->emergency_contact_phone) }}">
                </div>

                <!-- SEKSI 2: AKADEMIK & PENUGASAN -->
                <div class="section-divider">
                    <i class="fa-solid fa-building-columns"></i> 2. Institusi Asal & Penempatan PLTU
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Asal Kampus / Sekolah <span style="color: var(--error-text);">*</span></label>
                        <select name="universitas_id" id="select_univ" class="d-none" required>
                            @foreach($universitas as $u)
                                <option value="{{ $u->id_universitas }}" {{ old('universitas_id', $mahasiswa->universitas_id) == $u->id_universitas ? 'selected' : '' }}>
                                    {{ $u->nama_universitas }}
                                </option>
                            @endforeach
                            <option value="new">+ Tambah Kampus Baru...</option>
                        </select>
                        <div id="box_univ_baru" class="box-dinamis d-none">
                            <input type="text" name="nama_universitas_baru" placeholder="Tulis nama kampus baru...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jurusan / Program Studi <span style="color: var(--error-text);">*</span></label>
                        <select name="jurusan_id" id="select_jur" class="d-none" required>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}" {{ old('jurusan_id', $mahasiswa->jurusan_id) == $j->id_jurusan ? 'selected' : '' }}>
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                            <option value="new">+ Tambah Jurusan Baru...</option>
                        </select>
                        <div id="box_jur_baru" class="box-dinamis d-none">
                            <input type="text" name="nama_jurusan_baru" placeholder="Tulis nama jurusan baru...">
                        </div>
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Divisi Penempatan <span style="color: var(--error-text);">*</span></label>
                        <select name="divisi_id" class="d-none" required>
                            @foreach($divisi as $d)
                                <option value="{{ $d->id_divisi }}" {{ old('divisi_id', $pengajuan?->divisi_id) == $d->id_divisi ? 'selected' : '' }}>
                                    {{ $d->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pembimbing Lapangan</label>
                        <select name="pembimbing_id" class="d-none">
                            <option value="">-- Pilih Pembimbing --</option>
                            @foreach($pembimbings as $p)
                                <option value="{{ $p->id_users }}" {{ old('pembimbing_id', $pengajuan?->pembimbing_id) == $p->id_users ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }} ({{ $p->divisi->nama_divisi ?? 'Pembimbing' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Tanggal Mulai Magang <span style="color: var(--error-text);">*</span></label>
                        <div class="date-wrapper">
                            <input type="text" name="tanggal_mulai" class="flatpickr-date" value="{{ old('tanggal_mulai', $pengajuan?->tanggal_mulai) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Selesai Magang <span style="color: var(--error-text);">*</span></label>
                        <div class="date-wrapper">
                            <input type="text" name="tanggal_selesai" class="flatpickr-date" value="{{ old('tanggal_selesai', $pengajuan?->tanggal_selesai) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status Pelaksanaan Magang <span style="color: var(--error-text);">*</span></label>
                    <select name="status" class="d-none" required>
                        <option value="Sedang Magang" {{ old('status', $pengajuan?->status) == 'Sedang Magang' ? 'selected' : '' }}>Sedang Magang</option>
                        <option value="Akan Magang" {{ old('status', $pengajuan?->status) == 'Akan Magang' ? 'selected' : '' }}>Akan Magang</option>
                        <option value="Selesai Magang" {{ old('status', $pengajuan?->status) == 'Selesai Magang' ? 'selected' : '' }}>Selesai Magang</option>
                    </select>
                </div>

                <!-- SEKSI 3: PERBARUI DOKUMEN -->
                <div class="section-divider">
                    <i class="fa-solid fa-file-arrow-up"></i> 3. Perbarui Dokumen (Kosongkan jika tidak diubah)
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Ganti File CV</label>
                        <div class="upload-box" onclick="document.getElementById('file_cv').click()">
                            <input type="file" name="cv" id="file_cv" accept=".pdf,.doc,.docx" onchange="updateFileName(this, 'name_cv')">
                            <i class="fa-solid fa-cloud-arrow-up upload-box-icon"></i>
                            <div class="upload-box-text">Klik untuk Ubah CV</div>
                            <div class="upload-box-sub">PDF, DOCX (Maks. 5MB)</div>
                            <div class="file-name-display" id="name_cv"></div>

                            @if($pengajuan && $pengajuan->cv)
                                <div class="current-file" id="current_cv">
                                    <i class="fa-solid fa-check-circle"></i> File tersimpan: {{ $pengajuan->cv }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ganti File Proposal</label>
                        <div class="upload-box" onclick="document.getElementById('file_prop').click()">
                            <input type="file" name="proposal" id="file_prop" accept=".pdf,.doc,.docx" onchange="updateFileName(this, 'name_prop')">
                            <i class="fa-solid fa-cloud-arrow-up upload-box-icon"></i>
                            <div class="upload-box-text">Klik untuk Ubah Proposal</div>
                            <div class="upload-box-sub">PDF, DOCX (Maks. 5MB)</div>
                            <div class="file-name-display" id="name_prop"></div>

                            @if($pengajuan && $pengajuan->proposal)
                                <div class="current-file" id="current_prop">
                                    <i class="fa-solid fa-check-circle"></i> File tersimpan: {{ $pengajuan->proposal }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Ganti File Surat Pengantar Kampus</label>
                    <div class="upload-box" onclick="document.getElementById('file_srt').click()">
                        <input type="file" name="surat_permohonan" id="file_srt" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="updateFileName(this, 'name_srt')">
                        <i class="fa-solid fa-cloud-arrow-up upload-box-icon"></i>
                        <div class="upload-box-text">Klik untuk Ubah Surat Pengantar</div>
                        <div class="upload-box-sub">PDF, DOCX, JPG (Maks. 5MB)</div>
                        <div class="file-name-display" id="name_srt"></div>

                        @if($pengajuan && $pengajuan->surat_permohonan)
                            <div class="current-file" id="current_srt">
                                <i class="fa-solid fa-check-circle"></i> File tersimpan: {{ $pengajuan->surat_permohonan }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="button-group">
                    <a href="{{ route('superadmin.peserta') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // 1. INIT FLATPICKR
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                locale: "id",
                disableMobile: "true"
            });
        }

        // 2. GENERATOR CUSTOM DROPDOWN
        const nativeSelects = document.querySelectorAll('select.d-none');
        nativeSelects.forEach(select => {
            const wrapper = document.createElement('div');
            wrapper.className = 'custom-select-wrapper';

            const activeOption = select.options[select.selectedIndex] || select.options[0];
            const isPlaceholder = activeOption.disabled || activeOption.value === "";

            const trigger = document.createElement('div');
            trigger.className = `custom-select-trigger ${isPlaceholder ? 'is-empty' : ''}`;
            trigger.innerHTML = `<span>${activeOption.text}</span> <i class="fa-solid fa-chevron-down"></i>`;

            const optionsPanel = document.createElement('div');
            optionsPanel.className = 'custom-select-options';

            Array.from(select.options).forEach(option => {
                if(option.disabled && option.value === "") return;

                const optDiv = document.createElement('div');
                optDiv.className = 'custom-option';
                optDiv.textContent = option.text;
                if (option.selected) optDiv.classList.add('selected');

                if(option.value === 'new' || option.text.includes('+ Tambah')) {
                    optDiv.classList.add('is-new');
                }

                optDiv.addEventListener('click', (e) => {
                    e.stopPropagation();
                    select.value = option.value;
                    select.dispatchEvent(new Event('change'));

                    trigger.querySelector('span').textContent = option.text;
                    trigger.classList.remove('is-empty');
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

        // 3. LOGIKA DYNAMIC BOX
        const selectUniv = document.getElementById('select_univ');
        const boxUniv = document.getElementById('box_univ_baru');
        if(selectUniv) {
            selectUniv.addEventListener('change', function() {
                if (this.value === 'new') {
                    boxUniv.classList.remove('d-none');
                    boxUniv.querySelector('input').required = true;
                } else {
                    boxUniv.classList.add('d-none');
                    boxUniv.querySelector('input').required = false;
                }
            });
        }

        const selectJur = document.getElementById('select_jur');
        const boxJur = document.getElementById('box_jur_baru');
        if(selectJur) {
            selectJur.addEventListener('change', function() {
                if (this.value === 'new') {
                    boxJur.classList.remove('d-none');
                    boxJur.querySelector('input').required = true;
                } else {
                    boxJur.classList.add('d-none');
                    boxJur.querySelector('input').required = false;
                }
            });
        }
    });

    // 4. UPDATE NAMA FILE & HIDE CURRENT FILE
    function updateFileName(input, displayId) {
        const display = document.getElementById(displayId);
        // Mapping prefix ID dengan awalan box file lama
        const currentFileId = displayId.replace('name_', 'current_');
        const currentFileDiv = document.getElementById(currentFileId);

        if (input.files && input.files.length > 0) {
            display.textContent = "Baru dipilih: " + input.files[0].name;
            display.style.display = 'block';
            input.parentElement.style.borderColor = 'var(--success-text)';
            input.parentElement.style.background = 'var(--success-bg)';

            // Sembunyikan label file lama jika ada
            if(currentFileDiv) currentFileDiv.style.display = 'none';
        } else {
            display.style.display = 'none';
            input.parentElement.style.borderColor = '';
            input.parentElement.style.background = '';

            // Tampilkan kembali label file lama jika dibatalkan
            if(currentFileDiv) currentFileDiv.style.display = 'flex';
        }
    }
</script>
@endpush
