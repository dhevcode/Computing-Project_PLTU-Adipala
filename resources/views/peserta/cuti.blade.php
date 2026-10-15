@extends('peserta.layout')

@section('title', 'Pengajuan Cuti & Izin')

@section('content')

    <!-- Header Section -->
    <div class="page-header-card">
        <div class="page-header-content">
            <div class="page-icon">
                <i class="fa-regular fa-calendar-check"></i>
            </div>
            <div>
                <h1>Pengajuan Cuti & Izin Magang</h1>
                <p>Ajukan permohonan ketidakhadiran resmi untuk sakit, kegiatan akademik, atau cuti darurat.</p>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-primary" onclick="openCutiModal()">
                <i class="fa-solid fa-plus"></i> Buat Pengajuan Baru
            </button>
        </div>
    </div>

    <!-- Riwayat Pengajuan Cuti -->
    <div class="card">
        <div class="card-title-box">
            <h3><i class="fa-solid fa-clipboard-list"></i> Riwayat Pengajuan Cuti & Izin</h3>
        </div>

        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Tgl Pengajuan</th>
                        <th>Jenis Izin / Cuti</th>
                        <th>Periode Tanggal</th>
                        <th>Durasi</th>
                        <th>Alasan</th>
                        <th>Lampiran Bukti</th>
                        <th>Status</th>
                        <th>Catatan Pembimbing</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $item)
                        @php
                            $durasi = \Carbon\Carbon::parse($item->start_date)->diffInDays(\Carbon\Carbon::parse($item->end_date)) + 1;
                        @endphp
                        <tr>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ \App\Models\LeaveRequest::getLeaveTypeLabel($item->leave_type) }}</strong>
                            </td>
                            <td>
                                {{ $item->start_date->translatedFormat('d M Y') }} s/d {{ $item->end_date->translatedFormat('d M Y') }}
                            </td>
                            <td>{{ $durasi }} Hari</td>
                            <td style="max-width: 220px; font-size: 12.5px;">{{ $item->reason }}</td>
                            <td>
                                @if($item->attachment_path)
                                    <a href="{{ asset($item->attachment_path) }}" target="_blank" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">
                                        <i class="fa-solid fa-paperclip"></i> Dokumen Bukti
                                    </a>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $item->status }}">
                                    {{ match($item->status) {
                                        'submitted' => 'Menunggu Review',
                                        'under_review' => 'Sedang Diperiksa',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        'cancelled' => 'Dibatalkan',
                                        default => $item->status,
                                    } }}
                                </span>
                            </td>
                            <td style="max-width: 180px; font-size: 12px; color: #64748b;">
                                {{ $item->review_note ?: '-' }}
                            </td>
                            <td>
                                @if($item->status === 'submitted')
                                    <form action="{{ route('peserta.cuti.cancel', $item->id_leave) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline" style="color: #ef4444; border-color: #fca5a5;">
                                            <i class="fa-solid fa-xmark"></i> Batal
                                        </button>
                                    </form>
                                @else
                                    <span style="color: #cbd5e1;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 32px; color: #94a3b8;">
                                <i class="fa-regular fa-calendar-xmark" style="font-size: 32px; margin-bottom: 8px;"></i>
                                <p>Belum ada pengajuan cuti atau izin.</p>
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

    <!-- ================= MODAL PENGAJUAN CUTI / IZIN (README SECTION 10) ================= -->
    <div class="modal-overlay" id="cutiModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fa-regular fa-calendar-plus" style="color: var(--primary-color);"></i> Form Pengajuan Cuti & Izin Magang</h3>
                <button type="button" class="modal-close" onclick="closeCutiModal()">&times;</button>
            </div>
            <form action="{{ route('peserta.cuti.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="leave_type">Jenis Pengajuan Ketidakhadiran *</label>
                        <select id="leave_type" name="leave_type" class="form-select" required>
                            <option value="personal_leave">Cuti Kepentingan Pribadi / Keluarga</option>
                            <option value="sick_leave">Izin Sakit (Wajib melampirkan Surat Dokter)</option>
                            <option value="academic_leave">Izin Kegiatan Akademik (Sidang / Ujian / Kampus)</option>
                            <option value="official_permission">Izin Kegiatan Dinas / Resmi Perusahaan</option>
                            <option value="emergency">Izin Keadaan Darurat</option>
                            <option value="other">Jenis Izin Lainnya</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="leave_start">Tanggal Mulai Izin / Cuti *</label>
                            <input type="date" id="leave_start" name="start_date" min="{{ date('Y-m-d') }}" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="leave_end">Tanggal Selesai Izin / Cuti *</label>
                            <input type="date" id="leave_end" name="end_date" min="{{ date('Y-m-d') }}" required class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="leave_reason">Alasan Lengkap *</label>
                        <textarea id="leave_reason" name="reason" rows="3" class="form-control" placeholder="Jelaskan keperluan atau keterangan medis sakit..." required minlength="10"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="leave_emergency">Kontak yang Dapat Dihubungi Selama Izin</label>
                        <input type="text" id="leave_emergency" name="emergency_contact" value="{{ $mhs->emergency_contact_phone ? $mhs->emergency_contact_name . ' (' . $mhs->emergency_contact_phone . ')' : '' }}" placeholder="Nama & No HP (Contoh: Bapak Joko - 08123456789)" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="leave_file">Lampiran Bukti (Surat Dokter / Surat Izin Kampus)</label>
                        <input type="file" id="leave_file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="form-control">
                        <small style="color: #64748b; font-size: 11px;">Wajib untuk izin sakit. Format PDF/JPG/PNG. Maksimal 5 MB.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeCutiModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane"></i> Ajukan Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openCutiModal() {
        document.getElementById('cutiModal').classList.add('active');
    }
    function closeCutiModal() {
        document.getElementById('cutiModal').classList.remove('active');
    }
</script>
@endpush
