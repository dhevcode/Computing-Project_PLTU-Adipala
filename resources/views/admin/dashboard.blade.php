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
            text-decoration: none;
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
            <a href="{{ route('admin.export', request()->query()) }}" class="btn-add" style="background: #FFC107; color: #333;">
                <i class="fa-solid fa-file-export"></i> Ekspor Data
            </a>
            <a href="{{ route('admin.create') }}" class="btn-add">
                <i class="fa-solid fa-plus"></i> Tambah Mahasiswa
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline-block;">
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

        <!-- TABLE DATA MONITORING -->
        <div class="table-card">
            <div class="table-card-title">
                Data Monitoring Mahasiswa
            </div>
            <div class="table-card-subtitle">
                Menampilkan data monitoring mahasiswa PKL
            </div>

            <div style="overflow-x: auto; width: 100%;">
                <table class="table-admin">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Lengkap</th>
                            <th>Universitas</th>
                            <th>Jurusan</th>
                            <th>Divisi</th>
                            <th>Periode Magang</th>
                            <th>Status</th>
                            <th>Surat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $index => $row)
                            <tr>
                                <td>{{ $pengajuans->firstItem() + $index }}</td>
                                <td>{{ $row->mahasiswa->nim ?? '-' }}</td>
                                <td>{{ $row->mahasiswa->nama_lengkap ?? '-' }}</td>
                                <td>{{ $row->mahasiswa->universitas->nama_universitas ?? '-' }}</td>
                                <td>{{ $row->mahasiswa->jurusan->nama_jurusan ?? '-' }}</td>
                                <td>{{ $row->divisi->nama_divisi ?? '-' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y') }} s.d. {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y') }}
                                </td>
                                <td>
                                    @if($row->status === 'Akan Magang')
                                        <div class="status" style="background: #FFEED9; color: #D9822B;">Akan Magang</div>
                                    @elseif($row->status === 'Sedang Magang')
                                        <div class="status" style="background: #E8F4F4; color: #2B7C82;">Sedang Magang</div>
                                    @else
                                        <div class="status" style="background: #EAEAEA; color: #666;">Selesai Magang</div>
                                    @endif
                                </td>
                                <td>
                                    @if($row->surat_permohonan)
                                        <a href="{{ asset('uploads/surat_permohonan/' . $row->surat_permohonan) }}" target="_blank" title="Lihat Surat" style="color: #0A6B70; font-size: 16px;">
                                            <i class="fa-regular fa-file-pdf"></i>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="action">
                                        <a href="{{ route('admin.edit', $row->id_pengajuan) }}" class="edit" title="Ubah">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.destroy', $row->id_pengajuan) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data monitoring mahasiswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete" style="background: none; border: none; padding: 0; cursor: pointer;" title="Hapus">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align: center; color: #888; padding: 20px;">
                                    Belum ada data mahasiswa PKL yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 15px; display: flex; justify-content: center;">
                {{ $pengajuans->links() }}
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
                        data-labels='{{ e(json_encode($bidangLabels ?? [])) }}'
                        data-values='{{ e(json_encode($bidangData ?? [])) }}'
                    ></canvas>
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
                    <p>Data institusi pendidikan mahasiswa magang</p>
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
