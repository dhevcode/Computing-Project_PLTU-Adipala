<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @php
    echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Rekap Presensi</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
    @endphp
    <style>
        body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
        .header-title { font-size: 14pt; font-weight: bold; color: #0A5F63; text-align: center; }
        .sub-title { font-size: 11pt; font-weight: bold; text-align: center; color: #334155; }
        .meta-label { font-weight: bold; background-color: #F1F5F9; }
        .th-header { background-color: #0A5F63; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000; }
        .td-data { border: 1px solid #CBD5E1; vertical-align: middle; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .summary-header { background-color: #E2E8F0; font-weight: bold; text-align: center; border: 1px solid #000000; }
        .summary-val { font-weight: bold; font-size: 12pt; text-align: center; border: 1px solid #000000; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="8" class="header-title">PT PLN INDONESIA POWER UBP JAWA TENGAH 2 ADIPALA</td>
        </tr>
        <tr>
            <td colspan="8" class="sub-title">REKAPITULASI PRESENSI & KEHADIRAN MAHASISWA MAGANG</td>
        </tr>
        <tr><td colspan="8"></td></tr>

        <!-- BIODATA -->
        <tr>
            <td colspan="2" class="meta-label">Nama Mahasiswa</td>
            <td colspan="2"><strong>{{ $mhs->nama_lengkap }}</strong></td>
            <td colspan="2" class="meta-label">Asal Universitas</td>
            <td colspan="2">{{ $mhs->universitas->nama_universitas ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2" class="meta-label">NIM</td>
            <td colspan="2" style="mso-number-format:'\@';">{{ $mhs->nim }}</td>
            <td colspan="2" class="meta-label">Jurusan / Program Studi</td>
            <td colspan="2">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2" class="meta-label">Divisi Penempatan</td>
            <td colspan="2">{{ optional($penempatan->divisi)->nama_divisi ?? '-' }}</td>
            <td colspan="2" class="meta-label">Periode Magang</td>
            <td colspan="2">
                {{ optional($penempatan)->tanggal_mulai ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d/m/Y') : '-' }} s/d 
                {{ optional($penempatan)->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d/m/Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" class="meta-label">Status Magang</td>
            <td colspan="2"><strong>{{ $mhs->status_magang_label }}</strong></td>
            <td colspan="2" class="meta-label">Pembimbing Lapangan</td>
            <td colspan="2">{{ optional($penempatan->pembimbing)->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr><td colspan="8"></td></tr>

        <!-- SUMMARY STATS -->
        <tr>
            <td class="summary-header">Total Hari</td>
            <td class="summary-header">Hadir Tepat</td>
            <td class="summary-header">Terlambat</td>
            <td class="summary-header">Izin / Cuti</td>
            <td class="summary-header">Sakit</td>
            <td class="summary-header">Alpa / Belum</td>
            <td colspan="2" class="summary-header">Total Jam Kerja</td>
        </tr>
        <tr>
            <td class="summary-val">{{ $stats['total'] }}</td>
            <td class="summary-val" style="color: #166534;">{{ $stats['hadir_tepat'] }}</td>
            <td class="summary-val" style="color: #854d0e;">{{ $stats['terlambat'] }}</td>
            <td class="summary-val" style="color: #1e40af;">{{ $stats['izin'] }}</td>
            <td class="summary-val" style="color: #9a3412;">{{ $stats['sakit'] }}</td>
            <td class="summary-val" style="color: #991b1b;">{{ $stats['alpa'] }}</td>
            <td colspan="2" class="summary-val" style="color: #0A5F63;">{{ round($stats['total_jam_kerja'], 1) }} Jam</td>
        </tr>
        <tr><td colspan="8"></td></tr>

        <!-- DATA TABLE -->
        <thead>
            <tr>
                <th class="th-header" style="width: 40px;">No</th>
                <th class="th-header" style="width: 100px;">Tanggal</th>
                <th class="th-header" style="width: 80px;">Jam Masuk</th>
                <th class="th-header" style="width: 80px;">Jam Pulang</th>
                <th class="th-header" style="width: 110px;">Durasi Kerja</th>
                <th class="th-header" style="width: 90px;">Status</th>
                <th class="th-header" style="width: 110px;">Keterlambatan</th>
                <th class="th-header" style="width: 250px;">Catatan / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $idx => $row)
                @php
                    $status = $row->check_in_status;
                    $statusLabel = match($status) {
                        'present' => 'Hadir',
                        'late' => 'Terlambat',
                        'leave' => 'Izin / Cuti',
                        'sick' => 'Sakit',
                        default => 'Alpa'
                    };
                @endphp
                <tr>
                    <td class="td-data text-center">{{ $idx + 1 }}</td>
                    <td class="td-data text-center">{{ $row->attendance_date ? \Carbon\Carbon::parse($row->attendance_date)->format('d/m/Y') : '-' }}</td>
                    <td class="td-data text-center">{{ $row->check_in_at ? \Carbon\Carbon::parse($row->check_in_at)->format('H:i') : '-' }}</td>
                    <td class="td-data text-center">{{ $row->check_out_at ? \Carbon\Carbon::parse($row->check_out_at)->format('H:i') : '-' }}</td>
                    <td class="td-data text-center">{{ $row->total_work_minutes > 0 ? floor($row->total_work_minutes / 60) . 'j ' . ($row->total_work_minutes % 60) . 'm' : '-' }}</td>
                    <td class="td-data text-center"><strong>{{ $statusLabel }}</strong></td>
                    <td class="td-data text-center">{{ $row->late_minutes > 0 ? $row->late_minutes . ' Menit' : '-' }}</td>
                    <td class="td-data">{{ $row->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="td-data text-center">Belum ada riwayat presensi.</td>
                </tr>
            @endforelse
        </tbody>
        <tr><td colspan="8"></td></tr>

        <!-- TANDA TANGAN -->
        <tr>
            <td colspan="3" class="text-center">
                Mengetahui,<br>
                Pembimbing Lapangan PLTU Adipala<br><br><br><br>
                <strong><u>{{ optional($penempatan->pembimbing)->nama_lengkap ?? 'Pembimbing Lapangan' }}</u></strong><br>
                PT PLN Indonesia Power UBP Adipala
            </td>
            <td colspan="2"></td>
            <td colspan="3" class="text-center">
                Cilacap, {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}<br>
                Mahasiswa Magang<br><br><br><br>
                <strong><u>{{ $mhs->nama_lengkap }}</u></strong><br>
                NIM: {{ $mhs->nim }}
            </td>
        </tr>
    </table>
</body>
</html>
