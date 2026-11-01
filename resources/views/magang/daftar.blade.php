<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pengajuan Magang & PKL - PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        /* ============================================================
           WARNA: harus sama dengan blok :root di login.blade.php
           ============================================================ */
        :root {
            --primary: #00a2b9;
            --primary-dark: #007f92;
            --primary-soft: #e6f6f8;
            --accent: #ffc20e;
            --text: #1a2b3c;
            --muted: #6b7785;
            --border: #d5dbe1;
            --bg: #ffffff;
            --page: #f4f7f9;
            --success-bg: #e8f7ee;
            --success-text: #1e7e46;
            --error-bg: #fdecec;
            --error-text: #c0392b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--page);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .d-none { display: none !important; }

        /* ===================== NAVBAR ===================== */
        .reg-navbar {
            position: sticky;
            top: 0;
            z-index: 60;
            background: var(--bg);
            border-bottom: 1px solid var(--border);
        }

        .reg-nav-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .reg-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: inherit;
            min-width: 0;
        }

        .reg-logo-img { height: 44px; width: auto; display: block; }

        .reg-brand-text h1 { font-size: 15px; font-weight: 700; line-height: 1.2; }
        .reg-brand-text p  { font-size: 11px; color: var(--muted); }

        .btn-login-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            border: 1.5px solid var(--primary);
            border-radius: 6px;
            white-space: nowrap;
            transition: background .2s, color .2s;
        }
        .btn-login-link:hover { background: var(--primary); color: #fff; }

        /* ===================== HERO ===================== */
        .reg-hero {
            position: relative;
            background: url("{{ asset('assets/image/bg-pln.png') }}") center / cover no-repeat;
            color: #fff;
            padding: 64px 24px 120px;
        }

        .reg-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,.45), rgba(0,0,0,.7));
        }

        .reg-hero-inner {
            position: relative;
            max-width: 1100px;
            margin: 0 auto;
        }

        .reg-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.5);
            background: rgba(255,255,255,.1);
            margin-bottom: 18px;
        }
        .reg-hero-badge i { color: var(--accent); }

        .reg-hero h2 {
            font-size: 34px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 12px;
            max-width: 620px;
        }

        .reg-hero p {
            font-size: 14px;
            line-height: 1.7;
            color: rgba(255,255,255,.88);
            max-width: 620px;
            padding-left: 12px;
            border-left: 3px solid var(--accent);
        }

        /* ===================== CARD ===================== */
        .reg-main {
            flex: 1;
            width: 100%;
            max-width: 1100px;
            margin: -72px auto 0;
            padding: 0 24px 48px;
            position: relative;
        }

        .reg-card {
            background: var(--bg);
            border-radius: 14px;
            padding: 40px 44px;
            box-shadow: 0 10px 40px rgba(26, 43, 60, .1);
        }

        .alert-error-box {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            background: var(--error-bg);
            color: var(--error-text);
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 28px;
        }

        /* ===================== SECTIONS ===================== */
        .form-section + .form-section {
            margin-top: 44px;
            padding-top: 40px;
            border-top: 1px solid var(--border);
        }

        .section-head {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
        }

        .section-icon {
            flex: 0 0 auto;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--primary-soft);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .section-head-text h3 { font-size: 17px; font-weight: 700; }
        .section-head-text p  { font-size: 13px; color: var(--muted); margin-top: 2px; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            column-gap: 36px;
            row-gap: 26px;
        }

        .col-span-2 { grid-column: span 2; }

        /* ===================== FIELDS ===================== */
        .form-group { position: relative; min-width: 0; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 2px;
        }

        .req-star { color: var(--error-text); }

        .form-control {
            width: 100%;
            padding: 10px 0;
            font-family: inherit;
            font-size: 14px;
            color: var(--text);
            background: transparent;
            border: none;
            border-bottom: 1.5px solid var(--border);
            border-radius: 0;
            outline: none;
            transition: border-color .2s;
        }
        .form-control::placeholder { color: #a6afb8; font-size: 13px; }
        .form-control:focus { border-bottom-color: var(--primary); }

        .input-hint {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: var(--muted);
        }

        .error-text, .field-error {
            margin-top: 6px;
            font-size: 12px;
            color: var(--error-text);
        }

        .box-new-input {
            margin-top: 16px;
            padding: 14px 16px;
            background: var(--primary-soft);
            border-left: 3px solid var(--primary);
            border-radius: 6px;
        }
        .box-new-input .form-control { border-bottom-color: #b5dfe5; }

        /* ===================== CUSTOM SELECT & DATE PICKER ===================== */
        .cs { position: relative; }

        .cs-native {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .cs-trigger {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 0;
            font-family: inherit;
            font-size: 14px;
            color: var(--text);
            text-align: left;
            background: transparent;
            border: none;
            border-bottom: 1.5px solid var(--border);
            cursor: pointer;
            outline: none;
            transition: border-color .2s;
        }

        .cs-trigger .cs-value { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .cs-trigger .cs-value.placeholder { color: #a6afb8; font-size: 13px; }
        .cs-trigger i { color: var(--muted); font-size: 12px; transition: transform .2s, color .2s; }

        .cs.open .cs-trigger,
        .cs-trigger:focus-visible { border-bottom-color: var(--primary); }
        .cs.open .cs-trigger i { color: var(--primary); }
        .cs:not(.dp).open .cs-trigger i { transform: rotate(180deg); }
        .cs.invalid .cs-trigger { border-bottom-color: var(--error-text); }

        .cs-panel {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            z-index: 50;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 12px 32px rgba(26, 43, 60, .16);
            overflow: hidden;
            animation: panelIn .15s ease;
        }

        @keyframes panelIn {
            from { opacity: 0; transform: translateY(-4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .cs-search {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
        }
        .cs-search input {
            flex: 1;
            border: none;
            outline: none;
            font-family: inherit;
            font-size: 13px;
            color: var(--text);
            background: transparent;
        }

        .cs-list {
            list-style: none;
            max-height: 240px;
            overflow-y: auto;
            padding: 6px;
        }

        .cs-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 9px 12px;
            font-size: 13.5px;
            border-radius: 6px;
            cursor: pointer;
        }
        .cs-item.active { background: var(--primary-soft); }
        .cs-item.selected { color: var(--primary); font-weight: 600; }
        .cs-item.is-new { color: var(--primary); font-weight: 500; border-top: 1px dashed var(--border); border-radius: 0 0 6px 6px; margin-top: 4px; padding-top: 12px; }
        .cs-empty { padding: 14px; font-size: 13px; color: var(--muted); text-align: center; }

        /* Date picker */
        .dp-panel { left: 0; right: auto; width: 300px; max-width: calc(100vw - 48px); padding: 14px; }

        .dp-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .dp-title { font-size: 14px; font-weight: 600; }

        .dp-nav { display: flex; gap: 2px; }
        .dp-nav button {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 6px;
            background: transparent;
            color: var(--muted);
            font-size: 12px;
            cursor: pointer;
        }
        .dp-nav button:hover { background: var(--primary-soft); color: var(--primary); }

        .dp-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            text-align: center;
        }
        .dp-dow { font-size: 11px; font-weight: 600; color: var(--muted); padding: 6px 0; }

        .dp-day {
            aspect-ratio: 1;
            border: none;
            border-radius: 50%;
            background: transparent;
            font-family: inherit;
            font-size: 13px;
            color: var(--text);
            cursor: pointer;
            transition: background .15s;
        }
        .dp-day:hover:not(:disabled) { background: var(--primary-soft); }
        .dp-day.today { box-shadow: inset 0 0 0 1.5px var(--accent); }
        .dp-day.selected { background: var(--primary); color: #fff; font-weight: 600; }
        .dp-day:disabled { color: #c3cad1; cursor: not-allowed; }

        .dp-foot { margin-top: 10px; padding-top: 10px; border-top: 1px solid var(--border); text-align: center; }
        .dp-foot button {
            border: none;
            background: none;
            font-family: inherit;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--primary);
            cursor: pointer;
        }

        /* ===================== UPLOAD ===================== */
        .upload-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            align-items: start;
        }

        .upload-card {
            position: relative;
            text-align: center;
            padding: 26px 16px;
            border: 1.5px dashed var(--border);
            border-radius: 12px;
            background: var(--bg);
            transition: border-color .2s, background .2s;
        }
        .upload-card:hover,
        .upload-card.dragover { border-color: var(--primary); background: var(--primary-soft); }
        .upload-card.has-file { border-style: solid; border-color: var(--primary); background: var(--primary-soft); }
        .upload-card.invalid { border-color: var(--error-text); }

        .upload-card input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-icon-circle {
            width: 52px;
            height: 52px;
            margin: 0 auto 12px;
            border-radius: 50%;
            background: var(--primary-soft);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .upload-card.has-file .upload-icon-circle,
        .upload-card:hover .upload-icon-circle { background: var(--primary); color: #fff; }

        .upload-title { font-size: 14px; font-weight: 600; }
        .upload-meta  { font-size: 12px; color: var(--muted); margin-top: 3px; }

        .file-chosen-badge {
            margin-top: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            max-width: 100%;
            padding: 5px 10px;
            font-size: 12px;
            color: var(--success-text);
            background: var(--success-bg);
            border-radius: 999px;
            word-break: break-all;
            text-align: left;
        }

        /* ===================== CALLOUT & ACTIONS ===================== */
        .callout-box {
            margin-top: 40px;
            display: flex;
            gap: 14px;
            padding: 18px 20px;
            background: var(--primary-soft);
            border-left: 4px solid var(--accent);
            border-radius: 8px;
        }
        .callout-icon { color: var(--primary); font-size: 20px; padding-top: 2px; }
        .callout-text h4 { font-size: 14px; font-weight: 600; margin-bottom: 4px; }
        .callout-text p  { font-size: 13px; line-height: 1.7; color: var(--muted); }
        .callout-text strong { color: var(--text); }

        .form-actions {
            margin-top: 32px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
        }

        .btn-secondary-link,
        .btn-submit-reg {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s, color .2s, border-color .2s;
        }

        .btn-secondary-link { color: var(--primary); background: #fff; border: 1.5px solid var(--primary); }
        .btn-secondary-link:hover { background: var(--primary-soft); }

        .btn-submit-reg { color: #fff; background: var(--primary); border: 1.5px solid var(--primary); }
        .btn-submit-reg:hover:not(:disabled) { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-submit-reg:disabled { opacity: .75; cursor: not-allowed; }

        .btn-login-link:focus-visible,
        .btn-secondary-link:focus-visible,
        .btn-submit-reg:focus-visible,
        .dp-day:focus-visible,
        .dp-nav button:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; }

        /* ===================== FOOTER ===================== */
        .reg-footer {
            text-align: center;
            font-size: 12px;
            color: var(--muted);
            padding: 22px 24px;
            border-top: 1px solid var(--border);
            background: var(--bg);
        }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 900px) {
            .upload-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 700px) {
            .reg-nav-container { padding: 10px 16px; }
            .reg-brand-text p, .btn-login-link span { display: none; }
            .reg-hero { padding: 40px 20px 110px; }
            .reg-hero h2 { font-size: 26px; }
            .reg-main { padding: 0 14px 36px; }
            .reg-card { padding: 26px 20px; }
            .form-grid { grid-template-columns: 1fr; }
            .col-span-2 { grid-column: auto; }
            .form-actions { flex-direction: column-reverse; }
            .form-actions > * { width: 100%; }
        }

        @media (prefers-reduced-motion: reduce) {
            .cs-panel { animation: none; }
        }
    </style>
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
            <a href="{{ route('login') }}" class="btn-login-link">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                <span>Sudah Punya Akun? Masuk</span>
            </a>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="reg-hero">
        <div class="reg-hero-inner">
            <div class="reg-hero-badge">
                <i class="fa-solid fa-graduation-cap"></i> Penerimaan Magang & PKL Periode Aktif
            </div>
            <h2>Formulir Pendaftaran Magang Baru</h2>
            <p>
                Lengkapi data diri, latar belakang akademik, serta unggah berkas persyaratan resmi (CV, Proposal Magang, dan Surat Pengantar Kampus) untuk pengajuan praktik kerja di PLTU Adipala.
            </p>
        </div>
    </section>

    <!-- MAIN FORM -->
    <main class="reg-main">
        <div class="reg-card">

            @if ($errors->any())
                <div class="alert-error-box">
                    <span>⚠️</span>
                    <span>Pengajuan belum terkirim. Periksa kembali isian yang ditandai pada formulir di bawah.</span>
                </div>
            @endif

            <form action="{{ route('magang.store') }}" method="POST" enctype="multipart/form-data" id="formDaftarMagang" novalidate>
                @csrf

                <!-- SEKSI 1: DATA IDENTITAS DIRI -->
                <div class="form-section">
                    <div class="section-head">
                        <div class="section-icon"><i class="fa-regular fa-user"></i></div>
                        <div class="section-head-text">
                            <h3>1. Identitas Calon Peserta</h3>
                            <p>Isi data diri Anda secara lengkap dan akurat sesuai data resmi.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="req-star">*</span></label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                                   value="{{ old('nama_lengkap') }}" placeholder="Contoh: Muhammad Farhan" required>
                            @error('nama_lengkap')<p class="error-text">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group">
                            <label for="nim" class="form-label">NIM / NISN / No. Induk <span class="req-star">*</span></label>
                            <input type="text" id="nim" name="nim" class="form-control"
                                   value="{{ old('nim') }}" placeholder="Contoh: 2311102045" required>
                            <span class="input-hint">NIM ini juga akan digunakan sebagai username akun Anda.</span>
                            @error('nim')<p class="error-text">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="req-star">*</span></label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="js-select" required>
                                <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih jenis kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')<p class="error-text">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Alamat Email Aktif <span class="req-star">*</span></label>
                            <input type="email" id="email" name="email" class="form-control"
                                   value="{{ old('email') }}" placeholder="contoh: farhan@gmail.com" required>
                            <span class="input-hint">Digunakan untuk login portal dan korespondensi.</span>
                            @error('email')<p class="error-text">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group">
                            <label for="no_telepon" class="form-label">No. Telepon / WhatsApp <span class="req-star">*</span></label>
                            <input type="text" id="no_telepon" name="no_telepon" class="form-control"
                                   value="{{ old('no_telepon') }}" placeholder="Contoh: 081234567890" required>
                            @error('no_telepon')<p class="error-text">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group">
                            <label for="emergency_contact_name" class="form-label">Nama Kontak Darurat (Orang Tua / Wali)</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" class="form-control"
                                   value="{{ old('emergency_contact_name') }}" placeholder="Contoh: Joko Widodo (Ayah)">
                            @error('emergency_contact_name')<p class="error-text">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group col-span-2">
                            <label for="emergency_contact_phone" class="form-label">Nomor HP Kontak Darurat</label>
                            <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" class="form-control"
                                   value="{{ old('emergency_contact_phone') }}" placeholder="Contoh: 081987654321">
                            @error('emergency_contact_phone')<p class="error-text">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- SEKSI 2: INFORMASI AKADEMIK & PENEMPATAN -->
                <div class="form-section">
                    <div class="section-head">
                        <div class="section-icon"><i class="fa-solid fa-building-columns"></i></div>
                        <div class="section-head-text">
                            <h3>2. Asal Institusi & Rencana Penempatan</h3>
                            <p>Tentukan asal perguruan tinggi / sekolah, program studi, dan divisi yang dituju.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <!-- Universitas -->
                        <div class="form-group">
                            <label for="select_universitas" class="form-label">Asal Kampus / Sekolah <span class="req-star">*</span></label>
                            <select name="universitas_id" id="select_universitas" class="js-select" required>
                                <option value="" disabled {{ old('universitas_id') ? '' : 'selected' }}>Pilih asal kampus / sekolah</option>
                                @foreach($universitas as $u)
                                    <option value="{{ $u->id_universitas }}" {{ old('universitas_id') == $u->id_universitas ? 'selected' : '' }}>
                                        {{ $u->nama_universitas }}
                                    </option>
                                @endforeach
                                <option value="new" data-new="1" {{ old('universitas_id') == 'new' ? 'selected' : '' }}>+ Tambah Kampus / Sekolah Baru...</option>
                            </select>
                            @error('universitas_id')<p class="error-text">{{ $message }}</p>@enderror

                            <div id="field_universitas_baru" class="box-new-input {{ old('universitas_id') == 'new' ? '' : 'd-none' }}">
                                <label for="nama_universitas_baru" class="form-label">Tulis nama kampus / sekolah baru</label>
                                <input type="text" name="nama_universitas_baru" id="nama_universitas_baru" class="form-control"
                                       value="{{ old('nama_universitas_baru') }}" placeholder="Misal: Politeknik Negeri Semarang">
                                @error('nama_universitas_baru')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Jurusan -->
                        <div class="form-group">
                            <label for="select_jurusan" class="form-label">Jurusan / Program Studi <span class="req-star">*</span></label>
                            <select name="jurusan_id" id="select_jurusan" class="js-select" required>
                                <option value="" disabled {{ old('jurusan_id') ? '' : 'selected' }}>Pilih program studi / jurusan</option>
                                @foreach($jurusan as $j)
                                    <option value="{{ $j->id_jurusan }}" {{ old('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                                <option value="new" data-new="1" {{ old('jurusan_id') == 'new' ? 'selected' : '' }}>+ Tambah Jurusan / Prodi Baru...</option>
                            </select>
                            @error('jurusan_id')<p class="error-text">{{ $message }}</p>@enderror

                            <div id="field_jurusan_baru" class="box-new-input {{ old('jurusan_id') == 'new' ? '' : 'd-none' }}">
                                <label for="nama_jurusan_baru" class="form-label">Tulis nama jurusan baru</label>
                                <input type="text" name="nama_jurusan_baru" id="nama_jurusan_baru" class="form-control"
                                       value="{{ old('nama_jurusan_baru') }}" placeholder="Misal: D4 Teknik Mesin Pembangkit">
                                @error('nama_jurusan_baru')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Divisi Dituju -->
                        <div class="form-group col-span-2">
                            <label for="select_divisi" class="form-label">Divisi Penempatan yang Dituju <span class="req-star">*</span></label>
                            <select name="divisi_id" id="select_divisi" class="js-select" required>
                                <option value="" disabled {{ old('divisi_id') ? '' : 'selected' }}>Pilih divisi tujuan di PLTU Adipala</option>
                                @foreach($divisi as $d)
                                    <option value="{{ $d->id_divisi }}" {{ old('divisi_id') == $d->id_divisi ? 'selected' : '' }}>
                                        {{ $d->nama_divisi }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-hint">Pilih divisi yang paling relevan dengan bidang keahlian atau topik penelitian Anda.</span>
                            @error('divisi_id')<p class="error-text">{{ $message }}</p>@enderror
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="form-group">
                            <label for="tanggal_mulai" class="form-label">Rencana Mulai Magang <span class="req-star">*</span></label>
                            <input type="text" id="tanggal_mulai" name="tanggal_mulai" class="js-date form-control"
                                   value="{{ old('tanggal_mulai') }}" placeholder="Pilih tanggal mulai" required>
                            @error('tanggal_mulai')<p class="error-text">{{ $message }}</p>@enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="form-group">
                            <label for="tanggal_selesai" class="form-label">Rencana Selesai Magang <span class="req-star">*</span></label>
                            <input type="text" id="tanggal_selesai" name="tanggal_selesai" class="js-date form-control"
                                   value="{{ old('tanggal_selesai') }}" placeholder="Pilih tanggal selesai" required>
                            @error('tanggal_selesai')<p class="error-text">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- SEKSI 3: DOKUMEN PERSYARATAN -->
                <div class="form-section">
                    <div class="section-head">
                        <div class="section-icon"><i class="fa-solid fa-file-arrow-up"></i></div>
                        <div class="section-head-text">
                            <h3>3. Dokumen Persyaratan Magang</h3>
                            <p>Unggah file CV, Proposal Magang, dan Surat Pengantar resmi dari kampus/sekolah (Maks. 5MB per file).</p>
                        </div>
                    </div>

                    <div class="upload-grid">
                        <!-- CV -->
                        <div>
                            <div class="upload-card" id="card_cv">
                                <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required
                                       onchange="handleFileChange(this, 'card_cv', 'badge_cv')">
                                <div class="upload-icon-circle"><i class="fa-solid fa-id-card"></i></div>
                                <div class="upload-title">Curriculum Vitae (CV) <span class="req-star">*</span></div>
                                <div class="upload-meta">PDF, DOC, DOCX &bull; Maks. 5MB</div>
                                <div id="badge_cv" class="file-chosen-badge d-none"></div>
                            </div>
                            @error('cv')<p class="error-text">{{ $message }}</p>@enderror
                        </div>

                        <!-- Proposal Magang -->
                        <div>
                            <div class="upload-card" id="card_proposal">
                                <input type="file" id="proposal" name="proposal" accept=".pdf,.doc,.docx" required
                                       onchange="handleFileChange(this, 'card_proposal', 'badge_proposal')">
                                <div class="upload-icon-circle"><i class="fa-solid fa-file-lines"></i></div>
                                <div class="upload-title">Proposal Magang <span class="req-star">*</span></div>
                                <div class="upload-meta">PDF, DOC, DOCX &bull; Maks. 5MB</div>
                                <div id="badge_proposal" class="file-chosen-badge d-none"></div>
                            </div>
                            @error('proposal')<p class="error-text">{{ $message }}</p>@enderror
                        </div>

                        <!-- Surat Pengantar Kampus -->
                        <div>
                            <div class="upload-card" id="card_surat">
                                <input type="file" id="surat_permohonan" name="surat_permohonan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required
                                       onchange="handleFileChange(this, 'card_surat', 'badge_surat')">
                                <div class="upload-icon-circle"><i class="fa-solid fa-file-signature"></i></div>
                                <div class="upload-title">Surat Pengantar Kampus <span class="req-star">*</span></div>
                                <div class="upload-meta">PDF, DOC, DOCX, JPG &bull; Maks. 5MB</div>
                                <div id="badge_surat" class="file-chosen-badge d-none"></div>
                            </div>
                            @error('surat_permohonan')<p class="error-text">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- INFORMASI PEMBUATAN AKUN OLEH SUPER ADMIN -->
                <div class="callout-box">
                    <div class="callout-icon"><i class="fa-solid fa-circle-check"></i></div>
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

    <script>
    /* ============================================================
       CUSTOM SELECT  (menggantikan <select> bawaan browser)
       <select> asli tetap ada (tersembunyi) agar data form terkirim.
       ============================================================ */
    function enhanceSelect(sel) {
        const required = sel.required;
        sel.required = false;

        const wrap = document.createElement('div');
        wrap.className = 'cs';
        wrap.dataset.required = required ? '1' : '';
        sel.parentNode.insertBefore(wrap, sel);
        wrap.appendChild(sel);
        sel.classList.add('cs-native');
        sel.tabIndex = -1;

        const placeholder = (sel.options[0] && sel.options[0].value === '') ? sel.options[0].text : 'Pilih...';
        const items = Array.from(sel.options).filter(o => o.value !== '');
        const searchable = items.length > 8;

        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'cs-trigger';
        trigger.setAttribute('aria-haspopup', 'listbox');
        trigger.setAttribute('aria-expanded', 'false');
        trigger.innerHTML = '<span class="cs-value"></span><i class="fa-solid fa-chevron-down"></i>';
        wrap.appendChild(trigger);
        const valueEl = trigger.querySelector('.cs-value');

        const panel = document.createElement('div');
        panel.className = 'cs-panel d-none';
        panel.innerHTML =
            (searchable ? '<div class="cs-search"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Cari..." autocomplete="off"></div>' : '') +
            '<ul class="cs-list" role="listbox"></ul>';
        wrap.appendChild(panel);
        const list = panel.querySelector('.cs-list');
        const search = panel.querySelector('input');

        let active = -1;
        let shown = [];

        const error = document.createElement('p');
        error.className = 'field-error d-none';
        error.textContent = 'Bagian ini wajib dipilih.';
        wrap.appendChild(error);

        function updateValue() {
            const opt = sel.options[sel.selectedIndex];
            if (sel.value !== '' && opt) {
                valueEl.textContent = opt.text;
                valueEl.classList.remove('placeholder');
            } else {
                valueEl.textContent = placeholder;
                valueEl.classList.add('placeholder');
            }
        }

        function render() {
            const q = search ? search.value.trim().toLowerCase() : '';
            shown = items.filter(o => o.dataset.new || !q || o.text.toLowerCase().includes(q));
            list.innerHTML = '';
            if (!shown.length) {
                list.innerHTML = '<li class="cs-empty">Tidak ditemukan</li>';
                return;
            }
            shown.forEach((o, i) => {
                const li = document.createElement('li');
                li.className = 'cs-item' + (o.value === sel.value ? ' selected' : '') + (o.dataset.new ? ' is-new' : '') + (i === active ? ' active' : '');
                li.setAttribute('role', 'option');
                li.innerHTML = '<span></span>' + (o.value === sel.value ? '<i class="fa-solid fa-check"></i>' : '');
                li.firstChild.textContent = o.text;
                li.addEventListener('mousedown', e => { e.preventDefault(); choose(o.value); });
                li.addEventListener('mousemove', () => setActive(i, false));
                list.appendChild(li);
            });
        }

        function setActive(i, scroll) {
            active = i;
            list.querySelectorAll('.cs-item').forEach((el, idx) => {
                el.classList.toggle('active', idx === i);
                if (idx === i && scroll) el.scrollIntoView({ block: 'nearest' });
            });
        }

        function open() {
            document.querySelectorAll('.cs.open').forEach(c => { if (c !== wrap) c._close && c._close(); });
            wrap.classList.add('open');
            panel.classList.remove('d-none');
            trigger.setAttribute('aria-expanded', 'true');
            if (search) search.value = '';
            active = Math.max(0, shown.findIndex(o => o.value === sel.value));
            render();
            setActive(active, true);
            if (search) search.focus();
        }

        function close() {
            wrap.classList.remove('open');
            panel.classList.add('d-none');
            trigger.setAttribute('aria-expanded', 'false');
        }
        wrap._close = close;

        function choose(val) {
            sel.value = val;
            updateValue();
            close();
            trigger.focus();
            wrap.classList.remove('invalid');
            error.classList.add('d-none');
            sel.dispatchEvent(new Event('change', { bubbles: true }));
        }

        trigger.addEventListener('click', () => wrap.classList.contains('open') ? close() : open());

        wrap.addEventListener('keydown', e => {
            const isOpen = wrap.classList.contains('open');
            if (!isOpen && ['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(e.key) && e.target === trigger) {
                e.preventDefault(); open(); return;
            }
            if (!isOpen) return;
            if (e.key === 'Escape') { e.preventDefault(); close(); trigger.focus(); }
            else if (e.key === 'ArrowDown') { e.preventDefault(); setActive(Math.min(shown.length - 1, active + 1), true); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); setActive(Math.max(0, active - 1), true); }
            else if (e.key === 'Home') { e.preventDefault(); setActive(0, true); }
            else if (e.key === 'End') { e.preventDefault(); setActive(shown.length - 1, true); }
            else if (e.key === 'Enter') { e.preventDefault(); if (shown[active]) choose(shown[active].value); }
            else if (e.key === 'Tab') { close(); }
        });

        if (search) search.addEventListener('input', () => { active = 0; render(); });

        shown = items;
        updateValue();
    }

    /* ============================================================
       CUSTOM DATE PICKER  (menggantikan <input type="date">)
       Nilai tersimpan di input asli dengan format YYYY-MM-DD.
       ============================================================ */
    const BULAN = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const HARI  = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
    const pad = n => String(n).padStart(2, '0');
    const toYMD = (y, m, d) => y + '-' + pad(m + 1) + '-' + pad(d);
    const parseYMD = s => { const p = /^(\d{4})-(\d{2})-(\d{2})$/.exec(s || ''); return p ? { y: +p[1], m: +p[2] - 1, d: +p[3] } : null; };
    const fmtLong = s => { const p = parseYMD(s); return p ? p.d + ' ' + BULAN[p.m] + ' ' + p.y : ''; };

    function enhanceDate(inp) {
        const required = inp.required;
        const placeholder = inp.getAttribute('placeholder') || 'Pilih tanggal';
        inp.required = false;
        inp.type = 'hidden';

        const wrap = document.createElement('div');
        wrap.className = 'cs dp';
        wrap.dataset.required = required ? '1' : '';
        inp.parentNode.insertBefore(wrap, inp);
        wrap.appendChild(inp);

        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'cs-trigger';
        trigger.setAttribute('aria-haspopup', 'dialog');
        trigger.setAttribute('aria-expanded', 'false');
        trigger.setAttribute('aria-labelledby', 'lbl-' + inp.id);
        trigger.innerHTML = '<span class="cs-value"></span><i class="fa-regular fa-calendar"></i>';
        wrap.appendChild(trigger);
        const valueEl = trigger.querySelector('.cs-value');

        // beri id pada label agar bisa dibaca screen reader
        const label = inp.closest('.form-group').querySelector('label');
        if (label) { label.id = 'lbl-' + inp.id; label.removeAttribute('for'); }

        const panel = document.createElement('div');
        panel.className = 'cs-panel dp-panel d-none';
        panel.setAttribute('role', 'dialog');
        wrap.appendChild(panel);

        const error = document.createElement('p');
        error.className = 'field-error d-none';
        error.textContent = 'Tanggal wajib dipilih.';
        wrap.appendChild(error);

        let minDate = null;
        const today = new Date();
        const now = parseYMD(inp.value) || { y: today.getFullYear(), m: today.getMonth(), d: today.getDate() };
        let viewY = now.y, viewM = now.m;

        function updateValue() {
            if (inp.value) { valueEl.textContent = fmtLong(inp.value); valueEl.classList.remove('placeholder'); }
            else { valueEl.textContent = placeholder; valueEl.classList.add('placeholder'); }
        }

        function render() {
            const first = new Date(viewY, viewM, 1);
            const offset = (first.getDay() + 6) % 7; // Senin = 0
            const days = new Date(viewY, viewM + 1, 0).getDate();
            const todayYMD = toYMD(today.getFullYear(), today.getMonth(), today.getDate());

            let html =
                '<div class="dp-head">' +
                    '<div class="dp-title">' + BULAN[viewM] + ' ' + viewY + '</div>' +
                    '<div class="dp-nav">' +
                        '<button type="button" data-nav="-12" aria-label="Tahun sebelumnya"><i class="fa-solid fa-angles-left"></i></button>' +
                        '<button type="button" data-nav="-1" aria-label="Bulan sebelumnya"><i class="fa-solid fa-angle-left"></i></button>' +
                        '<button type="button" data-nav="1" aria-label="Bulan berikutnya"><i class="fa-solid fa-angle-right"></i></button>' +
                        '<button type="button" data-nav="12" aria-label="Tahun berikutnya"><i class="fa-solid fa-angles-right"></i></button>' +
                    '</div>' +
                '</div><div class="dp-grid">';

            HARI.forEach(h => html += '<div class="dp-dow">' + h + '</div>');
            for (let i = 0; i < offset; i++) html += '<div></div>';
            for (let d = 1; d <= days; d++) {
                const ymd = toYMD(viewY, viewM, d);
                const disabled = minDate && ymd < minDate;
                html += '<button type="button" class="dp-day' + (ymd === inp.value ? ' selected' : '') + (ymd === todayYMD ? ' today' : '') +
                        '" data-date="' + ymd + '"' + (disabled ? ' disabled' : '') + '>' + d + '</button>';
            }
            html += '</div><div class="dp-foot"><button type="button" data-today>Hari ini</button></div>';
            panel.innerHTML = html;
        }

        function open() {
            document.querySelectorAll('.cs.open').forEach(c => { if (c !== wrap) c._close && c._close(); });
            const p = parseYMD(inp.value) || parseYMD(minDate);
            if (p) { viewY = p.y; viewM = p.m; }
            render();
            wrap.classList.add('open');
            panel.classList.remove('d-none');
            trigger.setAttribute('aria-expanded', 'true');
        }

        function close() {
            wrap.classList.remove('open');
            panel.classList.add('d-none');
            trigger.setAttribute('aria-expanded', 'false');
        }
        wrap._close = close;

        function setValue(v, silent) {
            inp.value = v;
            updateValue();
            wrap.classList.remove('invalid');
            error.classList.add('d-none');
            if (!silent) inp.dispatchEvent(new Event('change', { bubbles: true }));
        }

        trigger.addEventListener('click', () => wrap.classList.contains('open') ? close() : open());

        panel.addEventListener('click', e => {
            const nav = e.target.closest('[data-nav]');
            const day = e.target.closest('[data-date]');
            const tdy = e.target.closest('[data-today]');
            if (nav) {
                const d = new Date(viewY, viewM + (+nav.dataset.nav), 1);
                viewY = d.getFullYear(); viewM = d.getMonth();
                render();
            } else if (day && !day.disabled) {
                setValue(day.dataset.date);
                close();
                trigger.focus();
            } else if (tdy) {
                const t = toYMD(today.getFullYear(), today.getMonth(), today.getDate());
                if (!minDate || t >= minDate) { setValue(t); close(); trigger.focus(); }
                else { viewY = today.getFullYear(); viewM = today.getMonth(); render(); }
            }
        });

        wrap.addEventListener('keydown', e => {
            if (e.key === 'Escape' && wrap.classList.contains('open')) { close(); trigger.focus(); }
        });

        inp._dp = {
            setMin: v => { minDate = v || null; },
            setValue: setValue
        };

        updateValue();
    }

    /* Tutup panel saat klik di luar */
    document.addEventListener('click', e => {
        document.querySelectorAll('.cs.open').forEach(c => { if (!c.contains(e.target)) c._close && c._close(); });
    });

    /* ============================================================
       UPLOAD FILE
       ============================================================ */
    function handleFileChange(input, cardId, badgeId) {
        const card = document.getElementById(cardId);
        const badge = document.getElementById(badgeId);
        card.classList.remove('invalid');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);

            if (file.size > 5 * 1024 * 1024) {
                input.value = '';
                card.classList.remove('has-file');
                card.classList.add('invalid');
                badge.classList.remove('d-none');
                badge.style.cssText = 'color:#c0392b;background:#fdecec;';
                badge.textContent = 'Ukuran file ' + sizeInMB + ' MB melebihi batas 5 MB';
                return;
            }

            card.classList.add('has-file');
            badge.style.cssText = '';
            badge.classList.remove('d-none');
            badge.innerHTML = '<i class="fa-solid fa-circle-check"></i> ';
            badge.appendChild(document.createTextNode(file.name + ' (' + sizeInMB + ' MB)'));
        } else {
            card.classList.remove('has-file');
            badge.classList.add('d-none');
            badge.textContent = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('select.js-select').forEach(enhanceSelect);
        document.querySelectorAll('input.js-date').forEach(enhanceDate);

        // efek drag & drop pada kartu upload
        document.querySelectorAll('.upload-card').forEach(card => {
            ['dragenter', 'dragover'].forEach(ev => card.addEventListener(ev, () => card.classList.add('dragover')));
            ['dragleave', 'drop'].forEach(ev => card.addEventListener(ev, () => card.classList.remove('dragover')));
        });

        // Toggle Universitas Baru
        const selectUniv = document.getElementById('select_universitas');
        const boxUnivBaru = document.getElementById('field_universitas_baru');
        const inputUnivBaru = document.getElementById('nama_universitas_baru');
        function toggleUniv() {
            const isNew = selectUniv.value === 'new';
            boxUnivBaru.classList.toggle('d-none', !isNew);
            inputUnivBaru.required = isNew;
            return isNew;
        }
        selectUniv.addEventListener('change', () => { if (toggleUniv()) inputUnivBaru.focus(); });
        toggleUniv();

        // Toggle Jurusan Baru
        const selectJur = document.getElementById('select_jurusan');
        const boxJurBaru = document.getElementById('field_jurusan_baru');
        const inputJurBaru = document.getElementById('nama_jurusan_baru');
        function toggleJur() {
            const isNew = selectJur.value === 'new';
            boxJurBaru.classList.toggle('d-none', !isNew);
            inputJurBaru.required = isNew;
            return isNew;
        }
        selectJur.addEventListener('change', () => { if (toggleJur()) inputJurBaru.focus(); });
        toggleJur();

        // Validasi Tanggal: tanggal selesai tidak boleh sebelum tanggal mulai
        const tglMulai = document.getElementById('tanggal_mulai');
        const tglSelesai = document.getElementById('tanggal_selesai');
        function syncDates() {
            tglSelesai._dp.setMin(tglMulai.value);
            if (tglMulai.value && tglSelesai.value && tglSelesai.value < tglMulai.value) {
                tglSelesai._dp.setValue(tglMulai.value, true);
            }
        }
        tglMulai.addEventListener('change', syncDates);
        syncDates();

        // Validasi & loading state saat submit
        const form = document.getElementById('formDaftarMagang');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function (e) {
            let firstInvalid = null;

            // select & tanggal custom
            form.querySelectorAll('.cs[data-required="1"]').forEach(wrap => {
                const field = wrap.querySelector('select, input');
                const err = wrap.querySelector('.field-error');
                if (!field.value) {
                    wrap.classList.add('invalid');
                    err.classList.remove('d-none');
                    firstInvalid = firstInvalid || wrap;
                }
            });

            // input teks, email, dan file bawaan
            form.querySelectorAll('input[required]').forEach(el => {
                if (el.type === 'hidden') return;
                if (!el.checkValidity()) {
                    firstInvalid = firstInvalid || (el.type === 'file' ? el.closest('.upload-card') : el);
                    if (el.type === 'file') el.closest('.upload-card').classList.add('invalid');
                }
            });

            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                if (firstInvalid.tagName === 'INPUT') {
                    firstInvalid.reportValidity();
                }
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Mengirim Pengajuan...</span>';
        });
    });
    </script>
</body>
</html>
