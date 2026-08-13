<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Mahasiswa</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/admin/edit.css') }}">
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