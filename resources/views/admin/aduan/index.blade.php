<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aduan Masalah - Admin Monitoring PKL</title>
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
                    <i class="fa-regular fa-comment-dots"></i>
                </div>
                <div>
                    <h1>Penanganan Aduan Masalah Mahasiswa</h1>
                    <p>Tindak lanjuti kendala teknis, lingkungan kerja, K3, dan administrasi yang disampaikan peserta magang.</p>
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
                <div class="stat-icon yellow"><i class="fa-solid fa-inbox"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $pendingCount }}</div>
                    <div class="lbl">Aduan Baru Masuk</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-arrows-rotate"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $inProgressCount }}</div>
                    <div class="lbl">Sedang Ditangani</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
                <div class="stat-info">
                    <div class="val">{{ $resolvedCount }}</div>
                    <div class="lbl">Aduan Selesai</div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="filter-card">
            <form action="{{ route('admin.aduan') }}" method="GET" class="filter-grid">
                <div class="form-group">
                    <label for="f_status">Status Aduan</label>
                    <select id="f_status" name="status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Aduan Masuk</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Ditangani</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="f_priority">Tingkat Prioritas</label>
                    <select id="f_priority" name="priority" class="form-select">
                        <option value="all">Semua Prioritas</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="f_search">Pencarian</label>
                    <input type="text" id="f_search" name="search" value="{{ request('search') }}" placeholder="No Aduan / Judul / Nama..." class="form-control">
                </div>

                <div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fa-solid fa-filter"></i> Filter Aduan
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Aduan -->
        <div class="table-card">
            <div class="table-header">
                <h3><i class="fa-solid fa-list-check"></i> Daftar Laporan Aduan Masalah</h3>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No Aduan</th>
                            <th>Mahasiswa</th>
                            <th>Kategori</th>
                            <th>Judul & Deskripsi</th>
                            <th>Prioritas</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $c)
                            <tr>
                                <td><strong>{{ $c->complaint_number }}</strong><br><small style="color: #64748b;">{{ $c->created_at->format('d/m/Y') }}</small></td>
                                <td>
                                    <strong>{{ $c->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                                    <small style="color: #64748b;">NIM: {{ $c->mahasiswa->nim ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ match($c->category) {
                                        'technical' => 'Teknis / Sistem',
                                        'work_environment' => 'Lingkungan Kerja',
                                        'safety' => 'Keselamatan (K3)',
                                        'schedule' => 'Jadwal',
                                        'supervision' => 'Pembimbingan',
                                        'administration' => 'Administrasi',
                                        default => 'Lainnya',
                                    } }}</span>
                                </td>
                                <td style="max-width: 250px;">
                                    <strong>{{ $c->title }}</strong>
                                    <p style="font-size: 12px; color: #64748b; margin-top: 2px;">{{ Str::limit($c->description, 90) }}</p>
                                </td>
                                <td>
                                    <span class="badge {{ $c->priority === 'urgent' ? 'badge-danger' : ($c->priority === 'high' ? 'badge-warning' : 'badge-secondary') }}">
                                        {{ strtoupper($c->priority) }}
                                    </span>
                                </td>
                                <td>
                                    @if($c->attachment_path)
                                        <a href="{{ asset($c->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline">
                                            <i class="fa-solid fa-paperclip"></i> Bukti
                                        </a>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $c->status }}">
                                        {{ match($c->status) {
                                            'submitted' => 'Aduan Masuk',
                                            'in_progress' => 'Ditangani',
                                            'resolved' => 'Selesai',
                                            'closed' => 'Ditutup',
                                            default => $c->status,
                                        } }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" onclick='openReplyModal({{ $c->id_complaint }}, @json($c))'>
                                        <i class="fa-regular fa-comment"></i> Tanggapi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 24px; color: #94a3b8;">
                                    Tidak ada laporan aduan yang cocok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $complaints->links() }}
            </div>
        </div>
    </main>

    <!-- Modal Balas Tanggapan Aduan -->
    <div class="modal-overlay" id="replyAduanModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tanggapan & Penanganan Aduan</h3>
                <button type="button" class="modal-close" onclick="closeReplyModal()">&times;</button>
            </div>
            <form action="" method="POST" id="replyForm">
                @csrf
                <div class="modal-body">
                    <div id="aduanDetailBox" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 16px; font-size: 13px;">
                    </div>

                    <div class="form-group">
                        <label for="rep_status">Perbarui Status Aduan *</label>
                        <select id="rep_status" name="status" class="form-select" required>
                            <option value="in_progress">Sedang Ditangani (In Progress)</option>
                            <option value="waiting_for_participant">Menunggu Informasi Tambahan dari Peserta</option>
                            <option value="resolved">Telah Diselesaikan (Resolved)</option>
                            <option value="closed">Tutup Aduan (Closed)</option>
                            <option value="rejected">Tolak Aduan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="rep_message">Pesan Tanggapan Resmi ke Mahasiswa *</label>
                        <textarea id="rep_message" name="message" rows="3" class="form-control" placeholder="Tuliskan tindak lanjut penanganan atau solusi untuk mahasiswa..." required minlength="3"></textarea>
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" id="is_internal" name="is_internal_note" value="1">
                        <label for="is_internal" style="margin: 0; font-size: 12.5px; color: #475569;">
                            Catatan internal saja (Hanya terlihat oleh pembimbing & admin, tidak dikirim ke mahasiswa)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeReplyModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Tanggapan</button>
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

        function openReplyModal(id, item) {
            document.getElementById('replyForm').action = '/admin/aduan/' + id + '/reply';
            document.getElementById('aduanDetailBox').innerHTML =
                '<strong>No Aduan:</strong> #' + item.complaint_number + '<br>' +
                '<strong>Pelapor:</strong> ' + (item.mahasiswa ? item.mahasiswa.nama_lengkap : '-') + '<br>' +
                '<strong>Judul:</strong> ' + item.title + '<br>' +
                '<strong>Deskripsi:</strong> ' + item.description;

            document.getElementById('rep_status').value = item.status === 'submitted' ? 'in_progress' : item.status;
            document.getElementById('replyAduanModal').classList.add('active');
        }

        function closeReplyModal() {
            document.getElementById('replyAduanModal').classList.remove('active');
        }
    </script>
</body>
</html>
