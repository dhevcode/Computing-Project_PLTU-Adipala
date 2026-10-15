<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi & Pengumuman - Admin Monitoring PKL</title>
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
                    <i class="fa-regular fa-building"></i>
                </div>
                <div>
                    <h1>Informasi & Pengumuman Perusahaan</h1>
                    <p>Kelola profil PLTU Adipala, koordinat geofencing absensi, tata tertib, prosedur K3, dan pengumuman resmi.</p>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-primary" onclick="openTambahInfoModal()">
                    <i class="fa-solid fa-plus"></i> Tambah Informasi / Pengumuman
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Pengaturan Geofence & Perusahaan -->
        <div class="table-card" style="margin-bottom: 24px;">
            <div class="table-header">
                <h3><i class="fa-solid fa-sliders"></i> Pengaturan Profil & Radius Presensi PLTU Adipala</h3>
            </div>

            <form action="{{ route('admin.pengumuman.company') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px;">
                    <div class="form-group">
                        <label for="comp_name">Nama Unit Perusahaan</label>
                        <input type="text" id="comp_name" name="name" value="{{ $company->name ?? 'PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala' }}" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="comp_industry">Bidang Industri</label>
                        <input type="text" id="comp_industry" name="industry" value="{{ $company->industry ?? 'Pembangkit Tenaga Listrik' }}" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="comp_radius">Radius Presensi Geofence (Meter) *</label>
                        <input type="number" id="comp_radius" name="attendance_radius_meters" value="{{ $company->attendance_radius_meters ?? 150 }}" min="20" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="comp_lat">Latitude Pusat PLTU *</label>
                        <input type="text" id="comp_lat" name="latitude" value="{{ $company->latitude ?? -7.680810191412908 }}" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="comp_lng">Longitude Pusat PLTU *</label>
                        <input type="text" id="comp_lng" name="longitude" value="{{ $company->longitude ?? 109.1366921585062 }}" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="comp_start">Jam Masuk Kerja Pokok</label>
                        <input type="time" id="comp_start" name="work_start_time" value="{{ substr($company->work_start_time ?? '08:00:00', 0, 5) }}" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="comp_end">Jam Pulang Kerja Pokok</label>
                        <input type="time" id="comp_end" name="work_end_time" value="{{ substr($company->work_end_time ?? '16:00:00', 0, 5) }}" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="comp_addr">Alamat Lengkap Perusahaan</label>
                        <input type="text" id="comp_addr" name="address" value="{{ $company->address ?? 'Jl. Laut, Desa Bunton, Kec. Adipala, Kab. Cilacap, Jawa Tengah' }}" required class="form-control">
                    </div>
                </div>

                <div style="margin-top: 14px; text-align: right;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-regular fa-floppy-disk"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Informasi & Pengumuman -->
        <div class="table-card">
            <div class="table-header">
                <h3><i class="fa-solid fa-bullhorn"></i> Daftar Informasi, Tata Tertib & Pengumuman</h3>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Judul Informasi</th>
                            <th>Ringkasan Konten</th>
                            <th>Lampiran</th>
                            <th>Tanggal Publikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($informations as $info)
                            <tr>
                                <td>
                                    <span class="badge badge-info">
                                        {{ \App\Models\CompanyInformation::getCategoryLabel($info->category) }}
                                    </span>
                                </td>
                                <td><strong>{{ $info->title }}</strong></td>
                                <td style="max-width: 320px; font-size: 12.5px; color: #475569;">
                                    {{ Str::limit($info->content, 90) }}
                                </td>
                                <td>
                                    @if($info->attachment_path)
                                        <a href="{{ asset($info->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline">
                                            <i class="fa-solid fa-paperclip"></i> File
                                        </a>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td>{{ $info->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.pengumuman.destroy', $info->id_info) }}" method="POST" onsubmit="return confirm('Hapus informasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline" style="color: #ef4444; border-color: #fca5a5;">
                                            <i class="fa-regular fa-trash-can"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 24px; color: #94a3b8;">
                                    Belum ada informasi yang dipublikasikan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $informations->links() }}
            </div>
        </div>
    </main>

    <!-- Modal Tambah Informasi / Pengumuman -->
    <div class="modal-overlay" id="tambahInfoModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tambah Informasi / Pengumuman Baru</h3>
                <button type="button" class="modal-close" onclick="closeTambahInfoModal()">&times;</button>
            </div>
            <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="info_cat">Kategori Informasi *</label>
                        <select id="info_cat" name="category" class="form-select" required>
                            <option value="announcement">Pengumuman Resmi (Broadcast ke Peserta)</option>
                            <option value="work_rule">Tata Tertib Magang</option>
                            <option value="safety">Prosedur Keselamatan Kerja (K3)</option>
                            <option value="contact">Kontak Penting Perusahaan</option>
                            <option value="document">Dokumen / Panduan Unduhan</option>
                            <option value="general">Informasi Umum</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="info_title">Judul Informasi / Pengumuman *</label>
                        <input type="text" id="info_title" name="title" placeholder="Contoh: Jadwal Pembekalan K3 Pekan Pertama" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="info_content">Isi Informasi Lengkap *</label>
                        <textarea id="info_content" name="content" rows="4" class="form-control" placeholder="Tuliskan detail pengumuman atau peraturan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="info_file">Lampiran File (PDF / DOCX / Gambar)</label>
                        <input type="file" id="info_file" name="attachment" accept=".pdf,.docx,.jpg,.jpeg,.png" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeTambahInfoModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Publikasikan Informasi</button>
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

        function openTambahInfoModal() {
            document.getElementById('tambahInfoModal').classList.add('active');
        }
        function closeTambahInfoModal() {
            document.getElementById('tambahInfoModal').classList.remove('active');
        }
    </script>
</body>
</html>
