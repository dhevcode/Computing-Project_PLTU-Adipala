<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pengajuan Pendaftaran Magang Baru</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1e293b; line-height: 1.6;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f1f5f9; padding: 30px 15px;">
        <tr>
            <td align="center">

                <!-- Main Container -->
                <table width="640" cellpadding="0" cellspacing="0" border="0" style="max-width: 640px; width: 100%; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">

                    <!-- Corporate Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0b5cab 0%, #032b53 100%); padding: 30px 36px; text-align: left;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #93c5fd; font-weight: 700; margin-bottom: 4px;">
                                            PT PLN INDONESIA POWER &bull; UBP ADIPALA
                                        </div>
                                        <div style="font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: -0.3px;">
                                            Pengajuan Magang / PKL Baru
                                        </div>
                                        <div style="font-size: 13px; color: #cbd5e1; margin-top: 4px;">
                                            Sistem Monitoring & Pendaftaran Magang Online
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Status Notification Banner -->
                    <tr>
                        <td style="padding: 24px 36px 12px 36px;">
                            <div style="background-color: #eff6ff; border-left: 4px solid #2563eb; padding: 14px 18px; border-radius: 6px;">
                                <div style="font-size: 14px; font-weight: 700; color: #1e40af; margin-bottom: 2px;">
                                    Status: Menunggu Verifikasi Berkas
                                </div>
                                <div style="font-size: 13px; color: #3b82f6;">
                                    Pengajuan pendaftaran magang baru telah diterima sistem pada <strong>{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</strong>.
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Section 1: Data Calon Peserta -->
                    <tr>
                        <td style="padding: 16px 36px 8px 36px;">
                            <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px 0; border-bottom: 2px solid #e2e8f0; padding-bottom: 6px;">
                                👤 1. Data Diri Calon Peserta
                            </h3>

                            <table width="100%" cellpadding="6" cellspacing="0" border="0" style="font-size: 13.5px;">
                                <tr>
                                    <td width="35%" style="color: #64748b; font-weight: 600;">Nama Lengkap</td>
                                    <td width="65%" style="color: #0f172a; font-weight: 700;">{{ $mahasiswa->nama_lengkap }}</td>
                                </tr>
                                <tr style="background-color: #f8fafc;">
                                    <td style="color: #64748b; font-weight: 600;">NIM / NIS</td>
                                    <td style="color: #0f172a; font-weight: 600;">{{ $mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #64748b; font-weight: 600;">Jenis Kelamin</td>
                                    <td style="color: #0f172a;">{{ $mahasiswa->jenis_kelamin }}</td>
                                </tr>
                                <tr style="background-color: #f8fafc;">
                                    <td style="color: #64748b; font-weight: 600;">Alamat Email</td>
                                    <td style="color: #0b5cab; font-weight: 600;">
                                        <a href="mailto:{{ $mahasiswa->email }}" style="color: #0b5cab; text-decoration: none;">{{ $mahasiswa->email }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748b; font-weight: 600;">Nomor HP / WhatsApp</td>
                                    <td style="color: #0f172a; font-weight: 600;">{{ $mahasiswa->no_telepon }}</td>
                                </tr>
                                @if(!empty($mahasiswa->emergency_contact_name) || !empty($mahasiswa->emergency_contact_phone))
                                <tr style="background-color: #f8fafc;">
                                    <td style="color: #64748b; font-weight: 600;">Kontak Darurat</td>
                                    <td style="color: #0f172a;">
                                        {{ $mahasiswa->emergency_contact_name ?? '-' }} ({{ $mahasiswa->emergency_contact_phone ?? '-' }})
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    <!-- Section 2: Data Akademik & Penempatan -->
                    <tr>
                        <td style="padding: 16px 36px 8px 36px;">
                            <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px 0; border-bottom: 2px solid #e2e8f0; padding-bottom: 6px;">
                                🎓 2. Asal Akademik & Rencana Magang
                            </h3>

                            <table width="100%" cellpadding="6" cellspacing="0" border="0" style="font-size: 13.5px;">
                                <tr>
                                    <td width="35%" style="color: #64748b; font-weight: 600;">Universitas / Kampus</td>
                                    <td width="65%" style="color: #0f172a; font-weight: 700;">
                                        {{ $mahasiswa->universitas->nama_universitas ?? '-' }}
                                    </td>
                                </tr>
                                <tr style="background-color: #f8fafc;">
                                    <td style="color: #64748b; font-weight: 600;">Jurusan / Program Studi</td>
                                    <td style="color: #0f172a; font-weight: 600;">
                                        {{ $mahasiswa->jurusan->nama_jurusan ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748b; font-weight: 600;">Divisi Penempatan Dituju</td>
                                    <td style="color: #0b5cab; font-weight: 700;">
                                        {{ $pengajuan->divisi->nama_divisi ?? 'Divisi Terkait' }}
                                    </td>
                                </tr>
                                <tr style="background-color: #f8fafc;">
                                    <td style="color: #64748b; font-weight: 600;">Rencana Periode Magang</td>
                                    <td style="color: #0f172a; font-weight: 600;">
                                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d F Y') }} 
                                        &nbsp;s/d&nbsp; 
                                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}
                                        <span style="font-size: 12px; color: #64748b; font-weight: 500;">
                                            ({{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($pengajuan->tanggal_selesai)) + 1 }} hari)
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Section 3: Berkas Persyaratan Terlampir -->
                    <tr>
                        <td style="padding: 16px 36px 12px 36px;">
                            <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px 0; border-bottom: 2px solid #e2e8f0; padding-bottom: 6px;">
                                📎 3. Berkas Dokumen Persyaratan
                            </h3>

                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 18px;">
                                <div style="font-size: 13px; color: #475569; margin-bottom: 8px;">
                                    Seluruh berkas dokumen pendaftaran telah <strong>dilampirkan pada email ini</strong>:
                                </div>
                                <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #1e293b; line-height: 1.8;">
                                    <li><strong>Surat Permohonan / Pengantar Kampus:</strong> {{ $pengajuan->surat_permohonan ?? 'Tidak ada berkas' }}</li>
                                    <li><strong>Proposal Magang:</strong> {{ $pengajuan->proposal ?? 'Tidak ada berkas' }}</li>
                                    <li><strong>Curriculum Vitae (CV):</strong> {{ $pengajuan->cv ?? 'Tidak ada berkas' }}</li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Call To Action Button -->
                    <tr>
                        <td style="padding: 20px 36px 30px 36px; text-align: center;">
                            <a href="{{ route('admin.detail', ['id' => $pengajuan->id_pengajuan ?? 1]) }}" style="display: inline-block; background-color: #0b5cab; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 700; box-shadow: 0 3px 8px rgba(11,92,171,0.3);">
                                Buka & Verifikasi di Portal Admin &rarr;
                            </a>
                            <div style="font-size: 12px; color: #94a3b8; margin-top: 10px;">
                                Klik tombol di atas untuk menyetujui, menolak, atau menugaskan pembimbing lapangan.
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 36px; text-align: center; font-size: 12px; color: #64748b; line-height: 1.6;">
                            <div>
                                <strong>PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala</strong><br>
                                Jl. Laut Desa Bunton, Kecamatan Adipala, Kabupaten Cilacap, Jawa Tengah 53271
                            </div>
                            <div style="margin-top: 8px; color: #94a3b8;">
                                Email ini dikirimkan secara otomatis oleh Sistem Monitoring Magang ke kotak masuk Outlook <em>internship.ubpadp@plnindonesiapower.co.id</em>.
                            </div>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
