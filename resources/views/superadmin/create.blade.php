<!DOCTYPE html>
<html lang="id">

<head>
<<<<<<< HEAD
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Data Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
body {
    background: #EEF2F5;
}

/* ===== HEADER ===== */
.header {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 58px;
    background: linear-gradient(90deg, #0A6D71, #053A3E);
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 999;
    box-shadow: 0 3px 12px rgba(0,0,0,.08);
}
.header-left {
    display: flex;
    align-items: center;
}
.logo-box {
    width: 135px;
    height: 58px;
    background: #fff;
    clip-path: polygon(0 0, 100% 0, 82% 100%, 0 100%);
    display: flex;
    justify-content: center;
    align-items: center;
}
.logo-box img {
    width: 90px;
}
.header-title {
    margin-left: 16px;
    color: white;
}
.header-title h2 {
    font-size: 16px;
    font-weight: 700;
}
.header-title p {
    font-size: 9px;
    opacity: .9;
}
.header-right {
    margin-right: 18px;
}
.logout {
    color: white;
    font-size: 18px;
    text-decoration: none;
}

/* ===== CONTENT ===== */
.content {
    max-width: 1280px;
    margin: 90px auto 30px;
    padding: 0 28px;
}
.card {
    background: #fff;
    border-radius: 26px;
    padding: 22px;
    box-shadow: 0 4px 16px rgba(0,0,0,.10);
    border: 1px solid #ECECEC;
}
.card h2 {
    font-size: 18px;
    font-weight: 700;
    color: #1D1D1D;
}
.subtitle {
    margin-top: 4px;
    font-size: 12px;
    color: #A8A8A8;
    margin-bottom: 18px;
}

/* ===== FORM ===== */
.form-group {
    margin-bottom: 16px;
}
label {
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    font-weight: 500;
    color: #222;
}
input, select {
    width: 100%;
    height: 38px;
    border: 1px solid #E4E4E4;
    border-radius: 6px;
    padding: 0 12px;
    outline: none;
    font-size: 13px;
    background: white;
    color: #444;
    transition: .2s;
}
input:focus, select:focus {
    border-color: #0A6D71;
    box-shadow: 0 0 0 3px rgba(10,109,113,.12);
}
.error-text {
    color: #FF5A5A;
    font-size: 11px;
    margin-top: 4px;
}

.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-top: 4px;
}

/* Box khusus divisi baru */
.box-divisi-baru {
    margin-top: 10px;
    background: #F8FAFC;
    padding: 14px;
    border-radius: 8px;
    border: 1px dashed #0A6D71;
}

/* Style hilangkan elemen */
.d-none {
    display: none !important;
}

