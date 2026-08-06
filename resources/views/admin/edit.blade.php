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
        <div class="header-right">


            <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline-block;">
                @csrf
                <button type="submit" class="logout" style="background: none; border: none; cursor: pointer; color: white;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <div class="content">
        <div class="card">
            <h1>Ubah data Mahasiswa Magang</h1>
            <p class="subtitle">
                Ubah data mahasiswa PKL dan simpan perubahan terbaru.
            </p>

            <form action="{{ route('admin.update', $pengajuan->id_pengajuan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid">

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pengajuan->mahasiswa->nama_lengkap) }}" required placeholder="Contoh: Jane Doe">
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
                        <input type="text" name="nim" value="{{ old('nim', $pengajuan->mahasiswa->nim) }}" required placeholder="Masukkan NIM...">
                        @error('nim') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Universitas</label>
                        <select name="universitas_id" id="select_universitas" required>
                            <option value="" disabled>Pilih Universitas</option>
                            @foreach($universitas as $u)
                            <option value="{{ $u->id_universitas }}" {{ old('universitas_id', $pengajuan->mahasiswa->universitas_id) == $u->id_universitas ? 'selected' : '' }}>
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
                            <option value="" disabled>Pilih Jurusan</option>
                            @foreach($jurusan as $j)
                            <option value="{{ $j->id_jurusan }}" {{ old('jurusan_id', $pengajuan->mahasiswa->jurusan_id) == $j->id_jurusan ? 'selected' : '' }}>
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
                            <option value="" disabled>Pilih Divisi</option>
                            @foreach($divisi as $d)
                            <option value="{{ $d->id_divisi }}" {{ old('divisi_id', $pengajuan->divisi_id) == $d->id_divisi ? 'selected' : '' }}>
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
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $pengajuan->tanggal_mulai) }}" required>
                        @error('tanggal_mulai') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Selesai Magang</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pengajuan->tanggal_selesai) }}" required>
                        @error('tanggal_selesai') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $pengajuan->mahasiswa->email) }}" required placeholder="mhs@email.com">
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $pengajuan->mahasiswa->no_telepon) }}" required placeholder="Contoh: 08123456789">
                        @error('no_telepon') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Nama Pembimbing Lapangan</label>
                        <input type="text" name="nama_pembimbing" value="{{ old('nama_pembimbing', $pengajuan->nama_pembimbing) }}" placeholder="Masukkan nama pembimbing (opsional)">
                        @error('nama_pembimbing') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <!-- <div class="form-group">
                        <label>Status Magang</label>
                        <select name="status" required>
                            <option value="Akan Magang" {{ old('status', $pengajuan->status) == 'Akan Magang' ? 'selected' : '' }}>Akan Magang</option>
                            <option value="Sedang Magang" {{ old('status', $pengajuan->status) == 'Sedang Magang' ? 'selected' : '' }}>Sedang Magang</option>
                            <option value="Selesai Magang" {{ old('status', $pengajuan->status) == 'Selesai Magang' ? 'selected' : '' }}>Selesai Magang</option>
                        </select>
                        @error('status') <p class="error-text">{{ $message }}</p> @enderror
                    </div> -->
                </div>

                <div class="upload-section">
                    <label>Surat Pengantar / Dokumen Pendukung (PDF/Doc/Docx/Image)</label>

                    @if($pengajuan->surat_permohonan)
                    <div style="background: #F4F7F6; padding: 10px 14px; border-radius: 8px; margin-bottom: 12px; display: flex; align-items: center; gap: 10px; border: 1px solid #E4E4E4;">
                        <i class="fa-solid fa-file-invoice" style="color: #0A5F63; font-size: 18px;"></i>
                        <div style="flex: 1;">
                            <p style="font-size: 12px; color: #666; font-weight: 500;">Dokumen saat ini:</p>
                            <a href="{{ asset('uploads/surat_permohonan/' . $pengajuan->surat_permohonan) }}" target="_blank" style="color: #0A5F63; font-size: 13px; font-weight: 600; text-decoration: none;">
                                {{ $pengajuan->surat_permohonan }}
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="upload-box" onclick="document.getElementById('uploadFile').click()">
                        <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                        <p class="upload-text">Klik di sini untuk mengunggah dokumen baru (mengganti dokumen lama)</p>
                        <span>Maksimal ukuran 4MB</span>
                        <input type="file" id="uploadFile" name="surat_permohonan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display: none;">
                        <div id="file-info" class="file-selected-name d-none"></div>
                    </div>
                    @error('surat_permohonan') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="button-area">
                    <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Batal</a>
                    <button class="btn-save" type="submit">Simpan Perubahan</button>
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

            // Display selected file name
            const fileInput = document.getElementById('uploadFile');
            const fileInfo = document.getElementById('file-info');

            fileInput.addEventListener('change', function() {
                if (fileInput.files.length > 0) {
                    fileInfo.textContent = 'File terpilih: ' + fileInput.files[0].name;
                    fileInfo.classList.remove('d-none');
                } else {
                    fileInfo.classList.add('d-none');
                    fileInfo.textContent = '';
                }
            });
        });
    </script>

</body>

</html>