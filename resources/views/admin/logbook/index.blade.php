<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logbook PKL - Admin Monitoring PKL</title>
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
                    <i class="fa-regular fa-book-open"></i>
                </div>
                <div>
                    <h1>Pemeriksaan Logbook PKL</h1>
                    <p>Tinjau laporan aktivitas harian mahasiswa magang, berikan feedback, dan setujui logbook.</p>
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
                    <div class="lbl">Menunggu Peninjauan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-check-double"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $approvedCount }}</div>
                    <div class="lbl">Logbook Disetujui</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="fa-solid fa-rotate-left"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $revisionCount }}</div>
                    <div class="lbl">Perlu Revisi</div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="filter-card">
            <form action="{{ route('admin.logbook') }}" method="GET" class="filter-grid">
                <div class="form-group">
                    <label for="f_status">Status Peninjauan</label>
                    <select id="f_status" name="status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="revision_required" {{ request('status') === 'revision_required' ? 'selected' : '' }}>Perlu Revisi</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="f_date">Tanggal Kegiatan</label>
                    <input type="date" id="f_date" name="date" value="{{ request('date') }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="f_search">Pencarian</label>
                    <input type="text" id="f_search" name="search" value="{{ request('search') }}" placeholder="Judul / Mahasiswa / Aktivitas..." class="form-control">
                </div>

                <div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fa-solid fa-filter"></i> Filter Data
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Logbook -->
        <div class="table-card">
            <div class="table-header">
                <h3><i class="fa-solid fa-table-list"></i> Daftar Logbook Mahasiswa</h3>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Judul Kegiatan</th>
                            <th>Aktivitas & Hasil</th>
                            <th>Lampiran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logbooks as $lb)
                            <tr>
                                <td>
                                    <strong>{{ $lb->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                                    <small style="color: #64748b;">NIM: {{ $lb->mahasiswa->nim ?? '-' }}</small>
                                </td>
                                <td>{{ $lb->logbook_date->format('d/m/Y') }}</td>
                                <td>{{ substr($lb->start_time, 0, 5) }} - {{ substr($lb->end_time, 0, 5) }}</td>
                                <td style="font-weight: 600; color: #1e293b;">{{ $lb->title }}</td>
                                <td style="max-width: 260px; font-size: 12px;">
                                    <strong>Aktivitas:</strong> {{ Str::limit($lb->activity, 80) }}<br>
                                    @if($lb->result)<strong>Hasil:</strong> {{ Str::limit($lb->result, 60) }}<br>@endif
                                    @if($lb->obstacle)<strong>Kendala:</strong> <span style="color: #b45309;">{{ Str::limit($lb->obstacle, 60) }}</span>@endif
                                </td>
                                <td>
                                    @if($lb->attachment_path)
                                        <a href="{{ asset($lb->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline">
                                            <i class="fa-solid fa-paperclip"></i> File
                                        </a>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $lb->status }}">
                                        {{ match($lb->status) {
                                            'submitted' => 'Menunggu Review',
                                            'approved' => 'Disetujui',
                                            'revision_required' => 'Perlu Revisi',
                                            'rejected' => 'Ditolak',
                                            default => $lb->status,
                                        } }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" onclick='openLogbookReviewModal({{ $lb->id_logbook }}, @json($lb))'>
                                        <i class="fa-solid fa-check-to-slot"></i> Review
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 24px; color: #94a3b8;">
                                    Tidak ada data logbook yang cocok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $logbooks->links() }}
            </div>
        </div>
    </main>

    <!-- Modal Review Logbook -->
    <div class="modal-overlay" id="reviewLogbookModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Review & Beri Feedback Logbook</h3>
                <button type="button" class="modal-close" onclick="closeLogbookReviewModal()">&times;</button>
            </div>
            <form action="" method="POST" id="logbookReviewForm">
                @csrf
                <div class="modal-body">
                    <div id="logbookDetailBox" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 16px; font-size: 13px;">
                    </div>

                    <div class="form-group">
                        <label for="lb_review_status">Keputusan Peninjauan *</label>
                        <select id="lb_review_status" name="status" class="form-select" required>
                            <option value="approved">Setujui Logbook (Approved)</option>
                            <option value="revision_required">Minta Revisi / Perbaikan (Revision Required)</option>
                            <option value="rejected">Tolak Logbook</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="lb_review_note">Catatan Peninjauan Resmi</label>
                        <input type="text" id="lb_review_note" name="review_note" placeholder="Contoh: Sangat baik, dokumentasi lengkap / Harap lengkapi kendala teknis" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="lb_comment">Komentar Pembimbing Tambahan (Opsional)</label>
                        <textarea id="lb_comment" name="comment" rows="2" class="form-control" placeholder="Tuliskan feedback langsung untuk mahasiswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeLogbookReviewModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Review</button>
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

        function openLogbookReviewModal(id, item) {
            document.getElementById('logbookReviewForm').action = '/admin/logbook/' + id + '/review';
            document.getElementById('logbookDetailBox').innerHTML =
                '<strong>Mahasiswa:</strong> ' + (item.mahasiswa ? item.mahasiswa.nama_lengkap : '-') + '<br>' +
                '<strong>Tanggal Kegiatan:</strong> ' + item.logbook_date + '<br>' +
                '<strong>Judul:</strong> ' + item.title + '<br>' +
                '<strong>Aktivitas:</strong> ' + item.activity + '<br>' +
                (item.result ? '<strong>Hasil:</strong> ' + item.result + '<br>' : '') +
                (item.obstacle ? '<strong>Kendala:</strong> ' + item.obstacle + '<br>' : '') +
                (item.solution ? '<strong>Solusi:</strong> ' + item.solution + '<br>' : '');

            document.getElementById('lb_review_status').value = item.status === 'approved' ? 'approved' : (item.status === 'revision_required' ? 'revision_required' : 'approved');
            document.getElementById('lb_review_note').value = item.review_note || '';
            document.getElementById('reviewLogbookModal').classList.add('active');
        }

        function closeLogbookReviewModal() {
            document.getElementById('reviewLogbookModal').classList.remove('active');
        }
    </script>
</body>
</html>
