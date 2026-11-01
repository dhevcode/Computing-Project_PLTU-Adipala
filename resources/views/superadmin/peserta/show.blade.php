@extends('superadmin.layouts.app')

@section('title', 'Detail Peserta - ' . $mahasiswa->nama_lengkap)
@section('page_title', 'Detail Peserta Magang')
@section('page_subtitle', 'Detail profil peserta magang & verifikasi berkas persyaratan.')

@push('styles')
<style>
    /* =========================================
       LAYOUT UTAMA
       ========================================= */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 24px;
        align-items: start;
    }

    /* =========================================
       CARD PROFIL (KIRI)
       ========================================= */
    .profile-card {
        background: #ffffff;
        border-radius: var(--radius);
        padding: 32px 24px;
        text-align: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .profile-avatar-lg {
        width: 86px;
        height: 86px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #ffffff;
        font-size: 34px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        box-shadow: 0 8px 20px rgba(0, 162, 185, 0.25);
    }
    .profile-card h3 {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 6px;
    }
    .profile-card p {
        font-size: 13.5px;
        color: var(--muted);
        margin-bottom: 20px;
    }
    .profile-divider {
        border: none;
        border-top: 1px solid var(--border);
        margin: 20px 0;
    }
    .profile-meta {
        text-align: left;
        font-size: 13px;
        line-height: 1.5;
    }
    .profile-meta > div {
        margin-bottom: 12px;
    }
    .profile-meta > div:last-child {
        margin-bottom: 0;
    }

    /* =========================================
       CARD INFORMASI (KANAN)
       ========================================= */
    .info-card {
        background: #ffffff;
        border-radius: var(--radius);
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .info-card h4 {
        font-size: 15px;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1.5px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .detail-row {
        display: flex;
        margin-bottom: 14px;
        font-size: 13.5px;
    }
    .detail-row:last-child { margin-bottom: 0; }
    .detail-label {
        width: 200px;
        color: var(--muted);
        font-weight: 500;
        flex-shrink: 0;
    }
    .detail-val {
        color: var(--text);
        font-weight: 600;
        flex: 1;
    }

    /* =========================================
       TOMBOL & BADGE SPESIFIK
       ========================================= */
    .doc-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 18px;
        border-radius: 8px;
        background: #F8FAFC;
        border: 1.5px solid var(--border);
        color: var(--text);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .doc-btn:hover {
        border-color: var(--primary);
        background: var(--primary-soft);
        color: var(--primary-dark);
        transform: translateY(-2px);
    }
    .doc-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f1f5f9;
        pointer-events: none;
    }

    .status-alert {
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
    }
    .status-alert-success {
        background: var(--success-bg);
        border: 1px solid #BBF7D0;
    }
    .status-alert-warning {
        background: #FEF3C7;
        border: 1px solid #FDE68A;
    }

    /* Style input untuk Reset/Buat Akun */
    .custom-input {
        width: 100%;
        height: 40px;
        padding: 0 14px;
        font-family: inherit;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13.5px;
        transition: border-color 0.2s;
        outline: none;
    }
    .custom-input:focus { border-color: var(--primary); }

    .btn-action-full {
        background: var(--primary);
        color: #fff;
        border: none;
        height: 40px;
        width: 100%;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        transition: background 0.2s;
    }
    .btn-action-full:hover { background: var(--primary-dark); }

    .btn-outline-full {
        background: transparent;
        color: var(--primary);
        border: 1.5px solid var(--primary);
        height: 40px;
        width: 100%;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-outline-full:hover { background: var(--primary-soft); }

    /* =========================================
       RESPONSIVE
       ========================================= */
    @media (max-width: 992px) {
        .detail-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 760px) {
        .detail-row { flex-direction: column; gap: 6px; margin-bottom: 20px; }
        .detail-label { width: 100%; font-size: 12.5px; }
        .detail-val { font-size: 14px; }
        .doc-btn { width: 100%; justify-content: center; }

        .footer-actions { flex-direction: column-reverse; }
        .footer-actions a { width: 100%; justify-content: center; text-align: center; }
    }
</style>
@endpush

@section('content')

    @php
        $pengajuan = $mahasiswa->latestPengajuan;
        $userAcc = $mahasiswa->user;
    @endphp

    <div class="detail-grid">
        <!-- =========================================
             KOLOM KIRI: PROFIL & STATUS AKUN
             ========================================= -->
        <div>
            <!-- Kartu Profil Utama -->
            <div class="profile-card" style="margin-bottom: 24px;">
                <div class="profile-avatar-lg">
                    {{ strtoupper(substr($mahasiswa->nama_lengkap ?? 'M', 0, 1)) }}
                </div>
                <h3>{{ $mahasiswa->nama_lengkap }}</h3>
                <p>NIM: <strong><span class="code-tag" style="background: transparent; padding: 0; color: inherit;">{{ $mahasiswa->nim }}</span></strong></p>

                @if($pengajuan)
                    <div style="margin-bottom: 20px;">
                        @if($pengajuan->status === 'Sedang Magang')
                            <span class="badge badge-active" style="padding: 6px 16px; font-size: 12.5px;">Sedang Magang</span>
                        @elseif($pengajuan->status === 'Akan Magang')
                            <span class="badge" style="background: #EEF2FF; color: #4338CA; padding: 6px 16px; font-size: 12.5px;">Akan Magang</span>
                        @else
                            <span class="badge badge-inactive" style="padding: 6px 16px; font-size: 12.5px;">Selesai Magang</span>
                        @endif
                    </div>
                @endif

                <hr class="profile-divider">

                <div class="profile-meta">
                    <div>
                        <span style="color: var(--muted); display: block; margin-bottom: 2px;">Asal Kampus / Sekolah:</span>
                        <strong>{{ $mahasiswa->universitas->nama_universitas ?? '-' }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--muted); display: block; margin-bottom: 2px;">Program Studi / Jurusan:</span>
                        <strong>{{ $mahasiswa->jurusan->nama_jurusan ?? '-' }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--muted); display: block; margin-bottom: 2px;">Divisi Magang Terakhir:</span>
                        <strong style="color: var(--primary);">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Kartu Status Akun Portal -->
            <div class="profile-card" style="text-align: left; padding: 24px;">
                <h4 style="font-size: 15px; font-weight: 700; color: var(--primary-dark); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-key"></i> Akun Portal Peserta
                </h4>

                @if($userAcc)
                    <div class="status-alert status-alert-success">
                        <div style="font-size: 12px; color: #166534; font-weight: 700; margin-bottom: 8px;">
                            <i class="fa-solid fa-circle-check" style="margin-right: 4px;"></i> AKUN AKTIF
                        </div>
                        <div style="font-size: 13.5px; line-height: 1.6; color: var(--text);">
                            Username: <strong>{{ $userAcc->username }}</strong><br>
                            Email: <strong>{{ $userAcc->email }}</strong><br>
                            Role: <span class="badge badge-role" style="padding: 2px 8px; margin-top: 4px;">{{ $userAcc->role }}</span>
                        </div>
                    </div>

                    <!-- Form Reset Password -->
                    <form action="{{ route('superadmin.peserta.reset_password', $mahasiswa->id_mhs) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengatur ulang password akun ini?');">
                        @csrf
                        <div style="margin-bottom: 12px;">
                            <label style="font-size: 12.5px; font-weight: 600; color: var(--muted); display: block; margin-bottom: 6px;">Ganti / Reset Password:</label>
                            <input type="text" name="new_password" value="mhs123" class="custom-input" placeholder="Minimal 6 karakter" required>
                        </div>
                        <button type="submit" class="btn-outline-full">
                            <i class="fa-solid fa-rotate-left"></i> Reset Password
                        </button>
                    </form>
                @else
                    <div class="status-alert status-alert-warning">
                        <div style="font-size: 12px; color: #92400E; font-weight: 700; margin-bottom: 6px;">
                            <i class="fa-solid fa-triangle-exclamation" style="margin-right: 4px;"></i> BELUM MEMILIKI AKUN
                        </div>
                        <p style="font-size: 12.5px; color: #B45309; line-height: 1.5; margin: 0;">
                            Mahasiswa ini belum dapat mengakses portal (absensi & logbook). Buatkan akunnya sekarang.
                        </p>
                    </div>

                    <!-- Form Buat Akun -->
                    <form action="{{ route('superadmin.peserta.buat_akun', $mahasiswa->id_mhs) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 12px;">
                            <label style="font-size: 12.5px; font-weight: 600; color: var(--muted); display: block; margin-bottom: 6px;">Tentukan Password Awal:</label>
                            <input type="text" name="password" value="mhs123" class="custom-input" required>
                            <small style="font-size: 11px; color: var(--muted); display: block; margin-top: 6px;">
                                Username otomatis: <strong>{{ $mahasiswa->nim }}</strong>
                            </small>
                        </div>
                        <button type="submit" class="btn-action-full">
                            <i class="fa-solid fa-user-plus"></i> Buatkan Akun Portal
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- =========================================
             KOLOM KANAN: INFORMASI LENGKAP & BERKAS
             ========================================= -->
        <div>
            <!-- Section 1: Info Kontak -->
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
                    <div class="detail-label">Alamat Email</div>
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
                            <br><small style="color: var(--muted); font-weight: 500;">Telp: {{ $mahasiswa->emergency_contact_phone }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Section 2: Info Magang & Pembimbing -->
            <div class="info-card">
                <h4><i class="fa-solid fa-briefcase"></i> Penempatan & Pembimbing Lapangan</h4>
                <div class="detail-row">
                    <div class="detail-label">Divisi Penempatan</div>
                    <div class="detail-val" style="color: var(--primary);">
                        {{ $pengajuan->divisi->nama_divisi ?? '-' }}
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Pembimbing Lapangan</div>
                    <div class="detail-val">
                        @if($pengajuan && ($pengajuan->pembimbing || $pengajuan->nama_pembimbing))
                            <span>{{ $pengajuan->pembimbing->nama_lengkap ?? $pengajuan->nama_pembimbing }}</span>
                            @if($pengajuan->pembimbing)
                                <br><small style="color: var(--muted); font-weight: 500;">NIP: {{ $pengajuan->pembimbing->username }} &bull; {{ $pengajuan->pembimbing->email }}</small>
                            @endif
                        @else
                            <span style="color: var(--muted); font-style: italic; font-weight: 500;">Belum ada pembimbing yang ditugaskan</span>
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Periode Pelaksanaan</div>
                    <div class="detail-val">
                        @if($pengajuan)
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d F Y') }} <span style="color: var(--muted); font-weight:400; padding: 0 6px;">s/d</span> {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d F Y') }}
                            <br><small style="color: var(--muted); font-weight: 500;">Total Durasi: {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($pengajuan->tanggal_selesai)) + 1 }} Hari</small>
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>

            <!-- Section 3: Dokumen -->
            <div class="info-card">
                <h4><i class="fa-regular fa-folder-open"></i> Berkas Dokumen Persyaratan</h4>
                <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                    @if($pengajuan && $pengajuan->cv)
                        <a href="{{ asset('uploads/cv/' . $pengajuan->cv) }}" target="_blank" class="doc-btn">
                            <i class="fa-regular fa-file-user" style="color: #059669; font-size: 18px;"></i>
                            <span>Curriculum Vitae (CV)</span>
                        </a>
                    @else
                        <div class="doc-btn disabled">
                            <i class="fa-regular fa-file-user"></i>
                            <span>CV Tidak Tersedia</span>
                        </div>
                    @endif

                    @if($pengajuan && $pengajuan->proposal)
                        <a href="{{ asset('uploads/proposal/' . $pengajuan->proposal) }}" target="_blank" class="doc-btn">
                            <i class="fa-regular fa-file-lines" style="color: #2563EB; font-size: 18px;"></i>
                            <span>Proposal Magang</span>
                        </a>
                    @else
                        <div class="doc-btn disabled">
                            <i class="fa-regular fa-file-lines"></i>
                            <span>Proposal Tidak Tersedia</span>
                        </div>
                    @endif

                    @if($pengajuan && $pengajuan->surat_permohonan)
                        <a href="{{ asset('uploads/surat_permohonan/' . $pengajuan->surat_permohonan) }}" target="_blank" class="doc-btn">
                            <i class="fa-regular fa-file-pdf" style="color: #DC2626; font-size: 18px;"></i>
                            <span>Surat Pengantar Kampus</span>
                        </a>
                    @else
                        <div class="doc-btn disabled">
                            <i class="fa-regular fa-file-pdf"></i>
                            <span>Surat Pengantar Tidak Tersedia</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="footer-actions" style="display: flex; gap: 14px; justify-content: flex-end; margin-top: 32px;">
                <a href="{{ route('superadmin.peserta') }}" style="background: #fff; color: var(--text); border: 1.5px solid var(--border); padding: 12px 24px; border-radius: 8px; font-size: 13.5px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                    Kembali
                </a>
                <a href="{{ route('superadmin.peserta.edit', $mahasiswa->id_mhs) }}" style="background: var(--primary); color: #fff; border: 1.5px solid var(--primary); padding: 12px 24px; border-radius: 8px; font-size: 13.5px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s;">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Data Peserta
                </a>
            </div>
        </div>
    </div>

@endsection
