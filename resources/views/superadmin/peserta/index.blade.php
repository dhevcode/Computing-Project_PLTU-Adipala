<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Peserta Magang - Superadmin PLTU Adipala</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/superadmin/dashboard.css') }}">
    <style>
        .filter-bar {
            background: #ffffff;
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .filter-select {
            height: 38px;
            border-radius: 8px;
            border: 1.5px solid #CBD5E1;
            padding: 0 12px;
            font-size: 13px;
            font-weight: 500;
            color: #1E293B;
            background: #ffffff;
            outline: none;
        }
        .filter-select:focus {
            border-color: #0A5F63;
        }
        .account-badge-yes {
            background: #DCFCE7;
            color: #15803D;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .account-badge-no {
            background: #FEF3C7;
            color: #B45309;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-create-acc {
            background: #0A5F63;
            color: #ffffff;
            border: none;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s ease;
        }
        .btn-create-acc:hover {
            background: #074B4E;
            transform: translateY(-1px);
        }
        .btn-reset-acc {
            background: #F1F5F9;
            color: #475569;
            border: 1px solid #CBD5E1;
            padding: 2px 7px;
            border-radius: 6px;
            font-size: 10.5px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-reset-acc:hover {
            background: #E2E8F0;
            color: #0F172A;
        }
        /* MODAL */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }
        .modal-overlay.active { display: flex; }
        .modal-card {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            padding: 24px;
        }
        .modal-card h3 {
            font-size: 17px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 8px;
        }
        .modal-card p {
            font-size: 13px;
            color: #64748B;
            margin-bottom: 20px;
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
                <p>Sistem Pengelolaan & Kontrol Akses Administrator PLTU</p>
            </div>
        </div>
        <div class="header-right">
            <a href="{{ route('superadmin.dashboard') }}" class="btn-header-nav">
                <i class="fa-solid fa-users-gear"></i>
                <span>Kelola Akun</span>
            </a>

            <a href="{{ route('superadmin.pembimbing') }}" class="btn-header-nav">
                <i class="fa-solid fa-user-tie"></i>
                <span>Pembimbing Lapangan</span>
            </a>

            <a href="{{ route('superadmin.peserta') }}" class="btn-header-nav active">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Peserta Magang</span>
            </a>

            <a href="{{ route('superadmin.peserta.create') }}" class="btn-header-action">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah Peserta</span>
            </a>

            <a href="{{ route('superadmin.profile') }}" class="btn-header-nav">
                <i class="fa-solid fa-user"></i>
                <span>Profile</span>
            </a>

            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-header-icon" title="Logout dari Sistem">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- CONTENT -->
    <div class="content">

        <!-- STATS WIDGETS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Total Peserta Magang</h4>
                    <div class="stat-value">{{ $totalPeserta ?? 0 }}</div>
                </div>
                <div class="stat-icon primary">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h4>Sedang Magang Aktif</h4>
                    <div class="stat-value">{{ $sedangMagang ?? 0 }}</div>
                </div>
                <div class="stat-icon success">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h4>Akan Magang</h4>
                    <div class="stat-value">{{ $akanMagang ?? 0 }}</div>
                </div>
                <div class="stat-icon warning">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h4>Belum Memiliki Akun</h4>
                    <div class="stat-value" style="color: {{ ($belumPunyaAkun ?? 0) > 0 ? '#DC2626' : '#059669' }};">
                        {{ $belumPunyaAkun ?? 0 }}
                    </div>
                </div>
                <div class="stat-icon primary" style="background: #FEE2E2; color: #DC2626;">
                    <i class="fa-solid fa-key"></i>
                </div>
            </div>
        </div>

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

        <!-- FILTER BAR -->
        <form method="GET" action="{{ route('superadmin.peserta') }}" class="filter-bar">
            <div class="filter-group">
                <select name="divisi_id" class="filter-select" onchange="this.form.submit()">
                    <option value="all">-- Semua Divisi --</option>
                    @foreach($divisiList as $d)
                        <option value="{{ $d->id_divisi }}" {{ request('divisi_id') == $d->id_divisi ? 'selected' : '' }}>
                            {{ $d->nama_divisi }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="all">-- Semua Status Magang --</option>
                    <option value="Sedang Magang" {{ request('status') == 'Sedang Magang' ? 'selected' : '' }}>Sedang Magang</option>
                    <option value="Akan Magang" {{ request('status') == 'Akan Magang' ? 'selected' : '' }}>Akan Magang</option>
                    <option value="Selesai Magang" {{ request('status') == 'Selesai Magang' ? 'selected' : '' }}>Selesai Magang</option>
                </select>

                <select name="status_akun" class="filter-select" onchange="this.form.submit()">
                    <option value="all">-- Status Akun Portal --</option>
                    <option value="ada_akun" {{ request('status_akun') == 'ada_akun' ? 'selected' : '' }}>Sudah Memiliki Akun</option>
                    <option value="belum_akun" {{ request('status_akun') == 'belum_akun' ? 'selected' : '' }}>Belum Ada Akun</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                <div class="search-box" style="margin: 0;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIM, email...">
                </div>
                <button type="submit" class="btn-view-mhs" style="height: 38px; border-radius: 8px;">Cari</button>
                @if(request()->anyFilled(['divisi_id', 'status', 'status_akun', 'q']))
                    <a href="{{ route('superadmin.peserta') }}" style="font-size: 12px; color: #DC2626; text-decoration: none; font-weight: 600;">Reset</a>
                @endif
            </div>
        </form>

        <!-- MAIN TABLE CARD -->
        <div class="card">
            <div class="card-header">
                <div class="card-title-group">
                    <h3>Daftar Data Peserta Magang / PKL</h3>
                    <p>Kelola data mahasiswa magang (CRUD), penempatan divisi, pembimbing lapangan, dan akun portal.</p>
                </div>
                <div>
                    <a href="{{ route('superadmin.peserta.create') }}" class="btn-header-action" style="background: #0A5F63; color: #fff;">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Peserta Baru</span>
                    </a>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">No</th>
                            <th>Mahasiswa</th>
                            <th>Kampus & Jurusan</th>
                            <th>Divisi Penempatan</th>
                            <th>Pembimbing Lapangan</th>
                            <th>Periode Magang</th>
                            <th style="text-align: center;">Status Magang</th>
                            <th style="text-align: center;">Akun Portal</th>
                            <th style="width: 120px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswas as $index => $mhs)
                            @php
                                $pengajuan = $mhs->latestPengajuan;
                                $userAcc = $mhs->user;
                            @endphp
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #94A3B8;">
                                    {{ $mahasiswas->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <div class="avatar-circle" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                                            {{ strtoupper(substr($mhs->nama_lengkap ?? 'M', 0, 1)) }}
                                        </div>
                                        <div class="user-info">
                                            <span class="name">{{ $mhs->nama_lengkap }}</span>
                                            <span class="email">NIM: <strong>{{ $mhs->nim }}</strong></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $mhs->universitas->nama_universitas ?? '-' }}</strong><br>
                                    <small style="color: #64748B;">{{ $mhs->jurusan->nama_jurusan ?? '-' }}</small>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: #0A5F63;">
                                        {{ $pengajuan->divisi->nama_divisi ?? 'Belum Ditentukan' }}
                                    </span>
                                </td>
                                <td>
                                    @if($pengajuan && ($pengajuan->pembimbing || $pengajuan->nama_pembimbing))
                                        <div style="font-size: 13px; font-weight: 600; color: #1E293B;">
                                            <i class="fa-solid fa-user-tie" style="color: #0A5F63; margin-right: 4px;"></i>
                                            {{ $pengajuan->pembimbing->nama_lengkap ?? $pengajuan->nama_pembimbing }}
                                        </div>
                                    @else
                                        <span style="color: #94A3B8; font-style: italic; font-size: 12px;">Belum Ditugaskan</span>
                                    @endif
                                </td>
                                <td style="font-size: 12px;">
                                    @if($pengajuan)
                                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d/m/Y') }} s/d<br>
                                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if($pengajuan)
                                        @if($pengajuan->status === 'Sedang Magang')
                                            <span class="badge badge-active">Sedang Magang</span>
                                        @elseif($pengajuan->status === 'Akan Magang')
                                            <span class="badge" style="background: #EEF2FF; color: #4338CA;">Akan Magang</span>
                                        @else
                                            <span class="badge badge-inactive">Selesai Magang</span>
                                        @endif
                                    @else
                                        <span class="badge badge-inactive">-</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if($userAcc)
                                        <div>
                                            <span class="account-badge-yes">
                                                <i class="fa-solid fa-check"></i> Aktif ({{ $userAcc->username }})
                                            </span>
                                        </div>
                                        <button type="button" class="btn-reset-acc" onclick="openResetModal({{ $mhs->id_mhs }}, '{{ $mhs->nama_lengkap }}')">
                                            <i class="fa-solid fa-key"></i> Reset Pass
                                        </button>
                                    @else
                                        <div>
                                            <span class="account-badge-no">
                                                <i class="fa-solid fa-xmark"></i> Belum Ada Akun
                                            </span>
                                        </div>
                                        <button type="button" class="btn-create-acc" onclick="openCreateAccModal({{ $mhs->id_mhs }}, '{{ $mhs->nama_lengkap }}', '{{ $mhs->nim }}')">
                                            <i class="fa-solid fa-user-plus"></i> Buatkan Akun
                                        </button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="action-group" style="justify-content: center;">
                                        <!-- Detail -->
                                        <a href="{{ route('superadmin.peserta.show', $mhs->id_mhs) }}" class="btn-action" style="background: #E0F2FE; color: #0284C7;" title="Lihat Detail Peserta & Berkas">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('superadmin.peserta.edit', $mhs->id_mhs) }}" class="btn-action btn-edit" title="Edit Data Peserta">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('superadmin.peserta.destroy', $mhs->id_mhs) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data peserta magang {{ $mhs->nama_lengkap }} beserta seluruh riwayat terkait?');" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Hapus Peserta Magang">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-user-slash"></i>
                                        <p>Tidak ada data peserta magang yang sesuai dengan filter / pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 16px 24px;">
                {{ $mahasiswas->links() }}
            </div>
        </div>

    </div>

    <!-- MODAL BUAT AKUN PESERTA -->
    <div id="modalBuatAkun" class="modal-overlay">
        <div class="modal-card">
            <h3><i class="fa-solid fa-user-plus" style="color: #0A5F63;"></i> Buatkan Akun Portal Peserta</h3>
            <p>Akun ini akan langsung aktif dan dapat digunakan mahasiswa untuk login portal absensi & logbook.</p>

            <form id="formBuatAkun" method="POST" action="">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">Nama Mahasiswa:</label>
                    <input type="text" id="modalMhsNama" class="filter-select" style="width: 100%; background: #F8FAFC;" readonly>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">Username / Login:</label>
                    <input type="text" id="modalMhsNim" class="filter-select" style="width: 100%; background: #F8FAFC;" readonly>
                    <small style="color: #64748B;">Username otomatis menggunakan NIM mahasiswa.</small>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">Tentukan Password Akun:</label>
                    <input type="text" name="password" class="filter-select" style="width: 100%;" value="mhs123" placeholder="Minimal 6 karakter..." required>
                    <small style="color: #059669;">Default disarankan: <code>mhs123</code></small>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-action" style="padding: 8px 16px; border-radius: 8px;" onclick="closeModal('modalBuatAkun')">Batal</button>
                    <button type="submit" class="btn-header-action" style="background: #0A5F63; color: #fff;">
                        <i class="fa-solid fa-check"></i> Buat Akun Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL RESET PASSWORD -->
    <div id="modalResetPass" class="modal-overlay">
        <div class="modal-card">
            <h3><i class="fa-solid fa-key" style="color: #0A5F63;"></i> Reset Password Akun Peserta</h3>
            <p>Masukkan password baru untuk mahasiswa yang bersangkutan.</p>

            <form id="formResetPass" method="POST" action="">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">Mahasiswa:</label>
                    <input type="text" id="modalResetNama" class="filter-select" style="width: 100%; background: #F8FAFC;" readonly>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">Password Baru:</label>
                    <input type="text" name="new_password" class="filter-select" style="width: 100%;" value="mhs123" placeholder="Minimal 6 karakter..." required>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-action" style="padding: 8px 16px; border-radius: 8px;" onclick="closeModal('modalResetPass')">Batal</button>
                    <button type="submit" class="btn-header-action" style="background: #0A5F63; color: #fff;">
                        <i class="fa-solid fa-check"></i> Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openCreateAccModal(id, nama, nim) {
        document.getElementById('modalMhsNama').value = nama;
        document.getElementById('modalMhsNim').value = nim;
        document.getElementById('formBuatAkun').action = '/superadmin/peserta/' + id + '/buat-akun';
        document.getElementById('modalBuatAkun').classList.add('active');
    }

    function openResetModal(id, nama) {
        document.getElementById('modalResetNama').value = nama;
        document.getElementById('formResetPass').action = '/superadmin/peserta/' + id + '/reset-password';
        document.getElementById('modalResetPass').classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }
    </script>
</body>
</html>
