<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Sertifikat - Monitoring Mahasiswa PKL
    </title>


    <!-- =====================================================
         FONT
    ====================================================== -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">


    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/admin/sertifikat.css') }}">
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
                Kelola dan pantau data mahasiswa PKL
                dengan mudah dan efisien.
            </p>

        </div>

    </div>


    <div class="header-right">

        <button
            type="button"
            class="menu-btn"
            id="menuBtn"
            title="Menu">

            <i class="fa-solid fa-bars"></i>

        </button>


        <form
            action="{{ route('logout') }}"
            method="POST"
            style="margin:0;">

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


    <a
        href="{{ route('admin.dashboard') }}"
        class="sidebar-menu">

        <i class="fa-regular fa-chart-bar"></i>

        <span>
            Dashboard
        </span>

    </a>


    <a
        href="{{ route('admin.data') }}"
        class="sidebar-menu">

        <i class="fa-regular fa-user"></i>

        <span>
            Data Mahasiswa
        </span>

    </a>


    <a
        href="{{ route('admin.dokumen') }}"
        class="sidebar-menu">

        <i class="fa-regular fa-file-lines"></i>

        <span>
            Dokumen Verification
        </span>

    </a>


    <a
        href="{{ route('admin.sertifikat') }}"
        class="sidebar-menu active">

        <i class="fa-regular fa-file"></i>

        <span>
            Sertifikat
        </span>

    </a>

</div>


<!-- =====================================================
     MAIN
====================================================== -->

