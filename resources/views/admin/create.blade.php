<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa - Sistem Magang</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

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

    <div class="header-right">
        <button type="button" class="menu-btn" id="menuBtn" title="Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout" title="Logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </button>
        </form>
    </div>
</header>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fa-regular fa-chart-bar"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.data') }}" class="sidebar-menu {{ request()->routeIs('admin.data') || request()->routeIs('admin.create') ? 'active' : '' }}">
        <i class="fa-regular fa-user"></i>
        <span>Data Mahasiswa</span>
    </a>

    <a href="{{ route('admin.dokumen') }}" class="sidebar-menu">
        <i class="fa-regular fa-file-lines"></i>
        <span>Dokumen Verification</span>
    </a>

    <a href="{{ route('admin.sertifikat') }}" class="sidebar-menu">
        <i class="fa-regular fa-file"></i>
        <span>Sertifikat</span>
    </a>
</aside>

<main class="content">
    <div class="page-heading">
        <h1>Tambah Mahasiswa</h1>
        <p>Tambahkan data mahasiswa magang secara lengkap untuk dipantau selama pelaksanaan PKL.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div>
                <h2>Data Mahasiswa Magang</h2>
                <p>Lengkapi seluruh informasi mahasiswa dan dokumen persyaratan.</p>
            </div>
        </div>

        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-section">
                <div class="section-title">
                    <i class="fa-regular fa-user"></i>
                    <div>
                        <h3>Informasi Mahasiswa</h3>
                        <p>Masukkan identitas dasar mahasiswa.</p>
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Contoh: Jane Doe" required>
                        @error('nama_lengkap')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nim">NIM</label>
                        <input type="text" id="nim" name="nim" value="{{ old('nim') }}" placeholder="Masukkan NIM..." required>
                        @error('nim')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required>
                            <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="mhs@email.com" required>
                        @error('email')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="no_telepon">No. Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 08123456789" required>
                        @error('no_telepon')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama_pembimbing">Nama Pembimbing Lapangan</label>
                        <input type="text" id="nama_pembimbing" name="nama_pembimbing" value="{{ old('nama_pembimbing') }}" placeholder="Masukkan nama pembimbing">
                        @error('nama_pembimbing')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
                    <i class="fa-solid fa-building-columns"></i>
                    <div>
                        <h3>Informasi Akademik & Penempatan</h3>
                        <p>Tentukan universitas, jurusan, dan divisi mahasiswa.</p>
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label for="select_universitas">Universitas</label>
                        <select name="universitas_id" id="select_universitas" required>
                            <option value="" disabled {{ old('universitas_id') ? '' : 'selected' }}>Pilih Universitas</option>
                            @foreach($universitas as $u)
                                <option value="{{ $u->id_universitas }}" {{ old('universitas_id') == $u->id_universitas ? 'selected' : '' }}>
                                    {{ $u->nama_universitas }}
                                </option>
                            @endforeach
                            <option value="new" {{ old('universitas_id') == 'new' ? 'selected' : '' }}>+ Tambah Universitas Baru...</option>
                        </select>
                        @error('universitas_id')
                            <p class="error-text">{{ $message }}</p>
                        @enderror

                        <div id="field_universitas_baru" class="box-new-input d-none">
                            <label for="nama_universitas_baru">Nama Universitas Baru</label>
                            <input type="text" name="nama_universitas_baru" id="nama_universitas_baru" value="{{ old('nama_universitas_baru') }}" placeholder="Misal: Universitas Indonesia">
                            @error('nama_universitas_baru')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="select_jurusan">Jurusan / Prodi</label>
                        <select name="jurusan_id" id="select_jurusan" required>
                            <option value="" disabled {{ old('jurusan_id') ? '' : 'selected' }}>Pilih Jurusan</option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}" {{ old('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                            <option value="new" {{ old('jurusan_id') == 'new' ? 'selected' : '' }}>+ Tambah Jurusan Baru...</option>
                        </select>
                        @error('jurusan_id')
                            <p class="error-text">{{ $message }}</p>
                        @enderror

                        <div id="field_jurusan_baru" class="box-new-input d-none">
                            <label for="nama_jurusan_baru">Nama Jurusan Baru</label>
                            <input type="text" name="nama_jurusan_baru" id="nama_jurusan_baru" value="{{ old('nama_jurusan_baru') }}" placeholder="Misal: Teknik Elektro">
                            @error('nama_jurusan_baru')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="select_divisi">Divisi Penempatan</label>
                        <select name="divisi_id" id="select_divisi" required>
                            <option value="" disabled {{ old('divisi_id') ? '' : 'selected' }}>Pilih Divisi</option>
                            @foreach($divisi as $d)
                                <option value="{{ $d->id_divisi }}" {{ old('divisi_id') == $d->id_divisi ? 'selected' : '' }}>
                                    {{ $d->nama_divisi }}
                                </option>
                            @endforeach
                            <option value="new" {{ old('divisi_id') == 'new' ? 'selected' : '' }}>+ Tambah Divisi Baru...</option>
                        </select>
                        @error('divisi_id')
                            <p class="error-text">{{ $message }}</p>
                        @enderror

                        <div id="field_divisi_baru" class="box-new-input d-none">
                            <label for="nama_divisi_baru">Nama Divisi Baru</label>
                            <input type="text" name="nama_divisi_baru" id="nama_divisi_baru" value="{{ old('nama_divisi_baru') }}" placeholder="Misal: Keamanan Informasi">
                            @error('nama_divisi_baru')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
                    <i class="fa-regular fa-calendar-days"></i>
                    <div>
                        <h3>Periode Magang</h3>
                        <p>Tentukan periode pelaksanaan kegiatan PKL.</p>
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label for="tanggal_mulai">Mulai Magang</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                        @error('tanggal_mulai')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tanggal_selesai">Selesai Magang</label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                        @error('tanggal_selesai')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-section upload-section">
                <div class="section-title">
                    <i class="fa-regular fa-folder-open"></i>
                    <div>
                        <h3>Dokumen Persyaratan Magang</h3>
                        <p>Unggah dokumen dalam format PDF, DOC, atau DOCX dengan ukuran maksimal 4MB per file.</p>
                    </div>
                </div>

                <div class="upload-grid">
                    <div class="upload-item">
                        <label>Surat Pengantar Kampus</label>
                        <div class="upload-box" data-input="surat_pengantar">
                            <input type="file" id="surat_pengantar" name="surat_permohonan" accept=".pdf,.doc,.docx,.txt">
                            <div class="upload-content">
                                <div class="upload-icon">
                                    <i class="fa-solid fa-file-signature"></i>
                                </div>
                                <strong>Pilih Surat Pengantar</strong>
                                <span>PDF / DOC / DOCX • Maks. 4MB</span>
                                <div id="surat_pengantar-info" class="file-selected-name d-none"></div>
                            </div>
                        </div>
                        @error('surat_permohonan')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="upload-item">
                        <label>Proposal Magang</label>
                        <div class="upload-box" data-input="proposal">
                            <input type="file" id="proposal" name="proposal" accept=".pdf,.doc,.docx,.txt">
                            <div class="upload-content">
                                <div class="upload-icon">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>
                                <strong>Pilih Proposal Magang</strong>
                                <span>PDF / DOC / DOCX • Maks. 4MB</span>
                                <div id="proposal-info" class="file-selected-name d-none"></div>
                            </div>
                        </div>
                        @error('proposal')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="upload-item">
                        <label>CV / Curriculum Vitae</label>
                        <div class="upload-box" data-input="cv">
                            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx,.txt">
                            <div class="upload-content">
                                <div class="upload-icon">
                                    <i class="fa-solid fa-file-user"></i>
                                </div>
                                <strong>Pilih CV</strong>
                                <span>PDF / DOC / DOCX • Maks. 4MB</span>
                                <div id="cv-info" class="file-selected-name d-none"></div>
                            </div>
                        </div>
                        @error('cv')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="button-area">
                <a href="{{ route('admin.data') }}" class="btn-cancel">
                    <i class="fa-solid fa-xmark"></i>
                    Batal
                </a>
                <button class="btn-save" type="submit">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('menuBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    menuBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

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

        function toggle() {
            const isNew = selectEl.value === 'new';
            boxEl.classList.toggle('d-none', !isNew);
            inputEl.required = isNew;

            if (!isNew) {
                inputEl.value = '';
            }
        }

        selectEl.addEventListener('change', toggle);
        toggle();
    });

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

    uploadFiles.forEach(item => {
        const fileInput = document.getElementById(item.input);
        const fileInfo = document.getElementById(item.info);

        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                fileInfo.textContent = fileInput.files[0].name;
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