<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Admin - Monitoring PKL</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/superadmin/edit.css') }}">
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
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h2>Edit Data Admin</h2>
                    <p>Perbarui rincian akun administrator, peran, atau divisi penugasan di bawah ini.</p>
                </div>
            </div>

            <form action="{{ route('superadmin.update', $admin->id_users) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nama Lengkap Admin</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $admin->nama_lengkap) }}" required placeholder="Contoh: John Doe">
                    @error('nama_lengkap') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" value="{{ old('username', $admin->username) }}" required placeholder="Contoh: adminjohn">
                        @error('username') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" required placeholder="admin@perusahaan.com">
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Kata Sandi <span style="font-weight: 400; color: #94A3B8; font-size: 11px;">(Biarkan kosong jika tidak diubah)</span></label>
                        <input type="password" name="password" placeholder="Masukkan kata sandi baru jika ingin mengganti">
                        @error('password') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Bagian / Divisi</label>
                        <select name="id_divisi" id="select_divisi" required>
                            <option value="" disabled>Pilih Bagian / Divisi</option>
                            @foreach($divisi as $d)
                                <option value="{{ $d->id_divisi }}" {{ old('id_divisi', $admin->id_divisi) == $d->id_divisi ? 'selected' : '' }}>
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

                <!-- FIELD NAMA DIVISI BARU -->
                <div id="field_divisi_baru" class="box-divisi-baru d-none">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="color: #0A5F63;"><i class="fa-solid fa-folder-plus" style="margin-right: 6px;"></i>Nama Divisi Baru</label>
                        <input type="text" name="nama_divisi_baru" id="nama_divisi_baru" value="{{ old('nama_divisi_baru') }}" placeholder="Misal: Operasi & Pemeliharaan">
                        @error('nama_divisi_baru') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Hak Akses (Role)</label>
                        <select name="role" required>
                            <option value="" disabled>Pilih Role</option>
                            <option value="Admin" {{ old('role', $admin->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Super Admin" {{ old('role', $admin->role) == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @error('role') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Status Akun</label>
                        <select name="is_active" required>
                            <option value="1" {{ old('is_active', $admin->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', $admin->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('is_active') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="button-group">
                    <a href="{{ route('superadmin.dashboard') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Perubahan</span>
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
            if (selectDivisi.value === 'new') {
                newDivisiBox.classList.remove('d-none');
                inputNamaDivisi.required = true;
            } else {
                newDivisiBox.classList.add('d-none');
                inputNamaDivisi.required = false;
                inputNamaDivisi.value = '';
            }
        }

        selectDivisi.addEventListener('change', checkDivisiSelection);
        checkDivisiSelection();
    });
    </script>

</body>

</html>