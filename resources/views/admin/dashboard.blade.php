<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Magang</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

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

    <div class="header-right">
        <button type="button" class="menu-btn" id="menuBtn">
            <i class="fa-solid fa-bars"></i>
        </button>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </button>
        </form>
    </div>
</header>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu active">
        <i class="fa-regular fa-chart-bar"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.data') }}" class="sidebar-menu">
        <i class="fa-regular fa-user"></i>
        <span>Data Mahasiswa</span>
    </a>

    <a href="{{ route('admin.dokumen') }}" class="sidebar-menu">
        <i class="fa-regular fa-file-lines"></i>
        <span>Dokumen Verification</span>
    </a>

    <a href="{{ route('admin.sertifikat') }}" class="sidebar-menu">
        <i class="fa-regular fa-file"></i>
        <span>Sertifikat</span>
    </a>
</div>

<div class="content">

    <!-- Header Dashboard -->
    <div class="page-header-card">
        <div class="page-header-content">
            <div class="page-icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>

            <div>
                <h1>Dashboard Admin</h1>
                <p>Ringkasan statistik mahasiswa PKL, status magang, divisi, dan sebaran universitas.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- ================= FILTER ================= -->

        <form method="GET"
            action="{{ route('admin.dashboard') }}"
            class="filter-section">

            <div class="filter-header">
                <div class="filter-icon">
                    <i class="fa-solid fa-filter"></i>
                </div>

                <div>
                    <h3>Filter Data Mahasiswa</h3>
                    <p>Gunakan filter untuk mencari data mahasiswa berdasarkan kriteria tertentu.</p>
                </div>
            </div>


            <div class="filter-grid">

                <!-- PENCARIAN -->

                <div class="input-group">
                    <label for="search">Pencarian</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nama / NIM...">
                </div>


                <!-- JURUSAN -->

                <div class="input-group">
                    <label for="jurusan_id">Jurusan</label>

                    <select name="jurusan_id" id="jurusan_id">
                        <option value="">Semua Jurusan</option>

                        @foreach($listJurusan as $j)
                        <option value="{{ $j->id_jurusan }}"
                            {{ request('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <!-- UNIVERSITAS -->

                <div class="input-group">
                    <label for="universitas_id">Universitas</label>

                    <select name="universitas_id" id="universitas_id">
                        <option value="">Semua Universitas</option>

                        @foreach($listUniversitas as $u)
                        <option value="{{ $u->id_universitas }}"
                            {{ request('universitas_id') == $u->id_universitas ? 'selected' : '' }}>
                            {{ $u->nama_universitas }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <!-- DIVISI -->

                <div class="input-group">
                    <label for="divisi_id">Divisi</label>

                    <select name="divisi_id" id="divisi_id">
                        <option value="">Semua Divisi</option>

                        @foreach($listDivisi as $d)
                        <option value="{{ $d->id_divisi }}"
                            {{ request('divisi_id') == $d->id_divisi ? 'selected' : '' }}>
                            {{ $d->nama_divisi }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <!-- JENIS KELAMIN -->

                <div class="input-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>

                    <select name="jenis_kelamin" id="jenis_kelamin">
                        <option value="">Semua Jenis Kelamin</option>

                        <option value="Laki-laki"
                            {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki
                        </option>

                        <option value="Perempuan"
                            {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>
                </div>


                <!-- TANGGAL MULAI -->

                <div class="input-group">
                    <label for="tanggal_mulai">Tanggal Masuk</label>

                    <input
                        type="date"
                        id="tanggal_mulai"
                        name="tanggal_mulai"
                        value="{{ request('tanggal_mulai') }}">
                </div>


                <!-- TANGGAL SELESAI -->

                <div class="input-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>

                    <input
                        type="date"
                        id="tanggal_selesai"
                        name="tanggal_selesai"
                        value="{{ request('tanggal_selesai') }}">
                </div>


                <!-- TAHUN -->

                <div class="input-group">
                    <label for="tahun">Tahun</label>

                    <select name="tahun" id="tahun">
                        <option value="">Semua Tahun</option>

                        @for($i = date('Y') + 2; $i >= 2020; $i--)
                        <option value="{{ $i }}"
                            {{ request('tahun') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                        @endfor
                    </select>
                </div>


                <!-- STATUS -->

                <div class="input-group">
                    <label for="status">Status Magang</label>

                    <select name="status" id="status">
                        <option value="">Semua Status</option>

                        <option value="Akan Magang"
                            {{ request('status') == 'Akan Magang' ? 'selected' : '' }}>
                            Akan Magang
                        </option>

                        <option value="Sedang Magang"
                            {{ request('status') == 'Sedang Magang' ? 'selected' : '' }}>
                            Sedang Magang
                        </option>

                        <option value="Selesai Magang"
                            {{ request('status') == 'Selesai Magang' ? 'selected' : '' }}>
                            Selesai Magang
                        </option>
                    </select>
                </div>

                

            </div>


            <!-- FILTER ACTIONS -->

            <div class="filter-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-reset">
                    <i class="fa-solid fa-rotate-left"></i>
                    Reset
                </a>

                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-filter"></i>
                    Terapkan Filter
                </button>
            </div>

        </form>

    <!-- Statistik -->
    <div class="cards">

        <div class="card green">
            <div class="icon-circle">
                <i class="fa-regular fa-calendar"></i>
            </div>

            <div class="card-title">Magang Hari Ini</div>
            <div class="card-value">{{ str_pad($magangHariIni,2,'0',STR_PAD_LEFT) }}</div>
            <div class="card-desc">Mahasiswa aktif saat ini</div>
        </div>

        <div class="card">
            <div class="icon-circle">
                <i class="fa-solid fa-users"></i>
            </div>

            <div class="card-title">Total Mahasiswa</div>
            <div class="card-value">{{ str_pad($totalMahasiswa,2,'0',STR_PAD_LEFT) }}</div>
            <div class="card-desc">{{ $belumSelesai }} mahasiswa belum selesai</div>
        </div>

        <div class="card">
            <div class="icon-circle">
                <i class="fa-regular fa-clock"></i>
            </div>

            <div class="card-title">Akan Datang</div>
            <div class="card-value">{{ str_pad($akanDatang,2,'0',STR_PAD_LEFT) }}</div>
            <div class="card-desc">Belum memulai magang</div>
        </div>

        <div class="card">
            <div class="icon-circle">
                <i class="fa-solid fa-building-user"></i>
            </div>

            <div class="card-title">Divisi Terlibat</div>
            <div class="card-value">{{ str_pad($divisiTerlibat,2,'0',STR_PAD_LEFT) }}</div>
            <div class="card-desc">Divisi penempatan aktif</div>
        </div>

    </div>

    <!-- Charts -->
    <div class="chart-grid">

        <div class="chart-card">
            <div class="chart-header">
                <h3>Jumlah Mahasiswa per Divisi</h3>
                <p>Total mahasiswa magang aktif pada setiap divisi penempatan.</p>
            </div>

            <div class="chart-body">
                <canvas
                    id="bidangChart"
                    data-labels='@json($bidangLabels)'
                    data-values='@json($bidangData)'>
                </canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <h3>Status Magang</h3>
                <p>Proporsi mahasiswa berdasarkan status magang.</p>
            </div>

            <div class="chart-body">
                <canvas
                    id="statusChart"
                    data-aktif="{{ $statusAktif }}"
                    data-akan-datang="{{ $statusAkanDatang }}"
                    data-selesai="{{ $statusSelesai }}">
                </canvas>
            </div>

            <div class="doughnut-legend">
                <div class="legend-item">
                    <span class="legend-dot active"></span>Aktif
                </div>

                <div class="legend-item">
                    <span class="legend-dot coming"></span>Akan Datang
                </div>

                <div class="legend-item">
                    <span class="legend-dot done"></span>Selesai
                </div>
            </div>
        </div>

        <div class="chart-card full-width">
            <div class="chart-header">
                <h3>Sebaran Mahasiswa per Universitas</h3>
                <p>Jumlah mahasiswa PKL berdasarkan institusi pendidikan.</p>
            </div>

            <div class="chart-body large">
                <canvas
                    id="universitasChart"
                    data-labels='@json($univLabels)'
                    data-values='@json($univData)'>
                </canvas>
            </div>
        </div>

    </div>
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ================= SIDEBAR ================= */
    const menuBtn = document.getElementById('menuBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    menuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

    /* ================= CHART DIVISI ================= */
    const bidangCanvas = document.getElementById('bidangChart');

    if (bidangCanvas) {
        const labels = JSON.parse(bidangCanvas.dataset.labels || '[]');
        const values = JSON.parse(bidangCanvas.dataset.values || '[]');

        new Chart(bidangCanvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: '#2B7C82',
                    borderRadius: 8,
                    maxBarThickness: 48
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, stepSize: 1 },
                        grid: { color: '#E8ECEF' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    /* ================= CHART STATUS ================= */
    const statusCanvas = document.getElementById('statusChart');

    if (statusCanvas) {
        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Aktif','Akan Datang','Selesai'],
                datasets: [{
                    data: [
                        Number(statusCanvas.dataset.aktif),
                        Number(statusCanvas.dataset.akanDatang),
                        Number(statusCanvas.dataset.selesai)
                    ],
                    backgroundColor: ['#0A5F63','#11B5B3','#062E43'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    }

    /* ================= CHART UNIVERSITAS ================= */
const univCanvas = document.getElementById('universitasChart');

if (univCanvas) {
    const labels = JSON.parse(univCanvas.dataset.labels || '[]');
    const values = JSON.parse(univCanvas.dataset.values || '[]');

    // Tinggi chart menyesuaikan jumlah universitas
const tinggiPerData = 32;
const tinggiMinimum = 320;

const chartHeight = Math.max(
    tinggiMinimum,
    labels.length * tinggiPerData
);

univCanvas.parentElement.style.height = `${chartHeight}px`;

    new Chart(univCanvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data: values,
                borderRadius: 6,
                barThickness: 20,
                backgroundColor: ctx => {
                    const chart = ctx.chart;
                    const { ctx: c, chartArea } = chart;

                    if (!chartArea) return '#0A5F63';

                    const gradient = c.createLinearGradient(
                        0, 0,
                        chartArea.right, 0
                    );

                    gradient.addColorStop(0, '#18A7A5');
                    gradient.addColorStop(1, '#0A5F63');

                    return gradient;
                }
            }]
        },

        options: {
            indexAxis: 'y',

            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                x: {
                    beginAtZero: true,

                    ticks: {
                        precision: 0,
                        stepSize: 1
                    },

                    grid: {
                        display: false
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
                        },

                        autoSkip: false
                    }
                }
            }
        }
    });
}

});
</script>

</body>
</html>