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
        <button type="button" class="menu-btn" id="menuBtn">
            <i class="fa-solid fa-bars"></i>
        </button>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </button>
        </form>
    </div>
</header>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu">
        <i class="fa-regular fa-chart-bar"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.data') }}" class="sidebar-menu active">
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
</div>

<div class="content">

    <div class="page-header-card">
        <row align=center gap=2>
            <box background="#E8F5F5" radius=lg size="52px" align=center justify=center>
                <icon name=edit color="#0A5F63" size=xl/>
            </box>
            <box gap=0>
                <title size=lg>Edit Data Mahasiswa</title>
                <caption>Perbarui informasi mahasiswa PKL dan simpan perubahan terbaru.</caption>
            </box>
        </row>
    </div>

    <form action="{{ route('admin.update', $pengajuan->id_pengajuan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">

            <div class="section">
                <div class="section-title">
                    <i class="fa-regular fa-user"></i>
                    <span>Informasi Mahasiswa</span>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap',$pengajuan->mahasiswa->nama_lengkap) }}" required>
                        @error('nama_lengkap')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin',$pengajuan->mahasiswa->jenis_kelamin)=='Laki-laki'?'selected':'' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin',$pengajuan->mahasiswa->jenis_kelamin)=='Perempuan'?'selected':'' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim" value="{{ old('nim',$pengajuan->mahasiswa->nim) }}" required>
                        @error('nim')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label>Universitas</label>
                        <select name="universitas_id" id="select_universitas" required>
                            <option value="">Pilih Universitas</option>
                            @foreach($universitas as $u)
                                <option value="{{ $u->id_universitas }}" {{ old('universitas_id',$pengajuan->mahasiswa->universitas_id)==$u->id_universitas?'selected':'' }}>
                                    {{ $u->nama_universitas }}
                                </option>
                            @endforeach
                            <option value="new" {{ old('universitas_id')=='new'?'selected':'' }}>
                                + Tambah Universitas Baru
                            </option>
                        </select>

                        <div id="field_universitas_baru" class="box-new-input d-none">
                            <label>Universitas Baru</label>
                            <input type="text" name="nama_universitas_baru" id="nama_universitas_baru" value="{{ old('nama_universitas_baru') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jurusan / Prodi</label>
                        <select name="jurusan_id" id="select_jurusan" required>
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}" {{ old('jurusan_id',$pengajuan->mahasiswa->jurusan_id)==$j->id_jurusan?'selected':'' }}>
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                            <option value="new" {{ old('jurusan_id')=='new'?'selected':'' }}>
                                + Tambah Jurusan Baru
                            </option>
                        </select>

                        <div id="field_jurusan_baru" class="box-new-input d-none">
                            <label>Jurusan Baru</label>
                            <input type="text" name="nama_jurusan_baru" id="nama_jurusan_baru" value="{{ old('nama_jurusan_baru') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email',$pengajuan->mahasiswa->email) }}" required>
                        @error('email')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon',$pengajuan->mahasiswa->no_telepon) }}" required>
                        @error('no_telepon')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">
                    <i class="fa-solid fa-briefcase"></i>
                    <span>Informasi Magang</span>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Divisi Penempatan</label>
                        <select name="divisi_id" id="select_divisi" required>
                            <option value="">Pilih Divisi</option>
                            @foreach($divisi as $d)
                                <option value="{{ $d->id_divisi }}" {{ old('divisi_id',$pengajuan->divisi_id)==$d->id_divisi?'selected':'' }}>
                                    {{ $d->nama_divisi }}
                                </option>
                            @endforeach
                            <option value="new" {{ old('divisi_id')=='new'?'selected':'' }}>
                                + Tambah Divisi Baru
                            </option>
                        </select>

                        <div id="field_divisi_baru" class="box-new-input d-none">
                            <label>Divisi Baru</label>
                            <input type="text" name="nama_divisi_baru" id="nama_divisi_baru" value="{{ old('nama_divisi_baru') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Pembimbing Lapangan</label>
                        <input type="text" name="nama_pembimbing" value="{{ old('nama_pembimbing',$pengajuan->nama_pembimbing) }}">
                    </div>

                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai',$pengajuan->tanggal_mulai) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai',$pengajuan->tanggal_selesai) }}" required>
                    </div>
                </div>
            </div>

                        <div class="section">
                <div class="section-title">
                    <i class="fa-regular fa-folder-open"></i>
                    <span>Dokumen Persyaratan Magang</span>
                </div>

                <p class="section-desc">
                    Dokumen yang sudah ada tetap tersimpan. Upload file baru hanya jika ingin mengganti dokumen lama.
                </p>

                <div class="document-upload-grid">

                    <!-- Surat Pengantar -->
                    <div class="upload-card">
                        <div class="upload-header">
                            <div class="upload-icon-box">
                                <i class="fa-solid fa-file-signature"></i>
                            </div>
                            <div>
                                <h4>Surat Pengantar</h4>
                                <span>PDF / DOC / DOCX</span>
                            </div>
                        </div>

                        @if($pengajuan->surat_permohonan)
                            <div class="current-file">
                                <i class="fa-solid fa-file-pdf"></i>
                                <a href="{{ asset('uploads/surat_permohonan/'.$pengajuan->surat_permohonan) }}" target="_blank">
                                    {{ $pengajuan->surat_permohonan }}
                                </a>
                            </div>
                        @endif

                        <label for="surat_pengantar" class="upload-button">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            Ganti Dokumen
                        </label>

                        <input type="file" id="surat_pengantar" name="surat_permohonan" accept=".pdf,.doc,.docx" hidden>

                        <div id="surat_pengantar-info" class="selected-file d-none"></div>

                        @error('surat_permohonan')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Proposal -->
                    <div class="upload-card">
                        <div class="upload-header">
                            <div class="upload-icon-box">
                                <i class="fa-solid fa-file-lines"></i>
                            </div>
                            <div>
                                <h4>Proposal Magang</h4>
                                <span>PDF / DOC / DOCX</span>
                            </div>
                        </div>

                        @if($pengajuan->proposal)
                            <div class="current-file">
                                <i class="fa-solid fa-file-pdf"></i>
                                <a href="{{ asset('uploads/proposal/'.$pengajuan->proposal) }}" target="_blank">
                                    {{ $pengajuan->proposal }}
                                </a>
                            </div>
                        @endif

                        <label for="proposal" class="upload-button">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            Ganti Dokumen
                        </label>

                        <input type="file" id="proposal" name="proposal" accept=".pdf,.doc,.docx" hidden>

                        <div id="proposal-info" class="selected-file d-none"></div>

                        @error('proposal')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CV -->
                    <div class="upload-card">
                        <div class="upload-header">
                            <div class="upload-icon-box">
                                <i class="fa-solid fa-file-user"></i>
                            </div>
                            <div>
                                <h4>CV Mahasiswa</h4>
                                <span>PDF / DOC / DOCX</span>
                            </div>
                        </div>

                        @if($pengajuan->cv)
                            <div class="current-file">
                                <i class="fa-solid fa-file-pdf"></i>
                                <a href="{{ asset('uploads/cv/'.$pengajuan->cv) }}" target="_blank">
                                    {{ $pengajuan->cv }}
                                </a>
                            </div>
                        @endif

                        <label for="cv" class="upload-button">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            Ganti Dokumen
                        </label>

                        <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" hidden>

                        <div id="cv-info" class="selected-file d-none"></div>

                        @error('cv')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="button-area">
                <a href="{{ route('admin.detail',$pengajuan->id_pengajuan) }}" class="btn-cancel">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>

                <button type="submit" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Perubahan
                </button>
            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Sidebar
    const menuBtn = document.getElementById('menuBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    menuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Input baru
    [
        ['select_universitas','field_universitas_baru','nama_universitas_baru'],
        ['select_jurusan','field_jurusan_baru','nama_jurusan_baru'],
        ['select_divisi','field_divisi_baru','nama_divisi_baru']
    ].forEach(([select,field,input])=>{

        const s=document.getElementById(select);
        const f=document.getElementById(field);
        const i=document.getElementById(input);

        const toggle=()=>{
            if(s.value==='new'){
                f.classList.remove('d-none');
                i.required=true;
            }else{
                f.classList.add('d-none');
                i.required=false;
            }
        };

        s.addEventListener('change',toggle);
        toggle();
    });

    // Preview nama file
    [
        ['surat_pengantar','surat_pengantar-info'],
        ['proposal','proposal-info'],
        ['cv','cv-info']
    ].forEach(([input,info])=>{

        const file=document.getElementById(input);
        const text=document.getElementById(info);

        file.addEventListener('change',()=>{
            if(file.files.length){
                text.textContent='File baru : '+file.files[0].name;
                text.classList.remove('d-none');
            }else{
                text.textContent='';
                text.classList.add('d-none');
            }
        });

    });

});
</script>

</body>
</html>