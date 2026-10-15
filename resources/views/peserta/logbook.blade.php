@extends('peserta.layout')

@section('title', 'Logbook Harian')

@section('content')

    <!-- Header Section -->
    <div class="page-header-card">
        <div class="page-header-content">
            <div class="page-icon">
                <i class="fa-regular fa-book-open"></i>
            </div>
            <div>
                <h1>Logbook Kegiatan Harian</h1>
                <p>Dokumentasikan kegiatan, hasil pencapaian, kendala, dan rencana magang Anda setiap hari kerja.</p>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-primary" onclick="openLogbookModal()">
                <i class="fa-solid fa-plus"></i> Buat Logbook Baru
            </button>
        </div>
    </div>

    <!-- Filter Nav Tabs (README Section 7.3) -->
    <div class="nav-tabs">
        <a href="{{ route('peserta.logbook', ['status' => 'all']) }}" class="nav-tab-item {{ request('status', 'all') === 'all' ? 'active' : '' }}">
            Semua Logbook
        </a>
        <a href="{{ route('peserta.logbook', ['status' => 'draft']) }}" class="nav-tab-item {{ request('status') === 'draft' ? 'active' : '' }}">
            Draft
        </a>
        <a href="{{ route('peserta.logbook', ['status' => 'submitted']) }}" class="nav-tab-item {{ request('status') === 'submitted' ? 'active' : '' }}">
            Menunggu Review
        </a>
        <a href="{{ route('peserta.logbook', ['status' => 'revision_required']) }}" class="nav-tab-item {{ request('status') === 'revision_required' ? 'active' : '' }}">
            Perlu Revisi
        </a>
        <a href="{{ route('peserta.logbook', ['status' => 'approved']) }}" class="nav-tab-item {{ request('status') === 'approved' ? 'active' : '' }}">
            Disetujui
        </a>
    </div>

    <!-- Daftar Logbook Cards -->
    <div style="display: flex; flex-direction: column; gap: 16px;">
        @forelse($logbooks as $item)
            <div class="card" style="margin-bottom: 0; border-left: 4px solid {{ match($item->status) {
                'approved' => '#10b981',
                'revision_required' => '#f59e0b',
                'submitted' => '#0284c7',
                default => '#94a3b8',
            } }};">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px; margin-bottom: 12px;">
                    <div>
                        <div style="font-size: 12px; color: #64748b; font-weight: 500;">
                            <i class="fa-regular fa-calendar"></i> {{ $item->logbook_date->translatedFormat('l, d F Y') }} &bull;
                            <i class="fa-regular fa-clock"></i> {{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }} WIB
                        </div>
                        <h2 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-top: 4px;">{{ $item->title }}</h2>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span class="badge badge-{{ $item->status }}">
                            {{ match($item->status) {
                                'draft' => 'Draft Tersimpan',
                                'submitted' => 'Menunggu Review',
                                'revision_required' => 'Perlu Revisi',
                                'approved' => 'Disetujui Pembimbing',
                                'rejected' => 'Ditolak',
                                default => $item->status,
                            } }}
                        </span>

                        @if(in_array($item->status, ['draft', 'revision_required']))
                            <button type="button" class="btn btn-sm btn-outline" onclick='editLogbook(@json($item))'>
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                        @endif
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; font-size: 13px; color: #334155; margin-bottom: 14px;">
                    <div>
                        <strong style="color: #64748b; font-size: 11px; text-transform: uppercase;">Aktivitas:</strong>
                        <p style="margin-top: 2px;">{{ $item->activity }}</p>
                    </div>

                    @if($item->result)
                    <div>
                        <strong style="color: #64748b; font-size: 11px; text-transform: uppercase;">Hasil / Output:</strong>
                        <p style="margin-top: 2px;">{{ $item->result }}</p>
                    </div>
                    @endif

                    @if($item->obstacle)
                    <div>
                        <strong style="color: #b45309; font-size: 11px; text-transform: uppercase;">Kendala & Solusi:</strong>
                        <p style="margin-top: 2px;">{{ $item->obstacle }} &bull; <em>{{ $item->solution }}</em></p>
                    </div>
                    @endif
                </div>

                <!-- Footer Logbook: Lampiran & Catatan Pembimbing -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 12px; font-size: 12px; color: #64748b; flex-wrap: wrap; gap: 10px;">
                    <div>
                        @if($item->attachment_path)
                            <a href="{{ asset($item->attachment_path) }}" target="_blank" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">
                                <i class="fa-solid fa-paperclip"></i> Unduh Lampiran: {{ $item->attachment_name ?: 'Lampiran Kegiatan' }}
                            </a>
                        @else
                            <span style="color: #94a3b8;">Tidak ada lampiran file</span>
                        @endif
                    </div>

                    @if($item->review_note)
                        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 6px 12px; color: #92400e;">
                            <strong>Catatan Pembimbing:</strong> {{ $item->review_note }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="card" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                <i class="fa-regular fa-book" style="font-size: 40px; margin-bottom: 12px;"></i>
                <h3 style="font-size: 16px; color: #475569;">Belum Ada Logbook Pada Kategori Ini</h3>
                <p style="font-size: 13px; margin-top: 4px;">Klik tombol "Buat Logbook Baru" untuk mencatat kegiatan harian Anda.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 20px;">
        {{ $logbooks->links() }}
    </div>

    <!-- ================= MODAL TAMBAH / EDIT LOGBOOK ================= -->
    <div class="modal-overlay" id="logbookModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fa-regular fa-book-open" style="color: var(--primary-color);"></i> Isi Logbook Harian</h3>
                <button type="button" class="modal-close" onclick="closeLogbookModal()">&times;</button>
            </div>
            <form action="{{ route('peserta.logbook.store') }}" method="POST" enctype="multipart/form-data" id="logbookForm">
                @csrf
                <input type="hidden" name="action_type" id="logbookActionType" value="submit">
                <input type="hidden" name="existing_attachment" id="existingAttachment">
                <input type="hidden" name="existing_attachment_name" id="existingAttachmentName">

                <div class="modal-body">
                    <!-- Pilih Tanggal Kegiatan Terintegrasi Absensi -->
                    <div class="form-group" style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 16px;">
                        <label for="lb_attendance_select" style="font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-calendar-check" style="color: var(--primary-color);"></i> Pilih dari Riwayat Absensi Anda:
                        </label>
                        <select id="lb_attendance_select" class="form-control" onchange="onAttendanceSelectChanged(this.value)" style="margin-top: 6px;">
                            <option value="">-- Pilih Tanggal Absensi --</option>
                            @foreach($attendances as $att)
                                <option value="{{ $att->attendance_date->format('Y-m-d') }}" {{ $att->attendance_date->format('Y-m-d') === date('Y-m-d') ? 'selected' : '' }}>
                                    {{ $att->attendance_date->translatedFormat('l, d F Y') }}
                                    [Masuk: {{ $att->check_in_at ? $att->check_in_at->format('H:i') . ' WIB' : 'Belum Check-in' }} | Pulang: {{ $att->check_out_at ? $att->check_out_at->format('H:i') . ' WIB' : 'Belum Check-out' }}]
                                </option>
                            @endforeach
                        </select>
                        <small style="color: #64748b; font-size: 11px;">Pilih tanggal untuk otomatis mengisi jam masuk (check-in) dan jam pulang (check-out) dari data presensi.</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="lb_date">Tanggal Kegiatan *</label>
                            <input type="date" id="lb_date" name="logbook_date" value="{{ date('Y-m-d') }}" required class="form-control" onchange="syncAttendanceWithLogbook(this.value)">
                        </div>
                        <div class="form-group">
                            <label for="lb_title">Judul Kegiatan *</label>
                            <input type="text" id="lb_title" name="title" placeholder="Contoh: Pemeliharaan Server Database SCADA" required class="form-control">
                        </div>
                    </div>

                    <!-- Notifikasi Status Sinkronisasi Presensi -->
                    <div id="attendanceSyncBox" style="margin-bottom: 16px; padding: 10px 14px; border-radius: 8px; font-size: 12px; display: flex; align-items: center; gap: 10px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
                        <i class="fa-solid fa-circle-check" style="font-size: 16px; color: #10b981;"></i>
                        <div id="attendanceSyncText">
                            <strong>Terintegrasi Absensi:</strong> Jam kerja terisi otomatis sesuai riwayat presensi.
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="lb_start" style="display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="fa-solid fa-clock"></i> Waktu Mulai (Check-in) *</span>
                                <span style="font-size: 10.5px; color: #0284c7; background: #e0f2fe; padding: 2px 6px; border-radius: 4px;"><i class="fa-solid fa-lock"></i> Auto Absen</span>
                            </label>
                            <input type="time" id="lb_start" name="start_time" value="{{ $defaultStartTime }}" required class="form-control" readonly style="background: #f8fafc; font-weight: 600;">
                        </div>
                        <div class="form-group">
                            <label for="lb_end" style="display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="fa-solid fa-clock"></i> Waktu Selesai (Check-out) *</span>
                                <span style="font-size: 10.5px; color: #0284c7; background: #e0f2fe; padding: 2px 6px; border-radius: 4px;"><i class="fa-solid fa-lock"></i> Auto Absen</span>
                            </label>
                            <input type="time" id="lb_end" name="end_time" value="{{ $defaultEndTime }}" required class="form-control" readonly style="background: #f8fafc; font-weight: 600;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lb_activity">Rincian Aktivitas Kegiatan *</label>
                        <textarea id="lb_activity" name="activity" rows="3" class="form-control" placeholder="Jelaskan secara detail pekerjaan yang Anda lakukan hari ini..." required minlength="15"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="lb_result">Hasil Pekerjaan / Output</label>
                        <textarea id="lb_result" name="result" rows="2" class="form-control" placeholder="Apa hasil nyata atau progress yang dicapai hari ini?"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="lb_obstacle">Kendala / Masalah yang Ditemui</label>
                            <textarea id="lb_obstacle" name="obstacle" rows="2" class="form-control" placeholder="Kendala teknis atau operasional jika ada..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="lb_solution">Solusi yang Diterapkan</label>
                            <textarea id="lb_solution" name="solution" rows="2" class="form-control" placeholder="Langkah pemecahan masalah yang dilakukan..."></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lb_next">Rencana Kegiatan Selanjutnya</label>
                        <input type="text" id="lb_next" name="next_plan" class="form-control" placeholder="Rencana aktivitas hari kerja berikutnya...">
                    </div>

                    <div class="form-group">
                        <label for="lb_attachment">Lampiran Pendukung (Foto/Laporan/PDF/DOCX)</label>
                        <input type="file" id="lb_attachment" name="attachment" accept=".pdf,.docx,.jpg,.jpeg,.png" class="form-control">
                        <small style="color: #64748b; font-size: 11px;">Maksimal 10 MB. Format: JPG, PNG, PDF, DOCX.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeLogbookModal()">Batal</button>
                    <button type="button" class="btn btn-warning" onclick="submitLogbookAs('draft')">
                        <i class="fa-regular fa-floppy-disk"></i> Simpan Draft
                    </button>
                    <button type="button" class="btn btn-primary" onclick="submitLogbookAs('submit')">
                        <i class="fa-solid fa-paper-plane"></i> Kirim ke Pembimbing
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const attendanceMap = @json($attendanceDates);
    const defaultStartTime = '{{ $defaultStartTime }}';
    const defaultEndTime = '{{ $defaultEndTime }}';

    function onAttendanceSelectChanged(dateVal) {
        if (!dateVal) return;
        document.getElementById('lb_date').value = dateVal;
        syncAttendanceWithLogbook(dateVal);
    }

    function syncAttendanceWithLogbook(dateVal) {
        const syncBox = document.getElementById('attendanceSyncBox');
        const syncText = document.getElementById('attendanceSyncText');
        const startInput = document.getElementById('lb_start');
        const endInput = document.getElementById('lb_end');
        const selectEl = document.getElementById('lb_attendance_select');

        if (selectEl && selectEl.value !== dateVal) {
            selectEl.value = dateVal;
        }

        if (attendanceMap && attendanceMap[dateVal]) {
            const att = attendanceMap[dateVal];
            const checkIn = att.check_in || defaultStartTime;
            const checkOut = att.check_out || defaultEndTime;

            startInput.value = checkIn;
            endInput.value = checkOut;

            syncBox.style.display = 'flex';
            syncBox.style.background = '#ecfdf5';
            syncBox.style.border = '1px solid #a7f3d0';
            syncBox.style.color = '#065f46';
            syncBox.querySelector('i').className = 'fa-solid fa-circle-check';
            syncBox.querySelector('i').style.color = '#10b981';

            let outMsg = att.check_out ? `${att.check_out} WIB` : `Belum Check-out (Estimasi: ${defaultEndTime} WIB)`;
            syncText.innerHTML = `<strong>Terintegrasi Absensi (${att.status_label}):</strong> Check-in: <strong>${att.check_in ? att.check_in + ' WIB' : 'Belum Check-in'}</strong> &bull; Check-out: <strong>${outMsg}</strong>`;
        } else {
            startInput.value = defaultStartTime;
            endInput.value = defaultEndTime;

            syncBox.style.display = 'flex';
            syncBox.style.background = '#fffbeb';
            syncBox.style.border = '1px solid #fde68a';
            syncBox.style.color = '#92400e';
            syncBox.querySelector('i').className = 'fa-solid fa-triangle-exclamation';
            syncBox.querySelector('i').style.color = '#f59e0b';

            syncText.innerHTML = `<strong>Tidak ada riwayat presensi tercatat pada tanggal ini.</strong> Jam kerja standar (${defaultStartTime} - ${defaultEndTime} WIB) digunakan secara otomatis.`;
        }
    }

    function openLogbookModal() {
        document.getElementById('logbookForm').reset();
        document.getElementById('modalTitle').innerHTML = '<i class="fa-regular fa-book-open" style="color: var(--primary-color);"></i> Isi Logbook Harian';
        const todayStr = '{{ date('Y-m-d') }}';
        document.getElementById('lb_date').value = todayStr;
        syncAttendanceWithLogbook(todayStr);
        document.getElementById('logbookModal').classList.add('active');
    }

    function closeLogbookModal() {
        document.getElementById('logbookModal').classList.remove('active');
    }

    function editLogbook(item) {
        document.getElementById('modalTitle').innerHTML = '<i class="fa-regular fa-pen-to-square" style="color: var(--primary-color);"></i> Perbaiki Logbook';
        const dateStr = item.logbook_date.split('T')[0];
        document.getElementById('lb_date').value = dateStr;
        syncAttendanceWithLogbook(dateStr);
        document.getElementById('lb_title').value = item.title;
        document.getElementById('lb_start').value = item.start_time.substring(0, 5);
        document.getElementById('lb_end').value = item.end_time.substring(0, 5);
        document.getElementById('lb_activity').value = item.activity;
        document.getElementById('lb_result').value = item.result || '';
        document.getElementById('lb_obstacle').value = item.obstacle || '';
        document.getElementById('lb_solution').value = item.solution || '';
        document.getElementById('lb_next').value = item.next_plan || '';
        document.getElementById('existingAttachment').value = item.attachment_path || '';
        document.getElementById('existingAttachmentName').value = item.attachment_name || '';

        document.getElementById('logbookModal').classList.add('active');
    }

    function submitLogbookAs(type) {
        document.getElementById('logbookActionType').value = type;
        const form = document.getElementById('logbookForm');
        if (form.checkValidity()) {
            form.submit();
        } else {
            form.reportValidity();
        }
    }
</script>
@endpush