<main class="main-content">

    <section class="data-card">


        <!-- =================================================
             FLASH MESSAGE
        ================================================== -->

        @if(session('success'))

            <div class="alert alert-success">

                <i class="fa-solid fa-circle-check"></i>

                {{ session('success') }}

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-error">

                <i class="fa-solid fa-circle-exclamation"></i>

                {{ session('error') }}

            </div>

        @endif


        <!-- =================================================
             VALIDATION ERROR
        ================================================== -->

        @if($errors->any())

            <div class="alert alert-error">

                @foreach($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif


        <!-- =================================================
             CARD HEADER
        ================================================== -->

        <div class="card-header">

            <div>

                <h1>
                    Filter data
                </h1>

                <p>

                    Menampilkan
                    {{ $mahasiswa->count() }}
                    mahasiswa

                </p>

            </div>

        </div>


        <!-- =================================================
             FILTER FORM
        ================================================== -->

        <form
            class="filter-form"
            method="GET"
            action="{{ route('admin.sertifikat') }}">


            <div class="filter-grid">


                <!-- CARI -->

                <div class="filter-item">

                    <label for="search">
                        Cari
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        placeholder="Nama, NIM, Email..."
                        value="{{ request('search') }}">

                </div>


                <!-- TANGGAL MULAI -->

                <div class="filter-item">

                    <label for="tanggal_mulai">
                        Tanggal mulai
                    </label>

                    <input
                        type="date"
                        id="tanggal_mulai"
                        name="tanggal_mulai"
                        value="{{ request('tanggal_mulai') }}">

                </div>


                <!-- TANGGAL SELESAI -->

                <div class="filter-item">

                    <label for="tanggal_selesai">
                        Tanggal selesai
                    </label>

                    <input
                        type="date"
                        id="tanggal_selesai"
                        name="tanggal_selesai"
                        value="{{ request('tanggal_selesai') }}">

                </div>


                <!-- JURUSAN -->

                <div class="filter-item">

                    <label for="jurusan">
                        Jurusan
                    </label>

                    <select
                        id="jurusan"
                        name="jurusan">

                        <option value="">
                            Semua
                        </option>

                        @foreach($jurusan as $itemJurusan)

                            <option
                                value="{{ $itemJurusan->id_jurusan }}"
                                @selected(request('jurusan') == $itemJurusan->id_jurusan)>

                                {{ $itemJurusan->nama_jurusan }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <!-- UNIVERSITAS -->

                <div class="filter-item">

                    <label for="universitas">
                        Universitas
                    </label>

                    <select
                        id="universitas"
                        name="universitas">

                        <option value="">
                            Semua
                        </option>

                        @foreach($universitas as $itemUniversitas)

                            <option
                                value="{{ $itemUniversitas->id_universitas }}"
                                @selected(request('universitas') == $itemUniversitas->id_universitas)>

                                {{ $itemUniversitas->nama_universitas }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <!-- DIVISI -->

                <div class="filter-item">

                    <label for="divisi">
                        Divisi
                    </label>

                    <select
                        id="divisi"
                        name="divisi">

                        <option value="">
                            Semua
                        </option>

                        @foreach($divisi as $itemDivisi)

                            <option
                                value="{{ $itemDivisi->id_divisi }}"
                                @selected(request('divisi') == $itemDivisi->id_divisi)>

                                {{ $itemDivisi->nama_divisi }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            <!-- ================= ROW 2 ================= -->

            <div class="filter-row-two">


                <!-- STATUS -->

                <div class="filter-item">

                    <label for="status">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status">

                        <option value="">
                            Semua
                        </option>

                        <option
                            value="Akan Datang"
                            @selected(request('status') == 'Akan Datang')>

                            Akan Datang

                        </option>

                        <option
                            value="Aktif"
                            @selected(request('status') == 'Aktif')>

                            Aktif

                        </option>

                        <option
                            value="Selesai"
                            @selected(request('status') == 'Selesai')>

                            Selesai

                        </option>

                    </select>

                </div>


                <!-- JENIS KELAMIN -->

                <div class="filter-item">

                    <label for="jenis_kelamin">
                        Jenis Kelamin
                    </label>

                    <select
                        id="jenis_kelamin"
                        name="jenis_kelamin">

                        <option value="">
                            Semua
                        </option>

                        <option
                            value="L"
                            @selected(request('jenis_kelamin') == 'L')>

                            Laki-laki

                        </option>

                        <option
                            value="P"
                            @selected(request('jenis_kelamin') == 'P')>

                            Perempuan

                        </option>

                    </select>

                </div>

            </div>


            <!-- ================= ACTION FILTER ================= -->

            <div class="filter-actions">

                <button
                    type="submit"
                    class="btn-filter">

                    <i class="fa-solid fa-filter"></i>

                    Terapkan Filter

                </button>


                <a
                    href="{{ route('admin.sertifikat') }}"
                    class="btn-reset-filter">

                    <i class="fa-solid fa-rotate-left"></i>

                    Reset Filter

                </a>

            </div>

        </form>


        <!-- =================================================
             TABLE
        ================================================== -->

        <div class="table-wrapper">

            <table class="student-table">

                <thead>

                    <tr>

                        <th>
                            Mahasiswa
                        </th>

                        <th>
                            Divisi
                        </th>

                        <th>
                            Status Magang
                        </th>

                        <th>
                            Laporan
                        </th>

                        <th>
                            ID Card
                        </th>

                        <th>
                            Sertifikat
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>


                    @forelse($mahasiswa as $item)

                        @php

    /*
    |--------------------------------------------------------------------------
    | PENGAJUAN MAGANG
    |--------------------------------------------------------------------------
    */

    $pengajuan = $item->pengajuanMagang;

    if ($pengajuan instanceof \Illuminate\Support\Collection) {

        $pengajuan = $pengajuan
            ->sortByDesc('id_pengajuan')
            ->first();

    }


    /*
    |--------------------------------------------------------------------------
    | SERTIFIKAT
    |--------------------------------------------------------------------------
    */

    $sertifikat = $item->sertifikat;

    if ($sertifikat instanceof \Illuminate\Support\Collection) {

        $sertifikat = $sertifikat
            ->sortByDesc('id_sertifikat')
            ->first();

    }


    /*
    |--------------------------------------------------------------------------
    | STATUS TAMPILAN
    |--------------------------------------------------------------------------
    */

    $statusTampilan = match ($pengajuan?->status) {

        'Akan Magang' =>
            'Akan Datang',

        'Sedang Magang' =>
            'Aktif',

        'Selesai Magang' =>
            'Selesai',

        default =>
            $pengajuan?->status ?? '-'

    };

@endphp


                        <tr>


                            <!-- =================================
                                 MAHASISWA
                            ================================== -->

                            <td>

                                <div class="student-name">

                                    <span class="name">

                                        {{ $item->nama_lengkap ?? '-' }}

                                    </span>

                                    <span class="year">

                                        {{ $item->nim ?? '-' }}

                                    </span>

                                </div>

                            </td>


                            <!-- =================================
                                 DIVISI
                            ================================== -->

                            <td>

                                {{ $pengajuan?->divisi?->nama_divisi ?? '-' }}

                            </td>


                            <!-- =================================
                                 STATUS
                            ================================== -->

                            <td>

                                <span class="status">

                                    {{ $statusTampilan }}

                                </span>

                            </td>


                            <!-- =================================
                                 LAPORAN
                            ================================== -->

                            <td>

    <div class="actions">

        @if($item->file_laporan)

    <a
        href="{{ asset($item->file_laporan) }}"
        target="_blank"
        rel="noopener noreferrer"
        class="btn btn-view">

        <i class="fa-regular fa-file-lines"></i>

        Lihat

    </a>

@endif


        <button
            type="button"
            class="btn btn-upload btn-upload-laporan"
            data-mahasiswa-id="{{ $item->id_mhs }}"
            data-nama="{{ $item->nama_lengkap ?? '-' }}"
            data-email="{{ $item->email ?? '-' }}"
            data-upload-url="{{ route('admin.laporan.upload', $item->id_mhs) }}">

            <i class="fa-solid fa-arrow-up"></i>

            @if($item->file_laporan)
                Ubah
            @else
                Upload
            @endif

        </button>

    </div>

</td>


                            <!-- =================================
                                 ID CARD
                            ================================== -->

                            <td>

    @if($item->id_card_verified)

        <form
            action="{{ route('admin.sertifikat.verify-id-card', $item->id_mhs) }}"
            method="POST"
            style="display:inline;">

            @csrf

            <button
                type="submit"
                class="id-card-circle active"
                title="ID Card sudah diverifikasi">

            </button>

        </form>

    @else

        <form
            action="{{ route('admin.sertifikat.verify-id-card', $item->id_mhs) }}"
            method="POST"
            style="display:inline;">

            @csrf

            <button
                type="submit"
                class="id-card-circle"
                title="Verifikasi ID Card">

            </button>

        </form>

    @endif

</td>


                            <!-- =================================
                                 SERTIFIKAT
                            ================================== -->

                            <td>

    @if(
    $sertifikat &&
    $sertifikat->status === 'Diterbitkan'
)

    <span class="certificate issued">
        Diterbitkan
    </span>

@elseif($item->id_card_verified)

    <span class="certificate issued">
        Sudah Memenuhi
    </span>

@else

    <span class="certificate pending">
        Belum Memenuhi
    </span>

@endif

</td>


                            <!-- =================================
                                 AKSI
                            ================================== -->

                            <td>

                                <div class="actions">


                                    @if(
                                        $sertifikat &&
                                        $sertifikat->file_sertifikat &&
                                        file_exists(public_path($sertifikat->file_sertifikat))
                                    )

                                        <a
    href="{{ asset($sertifikat->file_sertifikat) }}"
    target="_blank"
    rel="noopener noreferrer"
    class="btn btn-view">

    <i class="fa-regular fa-file-lines"></i>

    Lihat

</a>

                                    @endif


                                    <!--
    PENTING:
    Route dibuat di HTML menggunakan
    data-upload-url.

    Jadi JavaScript tidak lagi
    membuat URL secara langsung.
-->

                                    @if(
    $item->id_card_verified
)

    <button
        type="button"
        class="btn btn-upload btn-open-certificate id-verified"
        data-mahasiswa-id="{{ $item->id_mhs }}"
        data-upload-url="{{ route('admin.sertifikat.upload', $item->id_mhs) }}">

        <i class="fa-solid fa-arrow-up"></i>

        @if(
            $sertifikat &&
            $sertifikat->file_sertifikat
        )

            Ubah Sertifikat

        @else

            Upload Sertifikat

        @endif

    </button>

@else

    <button
        type="button"
        class="btn btn-upload"
        disabled
        title="Verifikasi ID Card terlebih dahulu">

        <i class="fa-solid fa-lock"></i>

        Upload Sertifikat

    </button>

@endif

<a
    href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $item->no_telepon)) }}?text={{ urlencode(
        "Halo {$item->nama_lengkap},\n\n" .
        "Kami informasikan bahwa sertifikat PKL Anda sudah diterbitkan dan telah dikirim melalui email perusahaan.\n\n" .
        "Mohon segera membuka email Anda dan memeriksa pesan dari perusahaan terkait sertifikat PKL.\n\n" .
        "Jika belum menerima email, silakan cek folder Spam/Junk.\n\n" .
        "Terima kasih."
    ) }}"
    target="_blank"
    class="btn-document btn-wa"
