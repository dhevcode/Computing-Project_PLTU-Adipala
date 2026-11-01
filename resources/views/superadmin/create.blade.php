@extends('superadmin.layouts.app')

@section('title', 'Tambah Akun Baru')
@section('page_title', 'Tambah Akun')
@section('page_subtitle', 'Sistem Pengelolaan & Kontrol Akses Administrator PLTU')

@push('styles')
<style>
    /* =========================================
       STYLING FORM KHUSUS (SESUAI GAMBAR 2 & 3)
       ========================================= */
    .form-group {
        margin-bottom: 24px;
    }
    .form-group label {
        display: block;
        font-size: 13.5px;
        font-weight: 500;
        color: var(--text);
        margin-bottom: 4px;
    }

    /* Input Teks bergaya Border-Bottom (Seperti Gambar 2 & 3) */
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"] {
        width: 100%;
        padding: 10px 0;
        font-family: inherit;
        font-size: 14px;
        color: var(--text);
        background: transparent;
        border: none;
        border-bottom: 1.5px solid var(--primary); /* Garis bawah teal */
        outline: none;
        transition: border-color .2s;
    }
    .form-group input::placeholder {
        color: #a6afb8;
    }
    .form-group input:focus {
        border-bottom: 2px solid var(--primary-dark);
    }
    .error-text {
        font-size: 12px;
        color: var(--error-text);
        margin-top: 6px;
    }
    .grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    /* =========================================
       CUSTOM DROPDOWN SELECT (SESUAI GAMBAR 2)
       ========================================= */
    .custom-select-wrapper {
        position: relative;
        width: 100%;
        user-select: none;
    }
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
    .custom-select-trigger.is-empty {
        color: #a6afb8; /* Warna placeholder */
    }
    .custom-select-trigger i {
        color: var(--primary);
        font-size: 12px;
        transition: transform 0.3s;
    }
    .custom-select-wrapper.open .custom-select-trigger i {
        transform: rotate(180deg);
    }

    /* Panel Melayang Dropdown */
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
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Item Pilihan */
    .custom-option {
        padding: 10px 14px;
        font-size: 13.5px;
        color: var(--text);
        border-radius: 8px; /* Rounded mirip Gambar 2 */
        cursor: pointer;
        transition: background 0.2s;
        margin-bottom: 2px;
    }
    .custom-option:hover, .custom-option.selected {
        background: var(--primary-soft);
        color: var(--primary-dark);
        font-weight: 500;
    }

    /* Garis pemisah untuk opsi "+ Tambah Divisi Baru" */
    .custom-option.is-new {
        border-top: 1px dashed var(--border);
        border-radius: 0 0 8px 8px;
        margin-top: 6px;
        padding-top: 12px;
        color: var(--primary-dark);
        font-weight: 600;
    }

    /* Styling Box Dinamis */
    .box-divisi-baru {
        background: var(--page);
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        border: 1.5px dashed var(--border);
    }

    /* Tombol Aksi */
    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 32px;
        padding-top: 20px;
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
    <div class="card">
        <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
            <div class="card-title-group" style="display: flex; gap: 14px; align-items: center;">
                <div class="avatar-circle" style="background: var(--primary-soft); color: var(--primary); font-size: 16px; width: 42px; height: 42px;">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h3 style="font-size: 18px;">Tambah Akun Baru</h3>
                    <p style="font-size: 13px;">Isi formulir berikut untuk mendaftarkan akun administrator baru ke dalam sistem.</p>
                </div>
            </div>
        </div>

        <div style="padding: 24px;">
            <form action="{{ route('superadmin.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nama Lengkap Admin *</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Ahmad Rizki">
                    @error('nama_lengkap') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: ahmad_admin">
                        @error('username') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Alamat Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@perusahaan.com">
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Kata Sandi (Password) *</label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter">
                        @error('password') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Bagian / Divisi Penugasan *</label>
                        {{-- Select disembunyikan menggunakan d-none, logic JS akan membangun UI baru di atasnya --}}
                        <select name="id_divisi" id="select_divisi" required class="d-none">
                            <option value="" disabled selected>Pilih Bagian / Divisi</option>
                            @foreach($divisi as $d)
                                <option value="{{ $d->id_divisi }}" {{ old('id_divisi') == $d->id_divisi ? 'selected' : '' }}>
                                    {{ $d->nama_divisi }}
                                </option>
                            @endforeach
                            <option value="new">+ Tambah Bagian/Divisi Baru...</option>
                        </select>
                        @error('id_divisi') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- FIELD INPUT NAMA DIVISI BARU -->
                <div id="field_divisi_baru" class="box-divisi-baru d-none">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="color: var(--primary-dark);"><i class="fa-solid fa-folder-plus" style="margin-right: 6px;"></i>Nama Divisi Baru</label>
                        <input type="text" name="nama_divisi_baru" id="nama_divisi_baru" value="{{ old('nama_divisi_baru') }}" placeholder="Misal: Operasi & Pemeliharaan Boiler">
                        @error('nama_divisi_baru') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Hak Akses (Role) *</label>
                        <select name="role" id="select_role" required class="d-none">
                            <option value="" disabled selected>Pilih Hak Akses</option>
                            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Pembimbing" {{ old('role') == 'Pembimbing' ? 'selected' : '' }}>Pembimbing Lapangan</option>
                            <option value="Peserta" {{ old('role') == 'Peserta' ? 'selected' : '' }}>Peserta Magang</option>
                            <option value="Super Admin" {{ old('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @error('role') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Status Akun Saat Ini *</label>
                        <select name="is_active" required class="d-none">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('is_active') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- PILIH MAHASISWA -->
                <div id="field_pilih_mahasiswa" class="box-divisi-baru d-none" style="background: var(--success-bg); border-color: var(--success-text);">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="color: var(--success-text);">
                            <i class="fa-solid fa-graduation-cap" style="margin-right: 6px;"></i>
                            Tautkan ke Data Mahasiswa Magang
                        </label>
                        <select name="id_mhs" id="select_mahasiswa" class="d-none">
                            <option value="" disabled selected>Pilih Mahasiswa yang Belum Punya Akun</option>
                            @if(isset($mahasiswaTanpaAkun))
                                @foreach($mahasiswaTanpaAkun as $m)
                                    <option value="{{ $m->id_mhs }}"
                                        data-nama="{{ $m->nama_lengkap }}"
                                        data-nim="{{ $m->nim }}"
                                        data-email="{{ $m->email }}"
                                        data-divisi="{{ $m->latestPengajuan?->divisi_id }}">
                                        {{ $m->nama_lengkap }} (NIM: {{ $m->nim }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="button-group">
                    <a href="{{ route('superadmin.dashboard') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Akun</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ==========================================
    // 1. GENERATOR CUSTOM DROPDOWN (UI GAMBAR 2)
    // ==========================================
    const nativeSelects = document.querySelectorAll('select.d-none');

    nativeSelects.forEach(select => {
        // Buat wrapper utama
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper';

        // Cek teks aktif saat ini
        const activeOption = select.options[select.selectedIndex];
        const isPlaceholder = activeOption.disabled;

        // Buat kotak yang bisa diklik (trigger)
        const trigger = document.createElement('div');
        trigger.className = `custom-select-trigger ${isPlaceholder ? 'is-empty' : ''}`;
        trigger.innerHTML = `<span>${activeOption.text}</span> <i class="fa-solid fa-chevron-down"></i>`;

        // Buat panel menu dropdown
        const optionsPanel = document.createElement('div');
        optionsPanel.className = 'custom-select-options';

        // Isi menu dropdown dengan opsi dari <select> asli
        Array.from(select.options).forEach(option => {
            if(option.disabled && option.value === "") return; // Skip placeholder

            const optDiv = document.createElement('div');
            optDiv.className = 'custom-option';
            optDiv.textContent = option.text;

            // Berikan garis putus-putus jika teksnya "+ Tambah..."
            if(option.value === 'new' || option.text.includes('+ Tambah')) {
                optDiv.classList.add('is-new');
            }

            // Event ketika item dipilih
            optDiv.addEventListener('click', (e) => {
                e.stopPropagation();

                // Ubah nilai select asli dan trigger event change
                select.value = option.value;
                select.dispatchEvent(new Event('change'));

                // Update teks dan warna pada trigger custom
                trigger.querySelector('span').textContent = option.text;
                trigger.classList.remove('is-empty');

                // Tutup dropdown
                wrapper.classList.remove('open');

                // Tandai opsi yang dipilih
                optionsPanel.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
                optDiv.classList.add('selected');
            });

            optionsPanel.appendChild(optDiv);
        });

        // Susun elemen dan masukkan ke dalam form
        wrapper.appendChild(trigger);
        wrapper.appendChild(optionsPanel);
        select.parentNode.insertBefore(wrapper, select.nextSibling);

        // Event buka/tutup dropdown
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            // Tutup dropdown lain yang sedang terbuka
            document.querySelectorAll('.custom-select-wrapper').forEach(w => {
                if(w !== wrapper) w.classList.remove('open');
            });
            wrapper.classList.toggle('open');
        });
    });

    // Menutup dropdown jika user klik area kosong di luar dropdown
    document.addEventListener('click', () => {
        document.querySelectorAll('.custom-select-wrapper').forEach(w => w.classList.remove('open'));
    });

    // ==========================================
    // 2. LOGIKA FORM DINAMIS (BAWAAN SISTEM)
    // ==========================================
    const selectDivisi = document.getElementById('select_divisi');
    const newDivisiBox = document.getElementById('field_divisi_baru');
    const inputNamaDivisi = document.getElementById('nama_divisi_baru');

    if (selectDivisi) {
        selectDivisi.addEventListener('change', function() {
            if (this.value === 'new') {
                newDivisiBox.classList.remove('d-none');
                inputNamaDivisi.required = true;
            } else {
                newDivisiBox.classList.add('d-none');
                inputNamaDivisi.required = false;
                inputNamaDivisi.value = '';
            }
        });
    }

    const selectRole = document.getElementById('select_role');
    const mhsBox = document.getElementById('field_pilih_mahasiswa');
    const selectMhs = document.getElementById('select_mahasiswa');
    const inputs = {
        nama: document.querySelector('input[name="nama_lengkap"]'),
        username: document.querySelector('input[name="username"]'),
        email: document.querySelector('input[name="email"]')
    };

    if (selectRole) {
        selectRole.addEventListener('change', function() {
            if (this.value === 'Peserta') mhsBox.classList.remove('d-none');
            else mhsBox.classList.add('d-none');
        });
    }

    if (selectMhs) {
        selectMhs.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                if (opt.dataset.nama && inputs.nama) inputs.nama.value = opt.dataset.nama;
                if (opt.dataset.nim && inputs.username) inputs.username.value = opt.dataset.nim;
                if (opt.dataset.email && inputs.email) inputs.email.value = opt.dataset.email;

                // Update select divisi secara programatis
                if (opt.dataset.divisi && selectDivisi) {
                    selectDivisi.value = opt.dataset.divisi;
                    selectDivisi.dispatchEvent(new Event('change'));

                    // Update teks pada custom trigger-nya
                    const wrapper = selectDivisi.nextElementSibling;
                    const triggerSpan = wrapper.querySelector('.custom-select-trigger span');
                    const selectedOpt = Array.from(selectDivisi.options).find(o => o.value === opt.dataset.divisi);
                    if (selectedOpt) {
                        triggerSpan.textContent = selectedOpt.text;
                        wrapper.querySelector('.custom-select-trigger').classList.remove('is-empty');
                    }
                }
            }
        });
    }
});
</script>
@endpush
