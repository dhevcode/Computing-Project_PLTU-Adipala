<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Super Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#EEF2F5;
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

.content{

    margin-top: 25px;
    padding:70px 14px 14px;

}

.card{

    background:white;

    border-radius:14px;

    padding:18px;

    min-height:340px;

    box-shadow:
    0 2px 10px rgba(0,0,0,.08);

}

.card-title{

    color:#0A6B70;

    font-size:18px;

    font-weight:700;

}

.card-subtitle{

    color:#6F6F6F;

    font-size:11px;

    margin-bottom:12px;

}

/* ================= TABLE ================= */

.table-admin{

    width:100%;
    border-collapse:collapse;

}

.table-admin thead th{

    text-align:left;

    font-size:11px;

    font-weight:500;

    color:#4E4E4E;

    padding:10px 4px;

    border-bottom:1px solid #D8D8D8;

}

.table-admin tbody td{

    font-size:12px;

    color:#2F2F2F;

    padding:12px 4px;

    border-bottom:1px solid #D8D8D8;

}

.status{

    width:56px;
    height:22px;

    background:#0A5E86;

    border-radius:999px;

    color:white;

    display:flex;
    justify-content:center;
    align-items:center;

    font-size:10px;

    font-weight:500;

}

.action{

    display:flex;
    gap:10px;

}

.edit{

    color:#444;
}

.delete{

    color:#FF5A5A;
}

</style>

</head>

<body>

<header class="header">

    <div class="header-left">

        <div class="logo-box">

            <img src="{{ asset('assets/image/logo-pln.png') }}">

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

        <a href="#" class="logout">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a>

    </div>

</header>

<div class="content">

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
            <th>Password</th>
            <th>Bagian</th>
            <th>Role</th>
            <th>Status</th>
            <th>Aksi</th>

        </tr>

        </thead>

        <tbody>

        <tr>

            <td>James</td>
            <td>adminjames</td>
            <td>098</td>
            <td>Umum</td>
            <td>Admin</td>

            <td>
                <div class="status">
                    Aktif
                </div>
            </td>

            <td>

                <div class="action">

                    <i class="fa-regular fa-pen-to-square edit"></i>

                    <i class="fa-regular fa-trash-can delete"></i>

                </div>

            </td>

        </tr>

                <tr>

            <td>James</td>
            <td>adminjames</td>
            <td>098</td>
            <td>Umum</td>
            <td>Admin</td>

            <td>
                <div class="status">
                    Aktif
                </div>
            </td>

            <td>

                <div class="action">

                    <i class="fa-regular fa-pen-to-square edit"></i>

                    <i class="fa-regular fa-trash-can delete"></i>

                </div>

            </td>

        </tr>

        <tr>

            <td>James</td>
            <td>adminjames</td>
            <td>098</td>
            <td>Umum</td>
            <td>Admin</td>

            <td>
                <div class="status">
                    Aktif
                </div>
            </td>

            <td>

                <div class="action">

                    <i class="fa-regular fa-pen-to-square edit"></i>

                    <i class="fa-regular fa-trash-can delete"></i>

                </div>

            </td>

        </tr>

        <tr>

            <td>James</td>
            <td>adminjames</td>
            <td>098</td>
            <td>Umum</td>
            <td>Admin</td>

            <td>
                <div class="status">
                    Aktif
                </div>
            </td>

            <td>

                <div class="action">

                    <i class="fa-regular fa-pen-to-square edit"></i>

                    <i class="fa-regular fa-trash-can delete"></i>

                </div>

            </td>

        </tr>

        <tr>

            <td>James</td>
            <td>adminjames</td>
            <td>098</td>
            <td>Umum</td>
            <td>Admin</td>

            <td>
                <div class="status">
                    Aktif
                </div>
            </td>

            <td>

                <div class="action">

                    <i class="fa-regular fa-pen-to-square edit"></i>

                    <i class="fa-regular fa-trash-can delete"></i>

                </div>

            </td>

        </tr>

        </tbody>

    </table>

</div>

</div>

</body>
</html>