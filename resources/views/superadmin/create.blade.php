<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin Baru - Monitoring PKL</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/superadmin/create.css') }}">
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <div class="header-left">
            <div class="logo-box">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
            </div>
            <div class="header-title">
                <h2>Monitoring Mahasiswa PKL</h2>
                <p>Kelola dan pantau data mahasiswa PKL dengan mudah dan efisien.</p>
            </div>
        </div>
        <div class="header-right">
            <a href="{{ route('superadmin.dashboard') }}" class="btn-header-action" title="Kembali ke Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-header-icon" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- CONTENT -->
    <div class="content">
        <div class="card">
            <div class="card-title-box">
                <div class="icon-badge">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h2>Tambah Admin Baru</h2>
                    <p>Isi formulir berikut untuk mendaftarkan akun administrator baru ke dalam sistem.</p>
                </div>
            </div>

            <form action="{{ route('superadmin.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nama Lengkap Admin</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Ahmad Rizki">
                    @error('nama_lengkap') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: ahmad_admin">
                        @error('username') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@perusahaan.com">
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Kata Sandi (Password)</label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter">
                        @error('password') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Bagian / Divisi Penugasan</label>
                        <select name="id_divisi" id="select_divisi" required>
                            <option value="" disabled selected>Pilih Bagian / Divisi</option>
                            @foreach($divisi as $d)
                                <option value="{{ $d->id_divisi }}" {{ old('id_divisi') == $d->id_divisi ? 'selected' : '' }}>
                                    {{ $d->nama_divisi }}
                                </option>
                            @endforeach
                            <option value="new" style="font-weight: 700; color: #0A5F63;" {{ old('id_divisi') == 'new' ? 'selected' : '' }}>
                                + Tambah Bagian/Divisi Baru...
                            </option>
                        </select>
                        @error('id_divisi') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- FIELD INPUT NAMA DIVISI BARU -->
                <div id="field_divisi_baru" class="box-divisi-baru d-none">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="color: #0A5F63;"><i class="fa-solid fa-folder-plus" style="margin-right: 6px;"></i>Nama Divisi Baru</label>
                        <input type="text" name="nama_divisi_baru" id="nama_divisi_baru" value="{{ old('nama_divisi_baru') }}" placeholder="Misal: Operasi & Pemeliharaan Boiler">
                        @error('nama_divisi_baru') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Hak Akses (Role)</label>
                        <select name="role" id="select_role" required>
                            <option value="" disabled selected>Pilih Hak Akses</option>
                            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Pembimbing" {{ old('role') == 'Pembimbing' ? 'selected' : '' }}>Pembimbing Lapangan</option>
                            <option value="Peserta" {{ old('role') == 'Peserta' ? 'selected' : '' }}>Peserta Magang</option>
                            <option value="Super Admin" {{ old('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @error('role') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Status Akun Saat Ini</label>
                        <select name="is_active" required>
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('is_active') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- PILIH MAHASISWA (MUNCUL JIKA ROLE PESERTA DIPILIH) -->
                <div id="field_pilih_mahasiswa" class="box-divisi-baru d-none" style="background: #F0FDF4; border: 1.5px dashed #10B981; margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="color: #047857;">
                            <i class="fa-solid fa-graduation-cap" style="margin-right: 6px;"></i>
                            Tautkan ke Data Mahasiswa Magang (Otomatis Isi Data)
                        </label>
                        <select name="id_mhs" id="select_mahasiswa" style="width: 100%; height: 46px; border: 1.5px solid #A7F3D0; border-radius: 10px; padding: 0 14px; font-size: 13.5px; background: #fff;">
                            <option value="">-- Pilih Mahasiswa yang Belum Punya Akun --</option>
                            @if(isset($mahasiswaTanpaAkun))
                                @foreach($mahasiswaTanpaAkun as $m)
                                    <option value="{{ $m->id_mhs }}" 
                                        data-nama="{{ $m->nama_lengkap }}" 
                                        data-nim="{{ $m->nim }}" 
                                        data-email="{{ $m->email }}"
                                        data-divisi="{{ $m->latestPengajuan?->divisi_id }}">
                                        {{ $m->nama_lengkap }} (NIM: {{ $m->nim }}) - {{ $m->universitas->nama_universitas ?? '' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <small style="color: #065F46; display: block; margin-top: 6px;">
                            * Memilih mahasiswa akan otomatis mengisi Nama Lengkap, Username (NIM), Email, dan Divisi.
                        </small>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectDivisi = document.getElementById('select_divisi');
        const newDivisiBox = document.getElementById('field_divisi_baru');
        const inputNamaDivisi = document.getElementById('nama_divisi_baru');

        function checkDivisiSelection() {
            if (selectDivisi && selectDivisi.value === 'new') {
                newDivisiBox.classList.remove('d-none');
                inputNamaDivisi.required = true;
            } else if (newDivisiBox && inputNamaDivisi) {
                newDivisiBox.classList.add('d-none');
                inputNamaDivisi.required = false;
                inputNamaDivisi.value = '';
            }
        }

        if (selectDivisi) {
            selectDivisi.addEventListener('change', checkDivisiSelection);
            checkDivisiSelection();
        }

        // Toggle Pilih Mahasiswa saat Role Peserta dipilih
        const selectRole = document.getElementById('select_role');
        const mhsBox = document.getElementById('field_pilih_mahasiswa');
        const selectMhs = document.getElementById('select_mahasiswa');
        const inputNama = document.querySelector('input[name="nama_lengkap"]');
        const inputUsername = document.querySelector('input[name="username"]');
        const inputEmail = document.querySelector('input[name="email"]');

        function checkRole() {
            if (selectRole.value === 'Peserta') {
                mhsBox.classList.remove('d-none');
            } else {
                mhsBox.classList.add('d-none');
            }
        }

        if (selectRole) {
            selectRole.addEventListener('change', checkRole);
            checkRole();
        }

        if (selectMhs) {
            selectMhs.addEventListener('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                if (selectedOpt && selectedOpt.value) {
                    if (selectedOpt.dataset.nama && inputNama) inputNama.value = selectedOpt.dataset.nama;
                    if (selectedOpt.dataset.nim && inputUsername) inputUsername.value = selectedOpt.dataset.nim;
                    if (selectedOpt.dataset.email && inputEmail) inputEmail.value = selectedOpt.dataset.email;
                    if (selectedOpt.dataset.divisi && selectDivisi) {
                        selectDivisi.value = selectedOpt.dataset.divisi;
                    }
                }
            });
        }
    });
    </script>

</body>

</html>