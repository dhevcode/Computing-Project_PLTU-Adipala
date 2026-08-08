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
            background-image: url('{{ asset("assets/image/bg-pln.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
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

        .logout {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;
            cursor: pointer;

            border-radius: 8px;

            background: none;
            border: none;

            font-size: 17px;

            transition: .2s;
        }

        .logout:hover {
            background: rgba(255, 255, 255, .15);
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
            text-decoration: none;
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

        /* ================= HEADER RIGHT ================= */
        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-right: 16px;
        }

        /* ================= HAMBURGER BUTTON ================= */
        .menu-btn {
            width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            color: white;
            font-size: 21px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: .2s;
        }

        .menu-btn:hover {
            background: rgba(255, 255, 255, .12);
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            position: fixed;
            top: 82px;
            right: -270px;

            width: 250px;
            height: 470px;

            background: rgba(210, 210, 210, 0.96);
            backdrop-filter: blur(8px);

            border-radius: 25px;

            padding: 25px 18px;

            z-index: 9998;

            box-shadow: -5px 5px 20px rgba(0, 0, 0, .12);

            transition: right .3s ease;
        }

        /* Sidebar ketika dibuka */
        .sidebar.active {
            right: 10px;
        }


        /* ================= SIDEBAR MENU ================= */
        .sidebar-menu {
            display: flex;
            align-items: center;
            gap: 12px;

            width: 100%;
            padding: 11px 10px;

            margin-bottom: 8px;

            color: white;
            text-decoration: none;

            border-radius: 12px;

            font-size: 16px;
            font-weight: 400;

            transition: .2s;
        }

        .sidebar-menu i {
            width: 30px;
            font-size: 23px;
            text-align: center;
        }


        /* Hover */
        .sidebar-menu:hover {
            background: rgba(255, 255, 255, .15);
            transform: translateX(3px);
        }


        /* Menu aktif */
        .sidebar-menu.active {
            background: rgba(255, 255, 255, .15);
            font-weight: 500;
        }


        /* ================= SIDEBAR OVERLAY ================= */
        .sidebar-overlay {
            position: fixed;
            inset: 0;

            background: rgba(0, 0, 0, .15);

            z-index: 9997;

            opacity: 0;
            visibility: hidden;

            transition: .3s;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }


        /* ================= CONTENT ================= */
        .content {
            width: 100%;
            max-width: 1000px;
            margin: 100px auto 30px;
            padding: 0 20px;
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
            border-color: #0B6E72;
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

        .box-new-input {
            background: #F7FBFB;
            border: 1px dashed #0B6E72;
            border-radius: 8px;
            padding: 14px;
            margin-top: 10px;
        }

        .d-none {
            display: none !important;
        }

        /* ================= BUTTONS ================= */
        .button-area {
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

        /* ================= UPLOAD ================= */
        .upload-section {
            margin-top: 20px;
        }

        .upload-box {
            height: 140px;
            border: 2px dashed #D8DDE3;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #FCFCFC;
            transition: .2s;
            cursor: pointer;
            position: relative;
        }

        .upload-box:hover {
            border-color: #0A5F63;
            background: #F7FBFB;
        }

        .upload-icon {
            font-size: 36px;
            color: #AEB6BE;
            margin-bottom: 8px;
        }

        .upload-text {
            font-size: 13px;
            color: #666;
        }

        .upload-box span {
            margin: 4px 0;
            font-size: 12px;
            color: #999;
        }

        .file-selected-name {
            font-size: 12px;
            color: #0A5F63;
            font-weight: 600;
            margin-top: 6px;
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

        <!-- HEADER RIGHT -->
        <div class="header-right">

            <!-- Hamburger -->
            <button type="button" class="menu-btn" id="menuBtn" title="Menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf

                <button type="submit" class="logout" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>

        </div>

    </header>

    <!-- ================= SIDEBAR ================= -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

            <i class="fa-regular fa-chart-bar"></i>

            <span>Dashboard</span>

        </a>


        <!-- Data Mahasiswa -->
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-user"></i>

            <span>Data Mahasiswa</span>

        </a>


        <!-- Dokumen Verification -->
        <a href="#"
            class="sidebar-menu">

            <i class="fa-regular fa-file-lines"></i>

            <span>Dokumen Verification</span>

        </a>


        <!-- Sertifikat -->
        <a href="#"
            class="sidebar-menu">

            <i class="fa-regular fa-file"></i>

            <span>Sertifikat</span>

        </a>


        <!-- Tambah Mahasiswa -->
        <a href="{{ route('admin.create') }}"
            class="sidebar-menu">

            <i class="fa-solid fa-user-plus"></i>

            <span>Tambah Mahasiswa</span>

        </a>

    </div>

    <div class="content">
        <div class="card">
            <h1>Tambahkan data Mahasiswa Magang</h1>
            <p class="subtitle">
                Masukkan data mahasiswa secara lengkap agar dapat dipantau selama pelaksanaan PKL.
            </p>

            <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid">

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Jane Doe">
                        @error('nama_lengkap') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" required>
                            <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>
                                Pilih Jenis Kelamin
                            </option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>

                        @error('jenis_kelamin')
                        <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" required placeholder="Masukkan NIM...">
                        @error('nim') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Universitas</label>
                        <select name="universitas_id" id="select_universitas" required>
                            <option value="" disabled {{ old('universitas_id') ? '' : 'selected' }}>Pilih Universitas</option>
                            @foreach($universitas as $u)
                            <option value="{{ $u->id_universitas }}" {{ old('universitas_id') == $u->id_universitas ? 'selected' : '' }}>
                                {{ $u->nama_universitas }}
                            </option>
                            @endforeach
                            <option value="new" style="font-weight: bold; color: #0B6E72;" {{ old('universitas_id') == 'new' ? 'selected' : '' }}>
                                + Tambah Universitas Baru...
                            </option>
                        </select>
                        @error('universitas_id') <p class="error-text">{{ $message }}</p> @enderror

                        <!-- FIELD NAMA UNIVERSITAS BARU -->
                        <div id="field_universitas_baru" class="box-new-input d-none">
                            <label style="color: #0b6f77;">Nama Universitas Baru</label>
                            <input type="text" name="nama_universitas_baru" id="nama_universitas_baru" value="{{ old('nama_universitas_baru') }}" placeholder="Misal: Universitas Indonesia">
                            @error('nama_universitas_baru') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Jurusan / Prodi</label>
                        <select name="jurusan_id" id="select_jurusan" required>
                            <option value="" disabled {{ old('jurusan_id') ? '' : 'selected' }}>Pilih Jurusan</option>
                            @foreach($jurusan as $j)
                            <option value="{{ $j->id_jurusan }}" {{ old('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>
                                {{ $j->nama_jurusan }}
                            </option>
                            @endforeach
                            <option value="new" style="font-weight: bold; color: #0B6E72;" {{ old('jurusan_id') == 'new' ? 'selected' : '' }}>
                                + Tambah Jurusan Baru...
                            </option>
                        </select>
                        @error('jurusan_id') <p class="error-text">{{ $message }}</p> @enderror

                        <!-- FIELD NAMA JURUSAN BARU -->
                        <div id="field_jurusan_baru" class="box-new-input d-none">
                            <label style="color: #0b6f77;">Nama Jurusan Baru</label>
                            <input type="text" name="nama_jurusan_baru" id="nama_jurusan_baru" value="{{ old('nama_jurusan_baru') }}" placeholder="Misal: Teknik Elektro">
                            @error('nama_jurusan_baru') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Divisi Penempatan</label>
                        <select name="divisi_id" id="select_divisi" required>
                            <option value="" disabled {{ old('divisi_id') ? '' : 'selected' }}>Pilih Divisi</option>
                            @foreach($divisi as $d)
                            <option value="{{ $d->id_divisi }}" {{ old('divisi_id') == $d->id_divisi ? 'selected' : '' }}>
                                {{ $d->nama_divisi }}
                            </option>
                            @endforeach
                            <option value="new" style="font-weight: bold; color: #0B6E72;" {{ old('divisi_id') == 'new' ? 'selected' : '' }}>
                                + Tambah Divisi Baru...
                            </option>
                        </select>
                        @error('divisi_id') <p class="error-text">{{ $message }}</p> @enderror

                        <!-- FIELD NAMA DIVISI BARU -->
                        <div id="field_divisi_baru" class="box-new-input d-none">
                            <label style="color: #0b6f77;">Nama Divisi Baru</label>
                            <input type="text" name="nama_divisi_baru" id="nama_divisi_baru" value="{{ old('nama_divisi_baru') }}" placeholder="Misal: Keamanan Informasi">
                            @error('nama_divisi_baru') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Mulai Magang</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                        @error('tanggal_mulai') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Selesai Magang</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                        @error('tanggal_selesai') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="mhs@email.com">
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" required placeholder="Contoh: 08123456789">
                        @error('no_telepon') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Nama Pembimbing Lapangan</label>
                        <input type="text" name="nama_pembimbing" value="{{ old('nama_pembimbing') }}" placeholder="Masukkan nama pembimbing (opsional)">
                        @error('nama_pembimbing') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <!-- <div class="form-group">
                        <label>Status Magang</label>
                        <select name="status" required>
                            <option value="Akan Magang" {{ old('status') == 'Akan Magang' ? 'selected' : '' }}>Akan Magang</option>
                            <option value="Sedang Magang" {{ old('status') == 'Sedang Magang' ? 'selected' : '' }}>Sedang Magang</option>
                            <option value="Selesai Magang" {{ old('status') == 'Selesai Magang' ? 'selected' : '' }}>Selesai Magang</option>
                        </select>
                        @error('status') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div> -->

                    <!-- ================= DOKUMEN MAGANG ================= -->
<div class="upload-section">

    <label>Dokumen Persyaratan Magang</label>

    <p style="font-size: 12px; color: #888; margin-bottom: 15px;">
        Unggah dokumen persyaratan mahasiswa dalam format PDF, DOC, atau DOCX.
        Maksimal ukuran setiap file 4MB.
    </p>

    <div class="grid">

        <!-- SURAT PENGANTAR -->
        <div>

            <div class="upload-box"
                onclick="document.getElementById('surat_pengantar').click()">

                <i class="fa-solid fa-file-signature upload-icon"></i>

                <p class="upload-text">
                    Surat Pengantar Kampus
                </p>

                <span>
                    PDF / DOC / DOCX • Maks. 4MB
                </span>

                <input
                    type="file"
                    id="surat_pengantar"
                    name="surat_permohonan"
                    accept=".pdf,.doc,.docx"
                    style="display: none;">

                <div
                    id="surat_pengantar-info"
                    class="file-selected-name d-none">
                </div>

            </div>

            @error('surat_permohonan')
            <p class="error-text">{{ $message }}</p>
            @enderror

        </div>


        <!-- PROPOSAL -->
        <div>

            <div class="upload-box"
                onclick="document.getElementById('proposal').click()">

                <i class="fa-solid fa-file-lines upload-icon"></i>

                <p class="upload-text">
                    Proposal Magang
                </p>

                <span>
                    PDF / DOC / DOCX • Maks. 4MB
                </span>

                <input
                    type="file"
                    id="proposal"
                    name="proposal"
                    accept=".pdf,.doc,.docx"
                    style="display: none;">

                <div
                    id="proposal-info"
                    class="file-selected-name d-none">
                </div>

            </div>

            @error('proposal')
            <p class="error-text">{{ $message }}</p>
            @enderror

        </div>

    </div>


    <!-- CV -->
    <div style="margin-top: 20px;">

        <div class="upload-box"
            onclick="document.getElementById('cv').click()">

            <i class="fa-solid fa-file-user upload-icon"></i>

            <p class="upload-text">
                CV / Curriculum Vitae
            </p>

            <span>
                PDF / DOC / DOCX • Maks. 4MB
            </span>

            <input
                type="file"
                id="cv"
                name="cv"
                accept=".pdf,.doc,.docx"
                style="display: none;">

            <div
                id="cv-info"
                class="file-selected-name d-none">
            </div>

        </div>

        @error('cv')
        <p class="error-text">{{ $message }}</p>
        @enderror

    </div>

</div>
                    <div class="button-area">
                        <a href="{{ route('admin.data') }}" class="btn-cancel">Batal</a>
                        <button class="btn-save" type="submit">Simpan</button>
                    </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle new input fields
            const selectors = [{
                    select: 'select_universitas',
                    field: 'field_universitas_baru',
                    input: 'nama_universitas_baru'
                },
                {
                    select: 'select_jurusan',
                    field: 'field_jurusan_baru',
                    input: 'nama_jurusan_baru'
                },
                {
                    select: 'select_divisi',
                    field: 'field_divisi_baru',
                    input: 'nama_divisi_baru'
                }
            ];

            selectors.forEach(item => {
                const selectEl = document.getElementById(item.select);
                const boxEl = document.getElementById(item.field);
                const inputEl = document.getElementById(item.input);

                function toggle() {
                    if (selectEl.value === 'new') {
                        boxEl.classList.remove('d-none');
                        inputEl.required = true;
                    } else {
                        boxEl.classList.add('d-none');
                        inputEl.required = false;
                        inputEl.value = '';
                    }
                }

                selectEl.addEventListener('change', toggle);
                toggle(); // Run on load
            });

            // ================= FILE UPLOAD =================

const uploadFiles = [
    {
        input: 'surat_pengantar',
        info: 'surat_pengantar-info'
    },
    {
        input: 'proposal',
        info: 'proposal-info'
    },
    {
        input: 'cv',
        info: 'cv-info'
    }
];

uploadFiles.forEach(function(item) {

    const fileInput = document.getElementById(item.input);
    const fileInfo = document.getElementById(item.info);

    fileInput.addEventListener('change', function() {

        if (fileInput.files.length > 0) {

            fileInfo.textContent =
                'File terpilih: ' + fileInput.files[0].name;

            fileInfo.classList.remove('d-none');

        } else {

            fileInfo.textContent = '';

            fileInfo.classList.add('d-none');

        }

    });

});
        });
    </script>

</body>

</html>