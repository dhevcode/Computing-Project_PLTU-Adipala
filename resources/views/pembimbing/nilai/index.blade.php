@extends('pembimbing.layouts.app')

@section('title', 'Penilaian Akhir Magang')
@section('page_title', 'Penilaian Akhir Magang')
@section('page_subtitle', 'Input nilai evaluasi keseluruhan mahasiswa bimbingan di akhir masa kontrak PKL')

@push('styles')
<!-- Menambahkan CSS khusus Flatpickr MonthSelect Plugin -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<style>
    /* ================= CARDS & TABLE ================= */
    .card { background: var(--bg, #ffffff); border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); border: 1px solid var(--border, #e2e8f0); overflow: hidden; padding: 24px; }
    
    .card-header-top { 
        display: flex; justify-content: space-between; align-items: center; 
        flex-wrap: wrap; gap: 16px; margin-bottom: 20px;
    }
    .card-title-wrap { display: flex; align-items: center; gap: 16px; }
    .icon-circle-box { 
        background: var(--primary-soft, #ECFDF5); color: var(--primary, #0A5F63); 
        font-size: 20px; width: 48px; height: 48px; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; flex-shrink: 0; 
    }
    .card-title-wrap h3 { font-size: 16.5px; font-weight: 700; color: var(--text, #0f172a); margin: 0 0 4px 0; line-height: 1.2; }
    .card-title-wrap p { font-size: 12.5px; color: var(--muted, #64748b); margin: 0; line-height: 1.4; }
    
    .status-badge { padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; border: 1px solid var(--border, #e2e8f0); }
    .badge-published { background: #ECFDF5; color: #059669; border-color: #A7F3D0; }
    .badge-draft { background: #F1F5F9; color: #475569; }

    .btn-sm { padding: 8px 14px; font-size: 12.5px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; transition: 0.2s; white-space: nowrap; height: fit-content; }
    .btn-outline { background: #F8FAFC; color: #475569; border: 1px solid var(--border, #e2e8f0); }
    .btn-outline:hover { background: #E2E8F0; color: var(--text, #0f172a); }
    .btn-primary { background: var(--primary, #0A5F63); color: #fff; border: 1px solid var(--primary, #0A5F63); }
    .btn-primary:hover { background: #07474a; border-color: #07474a; }

    /* ================= FILTER GRID ================= */
    .filter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; align-items: end; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--border, #e2e8f0); }
    .form-group { margin-bottom: 0; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--muted, #64748b); margin-bottom: 6px; }
    .btn-filter-full { width: 100%; height: 38px; justify-content: center; font-size: 13px; border-radius: 8px; margin-top: 24px; }

    /* ================= INPUT CUSTOM ================= */
    .date-wrapper { position: relative; width: 100%; }
    .custom-input, .search-input { 
        width: 100%; padding: 10px 0; font-family: inherit; font-size: 13.5px; color: var(--text, #0f172a); 
        background: transparent; border: none; border-bottom: 1.5px solid var(--border, #e2e8f0); 
        outline: none; transition: 0.2s; position: relative; z-index: 2; border-radius: 0;
    }
    .custom-input { cursor: pointer; }
    .custom-input:focus, .search-input:focus { border-bottom: 2px solid var(--primary, #0A5F63); }
    
    .date-wrapper::after { 
        content: '\f133'; font-family: 'Font Awesome 6 Free'; font-weight: 900; 
        position: absolute; right: 0; top: 50%; transform: translateY(-50%); 
        color: var(--primary, #0A5F63); z-index: 1; 
    }

    /* Sembunyikan Select Asli */
    select.custom-select { display: none !important; }

    /* ================= SCORING MODAL UI ================= */
    .score-item { display: flex; justify-content: space-between; align-items: center; background: #F8FAFC; padding: 12px 16px; border-radius: 8px; border: 1px solid var(--border, #e2e8f0); gap: 14px; margin-bottom: 10px; }
    .score-item-title { font-size: 13px; color: var(--text, #0f172a); font-weight: 700; margin-bottom: 4px; }
    .score-item-desc { font-size: 11px; color: var(--muted, #64748b); line-height: 1.4; }
    .score-input { width: 80px; text-align: center; font-weight: 700; font-size: 15px; border: 1.5px solid var(--border, #e2e8f0); border-radius: 6px; padding: 8px; outline: none; transition: 0.2s; color: var(--primary, #0A5F63); }
    .score-input:focus { border-color: var(--primary, #0A5F63); }

    /* ================= MODAL OVERLAY ================= */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 20px; opacity: 0; transition: opacity 0.2s; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .custom-modal { background: #fff; width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: scale(0.95) translateY(20px); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .modal-overlay.active .custom-modal { transform: scale(1) translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border, #e2e8f0); background: #F9FAFB; border-radius: 16px 16px 0 0; }
    .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--text, #0f172a); margin: 0; display: flex; align-items: center; gap: 8px;}
    .modal-close { background: none; border: none; font-size: 20px; color: var(--muted, #64748b); cursor: pointer; padding: 4px 8px; border-radius: 6px; transition: 0.2s; }
    .modal-close:hover { background: #FEE2E2; color: #DC2626; }
    .modal-body { padding: 24px; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border, #e2e8f0); display: flex; justify-content: flex-end; gap: 10px; background: #fafbfc; border-radius: 0 0 16px 16px; }

    /* ================= RESPONSIVE ================= */
    @media (min-width: 761px) {
        .cell-catatan { max-width: 250px; text-align: left; }
    }
    @media (max-width: 1100px) {
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
        .btn-filter-full { margin-top: 0; }
    }
    @media (max-width: 760px) {
        .filter-grid { grid-template-columns: 1fr; }
        .btn-filter-full { margin-top: 8px; }

        /* Tabel Mobile */
        table, tbody, tr, td { display: block; width: 100%; }
        thead { display: none; }
        tbody tr { border: 1px solid var(--border, #e2e8f0); border-radius: 12px; margin-bottom: 16px; padding: 16px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        tbody td { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #F1F5F9; text-align: right; word-wrap: break-word; overflow-wrap: break-word; }
        tbody td:last-child { border-bottom: none; padding-bottom: 0; }
        tbody td[data-label]::before { content: attr(data-label); font-weight: 600; color: var(--muted, #64748b); text-align: left; flex-shrink: 0; margin-right: 12px; font-size: 12.5px; }
        
        tbody td.cell-user { display: block; text-align: left; background: #F8FAFC; padding: 14px; border-radius: 8px; margin-bottom: 12px; border-bottom: none; }
        tbody td.cell-user::before { display: none; }
        
        tbody td.cell-action { display: grid; grid-template-columns: 1fr; gap: 8px; padding-top: 16px; margin-top: 8px; }
        tbody td.cell-action::before { display: none; }
        tbody td.cell-action .btn-sm { width: 100%; justify-content: center; }

        tbody td.cell-catatan { flex-direction: column; text-align: left; }
        tbody td.cell-catatan::before { margin-bottom: 8px; }
        
        .score-item { flex-direction: column; align-items: stretch; text-align: left; }
        .score-input { width: 100%; margin-top: 10px; }

        .modal-footer { flex-direction: column-reverse; }
        .modal-footer .btn-sm { width: 100%; justify-content: center; height: 38px; }
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

    @if(session('error'))
        <div class="alert alert-error animate-fade-up" style="background: #FEF2F2; color: #991B1B; border-color: #FECACA; padding: 12px; border-radius: 8px; display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
            <i class="fa-solid fa-circle-xmark"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- FILTER & DAFTAR PENILAIAN -->
    <div class="card animate-fade-up delay-1">
        
        <div class="card-header-top">
            <div class="card-title-wrap">
                <div class="icon-circle-box">
                    <i class="fa-solid fa-award"></i>
                </div>
                <div>
                    <h3>Arsip Rekap Penilaian Keseluruhan</h3>
                    <p>Daftar nilai akhir mahasiswa bimbingan yang telah atau belum dipublikasikan.</p>
                </div>
            </div>
            <button type="button" class="btn-sm btn-primary" onclick="openInputNilaiModal()">
                <i class="fa-solid fa-plus"></i> Input Nilai Baru
            </button>
        </div>

        <form action="{{ route('pembimbing.nilai') }}" method="GET" class="filter-grid">
            <div class="form-group">
                <label>Status Publikasi</label>
                <select name="status" class="d-none custom-select">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            <div class="form-group">
                <label>Bulan Penilaian</label>
                <div class="date-wrapper">
                    <input type="text" name="month" value="{{ request('month') }}" class="custom-input flatpickr-month-only" placeholder="Pilih Bulan">
                </div>
            </div>

            <div class="form-group">
                <label>Cari Mahasiswa</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIM..." class="search-input">
            </div>

            <div class="form-group">
                <button type="submit" class="btn-sm btn-primary btn-filter-full">
                    <i class="fa-solid fa-filter"></i> Filter Data
                </button>
            </div>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Mahasiswa & NIM</th>
                        <th>Tanggal Evaluasi</th>
                        <th>Penilai</th>
                        <th style="text-align: center;">Skor Akhir</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evaluations as $item)
                        <tr>
                            <td class="cell-user">
                                <strong style="color: var(--text, #0f172a); display: block;">{{ $item->mahasiswa->nama_lengkap ?? '-' }}</strong>
                                <small style="color: var(--muted, #64748b);">NIM: <span class="code-tag" style="background: transparent; padding: 0;">{{ $item->mahasiswa->nim ?? '-' }}</span></small>
                            </td>
                            
                            <td data-label="Tanggal Evaluasi">
                                <span style="font-weight: 600; color: var(--text, #0f172a);">{{ $item->evaluation_date->format('d/m/Y') }}</span>
                            </td>
                            
                            <td data-label="Penilai" style="font-size: 13px;">
                                {{ $item->evaluator->nama_lengkap ?? 'Pembimbing Lapangan' }}
                            </td>
                            
                            <td data-label="Skor Akhir" style="text-align: center;">
                                <span style="font-size: 18px; font-weight: 800; color: var(--primary, #0A5F63);">
                                    {{ $item->total_score }}
                                </span> <span style="font-size: 12px; color: var(--muted, #64748b);">/ 100</span>
                            </td>
                            
                            <td data-label="Status" style="text-align: center;">
                                <span class="status-badge {{ $item->status === 'published' ? 'badge-published' : 'badge-draft' }}">
                                    {{ $item->status === 'published' ? 'Dipublikasikan' : 'Draft' }}
                                </span>
                            </td>
                            
                            <td class="cell-action" style="text-align: center;">
                                <button type="button" class="btn-sm btn-outline" onclick='openDetailNilaiModal(@json($item))'>
                                    <i class="fa-solid fa-list-check"></i> Rincian Skor
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state" style="text-align: center; padding: 36px; color: var(--muted, #64748b);">
                                    <i class="fa-solid fa-folder-open" style="font-size: 36px; display: block; margin-bottom: 12px; opacity: 0.5;"></i>
                                    <p style="margin: 0;">Belum ada data evaluasi penilaian yang diinput.<br>Klik "Input Nilai Baru" untuk memulai.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($evaluations->hasPages())
            <div class="pagination-wrapper" style="margin-top: 20px;">
                {{ $evaluations->links() }}
            </div>
        @endif
    </div>

@endsection

<!-- ================= MODAL INPUT NILAI BARU ================= -->
<div class="modal-overlay" id="inputNilaiModal">
    <div class="custom-modal">
        <div class="modal-header">
            <h3><i class="fa-solid fa-star" style="color: var(--primary, #0A5F63);"></i> Input Penilaian Akhir Magang</h3>
            <button type="button" class="modal-close" onclick="closeInputNilaiModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('pembimbing.nilai.store') }}" method="POST" id="nilaiForm">
            @csrf
            <input type="hidden" name="action_type" id="nilaiActionType" value="publish">

            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Pilih Mahasiswa PKL <span style="color: #DC2626;">*</span></label>
                    <select name="mahasiswa_id" class="d-none custom-select" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswas as $m)
                            <option value="{{ $m->id_mhs }}">{{ $m->nama_lengkap }} (NIM: {{ $m->nim }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label>Tanggal Evaluasi <span style="color: #DC2626;">*</span></label>
                    <div class="date-wrapper">
                        <input type="text" name="evaluation_date" value="{{ date('Y-m-d') }}" class="flatpickr-date custom-input" required>
                    </div>
                </div>

                <div style="margin-bottom: 12px; border-bottom: 2px solid #F1F5F9; padding-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <h4 style="font-size: 13px; font-weight: 700; color: var(--primary, #0A5F63); text-transform: uppercase; margin: 0;">
                        Komponen Penilaian (Skala 0 - 100)
                    </h4>
                    <div style="font-size: 13px; font-weight: 700; color: var(--primary, #0A5F63);">
                        Total: <span id="previewScore">0.00</span> / 100
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    @foreach($criteria as $c)
                        <div class="score-item">
                            <div>
                                <div class="score-item-title">{{ $c->criterion_name }}</div>
                                <div class="score-item-desc">Bobot Maksimal: {{ $c->weight }}% &bull; {{ $c->description }}</div>
                            </div>
                            <div>
                                <input type="number" name="scores[{{ $c->id_criterion }}]" min="0" max="100" class="score-input" data-weight="{{ $c->weight }}" placeholder="0-100" required oninput="calculatePreviewScore()">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Catatan & Masukan untuk Peserta (Tampil di Dashboard Mhs)</label>
                    <textarea name="participant_note" rows="3" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border, #e2e8f0); border-radius: 8px; font-size: 13.5px; color: var(--text, #0f172a); background: #fff; outline: none; font-family: inherit; resize: vertical;" placeholder="Contoh: Sangat aktif dalam penyelesaian proyek, pertahankan kedisiplinan."></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>Catatan Internal Pembimbing (Opsional, Hanya untuk Arsip)</label>
                    <input type="text" name="internal_note" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border, #e2e8f0); border-radius: 8px; font-size: 13.5px; color: var(--text, #0f172a); background: #fff; outline: none; font-family: inherit;" placeholder="Catatan khusus pembimbing lapangan...">
                </div>
            </div>

            <div class="modal-footer" style="flex-wrap: wrap;">
                <button type="button" class="btn-sm btn-outline" onclick="closeInputNilaiModal()" style="margin-right: auto;">Batal</button>
                <button type="button" class="btn-sm btn-outline" onclick="submitNilaiAs('draft')" style="background: #FFFBEB; color: #D97706; border-color: #FDE68A;">
                    <i class="fa-regular fa-bookmark"></i> Simpan Draft
                </button>
                <button type="button" class="btn-sm btn-primary" onclick="submitNilaiAs('publish')">
                    <i class="fa-solid fa-paper-plane"></i> Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL RINCIAN NILAI ================= -->
<div class="modal-overlay" id="detailNilaiModal">
    <div class="custom-modal" style="max-width: 550px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-list-check" style="color: var(--primary, #0A5F63);"></i> Rincian Skor Akhir Magang</h3>
            <button type="button" class="modal-close" onclick="closeDetailNilaiModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <!-- Info Ringkasan -->
            <div style="background: #F8FAFC; padding: 16px; border-radius: 8px; margin-bottom: 16px; border: 1px solid var(--border, #e2e8f0);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <div>
                        <span style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase; display: block; margin-bottom: 2px;">Mahasiswa</span>
                        <strong id="dtlMhsName" style="font-size: 14px; color: var(--text, #0f172a);">-</strong>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase; display: block; margin-bottom: 2px;">Tanggal Evaluasi</span>
                        <strong id="dtlDate" style="font-size: 13.5px; color: var(--text, #0f172a);">-</strong>
                    </div>
                </div>
                <div style="border-top: 1px solid #E2E8F0; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 12.5px; font-weight: 700; color: var(--text, #0f172a);">SKOR AKHIR KESELURUHAN</span>
                    <div style="font-size: 20px; font-weight: 800; color: var(--primary, #0A5F63);">
                        <span id="dtlScore">-</span> <small style="font-size: 14px; color: var(--muted, #64748b); font-weight: 600;">/ 100</small>
                    </div>
                </div>
            </div>

            <!-- Breakdown Kriteria -->
            <label style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase; margin-bottom: 8px; display: block;">Detail Penilaian Kriteria</label>
            <div id="dtlCriteriaList" style="display: flex; flex-direction: column; gap: 8px;"></div>
            
            <div style="margin-top: 16px;">
                <label style="font-size: 11.5px; font-weight: 700; color: var(--muted, #64748b); text-transform: uppercase; margin-bottom: 4px; display: block;">Catatan untuk Peserta</label>
                <div id="dtlParticipantNote" style="font-size: 13px; color: var(--text, #0f172a); background: #F8FAFC; padding: 12px; border-radius: 8px; border: 1px solid var(--border, #e2e8f0); white-space: pre-wrap;">-</div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-sm btn-outline" onclick="closeDetailNilaiModal()">Tutup Rincian</button>
        </div>
    </div>
</div>

@push('scripts')
<!-- Memuat Plugin Flatpickr MonthSelect -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // 1. INIT FLATPICKR
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-date", { dateFormat: "Y-m-d", locale: "id", disableMobile: "true" });
            
            flatpickr(".flatpickr-month-only", {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true, 
                        dateFormat: "Y-m", 
                        altFormat: "F Y"
                    })
                ],
                locale: "id", 
                disableMobile: "true" 
            });
        }

        // 2. CUSTOM DROPDOWN
        const nativeSelects = document.querySelectorAll('select.custom-select');
        nativeSelects.forEach(select => {
            if (select.nextElementSibling && select.nextElementSibling.classList.contains('custom-select-wrapper')) return;

            const wrapper = document.createElement('div');
            wrapper.className = 'custom-select-wrapper';
            
            const activeOption = select.options[select.selectedIndex];
            const trigger = document.createElement('div');
            trigger.className = 'custom-select-trigger';
            trigger.innerHTML = `<span>${activeOption ? activeOption.text : 'Pilih...'}</span> <i class="fa-solid fa-chevron-down"></i>`;
            
            const optionsPanel = document.createElement('div');
            optionsPanel.className = 'custom-select-options';
            
            Array.from(select.options).forEach(option => {
                const optDiv = document.createElement('div');
                optDiv.className = 'custom-option';
                optDiv.textContent = option.text;
                if (option.selected) optDiv.classList.add('selected');
                
                optDiv.addEventListener('click', (e) => {
                    e.stopPropagation();
                    select.value = option.value;
                    trigger.querySelector('span').textContent = option.text;
                    wrapper.classList.remove('open');
                    
                    optionsPanel.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
                    optDiv.classList.add('selected');
                });
                optionsPanel.appendChild(optDiv);
            });
            
            wrapper.appendChild(trigger);
            wrapper.appendChild(optionsPanel);
            select.parentNode.insertBefore(wrapper, select.nextSibling);
            
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.custom-select-wrapper').forEach(w => {
                    if(w !== wrapper) w.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });
        });
        
        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-select-wrapper').forEach(w => w.classList.remove('open'));
        });

        // 3. MODAL OVERLAY ESC KEY & CLICK BG
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeInputNilaiModal();
                closeDetailNilaiModal();
            }
        });
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => {
                if(e.target === overlay) {
                    closeInputNilaiModal();
                    closeDetailNilaiModal();
                }
            });
        });
    });

    // ================= FUNGSI KALKULASI SKOR =================
    function calculatePreviewScore() {
        const inputs = document.querySelectorAll('.score-input');
        let weightedSum = 0;
        let totalWeight = 0;

        inputs.forEach(input => {
            const val = parseFloat(input.value) || 0;
            const weight = parseFloat(input.dataset.weight) || 0;
            weightedSum += (val * weight);
            totalWeight += weight;
        });

        const finalVal = totalWeight > 0 ? (weightedSum / totalWeight).toFixed(2) : '0.00';
        document.getElementById('previewScore').textContent = finalVal;
    }

    // ================= FUNGSI MODAL INPUT =================
    window.openInputNilaiModal = function() {
        // Reset formulir skor
        document.getElementById('nilaiForm').reset();
        document.getElementById('previewScore').textContent = '0.00';
        
        const m = document.getElementById('inputNilaiModal');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }
    
    window.closeInputNilaiModal = function() {
        const m = document.getElementById('inputNilaiModal');
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);
    }

    window.submitNilaiAs = function(type) {
        document.getElementById('nilaiActionType').value = type;
        const form = document.getElementById('nilaiForm');
        
        if (form.checkValidity()) {
            form.submit();
        } else {
            form.reportValidity();
        }
    }

    // ================= FUNGSI MODAL DETAIL =================
    window.openDetailNilaiModal = function(item) {
        document.getElementById('dtlMhsName').textContent = item.mahasiswa ? item.mahasiswa.nama_lengkap + ' (' + item.mahasiswa.nim + ')' : '-';
        
        if (item.evaluation_date) {
            const dateObj = new Date(item.evaluation_date);
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            document.getElementById('dtlDate').textContent = dateObj.toLocaleDateString('id-ID', options);
        } else {
            document.getElementById('dtlDate').textContent = '-';
        }
        
        document.getElementById('dtlScore').textContent = item.total_score;
        document.getElementById('dtlParticipantNote').textContent = item.participant_note || 'Tidak ada catatan.';

        const list = document.getElementById('dtlCriteriaList');
        list.innerHTML = '';

        if (item.details && item.details.length > 0) {
            item.details.forEach(d => {
                const div = document.createElement('div');
                div.style.display = 'flex';
                div.style.justifyContent = 'space-between';
                div.style.alignItems = 'center';
                div.style.padding = '10px 14px';
                div.style.background = '#F8FAFC';
                div.style.border = '1px solid #E2E8F0';
                div.style.borderRadius = '6px';
                div.innerHTML = `<span style="font-size: 13px; color: var(--text, #0f172a); font-weight: 600;">${d.criterion ? d.criterion.criterion_name : 'Kriteria'}</span> 
                                 <strong style="font-size: 14px; color: var(--primary, #0A5F63);">${d.score} <span style="font-size: 11px; color: var(--muted, #64748b); font-weight: 500;">/ 100</span></strong>`;
                list.appendChild(div);
            });
        } else {
            list.innerHTML = '<span style="color: var(--muted, #64748b); font-size: 13px; font-style: italic; background: #F8FAFC; padding: 12px; border-radius: 6px; text-align: center; border: 1px dashed #E2E8F0;">Rincian kriteria tidak tersedia.</span>';
        }

        const m = document.getElementById('detailNilaiModal');
        m.style.display = 'flex';
        setTimeout(() => m.classList.add('active'), 10);
    }

    window.closeDetailNilaiModal = function() {
        const m = document.getElementById('detailNilaiModal');
        m.classList.remove('active');
        setTimeout(() => m.style.display = 'none', 200);
    }
</script>
@endpush