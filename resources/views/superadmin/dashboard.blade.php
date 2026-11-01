@extends('superadmin.layouts.app')

@section('title', 'Dashboard Superadmin')
@section('page_title', 'Kelola Akun')
@section('page_subtitle', 'Sistem Pengelolaan & Kontrol Akses Administrator PLTU')

@section('content')

    <!-- QUICK STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h4>Admin Sistem</h4>
                <div class="stat-value">{{ $totalAdmin ?? $admins->whereIn('role', ['Admin', 'Super Admin'])->count() }}</div>
            </div>
            <div class="stat-icon primary">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h4>Pembimbing Lapangan</h4>
                <div class="stat-value">{{ $totalPembimbing ?? $admins->whereIn('role', ['Pembimbing', 'Pembimbing Lapangan'])->count() }}</div>
            </div>
            <div class="stat-icon warning">
                <i class="fa-solid fa-user-tie"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h4>Total Peserta Magang</h4>
                <div class="stat-value">{{ $totalMahasiswa ?? 0 }}</div>
            </div>
            <div class="stat-icon success">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </div>

        @php $tanpaAkun = $mahasiswaTanpaAkun ?? 0; @endphp
        <div class="stat-card">
            <div class="stat-info">
                <h4>Peserta Belum Ada Akun</h4>
                <div class="stat-value" style="color: {{ $tanpaAkun > 0 ? 'var(--error-text)' : 'var(--success-text)' }};">
                    {{ $tanpaAkun }}
                </div>
            </div>
            <div class="stat-icon {{ $tanpaAkun > 0 ? 'danger' : 'success' }}">
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </div>
    </div>

    <!-- DAFTAR ADMIN -->
    <div class="card">
        <div class="card-header">
            <div class="card-title-group">
                <h3>Daftar Administrator Sistem</h3>
                <p>Kelola hak akses, status akun, dan penugasan divisi admin PKL.</p>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Cari admin, username, divisi..." autocomplete="off">
            </div>
        </div>

        <div class="table-wrapper">
            <table id="adminTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Nama & Email</th>
                        <th>Username</th>
                        <th>Bagian / Divisi</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $index => $admin)
                        <tr class="data-row">
                            <td class="text-center row-number cell-number">{{ $index + 1 }}</td>
                            <td class="cell-user">
                                <div class="user-cell">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($admin->nama_lengkap ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="user-info">
                                        <span class="name">{{ $admin->nama_lengkap }}</span>
                                        <span class="email">{{ $admin->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Username"><span class="code-tag">{{ $admin->username }}</span></td>
                            <td data-label="Divisi">{{ $admin->divisi->nama_divisi ?? 'Belum Diatur' }}</td>
                            <td data-label="Role">
                                <span class="badge badge-role">
                                    <i class="fa-solid fa-shield-halved" style="font-size: 10px;"></i>
                                    {{ $admin->role }}
                                </span>
                            </td>
                            <td data-label="Status">
                                @if($admin->is_active)
                                    <span class="badge badge-active"><span class="badge-dot"></span> Aktif</span>
                                @else
                                    <span class="badge badge-inactive"><span class="badge-dot"></span> Nonaktif</span>
                                @endif
                            </td>
                            <td class="cell-action">
                                <div class="action-group">
                                    <a href="{{ route('superadmin.edit', $admin->id_users) }}" class="btn-action btn-edit" title="Edit Admin" aria-label="Edit {{ $admin->nama_lengkap }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('superadmin.destroy', $admin->id_users) }}" method="POST"
                                          data-confirm="Akun {{ $admin->nama_lengkap }} akan dihapus permanen dan tidak bisa dikembalikan."
                                          data-confirm-title="Hapus akun admin?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus Admin" aria-label="Hapus {{ $admin->nama_lengkap }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fa-solid fa-user-slash"></i>
                                    <p>Belum ada data admin terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    {{-- Muncul saat hasil pencarian kosong --}}
                    <tr id="noResultRow" class="d-none">
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <p>Tidak ada data yang cocok dengan pencarian.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    (function () {
        var input = document.getElementById('searchInput');
        var rows = document.querySelectorAll('#adminTable tr.data-row');
        var noResult = document.getElementById('noResultRow');

        input.addEventListener('input', function () {
            var q = input.value.trim().toLowerCase();
            var visible = 0;

            rows.forEach(function (row) {
                var match = row.textContent.toLowerCase().includes(q);
                row.classList.toggle('d-none', !match);
                if (match) visible++;
            });

            noResult.classList.toggle('d-none', !(rows.length > 0 && visible === 0));
        });
    })();
</script>
@endpush
