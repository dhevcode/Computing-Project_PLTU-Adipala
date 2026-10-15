<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuti & Izin - Admin Monitoring PKL</title>
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

    @include('admin.sidebar')

    <main class="content">
        <div class="page-header-card">
            <div class="page-header-content">
                <div class="page-icon">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
                <div>
                    <h1>Pengajuan Cuti & Izin Mahasiswa</h1>
                    <p>Verifikasi permohonan izin sakit, akademik, atau cuti pribadi. Izin yang disetujui otomatis memperbarui presensi.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon yellow"><i class="fa-solid fa-hourglass-half"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $pendingCount }}</div>
                    <div class="lbl">Menunggu Persetujuan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $approvedCount }}</div>
                    <div class="lbl">Disetujui</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="fa-solid fa-circle-xmark"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $rejectedCount }}</div>
                    <div class="lbl">Ditolak</div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="filter-card">
            <form action="{{ route('admin.cuti') }}" method="GET" class="filter-grid">
                <div class="form-group">
                    <label for="f_status">Status Pengajuan</label>
                    <select id="f_status" name="status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="f_type">Jenis Cuti / Izin</label>
                    <select id="f_type" name="leave_type" class="form-select">
                        <option value="all">Semua Jenis</option>
                        <option value="personal_leave" {{ request('leave_type') === 'personal_leave' ? 'selected' : '' }}>Cuti Pribadi</option>
                        <option value="sick_leave" {{ request('leave_type') === 'sick_leave' ? 'selected' : '' }}>Izin Sakit</option>
                        <option value="academic_leave" {{ request('leave_type') === 'academic_leave' ? 'selected' : '' }}>Izin Akademik</option>
                        <option value="official_permission" {{ request('leave_type') === 'official_permission' ? 'selected' : '' }}>Izin Dinas Resmi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="f_search">Cari Nama / NIM</label>
                    <input type="text" id="f_search" name="search" value="{{ request('search') }}" placeholder="Ketik nama mahasiswa..." class="form-control">
                </div>

                <div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fa-solid fa-filter"></i> Filter Pengajuan
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Cuti -->
        <div class="table-card">
            <div class="table-header">
                <h3><i class="fa-solid fa-calendar-days"></i> Daftar Pengajuan Cuti & Izin</h3>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Jenis Izin</th>
                            <th>Periode Tanggal</th>
                            <th>Durasi</th>
                            <th>Alasan</th>
                            <th>Kontak Darurat</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $item)
                            @php
                                $durasi = \Carbon\Carbon::parse($item->start_date)->diffInDays(\Carbon\Carbon::parse($item->end_date)) + 1;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $item->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                                    <small style="color: #64748b;">NIM: {{ $item->mahasiswa->nim ?? '-' }}</small>
                                </td>
                                <td><strong>{{ \App\Models\LeaveRequest::getLeaveTypeLabel($item->leave_type) }}</strong></td>
                                <td>{{ $item->start_date->format('d/m/Y') }} - {{ $item->end_date->format('d/m/Y') }}</td>
                                <td>{{ $durasi }} Hari</td>
                                <td style="max-width: 200px; font-size: 12px;">{{ $item->reason }}</td>
                                <td style="font-size: 12px; color: #64748b;">{{ $item->emergency_contact ?: '-' }}</td>
                                <td>
                                    @if($item->attachment_path)
                                        <a href="{{ asset($item->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline">
                                            <i class="fa-solid fa-paperclip"></i> File
                                        </a>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $item->status }}">
                                        {{ match($item->status) {
                                            'submitted' => 'Menunggu Review',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'cancelled' => 'Dibatalkan',
                                            default => $item->status,
                                        } }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" onclick='openCutiReviewModal({{ $item->id_leave }}, @json($item))'>
                                        <i class="fa-solid fa-pen-to-square"></i> Review
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 24px; color: #94a3b8;">
                                    Belum ada data pengajuan cuti/izin.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $leaves->links() }}
            </div>
        </div>
    </main>

    <!-- Modal Review Cuti -->
    <div class="modal-overlay" id="reviewCutiModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Persetujuan Pengajuan Cuti / Izin</h3>
                <button type="button" class="modal-close" onclick="closeCutiReviewModal()">&times;</button>
            </div>
            <form action="" method="POST" id="cutiReviewForm">
                @csrf
                <div class="modal-body">
                    <div id="cutiDetailBox" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 16px; font-size: 13px;">
                    </div>

                    <div class="form-group">
                        <label for="cuti_status">Keputusan Persetujuan *</label>
                        <select id="cuti_status" name="status" class="form-select" required>
                            <option value="approved">Setujui Cuti / Izin (Update Presensi Mahasiswa)</option>
                            <option value="rejected">Tolak Pengajuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cuti_note">Catatan Alasan Keputusan</label>
                        <input type="text" id="cuti_note" name="review_note" placeholder="Contoh: Bukti surat dokter terverifikasi / Keperluan mendesak disetujui" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeCutiReviewModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                </div>
            </form>
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

        function openCutiReviewModal(id, item) {
            document.getElementById('cutiReviewForm').action = '/admin/cuti/' + id + '/review';
            document.getElementById('cutiDetailBox').innerHTML =
                '<strong>Mahasiswa:</strong> ' + (item.mahasiswa ? item.mahasiswa.nama_lengkap : '-') + '<br>' +
                '<strong>Jenis:</strong> ' + item.leave_type + '<br>' +
                '<strong>Periode:</strong> ' + item.start_date + ' s/d ' + item.end_date + '<br>' +
                '<strong>Alasan:</strong> ' + item.reason + '<br>' +
                (item.emergency_contact ? '<strong>Kontak Darurat:</strong> ' + item.emergency_contact : '');

            document.getElementById('cuti_status').value = item.status === 'approved' ? 'approved' : (item.status === 'rejected' ? 'rejected' : 'approved');
            document.getElementById('cuti_note').value = item.review_note || '';
            document.getElementById('reviewCutiModal').classList.add('active');
        }

        function closeCutiReviewModal() {
            document.getElementById('reviewCutiModal').classList.remove('active');
        }
    </script>
</body>
</html>