>
    <i class="fa-brands fa-whatsapp"></i>
    Kirim WA
</a>

                                </div>

                            </td>

                        </tr>



                    @empty

                        <tr>

                            <td
                                colspan="7"
                                style="
                                    text-align:center;
                                    padding:35px;
                                    color:#888;
                                ">

                                <i
                                    class="fa-regular fa-folder-open"
                                    style="
                                        font-size:25px;
                                        display:block;
                                        margin-bottom:8px;
                                    ">
                                </i>

                                Tidak ada data mahasiswa
                                yang ditemukan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</main>


<!-- =====================================================
     MODAL UPLOAD LAPORAN
====================================================== -->

<div
    class="laporan-modal-overlay"
    id="laporanModal">

    <div class="laporan-modal">

    <form
        id="laporanForm"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        <div class="laporan-modal-title">

            Tambah laporan mahasiswa

        </div>


        <div class="laporan-modal-description">

            Laporan diunggah untuk

            <strong id="laporanNama">
                mahasiswa
            </strong>.

            Pastikan laporan yang diunggah merupakan
            laporan PKL yang sesuai.

        </div>


        <label
            for="laporanFile"
            class="laporan-modal-label">

            Berkas laporan (PDF)

        </label>


        <input
            type="file"
            id="laporanFile"
            name="laporan"
            class="laporan-file"
            accept=".pdf,application/pdf"
            required>


        <div class="laporan-email">

            Email mahasiswa:

            <span id="laporanEmail">
                -
            </span>

        </div>


        <div class="laporan-modal-actions">

            <button
                type="button"
                class="btn-laporan-cancel"
                id="cancelLaporan">

                Batal

            </button>


            <button
                type="submit"
                class="btn-laporan-submit"
                id="submitLaporan">

                <i class="fa-regular fa-paper-plane"></i>

                Simpan & kirim

            </button>

        </div>

    </form>

