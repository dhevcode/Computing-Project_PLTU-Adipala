<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Mahasiswa</title>

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

    .header-right {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-right: 16px;
    }

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

    .sidebar.active {
        right: 10px;
    }

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

    .sidebar-menu:hover {
        background: rgba(255, 255, 255, .15);
        transform: translateX(3px);
    }

    .sidebar-menu.active {
        background: rgba(255, 255, 255, .15);
        font-weight: 500;
    }

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

    /* ================= BUTTON ================= */

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

    /* ================= DOKUMEN ================= */

    .upload-section {
        margin-top: 25px;
    }

    .upload-section > label {
        margin-bottom: 4px;
    }

    .upload-description {
        font-size: 12px;
        color: #888;
        margin-bottom: 15px;
    }

    .upload-box {
        min-height: 140px;
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
        padding: 15px;
        text-align: center;
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
        word-break: break-word;
    }

    /* ================= DOKUMEN LAMA ================= */

    .current-file {
        background: #F4F7F6;
        border: 1px solid #E4E4E4;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 10px;

        display: flex;
        align-items: center;
        gap: 10px;
    }

    .current-file-icon {
        color: #0A5F63;
        font-size: 18px;
    }

    .current-file-info {
        flex: 1;
        min-width: 0;
    }

    .current-file-label {
        font-size: 11px;
        color: #888;
        margin-bottom: 2px;
    }

    .current-file a {
        color: #0A5F63;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        word-break: break-all;
    }

    .current-file a:hover {
        text-decoration: underline;
    }

    .replace-text {
        font-size: 11px;
        color: #999;
        margin-bottom: 8px;
    }

    /* ================= RESPONSIVE ================= */

    @media (max-width: 700px) {

        .grid {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .content {
            padding: 0 12px;
        }

        .card {
            padding: 20px;
        }

        .logo-box {
            width: 150px;
        }

        .logo-box img {
            width: 110px;
        }

        .header-title {
            display: none;
        }
    }
</style>


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

        <button type="button" class="menu-btn" id="menuBtn" title="Menu">
            <i class="fa-solid fa-bars"></i>
        </button>

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

    <a href="{{ route('admin.dashboard') }}"
        class="sidebar-menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

        <i class="fa-regular fa-chart-bar"></i>

        <span>Dashboard</span>

    </a>

    <a href="{{ route('admin.dashboard') }}"
        class="sidebar-menu">

        <i class="fa-regular fa-user"></i>

        <span>Data Mahasiswa</span>

    </a>

    <a href="#"
        class="sidebar-menu">

        <i class="fa-regular fa-file-lines"></i>

        <span>Dokumen Verification</span>

    </a>

    <a href="#"
        class="sidebar-menu">

        <i class="fa-regular fa-file"></i>

        <span>Sertifikat</span>

    </a>

    <a href="{{ route('admin.create') }}"
        class="sidebar-menu">

        <i class="fa-solid fa-user-plus"></i>

        <span>Tambah Mahasiswa</span>

    </a>

</div>


<!-- ================= CONTENT ================= -->

<div class="content">

    <div class="card">

        <h1>Ubah data Mahasiswa Magang</h1>

        <p class="subtitle">
            Ubah data mahasiswa PKL dan simpan perubahan terbaru.
        </p>


        <form
            action="{{ route('admin.update', $pengajuan->id_pengajuan) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')


            <!-- ================= DATA MAHASISWA ================= -->

            <div class="grid">

                <!-- NAMA -->

                <div class="form-group">

                    <label>Nama Lengkap</label>

                    <input
                        type="text"
                        name="nama_lengkap"
                        value="{{ old('nama_lengkap', $pengajuan->mahasiswa->nama_lengkap) }}"
                        required
                        placeholder="Contoh: Jane Doe">

                    @error('nama_lengkap')
                    <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>


                <!-- JENIS KELAMIN -->

                <div class="form-group">

                    <label>Jenis Kelamin</label>

                    <select name="jenis_kelamin" required>

                        <option value="" disabled>
                            Pilih Jenis Kelamin
                        </option>

                        <option
                            value="Laki-laki"
                            {{ old('jenis_kelamin', $pengajuan->mahasiswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki
                        </option>

                        <option
                            value="Perempuan"
                            {{ old('jenis_kelamin', $pengajuan->mahasiswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>

                    </select>

                    @error('jenis_kelamin')
                    <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>


                <!-- NIM -->

                <div class="form-group">

                    <label>NIM</label>

                    <input
                        type="text"
                        name="nim"
                        value="{{ old('nim', $pengajuan->mahasiswa->nim) }}"
                        required
                        placeholder="Masukkan NIM...">

                    @error('nim')
                    <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>


                <!-- UNIVERSITAS -->

                <div class="form-group">

                    <label>Universitas</label>

                    <select
                        name="universitas_id"
                        id="select_universitas"
                        required>

                        <option value="" disabled>
                            Pilih Universitas
                        </option>

                        @foreach($universitas as $u)

                        <option
                            value="{{ $u->id_universitas }}"
                            {{ old('universitas_id', $pengajuan->mahasiswa->universitas_id) == $u->id_universitas ? 'selected' : '' }}>

                            {{ $u->nama_universitas }}

                        </option>

                        @endforeach

                        <option
                            value="new"
                            style="font-weight: bold; color: #0B6E72;"
                            {{ old('universitas_id') == 'new' ? 'selected' : '' }}>

                            + Tambah Universitas Baru...

                        </option>

                    </select>

                    @error('universitas_id')
                    <p class="error-text">{{ $message }}</p>
                    @enderror


                    <div
                        id="field_universitas_baru"
                        class="box-new-input d-none">

                        <label style="color: #0b6f77;">
                            Nama Universitas Baru
                        </label>

                        <input
                            type="text"
                            name="nama_universitas_baru"
                            id="nama_universitas_baru"
                            value="{{ old('nama_universitas_baru') }}"
                            placeholder="Misal: Universitas Indonesia">

                        @error('nama_universitas_baru')
                        <p class="error-text">{{ $message }}</p>
                        @enderror

                    </div>

                </div>

            </div>


            <!-- ================= JURUSAN & DIVISI ================= -->

            <div class="grid">

                <!-- JURUSAN -->

                <div class="form-group">

                    <label>Jurusan / Prodi</label>

                    <select
                        name="jurusan_id"
                        id="select_jurusan"
                        required>

                        <option value="" disabled>
                            Pilih Jurusan
                        </option>

                        @foreach($jurusan as $j)

                        <option
                            value="{{ $j->id_jurusan }}"
                            {{ old('jurusan_id', $pengajuan->mahasiswa->jurusan_id) == $j->id_jurusan ? 'selected' : '' }}>

                            {{ $j->nama_jurusan }}

                        </option>

                        @endforeach

                        <option
                            value="new"
                            style="font-weight: bold; color: #0B6E72;"
                            {{ old('jurusan_id') == 'new' ? 'selected' : '' }}>

                            + Tambah Jurusan Baru...

                        </option>

                    </select>

                    @error('jurusan_id')
                    <p class="error-text">{{ $message }}</p>
                    @enderror


                    <div
                        id="field_jurusan_baru"
                        class="box-new-input d-none">

                        <label style="color: #0b6f77;">
                            Nama Jurusan Baru
                        </label>

                        <input
                            type="text"
                            name="nama_jurusan_baru"
                            id="nama_jurusan_baru"
                            value="{{ old('nama_jurusan_baru') }}"
                            placeholder="Misal: Teknik Elektro">

                        @error('nama_jurusan_baru')
                        <p class="error-text">{{ $message }}</p>
                        @enderror

                    </div>

                </div>


                <!-- DIVISI -->

                <div class="form-group">

                    <label>Divisi Penempatan</label>

                    <select
                        name="divisi_id"
                        id="select_divisi"
                        required>

                        <option value="" disabled>
                            Pilih Divisi
                        </option>

                        @foreach($divisi as $d)

                        <option
                            value="{{ $d->id_divisi }}"
                            {{ old('divisi_id', $pengajuan->divisi_id) == $d->id_divisi ? 'selected' : '' }}>

                            {{ $d->nama_divisi }}

                        </option>

                        @endforeach

                        <option
                            value="new"
                            style="font-weight: bold; color: #0B6E72;"
                            {{ old('divisi_id') == 'new' ? 'selected' : '' }}>

                            + Tambah Divisi Baru...

                        </option>

                    </select>

                    @error('divisi_id')
                    <p class="error-text">{{ $message }}</p>
                    @enderror


                    <div
                        id="field_divisi_baru"
                        class="box-new-input d-none">

                        <label style="color: #0b6f77;">
                            Nama Divisi Baru
                        </label>

                        <input
                            type="text"
                            name="nama_divisi_baru"
                            id="nama_divisi_baru"
                            value="{{ old('nama_divisi_baru') }}"
                            placeholder="Misal: Keamanan Informasi">

                        @error('nama_divisi_baru')
                        <p class="error-text">{{ $message }}</p>
                        @enderror

                    </div>

                </div>

            </div>


            <!-- ================= TANGGAL ================= -->

            <div class="grid">

                <div class="form-group">

                    <label>Mulai Magang</label>

                    <input
                        type="date"
                        name="tanggal_mulai"
                        value="{{ old('tanggal_mulai', $pengajuan->tanggal_mulai) }}"
                        required>

                    @error('tanggal_mulai')
                    <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>


                <div class="form-group">

                    <label>Selesai Magang</label>

                    <input
                        type="date"
                        name="tanggal_selesai"
                        value="{{ old('tanggal_selesai', $pengajuan->tanggal_selesai) }}"
                        required>

                    @error('tanggal_selesai')
                    <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

            </div>


            <!-- ================= KONTAK ================= -->

            <div class="grid">

                <div class="form-group">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $pengajuan->mahasiswa->email) }}"
                        required
                        placeholder="mhs@email.com">

                    @error('email')
                    <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>


                <div class="form-group">

                    <label>No. Telepon</label>

                    <input
                        type="text"
                        name="no_telepon"
                        value="{{ old('no_telepon', $pengajuan->mahasiswa->no_telepon) }}"
                        required
                        placeholder="Contoh: 08123456789">

                    @error('no_telepon')
                    <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

            </div>


            <!-- ================= PEMBIMBING ================= -->

            <div class="grid">

                <div class="form-group">

                    <label>Nama Pembimbing Lapangan</label>

                    <input
                        type="text"
                        name="nama_pembimbing"
                        value="{{ old('nama_pembimbing', $pengajuan->nama_pembimbing) }}"
                        placeholder="Masukkan nama pembimbing (opsional)">

                    @error('nama_pembimbing')
                    <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

            </div>


            <!-- ================= DOKUMEN ================= -->

            <div class="upload-section">

                <label>Dokumen Persyaratan Magang</label>

                <p class="upload-description">
                    Kelola dokumen persyaratan mahasiswa. Upload file baru jika ingin mengganti dokumen lama.
                    Maksimal ukuran setiap file 4MB.
                </p>


                <div class="grid">

                    <!-- ================= SURAT PENGANTAR ================= -->

                    <div>

                        <label>Surat Pengantar Kampus</label>

                        @if($pengajuan->surat_permohonan)

                        <div class="current-file">

                            <i class="fa-solid fa-file-signature current-file-icon"></i>

                            <div class="current-file-info">

                                <p class="current-file-label">
                                    Dokumen saat ini:
                                </p>

                                <a
                                    href="{{ asset('uploads/surat_permohonan/' . $pengajuan->surat_permohonan) }}"
                                    target="_blank">

                                    {{ $pengajuan->surat_permohonan }}

                                </a>

                            </div>

                        </div>

                        @endif


                        <p class="replace-text">
                            Pilih file baru jika ingin mengganti dokumen.
                        </p>


                        <div
                            class="upload-box"
                            onclick="document.getElementById('surat_pengantar').click()">

                            <i class="fa-solid fa-file-signature upload-icon"></i>

                            <p class="upload-text">
                                Klik untuk upload Surat Pengantar baru
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


                    <!-- ================= PROPOSAL ================= -->

                    <div>

                        <label>Proposal Magang</label>

                        @if($pengajuan->proposal)

                        <div class="current-file">

                            <i class="fa-solid fa-file-lines current-file-icon"></i>

                            <div class="current-file-info">

                                <p class="current-file-label">
                                    Dokumen saat ini:
                                </p>

                                <a
                                    href="{{ asset('uploads/proposal/' . $pengajuan->proposal) }}"
                                    target="_blank">

                                    {{ $pengajuan->proposal }}

                                </a>

                            </div>

                        </div>

                        @endif


                        <p class="replace-text">
                            Pilih file baru jika ingin mengganti dokumen.
                        </p>


                        <div
                            class="upload-box"
                            onclick="document.getElementById('proposal').click()">

                            <i class="fa-solid fa-file-lines upload-icon"></i>

                            <p class="upload-text">
                                Klik untuk upload Proposal baru
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


                <!-- ================= CV ================= -->

                <div style="margin-top: 20px;">

                    <label>CV / Curriculum Vitae</label>

                    @if($pengajuan->cv)

                    <div class="current-file">

                        <i class="fa-solid fa-file-user current-file-icon"></i>

                        <div class="current-file-info">

                            <p class="current-file-label">
                                Dokumen saat ini:
                            </p>

                            <a
                                href="{{ asset('uploads/cv/' . $pengajuan->cv) }}"
                                target="_blank">

                                {{ $pengajuan->cv }}

                            </a>

                        </div>

                    </div>

                    @endif


                    <p class="replace-text">
                        Pilih file baru jika ingin mengganti dokumen.
                    </p>


                    <div
                        class="upload-box"
                        onclick="document.getElementById('cv').click()">

                        <i class="fa-solid fa-file-user upload-icon"></i>

                        <p class="upload-text">
                            Klik untuk upload CV baru
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


            <!-- ================= BUTTON ================= -->

            <div class="button-area">

                <a
                    href="{{ route('admin.data') }}"
                    class="btn-cancel">

                    Batal

                </a>

                <button
                    class="btn-save"
                    type="submit">

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* ================= SIDEBAR ================= */

        const menuBtn = document.getElementById('menuBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (menuBtn && sidebar && sidebarOverlay) {

            menuBtn.addEventListener('click', function() {

                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');

            });

            sidebarOverlay.addEventListener('click', function() {

                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');

            });

        }


        /* ================= SELECT TAMBAH BARU ================= */

        const selectors = [

            {
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

            if (!selectEl || !boxEl || !inputEl) {
                return;
            }


            function toggle() {

                if (selectEl.value === 'new') {

                    boxEl.classList.remove('d-none');

                    inputEl.required = true;

                } else {

                    boxEl.classList.add('d-none');

                    inputEl.required = false;

                    /*
                     * Jangan hapus value ketika halaman pertama kali
                     * dibuka jika berasal dari old().
                     */
                }

            }


            selectEl.addEventListener('change', function() {

                if (selectEl.value !== 'new') {
                    inputEl.value = '';
                }

                toggle();

            });


            toggle();

        });


        /* ================= FILE UPLOAD ================= */

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


            if (!fileInput || !fileInfo) {
                return;
            }


            fileInput.addEventListener('change', function() {

                if (fileInput.files.length > 0) {

                    fileInfo.textContent =
                        'File baru: ' + fileInput.files[0].name;

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