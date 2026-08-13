<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/create.css') }}">
    
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
                        <input type="text" name="nama_pembimbing" value="{{ old('nama_pembimbing') }}" placeholder="Masukkan nama pembimbing">
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