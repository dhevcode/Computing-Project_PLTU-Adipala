@extends('pembimbing.layout')

@section('title', 'Evaluasi & Penilaian Bimbingan')

@section('content')
<div class="page-header-card">
    <div class="page-header-content">
        <div class="page-header-icon">
            <i class="fa-regular fa-star"></i>
        </div>
        <div>
            <h1>Evaluasi & Penilaian Harian / Berkala</h1>
            <p>Berikan penilaian komprehensif berbasis bobot kompetensi dan sampaikan evaluasi kinerja lapangan kepada mahasiswa bimbingan Anda.</p>
        </div>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <button type="button" class="btn btn-primary" onclick="openInputNilaiModal()">
            <i class="fa-solid fa-plus"></i> Input Penilaian Baru
        </button>
    </div>
</div>

<div class="card-box">
    <!-- Filter Bar -->
    <form method="GET" action="{{ route('pembimbing.nilai') }}" class="filter-bar">
        <div>
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Pilih Mahasiswa</label>
            <select name="mahasiswa_id" class="form-select" onchange="this.form.submit()">
                <option value="all">-- Semua Mahasiswa Bimbingan --</option>
                @foreach($mahasiswas as $m)
                    <option value="{{ $m->id_mhs }}" {{ request('mahasiswa_id') == $m->id_mhs ? 'selected' : '' }}>
                        {{ $m->nama_lengkap }} ({{ $m->nim }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Status Publikasi</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="all">-- Semua Status --</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft Pembimbing</option>
            </select>
        </div>

        @if(request()->hasAny(['mahasiswa_id', 'status']))
            <div style="align-self: flex-end;">
                <a href="{{ route('pembimbing.nilai') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate-left"></i> Reset Filter
                </a>
            </div>
        @endif
    </form>

    <!-- Tabel Nilai -->
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Peserta Mahasiswa</th>
                    <th>Tanggal Penilaian</th>
                    <th>Penilai</th>
                    <th>Skor Akhir</th>
                    <th>Status</th>
                    <th>Catatan untuk Peserta</th>
                    <th>Detail Kriteria</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $item)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #0A5F63; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                    {{ strtoupper(substr($item->mahasiswa->nama_lengkap ?? 'M', 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $item->mahasiswa->nama_lengkap ?? '-' }}</strong><br>
                                    <small style="color: var(--text-muted);">NIM: {{ $item->mahasiswa->nim ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td><strong>{{ $item->evaluation_date->format('d/m/Y') }}</strong></td>
                        <td>{{ $item->evaluator->nama_lengkap ?? 'Pembimbing' }}</td>
                        <td>
                            <span style="font-size: 17px; font-weight: 800; color: #0A5F63;">
                                {{ $item->total_score }}
                            </span> <small style="color: var(--text-muted);">/ 100</small>
                        </td>
                        <td>
                            <span class="badge {{ $item->status === 'published' ? 'badge-published' : 'badge-draft' }}">
                                {{ $item->status === 'published' ? 'Dipublikasikan' : 'Draft' }}
                            </span>
                        </td>
                        <td style="max-width: 250px; font-size: 12.5px; color: var(--text-dark);">
                            {{ $item->participant_note ?: '-' }}
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-secondary" onclick='openDetailNilaiModal(@json($item))'>
                                <i class="fa-solid fa-list-check"></i> Rincian Skor
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 36px; color: var(--text-muted);">
                            <i class="fa-regular fa-star" style="font-size: 36px; display: block; margin-bottom: 8px;"></i>
                            Belum ada penilaian yang diinput untuk mahasiswa bimbingan Anda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $evaluations->links() }}
    </div>
</div>

<!-- Modal Input Penilaian Baru -->
<div class="modal-overlay" id="inputNilaiModal">
    <div class="modal-card" style="max-width: 720px;">
        <div class="modal-header">
            <h3>Input Evaluasi & Penilaian Bimbingan</h3>
            <button type="button" class="modal-close" onclick="closeInputNilaiModal()">&times;</button>
        </div>
        <form action="{{ route('pembimbing.nilai.store') }}" method="POST" id="nilaiForm">
            @csrf
            <input type="hidden" name="action_type" id="nilaiActionType" value="publish">

            <div class="modal-body" style="max-height: 70vh;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Pilih Mahasiswa Bimbingan *</label>
                        <select name="mahasiswa_id" class="form-select" style="width: 100%;" required>
                            <option value="">-- Pilih Mahasiswa Bimbingan --</option>
                            @foreach($mahasiswas as $m)
                                <option value="{{ $m->id_mhs }}">{{ $m->nama_lengkap }} ({{ $m->nim }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Tanggal Penilaian *</label>
                        <input type="date" name="evaluation_date" class="form-control" style="width: 100%;" value="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                    </div>
                </div>

                <div style="background: #F8FAFC; padding: 14px; border-radius: var(--radius-md); margin-bottom: 16px; border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <h4 style="font-size: 13px; font-weight: 700; color: #0A5F63; text-transform: uppercase;">
                            <i class="fa-solid fa-calculator"></i> Kriteria Kompetensi & Bobot
                        </h4>
                        <div style="font-size: 13px; font-weight: 700; color: #0A5F63;">
                            Estimasi Skor: <span id="previewScore">0.00</span> / 100
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($criteria as $c)
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 14px; background: #fff; padding: 10px 14px; border-radius: var(--radius-sm); border: 1px solid #E2E8F0;">
                                <div style="flex: 1;">
                                    <strong style="font-size: 13px;">{{ $c->criterion_name }}</strong>
                                    <div style="font-size: 11px; color: var(--text-muted);">
                                        Bobot: {{ $c->weight }}% • {{ $c->description }}
                                    </div>
                                </div>
                                <div style="width: 100px;">
                                    <input type="number" name="scores[{{ $c->id_criterion }}]" class="form-control score-input" data-weight="{{ $c->weight }}" min="0" max="100" step="1" placeholder="0-100" required oninput="calculatePreviewScore()">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                        Catatan / Feedback untuk Mahasiswa
                    </label>
                    <textarea name="participant_note" rows="2" class="form-control" style="width: 100%;" placeholder="Catatan evaluasi ini dapat dibaca oleh mahasiswa di portalnya..."></textarea>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                        Catatan Internal Pembimbing (Rahasia)
                    </label>
                    <textarea name="internal_note" rows="2" class="form-control" style="width: 100%;" placeholder="Catatan khusus hanya untuk dokumentasi pembimbing/HR..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeInputNilaiModal()">Batal</button>
                <button type="submit" class="btn btn-secondary" onclick="document.getElementById('nilaiActionType').value='draft'">
                    <i class="fa-regular fa-bookmark"></i> Simpan Draft
                </button>
                <button type="submit" class="btn btn-primary" onclick="document.getElementById('nilaiActionType').value='publish'">
                    <i class="fa-solid fa-paper-plane"></i> Publikasikan Nilai
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Rincian Nilai -->
<div class="modal-overlay" id="detailNilaiModal">
    <div class="modal-card" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Rincian Skor Penilaian Evaluasi</h3>
            <button type="button" class="modal-close" onclick="closeDetailNilaiModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div style="margin-bottom: 14px; background: #F8FAFC; padding: 12px; border-radius: var(--radius-md);">
                <p>Mahasiswa: <strong id="dtlMhsName">-</strong></p>
                <p>Tanggal: <strong id="dtlDate">-</strong></p>
                <p>Skor Akhir: <strong id="dtlScore" style="color: #0A5F63; font-size: 16px;">-</strong></p>
            </div>

            <div id="dtlCriteriaList" style="display: flex; flex-direction: column; gap: 8px;"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeDetailNilaiModal()">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openInputNilaiModal() {
    document.getElementById('inputNilaiModal').classList.add('active');
}

function closeInputNilaiModal() {
    document.getElementById('inputNilaiModal').classList.remove('active');
}

function calculatePreviewScore() {
    const inputs = document.querySelectorAll('.score-input');
    let weightedSum = 0;
    let totalWeight = 0;

    inputs.forEach(input => {
        const val = parseFloat(input.value) || 0;
        const weight = parseFloat(input.dataset.weight) || 10;
        weightedSum += (val * weight);
        totalWeight += weight;
    });

    const finalVal = totalWeight > 0 ? (weightedSum / totalWeight).toFixed(2) : '0.00';
    document.getElementById('previewScore').textContent = finalVal;
}

function openDetailNilaiModal(item) {
    document.getElementById('dtlMhsName').textContent = item.mahasiswa ? item.mahasiswa.nama_lengkap + ' (' + item.mahasiswa.nim + ')' : '-';
    document.getElementById('dtlDate').textContent = item.evaluation_date ? item.evaluation_date.split('T')[0] : '-';
    document.getElementById('dtlScore').textContent = item.total_score + ' / 100';

    const list = document.getElementById('dtlCriteriaList');
    list.innerHTML = '';

    if (item.details && item.details.length > 0) {
        item.details.forEach(d => {
            const div = document.createElement('div');
            div.style.display = 'flex';
            div.style.justifyContent = 'space-between';
            div.style.padding = '8px 12px';
            div.style.background = '#F8FAFC';
            div.style.borderRadius = '6px';
            div.innerHTML = `<span>${d.criterion ? d.criterion.criterion_name : 'Kriteria'}:</span> <strong>${d.score} / 100</strong>`;
            list.appendChild(div);
        });
    } else {
        list.innerHTML = '<span style="color: var(--text-muted); font-size: 13px;">Tidak ada rincian kriteria.</span>';
    }

    document.getElementById('detailNilaiModal').classList.add('active');
}

function closeDetailNilaiModal() {
    document.getElementById('detailNilaiModal').classList.remove('active');
}
</script>
@endpush
@endsection
