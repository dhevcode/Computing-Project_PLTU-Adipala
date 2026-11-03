@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')
@section('page_subtitle', 'Ringkasan statistik dan sebaran mahasiswa PKL')

@push('styles')
<style>
    /* Filter Section */
    .filter-section { background: var(--bg); border-radius: 16px; padding: 20px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border); }
    .filter-header { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--page); }
    .filter-header i { color: var(--primary); font-size: 18px; }
    .filter-header h3 { font-size: 15px; font-weight: 700; color: var(--text); }
    .filter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .input-group label { display: block; font-size: 12.5px; font-weight: 600; color: var(--muted); margin-bottom: 6px; }
    .filter-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }

    .btn-reset { background: #F1F5F9; color: #475569; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s; text-decoration: none; }
    .btn-reset:hover { background: #E2E8F0; color: var(--text); }
    .btn-filter { background: var(--primary); color: #fff; border: none; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: 0.2s; font-family: inherit; }
    .btn-filter:hover { background: var(--primary-dark); }

    /* Charts */
    .chart-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 24px; }
    .chart-body { position: relative; height: 300px; width: 100%; margin-top: 16px; }
    .full-width { grid-column: 1 / -1; }

    @media (max-width: 1100px) {
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
        .chart-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 760px) {
        .filter-grid { grid-template-columns: 1fr; }
        .filter-actions { flex-direction: column-reverse; }
        .btn-reset, .btn-filter { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success animate-fade-up">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(isset($menungguVerifikasi) && $menungguVerifikasi > 0)
        <div class="card animate-fade-up" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); border: 1.5px solid #F59E0B; margin-bottom: 24px;">
            <div class="card-header" style="border: none; padding: 20px 24px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; width: 100%;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 46px; height: 46px; border-radius: 12px; background: #F59E0B; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fa-solid fa-user-clock"></i>
                        </div>
                        <div>
                            <strong style="color: #92400E; font-size: 15px; display: block; margin-bottom: 4px;">{{ $menungguVerifikasi }} Pendaftaran Menunggu Keputusan!</strong>
                            <span style="color: #B45309; font-size: 13px;">Segera verifikasi berkas dan ploting Pembimbing Lapangan agar mahasiswa dapat segera magang.</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.data', ['status' => 'Menunggu Verifikasi']) }}" class="btn-topbar-action" style="background: #0A5F63; border-color: #0A5F63;">
                        <i class="fa-solid fa-list-check"></i> Tinjau Pendaftaran
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- STATISTIK GRID -->
    <div class="stats-grid animate-fade-up delay-1">
        <div class="stat-card">
            <div class="stat-info">
                <h4>Magang Hari Ini</h4>
                <div class="stat-value">{{ str_pad($magangHariIni,2,'0',STR_PAD_LEFT) }}</div>
            </div>
            <div class="stat-icon" style="background: #ECFDF5; color: #059669;">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h4>Total Mahasiswa Aktif</h4>
                <div class="stat-value">{{ str_pad($totalMahasiswa,2,'0',STR_PAD_LEFT) }}</div>
            </div>
            <div class="stat-icon primary">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h4>Akan Datang</h4>
                <div class="stat-value">{{ str_pad($akanDatang,2,'0',STR_PAD_LEFT) }}</div>
            </div>
            <div class="stat-icon warning">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h4>Divisi Terlibat</h4>
                <div class="stat-value">{{ str_pad($divisiTerlibat,2,'0',STR_PAD_LEFT) }}</div>
            </div>
            <div class="stat-icon" style="background: #F5F3FF; color: #7C3AED;">
                <i class="fa-solid fa-building-user"></i>
            </div>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-section animate-fade-up delay-2">
        <div class="filter-header">
            <i class="fa-solid fa-filter"></i>
            <h3>Filter Data Mahasiswa</h3>
        </div>

        <div class="filter-grid">
            <div class="input-group">
                <label>Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" class="input-box" placeholder="Nama / NIM...">
            </div>
            <div class="input-group">
                <label>Jurusan</label>
                <select name="jurusan_id" class="d-none custom-filter-select">
                    <option value="" {{ !request('jurusan_id') ? 'selected' : '' }}>Semua Jurusan</option>
                    @foreach($listJurusan as $j)
                        <option value="{{ $j->id_jurusan }}" {{ request('jurusan_id') == $j->id_jurusan ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group">
                <label>Universitas</label>
                <select name="universitas_id" class="d-none custom-filter-select">
                    <option value="" {{ !request('universitas_id') ? 'selected' : '' }}>Semua Universitas</option>
                    @foreach($listUniversitas as $u)
                        <option value="{{ $u->id_universitas }}" {{ request('universitas_id') == $u->id_universitas ? 'selected' : '' }}>{{ $u->nama_universitas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group">
                <label>Divisi</label>
                <select name="divisi_id" class="d-none custom-filter-select">
                    <option value="" {{ !request('divisi_id') ? 'selected' : '' }}>Semua Divisi</option>
                    @foreach($listDivisi as $d)
                        <option value="{{ $d->id_divisi }}" {{ request('divisi_id') == $d->id_divisi ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="d-none custom-filter-select">
                    <option value="" {{ !request('jenis_kelamin') ? 'selected' : '' }}>Semua</option>
                    <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="input-group">
                <label>Tanggal Masuk</label>
                <input type="text" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="flatpickr-date" placeholder="Pilih Tanggal...">
            </div>
            <div class="input-group">
                <label>Tanggal Selesai</label>
                <input type="text" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="flatpickr-date" placeholder="Pilih Tanggal...">
            </div>
            <div class="input-group">
                <label>Status Magang</label>
                <select name="status" class="d-none custom-filter-select">
                    <option value="" {{ !request('status') ? 'selected' : '' }}>Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="Akan Magang" {{ request('status') == 'Akan Magang' ? 'selected' : '' }}>Akan Magang</option>
                    <option value="Sedang Magang" {{ request('status') == 'Sedang Magang' ? 'selected' : '' }}>Sedang Magang</option>
                    <option value="Selesai Magang" {{ request('status') == 'Selesai Magang' ? 'selected' : '' }}>Selesai Magang</option>
                </select>
            </div>
        </div>

        <div class="filter-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn-reset"><i class="fa-solid fa-rotate-left"></i> Reset Filter</a>
            <button type="submit" class="btn-filter"><i class="fa-solid fa-filter"></i> Terapkan Filter</button>
        </div>
    </form>

    <!-- CHARTS SECTION -->
    <div class="chart-grid animate-fade-up delay-3">
        <div class="card">
            <div class="card-header"><div class="card-title-group"><h3>Jumlah Mahasiswa per Divisi</h3></div></div>
            <div class="chart-body" style="padding: 0 24px 24px;">
                <canvas id="bidangChart" data-labels='@json($bidangLabels)' data-values='@json($bidangData)'></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title-group"><h3>Status Magang</h3></div></div>
            <div class="chart-body" style="padding: 0 24px 24px;">
                <canvas id="statusChart" data-aktif="{{ $statusAktif }}" data-akan-datang="{{ $statusAkanDatang }}" data-selesai="{{ $statusSelesai }}"></canvas>
            </div>
        </div>
        <div class="card full-width">
            <div class="card-header"><div class="card-title-group"><h3>Sebaran Mahasiswa per Universitas</h3></div></div>
            <div class="chart-body" style="padding: 0 24px 24px; min-height: 350px;">
                <canvas id="universitasChart" data-labels='@json($univLabels)' data-values='@json($univData)'></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#64748B';

    // Chart Divisi
    const bidangCanvas = document.getElementById('bidangChart');
    if (bidangCanvas) {
        new Chart(bidangCanvas, {
            type: 'bar',
            data: {
                labels: JSON.parse(bidangCanvas.dataset.labels || '[]'),
                datasets: [{ data: JSON.parse(bidangCanvas.dataset.values || '[]'), backgroundColor: '#00a2b9', borderRadius: 6, maxBarThickness: 32 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#F1F5F9' } }, x: { grid: { display: false } } } }
        });
    }

    // Chart Status
    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Sedang Magang', 'Akan Magang', 'Selesai Magang'],
                datasets: [{
                    data: [Number(statusCanvas.dataset.aktif), Number(statusCanvas.dataset.akanDatang), Number(statusCanvas.dataset.selesai)],
                    backgroundColor: ['#00a2b9', '#10B981', '#CBD5E1'], borderWidth: 0, hoverOffset: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '72%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } } }
        });
    }

    // Chart Universitas
    const univCanvas = document.getElementById('universitasChart');
    if (univCanvas) {
        const labels = JSON.parse(univCanvas.dataset.labels || '[]');
        const chartHeight = Math.max(350, labels.length * 40);
        univCanvas.parentElement.style.height = `${chartHeight}px`;

        new Chart(univCanvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: JSON.parse(univCanvas.dataset.values || '[]'), borderRadius: 6, barThickness: 16,
                    backgroundColor: ctx => {
                        const { ctx: c, chartArea } = ctx.chart;
                        if (!chartArea) return '#00a2b9';
                        const gradient = c.createLinearGradient(0, 0, chartArea.right, 0);
                        gradient.addColorStop(0, '#10B981'); gradient.addColorStop(1, '#00a2b9');
                        return gradient;
                    }
                }]
            },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { display: false } }, y: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' } } } } }
        });
    }
});
</script>
@endpush
