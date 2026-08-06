<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Super Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image:url('{{ asset("assets/image/bg-pln.png") }}');

            background-size: cover;

            background-position: center;

            background-repeat: no-repeat;

            background-attachment: fixed;
        }

        /* ================= HEADER ================= */

        .header {

            position: fixed;

            top: 0;

            left: 0;

            right: 0;

            height: 72px;

            background: #0A5F63;

            display: flex;

            justify-content: space-between;

            align-items: center;

            z-index: 9999;

            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);

        }

        /* Logo */

        .logo-box {

            width: 230px;

            height: 72px;

            background: white;

            clip-path: polygon(0 0, 100% 0, 82% 100%, 0 100%);

            display: flex;

            justify-content: center;

            align-items: center;

        }

        .logo-box img {

            width: 150px;

        }



        /* Title */

        .header-left {

            display: flex;

            align-items: center;

            gap: 18px;

        }

        .header-title {

            margin-left: 16px;

            color: white;

        }

        .header-title h2 {

            font-size: 18px;

            font-weight: 700;

            line-height: 1;

        }

        .header-title p {

            font-size: 10px;

            margin-top: 3px;

            opacity: .9;

        }

        /* Right */

        .header-right {

            display: flex;

            align-items: center;

            gap: 10px;

            padding-right: 16px;

        }

        .btn-add {

            background: white;

            border: none;

            height: 32px;

            padding: 0 15px;

            border-radius: 7px;

            font-size: 12px;

            font-weight: 600;

            color: #0A5F63;

            cursor: pointer;

            display: flex;

            align-items: center;

            gap: 7px;

            transition: .2s;

        }

        .btn-add:hover {

            transform: translateY(-2px);

        }

        .logout {

            width: 32px;

            height: 32px;

            display: flex;

            align-items: center;

            justify-content: center;

            color: white;

            cursor: pointer;

            border-radius: 8px;

            transition: .2s;

        }

        .logout:hover {

            background: rgba(255, 255, 255, .15);

        }

        /* ================= CONTENT ================= */

        .content {

            margin-top: 25px;
            padding: 70px 14px 14px;

        }

        .card {

            background: white;

            border-radius: 14px;

            padding: 18px;

            min-height: 340px;

            box-shadow:
                0 2px 10px rgba(0, 0, 0, .08);

        }

        .card-title {

            color: #0A6B70;

            font-size: 18px;

            font-weight: 700;

        }

        .card-subtitle {

            color: #6F6F6F;

            font-size: 11px;

            margin-bottom: 12px;

        }

        /* ================= TABLE ================= */

        .table-admin {

            width: 100%;
            border-collapse: collapse;

        }

        .table-admin thead th {

            text-align: left;

            font-size: 11px;

            font-weight: 500;

            color: #4E4E4E;

            padding: 10px 4px;

            border-bottom: 1px solid #D8D8D8;

        }

        .table-admin tbody td {

            font-size: 12px;

            color: #2F2F2F;

            padding: 12px 4px;

            border-bottom: 1px solid #D8D8D8;

        }

        .status {

            width: 56px;
            height: 22px;

            background: #0A5E86;

            border-radius: 999px;

            color: white;

            display: flex;
            justify-content: center;
            align-items: center;

            font-size: 10px;

            font-weight: 500;

        }

        .action {

            display: flex;
            gap: 10px;

        }

        .edit {

            color: #444;
        }

        .delete {

            color: #FF5A5A;
        }
    </style>

</head>

<body>

    <header class="header">
        <div class="header-left">
            <div class="logo-box">
                <img src="{{ asset('assets/image/logo-pln.png') }}" alt="Logo PLN">
            </div>
            <div class="header-title">
                <h2>Monitoring Mahasiswa PKL</h2>
                <p>Kelola dan pantau data mahasiswa PKL dengan mudah dan efisien.</p>
            </div>
        </div>
        <div class="header-right">
            <a href="{{ route('superadmin.create') }}" class="btn-add">
                <i class="fa-solid fa-plus"></i>
                Tambah Admin
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout" style="background: none; border: none; cursor: pointer; color: white;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <div class="content">
        @if(session('success'))
        <div style="background: #e6f4f4; color: #0A5F63; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bfe3e3; font-size: 13px; font-weight: 600;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
        @endif

        <div class="card">
            <div class="card-title">
                Data Admin
            </div>
            <div class="card-subtitle">
                Menampilkan data admin
            </div>

            <table class="table-admin">
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Bagian</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr>
                        <td>{{ $admin->nama_lengkap }}</td>
                        <td>{{ $admin->username }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->divisi->nama_divisi ?? '-' }}</td>
                        <td>{{ $admin->role }}</td>
                        <td>
                            @if($admin->is_active)
                            <div class="status" style="background: #0A5E86;">
                                Aktif
                            </div>
                            @else
                            <div class="status" style="background: #D9534F;">
                                Nonaktif
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="action">
                                <a href="{{ route('superadmin.edit', $admin->id_users) }}" class="edit" title="Edit Admin">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('superadmin.destroy', $admin->id_users) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete" style="background: none; border: none; padding: 0; cursor: pointer;" title="Hapus Admin">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #888; padding: 20px;">
                            Belum ada data Admin yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>