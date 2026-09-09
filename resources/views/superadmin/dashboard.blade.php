<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - Monitoring PKL PLTU</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/superadmin/dashboard.css') }}">
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
            <a href="{{ route('superadmin.create') }}" class="btn-header-action">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah Admin</span>
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

        <!-- QUICK STATS WIDGETS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Total Administrator</h4>
                    <div class="stat-value">{{ $admins->count() ?? 0 }}</div>
                </div>
                <div class="stat-icon primary">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h4>Akun Aktif</h4>
                    <div class="stat-value">{{ $admins->where('is_active', 1)->count() ?? 0 }}</div>
                </div>
                <div class="stat-icon success">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h4>Divisi Terdaftar</h4>
                    <div class="stat-value">{{ $admins->pluck('id_divisi')->unique()->count() ?? 0 }}</div>
                </div>
                <div class="stat-icon warning">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
            </div>
        </div>

        <!-- MAIN DATA TABLE CARD -->
        <div class="card">
            <div class="card-header">
                <div class="card-title-group">
                    <h3>Daftar Administrator Sistem</h3>
                    <p>Kelola hak akses, status akun, dan penugasan divisi admin PKL.</p>
                </div>
                <div class="table-controls">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="searchInput" placeholder="Cari admin, username, divisi..." onkeyup="filterTable()">
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="table-wrapper">
                <table id="adminTable">
                    <thead>
                        <tr>
                            <th style="width: 60px; text-align: center;">No</th>
                            <th>Nama & Email</th>
                            <th>Username</th>
                            <th>Bagian / Divisi</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th style="width: 100px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $index => $admin)
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #94A3B8;">{{ $index + 1 }}</td>
                                <td>
                                    <div class="user-cell">
                                        <div class="avatar-circle">
                                            {{ strtoupper(substr($admin->nama_lengkap ?? 'A', 0, 1)) }}
                                        </div>
                                        <div class="user-info">
                                            <span class="name">{{ $admin->nama_lengkap }}</span>
                                            <span class="email">{{ $admin->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td><code style="background: #F1F5F9; padding: 3px 8px; border-radius: 6px; color: #0A5F63; font-weight: 600;">{{ $admin->username }}</code></td>
                                <td>
                                    <span style="font-weight: 600; color: #334155;">
                                        {{ $admin->divisi->nama_divisi ?? 'Belum Diatur' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-role">
                                        <i class="fa-solid fa-shield-halved" style="font-size: 10px;"></i>
                                        {{ $admin->role }}
                                    </span>
                                </td>
                                <td>
                                    @if($admin->is_active)
                                        <span class="badge badge-active">
                                            <span class="badge-dot"></span> Aktif
                                        </span>
                                    @else
                                        <span class="badge badge-inactive">
                                            <span class="badge-dot"></span> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-group" style="justify-content: center;">
                                        <a href="{{ route('superadmin.edit', $admin->id_users) }}" class="btn-action btn-edit" title="Edit Admin">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('superadmin.destroy', $admin->id_users) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin ini?');" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Hapus Admin">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-user-slash"></i>
                                        <p>Belum ada data admin terdaftar.</p>
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
        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("adminTable");
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