<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background: #EEF2F5;
            overflow-x: hidden;

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

        .container {

            padding: 95px 18px 18px;

        }

        .cards {

            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 18px;

        }

        /* ================= CARD ================= */

        .card {

            background: white;

            border-radius: 14px;

            padding: 18px;

            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);

            position: relative;

            border: 1px solid #E5E8EC;

        }

        .card.green {

            background: #0A777B;

            color: white;

            border: none;

        }

        .card-title {

            font-size: 13px;

            font-weight: 600;

            margin-bottom: 10px;

        }

        .card-value {

            font-size: 28px;

            font-weight: 700;

        }

        .card-desc {

            font-size: 11px;

            margin-top: 5px;

            font-weight: 500;

        }

        .card:not(.green) .card-value {

            color: #0A5F63;

        }

        .card:not(.green) .card-desc {

            color: #0A5F63;

        }

        .icon-circle {

            position: absolute;

            right: 16px;

            top: 16px;

            width: 28px;

            height: 28px;

            border-radius: 50%;

            display: flex;

            justify-content: center;

            align-items: center;

            background: rgba(255, 255, 255, .18);

        }

        .card:not(.green) .icon-circle {

            background: #E8F5F5;

            color: #0A5F63;

        }

        @media(max-width:1100px) {

            .cards {

                grid-template-columns: repeat(2, 1fr);

            }

        }

        @media(max-width:700px) {

            .header {

                height: auto;

                padding: 10px;

                flex-direction: column;

                align-items: flex-start;

                gap: 10px;

            }

            .header-right {

                padding: 0;

            }

            .cards {

                grid-template-columns: 1fr;

            }

            .logo-box {

                clip-path: none;

                border-radius: 8px;

            }

        }

        /* ================= GRAPH ================= */

        .chart-grid {

            margin-top: 18px;

            display: grid;

            grid-template-columns: 2fr 1fr;

            gap: 18px;

        }

        .chart-card {

            background: white;

            border-radius: 14px;

            padding: 18px;

            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);

            border: 1px solid #E5E8EC;

        }

        .chart-header h3 {

            font-size: 22px;

            font-weight: 700;

            color: #0A5F63;

        }

        .chart-header p {

            font-size: 12px;

            color: #7A7A7A;

            margin-top: 3px;

        }

        .chart-area {

            height: 300px;

            margin-top: 15px;

        }

        .status-card {

            display: flex;

            flex-direction: column;

        }

        .donut-wrapper {

            height: 220px;

            display: flex;

            justify-content: center;

            align-items: center;

        }

        .legend {

            display: flex;

            justify-content: center;

            gap: 15px;

            margin-top: 5px;

            font-size: 12px;

        }

        .legend span {

            display: flex;

            align-items: center;

            gap: 6px;

        }

        .dot {

            width: 10px;

            height: 10px;

            border-radius: 50%;

            display: inline-block;

        }

        .dot.aktif {

            background: #0A5F63;

        }

        .dot.datang {

            background: #11B5B3;

        }

        .dot.selesai {

            background: #062E43;

        }

        canvas {

            width: 100% !important;

            height: 100% !important;

        }

        @media(max-width:1000px) {

            .chart-grid {

                grid-template-columns: 1fr;

            }

        }

        .university-card {

            margin-top: 18px;

        }

        .chart-area-univ {

            height: 180px;

            margin-top: 12px;

        }

        /* ================= FILTER ================= */

        .filter-card {

            margin-top: 20px;

            background: white;

            padding: 22px;

            border-radius: 14px;

            border: 1px solid #E5E8EC;

            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);

        }

        .filter-top {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 20px;

        }

        .filter-top h3 {

            font-size: 22px;

            color: #0A5F63;

            font-weight: 700;

        }

        .filter-top span {

            font-size: 12px;

            color: #8A8A8A;

        }

        .filter-button {

            display: flex;

            gap: 10px;

        }

        .btn-reset {

            height: 38px;

            padding: 0 18px;

            background: #F2F4F7;

            border: none;

            border-radius: 8px;

            cursor: pointer;

            font-weight: 600;

        }

        .btn-export {

            height: 38px;

            padding: 0 18px;

            background: #0A5F63;

            color: white;

            border: none;

            border-radius: 8px;

            cursor: pointer;

            font-weight: 600;

            display: flex;

            align-items: center;

            gap: 8px;

        }

        .filter-grid {

            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 16px;

        }

        .input-group {

            display: flex;

            flex-direction: column;

        }

        .input-group label {

            font-size: 12px;

            font-weight: 600;

            margin-bottom: 6px;

            color: #5F6368;

        }

        .input-group input,

        .input-group select {

            height: 42px;

            padding: 0 14px;

            border: 1px solid #D8DEE5;

            border-radius: 8px;

            outline: none;

            font-size: 13px;

            background: white;

            transition: .2s;

        }

        .input-group input:focus,

        .input-group select:focus {

            border-color: #0A5F63;

            box-shadow: 0 0 0 3px rgba(10, 95, 99, .12);

        }

        .btn-reset:hover {

            background: #E8EBEF;

        }

        .btn-export:hover {

            background: #094D51;

        }

        @media(max-width:1100px) {

            .filter-grid {

                grid-template-columns: repeat(2, 1fr);

            }

        }

        @media(max-width:700px) {

            .filter-top {

                flex-direction: column;

                align-items: flex-start;

                gap: 15px;

            }

            .filter-grid {

                grid-template-columns: 1fr;

            }

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

            <a href="{{ route('mahasiswa.create') }}" class="btn-add">
                <i class="fa-solid fa-plus"></i>
                Tambah Mahasiswa
            </a>

            <a href="#" class="logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>

        </div>

    </header>

    <div class="container">

        <div class="cards">

            <div class="card green">

                <div class="icon-circle">

                    <i class="fa-regular fa-calendar"></i>

                </div>

                <div class="card-title">
                    Magang hari ini
                </div>

                <div class="card-value">
                    {{ $magangHariIni }}
                </div>

                <div class="card-desc">
                    Mahasiswa aktif
                </div>

            </div>

            <div class="card">

                <div class="icon-circle">

                    <i class="fa-solid fa-users"></i>

                </div>

                <div class="card-title">
                    Total Mahasiswa
                </div>

                <div class="card-value">
                    {{ $totalMahasiswa }}
                </div>

                <div class="card-desc">
                    {{ $belumSelesai }} belum selesai
                </div>

            </div>

            <div class="card">

                <div class="icon-circle">

                    <i class="fa-regular fa-clock"></i>

                </div>

                <div class="card-title">
                    Akan datang
                </div>

                <div class="card-value">
                    {{ $akanDatang }}
                </div>

                <div class="card-desc">
                    Belum mulai magang
                </div>

            </div>

            <div class="card">

                <div class="icon-circle">

                    <i class="fa-solid fa-briefcase"></i>

                </div>

                <div class="card-title">
                    Divisi terlibat
                </div>

                <div class="card-value">
                    {{ $divisiTerlibat }}
                </div>

                <div class="card-desc">
                    Penempatan aktif
                </div>

            </div>

        </div>

        <div class="chart-grid">

            <div class="chart-card">

                <div class="chart-header">

                    <h3>Mahasiswa setiap bidang</h3>

                    <p>Mengikuti filter aktif</p>

                </div>

                <div class="chart-area">

                    <canvas id="bidangChart"></canvas>

                </div>

            </div>

            <div class="chart-card status-card">

                <div class="chart-header">

                    <h3>Status magang</h3>

                    <p>
                        Visualisasi status mahasiswa berdasarkan
                        tahapan pelaksanaan magang.
                    </p>

                </div>

                <div class="donut-wrapper">

                    <canvas id="statusChart"></canvas>

                </div>

                <div class="legend">

                    <span><i class="dot aktif"></i>Aktif</span>

                    <span><i class="dot datang"></i>Akan Datang</span>

                    <span><i class="dot selesai"></i>Selesai</span>

                </div>



            </div>



        </div>
        <div class="chart-card university-card">

            <div class="chart-header">
                <h3>Sebaran Universitas</h3>
                <p>Menampilkan distribusi mahasiswa PKL berdasarkan asal universitas.</p>
            </div>

            <div class="chart-area-univ">
                <canvas id="universitasChart"></canvas>
            </div>

        </div>

        <!-- ================= FILTER ================= -->

        <div class="filter-card">

            <div class="filter-top">

                <div>

                    <h3>Filter & Export Data</h3>

                    <span>Menampilkan 4 dari 4 mahasiswa</span>

                </div>

                <div class="filter-button">

                    <a href="{{ request()->url() }}" class="btn-reset" style="text-decoration: none; display: inline-flex; align-items: center;">

                        Reset Filter

                    </a>

                    <button class="btn-export" form="filterForm" type="submit">

                        <i class="fa-solid fa-download"></i>

                        Terapkan Filter

                    </button>

                </div>

            </div>

            <form id="filterForm" method="GET" action="{{ request()->url() }}" class="filter-grid">

                <div class="input-group">

                    <label>Cari</label>

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, NIM, Kampus...">

                </div>

                <div class="input-group">

                    <label>Status</label>

                    <select name="status">

                        <option value="">Semua</option>

                        <option value="Sedang Magang" {{ request('status') == 'Sedang Magang' ? 'selected' : '' }}>Aktif</option>

                        <option value="Selesai Magang" {{ request('status') == 'Selesai Magang' ? 'selected' : '' }}>Selesai</option>

                        <option value="Akan Magang" {{ request('status') == 'Akan Magang' ? 'selected' : '' }}>Akan Datang</option>

                    </select>

                </div>

                <div class="input-group">

                    <label>Jenis Kelamin</label>

                    <select name="jenis_kelamin">

                        <option value="">Semua</option>

                        <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>

                        <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>

                    </select>

                </div>

                <div class="input-group">

                    <label>Tanggal Mulai</label>

                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">

                </div>

                <div class="input-group">

                    <label>Tanggal Selesai</label>

                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">

                </div>

                <div class="input-group">

                    <label>Jurusan</label>

                    <select name="jurusan_id">

                        <option value="">Semua</option>

                        @foreach ($listJurusan as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan_id') == $jurusan->id_jurusan ? 'selected' : '' }}>
                                {{ $jurusan->nama_jurusan }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="input-group">

                    <label>Universitas</label>

                    <select name="universitas_id">

                        <option value="">Semua</option>

                        @foreach ($listUniversitas as $universitas)
                            <option value="{{ $universitas->id_universitas }}" {{ request('universitas_id') == $universitas->id_universitas ? 'selected' : '' }}>
                                {{ $universitas->nama_universitas }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="input-group">

                    <label>Divisi</label>

                    <select name="divisi_id">

                        <option value="">Semua</option>

                        @foreach ($listDivisi as $divisi)
                            <option value="{{ $divisi->id_divisi }}" {{ request('divisi_id') == $divisi->id_divisi ? 'selected' : '' }}>
                                {{ $divisi->nama_divisi }}
                            </option>
                        @endforeach

                    </select>

                </div>

            </form>

        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        new Chart(document.getElementById('bidangChart'), {

            type: 'bar',

            data: {

                labels: @json($bidangLabels),

                datasets: [{

                    data: @json($bidangData),

                    backgroundColor: '#2B7C82',

                    borderRadius: 4,

                    barThickness: 60

                }]

            },

            options: {

                plugins: {

                    legend: {

                        display: false

                    }

                },

                maintainAspectRatio: false,

                responsive: true,

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            stepSize: 1

                        },

                        grid: {

                            color: '#D9D9D9'

                        }

                    },

                    x: {

                        grid: {

                            display: false

                        }

                    }

                }

            }

        });

        new Chart(document.getElementById('statusChart'), {

            type: 'doughnut',

            data: {

                labels: ['Aktif', 'Akan Datang', 'Selesai'],

                datasets: [{

                    data: @json([$statusAktif, $statusAkanDatang, $statusSelesai]),

                    backgroundColor: [

                        '#0A5F63',

                        '#11B5B3',

                        '#062E43'

                    ],

                    borderWidth: 0

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '63%',

                plugins: {

                    legend: {

                        display: false

                    }

                }

            }

        });

        new Chart(document.getElementById('universitasChart'), {

            type: 'bar',

            data: {

                labels: @json($univLabels),

                datasets: [{

                    data: @json($univData),

                    backgroundColor: (context) => {

                        const chart = context.chart;
                        const {
                            ctx,
                            chartArea
                        } = chart;

                        if (!chartArea) return '#0E9C9A';

                        const gradient = ctx.createLinearGradient(
                            0,
                            0,
                            chartArea.right,
                            0
                        );

                        gradient.addColorStop(0, '#139D99');
                        gradient.addColorStop(1, '#0A5F63');

                        return gradient;

                    },

                    borderRadius: 4,

                    borderSkipped: false

                }]

            },

            options: {

                indexAxis: 'y',

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: false

                    }

                },

                scales: {

                    x: {

                        beginAtZero: true,

                        suggestedMax: 10,

                        grid: {

                            display: false

                        },

                        ticks: {

                            stepSize: 1,

                            color: '#666'

                        }

                    },

                    y: {

                        grid: {

                            display: false

                        },

                        ticks: {

                            color: '#555',

                            font: {

                                size: 11,

                                weight: '500'

                            }

                        }

                    }

                }

            }

        });
    </script>
</body>

</html>
