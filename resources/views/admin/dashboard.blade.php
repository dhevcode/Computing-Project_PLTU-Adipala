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
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
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