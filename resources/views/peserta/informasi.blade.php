@extends('peserta.layout')

@section('title', 'Informasi Perusahaan')

@section('content')

    <!-- Header Section -->
    <div class="page-header-card">
        <div class="page-header-content">
            <div class="page-icon">
                <i class="fa-regular fa-building"></i>
            </div>
            <div>
                <h1>Informasi Perusahaan & Panduan Magang</h1>
                <p>Panduan lengkap profil perusahaan, tata tertib operasional, jam kerja, kontak penting, dan dokumen PLTU Adipala.</p>
            </div>
        </div>
    </div>

    <!-- Profil Perusahaan & Penempatan Magang -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 24px;">

        <!-- Profil Perusahaan -->
        <div class="card">
            <div class="card-title-box">
                <h3><i class="fa-solid fa-industry"></i> Profil Perusahaan</h3>
            </div>

            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px; width: 140px; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('assets/image/logo-pln.png') }}" alt="PLN Logo" style="max-width: 100%; height: auto;">
                </div>
                <div>
                    <h2 style="font-size: 16px; font-weight: 700; color: #0f172a;">{{ $company->name }}</h2>
                    <p style="font-size: 12px; color: #0284c7; font-weight: 500;">{{ $company->industry }}</p>
                </div>
            </div>

            <p style="font-size: 13px; color: #334155; line-height: 1.5; margin-bottom: 16px;">
                {{ $company->description }}
            </p>

            <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px; font-size: 12.5px; color: #475569;">
                <li><i class="fa-solid fa-location-dot" style="width: 20px; color: var(--primary-color);"></i> {{ $company->address }}</li>
                <li><i class="fa-solid fa-phone" style="width: 20px; color: var(--primary-color);"></i> {{ $company->phone }}</li>
                <li><i class="fa-solid fa-envelope" style="width: 20px; color: var(--primary-color);"></i> {{ $company->email }}</li>
                <li><i class="fa-solid fa-globe" style="width: 20px; color: var(--primary-color);"></i> <a href="{{ $company->website }}" target="_blank" style="color: var(--primary-color); text-decoration: none;">{{ $company->website }}</a></li>
            </ul>
        </div>

        <!-- Penempatan & Jadwal Kerja -->
        <div class="card">
            <div class="card-title-box">
                <h3><i class="fa-solid fa-user-check"></i> Penempatan & Jadwal Magang</h3>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px;">
                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; color: #64748b; text-transform: uppercase;">Divisi Penempatan</div>
                    <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-top: 2px;">
                        {{ $penempatan->divisi->nama_divisi ?? 'Sistem Informasi' }}
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; color: #64748b; text-transform: uppercase;">Pembimbing Lapangan</div>
                    <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-top: 2px;">
                        {{ $penempatan->nama_pembimbing ?? 'Kang Cihuy' }}
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; color: #64748b; text-transform: uppercase;">Tanggal Mulai Magang</div>
                    <div style="font-size: 13px; font-weight: 600; color: #0f172a; margin-top: 2px;">
                        {{ $penempatan ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d M Y') : '13 Okt 2026' }}
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; color: #64748b; text-transform: uppercase;">Tanggal Selesai Magang</div>
                    <div style="font-size: 13px; font-weight: 600; color: #0f172a; margin-top: 2px;">
                        {{ $penempatan ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d M Y') : '31 Okt 2026' }}
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid #f1f5f9; padding-top: 14px;">
                <h4 style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">Jadwal Kerja Operasional:</h4>
                @php
                    $startWib = substr($company->work_start_time ?? '08:00:00', 0, 5);
                    $endWib = substr($company->work_end_time ?? '16:00:00', 0, 5);
                    $toleranceMin = $policy->late_tolerance_minutes ?? 15;
                    $deadlineWib = \Carbon\Carbon::createFromFormat('H:i:s', $company->work_start_time ?? '08:00:00')->addMinutes($toleranceMin)->format('H:i');
                @endphp
                <div style="font-size: 12.5px; color: #475569; display: flex; flex-direction: column; gap: 6px;">
                    <div>&bull; <strong>Hari Kerja:</strong> Senin - Jumat</div>
                    <div>&bull; <strong>Jam Masuk:</strong> {{ $startWib }} WIB (Toleransi batas presensi {{ $deadlineWib }} WIB)</div>
                    <div>&bull; <strong>Jam Pulang:</strong> {{ $endWib }} WIB</div>
                    <div>&bull; <strong>Istirahat:</strong> 12:00 - 13:00 WIB (Jumat: 11:30 - 13:00 WIB)</div>
                </div>

                <div style="margin-top: 14px;">
                    <a href="{{ route('peserta.absensi') }}" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 8px 16px; border-radius: 6px;">
                        <i class="fa-solid fa-location-dot"></i> Lakukan Presensi / Absensi Sekarang
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Tata Tertib & Prosedur K3 (README Section 11.6) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 24px;">

        <div class="card">
            <div class="card-title-box">
                <h3><i class="fa-solid fa-scale-balanced"></i> Tata Tertib Magang</h3>
            </div>

            <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px; font-size: 13px; color: #334155;">
                <li style="display: flex; gap: 10px;">
                    <i class="fa-solid fa-check" style="color: #10b981; margin-top: 3px;"></i>
                    <span><strong>Pakaian Rapi:</strong> Memakai kemeja berkerah rapi, celana/rok bahan gelap, dan sepatu tertutup. Dilarang memakai kaos oblong atau sandal.</span>
                </li>
                <li style="display: flex; gap: 10px;">
                    <i class="fa-solid fa-check" style="color: #10b981; margin-top: 3px;"></i>
                    <span><strong>Identitas PKL:</strong> Wajib mengalungkan ID Card resmi selama berada di kawasan perkantoran dan pembangkit.</span>
                </li>
                <li style="display: flex; gap: 10px;">
                    <i class="fa-solid fa-check" style="color: #10b981; margin-top: 3px;"></i>
                    <span><strong>Kerahasiaan Data:</strong> Menjaga kerahasiaan dokumen teknis, blueprint instalasi, dan kredensial jaringan PLTU Adipala.</span>
                </li>
                <li style="display: flex; gap: 10px;">
                    <i class="fa-solid fa-check" style="color: #10b981; margin-top: 3px;"></i>
                    <span><strong>Perangkat & Internet:</strong> Menggunakan akses internet dan laptop kantor hanya untuk kegiatan riset dan tugas magang resmi.</span>
                </li>
            </ul>
        </div>

        <div class="card">
            <div class="card-title-box">
                <h3><i class="fa-solid fa-helmet-safety"></i> Prosedur Keselamatan Kerja (K3)</h3>
            </div>

            <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px; font-size: 13px; color: #334155;">
                <li style="display: flex; gap: 10px;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #f59e0b; margin-top: 3px;"></i>
                    <span><strong>Alat Pelindung Diri (APD):</strong> Memakai helm safety, kacamata pengaman, dan safety shoes saat inspeksi ke area boiler / turbin.</span>
                </li>
                <li style="display: flex; gap: 10px;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #f59e0b; margin-top: 3px;"></i>
                    <span><strong>Patuhi Rambu Bahaya:</strong> Perhatikan tanda bahaya tegangan tinggi (HV), area bising (ear protection), dan jalur evakuasi darurat.</span>
                </li>
                <li style="display: flex; gap: 10px;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #f59e0b; margin-top: 3px;"></i>
                    <span><strong>Dampingan Pembimbing:</strong> Dilarang memasuki area confined space atau mengoperasikan tuas instrumen tanpa pendampingan teknisi resmi.</span>
                </li>
                <li style="display: flex; gap: 10px;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #f59e0b; margin-top: 3px;"></i>
                    <span><strong>Tanggap Darurat:</strong> Jika mendengar sirine bahaya, segera berkumpul di <em>Assembly Point</em> terdekat secara tenang dan tertib.</span>
                </li>
            </ul>
        </div>

    </div>

    <!-- Kontak Penting & Dokumen Perusahaan (README Section 11.7 & 11.8) -->
    <div class="card">
        <div class="card-title-box">
            <h3><i class="fa-solid fa-address-book"></i> Kontak Penting & Dokumen Panduan Magang</h3>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
            <!-- Kontak -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px;">
                <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">Kontak Cepat Internal:</h4>
                <div style="display: flex; flex-direction: column; gap: 8px; font-size: 13px; color: #475569;">
                    <div><strong>&bull; Pos Keamanan Utama:</strong> (0282) 555-111</div>
                    <div><strong>&bull; Tanggap Darurat & Medis K3:</strong> (0282) 555-911</div>
                    <div><strong>&bull; Helpdesk Sistem Informasi:</strong> Ext. 101</div>
                    <div><strong>&bull; Bagian SDM & Kepegawaian:</strong> sdm.adipala@indonesiapower.co.id</div>
                </div>
            </div>

            <!-- Dokumen Unduhan -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px;">
                <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">Unduhan Dokumen Magang:</h4>
                <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                    <a href="#" class="btn btn-sm btn-outline" style="justify-content: flex-start;">
                        <i class="fa-solid fa-file-pdf" style="color: #dc2626;"></i> Buku Panduan Pelaksanaan PKL Adipala.pdf
                    </a>
                    <a href="#" class="btn btn-sm btn-outline" style="justify-content: flex-start;">
                        <i class="fa-solid fa-file-lines" style="color: #0284c7;"></i> Form Orientasi & Komitmen K3 Mahasiswa.pdf
                    </a>
                    <a href="#" class="btn btn-sm btn-outline" style="justify-content: flex-start;">
                        <i class="fa-solid fa-file-word" style="color: #2563eb;"></i> Format Laporan Akhir PKL Adipala.docx
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