</div>

</div>


<!-- =====================================================
     MODAL UPLOAD SERTIFIKAT
====================================================== -->

<div
    class="certificate-modal-overlay"
    id="certificateModal">

    <div class="certificate-modal">

        <form
            id="certificateForm"
            method="POST"
            enctype="multipart/form-data">

            @csrf


            <h3>
                Unggah sertifikat digital
            </h3>


            <p class="modal-description">

                Pastikan seluruh persyaratan
                sudah terpenuhi.

            </p>


            <div class="modal-file-group">

                <label for="certificateFile">

                    Berkas sertifikat
                    (PDF/Gambar)

                </label>


                <input
                    type="file"
                    id="certificateFile"
                    name="certificate"
                    accept=".pdf,.jpg,.jpeg,.png"
                    required>

            </div>


            <div class="modal-actions">

                <button
                    type="button"
                    class="modal-btn modal-cancel"
                    id="closeCertificateButton">

                    Batal

                </button>


                <button
                    type="submit"
                    class="modal-btn modal-save">

                    <i class="fa-regular fa-paper-plane"></i>

                    Simpan & kirim

                </button>

            </div>

        </form>

    </div>

</div>


<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =====================================================
       SIDEBAR
    ====================================================== */

    const menuBtn =
        document.getElementById('menuBtn');

    const sidebar =
        document.getElementById('sidebar');

    const sidebarOverlay =
        document.getElementById('sidebarOverlay');


    if (menuBtn && sidebar && sidebarOverlay) {

        menuBtn.addEventListener('click', function () {

            sidebar.classList.toggle('active');

            sidebarOverlay.classList.toggle('active');

        });


        sidebarOverlay.addEventListener('click', function () {

            sidebar.classList.remove('active');

            sidebarOverlay.classList.remove('active');

        });

    }


    /* =====================================================
       ID CARD
    ====================================================== */

    const idCardButtons =
        document.querySelectorAll('.id-card-circle');


    idCardButtons.forEach(function (button) {

    button.addEventListener('click', function () {

        this.classList.toggle('active');


        const row =
            this.closest('tr');

        if (!row) {

            return;

        }


        const certificateBadge =
            row.querySelector('.certificate');

        const uploadSertifikatBtn =
            row.querySelector('.btn-open-certificate');


        if (this.classList.contains('active')) {

            if (certificateBadge) {

                certificateBadge.classList.add(
                    'id-verified'
                );

            }


            if (uploadSertifikatBtn) {

                uploadSertifikatBtn.classList.add(
                    'id-verified'
                );

            }

        } else {

            if (certificateBadge) {

                certificateBadge.classList.remove(
                    'id-verified'
                );

            }


            if (uploadSertifikatBtn) {

                uploadSertifikatBtn.classList.remove(
                    'id-verified'
                );

            }

        }

    });

});


    /* =====================================================
       MODAL SERTIFIKAT
    ====================================================== */

    const certificateModal =
        document.getElementById('certificateModal');

    const certificateForm =
        document.getElementById('certificateForm');

    const certificateFile =
        document.getElementById('certificateFile');

    const closeCertificateButton =
        document.getElementById('closeCertificateButton');


    const certificateButtons =
        document.querySelectorAll('.btn-open-certificate');


    /*
     * Buka modal sertifikat.
     */

    certificateButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const uploadUrl =
                this.dataset.uploadUrl;


            const mahasiswaId =
                this.dataset.mahasiswaId;


            if (!uploadUrl) {

                alert(
                    'URL upload sertifikat tidak ditemukan.'
                );

                return;
            }


            if (!mahasiswaId) {

                alert(
                    'ID mahasiswa tidak ditemukan.'
                );

                return;
            }


            /*
             * URL sudah dibuat oleh Blade
             * melalui data-upload-url.
             *
             * JavaScript hanya mengambilnya.
             */

            certificateForm.action =
                uploadUrl;


            certificateFile.value = '';


            certificateModal.classList.add(
                'active'
            );


            setTimeout(function () {

                certificateFile.focus();

            }, 100);

        });

    });


    /*
     * Tutup modal sertifikat.
     */

    function closeCertificateModal() {

        certificateModal.classList.remove(
            'active'
        );

        certificateFile.value = '';

        certificateForm.action = '';

    }


    if (closeCertificateButton) {

        closeCertificateButton.addEventListener(
            'click',
            function () {

                closeCertificateModal();

            }
        );

    }


    /*
     * Klik area luar modal.
     */

    certificateModal.addEventListener(
        'click',
        function (event) {

            if (
                event.target ===
                certificateModal
            ) {

                closeCertificateModal();

            }

        }
    );


    /* =====================================================
       VALIDASI SERTIFIKAT
    ====================================================== */

    certificateForm.addEventListener(
        'submit',
        function (event) {

            const file =
                certificateFile.files[0];


            /*
             * Pastikan file dipilih.
             */

            if (!file) {

                event.preventDefault();

                alert(
                    'Silakan pilih berkas sertifikat terlebih dahulu.'
                );

                return;

            }


            /*
             * Validasi ekstensi.
             */

            const fileName =
                file.name || '';


            const extension =
                fileName
                    .split('.')
                    .pop()
                    .toLowerCase();


            const allowedExtensions = [
                'pdf',
                'jpg',
                'jpeg',
                'png'
            ];


            if (
                !allowedExtensions.includes(
                    extension
                )
            ) {

                event.preventDefault();

                alert(
                    'Sertifikat harus berupa PDF, JPG, JPEG, atau PNG.'
                );

                certificateFile.value = '';

                return;

            }


            /*
             * Validasi ukuran.
             *
             * 5 MB = 5 * 1024 * 1024 bytes
             */

            const maxSize =
                5 * 1024 * 1024;


            if (file.size > maxSize) {

                event.preventDefault();

                alert(
                    'Ukuran sertifikat maksimal 5 MB.'
                );

                certificateFile.value = '';

                return;

            }


            /*
             * Pastikan form memiliki action.
             */

            if (!certificateForm.action) {

                event.preventDefault();

                alert(
                    'URL upload sertifikat tidak ditemukan.'
                );

                return;

            }

        }
    );


    /* =====================================================
       MODAL LAPORAN
    ====================================================== */
    const laporanForm =
    document.getElementById('laporanForm');

    const laporanModal =
        document.getElementById('laporanModal');
        

    const laporanButtons =
        document.querySelectorAll(
            '.btn-upload-laporan'
        );

    const cancelLaporan =
        document.getElementById(
            'cancelLaporan'
        );

    const submitLaporan =
        document.getElementById(
            'submitLaporan'
        );

    const laporanNama =
        document.getElementById(
            'laporanNama'
        );

    const laporanEmail =
        document.getElementById(
            'laporanEmail'
        );

    const laporanFile =
        document.getElementById(
            'laporanFile'
        );


    /*
     * Buka modal laporan.
     */

    laporanButtons.forEach(
    function (button) {

        button.addEventListener(
            'click',
            function () {

                const uploadUrl =
                    this.dataset.uploadUrl;


                const nama =
                    this.dataset.nama ||
                    'mahasiswa';


                const email =
                    this.dataset.email ||
                    '-';


                if (!uploadUrl) {

                    alert(
                        'URL upload laporan tidak ditemukan.'
                    );

                    return;

                }


                laporanForm.action =
                    uploadUrl;


                laporanNama.textContent =
                    nama;


                laporanEmail.textContent =
                    email;


                laporanFile.value = '';


                laporanModal.classList.add(
                    'active'
                );

            }
        );

    }
);


    /*
     * Tutup modal laporan.
     */

    function closeLaporanModal() {

        laporanModal.classList.remove(
            'active'
        );

        laporanFile.value = '';
        laporanForm.action = '';
    }


    cancelLaporan.addEventListener(
        'click',
        function () {

            closeLaporanModal();

        }
    );


    laporanModal.addEventListener(
        'click',
        function (event) {

            if (
                event.target ===
                laporanModal
            ) {

                closeLaporanModal();

            }

        }
    );


    /*
     * Submit laporan.
     *
     * Saat ini bagian laporan masih UI karena
     * route/controller upload laporan belum diberikan.
     */

    submitLaporan.addEventListener(
        'click',
        function () {

            const file =
                laporanFile.files[0];


            if (!file) {

                alert(
                    'Silakan pilih berkas laporan terlebih dahulu.'
                );

                return;

            }


            const extension =
                file.name
                    .split('.')
                    .pop()
                    .toLowerCase();


            if (extension !== 'pdf') {

                alert(
                    'Berkas laporan harus berupa PDF.'
                );

                laporanFile.value = '';

                return;

            }


            if (
                file.size >
                5 * 1024 * 1024
            ) {

                alert(
                    'Ukuran laporan maksimal 5 MB.'
                );

                laporanFile.value = '';

                return;

            };

        }
    );

