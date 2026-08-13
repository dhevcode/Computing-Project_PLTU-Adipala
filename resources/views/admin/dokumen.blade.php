<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Dokumen Mahasiswa - Monitoring Mahasiswa PKL
    </title>


    <!-- =====================================================
         FONT & FONT AWESOME
    ====================================================== -->

    <link rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/dokumen.css') }}">
</head>


<body>


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <header class="header">

        <div class="header-left">

            <div class="logo-box">

                <img
                    src="{{ asset('assets/image/logo-pln.png') }}"
                    alt="Logo PLN">

            </div>


            <div class="header-title">

                <h2>
                    Monitoring Mahasiswa PKL
                </h2>

                <p>
                    Kelola dan pantau data mahasiswa PKL dengan mudah dan efisien.
                </p>

            </div>

        </div>


        <!-- HEADER RIGHT -->

        <div class="header-right">

            <!-- HAMBURGER -->

            <button
                type="button"
                class="menu-btn"
                id="menuBtn"
                title="Menu">

                <i class="fa-solid fa-bars"></i>

            </button>


            <!-- LOGOUT -->

            <form
                action="{{ route('logout') }}"
                method="POST"
                style="margin: 0;">

                @csrf

                <button
                    type="submit"
                    class="logout"
                    title="Logout">

                    <i class="fa-solid fa-arrow-right-from-bracket"></i>

                </button>

            </form>

        </div>

    </header>



    <!-- =====================================================
         SIDEBAR OVERLAY
    ====================================================== -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay">
    </div>



    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <div
        class="sidebar"
        id="sidebar">


        <!-- DASHBOARD -->

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

            <i class="fa-regular fa-chart-bar"></i>

            <span>Dashboard</span>

        </a>


        <!-- Data Mahasiswa -->
        <a href="{{ route('admin.data') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-user"></i>

            <span>Data Mahasiswa</span>

        </a>


        <!-- Dokumen Verification -->
        <a href="{{ route('admin.dokumen') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-file-lines"></i>

            <span>Dokumen Verification</span>

        </a>


        <!-- Sertifikat -->
        <a href="{{ route('admin.sertifikat') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-file"></i>

            <span>Sertifikat</span>

        </a>

    </div>



    <!-- =====================================================
         CONTENT
    ====================================================== -->

    <div class="content">

    @if(session('success'))
    <div style="
        margin-bottom: 20px;
        padding: 14px 18px;
        background: #E8F8EF;
        color: #16834A;
        border: 1px solid #B8E8CC;
        border-radius: 10px;
        font-size: 12px;
    ">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="
        margin-bottom: 20px;
        padding: 14px 18px;
        background: #FDECEC;
        color: #C0392B;
        border: 1px solid #F5C6C2;
        border-radius: 10px;
        font-size: 12px;
    ">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ session('error') }}
    </div>
