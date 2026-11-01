<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peserta Magang - {{ $mahasiswa->nama_lengkap }} - Superadmin</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/superadmin/create.css') }}">
    <style>
        .section-divider {
            font-size: 14px;
            font-weight: 700;
            color: #0A5F63;
            margin: 24px 0 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #E2E8F0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .upload-input-box {
            background: #F8FAFC;
            border: 1.5px dashed #CBD5E1;
            padding: 14px;
            border-radius: 10px;
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
                <h2>Monitoring Mahasiswa PKL</h2>
                <p>Edit data peserta magang & penugasan pembimbing.</p>
            </div>
        </div>
        <div class="header-right">
            <a href="{{ route('superadmin.peserta') }}" class="btn-header-action" title="Kembali ke Daftar">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
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
    @endphp

    <!-- CONTENT -->
    <div class="content">
        <div class="card" style="max-width: 900px; margin: 0 auto;">
            <div class="card-title-box">
                <div class="icon-badge">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>
                    <h2>Edit Data Peserta Magang</h2>
                    <p>Perbarui informasi biodata, asal kampus, divisi penempatan, pembimbing lapangan, atau status magang.</p>
                </div>
            </div>

            <form action="{{ route('superadmin.peserta.update', $mahasiswa->id_mhs) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- SEKSI 1: BIODATA MAHASISWA -->
                <div class="section-divider">
                    <i class="fa-solid fa-user"></i>
                    <span>1. Biodata Pribadi Mahasiswa</span>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color: #DC2626;">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" required>
                        @error('nama_lengkap') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>NIM / NISN <span style="color: #DC2626;">*</span></label>
                        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required>
                        @error('nim') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Jenis Kelamin <span style="color: #DC2626;">*</span></label>
                        <select name="jenis_kelamin" required>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Alamat Email <span style="color: #DC2626;">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $mahasiswa->email) }}" required>
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>No. HP / WhatsApp <span style="color: #DC2626;">*</span></label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $mahasiswa->no_telepon) }}" required>
                        @error('no_telepon') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Nama Kontak Darurat (Orang Tua / Wali)</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $mahasiswa->emergency_contact_name) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>No. HP Kontak Darurat</label>
                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $mahasiswa->emergency_contact_phone) }}">
                </div>

                <!-- SEKSI 2: AKADEMIK & PENUGASAN -->
                <div class="section-divider">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>2. Institusi Asal & Penempatan di PLTU Adipala</span>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Asal Kampus / Sekolah <span style="color: #DC2626;">*</span></label>
                        <select name="universitas_id" id="select_univ" required>
                            @foreach($universitas as $u)
                                <option value="{{ $u->id_universitas }}" {{ old('universitas_id', $mahasiswa->universitas_id) == $u->id_universitas ? 'selected' : '' }}>
                                    {{ $u->nama_universitas }}
                                </option>
                            @endforeach
                            <option value="new" style="color: #0A5F63; font-weight: 700;">+ Tambah Kampus Baru...</option>
                        </select>
                        <div id="box_univ_baru" class="d-none" style="margin-top: 8px;">
                            <input type="text" name="nama_universitas_baru" placeholder="Tulis nama kampus baru...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jurusan / Program Studi <span style="color: #DC2626;">*</span></label>
                        <select name="jurusan_id" id="select_jur" required>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}" {{ old('jurusan_id', $mahasiswa->jurusan_id) == $j->id_jurusan ? 'selected' : '' }}>
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                            <option value="new" style="color: #0A5F63; font-weight: 700;">+ Tambah Jurusan Baru...</option>
                        </select>
                        <div id="box_jur_baru" class="d-none" style="margin-top: 8px;">
                            <input type="text" name="nama_jurusan_baru" placeholder="Tulis nama jurusan baru...">
                        </div>
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Divisi Penempatan <span style="color: #DC2626;">*</span></label>
                        <select name="divisi_id" required>
                            @foreach($divisi as $d)
                                <option value="{{ $d->id_divisi }}" {{ old('divisi_id', $pengajuan?->divisi_id) == $d->id_divisi ? 'selected' : '' }}>
                                    {{ $d->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pembimbing Lapangan</label>
                        <select name="pembimbing_id">
                            <option value="">-- Pilih Pembimbing Lapangan --</option>
                            @foreach($pembimbings as $p)
                                <option value="{{ $p->id_users }}" {{ old('pembimbing_id', $pengajuan?->pembimbing_id) == $p->id_users ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }} ({{ $p->divisi->nama_divisi ?? 'Pembimbing' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Tanggal Mulai Magang <span style="color: #DC2626;">*</span></label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $pengajuan?->tanggal_mulai) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Selesai Magang <span style="color: #DC2626;">*</span></label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pengajuan?->tanggal_selesai) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status Pelaksanaan Magang <span style="color: #DC2626;">*</span></label>
                    <select name="status" required>
                        <option value="Sedang Magang" {{ old('status', $pengajuan?->status) == 'Sedang Magang' ? 'selected' : '' }}>Sedang Magang</option>
                        <option value="Akan Magang" {{ old('status', $pengajuan?->status) == 'Akan Magang' ? 'selected' : '' }}>Akan Magang</option>
                        <option value="Selesai Magang" {{ old('status', $pengajuan?->status) == 'Selesai Magang' ? 'selected' : '' }}>Selesai Magang</option>
                    </select>
                </div>

                <!-- SEKSI 3: PERBARUI DOKUMEN -->
                <div class="section-divider">
                    <i class="fa-solid fa-file-arrow-up"></i>
                    <span>3. Perbarui Dokumen Persyaratan (Kosongkan jika tidak diubah)</span>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Ganti File CV</label>
                        <div class="upload-input-box">
                            <input type="file" name="cv" accept=".pdf,.doc,.docx">
                            @if($pengajuan && $pengajuan->cv)
                                <small style="display: block; color: #059669; margin-top: 4px;">File saat ini: {{ $pengajuan->cv }}</small>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ganti File Proposal</label>
                        <div class="upload-input-box">
                            <input type="file" name="proposal" accept=".pdf,.doc,.docx">
                            @if($pengajuan && $pengajuan->proposal)
                                <small style="display: block; color: #059669; margin-top: 4px;">File saat ini: {{ $pengajuan->proposal }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Ganti File Surat Pengantar Kampus</label>
                    <div class="upload-input-box">
                        <input type="file" name="surat_permohonan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        @if($pengajuan && $pengajuan->surat_permohonan)
                            <small style="display: block; color: #059669; margin-top: 4px;">File saat ini: {{ $pengajuan->surat_permohonan }}</small>
                        @endif
                    </div>
                </div>

                <div class="button-group">
                    <a href="{{ route('superadmin.peserta') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectUniv = document.getElementById('select_univ');
        const boxUniv = document.getElementById('box_univ_baru');
        selectUniv.addEventListener('change', function() {
            if (this.value === 'new') boxUniv.classList.remove('d-none');
            else boxUniv.classList.add('d-none');
        });

        const selectJur = document.getElementById('select_jur');
        const boxJur = document.getElementById('box_jur_baru');
        selectJur.addEventListener('change', function() {
            if (this.value === 'new') boxJur.classList.remove('d-none');
            else boxJur.classList.add('d-none');
        });
    });
    </script>
</body>
</html>
