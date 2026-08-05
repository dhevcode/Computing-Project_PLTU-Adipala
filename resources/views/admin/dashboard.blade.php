<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Magang</title>

    <!-- Font & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #F4F7F6;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar & Layout Container */
        .wrapper {
            display: flex;
            width: 100%;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* Header / Page Title */
        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #111;
        }

        /* Cards Statistik */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            position: relative;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        }

        .card .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #E8F4F4;
            color: #2B7C82;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .card.green {
            background: linear-gradient(135deg, #0A5F63 0%, #11B5B3 100%);
            color: #FFFFFF;
        }

        .card.green .icon-circle {
            background: rgba(255, 255, 255, 0.2);
            color: #FFFFFF;
        }

        .card .card-title {
            font-size: 13px;
            font-weight: 500;
            color: #666;
            margin-bottom: 6px;
        }

        .card.green .card-title {
            color: rgba(255, 255, 255, 0.85);
        }

        .card .card-value {
            font-size: 28px;
            font-weight: 700;
            color: #111;
            margin-bottom: 4px;
        }

        .card.green .card-value {
            color: #FFFFFF;
        }

        .card .card-desc {
            font-size: 12px;
            color: #888;
        }

        .card.green .card-desc {
            color: rgba(255, 255, 255, 0.75);
        }

        /* Filter Grid */
        .filter-section {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            margin-bottom: 30px;
        }

        .filter-section h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2B7C82;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .input-group label {
            font-size: 12px;
            font-weight: 500;
            color: #555;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            font-size: 13px;
            outline: none;
            background-color: #FAFAFA;
            transition: border-color 0.2s;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #2B7C82;
            background-color: #FFFFFF;
        }

        /* Chart Grid Layout */
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
        }

        .chart-card.full-width {
            grid-column: 1 / -1;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: #111;
        }

        .chart-body {
            position: relative;
            flex: 1;
            min-height: 260px;
        }

        /* Custom Legend Doughnut */
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
            font-size: 12px;
            color: #555;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        @media (max-width: 992px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <div class="main-content">
            
            <div class="page-header">
                <h1>Dashboard Ringkasan</h1>
            </div>

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

            <!-- FILTER DATA -->
<form method="GET" action="{{ route('admin.dashboard') }}" class="filter-section">
    <h3><i class="fa-solid fa-filter"></i> Filter Data Dashboard</h3>
    <div class="filter-grid">
        <div class="input-group">
            <label>Pencarian</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / NIM..." onchange="this.form.submit()">
        </div>

        <div class="input-group">
            <label>Jurusan</label>
            <select name="jurusan_id" onchange="this.form.submit()">
                <option value="">Semua Jurusan</option>
                @foreach($listJurusan as $j)
                    <option value="{{ $j->id_jurusan }}" {{ request('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>
                        {{ $j->nama_jurusan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="input-group">
            <label>Universitas</label>
            <select name="universitas_id" onchange="this.form.submit()">
                <option value="">Semua Universitas</option>
                @foreach($listUniversitas as $u)
                    <option value="{{ $u->id_universitas }}" {{ request('universitas_id') == $u->id_universitas ? 'selected' : '' }}>
                        {{ $u->nama_universitas }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="input-group">
            <label>Divisi</label>
            <select name="divisi_id" onchange="this.form.submit()">
                <option value="">Semua Divisi</option>
                @foreach($listDivisi as $d)
                    <option value="{{ $d->id_divisi }}" {{ request('divisi_id') == $d->id_divisi ? 'selected' : '' }}>
                        {{ $d->nama_divisi }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</form>

            <!-- CHARTS SECTION -->
            <div class="charts-grid">
                
                <!-- Grafik Batang Divisi -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Jumlah Mahasiswa per Divisi</h3>
                    </div>
                    <div class="chart-body">
                        <canvas
                            id="bidangChart"
                            data-labels='{{ e(json_encode($bidangLabels ?? [])) }}'
                            data-values='{{ e(json_encode($bidangData ?? [])) }}'
                        ></canvas>
                    </div>
                </div>

                <!-- Grafik Donut Status Magang -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Status Magang</h3>
                    </div>
                    <div class="chart-body">
                        <canvas
                            id="statusChart"
                            data-aktif='{{ e(json_encode($statusAktif ?? 0)) }}'
                            data-akan-datang='{{ e(json_encode($statusAkanDatang ?? 0)) }}'
                            data-selesai='{{ e(json_encode($statusSelesai ?? 0)) }}'
                        ></canvas>
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
                    </div>
                    <div class="chart-body" style="min-height: 300px;">
                        <canvas
                            id="universitasChart"
                            data-labels='{{ e(json_encode($univLabels ?? [])) }}'
                            data-values='{{ e(json_encode($univData ?? [])) }}'
                        ></canvas>
                    </div>
                </div>

            </div>

        </div>
    </div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        
        // 1. CHART BIDANG / DIVISI
        const ctxBidang = document.getElementById('bidangChart');
        if (ctxBidang) {
            const bidangLabels = JSON.parse(ctxBidang.dataset.labels || '[]');
            const bidangData = JSON.parse(ctxBidang.dataset.values || '[]');

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
                    plugins: { legend: { display: false } },
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, stepSize: 1 },
                            grid: { color: '#EAEAEA' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // 2. CHART STATUS MAGANG
        // Menggunakan json agar aman dari nilai null/undefined
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
                    plugins: { legend: { display: false } }
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
                            const { ctx, chartArea } = chart;
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
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { display: false },
                            ticks: { precision: 0, stepSize: 1, color: '#666' }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { color: '#555', font: { size: 12, weight: '500' } }
                        }
                    }
                }
            });
        }
    });
</script> 
</body>
</html>
