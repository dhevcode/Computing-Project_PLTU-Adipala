<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peserta Magang - Superadmin PLTU Adipala</title>
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
                <p>Tambah data peserta magang baru dan penempatan pembimbing.</p>
            </div>
        </div>
        <div class="header-right">
            <a href="{{ route('superadmin.peserta') }}" class="btn-header-action" title="Kembali ke Daftar Peserta">
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

    <!-- CONTENT -->
    <div class="content">
        <div class="card" style="max-width: 900px; margin: 0 auto;">
            <div class="card-title-box">
                <div class="icon-badge">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div>
                    <h2>Tambah Peserta Magang Baru</h2>
                    <p>Lengkapi biodata mahasiswa, penempatan divisi, penugasan pembimbing lapangan, dan berkas persyaratan.</p>
                </div>
            </div>

            <form action="{{ route('superadmin.peserta.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- SEKSI 1: BIODATA MAHASISWA -->
                <div class="section-divider">
                    <i class="fa-solid fa-user"></i>
                    <span>1. Biodata Pribadi Mahasiswa</span>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color: #DC2626;">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Contoh: Muhammad Farhan" required>
                        @error('nama_lengkap') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>NIM / NISN <span style="color: #DC2626;">*</span></label>
                        <input type="text" name="nim" value="{{ old('nim') }}" placeholder="Contoh: 2311102045" required>
                        @error('nim') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Jenis Kelamin <span style="color: #DC2626;">*</span></label>
                        <select name="jenis_kelamin" required>
                            <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Alamat Email <span style="color: #DC2626;">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="mhs@email.com" required>
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>No. HP / WhatsApp <span style="color: #DC2626;">*</span></label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 081234567890" required>
                        @error('no_telepon') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Nama Kontak Darurat (Orang Tua / Wali)</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Contoh: Joko Widodo (Ayah)">
                    </div>
                </div>

                <div class="form-group">
                    <label>No. HP Kontak Darurat</label>
                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" placeholder="Contoh: 081987654321">
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
                            <option value="" disabled {{ old('universitas_id') ? '' : 'selected' }}>-- Pilih Asal Kampus --</option>
                            @foreach($universitas as $u)
                                <option value="{{ $u->id_universitas }}" {{ old('universitas_id') == $u->id_universitas ? 'selected' : '' }}>
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
                            <option value="" disabled {{ old('jurusan_id') ? '' : 'selected' }}>-- Pilih Program Studi --</option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}" {{ old('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>
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
                            <option value="" disabled {{ old('divisi_id') ? '' : 'selected' }}>-- Pilih Divisi Penempatan --</option>
                            @foreach($divisi as $d)
                                <option value="{{ $d->id_divisi }}" {{ old('divisi_id') == $d->id_divisi ? 'selected' : '' }}>
                                    {{ $d->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pembimbing Lapangan</label>
                        <select name="pembimbing_id">
                            <option value="">-- Pilih Pembimbing (Opsional) --</option>
                            @foreach($pembimbings as $p)
                                <option value="{{ $p->id_users }}" {{ old('pembimbing_id') == $p->id_users ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }} ({{ $p->divisi->nama_divisi ?? 'Pembimbing' }})
                                </option>
                            @endforeach
                        </select>
                        <small style="color: #64748B;">* Dapat ditugaskan sekarang atau nanti.</small>
                    </div>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Tanggal Mulai Magang <span style="color: #DC2626;">*</span></label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Selesai Magang <span style="color: #DC2626;">*</span></label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d', strtotime('+3 months'))) }}" required>
                    </div>
                </div>

                <!-- SEKSI 3: DOKUMEN PERSYARATAN -->
                <div class="section-divider">
                    <i class="fa-solid fa-file-arrow-up"></i>
                    <span>3. Dokumen Persyaratan (Opsional)</span>
                </div>

                <div class="grid">
                    <div class="form-group">
                        <label>Curriculum Vitae (CV)</label>
                        <div class="upload-input-box">
                            <input type="file" name="cv" accept=".pdf,.doc,.docx">
                            <small style="display: block; color: #64748B; margin-top: 4px;">PDF, DOC, DOCX (Maks. 5MB)</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Proposal Magang</label>
                        <div class="upload-input-box">
                            <input type="file" name="proposal" accept=".pdf,.doc,.docx">
                            <small style="display: block; color: #64748B; margin-top: 4px;">PDF, DOC, DOCX (Maks. 5MB)</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Surat Pengantar Kampus / Permohonan</label>
                    <div class="upload-input-box">
                        <input type="file" name="surat_permohonan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small style="display: block; color: #64748B; margin-top: 4px;">PDF, DOC, DOCX, JPG, PNG (Maks. 5MB)</small>
                    </div>
                </div>

                <!-- SEKSI 4: PEMBUATAN AKUN USER PORTAL -->
                <div class="section-divider">
                    <i class="fa-solid fa-key"></i>
                    <span>4. Pembuatan Akun Portal Peserta (Super Admin)</span>
                </div>

                <div style="background: #F0FDF4; border: 1.5px dashed #10B981; border-radius: 12px; padding: 18px 20px; margin-bottom: 24px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 700; color: #047857; font-size: 14px;">
                        <input type="checkbox" name="buat_akun" value="1" id="chk_buat_akun" checked style="width: 18px; height: 18px; accent-color: #0A5F63;">
                        <span>Buatkan akun portal peserta magang sekarang</span>
                    </label>

                    <div id="box_password_akun" style="margin-top: 14px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="color: #065F46; font-size: 12.5px;">Password Awal Akun Peserta:</label>
                            <input type="text" name="password_akun" value="mhs123" placeholder="Minimal 6 karakter" style="background: #fff; border-color: #A7F3D0;">
                            <small style="color: #047857; display: block; margin-top: 4px;">
                                * Username otomatis menggunakan <strong>NIM</strong> dan login dapat menggunakan Email atau NIM.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <a href="{{ route('superadmin.peserta') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Peserta Magang</span>
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

        const chkAkun = document.getElementById('chk_buat_akun');
        const boxPass = document.getElementById('box_password_akun');
        chkAkun.addEventListener('change', function() {
            if (this.checked) boxPass.style.display = 'block';
            else boxPass.style.display = 'none';
        });
    });
    </script>
</body>
</html>
