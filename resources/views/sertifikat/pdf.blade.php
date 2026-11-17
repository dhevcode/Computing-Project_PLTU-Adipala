<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Penyelesaian Magang - {{ $nama }}</title>
    {{-- Template sertifikat 2 halaman (A4 landscape) — disesuaikan untuk dompdf: tanpa flex/grid/gradien. --}}
    <style>
        @page { margin: 0; size: A4 landscape; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif; color: #252525; }
        .page { position: relative; width: 297mm; height: 209mm; overflow: hidden; }
        .page-break { page-break-after: always; }

        /* Garis atas & pita bawah (warna PLN) */
        .top-rule { position: absolute; top: 0; left: 0; width: 297mm; height: 5mm; background: #075b73; }
        .top-rule-2 { position: absolute; top: 0; left: 150mm; width: 147mm; height: 5mm; background: #0799a8; }
        .top-mark { position: absolute; top: 0; right: 22mm; width: 14mm; height: 5mm; background: #ffffff; border-left: 2mm solid #0799a8; border-right: 2mm solid #075b73; }
        .bottom-band { position: absolute; left: 0; bottom: 0; width: 297mm; height: 12mm; background: #075b73; }
        .bottom-band-1 { position: absolute; left: 0; bottom: 0; width: 80mm; height: 12mm; background: #eaf5f5; }
        .bottom-band-2 { position: absolute; left: 80mm; bottom: 0; width: 90mm; height: 12mm; background: #0799a8; }
        .bottom-mark { position: absolute; left: 45mm; bottom: 0; width: 7mm; height: 12mm; background: #ffffff; border-left: 2mm solid #0799a8; border-right: 1.5mm solid #075b73; }
        .footer-url { position: absolute; right: 18mm; bottom: 3.6mm; color: #ffffff; font-size: 8pt; letter-spacing: 0.5px; }

        .logo { position: absolute; top: 11mm; right: 19mm; width: 30mm; text-align: center; }
        .logo img { width: 30mm; }
        .logo-text { font-size: 8pt; color: #075b73; font-weight: bold; }
        .doc-code { position: absolute; top: 13mm; left: 19mm; font-size: 8.5pt; color: #5d666b; }

        .draf { position: absolute; top: 85mm; left: 40mm; width: 220mm; text-align: center; color: #dce6e8; font-size: 46pt; font-weight: bold; letter-spacing: 6px; transform: rotate(-18deg); }

        /* Halaman 1 */
        .isi-sertifikat { position: absolute; top: 20mm; left: 28mm; width: 241mm; text-align: center; }
        .judul { margin: 0 0 3mm; color: #075b73; font-family: 'DejaVu Serif', Georgia, serif; font-size: 30pt; font-weight: bold; letter-spacing: 2px; }
        .intro { margin: 0 0 5mm; font-family: 'DejaVu Serif', Georgia, serif; font-size: 13pt; }
        .penerima { margin: 0 0 2mm; font-size: 23pt; font-weight: bold; text-transform: uppercase; }
        .identitas { margin: 0 0 5mm; font-size: 11pt; }
        .badan { font-size: 11.5pt; line-height: 1.55; }
        .kegiatan { margin: 2mm 0; font-size: 15pt; font-weight: bold; line-height: 1.35; text-transform: uppercase; }
        .periode { margin-top: 1.5mm; font-weight: bold; }
        .hasil { margin: 4mm 0 0; font-size: 11pt; }
        .ttd-1 { position: absolute; right: 30mm; top: 141mm; width: 95mm; text-align: center; font-size: 10pt; line-height: 1.45; }
        .ruang-ttd { height: 14mm; }
        .nama-ttd { font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .peran-ttd { color: #5d666b; }

        /* Halaman 2 */
        .isi-nilai { position: absolute; top: 14mm; left: 19mm; width: 259mm; }
        .judul-nilai { margin: 0 0 1.5mm; text-align: center; color: #075b73; font-family: 'DejaVu Serif', Georgia, serif; font-size: 18pt; font-weight: bold; }
        .sub-nilai { margin: 0 0 3.5mm; text-align: center; font-size: 10.5pt; text-transform: uppercase; }
        .data-peserta { width: 100%; margin-bottom: 3mm; font-size: 9.5pt; }
        .data-peserta td { padding: 0.6mm 0; border: 0; }
        table.nilai { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
        table.nilai th, table.nilai td { border: 1px solid #cbd5d8; padding: 1.5mm 2.5mm; }
        table.nilai th { background: #075b73; color: #ffffff; }
        .tengah { text-align: center; }
        table.rekap { width: 100%; border-collapse: collapse; margin-top: 3mm; font-size: 10pt; font-weight: bold; background: #e8f6f7; }
        table.rekap td { border: 1px solid #8fcbd0; padding: 1.8mm 2.5mm; }
        table.rekap td.nilai-rekap { text-align: center; width: 68mm; font-size: 11.5pt; }
        table.bawah { width: 100%; margin-top: 3mm; font-size: 9pt; }
        table.bawah td { vertical-align: top; border: 0; }
        .legenda-judul { font-weight: bold; margin-bottom: 1mm; }
        .legenda div { line-height: 1.5; }
        .ttd-2 { text-align: center; font-size: 9.5pt; line-height: 1.4; }
        .ruang-ttd-kecil { height: 12mm; }
    </style>
</head>
<body>

{{-- ================= HALAMAN 1: SERTIFIKAT ================= --}}
<div class="page page-break">
    <div class="top-rule"></div><div class="top-rule-2"></div><div class="top-mark"></div>
    <div class="doc-code">No. {{ $kode }}</div>
    <div class="logo">
        @if($logo)<img src="{{ $logo }}" alt="PLN">@else<div class="logo-text">PLN<br>Indonesia Power</div>@endif
    </div>
    @if($draf)<div class="draf">PRATINJAU</div>@endif

    <div class="isi-sertifikat">
        <h1 class="judul">SERTIFIKAT</h1>
        <p class="intro">Dengan ini menyatakan bahwa:</p>
        <h2 class="penerima">{{ $nama }}</h2>
        <p class="identitas">NIM: <strong>{{ $nim }}</strong>@if($universitas) &nbsp;&bull;&nbsp; {{ $universitas }}@endif</p>
        <div class="badan">
            telah menyelesaikan kegiatan
            <div class="kegiatan">MAGANG<br>DI PLTU ADIPALA CILACAP</div>
            yang diselenggarakan oleh <strong>{{ $instansi }}</strong>
            <div class="periode">pada tanggal {{ $tanggal_mulai }} sampai dengan {{ $tanggal_selesai }}</div>
            <p class="hasil">
                dan telah memenuhi persyaratan penyelesaian program magang
                dengan predikat <strong>{{ $grade }} ({{ $predikat }})</strong>.
            </p>
        </div>
    </div>

    <div class="ttd-1">
        {{ $kota }}, {{ $tanggal_terbit }}<br>
        Mengetahui,<br>
        <strong>{{ $jabatan }}</strong>
        <div class="ruang-ttd"></div>
        <div class="nama-ttd">{{ $penandatangan }}</div>
        @if($identitas)<div class="peran-ttd">{{ $identitas }}</div>@endif
    </div>

    <div class="bottom-band"></div><div class="bottom-band-1"></div><div class="bottom-band-2"></div><div class="bottom-mark"></div>
    <div class="footer-url">{{ $website }}</div>
</div>

{{-- ================= HALAMAN 2: DAFTAR NILAI ================= --}}
<div class="page">
    <div class="top-rule"></div><div class="top-rule-2"></div><div class="top-mark"></div>
    <div class="logo">
        @if($logo)<img src="{{ $logo }}" alt="PLN">@else<div class="logo-text">PLN<br>Indonesia Power</div>@endif
    </div>
    @if($draf)<div class="draf">PRATINJAU</div>@endif

    <div class="isi-nilai">
        <h1 class="judul-nilai">DAFTAR NILAI KEGIATAN MAGANG</h1>
        <p class="sub-nilai">Program Magang di PLTU Adipala Cilacap</p>

        <table class="data-peserta">
            <tr>
                <td style="width: 32mm;">Nama</td><td style="width: 95mm;">: <strong>{{ $nama }}</strong></td>
                <td style="width: 32mm;">No. Sertifikat</td><td>: {{ $kode }}</td>
            </tr>
            <tr>
                <td>NIM</td><td>: {{ $nim }}</td>
                <td>Periode</td><td>: {{ $tanggal_mulai }} s/d {{ $tanggal_selesai }}</td>
            </tr>
            @if($universitas || $divisi)
            <tr>
                <td>Asal Instansi</td><td>: {{ $universitas ?? '-' }}</td>
                <td>Bidang / Divisi</td><td>: {{ $divisi ?? '-' }}</td>
            </tr>
            @endif
        </table>

        <table class="nilai">
            <thead>
                <tr>
                    <th class="tengah" style="width: 12mm;">No.</th>
                    <th style="text-align: left;">Aspek Penilaian</th>
                    <th class="tengah" style="width: 34mm;">Nilai</th>
                    <th class="tengah" style="width: 34mm;">Nilai Huruf</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aspek as $i => $a)
                    <tr>
                        <td class="tengah">{{ $i + 1 }}</td>
                        <td>{{ $a['nama'] }}</td>
                        <td class="tengah">{{ rtrim(rtrim(number_format($a['nilai'], 2, ',', ''), '0'), ',') }}</td>
                        <td class="tengah">{{ $a['huruf'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="tengah">Belum ada rincian penilaian.</td></tr>
                @endforelse
            </tbody>
        </table>

        <table class="rekap">
            <tr><td>Nilai Akhir</td><td class="nilai-rekap">{{ rtrim(rtrim(number_format($nilai_akhir, 2, ',', ''), '0'), ',') }}</td></tr>
            <tr><td>Predikat</td><td class="nilai-rekap">{{ $grade }} ({{ $predikat }})</td></tr>
        </table>

        <table class="bawah">
            <tr>
                <td class="legenda" style="width: 60%;">
                    <div class="legenda-judul">Keterangan Predikat:</div>
                    @foreach($skala as $s)
                        <div>{{ $s['rentang'] }} &mdash; {{ $s['grade'] }} ({{ $s['keterangan'] }})</div>
                    @endforeach
                </td>
                <td class="ttd-2">
                    {{ $kota }}, {{ $tanggal_terbit }}<br>
                    <strong>{{ $jabatan }}</strong>
                    <div class="ruang-ttd-kecil"></div>
                    <strong class="nama-ttd">{{ $penandatangan }}</strong>
                    @if($identitas)<div class="peran-ttd">{{ $identitas }}</div>@endif
                </td>
            </tr>
        </table>
    </div>

    <div class="bottom-band"></div><div class="bottom-band-1"></div><div class="bottom-band-2"></div><div class="bottom-mark"></div>
    <div class="footer-url">{{ $website }}</div>
</div>

</body>
</html>
