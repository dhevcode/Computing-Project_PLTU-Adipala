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
            background: #EEF2F5;
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

</head>

<body>

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

            </div>

        </div>

    </div>

</body>

</html>