<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @php
    echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Logbook Kegiatan</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
    @endphp
    <style>
        body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
        .header-title { font-size: 14pt; font-weight: bold; color: #0A5F63; text-align: center; }
        .sub-title { font-size: 11pt; font-weight: bold; text-align: center; color: #334155; }
        .meta-label { font-weight: bold; background-color: #F1F5F9; }
        .th-header { background-color: #0A5F63; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000; }
        .td-data { border: 1px solid #CBD5E1; vertical-align: top; }
        .text-center { text-align: center; }
        .summary-header { background-color: #E2E8F0; font-weight: bold; text-align: center; border: 1px solid #000000; }
        .summary-val { font-weight: bold; font-size: 12pt; text-align: center; border: 1px solid #000000; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="7" class="header-title">PT PLN INDONESIA POWER UBP JAWA TENGAH 2 ADIPALA</td>
        </tr>
        <tr>
            <td colspan="7" class="sub-title">LOGBOOK & JURNAL KEGIATAN HARIAN MAHASISWA MAGANG</td>
        </tr>
        <tr><td colspan="7"></td></tr>

        <!-- BIODATA -->
        <tr>
            <td class="meta-label">Nama Mahasiswa</td>
            <td colspan="2"><strong>{{ $mhs->nama_lengkap }}</strong></td>
            <td class="meta-label">Asal Universitas</td>
            <td colspan="3">{{ $mhs->universitas->nama_universitas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">NIM</td>
            <td colspan="2" style="mso-number-format:'\@';">{{ $mhs->nim }}</td>
            <td class="meta-label">Jurusan / Program Studi</td>
            <td colspan="3">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Divisi Penempatan</td>
            <td colspan="2">{{ optional($penempatan->divisi)->nama_divisi ?? '-' }}</td>
            <td class="meta-label">Periode Magang</td>
            <td colspan="3">
                {{ optional($penempatan)->tanggal_mulai ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d/m/Y') : '-' }} s/d 
                {{ optional($penempatan)->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d/m/Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="meta-label">Status Magang</td>
            <td colspan="2"><strong>{{ $mhs->status_magang_label }}</strong></td>
            <td class="meta-label">Pembimbing Lapangan</td>
            <td colspan="3">{{ optional($penempatan->pembimbing)->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr><td colspan="7"></td></tr>

        <!-- SUMMARY STATS -->
        <tr>
            <td class="summary-header">Total Logbook</td>
            <td class="summary-header" colspan="2">Disetujui Pembimbing</td>
            <td class="summary-header">Menunggu Review</td>
            <td class="summary-header">Perlu Revisi</td>
            <td class="summary-header" colspan="2">Draft Tersimpan</td>
        </tr>
        <tr>
            <td class="summary-val">{{ $stats['total'] }}</td>
            <td class="summary-val" colspan="2" style="color: #166534;">{{ $stats['approved'] }}</td>
            <td class="summary-val" style="color: #1e40af;">{{ $stats['submitted'] }}</td>
            <td class="summary-val" style="color: #854d0e;">{{ $stats['revision'] }}</td>
            <td class="summary-val" colspan="2" style="color: #475569;">{{ $stats['draft'] }}</td>
        </tr>
        <tr><td colspan="7"></td></tr>

        <!-- DATA TABLE -->
        <thead>
            <tr>
                <th class="th-header" style="width: 40px;">No</th>
                <th class="th-header" style="width: 100px;">Tanggal</th>
                <th class="th-header" style="width: 110px;">Jam Kerja</th>
                <th class="th-header" style="width: 250px;">Aktivitas / Uraian Tugas</th>
                <th class="th-header" style="width: 200px;">Hasil yang Dicapai</th>
                <th class="th-header" style="width: 200px;">Kendala & Solusi</th>
                <th class="th-header" style="width: 120px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logbooks as $idx => $row)
                @php
                    $status = $row->status ?? 'draft';
                    $statusLabel = match($status) {
                        'approved' => 'Disetujui',
                        'submitted' => 'Menunggu Review',
                        'revision_required' => 'Perlu Revisi',
                        default => 'Draft'
                    };
                @endphp
                <tr>
                    <td class="td-data text-center">{{ $idx + 1 }}</td>
                    <td class="td-data text-center">{{ $row->logbook_date ? \Carbon\Carbon::parse($row->logbook_date)->format('d/m/Y') : '-' }}</td>
                    <td class="td-data text-center">{{ substr($row->start_time ?? '00:00', 0, 5) }} - {{ substr($row->end_time ?? '00:00', 0, 5) }} WIB</td>
                    <td class="td-data">
                        <strong>{{ $row->title }}</strong><br>
                        {{ $row->activity }}
                    </td>
                    <td class="td-data">{{ $row->result ?: '-' }}</td>
                    <td class="td-data">
                        @if($row->obstacle) Kendala: {{ $row->obstacle }}<br>@endif
                        @if($row->solution) Solusi: {{ $row->solution }}@endif
                        @if(!$row->obstacle && !$row->solution)-@endif
                    </td>
                    <td class="td-data text-center"><strong>{{ $statusLabel }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="td-data text-center">Belum ada catatan logbook kegiatan.</td>
                </tr>
            @endforelse
        </tbody>
        <tr><td colspan="7"></td></tr>

        <!-- TANDA TANGAN -->
        <tr>
            <td colspan="3" class="text-center">
                Mengetahui & Menyetujui,<br>
                Pembimbing Lapangan PLTU Adipala<br><br><br><br>
                <strong><u>{{ optional($penempatan->pembimbing)->nama_lengkap ?? 'Pembimbing Lapangan' }}</u></strong><br>
                PT PLN Indonesia Power UBP Adipala
            </td>
            <td></td>
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
