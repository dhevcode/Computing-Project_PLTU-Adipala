<!DOCTYPE html>
<html lang="id">

<head>
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
            background-image: url('{{ asset("assets/image/bg-pln.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
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
            font-size: 18px;
            font-weight: 700;
            line-height: 1;
        }

        .header-title p {
            font-size: 10px;
            margin-top: 3px;
            opacity: .9;
        }

        .header-right {
            margin-right: 18px;
        }

        .logout {
            color: white;
            font-size: 18px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            transition: .2s;
        }

        .logout:hover {
            background: rgba(255, 255, 255, .15);
        }

        /* ===== CONTENT ===== */
        .content {
            max-width: 800px;
            margin: 110px auto 30px;
            padding: 0 20px;
        }

        .card {
            background: #fff;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
            border: 1px solid #E8EDF2;
        }

        .card h2 {
            font-size: 20px;
            font-weight: 700;
            color: #202020;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 13px;
            color: #A4A4A4;
            margin-bottom: 22px;
        }

        /* ===== FORM ===== */
        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #1E1E1E;
        }

        input,
        select {
            width: 100%;
            height: 42px;
            border: 1px solid #E4E4E4;
            border-radius: 8px;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            transition: .2s;
            background: white;
            color: #444;
        }

        input:focus,
        select:focus {
            border-color: #0b6f77;
            box-shadow: 0 0 0 3px rgba(11, 110, 114, .12);
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .error-text {
            color: #D9534F;
            font-size: 11px;
            margin-top: 4px;
            font-weight: 500;
        }

        .box-divisi-baru {
            background: #F7FBFB;
            border: 1px dashed #0A6D71;
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 18px;
        }

        .d-none {
            display: none !important;
        }

        /* ===== BUTTONS ===== */
        .button-group {
            margin-top: 24px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-save {
            height: 42px;
            padding: 0 24px;
            border: none;
            border-radius: 8px;
            background: #0A5F63;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .btn-save:hover {
            background: #084c4f;
        }

        .btn-cancel {
            height: 42px;
            padding: 0 24px;
            border-radius: 8px;
            background: #A4A4A4;
            color: white;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            font-weight: 600;
            transition: .2s;
        }

        .btn-cancel:hover {
            background: #8e8e8e;
        }
    </style>
</head>

<body>

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
            <a href="{{ route('superadmin.dashboard') }}" class="logout" title="Kembali ke Dashboard">
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

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                        <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Super Admin" {{ old('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                    @error('role') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="button-group">
                    <a href="{{ route('superadmin.dashboard') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-save">Simpan</button>
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