@endif


        <!-- =================================================
             STATISTIC CARDS
        ================================================== -->

        <div class="cards">


            <!-- VERIFIED -->

            <div class="card">

                <div class="icon-circle">

                    <i class="fa-regular fa-circle-check"></i>

                </div>

                <div class="card-title">
                    Verified
                </div>

                <div class="card-value">
                    {{ $verified }}
                </div>

            </div>



            <!-- NOT VERIFIED -->

            <div class="card">

                <div class="icon-circle">

                    <i class="fa-regular fa-circle-check"></i>

                </div>

                <div class="card-title">
                    Not verified
                </div>

               <div class="card-value">
                    {{ $notVerified }}
                </div>

            </div>



            <!-- TOTAL -->

            <div class="card">

                <div class="icon-circle">

                    <i class="fa-regular fa-circle-check"></i>

                </div>

                <div class="card-title">
                    Total Ditampilkan
                </div>

                <div class="card-value">
                    {{ $total }}
                </div>

            </div>

        </div>



        <!-- =================================================
             FILTER DATA
        ================================================== -->

        <form
            action="{{ route('admin.dokumen') }}"
            method="GET"
            class="filter-section">


            <div class="filter-header">

                <div>

                    <div class="filter-title">
                        Filter data
                    </div>

                    <div class="filter-subtitle">
                        Menampilkan {{ $total }} mahasiswa
                    </div>

                </div>


                <a
                    href="#"
                    class="reset-filter">

                    Reset Filter

                </a>

            </div>



            <!-- FILTER ROW 1 -->

            <div class="filter-grid">


                <!-- CARI -->

                <div class="input-group">

                    <label>
                        Cari
                    </label>

                    <input
    type="text"
    name="search"
    value="{{ request('search') }}"
    placeholder="Nama, NIM, kampus...">

                </div>



                <!-- TANGGAL MULAI -->

                <div class="input-group">

                    <label>
                        Tanggal mulai
                    </label>

                    <input
    type="date"
    name="tanggal_mulai"
    value="{{ request('tanggal_mulai') }}">

                </div>



                <!-- TANGGAL SELESAI -->

                <div class="input-group">

                    <label>
                        Tanggal selesai
                    </label>

                    <input
    type="date"
    name="tanggal_selesai"
    value="{{ request('tanggal_selesai') }}">

                </div>



                <!-- JURUSAN -->

                <div class="input-group">

                    <label>
                        Jurusan
                    </label>

                    <select name="jurusan_id">

    <option value="">
        Semua
    </option>

    @foreach($jurusan as $j)

        <option
            value="{{ $j->id_jurusan }}"
            {{ request('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>

            {{ $j->nama_jurusan }}

        </option>

    @endforeach

</select>

                </div>



                <!-- UNIVERSITAS -->

                <div class="input-group">

                    <label>
                        Universitas
                    </label>

                    <select name="universitas_id">

    <option value="">
        Semua
    </option>

    @foreach($universitas as $u)

        <option
            value="{{ $u->id_universitas }}"
            {{ request('universitas_id') == $u->id_universitas ? 'selected' : '' }}>

            {{ $u->nama_universitas }}

        </option>

    @endforeach

</select>

                </div>



                <!-- DIVISI -->

                <div class="input-group">

                    <label>
                        Divisi
                    </label>

                    <select name="divisi_id">

    <option value="">
        Semua
    </option>

    @foreach($divisi as $d)

        <option
            value="{{ $d->id_divisi }}"
            {{ request('divisi_id') == $d->id_divisi ? 'selected' : '' }}>

            {{ $d->nama_divisi }}

        </option>

    @endforeach

</select>

                </div>

            </div>



            <!-- FILTER ROW 2 -->

            <div class="filter-row-2">


                <!-- STATUS -->

                <div class="input-group">

                    <label>
                        Status
                    </label>

                    <select name="status_verifikasi">

    <option value="">
        Semua
    </option>

    <option
        value="verified"
        {{ request('status_verifikasi') == 'verified' ? 'selected' : '' }}>
        Verified
    </option>

    <option
        value="not_verified"
        {{ request('status_verifikasi') == 'not_verified' ? 'selected' : '' }}>
        Not Verified
    </option>

</select>

                </div>



                <!-- JENIS KELAMIN -->

                <div class="input-group">

                    <label>
                        Jenis Kelamin
                    </label>

                    <select name="jenis_kelamin">

    <option value="">
        Semua
    </option>

    <option
        value="Laki-laki"
        {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
        Laki-laki
    </option>

    <option
        value="Perempuan"
        {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
        Perempuan
    </option>

</select>

                </div>

            </div>
            <div style="
    margin-top:15px;
    display:flex;
    justify-content:flex-end;
    gap:10px;
">

    <a
        href="{{ route('admin.dokumen') }}"
        class="btn-document btn-view"
        style="height:34px; padding:0 15px;">

        Reset Filter

    </a>

    <button
        type="submit"
        class="btn-document btn-upload"
        style="height:34px; padding:0 15px;">

        <i class="fa-solid fa-filter"></i>

        Terapkan Filter

    </button>

</div>

        </form>



        <!-- =================================================
             TABLE DATA
        ================================================== -->

        <div class="table-card">

            <div class="table-wrapper">

                <table class="table-admin">


                    <thead>

                        <tr>

                            <th>
                                Mahasiswa
                            </th>

                            <th>
                                Universitas
                            </th>

                            <th>
                                Prodi
                            </th>

                            <th>
                                Divisi
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>



                    <tbody>

    @forelse($pengajuan as $p)

        <tr>

            <td>
                <strong>
                    {{ $p->mahasiswa->nama_lengkap }}
                </strong>

                <br>

                <small style="color:#999;">
                    {{ $p->mahasiswa->nim }}
                </small>
            </td>

            <td>
                {{ $p->mahasiswa->universitas->nama_universitas ?? '-' }}
            </td>

            <td>
                {{ $p->mahasiswa->jurusan->nama_jurusan ?? '-' }}
            </td>

            <td>
                {{ $p->divisi->nama_divisi ?? '-' }}
            </td>

            <td>
                {{ $p->mahasiswa->email }}
            </td>

            <td>

                @if($p->surat_balasan)

                    <span class="status status-verified">
                        Verified
                    </span>

                @else

                    <span class="status status-notverified">
                        Not verified
                    </span>

                @endif

            </td>

            <td>

                <div class="action">

                    @if($p->surat_balasan)

                        <a
                            href="{{ route('admin.dokumen.lihat', $p->id_pengajuan) }}"
                            target="_blank"
                            class="btn-document btn-view">

                            <i class="fa-regular fa-eye"></i>

                            Lihat

                        </a>

                        <button
                            type="button"
                            class="btn-document btn-change"
                            onclick="document.getElementById('file-{{ $p->id_pengajuan }}').click()">

                            <i class="fa-solid fa-upload"></i>

                            Ganti

                        </button>

                    @else

                        <button
    type="button"
    class="btn-document btn-upload upload-surat-btn"
    data-action="{{ route('admin.dokumen.upload', $p->id_pengajuan) }}"
    data-name="{{ $p->mahasiswa->nama_lengkap }}"
    data-email="{{ $p->mahasiswa->email }}">
    <i class="fa-solid fa-upload"></i>
    Upload Surat
</button>

                    @endif


                    <!-- FORM UPLOAD -->

                    <!-- <form
                        action="{{ route('admin.dokumen.upload', $p->id_pengajuan) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        style="display:none;">

                        @csrf

                        <input
                            type="file"
                            id="file-{{ $p->id_pengajuan }}"
                            name="surat_balasan"
                            accept=".pdf,.doc,.docx"
                            onchange="this.form.submit()">

                    </form> -->

                </div>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="7" style="text-align:center; padding:30px;">

                Belum ada data mahasiswa.

            </td>

        </tr>

    @endforelse

</tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- =====================================================
     MODAL UPLOAD SURAT BALASAN
===================================================== -->

    <div class="modal-overlay" id="uploadModal">
    <div class="upload-modal">

        <div class="modal-header">
            <h3>Upload Surat Balasan</h3>

            <button type="button"
                    class="modal-close"
                    onclick="closeUploadModal()">
                &times;
            </button>
        </div>

        <form id="uploadForm"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="modal-body">

                <div class="student-info">
                    <div>
                        <strong id="modalStudentName"></strong>
                    </div>

                    <div id="modalStudentEmail"></div>
                </div>

                <div class="upload-area">

                    <label for="suratBalasanInput">
                        Pilih Surat Balasan
                    </label>

                     <input
            type="file"
            id="suratBalasan"
            name="surat_balasan"
            class="modal-file"
            accept=".pdf,application/pdf"
            required>

                    <small>
                        Format PDF, maksimal 4 MB.
                    </small>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    onclick="closeUploadModal()">
                    Batal
                </button>

                <button
                    type="submit"
                    id="submitUpload">
                    Simpan & Kirim
                </button>

            </div>

        </form>

    </div>
</div>


    <!-- =====================================================
         JAVASCRIPT SIDEBAR
    ====================================================== -->

    <script>

    /* =====================================================
       SIDEBAR
    ===================================================== */

    document.addEventListener('DOMContentLoaded', function () {

        const menuBtn = document.getElementById('menuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (menuBtn && sidebar && overlay) {

            /* BUKA / TUTUP SIDEBAR */
            menuBtn.addEventListener('click', function () {

                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');

            });


            /* KLIK AREA LUAR SIDEBAR */
            overlay.addEventListener('click', function () {

                sidebar.classList.remove('active');
                overlay.classList.remove('active');

            });

        }

    });



    /* =====================================================
       MODAL UPLOAD SURAT BALASAN
    ===================================================== */

    /* =====================================================
   MODAL UPLOAD SURAT BALASAN
===================================================== */

const uploadModal = document.getElementById('uploadModal');
const uploadButtons = document.querySelectorAll('.upload-surat-btn');

const modalStudentName = document.getElementById('modalStudentName');
const modalStudentEmail = document.getElementById('modalStudentEmail');

const suratBalasan = document.getElementById('suratBalasan');
const uploadForm = document.getElementById('uploadForm');
const submitUpload = document.getElementById('submitUpload');


/* =====================================================
   BUKA MODAL
===================================================== */

uploadButtons.forEach(function (button) {

    button.addEventListener('click', function () {

        const action = this.getAttribute('data-action');
        const nama = this.getAttribute('data-name');
        const email = this.getAttribute('data-email');

        // Set action form
        uploadForm.action = action;

        // Set data mahasiswa
        modalStudentName.textContent = nama || '';
        modalStudentEmail.textContent = email || '';

        // Reset file
        suratBalasan.value = '';

        // Reset tombol
        submitUpload.disabled = false;
        submitUpload.innerHTML =
            '<i class="fa-regular fa-paper-plane"></i> Simpan & Kirim';

        // Buka modal
        uploadModal.classList.add('active');
    });

});


/* =====================================================
   FUNGSI TUTUP MODAL
===================================================== */

function closeUploadModal() {

    uploadModal.classList.remove('active');

    if (suratBalasan) {
        suratBalasan.value = '';
    }
}


/* =====================================================
   KLIK BACKGROUND
===================================================== */

uploadModal.addEventListener('click', function (event) {

    if (event.target === uploadModal) {
        closeUploadModal();
    }

});


/* =====================================================
   ESC
===================================================== */

document.addEventListener('keydown', function (event) {

    if (
        event.key === 'Escape' &&
        uploadModal.classList.contains('active')
    ) {
        closeUploadModal();
    }

});


/* =====================================================
   SUBMIT UPLOAD
===================================================== */

uploadForm.addEventListener('submit', function (event) {

    const file = suratBalasan.files[0];

    /* FILE BELUM DIPILIH */

    if (!file) {

        event.preventDefault();

        alert(
            'Silakan pilih berkas surat balasan terlebih dahulu.'
        );

        return;
    }


    /* HARUS PDF */

    if (
        file.type !== 'application/pdf' &&
        !file.name.toLowerCase().endsWith('.pdf')
    ) {

        event.preventDefault();

        alert(
            'Berkas surat balasan harus berupa PDF.'
        );

        suratBalasan.value = '';

        return;
    }


    /* MAKSIMAL 4 MB */

    const maxSize = 4 * 1024 * 1024;

    if (file.size > maxSize) {

        event.preventDefault();

        alert(
            'Ukuran berkas maksimal 4 MB.'
        );

        suratBalasan.value = '';

        return;
    }


    /* FILE VALID */

    submitUpload.disabled = true;

    submitUpload.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';

    // JANGAN preventDefault()
    // biarkan form dikirim ke Laravel
});


    /* =====================================================
       TOMBOL LIHAT SURAT
    ===================================================== */

    const lihatButtons =
        document.querySelectorAll('.btn-lihat-surat');


    lihatButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const file =
                this.getAttribute('data-file');


            /* FILE BELUM ADA */

            if (!file) {

                alert(
                    'Dokumen surat belum tersedia.'
                );

                return;

            }


            /* BUKA FILE */

            window.open(
                file,
                '_blank'
            );

        });

    });

</script>


</body>

</html>