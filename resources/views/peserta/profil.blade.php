@extends('peserta.layout')

@section('title', 'Profil Peserta Magang')

@section('content')

    <!-- Header Section -->
    <div class="page-header-card">
        <div class="page-header-content">
            <div class="page-icon">
                <i class="fa-regular fa-user"></i>
            </div>
            <div>
                <h1>Profil Saya & Data Penempatan</h1>
                <p>Informasi identitas peserta magang, riwayat penempatan divisi, dan kontak darurat resmi.</p>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">

        <!-- Kartu Identitas Mahasiswa -->
        <div class="card">
            <div class="card-title-box">
                <h3><i class="fa-solid fa-id-card"></i> Identitas Peserta Magang</h3>
            </div>

            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--primary-light); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: 800;">
                    {{ strtoupper(substr($mhs->nama_lengkap ?? 'P', 0, 1)) }}
                </div>
                <div>
                    <h2 style="font-size: 18px; font-weight: 700; color: #0f172a;">{{ $mhs->nama_lengkap }}</h2>
                    <div style="font-size: 13px; color: #64748b;">NIM: {{ $mhs->nim }}</div>
                    <div style="margin-top: 4px;">
                        <span class="badge badge-success">Status: Aktif Magang</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">Asal Kampus / Universitas:</span>
                    <strong style="color: #1e293b;">{{ $mhs->universitas->nama_universitas ?? '-' }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">Program Studi / Jurusan:</span>
                    <strong style="color: #1e293b;">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">Jenis Kelamin:</span>
                    <strong style="color: #1e293b;">{{ $mhs->jenis_kelamin ?? '-' }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">Alamat Email:</span>
                    <strong style="color: #1e293b;">{{ $mhs->email }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">Nomor WhatsApp:</span>
                    <strong style="color: #1e293b;">{{ $mhs->no_telepon }}</strong>
                </div>
            </div>
        </div>

        <!-- Kartu Update Kontak & Kontak Darurat -->
        <div class="card">
            <div class="card-title-box">
                <h3><i class="fa-solid fa-phone-volume"></i> Kontak & Kontak Darurat</h3>
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
                    <small style="color: #64748b; font-size: 11px;">Nomor ini akan dihubungi pihak perusahaan dalam kondisi mendesak atau darurat.</small>
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                    <i class="fa-regular fa-floppy-disk"></i> Simpan Perubahan Kontak
                </button>
            </form>
        </div>

    </div>

    <!-- Informasi Penempatan Magang -->
    <div class="card" style="margin-top: 24px;">
        <div class="card-title-box">
            <h3><i class="fa-solid fa-briefcase"></i> Rincian Penempatan PKL di PLTU Adipala</h3>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                <div style="font-size: 11px; color: #64748b; text-transform: uppercase;">Divisi Magang</div>
                <div style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 2px;">
                    {{ $penempatan->divisi->nama_divisi ?? 'Sistem Informasi' }}
                </div>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                <div style="font-size: 11px; color: #64748b; text-transform: uppercase;">Pembimbing Lapangan</div>
                <div style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 2px;">
                    {{ $penempatan->nama_pembimbing ?? 'Kang Cihuy' }}
                </div>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                <div style="font-size: 11px; color: #64748b; text-transform: uppercase;">Periode Magang</div>
                <div style="font-size: 13.5px; font-weight: 600; color: #0f172a; margin-top: 2px;">
                    {{ $penempatan ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d M Y') : '13 Okt 2026' }} s/d
                    {{ $penempatan ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d M Y') : '31 Okt 2026' }}
                </div>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                <div style="font-size: 11px; color: #64748b; text-transform: uppercase;">Status Pengajuan</div>
                <div style="margin-top: 4px;">
                    <span class="badge badge-success">{{ $penempatan->status ?? 'Sedang Magang' }}</span>
                </div>
            </div>
        </div>
    </div>

@endsection
