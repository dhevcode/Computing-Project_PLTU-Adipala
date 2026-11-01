<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peserta Magang - {{ $mahasiswa->nama_lengkap }} - Superadmin</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/superadmin/dashboard.css') }}">
    <style>
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 24px;
        }
        .profile-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 28px 24px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .profile-avatar-lg {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0A5F63 0%, #00828A 100%);
            color: #ffffff;
            font-size: 32px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 6px 16px rgba(10, 95, 99, 0.25);
        }
        .info-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 20px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .info-card h4 {
            font-size: 15px;
            font-weight: 700;
            color: #0A5F63;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1.5px solid #F1F5F9;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 12px;
            font-size: 13.5px;
        }
        .detail-row:last-child { margin-bottom: 0; }
        .detail-label {
            width: 180px;
            color: #64748B;
            font-weight: 500;
            flex-shrink: 0;
        }
        .detail-val {
            color: #1E293B;
            font-weight: 600;
            flex: 1;
        }
        .doc-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 8px;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            color: #1E293B;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .doc-btn:hover {
            border-color: #0A5F63;
            background: #F0FDFD;
            color: #0A5F63;
        }
        @media (max-width: 900px) {
            .detail-grid { grid-template-columns: 1fr; }
            .detail-row { flex-direction: column; gap: 4px; }
            .detail-label { width: 100%; }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="header">
        <div class="header-left">
            <div class="logo-box">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
            </div>
            <div class="header-title">
                <h2>Monitoring Mahasiswa PKL <span>Superadmin</span></h2>
                <p>Detail profil peserta magang & verifikasi berkas.</p>
            </div>
        </div>
        <div class="header-right">
            <a href="{{ route('superadmin.peserta') }}" class="btn-header-action" title="Kembali">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            <a href="{{ route('superadmin.peserta.edit', $mahasiswa->id_mhs) }}" class="btn-header-nav" style="background: rgba(255,255,255,0.2);">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Data</span>
            </a>

            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-header-icon" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    @php
        $pengajuan = $mahasiswa->latestPengajuan;
        $userAcc = $mahasiswa->user;
    @endphp

    <!-- CONTENT -->
    <div class="content">

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 20px;">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" style="background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="detail-grid">
            <!-- LEFT COLUMN: PROFIL & STATUS AKUN -->
            <div>
                <div class="profile-card">
                    <div class="profile-avatar-lg">
                        {{ strtoupper(substr($mahasiswa->nama_lengkap ?? 'M', 0, 1)) }}
                    </div>
                    <h3 style="font-size: 19px; font-weight: 800; color: #0F172A; margin-bottom: 4px;">
                        {{ $mahasiswa->nama_lengkap }}
                    </h3>
                    <p style="font-size: 13px; color: #64748B; margin-bottom: 14px;">
                        NIM: <strong>{{ $mahasiswa->nim }}</strong>
                    </p>

                    @if($pengajuan)
                        <div style="margin-bottom: 16px;">
                            @if($pengajuan->status === 'Sedang Magang')
                                <span class="badge badge-active" style="padding: 6px 16px; font-size: 12.5px;">Sedang Magang</span>
                            @elseif($pengajuan->status === 'Akan Magang')
                                <span class="badge" style="background: #EEF2FF; color: #4338CA; padding: 6px 16px; font-size: 12.5px;">Akan Magang</span>
                            @else
                                <span class="badge badge-inactive" style="padding: 6px 16px; font-size: 12.5px;">Selesai Magang</span>
                            @endif
                        </div>
                    @endif

                    <hr style="border: none; border-top: 1px solid #E2E8F0; margin: 16px 0;">

                    <div style="text-align: left; font-size: 13px;">
                        <div style="margin-bottom: 8px;">
                            <span style="color: #64748B;">Asal Kampus:</span><br>
                            <strong>{{ $mahasiswa->universitas->nama_universitas ?? '-' }}</strong>
                        </div>
                        <div style="margin-bottom: 8px;">
                            <span style="color: #64748B;">Program Studi:</span><br>
                            <strong>{{ $mahasiswa->jurusan->nama_jurusan ?? '-' }}</strong>
                        </div>
                        <div>
                            <span style="color: #64748B;">Divisi Magang:</span><br>
                            <strong style="color: #0A5F63;">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</strong>
                        </div>
                    </div>
                </div>

                <!-- KARTU STATUS AKUN PORTAL -->
                <div class="profile-card" style="margin-top: 20px; text-align: left;">
                    <h4 style="font-size: 14px; font-weight: 700; color: #0A5F63; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-key"></i> Status Akun Portal Peserta
                    </h4>

                    @if($userAcc)
                        <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 10px; padding: 14px; margin-bottom: 14px;">
                            <div style="font-size: 12px; color: #166534; font-weight: 700; margin-bottom: 6px;">
                                <i class="fa-solid fa-circle-check"></i> AKUN AKTIF
                            </div>
                            <div style="font-size: 13px; line-height: 1.6;">
                                Username: <strong>{{ $userAcc->username }}</strong><br>
                                Email: <strong>{{ $userAcc->email }}</strong><br>
                                Role: <span class="badge badge-role" style="padding: 2px 8px;">{{ $userAcc->role }}</span>
                            </div>
                        </div>

                        <!-- Reset Password Form -->
                        <form action="{{ route('superadmin.peserta.reset_password', $mahasiswa->id_mhs) }}" method="POST" onsubmit="return confirm('Reset password akun ini menjadi password baru?');">
                            @csrf
                            <div style="margin-bottom: 10px;">
                                <label style="font-size: 12px; font-weight: 600; color: #475569;">Ganti / Reset Password:</label>
                                <input type="text" name="new_password" value="mhs123" class="form-control" style="width: 100%; height: 36px; padding: 0 10px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-size: 13px;" required>
                            </div>
                            <button type="submit" class="btn-view-mhs" style="width: 100%; justify-content: center; height: 36px;">
                                <i class="fa-solid fa-rotate-left"></i> Reset Password
                            </button>
                        </form>
                    @else
                        <div style="background: #FEF3C7; border: 1px solid #FDE68A; border-radius: 10px; padding: 14px; margin-bottom: 14px;">
                            <div style="font-size: 12px; color: #92400E; font-weight: 700; margin-bottom: 4px;">
                                <i class="fa-solid fa-triangle-exclamation"></i> BELUM MEMILIKI AKUN
                            </div>
                            <p style="font-size: 12px; color: #B45309; line-height: 1.5; margin: 0;">
                                Mahasiswa ini belum dapat login ke portal absensi & logbook. Anda dapat membuatkan akunnya sekarang.
                            </p>
                        </div>

                        <!-- Buat Akun Form -->
                        <form action="{{ route('superadmin.peserta.buat_akun', $mahasiswa->id_mhs) }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 10px;">
                                <label style="font-size: 12px; font-weight: 600; color: #475569;">Tentukan Password Awal:</label>
                                <input type="text" name="password" value="mhs123" class="form-control" style="width: 100%; height: 36px; padding: 0 10px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-size: 13px;" required>
                                <small style="font-size: 11px; color: #64748B;">Username otomatis: <strong>{{ $mahasiswa->nim }}</strong></small>
                            </div>
                            <button type="submit" class="btn-header-action" style="background: #0A5F63; color: #fff; width: 100%; justify-content: center; height: 38px;">
                                <i class="fa-solid fa-user-plus"></i> Buatkan Akun Portal
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- RIGHT COLUMN: INFORMASI LENGKAP & BERKAS -->
            <div>
                <!-- INFO KONTAK -->
                <div class="info-card">
                    <h4><i class="fa-regular fa-address-card"></i> Informasi Kontak & Pribadi</h4>
                    <div class="detail-row">
                        <div class="detail-label">Nama Lengkap</div>
                        <div class="detail-val">{{ $mahasiswa->nama_lengkap }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Jenis Kelamin</div>
                        <div class="detail-val">{{ $mahasiswa->jenis_kelamin }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Email Mahasiswa</div>
                        <div class="detail-val">{{ $mahasiswa->email }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">No. Telepon / WhatsApp</div>
                        <div class="detail-val">{{ $mahasiswa->no_telepon }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Kontak Darurat (Wali)</div>
                        <div class="detail-val">
                            {{ $mahasiswa->emergency_contact_name ?: '-' }} 
                            @if($mahasiswa->emergency_contact_phone)
                                ({{ $mahasiswa->emergency_contact_phone }})
                            @endif
                        </div>
                    </div>
                </div>

                <!-- INFO MAGANG & PEMBIMBING -->
                <div class="info-card">
                    <h4><i class="fa-solid fa-briefcase"></i> Penempatan & Pembimbing Lapangan</h4>
                    <div class="detail-row">
                        <div class="detail-label">Divisi Penempatan</div>
                        <div class="detail-val" style="color: #0A5F63;">
                            {{ $pengajuan->divisi->nama_divisi ?? '-' }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Pembimbing Lapangan</div>
                        <div class="detail-val">
                            @if($pengajuan && ($pengajuan->pembimbing || $pengajuan->nama_pembimbing))
                                <span style="font-weight: 700;">{{ $pengajuan->pembimbing->nama_lengkap ?? $pengajuan->nama_pembimbing }}</span>
                                @if($pengajuan->pembimbing)
                                    <br><small style="color: #64748B;">NIP: {{ $pengajuan->pembimbing->username }} &bull; {{ $pengajuan->pembimbing->email }}</small>
                                @endif
                            @else
                                <span style="color: #94A3B8; font-style: italic;">Belum ada pembimbing yang ditugaskan</span>
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Periode Pelaksanaan</div>
                        <div class="detail-val">
                            @if($pengajuan)
                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d F Y') }}
                                <br><small style="color: #64748B;">Durasi: {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($pengajuan->tanggal_selesai)) + 1 }} Hari</small>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>

                <!-- DOKUMEN PERSYARATAN -->
                <div class="info-card">
                    <h4><i class="fa-regular fa-folder-open"></i> Berkas Dokumen Persyaratan</h4>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        @if($pengajuan && $pengajuan->cv)
                            <a href="{{ asset('uploads/cv/' . $pengajuan->cv) }}" target="_blank" class="doc-btn">
                                <i class="fa-regular fa-file-user" style="color: #059669; font-size: 16px;"></i>
                                <span>Lihat Curriculum Vitae (CV)</span>
                            </a>
                        @else
                            <div class="doc-btn" style="opacity: 0.6; cursor: not-allowed;">
                                <i class="fa-regular fa-file-user"></i>
                                <span>CV Tidak Ada</span>
                            </div>
                        @endif

                        @if($pengajuan && $pengajuan->proposal)
                            <a href="{{ asset('uploads/proposal/' . $pengajuan->proposal) }}" target="_blank" class="doc-btn">
                                <i class="fa-regular fa-file-lines" style="color: #2563EB; font-size: 16px;"></i>
                                <span>Lihat Proposal Magang</span>
                            </a>
                        @else
                            <div class="doc-btn" style="opacity: 0.6; cursor: not-allowed;">
                                <i class="fa-regular fa-file-lines"></i>
                                <span>Proposal Tidak Ada</span>
                            </div>
                        @endif

                        @if($pengajuan && $pengajuan->surat_permohonan)
                            <a href="{{ asset('uploads/surat_permohonan/' . $pengajuan->surat_permohonan) }}" target="_blank" class="doc-btn">
                                <i class="fa-regular fa-file-pdf" style="color: #DC2626; font-size: 16px;"></i>
                                <span>Lihat Surat Pengantar Kampus</span>
                            </a>
                        @else
                            <div class="doc-btn" style="opacity: 0.6; cursor: not-allowed;">
                                <i class="fa-regular fa-file-pdf"></i>
                                <span>Surat Pengantar Tidak Ada</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- AKSI TOMBOL FOOTER -->
                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                    <a href="{{ route('superadmin.peserta') }}" class="btn-header-action" style="background: #E2E8F0; color: #475569;">
                        Kembali
                    </a>
                    <a href="{{ route('superadmin.peserta.edit', $mahasiswa->id_mhs) }}" class="btn-header-action" style="background: #0A5F63; color: #ffffff;">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Data Peserta
                    </a>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
