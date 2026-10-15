<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Harian - Admin Monitoring PKL</title>
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
                    <i class="fa-regular fa-star"></i>
                </div>
                <div>
                    <h1>Penilaian Harian & Evaluasi Magang</h1>
                    <p>Input penilaian harian/mingguan mahasiswa berdasarkan kriteria pembobotan dan publikasikan hasil evaluasi.</p>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-primary" onclick="openInputNilaiModal()">
                    <i class="fa-solid fa-plus"></i> Input Nilai Baru
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Daftar Nilai -->
        <div class="table-card">
            <div class="table-header">
                <h3><i class="fa-solid fa-award"></i> Arsip Rekap Penilaian Mahasiswa</h3>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Tanggal Penilaian</th>
                            <th>Penilai</th>
                            <th>Skor Akhir</th>
                            <th>Status Publikasi</th>
                            <th>Catatan Peserta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                                    <small style="color: #64748b;">NIM: {{ $item->mahasiswa->nim ?? '-' }}</small>
                                </td>
                                <td><strong>{{ $item->evaluation_date->format('d/m/Y') }}</strong></td>
                                <td>{{ $item->evaluator->nama_lengkap ?? 'Pembimbing' }}</td>
                                <td>
                                    <span style="font-size: 16px; font-weight: 800; color: #0A5F63;">
                                        {{ $item->total_score }}
                                    </span> / 100
                                </td>
                                <td>
                                    <span class="badge {{ $item->status === 'published' ? 'badge-success' : 'badge-draft' }}">
                                        {{ $item->status === 'published' ? 'Dipublikasikan' : 'Draft' }}
                                    </span>
                                </td>
                                <td style="max-width: 250px; font-size: 12px; color: #475569;">
                                    {{ $item->participant_note ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 24px; color: #94a3b8;">
                                    Belum ada data penilaian yang diinput. Klik "Input Nilai Baru" untuk memulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $evaluations->links() }}
            </div>
        </div>
    </main>

    <!-- Modal Input Nilai Baru -->
    <div class="modal-overlay" id="inputNilaiModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Input Penilaian Mahasiswa PKL</h3>
                <button type="button" class="modal-close" onclick="closeInputNilaiModal()">&times;</button>
            </div>
            <form action="{{ route('admin.nilai.store') }}" method="POST" id="nilaiForm">
                @csrf
                <input type="hidden" name="action_type" id="nilaiActionType" value="publish">

                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="eval_mhs">Pilih Mahasiswa *</label>
                            <select id="eval_mhs" name="mahasiswa_id" class="form-select" required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $m)
                                    <option value="{{ $m->id_mhs }}">{{ $m->nama_lengkap }} ({{ $m->nim }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="eval_date">Tanggal Penilaian *</label>
                            <input type="date" id="eval_date" name="evaluation_date" value="{{ date('Y-m-d') }}" required class="form-control">
                        </div>
                    </div>

                    <div style="margin: 16px 0 10px;">
                        <h4 style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase;">
                            Skor Penilaian Per Kriteria (Skala 0 - 100):
                        </h4>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($criteria as $c)
                            <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0; gap: 14px;">
                                <div style="flex: 1;">
                                    <strong style="font-size: 13px; color: #1e293b;">{{ $c->criterion_name }}</strong>
                                    <div style="font-size: 11px; color: #64748b;">Bobot: {{ $c->weight }}% &bull; {{ $c->description }}</div>
                                </div>
                                <div style="width: 100px;">
                                    <input type="number" name="scores[{{ $c->id_criterion }}]" min="0" max="100" value="85" required class="form-control" style="text-align: center; font-weight: 700;">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group" style="margin-top: 16px;">
                        <label for="eval_participant_note">Catatan & Masukan untuk Peserta (Terlihat oleh Mahasiswa)</label>
                        <textarea id="eval_participant_note" name="participant_note" rows="2" class="form-control" placeholder="Contoh: Sangat aktif dalam pemecahan masalah jaringan, pertahankan kedisiplinan."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="eval_internal_note">Catatan Internal Pembimbing (Hanya terlihat oleh Admin)</label>
                        <input type="text" id="eval_internal_note" name="internal_note" class="form-control" placeholder="Catatan evaluasi internal khusus pembimbing...">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeInputNilaiModal()">Batal</button>
                    <button type="button" class="btn btn-warning" onclick="submitNilaiAs('draft')">Simpan Draft</button>
                    <button type="button" class="btn btn-primary" onclick="submitNilaiAs('publish')">Publikasikan Nilai</button>
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

        function openInputNilaiModal() {
            document.getElementById('inputNilaiModal').classList.add('active');
        }
        function closeInputNilaiModal() {
            document.getElementById('inputNilaiModal').classList.remove('active');
        }
        function submitNilaiAs(type) {
            document.getElementById('nilaiActionType').value = type;
            const form = document.getElementById('nilaiForm');
            if (form.checkValidity()) {
                form.submit();
            } else {
                form.reportValidity();
            }
        }
    </script>
</body>
</html>
