@extends('admin.layouts.app')

@section('title', 'Edit Data Mahasiswa')
@section('page_title', 'Edit Data Mahasiswa')
@section('page_subtitle', 'Perbarui informasi mahasiswa PKL dan simpan perubahan terbaru')

@push('styles')
<style>
    /* =========================================
       STYLING FORM & TYPOGRAPHY
       ========================================= */
    .form-group { margin-bottom: 24px; }
    .form-group label { display: block; font-size: 13.5px; font-weight: 500; color: var(--text); margin-bottom: 6px; }
    .form-group input[type="text"], .form-group input[type="email"] {
        width: 100%; padding: 10px 0; font-family: inherit; font-size: 14px; color: var(--text);
        background: transparent; border: none; border-bottom: 1.5px solid var(--primary); outline: none; transition: border-color .2s;
    }
    .form-group input::placeholder { color: #a6afb8; }
    .form-group input:focus { border-bottom: 2px solid var(--primary-dark); }
    .error-text { font-size: 12px; color: var(--error-text); margin-top: 6px; }

    .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .section-divider { font-size: 14px; font-weight: 700; color: var(--primary-dark); margin: 32px 0 20px; padding-bottom: 8px; border-bottom: 2px solid var(--border); display: flex; align-items: center; gap: 8px; }

    /* =========================================
       CUSTOM DROPDOWN SELECT
       ========================================= */
    .custom-select-wrapper { position: relative; width: 100%; user-select: none; }
    .custom-select-trigger {
        width: 100%; padding: 10px 0; font-size: 14px; color: var(--text); background: transparent; border: none;
        border-bottom: 1.5px solid var(--primary); cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: border-bottom 0.2s;
    }
    .custom-select-trigger.is-empty { color: #a6afb8; }
    .custom-select-trigger i { color: var(--primary); font-size: 12px; transition: transform 0.3s; }
    .custom-select-wrapper.open .custom-select-trigger i { transform: rotate(180deg); }

    .custom-select-options {
        position: absolute; top: calc(100% + 8px); left: 0; width: 100%; background: #fff;
        border: 1px solid var(--border); border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); z-index: 99; padding: 8px;
        opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.2s ease; max-height: 250px; overflow-y: auto;
    }
    .custom-select-wrapper.open .custom-select-options { opacity: 1; visibility: visible; transform: translateY(0); }
    .custom-option { padding: 10px 14px; font-size: 13.5px; color: var(--text); border-radius: 8px; cursor: pointer; transition: background 0.2s; margin-bottom: 2px; }
    .custom-option:hover, .custom-option.selected { background: var(--primary-soft); color: var(--primary-dark); font-weight: 500; }
    .custom-option.is-new { border-top: 1px dashed var(--border); border-radius: 0 0 8px 8px; margin-top: 6px; padding-top: 12px; color: var(--primary-dark); font-weight: 600; }

    /* =========================================
       CUSTOM DATE PICKER (FLATPICKR OVERRIDE)
       ========================================= */
    .flatpickr-input { width: 100% !important; padding: 10px 0 !important; font-family: inherit !important; font-size: 14px !important; color: var(--text) !important; background: transparent !important; border: none !important; border-bottom: 1.5px solid var(--primary) !important; border-radius: 0 !important; box-shadow: none !important; outline: none !important; cursor: pointer; }
    .flatpickr-input:focus { border-bottom: 2px solid var(--primary-dark) !important; }
    .date-wrapper { position: relative; }
    .date-wrapper::after { content: '\f133'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; right: 0; top: 50%; transform: translateY(-50%); color: var(--primary); pointer-events: none; }

    /* =========================================
       CUSTOM FILE UPLOAD AREA (STYLE EDIT)
       ========================================= */
    .upload-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .upload-item { margin-bottom: 0; }
    .upload-item label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text); }

    .upload-box {
        background: var(--page); border: 1.5px dashed var(--border); border-radius: 12px; padding: 20px 16px;
        text-align: center; position: relative; transition: all 0.2s; cursor: pointer; height: 100%; display: flex; flex-direction: column; justify-content: center;
    }
    .upload-box:hover { border-color: var(--primary); background: var(--primary-soft); }
    .upload-box input[type="file"] { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }

    .upload-icon { font-size: 26px; color: var(--primary); margin-bottom: 12px; }
    .upload-box strong { font-size: 13.5px; font-weight: 600; color: var(--text); display: block; margin-bottom: 2px; }
    .upload-box span { font-size: 11.5px; color: var(--muted); display: block; }

    .file-selected-name { font-size: 12px; color: var(--success-text); font-weight: 600; margin-top: 10px; display: none; }
    .current-file { font-size: 12px; color: var(--primary-dark); font-weight: 600; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 6px; }

    /* =========================================
       BUTTONS & BOXES
       ========================================= */
    .box-dinamis { background: var(--page); padding: 16px; border-radius: 8px; margin-top: 14px; border: 1.5px dashed var(--border); }
    .button-group { display: flex; justify-content: flex-end; gap: 12px; margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--border); }
    .btn-cancel, .btn-save { padding: 12px 20px; font-size: 13.5px; font-weight: 600; border-radius: 8px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all .2s; border: none; font-family: inherit; }
    .btn-cancel { background: #fff; color: var(--text); border: 1.5px solid var(--border); }
    .btn-cancel:hover { background: #f4f7f9; }
    .btn-save { background: var(--primary); color: #fff; border: 1.5px solid var(--primary); }
    .btn-save:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

    /* =========================================
       RESPONSIVE
       ========================================= */
    @media (max-width: 992px) {
        .upload-grid { grid-template-columns: repeat(2, 1fr); gap: 24px; }
    }
    @media (max-width: 760px) {
        .grid { grid-template-columns: 1fr; gap: 0; }
        .upload-grid { grid-template-columns: 1fr; gap: 24px; margin-bottom: 10px; }
        .upload-item { margin-bottom: 4px; }
        .button-group { flex-direction: column-reverse; }
        .btn-cancel, .btn-save { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

    <!-- Flash Messages -->
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

    <div class="card animate-fade-up" style="max-width: 900px; margin: 0 auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border);">
        <div class="card-header" style="padding: 24px; border-bottom: 1px solid var(--page);">
            <div class="card-title-group" style="display: flex; gap: 14px; align-items: center;">
                <div class="avatar-circle" style="background: #E8F5F5; color: var(--primary); font-size: 18px; width: 46px; height: 46px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 4px;">Edit Data Mahasiswa</h3>
                    <p style="font-size: 13px; color: var(--muted); margin: 0;">Perbarui informasi biodata, penempatan, dan status peserta.</p>
                </div>
            </div>
        </div>

        <div style="padding: 24px;">
            <form action="{{ route('admin.update', $pengajuan->id_pengajuan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- SEKSI 1: BIODATA -->
                <div class="section-divider" style="margin-top: 0;">
                    <i class="fa-solid fa-user"></i> 1. Biodata Pribadi Mahasiswa
                </div>
                <div class="grid">
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color: var(--error-text);">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pengajuan->mahasiswa->nama_lengkap) }}" required>
                        @error('nama_lengkap') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label>NIM / NISN <span style="color: var(--error-text);">*</span></label>
                        <input type="text" name="nim" value="{{ old('nim', $pengajuan->mahasiswa->nim) }}" required>
                        @error('nim') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Jenis Kelamin <span style="color: var(--error-text);">*</span></label>
                        <select name="jenis_kelamin" class="d-none custom-select" required>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $pengajuan->mahasiswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $pengajuan->mahasiswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label>Alamat Email <span style="color: var(--error-text);">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $pengajuan->mahasiswa->email) }}" required>
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>No. HP / WhatsApp <span style="color: var(--error-text);">*</span></label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $pengajuan->mahasiswa->no_telepon) }}" required>
                        @error('no_telepon') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label>Kontak Darurat (Orang Tua / Wali)</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $pengajuan->mahasiswa->emergency_contact_name) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>No. HP Kontak Darurat</label>
                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $pengajuan->mahasiswa->emergency_contact_phone) }}">
                </div>

                <!-- SEKSI 2: AKADEMIK & PENUGASAN -->
                <div class="section-divider">
                    <i class="fa-solid fa-building-columns"></i> 2. Institusi Asal & Penempatan PLTU
                </div>
                <div class="grid">
                    <div class="form-group">
                        <label>Asal Kampus / Sekolah <span style="color: var(--error-text);">*</span></label>
                        <select name="universitas_id" id="select_univ" class="d-none custom-select" required>
                            @foreach($universitas as $u)
                                <option value="{{ $u->id_universitas }}" {{ old('universitas_id', $pengajuan->mahasiswa->universitas_id) == $u->id_universitas ? 'selected' : '' }}>{{ $u->nama_universitas }}</option>
                            @endforeach
                            <option value="new">+ Tambah Kampus Baru...</option>
                        </select>
                        <div id="box_univ_baru" class="box-dinamis d-none">
                            <input type="text" name="nama_universitas_baru" placeholder="Tulis nama kampus baru...">
                            @error('nama_universitas_baru') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jurusan / Program Studi <span style="color: var(--error-text);">*</span></label>
                        <select name="jurusan_id" id="select_jur" class="d-none custom-select" required>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}" {{ old('jurusan_id', $pengajuan->mahasiswa->jurusan_id) == $j->id_jurusan ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                            @endforeach
                            <option value="new">+ Tambah Jurusan Baru...</option>
                        </select>
                        <div id="box_jur_baru" class="box-dinamis d-none">
                            <input type="text" name="nama_jurusan_baru" placeholder="Tulis nama jurusan baru...">
                            @error('nama_jurusan_baru') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Divisi Penempatan <span style="color: var(--error-text);">*</span></label>
                        <select name="divisi_id" id="select_divisi" class="d-none custom-select" required>
                            @foreach($divisi as $d)
                                <option value="{{ $d->id_divisi }}" {{ old('divisi_id', $pengajuan->divisi_id) == $d->id_divisi ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>
                            @endforeach
                            <option value="new">+ Tambah Divisi Baru...</option>
                        </select>
                        <div id="box_divisi_baru" class="box-dinamis d-none">
                            <input type="text" name="nama_divisi_baru" placeholder="Tulis nama divisi baru...">
                            @error('nama_divisi_baru') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Pembimbing Lapangan</label>
                        @if(isset($pembimbings) && count($pembimbings) > 0)
                            <select name="nama_pembimbing" class="d-none custom-select">
                                <option value="">-- Pilih Pembimbing Lapangan --</option>
                                @foreach($pembimbings as $p)
                                    <option value="{{ $p->nama_lengkap }}" {{ old('nama_pembimbing', $pengajuan->nama_pembimbing) == $p->nama_lengkap ? 'selected' : '' }}>{{ $p->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" name="nama_pembimbing" value="{{ old('nama_pembimbing', $pengajuan->nama_pembimbing) }}" placeholder="Tulis nama pembimbing...">
                        @endif
                        @error('nama_pembimbing') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Tanggal Mulai Magang <span style="color: var(--error-text);">*</span></label>
                        <div class="date-wrapper">
                            <input type="text" class="flatpickr-date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $pengajuan->tanggal_mulai) }}" required>
                        </div>
                        @error('tanggal_mulai') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Tanggal Selesai Magang <span style="color: var(--error-text);">*</span></label>
                        <div class="date-wrapper">
                            <input type="text" class="flatpickr-date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pengajuan->tanggal_selesai) }}" required>
                        </div>
                        @error('tanggal_selesai') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- SEKSI 3: PERBARUI DOKUMEN -->
                <div class="section-divider">
                    <i class="fa-solid fa-file-arrow-up"></i> 3. Perbarui Dokumen (Kosongkan jika tidak diubah)
                </div>

                <div class="upload-grid">
                    <div class="upload-item">
                        <label>Ganti Surat Pengantar</label>
                        <div class="upload-box" onclick="document.getElementById('surat_pengantar').click()">
                            <input type="file" name="surat_permohonan" id="surat_pengantar" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="updateFileName(this, 'surat-info')">
                            <div class="upload-icon"><i class="fa-solid fa-file-signature"></i></div>
                            <strong>Klik untuk Ubah Dokumen</strong>
                            <span>PDF / DOC / JPG (Maks 4MB)</span>
                            <div id="surat-info" class="file-selected-name"></div>
                            @if($pengajuan->surat_permohonan)
                                <div class="current-file" id="current_surat">
                                    <i class="fa-solid fa-check-circle"></i> File tersimpan: {{ $pengajuan->surat_permohonan }}
                                </div>
                            @endif
                        </div>
                        @error('surat_permohonan') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="upload-item">
                        <label>Ganti Proposal Magang</label>
                        <div class="upload-box" onclick="document.getElementById('proposal').click()">
                            <input type="file" name="proposal" id="proposal" accept=".pdf,.doc,.docx" onchange="updateFileName(this, 'proposal-info')">
                            <div class="upload-icon"><i class="fa-solid fa-file-lines"></i></div>
                            <strong>Klik untuk Ubah Proposal</strong>
                            <span>PDF / DOCX (Maks 4MB)</span>
                            <div id="proposal-info" class="file-selected-name"></div>
                            @if($pengajuan->proposal)
                                <div class="current-file" id="current_proposal">
                                    <i class="fa-solid fa-check-circle"></i> File tersimpan: {{ $pengajuan->proposal }}
                                </div>
                            @endif
                        </div>
                        @error('proposal') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="upload-item">
                        <label>Ganti File CV</label>
                        <div class="upload-box" onclick="document.getElementById('cv').click()">
                            <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx" onchange="updateFileName(this, 'cv-info')">
                            <div class="upload-icon"><i class="fa-regular fa-address-card"></i></div>
                            <strong>Klik untuk Ubah CV</strong>
                            <span>PDF / DOCX (Maks 4MB)</span>
                            <div id="cv-info" class="file-selected-name"></div>
                            @if($pengajuan->cv)
                                <div class="current-file" id="current_cv">
                                    <i class="fa-solid fa-check-circle"></i> File tersimpan: {{ $pengajuan->cv }}
                                </div>
                            @endif
                        </div>
                        @error('cv') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="button-group">
                    <a href="{{ route('admin.detail', $pengajuan->id_pengajuan) }}" class="btn-cancel">Batal</a>
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
        const nativeSelects = document.querySelectorAll('select.custom-select');
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

        // 3. LOGIKA DYNAMIC BOX (KAMPUS / JURUSAN / DIVISI BARU)
        const mappings = [
            { selectId: 'select_univ', boxId: 'box_univ_baru' },
            { selectId: 'select_jur', boxId: 'box_jur_baru' },
            { selectId: 'select_divisi', boxId: 'box_divisi_baru' }
        ];

        mappings.forEach(m => {
            const selectEl = document.getElementById(m.selectId);
            const boxEl = document.getElementById(m.boxId);
            if(selectEl && boxEl) {
                selectEl.addEventListener('change', function() {
                    const inputEl = boxEl.querySelector('input');
                    if (this.value === 'new') {
                        boxEl.classList.remove('d-none');
                        if(inputEl) inputEl.required = true;
                    } else {
                        boxEl.classList.add('d-none');
                        if(inputEl) {
                            inputEl.required = false;
                            inputEl.value = '';
                        }
                    }
                });
            }
        });
    });

    // 4. UPDATE NAMA FILE SAAT UPLOAD (DAN SEMBUNYIKAN FILE LAMA)
    window.updateFileName = function(input, displayId) {
        const display = document.getElementById(displayId);
        // Mapping prefix ID dengan awalan box file lama (current_surat, current_proposal, current_cv)
        let currentFileId = null;
        if(displayId === 'surat-info') currentFileId = 'current_surat';
        if(displayId === 'proposal-info') currentFileId = 'current_proposal';
        if(displayId === 'cv-info') currentFileId = 'current_cv';

        const currentFileDiv = currentFileId ? document.getElementById(currentFileId) : null;

        if (input.files && input.files.length > 0) {
            display.textContent = "Baru dipilih: " + input.files[0].name;
            display.style.display = 'block';
            input.parentElement.style.borderColor = 'var(--success-text)';
            input.parentElement.style.background = 'var(--success-bg)';
            input.parentElement.querySelector('.upload-icon').style.color = 'var(--success-text)';

            if(currentFileDiv) currentFileDiv.style.display = 'none';
        } else {
            display.style.display = 'none';
            input.parentElement.style.borderColor = '';
            input.parentElement.style.background = '';
            input.parentElement.querySelector('.upload-icon').style.color = 'var(--primary)';

            if(currentFileDiv) currentFileDiv.style.display = 'flex';
        }
    }
</script>
@endpush
