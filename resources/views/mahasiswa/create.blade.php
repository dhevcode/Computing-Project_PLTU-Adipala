<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Mahasiswa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html,
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

        /* Logo */

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



        /* Title */

        .header-left {

            display: flex;

            align-items: center;

            gap: 18px;

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

        /* Right */

        .header-right {

            display: flex;

            align-items: center;

            gap: 10px;

            padding-right: 16px;

        }

        .btn-add {

            background: white;

            border: none;

            height: 32px;

            padding: 0 15px;

            border-radius: 7px;

            font-size: 12px;

            font-weight: 600;

            color: #0A5F63;

            cursor: pointer;

            display: flex;

            align-items: center;

            gap: 7px;

            transition: .2s;

        }

        .btn-add:hover {

            transform: translateY(-2px);

        }

        .logout {

            width: 32px;

            height: 32px;

            display: flex;

            align-items: center;

            justify-content: center;

            color: white;

            cursor: pointer;

            border-radius: 8px;

            transition: .2s;

        }

        .logout:hover {

            background: rgba(255, 255, 255, .15);

        }

        /* ================= CONTENT ================= */

        .content {

            width: 100%;
            max-width: 1400px;

            margin: 90px auto 30px;

            padding: 0 35px;

        }

        .card {

            width: 100%;

            background: #FFFFFF;

            border-radius: 24px;

            padding: 28px;

            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);

            border: 1px solid #E8EDF2;

        }

        .card h1 {

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

        /* ================= FORM ================= */

        .form-group {

            margin-top: 18px;

        }

        label {

            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #1E1E1E;

        }

        input {

            width: 100%;
            height: 42px;
            border: 1px solid #E4E4E4;
            border-radius: 8px;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            transition: .2s;
            background: white;

        }

        input:focus {

            border-color: #0B6E72;
            box-shadow: 0 0 0 3px rgba(11, 110, 114, .12);

        }

        .grid {

            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 18px;

        }

        /* ================= BUTTON ================= */

        .button-area {

            margin-top: 20px;

            display: flex;

            justify-content: center;

            gap: 12px;

        }

        .btn-save {

            width: 96px;
            height: 42px;

            border: none;

            border-radius: 8px;

            background: #5A9CA4;

            color: white;

            font-size: 14px;

            font-weight: 600;

            cursor: pointer;

            transition: .2s;

        }

        .btn-save:hover {

            background: #4A8C94;

        }

        .btn-cancel {

            width: 96px;
            height: 42px;

            border-radius: 8px;

            background: #5A9CA4;

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

            background: #4A8C94;

        }

        /* ================= UPLOAD ================= */

        .upload-section {

            margin-top: 20px;

        }

        .upload-section label {

            display: block;

            margin-bottom: 10px;

            font-weight: 600;

            font-size: 14px;

        }

        .upload-box {

            height: 160px;

            border: 2px dashed #D8DDE3;

            border-radius: 10px;

            display: flex;

            flex-direction: column;

            justify-content: center;

            align-items: center;

            background: #FCFCFC;

            transition: .2s;

        }

        .upload-box:hover {

            border-color: #0A5F63;

            background: #F7FBFB;

        }

        .upload-icon {

            font-size: 42px;

            color: #AEB6BE;

            margin-bottom: 10px;

        }

        .upload-text {

            font-size: 13px;

            color: #666;

        }

        .upload-box span {

            margin: 6px 0;

            font-size: 12px;

            color: #999;

        }

        .upload-btn {

            background: #FFC107;

            padding: 8px 22px;

            border-radius: 6px;

            cursor: pointer;

            font-size: 13px;

            font-weight: 600;

            color: white;

            transition: .2s;

        }

        .upload-btn:hover {

            background: #FFB300;

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

            <a href="#" class="logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>

        </div>

    </header>

    <div class="content">

        <div class="wrapper">
            <div class="card">

                <div class="card">

                    <h1>Tambahkan data Mahasiswa Magang</h1>

                    <p class="subtitle">
                        Masukkan data mahasiswa secara lengkap agar dapat dipantau selama pelaksanaan PKL.
                    </p>

                    <div class="form-group">

                        <label>Nama lengkap</label>

                        <input type="text">

                    </div>

                    <div class="grid">

                        <div>

                            <label>NIM</label>

                            <input type="text">

                        </div>

                        <div>

                            <label>Universitas</label>

                            <input type="text">

                        </div>

                    </div>

                    <div class="grid">

                        <div>

                            <label>Jurusan/Prodi</label>

                            <input type="text">

                        </div>

                        <div>

                            <label>Divisi Penempatan</label>

                            <input type="text">

                        </div>

                    </div>

                    <div class="grid">

                        <div>

                            <label>Mulai magang</label>

                            <input type="date">

                        </div>

                        <div>

                            <label>Selesai magang</label>

                            <input type="date">

                        </div>

                    </div>

                    <div class="grid">

                        <div>

                            <label>Email</label>

                            <input type="email">

                        </div>

                        <div>

                            <label>No. Telepon</label>

                            <input type="text">

                        </div>

                    </div>

                </div>





            <div class="upload-section">

                <label>Surat Pengantar / Dokumen Pendukung</label>

                <div class="upload-box">

                    <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>

                    <p class="upload-text">
                        Drag & Drop file di sini
                    </p>

                    <span>atau</span>

                    <label for="uploadFile" class="upload-btn">
                        Upload
                    </label>

                    <input type="file" id="uploadFile" hidden>

                </div>

            </div>

            <div class="button-area">

                <a href="{{ url('/dashboardadmin') }}" class="btn-cancel">
                    Batal
                </a>

                <button class="btn-save" type="submit">
                    Simpan
                </button>

            </div>
            </div>

        </div>

    </div>

</body>

</html>

</div>

</div>

</body>

</html>