/* =====================================================
   KIRIM WHATSAPP
===================================================== */

const waButtons =
    document.querySelectorAll('.btn-kirim-wa');

waButtons.forEach(function (button) {

    button.addEventListener('click', function () {

        const nama =
            this.dataset.nama || '';

        const nomor =
            this.dataset.telepon || '';


        /* -----------------------------------------
           CEK NOMOR
        ----------------------------------------- */

        if (!nomor) {

            alert(
                'Nomor WhatsApp mahasiswa belum tersedia.'
            );

            return;

        }


        /* -----------------------------------------
           BERSIHKAN NOMOR
        ----------------------------------------- */

        let nomorWa =
            nomor.replace(/\D/g, '');


        /*
         * 08xxxxxxxxxx
         * menjadi
         * 628xxxxxxxxxx
         */

        if (nomorWa.startsWith('0')) {

            nomorWa =
                '62' + nomorWa.substring(1);

        }


        /*
         * Kalau nomor sudah menggunakan 62,
         * tidak perlu diubah.
         */

        const pesan =
            `Halo ${nama},

Kami informasikan bahwa sertifikat PKL Anda sudah diterbitkan dan telah dikirim melalui email perusahaan.

Mohon segera membuka email Anda dan memeriksa pesan dari perusahaan terkait sertifikat PKL.

Jika belum menerima email, silakan cek folder Spam/Junk.

Terima kasih.`;


        const url =
            'https://wa.me/' +
            nomorWa +
            '?text=' +
            encodeURIComponent(pesan);


        /*
         * Buka WhatsApp
         */

        window.open(
            url,
            '_blank'
        );

    });

});

    /* =====================================================
       ESCAPE
    ====================================================== */

    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key !== 'Escape') {

                return;

            }


            if (
                certificateModal.classList.contains(
                    'active'
                )
            ) {

                closeCertificateModal();

            }


            if (
                laporanModal.classList.contains(
                    'active'
                )
            ) {

                closeLaporanModal();

            }


            if (
                sidebar &&
                sidebar.classList.contains(
                    'active'
                )
            ) {

                sidebar.classList.remove(
                    'active'
                );


                sidebarOverlay.classList.remove(
                    'active'
                );

            }

        }
    );

});

</script>

</body>

</html>