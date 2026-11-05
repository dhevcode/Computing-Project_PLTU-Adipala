@extends('superadmin.layouts.app')

@section('title', 'Tambah Akun Baru')
@section('page_title', 'Tambah Akun')
@section('page_subtitle', 'Sistem Pengelolaan & Kontrol Akses Administrator PLTU')

@push('styles')
<style>
    /* =========================================
       ANIMASI & TRANSISI (MENGHILANGKAN KAKU)
       ========================================= */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes expandBox { from { opacity: 0; transform: translateY(-10px) scaleY(0.95); } to { opacity: 1; transform: translateY(0) scaleY(1); } }

    /* Animasi masuk & OVERFLOW VISIBLE agar dropdown tidak terpotong card */
    .card {
        animation: fadeInUp 0.5s ease-out both;
        overflow: visible !important;
    }

    .form-group-anim { animation: fadeInUp 0.4s ease-out both; position: relative; z-index: 1; }
    .form-group-anim:nth-child(1) { animation-delay: 0.1s; }
    .form-group-anim:nth-child(2) { animation-delay: 0.15s; }
    .form-group-anim:nth-child(3) { animation-delay: 0.2s; }
    .form-group-anim:nth-child(4) { animation-delay: 0.25s; }
    .form-group-anim:nth-child(5) { animation-delay: 0.3s; }
    .form-group-anim:nth-child(6) { animation-delay: 0.35s; }

    .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; position: relative; z-index: 1; overflow: visible !important; }
    .btn-group-anim { animation: fadeInUp 0.4s ease-out both; animation-delay: 0.4s; position: relative; z-index: 1; }

    /* =========================================
       STYLING FORM KHUSUS (SESUAI TEMA WEBSITE)
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
        transition: color 0.2s;
    }

    /* Input Teks bergaya Border-Bottom */
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
        border-bottom: 1.5px solid var(--border);
        outline: none;
        transition: border-bottom-color 0.3s ease, background 0.3s ease;
    }
    .form-group input::placeholder {
        color: #a6afb8;
    }

    /* Efek Fokus Halus */
    .form-group input:focus {
        border-bottom: 2px solid var(--primary);
    }
    .form-group input:focus + label,
    .form-group:focus-within label {
        color: var(--primary);
    }

    .error-text {
        font-size: 12px;
        color: var(--error-text);
        margin-top: 6px;
        animation: fadeIn 0.3s ease-in;
    }

    /* =========================================
       CUSTOM DROPDOWN SELECT (ANTI-TERTUTUP)
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
        border-bottom: 1.5px solid var(--border);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: border-bottom-color 0.3s ease;
    }
    .custom-select-trigger span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 10px;
    }
    .custom-select-trigger.is-empty {
        color: #a6afb8;
    }
    .custom-select-trigger i {
        color: var(--border);
        font-size: 12px;
        flex-shrink: 0;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.3s;
    }
    .custom-select-wrapper.open .custom-select-trigger {
        border-bottom-color: var(--primary);
    }
    .custom-select-wrapper.open .custom-select-trigger i {
        transform: rotate(180deg);
        color: var(--primary);
    }

    /* Panel Melayang Dropdown (Solid & Top Z-Index) */
    .custom-select-options {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 100%;
        min-width: 100%;
        background: var(--page, #ffffff);
        border: 1px solid var(--border);
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        z-index: 9999;
        padding: 8px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px) scale(0.98);
        transform-origin: top;
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        max-height: 250px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .custom-select-wrapper.open .custom-select-options {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    /* Item Pilihan - Teks otomatis turun jika kepanjangan */
    .custom-option {
        padding: 10px 14px;
        font-size: 13.5px;
        color: var(--text);
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
        margin-bottom: 2px;
        white-space: normal; /* Mengizinkan teks turun ke baris baru */
        word-wrap: break-word;
        line-height: 1.4;
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

    /* Styling Box Dinamis (muncul halus) */
    .box-dynamic-anim {
        animation: expandBox 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        transform-origin: top;
    }

    .box-divisi-baru {
        background: var(--page);
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        border: 1.5px dashed var(--border);
        transition: all 0.3s;
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
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-cancel { background: var(--page, #ffffff); color: var(--text); border: 1.5px solid var(--border); }
    .btn-cancel:hover { background: #f4f7f9; transform: translateY(-2px); }
    .btn-save { background: var(--primary); color: #ffffff; border: 1.5px solid var(--primary); }
    .btn-save:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0, 0.15); }
    .btn-save:active { transform: translateY(0); }

    @media (max-width: 760px) {
        .grid { grid-template-columns: 1fr; gap: 0; }
        .button-group { flex-direction: column-reverse; }
        .btn-cancel, .btn-save { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header" style="border-bottom: none; padding-bottom: 0; overflow: visible !important;">
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

        <div style="padding: 24px; overflow: visible !important;">
            <form action="{{ route('superadmin.store') }}" method="POST">
                @csrf

                <div class="form-group form-group-anim">
                    <label>Nama Lengkap Admin *</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Ahmad Rizki">
                    @error('nama_lengkap') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="grid form-group-anim">
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

                <div class="grid form-group-anim">
                    <div class="form-group">
                        <label>Kata Sandi (Password) *</label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter">
                        @error('password') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Bagian / Divisi Penugasan *</label>
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

                <div class="grid form-group-anim">
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
                <div id="field_pilih_mahasiswa" class="box-divisi-baru d-none" style="background: rgba(34, 197, 94, 0.1); border-color: #166534;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="color: #166534;">
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

                <div class="button-group btn-group-anim">
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

    // Fungsi reset Z-Index (Mencegah bug UI di Mobile)
    function resetZIndex() {
        document.querySelectorAll('.form-group-anim, .grid').forEach(el => {
            el.style.zIndex = '1';
        });
    }

    nativeSelects.forEach(select => {
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper';

        const activeOption = select.options[select.selectedIndex];
        const isPlaceholder = activeOption.disabled;

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

            if(option.value === 'new' || option.text.includes('+ Tambah')) {
                optDiv.classList.add('is-new');
            }

            optDiv.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                select.value = option.value;
                select.dispatchEvent(new Event('change'));

                trigger.querySelector('span').textContent = option.text;
                trigger.classList.remove('is-empty');
                wrapper.classList.remove('open');
                resetZIndex();

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
            const wasOpen = wrapper.classList.contains('open');

            // Tutup dropdown lain & kembalikan Z-Index awal
            document.querySelectorAll('.custom-select-wrapper').forEach(w => w.classList.remove('open'));
            resetZIndex();

            if (!wasOpen) {
                wrapper.classList.add('open');
                // Paksa Parent untuk naik ke layer teratas agar tidak tertutup
                const parentAnim = wrapper.closest('.form-group-anim');
                const parentGrid = wrapper.closest('.grid');
                if (parentAnim) parentAnim.style.zIndex = '999';
                if (parentGrid) parentGrid.style.zIndex = '999';
            }
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.custom-select-wrapper').forEach(w => w.classList.remove('open'));
        resetZIndex();
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
                newDivisiBox.classList.add('box-dynamic-anim');
                inputNamaDivisi.required = true;
            } else {
                newDivisiBox.classList.add('d-none');
                newDivisiBox.classList.remove('box-dynamic-anim');
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
            if (this.value === 'Peserta') {
                mhsBox.classList.remove('d-none');
                mhsBox.classList.add('box-dynamic-anim');
            } else {
                mhsBox.classList.add('d-none');
                mhsBox.classList.remove('box-dynamic-anim');
            }
        });
    }

    if (selectMhs) {
        selectMhs.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                if (opt.dataset.nama && inputs.nama) inputs.nama.value = opt.dataset.nama;
                if (opt.dataset.nim && inputs.username) inputs.username.value = opt.dataset.nim;
                if (opt.dataset.email && inputs.email) inputs.email.value = opt.dataset.email;

                if (opt.dataset.divisi && selectDivisi) {
                    selectDivisi.value = opt.dataset.divisi;
                    selectDivisi.dispatchEvent(new Event('change'));

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
