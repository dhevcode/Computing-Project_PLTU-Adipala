<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pengajuan Magang & PKL - PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/daftar-magang.css') }}">
</head>
<body>

    <!-- NAVBAR HEADER -->
    <header class="reg-navbar">
        <div class="reg-nav-container">
            <a href="{{ route('login') }}" class="reg-brand">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN" class="reg-logo-img">
                <div class="reg-brand-text">
                    <h1>Monitoring Magang PLTU</h1>
                    <p>PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala</p>
                </div>
            </a>
            <div class="reg-nav-action">
                <a href="{{ route('login') }}" class="btn-login-link">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Sudah Punya Akun? Masuk</span>
                </a>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="reg-hero">
        <div class="reg-hero-badge">
            <i class="fa-solid fa-graduation-cap"></i> Penerimaan Magang & PKL Periode Aktif
        </div>
        <h2>Formulir Pendaftaran Magang Baru</h2>
        <p>
            Lengkapi data diri, latar belakang akademik, serta unggah berkas persyaratan resmi (CV, Proposal Magang, dan Surat Pengantar Kampus) untuk pengajuan praktik kerja di PLTU Adipala.
        </p>
    </section>

    <!-- MAIN FORM -->
    <main class="reg-main">
        <div class="reg-card">
            
            <form action="{{ route('magang.store') }}" method="POST" enctype="multipart/form-data" id="formDaftarMagang">
                @csrf

                <!-- SEKSI 1: DATA IDENTITAS DIRI -->
                <div class="form-section">
                    <div class="section-head">
                        <div class="section-icon">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <div class="section-head-text">
                            <h3>1. Identitas Calon Peserta</h3>
                            <p>Isi data diri Anda secara lengkap dan akurat sesuai data resmi.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nama_lengkap" class="form-label">
                                Nama Lengkap <span class="req-star">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nama_lengkap" 
                                name="nama_lengkap" 
                                class="form-control" 
                                value="{{ old('nama_lengkap') }}" 
                                placeholder="Contoh: Muhammad Farhan" 
                                required
                            >
                            @error('nama_lengkap')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nim" class="form-label">
                                NIM / NISN / No. Induk <span class="req-star">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nim" 
                                name="nim" 
                                class="form-control" 
                                value="{{ old('nim') }}" 
                                placeholder="Contoh: 2311102045" 
                                required
                            >
                            <span class="input-hint">NIM ini juga akan digunakan sebagai username akun Anda.</span>
                            @error('nim')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jenis_kelamin" class="form-label">
                                Jenis Kelamin <span class="req-star">*</span>
                            </label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                Alamat Email Aktif <span class="req-star">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control" 
                                value="{{ old('email') }}" 
                                placeholder="contoh: farhan@gmail.com" 
                                required
                            >
                            <span class="input-hint">Digunakan untuk login portal dan korespondensi.</span>
                            @error('email')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="no_telepon" class="form-label">
                                No. Telepon / WhatsApp <span class="req-star">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="no_telepon" 
                                name="no_telepon" 
                                class="form-control" 
                                value="{{ old('no_telepon') }}" 
                                placeholder="Contoh: 081234567890" 
                                required
                            >
                            @error('no_telepon')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="emergency_contact_name" class="form-label">
                                Nama Kontak Darurat (Orang Tua / Wali)
                            </label>
                            <input 
                                type="text" 
                                id="emergency_contact_name" 
                                name="emergency_contact_name" 
                                class="form-control" 
                                value="{{ old('emergency_contact_name') }}" 
                                placeholder="Contoh: Joko Widodo (Ayah)"
                            >
                            @error('emergency_contact_name')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-span-2">
                            <label for="emergency_contact_phone" class="form-label">
                                Nomor HP Kontak Darurat
                            </label>
                            <input 
                                type="text" 
                                id="emergency_contact_phone" 
                                name="emergency_contact_phone" 
                                class="form-control" 
                                value="{{ old('emergency_contact_phone') }}" 
                                placeholder="Contoh: 081987654321"
                            >
                            @error('emergency_contact_phone')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEKSI 2: INFORMASI AKADEMIK & PENEMPATAN -->
                <div class="form-section">
                    <div class="section-head">
                        <div class="section-icon">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <div class="section-head-text">
                            <h3>2. Asal Institusi & Rencana Penempatan</h3>
                            <p>Tentukan asal perguruan tinggi / sekolah, program studi, dan divisi yang dituju.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <!-- Universitas -->
                        <div class="form-group">
                            <label for="select_universitas" class="form-label">
                                Asal Kampus / Sekolah <span class="req-star">*</span>
                            </label>
                            <select name="universitas_id" id="select_universitas" class="form-select" required>
                                <option value="" disabled {{ old('universitas_id') ? '' : 'selected' }}>-- Pilih Asal Kampus / Sekolah --</option>
                                @foreach($universitas as $u)
                                    <option value="{{ $u->id_universitas }}" {{ old('universitas_id') == $u->id_universitas ? 'selected' : '' }}>
                                        {{ $u->nama_universitas }}
                                    </option>
                                @endforeach
                                <option value="new" {{ old('universitas_id') == 'new' ? 'selected' : '' }}>+ Tambah Kampus / Sekolah Baru...</option>
                            </select>
                            @error('universitas_id')
                                <p class="error-text">{{ $message }}</p>
                            @enderror

                            <div id="field_universitas_baru" class="box-new-input {{ old('universitas_id') == 'new' ? '' : 'd-none' }}">
                                <label for="nama_universitas_baru" class="form-label">Tulis Nama Kampus / Sekolah Baru:</label>
                                <input 
                                    type="text" 
                                    name="nama_universitas_baru" 
                                    id="nama_universitas_baru" 
                                    class="form-control" 
                                    value="{{ old('nama_universitas_baru') }}" 
                                    placeholder="Misal: Politeknik Negeri Semarang"
                                >
                                @error('nama_universitas_baru')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Jurusan -->
                        <div class="form-group">
                            <label for="select_jurusan" class="form-label">
                                Jurusan / Program Studi <span class="req-star">*</span>
                            </label>
                            <select name="jurusan_id" id="select_jurusan" class="form-select" required>
                                <option value="" disabled {{ old('jurusan_id') ? '' : 'selected' }}>-- Pilih Program Studi / Jurusan --</option>
                                @foreach($jurusan as $j)
                                    <option value="{{ $j->id_jurusan }}" {{ old('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                                <option value="new" {{ old('jurusan_id') == 'new' ? 'selected' : '' }}>+ Tambah Jurusan / Prodi Baru...</option>
                            </select>
                            @error('jurusan_id')
                                <p class="error-text">{{ $message }}</p>
                            @enderror

                            <div id="field_jurusan_baru" class="box-new-input {{ old('jurusan_id') == 'new' ? '' : 'd-none' }}">
                                <label for="nama_jurusan_baru" class="form-label">Tulis Nama Jurusan Baru:</label>
                                <input 
                                    type="text" 
                                    name="nama_jurusan_baru" 
                                    id="nama_jurusan_baru" 
                                    class="form-control" 
                                    value="{{ old('nama_jurusan_baru') }}" 
                                    placeholder="Misal: D4 Teknik Mesin Pembangkit"
                                >
                                @error('nama_jurusan_baru')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Divisi Dituju -->
                        <div class="form-group col-span-2">
                            <label for="select_divisi" class="form-label">
                                Divisi Penempatan yang Dituju <span class="req-star">*</span>
                            </label>
                            <select name="divisi_id" id="select_divisi" class="form-select" required>
                                <option value="" disabled {{ old('divisi_id') ? '' : 'selected' }}>-- Pilih Divisi Tujuan di PLTU Adipala --</option>
                                @foreach($divisi as $d)
                                    <option value="{{ $d->id_divisi }}" {{ old('divisi_id') == $d->id_divisi ? 'selected' : '' }}>
                                        {{ $d->nama_divisi }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-hint">Pilih divisi yang paling relevan dengan bidang keahlian atau topik penelitian Anda.</span>
                            @error('divisi_id')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="form-group">
                            <label for="tanggal_mulai" class="form-label">
                                Rencana Mulai Magang <span class="req-star">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="tanggal_mulai" 
                                name="tanggal_mulai" 
                                class="form-control" 
                                value="{{ old('tanggal_mulai') }}" 
                                required
                            >
                            @error('tanggal_mulai')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="form-group">
                            <label for="tanggal_selesai" class="form-label">
                                Rencana Selesai Magang <span class="req-star">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="tanggal_selesai" 
                                name="tanggal_selesai" 
                                class="form-control" 
                                value="{{ old('tanggal_selesai') }}" 
                                required
                            >
                            @error('tanggal_selesai')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEKSI 3: DOKUMEN PERSYARATAN -->
                <div class="form-section">
                    <div class="section-head">
                        <div class="section-icon">
                            <i class="fa-solid fa-file-arrow-up"></i>
                        </div>
                        <div class="section-head-text">
                            <h3>3. Dokumen Persyaratan Magang</h3>
                            <p>Unggah file CV, Proposal Magang, dan Surat Pengantar resmi dari kampus/sekolah (Maks. 5MB per file).</p>
                        </div>
                    </div>

                    <div class="upload-grid">
                        <!-- CV -->
                        <div>
                            <div class="upload-card" id="card_cv">
                                <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required onchange="handleFileChange(this, 'card_cv', 'badge_cv')">
                                <div class="upload-icon-circle">
                                    <i class="fa-solid fa-file-user"></i>
                                </div>
                                <div class="upload-title">Curriculum Vitae (CV) <span class="req-star">*</span></div>
                                <div class="upload-meta">PDF, DOC, DOCX &bull; Maks. 5MB</div>
                                <div id="badge_cv" class="file-chosen-badge d-none"></div>
                            </div>
                            @error('cv')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Proposal Magang -->
                        <div>
                            <div class="upload-card" id="card_proposal">
                                <input type="file" id="proposal" name="proposal" accept=".pdf,.doc,.docx" required onchange="handleFileChange(this, 'card_proposal', 'badge_proposal')">
                                <div class="upload-icon-circle">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>
                                <div class="upload-title">Proposal Magang <span class="req-star">*</span></div>
                                <div class="upload-meta">PDF, DOC, DOCX &bull; Maks. 5MB</div>
                                <div id="badge_proposal" class="file-chosen-badge d-none"></div>
                            </div>
                            @error('proposal')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Surat Pengantar Kampus -->
                        <div>
                            <div class="upload-card" id="card_surat">
                                <input type="file" id="surat_permohonan" name="surat_permohonan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required onchange="handleFileChange(this, 'card_surat', 'badge_surat')">
                                <div class="upload-icon-circle">
                                    <i class="fa-solid fa-file-signature"></i>
                                </div>
                                <div class="upload-title">Surat Pengantar Kampus <span class="req-star">*</span></div>
                                <div class="upload-meta">PDF, DOC, DOCX, JPG &bull; Maks. 5MB</div>
                                <div id="badge_surat" class="file-chosen-badge d-none"></div>
                            </div>
                            @error('surat_permohonan')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- INFORMASI PEMBUATAN AKUN OLEH SUPER ADMIN -->
                <div class="callout-box" style="margin-top: 28px;">
                    <div class="callout-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="callout-text">
                        <h4>Informasi Akun Portal Magang</h4>
                        <p>
                            Seluruh data pendaftaran dan berkas persyaratan (CV, Proposal, dan Surat Pengantar) Anda akan diverifikasi oleh pihak PLTU Adipala. Akun portal peserta magang akan dibuatkan secara resmi oleh <strong>Administrator / Super Admin</strong> setelah permohonan disetujui. Informasi akun dapat dicek langsung atau dikoordinasikan dengan pihak PLTU.
                        </p>
                    </div>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="form-actions">
                    <a href="{{ route('login') }}" class="btn-secondary-link">
                        <i class="fa-solid fa-xmark"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn-submit-reg" id="submitBtn">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Kirim Pengajuan Magang</span>
                    </button>
                </div>
            </form>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="reg-footer">
        &copy; {{ date('Y') }} PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala. Hak Cipta Dilindungi Undang-Undang.
    </footer>

    <!-- INTERACTIVE JAVASCRIPT -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Universitas Baru
        const selectUniv = document.getElementById('select_universitas');
        const boxUnivBaru = document.getElementById('field_universitas_baru');
        const inputUnivBaru = document.getElementById('nama_universitas_baru');

        selectUniv.addEventListener('change', function() {
            if (this.value === 'new') {
                boxUnivBaru.classList.remove('d-none');
                inputUnivBaru.setAttribute('required', 'required');
                inputUnivBaru.focus();
            } else {
                boxUnivBaru.classList.add('d-none');
                inputUnivBaru.removeAttribute('required');
            }
        });

        // Toggle Jurusan Baru
        const selectJur = document.getElementById('select_jurusan');
        const boxJurBaru = document.getElementById('field_jurusan_baru');
        const inputJurBaru = document.getElementById('nama_jurusan_baru');

        selectJur.addEventListener('change', function() {
            if (this.value === 'new') {
                boxJurBaru.classList.remove('d-none');
                inputJurBaru.setAttribute('required', 'required');
                inputJurBaru.focus();
            } else {
                boxJurBaru.classList.add('d-none');
                inputJurBaru.removeAttribute('required');
            }
        });

        // Validasi Tanggal
        const tglMulai = document.getElementById('tanggal_mulai');
        const tglSelesai = document.getElementById('tanggal_selesai');

        tglMulai.addEventListener('change', function() {
            if (this.value) {
                tglSelesai.min = this.value;
                if (tglSelesai.value && tglSelesai.value < this.value) {
                    tglSelesai.value = this.value;
                }
            }
        });

        // Loading state saat submit
        const form = document.getElementById('formDaftarMagang');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim Pengajuan...';
        });
    });

    // Helper untuk preview file upload
    function handleFileChange(input, cardId, badgeId) {
        const card = document.getElementById(cardId);
        const badge = document.getElementById(badgeId);

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
            
            card.classList.add('has-file');
            badge.classList.remove('d-none');
            badge.innerHTML = `<i class="fa-solid fa-check-circle" style="color:#059669;"></i> ${file.name} (${sizeInMB} MB)`;
        } else {
            card.classList.remove('has-file');
            badge.classList.add('d-none');
            badge.textContent = '';
        }
    }
    </script>
</body>
</html>