/* ===== BUTTON ===== */
.button-group {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 22px;
}
.btn-save {
    width: 88px;
    height: 36px;
    border: none;
    border-radius: 6px;
    background: #5B97A1;
    color: white;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s;
}
.btn-save:hover {
    background: #4F8790;
}
.btn-cancel {
    width: 88px;
    height: 36px;
    border-radius: 6px;
    background: #5B97A1;
    color: white;
    text-decoration: none;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 12px;
    font-weight: 600;
    transition: .2s;
}
.btn-cancel:hover {
    background: #4F8790;
}
</style>
=======

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Data Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image:url('{{ asset("assets/image/bg-pln.png") }}');

            background-size: cover;

            background-position: center;

            background-repeat: no-repeat;

            background-attachment: fixed;
        }

        /* ================= HEADER ================= */

        .header {

            position: fixed;

            top: 0;

            left: 0;

            right: 0;

            height: 72px;

            background: #0A5F63;

            display: flex;

            justify-content: space-between;

            align-items: center;

            z-index: 9999;

            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);

        }


        .header-left {

            display: flex;
            align-items: center;

        }

        .logo-box {

            width: 230px;

            height: 72px;

            background: white;

            clip-path: polygon(0 0, 100% 0, 82% 100%, 0 100%);

            display: flex;

            justify-content: center;

            align-items: center;

        }

        .logo-box img {

            width: 150px;

        }


        .header-title {

            margin-left: 16px;
            color: white;

        }

        .header-title h2 {

            font-size: 16px;
            font-weight: 700;

        }

        .header-title p {

            font-size: 9px;
            opacity: .9;

        }

        .header-right {

            margin-right: 18px;

        }

        .logout {

            color: white;
            font-size: 18px;
            text-decoration: none;

        }

        /* ===== CONTENT ===== */

        .content {

            max-width: 1280px;

            margin: 90px auto 30px;

            padding: 0 28px;

        }

        .card {

            background: #fff;

            border-radius: 26px;

            padding: 22px;

            box-shadow: 0 4px 16px rgba(0, 0, 0, .10);

            border: 1px solid #ECECEC;

        }

        .card:hover {

            box-shadow: 0 8px 22px rgba(0, 0, 0, .12);

        }

        .card h2 {

            font-size: 18px;

            font-weight: 700;

            color: #1D1D1D;

        }

        .subtitle {

            margin-top: 4px;

            font-size: 12px;

            color: #A8A8A8;

            margin-bottom: 18px;

        }

        /* ===== FORM ===== */

        .form-group {

            margin-bottom: 16px;

        }

        label {

            display: block;

            margin-bottom: 6px;

            font-size: 13px;

            font-weight: 500;

            color: #222;

        }

        input {

            width: 100%;

            height: 38px;

            border: 1px solid #E4E4E4;

            border-radius: 6px;

            padding: 0 12px;

            outline: none;

            font-size: 13px;

            transition: .2s;

        }

        input:focus {

            border-color: #0A6D71;

            box-shadow: 0 0 0 3px rgba(10, 109, 113, .12);

        }

        .grid {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 18px;

            margin-top: 4px;

        }

        /* ================= BUTTON ================= */

        .button-group {

            display: flex;

            justify-content: flex-end;

            gap: 12px;

            margin-top: 22px;

        }

        .btn-save {

            width: 88px;

            height: 36px;

            border: none;

            border-radius: 6px;

            background: #5B97A1;

            color: white;

            font-size: 12px;

            font-weight: 600;

            cursor: pointer;

            transition: .2s;

        }

        .btn-save:hover {

            background: #4F8790;

        }

        .btn-cancel {

            width: 88px;

            height: 36px;

            border-radius: 6px;

            background: #5B97A1;

            color: white;

            text-decoration: none;

            display: flex;

            justify-content: center;

            align-items: center;

            font-size: 12px;

            font-weight: 600;

            transition: .2s;

        }

        .btn-cancel:hover {

            background: #4F8790;

        }

        select {

            width: 100%;

            height: 38px;

            border: 1px solid #E4E4E4;

            border-radius: 6px;

            padding: 0 12px;

            outline: none;

            font-size: 13px;

            background: white;

            color: #444;

            transition: .2s;

        }

        select:focus {

            border-color: #0A6D71;

            box-shadow: 0 0 0 3px rgba(10, 109, 113, .12);

        }
    </style>

>>>>>>> 1ac5fdd82e5ef02be6b558f8e7553952f5a7912d
</head>

<body>

<<<<<<< HEAD
<header class="header">
    <div class="header-left">
        <div class="logo-box">
            <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo">
        </div>
        <div class="header-title">
            <h2>Monitoring Mahasiswa PKL</h2>
            <p>Kelola dan pantau data mahasiswa PKL dengan mudah dan efisien.</p>
        </div>
    </div>
    <div class="header-right">
        <a href="{{ route('superadmin.dashboard') }}" class="logout">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a>
    </div>
</header>

<div class="content">
    <div class="card">
        <h2>Tambahkan data Admin</h2>
        <p class="subtitle">
            Masukkan data admin untuk memantau mahasiswa selama pelaksanaan PKL.
        </p>

        <form action="{{ route('superadmin.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: John Doe">
                @error('nama_lengkap') <p class="error-text">{{ $message }}</p> @enderror
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: adminjohn">
                    @error('username') <p class="error-text">{{ $message }}</p> @enderror
                </div>
