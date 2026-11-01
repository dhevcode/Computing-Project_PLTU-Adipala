<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Pembimbing Lapangan - Superadmin PLTU Adipala</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/superadmin/dashboard.css') }}">
    <style>
        .mentor-badge {
            background: #E0F2FE;
            color: #0369A1;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-view-mhs {
            background: #0A5F63;
            color: #ffffff;
            border: none;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        .btn-view-mhs:hover {
            background: #074B4E;
            transform: translateY(-1px);
        }
        /* MODAL STYLING */
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
        .modal-overlay.active {
            display: flex;
        }
        .modal-card {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 820px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            animation: modalFadeIn 0.2s ease;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-header h3 {
            font-size: 17px;
            font-weight: 700;
            color: #0F172A;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            color: #64748B;
            cursor: pointer;
            padding: 4px;
        }
        .modal-close:hover { color: #DC2626; }
        .modal-body {
            padding: 24px;
            overflow-y: auto;
        }
        .sub-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .sub-table th {
            background: #F8FAFC;
            padding: 10px 14px;
            text-align: left;
            font-weight: 700;
            color: #475569;
            border-bottom: 1.5px solid #E2E8F0;
        }
        .sub-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #F1F5F9;
            vertical-align: middle;
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

            <a href="{{ route('superadmin.pembimbing') }}" class="btn-header-nav active">
                <i class="fa-solid fa-user-tie"></i>
                <span>Pembimbing Lapangan</span>
            </a>

            <a href="{{ route('superadmin.peserta') }}" class="btn-header-nav">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Peserta Magang</span>
            </a>

            <a href="{{ route('superadmin.create') }}" class="btn-header-action">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah Akun</span>
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
                    <h4>Total Pembimbing Lapangan</h4>
                    <div class="stat-value">{{ $totalPembimbing ?? 0 }}</div>
                </div>
                <div class="stat-icon primary">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h4>Total Mahasiswa Dibimbing</h4>
                    <div class="stat-value">{{ $totalMahasiswaDibimbing ?? 0 }}</div>
                </div>
                <div class="stat-icon success">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h4>Pembimbing Aktif</h4>
                    <div class="stat-value">{{ $pembimbingAktif ?? 0 }}</div>
                </div>
                <div class="stat-icon warning">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
        </div>

        <!-- MAIN TABLE CARD -->
        <div class="card">
            <div class="card-header">
                <div class="card-title-group">
                    <h3>Data Pembimbing Lapangan & Mahasiswa Bimbingan</h3>
                    <p>Pantau beban pembimbingan, divisi penugasan, dan daftar mahasiswa yang magang bersama setiap mentor.</p>
                </div>
                <div class="table-controls">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="searchInput" placeholder="Cari pembimbing, divisi, email..." onkeyup="filterTable()">
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table id="pembimbingTable">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Pembimbing Lapangan</th>
                            <th>Username / NIP</th>
                            <th>Divisi / Bagian</th>
                            <th style="text-align: center;">Jumlah Bimbingan</th>
                            <th>Status Akun</th>
                            <th style="text-align: center;">Mahasiswa Bimbingan</th>
                            <th style="width: 80px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembimbingData as $index => $item)
                            @php
                                $mentor = $item['user'];
                                $mhsList = $item['daftar_bimbingan'];
                            @endphp
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #94A3B8;">{{ $index + 1 }}</td>
                                <td>
                                    <div class="user-cell">
                                        <div class="avatar-circle" style="background: linear-gradient(135deg, #0A5F63 0%, #00828A 100%);">
                                            {{ strtoupper(substr($mentor->nama_lengkap ?? 'P', 0, 1)) }}
                                        </div>
                                        <div class="user-info">
                                            <span class="name">{{ $mentor->nama_lengkap }}</span>
                                            <span class="email">{{ $mentor->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td><code style="background: #F1F5F9; padding: 3px 8px; border-radius: 6px; color: #0A5F63; font-weight: 600;">{{ $mentor->username }}</code></td>
                                <td>
                                    <span style="font-weight: 600; color: #334155;">
                                        {{ $mentor->divisi->nama_divisi ?? 'Belum Diatur' }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="mentor-badge">
                                        <i class="fa-solid fa-users"></i> {{ $item['total_mahasiswa'] }} Mahasiswa
                                    </span>
                                    @if($item['sedang_magang'] > 0)
                                        <div style="font-size: 11px; color: #059669; font-weight: 600; margin-top: 3px;">
                                            {{ $item['sedang_magang'] }} Aktif Magang
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($mentor->is_active)
                                        <span class="badge badge-active"><span class="badge-dot"></span> Aktif</span>
                                    @else
                                        <span class="badge badge-inactive"><span class="badge-dot"></span> Nonaktif</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if($item['total_mahasiswa'] > 0)
                                        <button type="button" class="btn-view-mhs" onclick="showMhsModal({{ $mentor->id_users }})">
                                            <i class="fa-solid fa-list-check"></i>
                                            <span>Lihat Mahasiswa ({{ $item['total_mahasiswa'] }})</span>
                                        </button>
                                    @else
                                        <span style="font-size: 12px; color: #94A3B8; font-style: italic;">Belum ada bimbingan</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="action-group" style="justify-content: center;">
                                        <a href="{{ route('superadmin.edit', $mentor->id_users) }}" class="btn-action btn-edit" title="Edit Akun Pembimbing">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- HIDDEN MODAL DATA CONTAINER -->
                            @if($item['total_mahasiswa'] > 0)
                                <div id="modal_mentor_{{ $mentor->id_users }}" class="modal-overlay" onclick="closeModalOnBg(event, {{ $mentor->id_users }})">
                                    <div class="modal-card">
                                        <div class="modal-header">
                                            <h3>
                                                <i class="fa-solid fa-user-graduate" style="color: #0A5F63;"></i>
                                                Mahasiswa Bimbingan: {{ $mentor->nama_lengkap }}
                                            </h3>
                                            <button type="button" class="modal-close" onclick="closeMhsModal({{ $mentor->id_users }})">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div style="margin-bottom: 14px; font-size: 13px; color: #475569;">
                                                Divisi: <strong>{{ $mentor->divisi->nama_divisi ?? '-' }}</strong> &bull; Total Mahasiswa: <strong>{{ $item['total_mahasiswa'] }} Orang</strong>
                                            </div>

                                            <table class="sub-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 40px;">No</th>
                                                        <th>Nama Mahasiswa</th>
                                                        <th>NIM</th>
                                                        <th>Asal Kampus / Sekolah</th>
                                                        <th>Periode Magang</th>
                                                        <th style="text-align: center;">Status Magang</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($mhsList as $idx => $p)
                                                        @php $m = $p->mahasiswa; @endphp
                                                        <tr>
                                                            <td style="color: #94A3B8; font-weight: 600;">{{ $idx + 1 }}</td>
                                                            <td>
                                                                <strong style="color: #0F172A;">{{ $m->nama_lengkap ?? '-' }}</strong><br>
                                                                <small style="color: #64748B;">{{ $m->jurusan->nama_jurusan ?? '-' }}</small>
                                                            </td>
                                                            <td><code>{{ $m->nim ?? '-' }}</code></td>
                                                            <td>{{ $m->universitas->nama_universitas ?? '-' }}</td>
                                                            <td style="font-size: 12px;">
                                                                {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}
                                                            </td>
                                                            <td style="text-align: center;">
                                                                @if($p->status === 'Sedang Magang')
                                                                    <span class="badge badge-active" style="padding: 4px 10px;">Sedang Magang</span>
                                                                @elseif($p->status === 'Akan Magang')
                                                                    <span class="badge" style="background: #EEF2FF; color: #4338CA; padding: 4px 10px;">Akan Magang</span>
                                                                @else
                                                                    <span class="badge badge-inactive" style="padding: 4px 10px;">Selesai</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-user-slash"></i>
                                        <p>Belum ada data pembimbing lapangan terdaftar dalam sistem.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
    function showMhsModal(id) {
        const modal = document.getElementById('modal_mentor_' + id);
        if (modal) modal.classList.add('active');
    }

    function closeMhsModal(id) {
        const modal = document.getElementById('modal_mentor_' + id);
        if (modal) modal.classList.remove('active');
    }

    function closeModalOnBg(e, id) {
        if (e.target.classList.contains('modal-overlay')) {
            closeMhsModal(id);
        }
    }

    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("pembimbingTable");
        const tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            let rowText = tr[i].textContent.toLowerCase();
            if (rowText.includes(filter)) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
    </script>
</body>
</html>
