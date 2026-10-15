<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi & Koreksi - Admin Monitoring PKL</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/features.css') }}">
</head>
<body>

    <!-- Header -->
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
            <button type="button" class="menu-btn" id="menuBtn"><i class="fa-solid fa-bars"></i></button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
            </form>
        </div>
    </header>

    <!-- Sidebar -->
    @include('admin.sidebar')

    <!-- Content -->
    <main class="content">

        <!-- Page Header Card -->
        <div class="page-header-card">
            <div class="page-header-content">
                <div class="page-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h1>Monitoring Presensi & Koreksi</h1>
                    <p>Pantau kehadiran geofencing harian dan validasi pengajuan koreksi absensi mahasiswa.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-user-check"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $totalHadirHariIni }}</div>
                    <div class="lbl">Hadir Tepat Hari Ini</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon yellow"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $totalTerlambatHariIni }}</div>
                    <div class="lbl">Terlambat Hari Ini</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-envelope-open-text"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $totalIzinHariIni }}</div>
                    <div class="lbl">Izin / Sakit / Cuti</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teal"><i class="fa-solid fa-users"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $totalMhs }}</div>
                    <div class="lbl">Total Mahasiswa PKL</div>
                </div>
            </div>
        </div>

        <!-- Pengajuan Koreksi Menunggu Verifikasi -->
        @if($pendingCorrections->count() > 0)
            <div class="table-card" style="border-left: 5px solid #f59e0b;">
                <div class="table-header">
                    <h3><i class="fa-solid fa-triangle-exclamation" style="color: #f59e0b;"></i> Pengajuan Koreksi Menunggu Verifikasi ({{ $pendingCorrections->count() }})</h3>
                </div>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Mahasiswa</th>
                                <th>Tgl Absensi</th>
                                <th>Jenis Koreksi</th>
                                <th>Waktu Diajukan</th>
                                <th>Alasan</th>
                                <th>Bukti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingCorrections as $corr)
                                <tr>
                                    <td>
                                        <strong>{{ $corr->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                                        <small style="color: #64748b;">NIM: {{ $corr->mahasiswa->nim ?? '-' }}</small>
                                    </td>
                                    <td>{{ $corr->correction_date->format('d/m/Y') }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $corr->correction_type)) }}</td>
                                    <td>
                                        @if($corr->requested_check_in_at) Masuk: {{ $corr->requested_check_in_at->format('H:i') }}<br>@endif
                                        @if($corr->requested_check_out_at) Pulang: {{ $corr->requested_check_out_at->format('H:i') }}@endif
                                    </td>
                                    <td style="max-width: 200px;">{{ $corr->reason }}</td>
                                    <td>
                                        @if($corr->attachment_path)
                                            <a href="{{ asset($corr->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline">
                                                <i class="fa-solid fa-paperclip"></i> Lihat
                                            </a>
                                        @else
                                            <span style="color: #94a3b8;">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" onclick='openReviewModal({{ $corr->id_correction }}, @json($corr))'>
                                            <i class="fa-solid fa-pen-to-square"></i> Review
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Filter Presensi Harian -->
        <div class="filter-card">
            <form action="{{ route('admin.absensi') }}" method="GET" class="filter-grid">
                <div class="form-group">
                    <label for="f_date">Pilih Tanggal</label>
                    <input type="date" id="f_date" name="date" value="{{ $date }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="f_status">Status Kehadiran</label>
                    <select id="f_status" name="status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Hadir Tepat</option>
                        <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="leave" {{ request('status') === 'leave' ? 'selected' : '' }}>Cuti / Izin</option>
                        <option value="sick" {{ request('status') === 'sick' ? 'selected' : '' }}>Sakit</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="f_search">Cari Nama / NIM</label>
                    <input type="text" id="f_search" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIM..." class="form-control">
                </div>

                <div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fa-solid fa-filter"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Catatan Presensi -->
        <div class="table-card">
            <div class="table-header">
                <h3><i class="fa-solid fa-list-check"></i> Daftar Kehadiran (Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }})</h3>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Bukti Selfie</th>
                            <th>Status</th>
                            <th>Keterlambatan</th>
                            <th>Jam Kerja</th>
                            <th>Jarak Geofence</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $row)
                            <tr>
                                <td>
                                    <strong>{{ $row->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                                    <small style="color: #64748b;">NIM: {{ $row->mahasiswa->nim ?? '-' }} &bull; {{ $row->mahasiswa->universitas->nama_universitas ?? '' }}</small>
                                </td>
                                <td>{{ $row->check_in_at ? $row->check_in_at->format('H:i') . ' WIB' : '-' }}</td>
                                <td>{{ $row->check_out_at ? $row->check_out_at->format('H:i') . ' WIB' : '-' }}</td>
                                <td>
                                    @if($row->selfie_path || $row->selfie_out_path)
                                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                            @if($row->selfie_path)
                                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px;" onclick="viewSelfieAdmin('{{ asset($row->selfie_path) }}', 'Bukti Selfie Masuk - {{ $row->mahasiswa->nama_lengkap ?? '' }} ({{ $row->check_in_at ? $row->check_in_at->format('H:i') : '' }} WIB)')">
                                                    <i class="fa-solid fa-camera"></i> Masuk
                                                </button>
                                            @endif
                                            @if($row->selfie_out_path)
                                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px;" onclick="viewSelfieAdmin('{{ asset($row->selfie_out_path) }}', 'Bukti Selfie Pulang - {{ $row->mahasiswa->nama_lengkap ?? '' }} ({{ $row->check_out_at ? $row->check_out_at->format('H:i') : '' }} WIB)')">
                                                    <i class="fa-solid fa-camera"></i> Pulang
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <span style="color: #94a3b8; font-size: 11px;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $row->check_in_status }}">
                                        {{ match($row->check_in_status) {
                                            'present' => 'Hadir Tepat',
                                            'late' => 'Terlambat',
                                            'leave' => 'Cuti / Izin',
                                            'sick' => 'Sakit',
                                            default => 'Alpa',
                                        } }}
                                    </span>
                                </td>
                                <td>{{ $row->late_minutes > 0 ? "{$row->late_minutes} menit" : '-' }}</td>
                                <td>{{ $row->total_work_minutes > 0 ? floor($row->total_work_minutes / 60) . 'j ' . ($row->total_work_minutes % 60) . 'm' : '-' }}</td>
                                <td>{{ $row->distance_in_meters ? "{$row->distance_in_meters} m" : '-' }}</td>
                                <td style="max-width: 180px; font-size: 12px; color: #64748b;">{{ $row->notes ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 24px; color: #94a3b8;">
                                    Belum ada data presensi pada tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $attendances->links() }}
            </div>
        </div>

    </main>

    <!-- Modal Review Koreksi Absensi -->
    <div class="modal-overlay" id="reviewKoreksiModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Review Pengajuan Koreksi Absensi</h3>
                <button type="button" class="modal-close" onclick="closeReviewModal()">&times;</button>
            </div>
            <form action="" method="POST" id="reviewForm">
                @csrf
                <div class="modal-body">
                    <p style="font-size: 13px; color: #475569; margin-bottom: 14px;" id="reviewStudentInfo"></p>

                    <div class="form-group">
                        <label for="rev_status">Keputusan Persetujuan *</label>
                        <select id="rev_status" name="status" class="form-select" required>
                            <option value="approved">Setujui Koreksi (Perbarui Presensi)</option>
                            <option value="rejected">Tolak Pengajuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="rev_note">Catatan / Alasan Verifikasi</label>
                        <input type="text" id="rev_note" name="review_note" placeholder="Contoh: Bukti surat dinas valid / Lokasi disetujui" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeReviewModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Viewer Bukti Foto Selfie Admin -->
    <div class="modal-overlay" id="modalSelfieAdmin">
        <div class="modal-card" style="max-width: 500px; text-align: center;">
            <div class="modal-header">
                <h3 id="adminSelfieTitle"><i class="fa-solid fa-camera"></i> Bukti Selfie Presensi</h3>
                <button type="button" class="modal-close" onclick="closeSelfieAdmin()">&times;</button>
            </div>
            <div class="modal-body" style="padding: 16px;">
                <img id="adminSelfieImg" src="" style="width: 100%; max-height: 480px; border-radius: 10px; object-fit: contain; border: 1px solid #cbd5e1; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn btn-outline" onclick="closeSelfieAdmin()">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.getElementById('menuBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (menuBtn && sidebar && sidebarOverlay) {
                menuBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                });
                sidebarOverlay.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            }
        });

        function openReviewModal(id, data) {
            document.getElementById('reviewForm').action = '/admin/absensi/koreksi/' + id + '/review';
            document.getElementById('reviewStudentInfo').innerHTML =
                'Mahasiswa: <strong>' + (data.mahasiswa ? data.mahasiswa.nama_lengkap : '-') + '</strong><br>' +
                'Tanggal Absensi: <strong>' + data.correction_date + '</strong><br>' +
                'Alasan: <em>' + data.reason + '</em>';
            document.getElementById('reviewKoreksiModal').classList.add('active');
        }
        function closeReviewModal() {
            document.getElementById('reviewKoreksiModal').classList.remove('active');
        }

        function viewSelfieAdmin(url, title) {
            document.getElementById('adminSelfieImg').src = url;
            document.getElementById('adminSelfieTitle').innerHTML = '<i class="fa-solid fa-camera"></i> ' + title;
            document.getElementById('modalSelfieAdmin').classList.add('active');
        }
        function closeSelfieAdmin() {
            document.getElementById('modalSelfieAdmin').classList.remove('active');
        }
    </script>
</body>
</html>