=======
    <header class="header">

        <div class="header-left">

            <div class="logo-box">

                <img src="{{ asset('assets/image/logo-pln.png') }}">

            </div>

            <div class="header-title">

                <h2>Monitoring Mahasiswa PKL</h2>

                <p>Kelola dan pantau data mahasiswa PKL dengan mudah dan efisien.</p>

            </div>

        </div>

        <div class="header-right">

            <a href="{{ route('superadmin.dashboard') }}" class="logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>

        </div>

    </header>

    <div class="content">

        <div class="card">

            <h2>Tambahkan data Admin</h2>

            <p class="subtitle">
                Masukkan data admin untuk memantau mahasiswa selama pelaksanaan PKL.
            </p>

            <div class="form-group">

                <label>Nama lengkap</label>

                <input type="text">

            </div>

            <div class="grid">

                <div class="form-group">

                    <label>Username</label>

                    <input type="text">

                </div>

                <div class="form-group">

                    <label>Password</label>

                    <input type="password">

                </div>

            </div>

            <div class="grid">

                <div class="form-group">

                    <label>Bagian</label>

                    <select>
                        <option selected disabled>Pilih Bagian</option>
                        <option>Umum</option>
                        <option>Operasi</option>
                        <option>Pemeliharaan</option>
                        <option>Keuangan</option>
                        <option>SDM</option>
                    </select>

                </div>

                <div class="form-group">

                    <label>Role</label>

                    <select>
                        <option selected disabled>Pilih Role</option>
                        <option>Admin</option>
                        <option>Super Admin</option>
                    </select>

                </div>

            </div>

            <div class="button-group">

                <button type="submit" class="btn-save">
                    Simpan
                </button>

                <a href="{{ route('superadmin.dashboard') }}" class="btn-cancel">
                    Batal
                </a>
>>>>>>> 1ac5fdd82e5ef02be6b558f8e7553952f5a7912d

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@perusahaan.com">
                    @error('email') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter">
                    @error('password') <p class="error-text">{{ $message }}</p> @enderror
                </div>

<<<<<<< HEAD
                <div class="form-group">
                    <label>Bagian / Divisi</label>
                    <select name="id_divisi" id="select_divisi" required>
                        <option value="" disabled {{ old('id_divisi') ? '' : 'selected' }}>Pilih Bagian</option>
                        @foreach($divisi as $d)
                            <option value="{{ $d->id_divisi }}" {{ old('id_divisi') == $d->id_divisi ? 'selected' : '' }}>
                                {{ $d->nama_divisi }}
                            </option>
                        @endforeach
                        <option value="new" style="font-weight: bold; color: #0A6D71;" {{ old('id_divisi') == 'new' ? 'selected' : '' }}>
                            + Tambah Bagian/Divisi Baru...
                        </option>
                    </select>
                    @error('id_divisi') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- FIELD NAMA DIVISI BARU -->
            <div id="field_divisi_baru" class="box-divisi-baru d-none">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="color: #0A6D71;">Nama Divisi Baru</label>
                    <input type="text" name="nama_divisi_baru" id="nama_divisi_baru" value="{{ old('nama_divisi_baru') }}" placeholder="Misal: Operasi & Pemeliharaan">
                    @error('nama_divisi_baru') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group" style="margin-top: 16px;">
                <label>Role</label>
                <select name="role" required>
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                    <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Super Admin" {{ old('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
                @error('role') <p class="error-text">{{ $message }}</p> @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn-save">Simpan</button>
                <a href="{{ route('superadmin.dashboard') }}" class="btn-cancel">Batal</a>
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

    // Jalankan saat dropdown berubah
    selectDivisi.addEventListener('change', checkDivisiSelection);

    // Jalankan saat halaman pertama kali dimuat (jika ada nilai 'old')
    checkDivisiSelection();
});
</script>
=======
    </div>
>>>>>>> 1ac5fdd82e5ef02be6b558f8e7553952f5a7912d

</body>

</html>