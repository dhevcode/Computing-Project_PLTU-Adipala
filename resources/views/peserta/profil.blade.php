@extends('peserta.layouts.app')

@section('title', 'Profil Peserta Magang')
@section('page_title', 'Profil Saya & Data Penempatan')
@section('page_subtitle', 'Informasi identitas peserta magang, riwayat penempatan divisi, dan update kontak resmi.')

@push('styles')
<style>
    /* ==========================================================================
       TEMA CLEAN DASHBOARD (IDENTIK DENGAN ADMIN/PEMBIMBING)
       ========================================================================== */

    /* Layout Utama Grid */
    .profile-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; align-items: start; }

    /* Standar Card */
    .card { background: #fff; border-radius: 16px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); overflow: hidden; padding: 24px; }

    /* Header Card (Icon Kiri, Teks Kanan) */
    .card-header-top { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--page); }
    .icon-circle-box { background: var(--primary-soft); color: var(--primary); font-size: 20px; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .card-title-wrap h3 { font-size: 16.5px; font-weight: 700; color: var(--text); margin: 0 0 4px 0; line-height: 1.2; }
    .card-title-wrap p { font-size: 12.5px; color: var(--muted); margin: 0; line-height: 1.4; }

    /* Profil Utama (Avatar & Nama) */
    .profile-main { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; padding: 16px; background: var(--page); border-radius: 12px; border: 1px solid var(--border); }
    .avatar-lg { width: 64px; height: 64px; border-radius: 50%; background: var(--primary-soft); color: var(--primary-dark); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; flex-shrink: 0; }
    .profile-main h2 { font-size: 18px; font-weight: 800; color: var(--text); margin: 0 0 6px 0; word-wrap: break-word; }
    .profile-main .nim-text { font-size: 13px; color: var(--muted); font-family: monospace; font-weight: 600; margin-bottom: 8px; display: block; }

    /* List Informasi Identitas */
    .info-list { display: flex; flex-direction: column; gap: 12px; }
    .info-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; padding: 10px 0; border-bottom: 1px dashed var(--page); font-size: 13.5px; }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .info-label { color: var(--muted); font-weight: 600; flex-shrink: 0; }
    .info-val { color: var(--text); font-weight: 700; text-align: right; word-wrap: break-word; overflow-wrap: break-word; }

    /* Badges */
    .status-badge { padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; border: 1px solid var(--border); }
    .badge-present { background: var(--success-bg); color: var(--success-text); border-color: #A7F3D0; }
    .badge-draft { background: #F1F5F9; color: #475569; }

    /* Form Inputs */
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 12.5px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
    .input-box { width: 100%; padding: 12px 16px; font-family: inherit; font-size: 13.5px; color: var(--text); background: #fff; border: 1.5px solid var(--border); border-radius: 12px; transition: all .2s ease; }
    .input-box::placeholder { color: var(--muted); }
    .input-box:focus { outline: none; border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px var(--primary-soft); }
    .form-group small { color: var(--muted); font-size: 11.5px; margin-top: 6px; display: block; line-height: 1.4; }

    /* Buttons */
    .btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 42px; padding: 0 20px; border-radius: 8px; font-family: inherit; font-size: 13.5px; font-weight: 700; background: var(--primary); color: #fff; border: 1px solid var(--primary); cursor: pointer; text-decoration: none; transition: 0.2s; width: 100%; margin-top: 10px; }
    .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

    /* Grid Penempatan */
    .placement-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
    .placement-box { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
    .placement-lbl { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; margin-bottom: 8px; display: block; }
    .placement-val { font-size: 14px; font-weight: 700; color: var(--text); line-height: 1.4; }

    /* ================= RESPONSIVE MOBILE ================= */
    @media (max-width: 900px) {
        .profile-layout { grid-template-columns: 1fr; gap: 16px; margin-bottom: 16px; }
    }
    @media (max-width: 768px) {
        .card { padding: 18px; }
        .card-header-top { flex-direction: column; align-items: flex-start; gap: 12px; }

        .profile-main { flex-direction: column; align-items: flex-start; text-align: left; gap: 16px; }

        /* Merubah list info agar turun ke bawah */
        .info-row { flex-direction: column; align-items: flex-start; gap: 6px; padding-bottom: 10px; border-bottom: 1px dashed var(--border); }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .info-label { font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-val { text-align: left; width: 100%; font-size: 14px; }

        .placement-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

    @if(session('success'))
        <div class="alert alert-success animate-fade-up" style="margin-bottom: 24px;">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="profile-layout animate-fade-up delay-1">

        <!-- ================= KARTU IDENTITAS KIRI ================= -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header-top">
                <div class="card-title-wrap">
                    <div class="icon-circle-box">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <div>
                        <h3>Identitas Peserta Magang</h3>
                        <p>Informasi dasar profil akademik Anda.</p>
                    </div>
                </div>
            </div>

            <div class="profile-main">
                <div class="avatar-lg">
                    {{ strtoupper(substr($mhs->nama_lengkap ?? 'M', 0, 1)) }}
                </div>
                <div>
                    <h2>{{ $mhs->nama_lengkap ?? 'Nama Tidak Tersedia' }}</h2>
                    <span class="nim-text">NIM: {{ $mhs->nim ?? '-' }}</span>
                    <span class="status-badge badge-present"><i class="fa-solid fa-check"></i> Aktif Magang</span>
                </div>
            </div>

            <div class="info-list">
                <div class="info-row">
                    <span class="info-label">Asal Kampus / Universitas</span>
                    <span class="info-val">{{ $mhs->universitas->nama_universitas ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Program Studi / Jurusan</span>
                    <span class="info-val">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jenis Kelamin</span>
                    <span class="info-val">{{ $mhs->jenis_kelamin ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Alamat Email</span>
                    <span class="info-val">{{ $mhs->email ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nomor WhatsApp Pribadi</span>
                    <span class="info-val">{{ $mhs->no_telepon ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- ================= KARTU FORM KONTAK KANAN ================= -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header-top">
                <div class="card-title-wrap">
                    <div class="icon-circle-box" style="background: #FFFBEB; color: #D97706;">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>
                    <div>
                        <h3>Update Kontak Pribadi</h3>
                        <p>Perbarui kontak Anda dan wali agar mudah dihubungi.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('peserta.profil.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="no_telepon">Nomor Telepon / WhatsApp Saya *</label>
                    <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $mhs->no_telepon ?? '') }}" required class="input-box" placeholder="Contoh: 08123456789">
                </div>

                <div style="border-top: 1px dashed var(--page); margin: 24px 0;"></div>

                <div class="form-group">
                    <label for="emergency_contact_name">Nama Wali Darurat (Orang Tua / Saudara) *</label>
                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $mhs->emergency_contact_name ?? '') }}" placeholder="Contoh: Bapak Supardi" required class="input-box">
                </div>

                <div class="form-group">
                    <label for="emergency_contact_phone">Nomor Telepon Wali Darurat *</label>
                    <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $mhs->emergency_contact_phone ?? '') }}" placeholder="Contoh: 08123456789" required class="input-box">
                    <small>Nomor darurat ini wajib diisi. Pihak PLTU atau pembimbing hanya akan menghubungi nomor ini jika terjadi kondisi mendesak.</small>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Kontak
                </button>
            </form>
        </div>

    </div>

    <!-- ================= KARTU RINCIAN PENEMPATAN BAWAH ================= -->
    <div class="card animate-fade-up delay-2">
        <div class="card-header-top">
            <div class="card-title-wrap">
                <div class="icon-circle-box" style="background: #EFF6FF; color: #2563EB;">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div>
                    <h3>Rincian Penempatan PKL</h3>
                    <p>Status penempatan divisi dan masa magang Anda di PLTU Adipala.</p>
                </div>
            </div>
        </div>

        <div class="placement-grid">
            <div class="placement-box">
                <span class="placement-lbl">Divisi Magang</span>
                <div class="placement-val" style="color: var(--primary);">{{ $penempatan->divisi->nama_divisi ?? 'Belum Ditentukan' }}</div>
            </div>

            <div class="placement-box">
                <span class="placement-lbl">Pembimbing Lapangan</span>
                <div class="placement-val">{{ $penempatan->nama_pembimbing ?? 'Belum Diplot' }}</div>
            </div>

            <div class="placement-box">
                <span class="placement-lbl">Periode Magang</span>
                <div class="placement-val" style="font-size: 13.5px;">
                    {{ isset($penempatan->tanggal_mulai) ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->translatedFormat('d M Y') : '-' }} <br> s/d <br>
                    {{ isset($penempatan->tanggal_selesai) ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->translatedFormat('d M Y') : '-' }}
                </div>
            </div>

            <div class="placement-box">
                <span class="placement-lbl">Status Pengajuan</span>
                <div style="margin-top: 4px;">
                    <span class="status-badge {{ ($penempatan->status ?? '') === 'Selesai Magang' ? 'badge-draft' : 'badge-present' }}">
                        {{ $penempatan->status ?? 'Sedang Magang' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

@endsection
