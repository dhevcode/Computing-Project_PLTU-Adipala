<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transkrip Penilaian Akhir Magang - {{ $mhs->nama_lengkap }}</title>
    <style>
        @page {
            margin: 18mm 15mm 20mm 15mm;
            size: a4 portrait;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10.5px;
            color: #1e293b;
            line-height: 1.45;
        }
        .header-kop {
            width: 100%;
            border-bottom: 2.5px solid #0A5F63;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header-kop td {
            vertical-align: middle;
        }
        .instansi-title {
            font-size: 13px;
            font-weight: 800;
            color: #0A5F63;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .instansi-sub {
            font-size: 9.5px;
            color: #475569;
            margin-top: 2px;
        }
        .doc-title {
            text-align: center;
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            margin: 14px 0 18px 0;
            letter-spacing: 0.8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3px 4px;
            font-size: 10px;
            vertical-align: top;
        }
        .info-label {
            width: 24%;
            font-weight: bold;
            color: #475569;
        }
        .info-colon {
            width: 2%;
            text-align: center;
        }
        .info-val {
            width: 74%;
            color: #0f172a;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background: #0A5F63;
            color: #ffffff;
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 8px 6px;
            border: 1px solid #07474a;
            text-align: center;
        }
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 6px;
            font-size: 9.5px;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .score-box-final {
            background: #f8fafc;
            border: 1.5px solid #0A5F63;
            border-radius: 6px;
            padding: 12px 18px;
            margin-bottom: 18px;
        }
        .note-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #0A5F63;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 9.5px;
        }
        .signature-table {
            width: 100%;
            margin-top: 28px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 10px;
        }
        .sign-space {
            height: 60px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
        .sign-title {
            color: #64748b;
            font-size: 9px;
        }
    </style>
</head>
<body>

    <!-- KOP RESMI -->
    <table class="header-kop">
        <tr>
            <td style="width: 70px;">
                <img src="{{ public_path('images/logo-pln.png') }}" style="height: 48px;" alt="PLN Logo" onerror="this.style.display='none'">
            </td>
            <td>
                <div class="instansi-title">PT PLN INDONESIA POWER</div>
                <div class="instansi-sub">
                    <strong>UBP JAWA TENGAH 2 ADIPALA</strong><br>
                    Jl. Laut, Karangkandri, Adipala, Kabupaten Cilacap, Jawa Tengah 53271<br>
                    Email: internship.ubpadp@plnindonesiapower.co.id &bull; Website: plnindonesiapower.co.id
                </div>
            </td>
            <td style="width: 120px; text-align: right; font-size: 8.5px; color: #64748b;">
                Tgl Terbit: {{ $evaluation && $evaluation->published_at ? \Carbon\Carbon::parse($evaluation->published_at)->translatedFormat('d M Y') : date('d/m/Y') }}
            </td>
        </tr>
    </table>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-title">
        TRANSKRIP PENILAIAN AKHIR MAGANG & PRAKTIK KERJA LAPANGAN
    </div>

    <!-- BIODATA MAHASISWA -->
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Lengkap</td>
            <td class="info-colon">:</td>
            <td class="info-val"><strong>{{ $mhs->nama_lengkap }}</strong></td>
            <td class="info-label">Asal Perguruan Tinggi</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ $mhs->universitas->nama_universitas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Nomor Induk Mahasiswa (NIM)</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ $mhs->nim }}</td>
            <td class="info-label">Program Studi / Jurusan</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Divisi Penempatan</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ optional($penempatan->divisi)->nama_divisi ?? 'Operasional & Pemeliharaan' }}</td>
            <td class="info-label">Periode Magang</td>
            <td class="info-colon">:</td>
            <td class="info-val">
                {{ optional($penempatan)->tanggal_mulai ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                s/d
                {{ optional($penempatan)->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->translatedFormat('d M Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="info-label">Status Kelulusan</td>
            <td class="info-colon">:</td>
            <td class="info-val"><strong>LULUS (ALUMNI MAGANG)</strong></td>
            <td class="info-label">Pembimbing Lapangan</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ optional($penempatan->pembimbing)->nama_lengkap ?? 'Pembimbing PLTU Adipala' }}</td>
        </tr>
    </table>

    <!-- TABEL RINCIAN NILAI -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Komponen Evaluasi & Kriteria Penilaian</th>
                <th style="width: 80px;">Bobot</th>
                <th style="width: 80px;">Skor (0-100)</th>
                <th style="width: 90px;">Skor Terbobot</th>
            </tr>
        </thead>
        <tbody>
            @php $totalWeighted = 0; $totalWeight = 0; @endphp
            @forelse($criteria as $idx => $c)
                @php
                    $detail = $evaluation ? $evaluation->details->firstWhere('criterion_id', $c->id_criterion) : null;
                    $score = $detail ? $detail->score : 85;
                    $weighted = round(($score * $c->weight) / 100, 2);
                    $totalWeighted += $weighted;
                    $totalWeight += $c->weight;
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $c->criterion_name }}</strong><br>
                        <span style="font-size: 8.5px; color: #64748b;">{{ $c->description }}</span>
                    </td>
                    <td class="text-center">{{ $c->weight }}%</td>
                    <td class="text-center"><strong>{{ $score }}</strong></td>
                    <td class="text-center">{{ number_format($weighted, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Kriteria penilaian tidak ditemukan.</td></tr>
            @endforelse
            <tr style="background: #f1f5f9; font-weight: bold;">
                <td colspan="2" class="text-right">TOTAL NILAI AKHIR (SKALA 100) :</td>
                <td class="text-center">{{ $totalWeight }}%</td>
                <td colspan="2" class="text-center" style="font-size: 13px; color: #0A5F63;">
                    {{ $evaluation ? $evaluation->total_score : round($totalWeighted, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- KOTAK PREDIKAT KELULUSAN -->
    @php
        $finalScore = $evaluation ? $evaluation->total_score : round($totalWeighted, 2);
        $grade = $evaluation ? $evaluation->grade : ($finalScore >= 85 ? 'A' : ($finalScore >= 75 ? 'B' : ($finalScore >= 60 ? 'C' : 'D')));
        $pred = $evaluation ? $evaluation->predicate : ($finalScore >= 85 ? 'Sangat Baik' : ($finalScore >= 75 ? 'Baik' : ($finalScore >= 60 ? 'Cukup' : 'Kurang')));
    @endphp
    <div class="score-box-final">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <span style="font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: bold;">Predikat Kelulusan:</span><br>
                    <span style="font-size: 16px; font-weight: 800; color: #0A5F63;">GRADE {{ $grade }} &bull; {{ strtoupper($pred) }}</span>
                </td>
                <td style="width: 50%; text-align: right;">
                    <span style="font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: bold;">Keterangan Status:</span><br>
                    <span style="font-size: 13px; font-weight: 700; color: #166534;">DINYATAKAN SELESAI & MEMENUHI SYARAT PKL</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- CATATAN & REKOMENDASI PEMBIMBING -->
    @if($evaluation && $evaluation->participant_note)
        <div class="note-box">
            <strong>Catatan & Rekomendasi Pembimbing Lapangan:</strong><br>
            <em>"{{ $evaluation->participant_note }}"</em>
        </div>
    @endif

    <!-- LEMBAR PENGESAHAN -->
    <table class="signature-table">
        <tr>
            <td>
                Menyetujui,<br>
                <strong>Pembimbing Lapangan PLTU Adipala</strong>
                <div class="sign-space"></div>
                <div class="sign-name">{{ optional($penempatan->pembimbing)->nama_lengkap ?? 'Pembimbing Lapangan' }}</div>
                <div class="sign-title">PT PLN Indonesia Power UBP Adipala</div>
            </td>
            <td>
                Cilacap, {{ $evaluation && $evaluation->published_at ? \Carbon\Carbon::parse($evaluation->published_at)->translatedFormat('d F Y') : \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}<br>
                <strong>Assistant Manager Keuangan & Umum</strong>
                <div class="sign-space"></div>
                <div class="sign-name">TIM MANAJEMEN SDM</div>
                <div class="sign-title">PT PLN Indonesia Power UBP Adipala</div>
            </td>
        </tr>
    </table>

</body>
</html>

