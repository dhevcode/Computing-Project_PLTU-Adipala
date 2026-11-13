<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Presensi Magang - {{ $mhs->nama_lengkap }}</title>
    <style>
        @page {
            margin: 18mm 15mm 20mm 15mm;
            size: a4 portrait;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.35;
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
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            margin: 12px 0 16px 0;
            letter-spacing: 0.8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3px 4px;
            font-size: 9.5px;
            vertical-align: top;
        }
        .info-label {
            width: 22%;
            font-weight: bold;
            color: #475569;
        }
        .info-colon {
            width: 2%;
            text-align: center;
        }
        .info-val {
            width: 76%;
            color: #0f172a;
        }
        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .summary-box td {
            border: 1px solid #cbd5e1;
            padding: 6px;
            text-align: center;
            background: #f8fafc;
        }
        .summary-box .num {
            font-size: 12px;
            font-weight: bold;
            color: #0A5F63;
        }
        .summary-box .lbl {
            font-size: 8.5px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background: #0A5F63;
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 6px 4px;
            border: 1px solid #07474a;
            text-align: center;
        }
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 4px;
            font-size: 8.5px;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-present { background: #dcfce7; color: #166534; }
        .badge-late { background: #fef9c3; color: #854d0e; }
        .badge-leave { background: #dbeafe; color: #1e40af; }
        .badge-sick { background: #ffedd5; color: #9a3412; }
        .badge-absent { background: #fee2e2; color: #991b1b; }

        .signature-table {
            width: 100%;
            margin-top: 24px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9.5px;
        }
        .sign-space {
            height: 55px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
        .sign-title {
            color: #64748b;
            font-size: 8.5px;
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
                Tgl Cetak: {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d M Y') }}<br>
                Jam: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB
            </td>
        </tr>
    </table>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-title">
        REKAPITULASI PRESENSI & KEHADIRAN MAHASISWA MAGANG
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
            <td class="info-label">Divisi / Penempatan Unit</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ optional($penempatan->divisi)->nama_divisi ?? 'Operasional & Pemeliharaan' }}</td>
            <td class="info-label">Periode Kontrak PKL</td>
            <td class="info-colon">:</td>
            <td class="info-val">
                {{ optional($penempatan)->tanggal_mulai ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                s/d
                {{ optional($penempatan)->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->translatedFormat('d M Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="info-label">Pembimbing Lapangan</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ optional($penempatan->pembimbing)->nama_lengkap ?? 'Tim Pembimbing PLTU Adipala' }}</td>
            <td class="info-label">Status Magang</td>
            <td class="info-colon">:</td>
            <td class="info-val"><strong>{{ $mhs->status_magang_label }}</strong></td>
        </tr>
    </table>

    <!-- RINGKASAN REKAPITULASI -->
    <table class="summary-box">
        <tr>
            <td>
                <div class="num">{{ $stats['total'] }}</div>
                <div class="lbl">Total Hari Sesi</div>
            </td>
            <td>
                <div class="num" style="color: #166534;">{{ $stats['hadir_tepat'] }}</div>
                <div class="lbl">Hadir Tepat</div>
            </td>
            <td>
                <div class="num" style="color: #854d0e;">{{ $stats['terlambat'] }}</div>
                <div class="lbl">Terlambat</div>
            </td>
            <td>
                <div class="num" style="color: #1e40af;">{{ $stats['izin'] }}</div>
                <div class="lbl">Izin / Cuti</div>
            </td>
            <td>
                <div class="num" style="color: #9a3412;">{{ $stats['sakit'] }}</div>
                <div class="lbl">Sakit</div>
            </td>
            <td>
                <div class="num" style="color: #991b1b;">{{ $stats['alpa'] }}</div>
                <div class="lbl">Alpa / Belum</div>
            </td>
            <td>
                <div class="num" style="color: #0A5F63;">{{ round($stats['total_jam_kerja'], 1) }} Jam</div>
                <div class="lbl">Total Waktu Kerja</div>
            </td>
        </tr>
    </table>

    <!-- TABEL LOG PRESENSI LENGKAP -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 75px;">Tanggal</th>
                <th style="width: 55px;">Jam In</th>
                <th style="width: 55px;">Jam Out</th>
                <th style="width: 65px;">Durasi Kerja</th>
                <th style="width: 60px;">Status</th>
                <th style="width: 70px;">Keterlambatan</th>
                <th>Catatan / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $idx => $row)
                @php
                    $status = $row->check_in_status;
                    $badgeClass = match($status) {
                        'present' => 'badge-present',
                        'late' => 'badge-late',
                        'leave' => 'badge-leave',
                        'sick' => 'badge-sick',
                        default => 'badge-absent'
                    };
                    $statusLabel = match($status) {
                        'present' => 'Hadir',
                        'late' => 'Terlambat',
                        'leave' => 'Izin/Cuti',
                        'sick' => 'Sakit',
                        default => 'Alpa'
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $row->attendance_date ? \Carbon\Carbon::parse($row->attendance_date)->translatedFormat('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $row->check_in_at ? \Carbon\Carbon::parse($row->check_in_at)->format('H:i') : '-' }}</td>
                    <td class="text-center">{{ $row->check_out_at ? \Carbon\Carbon::parse($row->check_out_at)->format('H:i') : '-' }}</td>
                    <td class="text-center">{{ $row->total_work_minutes > 0 ? floor($row->total_work_minutes / 60) . 'j ' . ($row->total_work_minutes % 60) . 'm' : '-' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td class="text-center">
                        {{ $row->late_minutes > 0 ? $row->late_minutes . ' Menit' : '-' }}
                    </td>
                    <td>{{ $row->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 18px; color: #64748b;">
                        Tidak ada riwayat presensi tercatat untuk peserta ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- LEMBAR PENGESAHAN -->
    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Pembimbing Lapangan PLTU Adipala</strong>
                <div class="sign-space"></div>
                <div class="sign-name">{{ optional($penempatan->pembimbing)->nama_lengkap ?? 'Pembimbing Lapangan' }}</div>
                <div class="sign-title">PT PLN Indonesia Power UBP Adipala</div>
            </td>
            <td>
                Cilacap, {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}<br>
                <strong>Mahasiswa Magang</strong>
                <div class="sign-space"></div>
                <div class="sign-name">{{ $mhs->nama_lengkap }}</div>
                <div class="sign-title">NIM: {{ $mhs->nim }}</div>
            </td>
        </tr>
    </table>

</body>
</html>

