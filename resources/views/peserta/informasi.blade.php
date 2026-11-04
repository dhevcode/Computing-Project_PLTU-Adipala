@extends('peserta.layouts.app')

@section('title', 'Informasi Perusahaan')
@section('page_title', 'Informasi & Panduan Magang')
@section('page_subtitle', 'Panduan profil perusahaan, tata tertib operasional, jam kerja, kontak penting, dan dokumen PLTU Adipala.')

@push('styles')
<style>
    /* ==========================================================================
       MODUL INFORMASI PERUSAHAAN - TEMA CLEAN DASHBOARD & RESPONSIVE
       ========================================================================== */

    /* ANIMATIONS */
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* ================= HEADER DASHBOARD ================= */
    .dashboard-header {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 16px; margin-bottom: 24px;
        animation: fadeIn 0.4s ease-out;
    }
    .header-info h2 {
        font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px 0;
    }
    .header-info p {
        font-size: 13.5px; color: var(--muted); margin: 0; line-height: 1.4;
    }

    /* ================= GRID & KARTU UTAMA ================= */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }

    .card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        color: var(--text);
        box-shadow: 0 4px 14px rgba(0,0,0,0.02);
        animation: fadeInUp 0.4s ease-out both;
        transition: 0.3s;
    }
    .card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.05); border-color: #CBD5E1; }

    /* Staggered Animation */
    .info-grid:nth-child(2) .card:nth-child(1) { animation-delay: 0.1s; }
    .info-grid:nth-child(2) .card:nth-child(2) { animation-delay: 0.2s; }
    .info-grid:nth-child(3) .card:nth-child(1) { animation-delay: 0.3s; }
    .info-grid:nth-child(3) .card:nth-child(2) { animation-delay: 0.4s; }

    /* Header Kartu */
    .card-head {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        border-bottom: 1px dashed var(--page);
        padding-bottom: 16px;
    }
    .icon-circle {
        width: 44px; height: 44px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .icon-primary { background: var(--primary-soft); color: var(--primary-dark); }
    .icon-success { background: #ECFDF5; color: #059669; }
    .icon-warning { background: #FFFBEB; color: #D97706; }

    .card-title { margin: 0; font-size: 16.5px; font-weight: 800; color: var(--text); }

    /* ================= KONTEN PROFIL ================= */
    .profile-wrap { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; flex-wrap: wrap; }
    .logo-box {
        background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 12px;
        width: 110px; height: 110px; display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .logo-box img { max-width: 100%; max-height: 100%; object-fit: contain; }

    .profile-info { flex: 1; min-width: 200px; }
    .profile-name { font-size: 18px; font-weight: 800; color: var(--text); margin: 0 0 4px 0; line-height: 1.3; }
    .profile-ind { font-size: 12px; color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; }

    .company-desc { font-size: 13.5px; color: var(--muted); line-height: 1.6; margin-bottom: 20px; }

    .contact-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px; font-size: 13.5px; color: var(--muted); }
    .contact-list li { display: flex; align-items: flex-start; gap: 12px; line-height: 1.5; }
    .contact-list i { color: var(--primary); width: 16px; text-align: center; margin-top: 2px; }
    .contact-list a { color: var(--primary); text-decoration: none; font-weight: 600; }
    .contact-list a:hover { color: var(--primary-dark); text-decoration: underline; }

    /* ================= KONTEN PENEMPATAN & JADWAL ================= */
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
    .detail-box { background: var(--page); padding: 16px; border-radius: 12px; border: 1px solid var(--border); }
    .detail-lbl { font-size: 11px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
    .detail-val { font-size: 14px; font-weight: 700; color: var(--text); line-height: 1.4; word-wrap: break-word; }

    .schedule-wrap { border-top: 1px dashed var(--border); padding-top: 20px; }
    .schedule-title { font-size: 14px; font-weight: 800; color: var(--text); margin: 0 0 12px 0; display: flex; align-items: center; gap: 8px; }
    .schedule-title i { color: var(--primary); }

    .schedule-list { display: flex; flex-direction: column; gap: 12px; font-size: 13.5px; color: var(--muted); }
    .schedule-list div { display: flex; align-items: flex-start; gap: 12px; line-height: 1.5; }
    .schedule-list strong { color: var(--text); font-weight: 700; width: 90px; flex-shrink: 0; }
    .schedule-list i { color: var(--primary); font-size: 12px; margin-top: 4px; }

    /* ================= KONTEN TATA TERTIB & K3 ================= */
    .rules-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 14px; font-size: 13.5px; color: var(--muted); line-height: 1.5; }
    .rules-list li { display: flex; gap: 14px; align-items: flex-start; background: var(--page); padding: 16px; border-radius: 12px; border: 1px solid var(--border); }
    .rules-list i { font-size: 18px; margin-top: 2px; }
    .rules-list strong { color: var(--text); display: block; margin-bottom: 4px; font-size: 13.5px; font-weight: 800; }

    .rule-ok i { color: #059669; }
    .rule-warn i { color: #D97706; }

    /* ================= DOKUMEN & KONTAK CEPAT ================= */
    .docs-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
    .docs-box { background: var(--page); border: 1px solid var(--border); border-radius: 12px; padding: 20px; }
    .docs-title { font-size: 14.5px; font-weight: 800; color: var(--text); margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px; border-bottom: 1px dashed var(--border); padding-bottom: 12px; }

    .contact-fast { display: flex; flex-direction: column; gap: 12px; font-size: 13.5px; color: var(--muted); }
    .contact-fast div { display: flex; flex-direction: column; gap: 4px; padding-bottom: 12px; border-bottom: 1px dashed var(--border); }
    .contact-fast div:last-child { border-bottom: none; padding-bottom: 0; }
    .contact-fast strong { color: var(--text); font-weight: 800; }

    .downloads-list { display: flex; flex-direction: column; gap: 10px; }
    .btn-outline {
        display: flex; align-items: center; gap: 10px; width: 100%;
        background: #fff; border: 1px solid var(--border); color: var(--text);
        padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
        text-decoration: none; transition: 0.2s;
    }
    .btn-outline:hover { background: #F8FAFC; border-color: var(--primary); }
    .btn-outline span { line-height: 1.4; word-wrap: break-word; text-align: left; }

    .btn-primary {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        background: var(--primary); color: #fff; border-radius: 8px;
        padding: 10px 20px; font-size: 13.5px; font-weight: 700; text-decoration: none; transition: 0.2s;
    }
    .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

    /* ================= RESPONSIVE MOBILE ================= */
    @media (max-width: 900px) {
        .info-grid { grid-template-columns: 1fr; gap: 16px; margin-bottom: 16px; }
    }
    @media (max-width: 640px) {
        .dashboard-header { flex-direction: column; align-items: flex-start; gap: 12px; }

        .card { padding: 20px 16px; }
        .profile-wrap { flex-direction: column; align-items: flex-start; text-align: left; gap: 16px; }

        .detail-grid { grid-template-columns: 1fr; gap: 12px; }
        .docs-grid { grid-template-columns: 1fr; gap: 16px; }

        .schedule-list strong { width: 100%; margin-bottom: 4px; }
        .schedule-list div { flex-direction: column; gap: 2px; border-bottom: 1px dashed var(--border); padding-bottom: 12px; }
        .schedule-list div:last-child { border-bottom: none; padding-bottom: 0; }
        .schedule-list i { display: none; }

        .btn-primary { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

<!-- ================= HEADER DASHBOARD ================= -->
<div class="dashboard-header">
    <div class="header-info">
        <h2>Informasi Perusahaan & Panduan Magang</h2>
        <p>Panduan profil perusahaan, tata tertib operasional, jadwal, dan dokumen resmi PLTU Adipala.</p>
    </div>
</div>

<!-- ================= PROFIL & PENEMPATAN ================= -->
<div class="info-grid">

    <!-- Profil Perusahaan -->
    <section class="card">
        <div class="card-head">
            <div class="icon-circle icon-primary"><i class="fa-solid fa-industry"></i></div>
            <h3 class="card-title">Profil Perusahaan</h3>
        </div>

        <div class="profile-wrap">
            <div class="logo-box">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="PLN Logo">
            </div>
            <div class="profile-info">
                <h2 class="profile-name">{{ $company->name ?? 'PT PLN Indonesia Power' }}</h2>
                <p class="profile-ind">{{ $company->industry ?? 'Energi & Pembangkit Listrik' }}</p>
            </div>
        </div>

        <p class="company-desc">
            {{ $company->description ?? 'Informasi deskripsi perusahaan belum tersedia dalam sistem.' }}
        </p>

        <ul class="contact-list">
            <li><i class="fa-solid fa-location-dot"></i> <span>{{ $company->address ?? 'Alamat belum diatur' }}</span></li>
            <li><i class="fa-solid fa-phone"></i> <span>{{ $company->phone ?? '-' }}</span></li>
            <li><i class="fa-solid fa-envelope"></i> <span>{{ $company->email ?? '-' }}</span></li>
            <li><i class="fa-solid fa-globe"></i> <a href="{{ $company->website ?? '#' }}" target="_blank">{{ $company->website ?? 'Website Belum Tersedia' }}</a></li>
        </ul>
    </section>

    <!-- Penempatan & Jadwal Magang -->
    <section class="card">
        <div class="card-head">
            <div class="icon-circle icon-success"><i class="fa-solid fa-user-check"></i></div>
            <h3 class="card-title">Penempatan & Jadwal PKL</h3>
        </div>

        <div class="detail-grid">
            <div class="detail-box">
                <div class="detail-lbl">Divisi Penempatan</div>
                <div class="detail-val" style="color: var(--primary);">{{ $penempatan->divisi->nama_divisi ?? 'Belum Ditentukan' }}</div>
            </div>

            <div class="detail-box">
                <div class="detail-lbl">Pembimbing Lapangan</div>
                <div class="detail-val">{{ $penempatan->nama_pembimbing ?? 'Belum Diplot' }}</div>
            </div>

            <div class="detail-box">
                <div class="detail-lbl">Mulai Magang</div>
                <div class="detail-val">
                    {{ isset($penempatan->tanggal_mulai) ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->translatedFormat('d F Y') : '-' }}
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-lbl">Selesai Magang</div>
                <div class="detail-val">
                    {{ isset($penempatan->tanggal_selesai) ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->translatedFormat('d F Y') : '-' }}
                </div>
            </div>
        </div>

        <div class="schedule-wrap">
            <h4 class="schedule-title"><i class="fa-regular fa-clock"></i> Jadwal Kerja Operasional:</h4>
            @php
                $startWib = substr($company->work_start_time ?? '08:00:00', 0, 5);
                $endWib = substr($company->work_end_time ?? '16:00:00', 0, 5);
                $toleranceMin = $policy->late_tolerance_minutes ?? 15;
                try {
                    $deadlineWib = \Carbon\Carbon::createFromFormat('H:i:s', (strlen($company->work_start_time ?? '') === 5 ? ($company->work_start_time ?? '08:00') . ':00' : ($company->work_start_time ?? '08:00:00')))
                        ->addMinutes((int)$toleranceMin)
                        ->format('H:i');
                } catch (\Exception $e) {
                    $deadlineWib = '08:15';
                }
            @endphp
            <div class="schedule-list">
                <div><i class="fa-solid fa-circle"></i> <strong>Hari Kerja</strong> <span>Senin - Jumat (Kecuali Hari Libur Nasional)</span></div>
                <div><i class="fa-solid fa-circle"></i> <strong>Jam Masuk</strong> <span>{{ $startWib }} WIB <br><small style="color: var(--muted); font-size: 11.5px;">(Batas toleransi check-in presensi maksimal {{ $deadlineWib }} WIB)</small></span></div>
                <div><i class="fa-solid fa-circle"></i> <strong>Jam Pulang</strong> <span>Mulai pukul {{ $endWib }} WIB</span></div>
                <div><i class="fa-solid fa-circle"></i> <strong>Istirahat</strong> <span>12:00 - 13:00 WIB (Khusus Jumat: 11:30 - 13:00)</span></div>
            </div>

            <div style="margin-top: 20px;">
                <a href="{{ route('peserta.absensi') }}" class="btn-primary">
                    <i class="fa-solid fa-location-dot"></i> Buka Monitoring Presensi
                </a>
            </div>
        </div>
    </section>
</div>

<!-- ================= TATA TERTIB & K3 ================= -->
<div class="info-grid">
    <section class="card">
        <div class="card-head">
            <div class="icon-circle icon-primary"><i class="fa-solid fa-scale-balanced"></i></div>
            <h3 class="card-title">Tata Tertib Pelaksanaan Magang</h3>
        </div>

        <ul class="rules-list rule-ok">
            <li>
                <i class="fa-solid fa-circle-check"></i>
                <div>
                    <strong>Pakaian Seragam & Penampilan</strong>
                    Memakai kemeja berkerah rapi, celana/rok bahan warna gelap, dan sepatu tertutup. Dilarang keras memakai kaos oblong, celana jeans sobek, atau sandal di area perkantoran.
                </div>
            </li>
            <li>
                <i class="fa-solid fa-circle-check"></i>
                <div>
                    <strong>Tanda Pengenal (ID Card)</strong>
                    Peserta wajib mengalungkan ID Card resmi selama berada di seluruh kawasan perkantoran dan lingkungan pembangkit PLTU.
                </div>
            </li>
            <li>
                <i class="fa-solid fa-circle-check"></i>
                <div>
                    <strong>Kerahasiaan Data (NDA)</strong>
                    Menjaga kerahasiaan seluruh dokumen teknis, blueprint instalasi, dan dilarang menyebarkan foto area terbatas ke media sosial.
                </div>
            </li>
            <li>
                <i class="fa-solid fa-circle-check"></i>
                <div>
                    <strong>Fasilitas Perangkat & Internet</strong>
                    Menggunakan akses fasilitas internet LAN/Wi-Fi dan sarana kantor murni hanya untuk keperluan penyelesaian tugas magang resmi.
                </div>
            </li>
        </ul>
    </section>

    <section class="card">
        <div class="card-head">
            <div class="icon-circle icon-warning"><i class="fa-solid fa-helmet-safety"></i></div>
            <h3 class="card-title">Prosedur Keselamatan Kerja (K3)</h3>
        </div>

        <ul class="rules-list rule-warn">
            <li>
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                    <strong>Alat Pelindung Diri (APD) Wajib</strong>
                    Menggunakan helm safety, kacamata pengaman, dan safety shoes bersertifikat saat inspeksi masuk ke area boiler, turbin, atau yard.
                </div>
            </li>
            <li>
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                    <strong>Perhatikan Rambu Keselamatan</strong>
                    Waspada pada tanda peringatan bahaya tegangan tinggi (HV), area dengan tingkat kebisingan tinggi (wajib ear protection), dan ketahui jalur evakuasi.
                </div>
            </li>
            <li>
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                    <strong>Pendampingan Wajib (Escorting)</strong>
                    Dilarang keras memasuki area <em>confined space</em> atau memanipulasi operasional panel instrumen tanpa pendampingan langsung dari teknisi bersertifikat.
                </div>
            </li>
            <li>
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                    <strong>Prosedur Tanggap Darurat</strong>
                    Apabila mendengar alarm sirine bahaya, hentikan pekerjaan dan segera menuju <em>Assembly Point</em> terdekat secara tenang dan tidak berlarian.
                </div>
            </li>
        </ul>
    </section>
</div>

<!-- ================= KONTAK & DOKUMEN UNDUHAN ================= -->
<section class="card" style="margin-bottom: 24px;">
    <div class="card-head">
        <div class="icon-circle icon-primary"><i class="fa-solid fa-address-book"></i></div>
        <h3 class="card-title">Kontak Penting & Dokumen Panduan</h3>
    </div>

    <div class="docs-grid">
        <!-- Kontak Cepat Internal -->
        <div class="docs-box">
            <h4 class="docs-title">Kontak Cepat Internal PLTU</h4>
            <div class="contact-fast">
                <div><strong>Pos Keamanan & Security Utama</strong> <span>(0282) 555-111</span></div>
                <div><strong>Tim Tanggap Darurat Medis & K3</strong> <span>(0282) 555-911</span></div>
                <div><strong>Helpdesk Jaringan IT / Sistem</strong> <span>Ext. 101</span></div>
                <div><strong>Bagian SDM, Kepegawaian & Magang</strong> <span>sdm.adipala@indonesiapower.co.id</span></div>
            </div>
        </div>

        <!-- Dokumen Unduhan -->
        <div class="docs-box">
            <h4 class="docs-title">Unduhan Dokumen Administrasi</h4>
            <div class="downloads-list">
                <a href="#" class="btn-outline">
                    <i class="fa-solid fa-file-pdf" style="color: #EF4444; font-size: 16px; flex-shrink: 0;"></i>
                    <span>Buku Panduan Pelaksanaan PKL & K3 Terpadu.pdf</span>
                </a>
                <a href="#" class="btn-outline">
                    <i class="fa-solid fa-file-lines" style="color: #2563EB; font-size: 16px; flex-shrink: 0;"></i>
                    <span>Form Orientasi Awal & Lembar Komitmen K3.pdf</span>
                </a>
                <a href="#" class="btn-outline">
                    <i class="fa-solid fa-file-word" style="color: #3B82F6; font-size: 16px; flex-shrink: 0;"></i>
                    <span>Template Standar Format Laporan Akhir PKL.docx</span>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
