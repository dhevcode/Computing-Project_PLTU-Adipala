<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Verification - Monitoring Mahasiswa PKL</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/dokumen.css') }}">
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
        <button class="menu-btn" id="menuBtn">
            <i class="fa-solid fa-bars"></i>
        </button>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </button>
        </form>
    </div>
</header>

@include('admin.sidebar')

<div class="content">

    <div class="page-header-card">
        <div>
            <h1>Dokumen Verification</h1>
            <p>Kelola, verifikasi, dan kirim surat balasan mahasiswa PKL.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert success">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert error">
        <i class="fa-solid fa-circle-xmark"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon verified">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div class="stat-info">
                <span>Verified</span>
                <h2>{{ $verified }}</h2>
                <small>Dokumen telah diverifikasi</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="fa-regular fa-clock"></i>
            </div>

            <div class="stat-info">
                <span>Not Verified</span>
                <h2>{{ $notVerified }}</h2>
                <small>Menunggu surat balasan</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fa-solid fa-users"></i>
            </div>

            <div class="stat-info">
                <span>Total Data</span>
                <h2>{{ $total }}</h2>
                <small>Mahasiswa ditampilkan</small>
            </div>
        </div>

    </div>

    <form action="{{ route('admin.dokumen') }}" method="GET" class="filter-card">

        <div class="filter-header">
            <div class="filter-title-wrap">
                <div class="filter-icon">
                    <i class="fa-solid fa-filter"></i>
                </div>

                <div>
                    <h3>Filter Dokumen</h3>
                    <p>Gunakan filter untuk mencari data mahasiswa berdasarkan kriteria.</p>
                </div>
            </div>
        </div>

        <div class="filter-grid">

            <div class="input-group search">
                <label>Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, NIM, Kampus...">
            </div>

            <div class="input-group">
                <label>Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
            </div>

            <div class="input-group">
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">
            </div>

            <div class="input-group">
                <label>Status</label>
                <select name="status_verifikasi">
                    <option value="">Semua</option>
                    <option value="verified" {{ request('status_verifikasi')=='verified' ? 'selected':'' }}>Verified</option>
                    <option value="not_verified" {{ request('status_verifikasi')=='not_verified' ? 'selected':'' }}>Not Verified</option>
                </select>
            </div>

            <div class="input-group">
                <label>Universitas</label>
                <select name="universitas_id">
                    <option value="">Semua</option>
                    @foreach($universitas as $u)
                    <option value="{{ $u->id_universitas }}" {{ request('universitas_id')==$u->id_universitas?'selected':'' }}>
                        {{ $u->nama_universitas }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label>Jurusan</label>
                <select name="jurusan_id">
                    <option value="">Semua</option>
                    @foreach($jurusan as $j)
                    <option value="{{ $j->id_jurusan }}" {{ request('jurusan_id')==$j->id_jurusan?'selected':'' }}>
                        {{ $j->nama_jurusan }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label>Divisi</label>
                <select name="divisi_id">
                    <option value="">Semua</option>
                    @foreach($divisi as $d)
                    <option value="{{ $d->id_divisi }}" {{ request('divisi_id')==$d->id_divisi?'selected':'' }}>
                        {{ $d->nama_divisi }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin">
                    <option value="">Semua</option>
                    <option value="Laki-laki" {{ request('jenis_kelamin')=='Laki-laki'?'selected':'' }}>Laki-laki</option>
                    <option value="Perempuan" {{ request('jenis_kelamin')=='Perempuan'?'selected':'' }}>Perempuan</option>
                </select>
            </div>

            <div class="input-group sort-group">

                <label for="sort">Urutkan Berdasarkan</label>

                <select name="sort" id="sort">

                    <option value="terbaru"
                        {{ request('sort', 'terbaru') == 'terbaru' ? 'selected' : '' }}>
                        Terbaru Ditambahkan
                    </option>

                    <option value="terlama"
                        {{ request('sort') == 'terlama' ? 'selected' : '' }}>
                        Terlama Ditambahkan
                    </option>

                    <option value="nama_asc"
                        {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>
                        Nama A–Z
                    </option>

                    <option value="nama_desc"
                        {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>
                        Nama Z–A
                    </option>

                    <option value="mulai_asc"
                        {{ request('sort') == 'mulai_asc' ? 'selected' : '' }}>
                        Tanggal Mulai Terdekat
                    </option>

                    <option value="mulai_desc"
                        {{ request('sort') == 'mulai_desc' ? 'selected' : '' }}>
                        Tanggal Mulai Terjauh
                    </option>

                    <option value="selesai_asc"
                        {{ request('sort') == 'selesai_asc' ? 'selected' : '' }}>
                        Tanggal Selesai Terdekat
                    </option>

                    <option value="selesai_desc"
                        {{ request('sort') == 'selesai_desc' ? 'selected' : '' }}>
                        Tanggal Selesai Terjauh
                    </option>

                </select>

            </div>
        </div>

        <div class="filter-actions">
            <a href="{{ route('admin.dokumen') }}" class="btn-reset">
                <i class="fa-solid fa-rotate-left"></i>
                Reset
            </a>

            <button type="submit" class="btn-filter">
                <i class="fa-solid fa-filter"></i>
                Terapkan Filter
            </button>
        </div>

    </form>

    <div class="table-card">

        <div class="table-header">
            <div>
                <h3>Data Dokumen Mahasiswa</h3>
                <p>{{ $total }} mahasiswa ditampilkan</p>
            </div>
        </div>

        <div class="table-wrapper">

            <table class="table-admin">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>Universitas</th>
                        <th>Prodi</th>
                        <th>Divisi</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pengajuan as $index => $p)
                    <tr>
                        <td>
                            {{ $pengajuan->firstItem() + $index }}
                        </td>

                        <td>
                            <strong>{{ $p->mahasiswa->nama_lengkap }}</strong><br>
                            <small>{{ $p->mahasiswa->nim }}</small>
                        </td>

                        <td>{{ $p->mahasiswa->universitas->nama_universitas ?? '-' }}</td>
                        <td>{{ $p->mahasiswa->jurusan->nama_jurusan ?? '-' }}</td>
                        <td>{{ $p->divisi->nama_divisi ?? '-' }}</td>
                        <td>{{ $p->mahasiswa->email }}</td>

                        <td>
                            @if($p->surat_balasan)
                            <span class="status verified">Verified</span>
                            @else
                            <span class="status pending">Pending</span>
                            @endif
                        </td>

                        <td>
                            <div class="action">

                                @if($p->surat_balasan)

                                <a href="{{ route('admin.dokumen.lihat',$p->id_pengajuan) }}" target="_blank" class="btn-action secondary">
                                    <i class="fa-regular fa-eye"></i>
                                    Lihat
                                </a>

                                <button
                                    type="button"
                                    class="btn-action primary change-surat-btn"
                                    data-action="{{ route('admin.dokumen.upload',$p->id_pengajuan) }}"
                                    data-name="{{ $p->mahasiswa->nama_lengkap }}"
                                    data-email="{{ $p->mahasiswa->email }}">
                                    <i class="fa-solid fa-upload"></i>
                                    Ganti
                                </button>

                                @else

                                <button
                                    type="button"
                                    class="btn-action primary upload-surat-btn"
                                    data-action="{{ route('admin.dokumen.upload',$p->id_pengajuan) }}"
                                    data-name="{{ $p->mahasiswa->nama_lengkap }}"
                                    data-email="{{ $p->mahasiswa->email }}">
                                    <i class="fa-solid fa-upload"></i>
                                    Upload
                                </button>

                                @endif

                            </div>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fa-regular fa-folder-open"></i>
                            <p>Belum ada data mahasiswa.</p>
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>
            {{-- ================= PAGINATION ================= --}}
            @if($pengajuan->hasPages())

                <div class="pagination-wrapper">

                    {{-- PREVIOUS --}}
                    @if($pengajuan->onFirstPage())

                        <span class="pagination-btn disabled">
                            <i class="fa-solid fa-chevron-left"></i>
                        </span>

                    @else

                        <a
                            href="{{ $pengajuan->previousPageUrl() }}"
                            class="pagination-btn"
                        >
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>

                    @endif


                    {{-- NOMOR HALAMAN --}}
                    @foreach($pengajuan->getUrlRange(1, $pengajuan->lastPage()) as $page => $url)

                        @if($page == $pengajuan->currentPage())

                            <span class="pagination-btn active">
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $url }}"
                                class="pagination-btn"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endforeach


                    {{-- NEXT --}}
                    @if($pengajuan->hasMorePages())

                        <a
                            href="{{ $pengajuan->nextPageUrl() }}"
                            class="pagination-btn"
                        >
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>

                    @else

                        <span class="pagination-btn disabled">
                            <i class="fa-solid fa-chevron-right"></i>
                        </span>

                    @endif

                </div>

            @endif
        </div>

    </div>

</div>

<div class="modal-overlay" id="uploadModal">

    <div class="upload-modal">

        <div class="modal-header">

            <h3 id="uploadModalTitle">Upload Surat Balasan</h3>

            <button type="button" class="modal-close" onclick="closeUploadModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>

        <form id="uploadForm" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-body">

                <div class="student-info">
                    <strong id="modalStudentName"></strong>
                    <span id="modalStudentEmail"></span>
                </div>

                <label class="upload-label">Surat Balasan (PDF)</label>

                <div class="upload-area" onclick="document.getElementById('suratBalasan').click()">

                    <i class="fa-solid fa-file-pdf"></i>

                    <p id="uploadLabel">Klik untuk memilih file PDF</p>

                    <small>Maksimal ukuran 4 MB</small>

                    <input
                        type="file"
                        id="suratBalasan"
                        name="surat_balasan"
                        accept=".pdf,application/pdf"
                        hidden
                        required>

                    <div id="selectedFile" class="selected-file"></div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn-cancel" onclick="closeUploadModal()">
                    Batal
                </button>

                <button type="submit" class="btn-save" id="submitUpload">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span id="submitUploadText">Simpan & Kirim</span>
                </button>

            </div>

        </form>

    </div>

</div>

<script>
const menuBtn=document.getElementById('menuBtn');
const sidebar=document.getElementById('sidebar');
const overlay=document.getElementById('sidebarOverlay');

menuBtn.onclick=()=>{
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
};

overlay.onclick=()=>{
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
};

const uploadModal=document.getElementById('uploadModal');
const uploadForm=document.getElementById('uploadForm');
const fileInput=document.getElementById('suratBalasan');
const selectedFile=document.getElementById('selectedFile');

document.querySelectorAll('.upload-surat-btn,.change-surat-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{

        uploadForm.action=btn.dataset.action;

        document.getElementById('modalStudentName').textContent=btn.dataset.name;
        document.getElementById('modalStudentEmail').textContent=btn.dataset.email;

        const change=btn.classList.contains('change-surat-btn');

        document.getElementById('uploadModalTitle').textContent=change?'Ganti Surat Balasan':'Upload Surat Balasan';
        document.getElementById('submitUploadText').textContent=change?'Ganti & Kirim':'Simpan & Kirim';

        fileInput.value='';
        selectedFile.textContent='';

        uploadModal.classList.add('active');

    });
});

function closeUploadModal(){
    uploadModal.classList.remove('active');
    fileInput.value='';
    selectedFile.textContent='';
}

uploadModal.onclick=e=>{
    if(e.target===uploadModal) closeUploadModal();
};

document.addEventListener('keydown',e=>{
    if(e.key==='Escape') closeUploadModal();
});

fileInput.onchange=()=>{
    if(fileInput.files.length){
        selectedFile.textContent=fileInput.files[0].name;
    }
};

uploadForm.onsubmit=e=>{

    const file=fileInput.files[0];

    if(!file){
        e.preventDefault();
        alert('Pilih file PDF terlebih dahulu.');
        return;
    }

    if(file.size>4*1024*1024){
        e.preventDefault();
        alert('Ukuran file maksimal 4 MB.');
        return;
    }

    document.getElementById('submitUpload').disabled=true;
};
</script>

</body>
</html>