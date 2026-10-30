@extends('peserta.layout')

@section('title', 'Informasi Perusahaan')

@section('content')

<style>
    /* ==========================================================================
       INFORMASI PERUSAHAAN - PANEL KACA & KARTU BENTO (TEMA DASHBOARD & ABSENSI)
       ========================================================================== */
    .in-page {
        box-sizing: border-box; width: 100%; max-width: 1200px;
        margin-left: auto; margin-right: auto; color: var(--text);
    }
    .in-page *, .in-page *::before, .in-page *::after { box-sizing: border-box; }

    /* ---------- Panel Utama ---------- */
    .in-panel {
        background: var(--panel);
        backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 40px; padding: 30px;
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.22);
    }
    .in-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 18px 24px; margin-bottom: 26px; }
    .in-top-text { flex: 1 1 360px; min-width: 0; }
    .in-title { margin: 0; font-size: clamp(24px, 3vw, 36px); font-weight: 700; letter-spacing: -0.025em; line-height: 1.15; color: #ffffff; }
    .in-sub { margin: 10px 0 0; font-size: 13.5px; line-height: 1.6; color: var(--muted); font-weight: 500; }

    /* ---------- Kartu (Bento Style) ---------- */
    .in-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-bottom: 24px; }
    .in-card {
        background: var(--card); backdrop-filter: blur(16px) saturate(130%); -webkit-backdrop-filter: blur(16px) saturate(130%);
        border: 1px solid var(--border); border-radius: 26px; padding: 22px; min-width: 0;
        display: flex; flex-direction: column; color: var(--text);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
    }
    .in-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 20px; }
    .in-card-title { margin: 0; font-size: 16px; font-weight: 700; letter-spacing: -0.01em; color: #ffffff; display: flex; align-items: center; gap: 10px; }
    .in-card-title i { color: var(--accent); }

    /* ---------- Profil Perusahaan ---------- */
    .in-profile { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
    .in-logo-box {
        background: #ffffff; border: 1px solid var(--border-strong); border-radius: 12px; padding: 10px;
        width: 140px; display: flex; align-items: center; justify-content: center;
    }
    .in-profile-name { font-size: 17px; font-weight: 700; color: #ffffff; margin: 0 0 4px 0; }
    .in-profile-ind { font-size: 12px; color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; }

    .in-desc { font-size: 13.5px; color: var(--text-2); line-height: 1.6; margin-bottom: 18px; }

    .in-contact-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px; font-size: 13px; color: var(--text-2); }
    .in-contact-list li { display: flex; align-items: flex-start; gap: 12px; line-height: 1.4; }
    .in-contact-list i { color: var(--accent); width: 16px; text-align: center; margin-top: 2px; }
    .in-contact-list a { color: var(--accent); text-decoration: none; font-weight: 500; }
    .in-contact-list a:hover { color: #ffffff; text-decoration: underline; }

    /* ---------- Penempatan & Jadwal ---------- */
    .in-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px; }
    .in-detail-box { background: rgba(255, 255, 255, 0.04); padding: 14px; border-radius: 12px; border: 1px solid var(--border-strong); }
    .in-detail-lbl { font-size: 10.5px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; font-family: var(--font-num); margin-bottom: 4px; }
    .in-detail-val { font-size: 13.5px; font-weight: 700; color: #ffffff; line-height: 1.3; }

    .in-schedule { border-top: 1px solid var(--border); padding-top: 16px; }
    .in-schedule-title { font-size: 13px; font-weight: 700; color: #ffffff; margin: 0 0 10px 0; }
    .in-schedule-list { display: flex; flex-direction: column; gap: 8px; font-size: 13px; color: var(--text-2); }
    .in-schedule-list div { display: flex; align-items: flex-start; gap: 8px; line-height: 1.4; }
    .in-schedule-list strong { color: #ffffff; }
    .in-schedule-list i { color: var(--accent); font-size: 10px; margin-top: 5px; }

    /* ---------- Tombol ---------- */
    .in-page .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 40px; padding: 0 20px; border-radius: 9999px; font-family: inherit; font-size: 13.5px; font-weight: 600; line-height: 1;
        border: 1px solid transparent; cursor: pointer; text-decoration: none; white-space: nowrap;
        transition: background .2s ease, border-color .2s ease, transform .2s ease, color .2s ease;
    }
    .in-page .btn:hover { transform: translateY(-1px); }
    .in-page .btn-sm { height: 34px; padding: 0 14px; font-size: 12px; }
    .in-page .btn-primary { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }
    .in-page .btn-primary:hover { background: #d6f55a; border-color: #d6f55a; }
    .in-page .btn-outline { background: transparent; color: var(--text); border-color: var(--border-strong); justify-content: flex-start; }
    .in-page .btn-outline:hover { background: var(--card-2-hover); color: #ffffff; }

    /* ---------- Tata Tertib & K3 ---------- */
    .in-rules { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 14px; font-size: 13px; color: var(--text-2); line-height: 1.5; }
    .in-rules li { display: flex; gap: 12px; align-items: flex-start; background: rgba(255, 255, 255, 0.03); padding: 14px; border-radius: 12px; border: 1px solid var(--border-strong); }
    .in-rules i { font-size: 16px; margin-top: 2px; }
    .in-rules strong { color: #ffffff; display: block; margin-bottom: 2px; font-size: 13.5px; }

    .rule-ok i { color: var(--ok); }
    .rule-warn i { color: var(--warn); }

    /* ---------- Kontak Internal & Dokumen ---------- */
    .in-docs-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
    .in-docs-box { background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-strong); border-radius: 14px; padding: 18px; }
    .in-docs-title { font-size: 14px; font-weight: 700; color: #ffffff; margin: 0 0 14px 0; display: flex; align-items: center; gap: 8px; }

    .in-contact-fast { display: flex; flex-direction: column; gap: 10px; font-size: 13px; color: var(--text-2); }
    .in-contact-fast div { display: flex; flex-direction: column; gap: 2px; padding-bottom: 10px; border-bottom: 1px dashed var(--border-strong); }
    .in-contact-fast div:last-child { border-bottom: none; padding-bottom: 0; }
    .in-contact-fast strong { color: #ffffff; }

    .in-downloads { display: flex; flex-direction: column; gap: 10px; }
    .in-downloads .btn-outline { background: var(--card-2); }

    /* Responsif */
    @media (max-width: 768px) {
        .in-panel { padding: 18px; border-radius: 30px; }
        .in-card { padding: 18px; border-radius: 20px; }
        .in-detail-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="in-page">
<div class="in-panel">

    <!-- ================= KEPALA PANEL ================= -->
    <header class="in-top">
        <div class="in-top-text">
            <h1 class="in-title">Informasi Perusahaan & Panduan Magang</h1>
            <p class="in-sub">Panduan lengkap profil perusahaan, tata tertib operasional, jam kerja, kontak penting, dan dokumen PLTU Adipala.</p>
        </div>
    </header>

    <!-- ================= PROFIL & PENEMPATAN ================= -->
    <div class="in-grid">

        <!-- Profil Perusahaan -->
        <section class="in-card">
            <div class="in-head">
                <h3 class="in-card-title"><i class="fa-solid fa-industry"></i> Profil Perusahaan</h3>
            </div>

            <div class="in-profile">
                <div class="in-logo-box">
                    <img src="{{ asset('assets/image/logo-pln.png') }}" alt="PLN Logo" style="max-width: 100%; height: auto; filter: brightness(0.9);">
                </div>
                <div>
                    <h2 class="in-profile-name">{{ $company->name }}</h2>
                    <p class="in-profile-ind">{{ $company->industry }}</p>
                </div>
            </div>

            <p class="in-desc">
                {{ $company->description }}
            </p>

            <ul class="in-contact-list">
                <li><i class="fa-solid fa-location-dot"></i> <span>{{ $company->address }}</span></li>
                <li><i class="fa-solid fa-phone"></i> <span>{{ $company->phone }}</span></li>
                <li><i class="fa-solid fa-envelope"></i> <span>{{ $company->email }}</span></li>
                <li><i class="fa-solid fa-globe"></i> <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a></li>
            </ul>
        </section>

        <!-- Penempatan & Jadwal Magang -->
        <section class="in-card">
            <div class="in-head">
                <h3 class="in-card-title"><i class="fa-solid fa-user-check"></i> Penempatan & Jadwal Magang</h3>
            </div>

            <div class="in-detail-grid">
                <div class="in-detail-box">
                    <div class="in-detail-lbl">Divisi Penempatan</div>
                    <div class="in-detail-val">{{ $penempatan->divisi->nama_divisi ?? 'Sistem Informasi' }}</div>
                </div>

                <div class="in-detail-box">
                    <div class="in-detail-lbl">Pembimbing Lapangan</div>
                    <div class="in-detail-val">{{ $penempatan->nama_pembimbing ?? 'Kang Cihuy' }}</div>
                </div>

                <div class="in-detail-box">
                    <div class="in-detail-lbl">Tanggal Mulai Magang</div>
                    <div class="in-detail-val">
                        {{ $penempatan ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d M Y') : '13 Okt 2026' }}
                    </div>
                </div>

                <div class="in-detail-box">
                    <div class="in-detail-lbl">Tanggal Selesai Magang</div>
                    <div class="in-detail-val">
                        {{ $penempatan ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d M Y') : '31 Okt 2026' }}
                    </div>
                </div>
            </div>

            <div class="in-schedule">
                <h4 class="in-schedule-title">Jadwal Kerja Operasional:</h4>
                @php
                    $startWib = substr($company->work_start_time ?? '08:00:00', 0, 5);
                    $endWib = substr($company->work_end_time ?? '16:00:00', 0, 5);
                    $toleranceMin = $policy->late_tolerance_minutes ?? 15;
                    $deadlineWib = \Carbon\Carbon::createFromFormat('H:i:s', $company->work_start_time ?? '08:00:00')->addMinutes($toleranceMin)->format('H:i');
                @endphp
                <div class="in-schedule-list">
                    <div><i class="fa-solid fa-circle"></i> <span><strong>Hari Kerja:</strong> Senin - Jumat</span></div>
                    <div><i class="fa-solid fa-circle"></i> <span><strong>Jam Masuk:</strong> {{ $startWib }} WIB <br><small style="color: var(--muted);">(Toleransi keterlambatan presensi {{ $deadlineWib }} WIB)</small></span></div>
                    <div><i class="fa-solid fa-circle"></i> <span><strong>Jam Pulang:</strong> {{ $endWib }} WIB</span></div>
                    <div><i class="fa-solid fa-circle"></i> <span><strong>Istirahat:</strong> 12:00 - 13:00 WIB (Jumat: 11:30 - 13:00)</span></div>
                </div>

                <div style="margin-top: 18px;">
                    <a href="{{ route('peserta.absensi') }}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-location-dot"></i> Buka Halaman Presensi
                    </a>
                </div>
            </div>
        </section>
    </div>

    <!-- ================= TATA TERTIB & K3 ================= -->
    <div class="in-grid">
        <section class="in-card">
            <div class="in-head">
                <h3 class="in-card-title"><i class="fa-solid fa-scale-balanced"></i> Tata Tertib Magang</h3>
            </div>

            <ul class="in-rules rule-ok">
                <li>
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <strong>Pakaian Rapi</strong>
                        Memakai kemeja berkerah rapi, celana/rok bahan gelap, dan sepatu tertutup. Dilarang memakai kaos oblong atau sandal.
                    </div>
                </li>
                <li>
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <strong>Identitas PKL</strong>
                        Wajib mengalungkan ID Card resmi selama berada di kawasan perkantoran dan pembangkit.
                    </div>
                </li>
                <li>
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <strong>Kerahasiaan Data</strong>
                        Menjaga kerahasiaan dokumen teknis, blueprint instalasi, dan kredensial jaringan PLTU Adipala.
                    </div>
                </li>
                <li>
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <strong>Perangkat & Internet</strong>
                        Menggunakan akses internet dan laptop kantor hanya untuk kegiatan riset dan tugas magang resmi.
                    </div>
                </li>
            </ul>
        </section>

        <section class="in-card">
            <div class="in-head">
                <h3 class="in-card-title"><i class="fa-solid fa-helmet-safety"></i> Prosedur Keselamatan Kerja (K3)</h3>
            </div>

            <ul class="in-rules rule-warn">
                <li>
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <strong>Alat Pelindung Diri (APD)</strong>
                        Memakai helm safety, kacamata pengaman, dan safety shoes saat inspeksi ke area boiler / turbin.
                    </div>
                </li>
                <li>
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <strong>Patuhi Rambu Bahaya</strong>
                        Perhatikan tanda bahaya tegangan tinggi (HV), area bising (ear protection), dan jalur evakuasi darurat.
                    </div>
                </li>
                <li>
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <strong>Dampingan Pembimbing</strong>
                        Dilarang memasuki area confined space atau mengoperasikan tuas instrumen tanpa pendampingan teknisi resmi.
                    </div>
                </li>
                <li>
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <strong>Tanggap Darurat</strong>
                        Jika mendengar sirine bahaya, segera berkumpul di <em>Assembly Point</em> terdekat secara tenang dan tertib.
                    </div>
                </li>
            </ul>
        </section>
    </div>

    <!-- ================= KONTAK & DOKUMEN ================= -->
    <section class="in-card">
        <div class="in-head">
            <h3 class="in-card-title"><i class="fa-solid fa-address-book"></i> Kontak Penting & Dokumen Panduan</h3>
        </div>

        <div class="in-docs-grid">
            <!-- Kontak Cepat Internal -->
            <div class="in-docs-box">
                <h4 class="in-docs-title">Kontak Cepat Internal</h4>
                <div class="in-contact-fast">
                    <div><strong>Pos Keamanan Utama</strong> (0282) 555-111</div>
                    <div><strong>Tanggap Darurat & Medis K3</strong> (0282) 555-911</div>
                    <div><strong>Helpdesk Sistem Informasi</strong> Ext. 101</div>
                    <div><strong>Bagian SDM & Kepegawaian</strong> sdm.adipala@indonesiapower.co.id</div>
                </div>
            </div>

            <!-- Dokumen Unduhan -->
            <div class="in-docs-box">
                <h4 class="in-docs-title">Unduhan Dokumen Magang</h4>
                <div class="in-downloads">
                    <a href="#" class="btn btn-sm btn-outline">
                        <i class="fa-solid fa-file-pdf" style="color: #ff5a5a; width: 14px;"></i>
                        <span style="flex: 1; text-align: left;">Buku Panduan Pelaksanaan PKL.pdf</span>
                    </a>
                    <a href="#" class="btn btn-sm btn-outline">
                        <i class="fa-solid fa-file-lines" style="color: var(--info); width: 14px;"></i>
                        <span style="flex: 1; text-align: left;">Form Orientasi & Komitmen K3.pdf</span>
                    </a>
                    <a href="#" class="btn btn-sm btn-outline">
                        <i class="fa-solid fa-file-word" style="color: #5a96ff; width: 14px;"></i>
                        <span style="flex: 1; text-align: left;">Format Laporan Akhir PKL.docx</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
</div>

@endsection
