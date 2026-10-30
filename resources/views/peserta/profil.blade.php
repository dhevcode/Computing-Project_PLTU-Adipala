@extends('peserta.layout')

@section('title', 'Profil Peserta Magang')

@section('content')

<style>
    /* ==========================================================================
       PROFIL PESERTA - PANEL KACA & KARTU BENTO (TEMA DASHBOARD)
       ========================================================================== */
    .pr-page {
        box-sizing: border-box; width: 100%; max-width: 1200px;
        margin-left: auto; margin-right: auto; color: var(--text);
    }
    .pr-page *, .pr-page *::before, .pr-page *::after { box-sizing: border-box; }

    /* ---------- Panel Utama ---------- */
    .pr-panel {
        background: var(--panel);
        backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 40px; padding: 30px;
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.22);
    }
    .pr-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 18px 24px; margin-bottom: 26px; }
    .pr-top-text { flex: 1 1 360px; min-width: 0; }
    .pr-title { margin: 0; font-size: clamp(24px, 3vw, 36px); font-weight: 700; letter-spacing: -0.025em; line-height: 1.15; color: #ffffff; }
    .pr-sub { margin: 10px 0 0; font-size: 13.5px; line-height: 1.6; color: var(--muted); font-weight: 500; }

    /* ---------- Grid Bento & Kartu ---------- */
    .pr-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 24px; }
    .pr-card {
        background: var(--card); backdrop-filter: blur(16px) saturate(130%); -webkit-backdrop-filter: blur(16px) saturate(130%);
        border: 1px solid var(--border); border-radius: 26px; padding: 22px; min-width: 0;
        display: flex; flex-direction: column; color: var(--text);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
    }
    .pr-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 20px; }
    .pr-card-title { margin: 0; font-size: 16px; font-weight: 700; letter-spacing: -0.01em; color: #ffffff; display: flex; align-items: center; gap: 10px; }
    .pr-card-title i { color: var(--accent); }

    /* ---------- Identitas Utama ---------- */
    .pr-id-top { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; }
    .pr-avatar {
        width: 64px; height: 64px; border-radius: 50%;
        background: var(--accent-soft); color: var(--accent); border: 1px solid var(--accent-bd);
        display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: 800;
    }
    .pr-name { font-size: 18px; font-weight: 700; color: #ffffff; margin: 0 0 4px 0; }
    .pr-nim { font-size: 13px; color: var(--muted); font-family: var(--font-num); letter-spacing: 0.05em; margin-bottom: 6px; }

    .pr-badge {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 9999px;
        font-size: 11px; font-weight: 700; white-space: nowrap;
        background: var(--ok-bg); color: var(--ok); border: 1px solid var(--ok-bd);
    }

    .pr-info-list {
        display: flex; flex-direction: column; gap: 14px; font-size: 13px;
        border-top: 1px solid var(--border); padding-top: 16px;
    }
    .pr-info-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; line-height: 1.4; }
    .pr-info-lbl { color: var(--muted); }
    .pr-info-val { color: #ffffff; font-weight: 600; text-align: right; }

    /* ---------- Form Input ---------- */
    .pr-page .form-group { margin-bottom: 16px; }
    .pr-page .form-group > label { display: block; margin-bottom: 8px; font-size: 12.5px; font-weight: 600; color: var(--text-2); }
    .pr-page .form-control {
        width: 100%; padding: 11px 16px; font-family: inherit; font-size: 13.5px; color: var(--text);
        background: rgba(255, 255, 255, 0.07); border: 1px solid var(--border-strong); border-radius: 16px; color-scheme: dark;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .pr-page .form-control::placeholder { color: var(--muted); }
    .pr-page .form-control:focus { outline: none; border-color: var(--accent); background: rgba(255, 255, 255, 0.10); box-shadow: 0 0 0 3px var(--accent-soft); }
    .pr-page .form-group small { color: var(--muted); font-size: 11.5px; margin-top: 6px; display: block; }

    .pr-page .btn-primary {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 40px; padding: 0 20px; border-radius: 9999px; font-family: inherit; font-size: 13.5px; font-weight: 600; line-height: 1;
        background: var(--accent); color: var(--accent-ink); border: 1px solid var(--accent);
        cursor: pointer; text-decoration: none; white-space: nowrap; transition: background .2s, transform .2s;
        margin-top: 10px;
    }
    .pr-page .btn-primary:hover { background: #d6f55a; border-color: #d6f55a; transform: translateY(-1px); }

    /* ---------- Rincian Penempatan ---------- */
    .pr-placement-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
    .pr-placement-box { background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-strong); border-radius: 14px; padding: 16px; }
    .pr-placement-lbl { font-size: 10.5px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; font-family: var(--font-num); margin-bottom: 6px; }
    .pr-placement-val { font-size: 14px; font-weight: 700; color: #ffffff; line-height: 1.3; }

    @media (max-width: 768px) {
        .pr-panel { padding: 18px; border-radius: 30px; }
        .pr-card { padding: 18px; border-radius: 20px; }
        .pr-info-row { flex-direction: column; align-items: flex-start; gap: 4px; }
        .pr-info-val { text-align: left; }
    }
</style>

<div class="pr-page">
<div class="pr-panel">

    <!-- ================= KEPALA PANEL ================= -->
    <header class="pr-top">
        <div class="pr-top-text">
            <h1 class="pr-title">Profil Saya & Data Penempatan</h1>
            <p class="pr-sub">Informasi identitas peserta magang, riwayat penempatan divisi, dan kontak darurat resmi.</p>
        </div>
    </header>

    <div class="pr-grid">

        <!-- ================= KARTU IDENTITAS ================= -->
        <section class="pr-card">
            <div class="pr-head">
                <h3 class="pr-card-title"><i class="fa-solid fa-id-card"></i> Identitas Peserta Magang</h3>
            </div>

            <div class="pr-id-top">
                <div class="pr-avatar">
                    {{ strtoupper(substr($mhs->nama_lengkap ?? 'P', 0, 1)) }}
                </div>
                <div>
                    <h2 class="pr-name">{{ $mhs->nama_lengkap }}</h2>
                    <div class="pr-nim">NIM: {{ $mhs->nim }}</div>
                    <span class="pr-badge"><i class="fa-solid fa-check"></i> Aktif Magang</span>
                </div>
            </div>

            <div class="pr-info-list">
                <div class="pr-info-row">
                    <span class="pr-info-lbl">Asal Kampus / Universitas:</span>
                    <span class="pr-info-val">{{ $mhs->universitas->nama_universitas ?? '-' }}</span>
                </div>
                <div class="pr-info-row">
                    <span class="pr-info-lbl">Program Studi / Jurusan:</span>
                    <span class="pr-info-val">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</span>
                </div>
                <div class="pr-info-row">
                    <span class="pr-info-lbl">Jenis Kelamin:</span>
                    <span class="pr-info-val">{{ $mhs->jenis_kelamin ?? '-' }}</span>
                </div>
                <div class="pr-info-row">
                    <span class="pr-info-lbl">Alamat Email:</span>
                    <span class="pr-info-val">{{ $mhs->email }}</span>
                </div>
                <div class="pr-info-row">
                    <span class="pr-info-lbl">Nomor WhatsApp:</span>
                    <span class="pr-info-val">{{ $mhs->no_telepon }}</span>
                </div>
            </div>
        </section>

        <!-- ================= UPDATE KONTAK & DARURAT ================= -->
        <section class="pr-card">
            <div class="pr-head">
                <h3 class="pr-card-title"><i class="fa-solid fa-phone-volume"></i> Kontak & Kontak Darurat</h3>
            </div>

            <form action="{{ route('peserta.profil.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="no_telepon">Nomor Telepon / WhatsApp Saya *</label>
                    <input type="text" id="no_telepon" name="no_telepon" value="{{ $mhs->no_telepon }}" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="emergency_contact_name">Nama Kontak Darurat (Orang Tua / Wali) *</label>
                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ $mhs->emergency_contact_name }}" placeholder="Contoh: Bapak Supardi (Orang Tua)" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="emergency_contact_phone">Nomor Telepon Kontak Darurat *</label>
                    <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ $mhs->emergency_contact_phone }}" placeholder="Contoh: 08123456789" required class="form-control">
                    <small>Nomor ini akan dihubungi pihak perusahaan dalam kondisi mendesak atau darurat.</small>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fa-regular fa-floppy-disk"></i> Simpan Perubahan Kontak
                </button>
            </form>
        </section>

    </div>

    <!-- ================= RINCIAN PENEMPATAN ================= -->
    <section class="pr-card">
        <div class="pr-head">
            <h3 class="pr-card-title"><i class="fa-solid fa-briefcase"></i> Rincian Penempatan PKL di PLTU Adipala</h3>
        </div>

        <div class="pr-placement-grid">
            <div class="pr-placement-box">
                <div class="pr-placement-lbl">Divisi Magang</div>
                <div class="pr-placement-val">{{ $penempatan->divisi->nama_divisi ?? 'Sistem Informasi' }}</div>
            </div>

            <div class="pr-placement-box">
                <div class="pr-placement-lbl">Pembimbing Lapangan</div>
                <div class="pr-placement-val">{{ $penempatan->nama_pembimbing ?? 'Kang Cihuy' }}</div>
            </div>

            <div class="pr-placement-box">
                <div class="pr-placement-lbl">Periode Magang</div>
                <div class="pr-placement-val">
                    {{ $penempatan ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d M Y') : '13 Okt 2026' }} s/d <br>
                    {{ $penempatan ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d M Y') : '31 Okt 2026' }}
                </div>
            </div>

            <div class="pr-placement-box">
                <div class="pr-placement-lbl">Status Pengajuan</div>
                <div style="margin-top: 6px;">
                    <span class="pr-badge">{{ $penempatan->status ?? 'Sedang Magang' }}</span>
                </div>
            </div>
        </div>
    </section>

</div>
</div>

@endsection
