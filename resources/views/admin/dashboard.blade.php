<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Magang</title>

    <!-- Font & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image: url('{{ asset("assets/image/bg-pln.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
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

        .logout {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;
            cursor: pointer;

            border-radius: 8px;

            background: none;
            border: none;

            font-size: 17px;

            transition: .2s;
        }

        .logout:hover {
            background: rgba(255, 255, 255, .15);
        }

        /* ================= LOGO ================= */
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

        /* ================= TITLE ================= */
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

        /* ================= HEADER RIGHT ================= */
        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-right: 16px;
        }

        /* ================= HAMBURGER BUTTON ================= */
        .menu-btn {
            width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            color: white;
            font-size: 21px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: .2s;
        }

        .menu-btn:hover {
            background: rgba(255, 255, 255, .12);
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            position: fixed;
            top: 82px;
            right: -270px;

            width: 250px;
            height: 470px;

            background: rgba(210, 210, 210, 0.96);
            backdrop-filter: blur(8px);

            border-radius: 25px;

            padding: 25px 18px;

            z-index: 9998;

            box-shadow: -5px 5px 20px rgba(0, 0, 0, .12);

            transition: right .3s ease;
        }

        /* Sidebar ketika dibuka */
        .sidebar.active {
            right: 10px;
        }


        /* ================= SIDEBAR MENU ================= */
        .sidebar-menu {
            display: flex;
            align-items: center;
            gap: 12px;

            width: 100%;
            padding: 11px 10px;

            margin-bottom: 8px;

            color: white;
            text-decoration: none;

            border-radius: 12px;

            font-size: 16px;
            font-weight: 400;

            transition: .2s;
        }

        .sidebar-menu i {
            width: 30px;
            font-size: 23px;
            text-align: center;
        }


        /* Hover */
        .sidebar-menu:hover {
            background: rgba(255, 255, 255, .15);
            transform: translateX(3px);
        }


        /* Menu aktif */
        .sidebar-menu.active {
            background: rgba(255, 255, 255, .15);
            font-weight: 500;
        }


        /* ================= SIDEBAR OVERLAY ================= */
        .sidebar-overlay {
            position: fixed;
            inset: 0;

            background: rgba(0, 0, 0, .15);

            z-index: 9997;

            opacity: 0;
            visibility: hidden;

            transition: .3s;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* ================= CONTENT ================= */
        .content {
            margin-top: 25px;
            padding: 70px 14px 14px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 20px;
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

        .card.green .card-title {
            color: rgba(255, 255, 255, 0.85);
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
            .cards {
                grid-template-columns: 1fr;
            }
        }

        /* ================= FILTER & TABLE CARD ================= */
        .table-card {
            background: white;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
            border: 1px solid #E5E8EC;
            margin-bottom: 20px;
        }

        .table-card-title {
            color: #0A6B70;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .table-card-subtitle {
            color: #6F6F6F;
            font-size: 11px;
            margin-bottom: 12px;
        }

        /* ================= FILTER DATA ================= */
        .filter-section {
            background: white;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
            border: 1px solid #E5E8EC;
            margin-bottom: 20px;
        }

        .filter-section h3 {
            font-size: 15px;
            font-weight: 700;
            color: #0A6B70;
            margin-bottom: 12px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .filter-action {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 18px;
        }

        .btn-filter {
            background: #0A5F63;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .btn-filter:hover {
            background: #084C50;
        }

        .btn-reset {
            background: #F3F4F6;
            color: #444;
            border: 1px solid #D8D8D8;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: .2s;
        }

        .btn-reset:hover {
            background: #E5E7EB;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .input-group label {
            font-size: 11px;
            font-weight: 600;
            color: #4E4E4E;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            height: 38px;
            border: 1px solid #E4E4E4;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 12px;
            outline: none;
            background: white;
            color: #444;
            transition: .2s;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #0A6D71;
            box-shadow: 0 0 0 3px rgba(10, 109, 113, .12);
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
            width: 90px;
            height: 22px;
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
            font-size: 14px;
        }

        .delete {
            color: #FF5A5A;
            font-size: 14px;
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

        .chart-card.full-width {
            grid-column: 1 / -1;
        }

        .chart-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: #0A5F63;
        }

        .chart-header p {
            font-size: 11px;
            color: #7A7A7A;
            margin-top: 3px;
        }

        .chart-body {
            position: relative;
            min-height: 260px;
            margin-top: 15px;
        }

        .doughnut-legend {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #555;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        @media (max-width: 992px) {
            .chart-grid {
                grid-template-columns: 1fr;
            }
        }

        .section-space {
            height: 30px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .table-header .table-card-title {
            margin: 0;
        }

        .table-header .btn-add {
            margin: 0;
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

        <!-- HEADER RIGHT -->
        <div class="header-right">

            <!-- Hamburger -->
            <button type="button" class="menu-btn" id="menuBtn" title="Menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf

                <button type="submit" class="logout" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>

        </div>

    </header>

    <!-- ================= SIDEBAR ================= -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

            <i class="fa-regular fa-chart-bar"></i>

            <span>Dashboard</span>

        </a>


        <!-- Data Mahasiswa -->
        <a href="{{ route('admin.data') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-user"></i>

            <span>Data Mahasiswa</span>

        </a>


        <!-- Dokumen Verification -->
        <a href="{{ route('admin.dokumen') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-file-lines"></i>

            <span>Dokumen Verification</span>

        </a>


        <!-- Sertifikat -->
        <a href="{{ route('admin.sertifikat') }}"
            class="sidebar-menu">

            <i class="fa-regular fa-file"></i>

            <span>Sertifikat</span>

        </a>


    </div>

    <div class="content">

        @if(session('success'))
        <div style="background: #e6f4f4; color: #0A5F63; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bfe3e3; font-size: 13px; font-weight: 600;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
        @endif

        <!-- CARDS STATISTIK -->
        <div class="cards">
            <div class="card green">
                <div class="icon-circle"><i class="fa-regular fa-calendar"></i></div>
                <div class="card-title">Magang Hari Ini</div>
                <div class="card-value">{{ str_pad($magangHariIni, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="card-desc">Mahasiswa aktif saat ini</div>
            </div>

            <div class="card">
                <div class="icon-circle"><i class="fa-solid fa-users"></i></div>
                <div class="card-title">Total Mahasiswa</div>
                <div class="card-value">{{ str_pad($totalMahasiswa, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="card-desc">{{ $belumSelesai }} belum selesai</div>
            </div>

            <div class="card">
                <div class="icon-circle"><i class="fa-regular fa-clock"></i></div>
                <div class="card-title">Akan Datang</div>
                <div class="card-value">{{ str_pad($akanDatang, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="card-desc">Belum mulai magang</div>
            </div>

            <div class="card">
                <div class="icon-circle"><i class="fa-solid fa-briefcase"></i></div>
                <div class="card-title">Divisi Terlibat</div>
                <div class="card-value">{{ str_pad($divisiTerlibat, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="card-desc">Penempatan aktif</div>
            </div>
        </div>

        <!-- CHARTS SECTION -->
        <div class="chart-grid">

            <!-- Grafik Batang Divisi -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Jumlah Mahasiswa per Divisi</h3>
                    <p>Total mahasiswa magang aktif per divisi penempatan</p>
                </div>
                <div class="chart-body">
                    <canvas
                        id="bidangChart"
                        data-labels='@json($bidangLabels)'
                        data-values='@json($bidangData)'>
                    </canvas>
                </div>
            </div>

            <!-- Grafik Donut Status Magang -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Status Magang</h3>
                    <p>Proporsi status magang mahasiswa saat ini</p>
                </div>
                <div class="chart-body">
                    <canvas
                        id="statusChart"
                        data-aktif="{{ $statusAktif }}"
                        data-akan-datang="{{ $statusAkanDatang }}"
                        data-selesai="{{ $statusSelesai }}"></canvas>
                </div>
                <div class="doughnut-legend">
                    <div class="legend-item">
                        <span class="legend-dot" style="background-color: #0A5F63;"></span> Aktif
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background-color: #11B5B3;"></span> Akan Datang
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background-color: #062E43;"></span> Selesai
                    </div>
                </div>
            </div>

            <!-- Grafik Horizontal Sebaran Universitas -->
            <div class="chart-card full-width">
                <div class="chart-header">
                    <h3>Sebaran Mahasiswa per Universitas</h3>
                    <p>Data institusi pendidikan mahasiswa magang</p>
                </div>
                <div class="chart-body" style="min-height: 300px;">
                    <canvas
                        id="universitasChart"
                        data-labels='@json($univLabels)'
                        data-values='@json($univData)'></canvas>
                </div>
            </div>
        </div>

        <div class="section-space"></div>

        
    </div>



    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // 1. CHART BIDANG / DIVISI
            const ctxBidang = document.getElementById('bidangChart');
            if (ctxBidang) {
                const bidangLabels = ctxBidang.dataset.labels ?
                    JSON.parse(ctxBidang.dataset.labels) : [];

                const bidangData = ctxBidang.dataset.values ?
                    JSON.parse(ctxBidang.dataset.values) : [];

                new Chart(ctxBidang, {
                    type: 'bar',
                    data: {
                        labels: bidangLabels,
                        datasets: [{
                            data: bidangData,
                            backgroundColor: '#2B7C82',
                            borderRadius: 6,
                            maxBarThickness: 50
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
                                    precision: 0,
                                    stepSize: 1
                                },
                                grid: {
                                    color: '#EAEAEA'
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
            }

            // 2. CHART STATUS MAGANG
            const ctxStatus = document.getElementById('statusChart');
            if (ctxStatus) {
                const statusAktif = JSON.parse(ctxStatus.dataset.aktif || '0');
                const statusAkanDatang = JSON.parse(ctxStatus.dataset.akanDatang || '0');
                const statusSelesai = JSON.parse(ctxStatus.dataset.selesai || '0');

                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: ['Aktif', 'Akan Datang', 'Selesai'],
                        datasets: [{
                            data: [statusAktif, statusAkanDatang, statusSelesai],
                            backgroundColor: ['#0A5F63', '#11B5B3', '#062E43'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            // 3. CHART SEBARAN UNIVERSITAS
            const ctxUniv = document.getElementById('universitasChart');
            if (ctxUniv) {
                const univLabels = JSON.parse(ctxUniv.dataset.labels || '[]');
                const univData = JSON.parse(ctxUniv.dataset.values || '[]');

                new Chart(ctxUniv, {
                    type: 'bar',
                    data: {
                        labels: univLabels,
                        datasets: [{
                            data: univData,
                            backgroundColor: (context) => {
                                const chart = context.chart;
                                const {
                                    ctx,
                                    chartArea
                                } = chart;
                                if (!chartArea) return '#0E9C9A';

                                const gradient = ctx.createLinearGradient(0, 0, chartArea.right, 0);
                                gradient.addColorStop(0, '#139D99');
                                gradient.addColorStop(1, '#0A5F63');
                                return gradient;
                            },
                            borderRadius: 4,
                            maxBarThickness: 30
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    precision: 0,
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
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {

            const menuBtn = document.getElementById('menuBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            // Buka / tutup sidebar
            menuBtn.addEventListener('click', function() {

                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');

            });


            // Tutup sidebar ketika klik area luar
            overlay.addEventListener('click', function() {

                sidebar.classList.remove('active');
                overlay.classList.remove('active');

            });

        });
    </script>
</body>


</html>