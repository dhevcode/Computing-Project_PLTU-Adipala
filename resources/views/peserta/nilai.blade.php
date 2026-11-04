@extends('peserta.layouts.app')

@section('title', 'Arsip Nilai Keseluruhan')
@section('page_title', 'Arsip Nilai & Rekapitulasi Evaluasi')
@section('page_subtitle', 'Pantau hasil nilai keseluruhan berdasarkan kontrak magang dan evaluasi performa yang telah dipublikasikan.')

@push('styles')
<style>
    /* ==========================================================================
       MODUL ARSIP NILAI - TEMA CLEAN DASHBOARD (SINKRON DENGAN TEMA UTAMA)
       ========================================================================== */

    /* ANIMATIONS */
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-20px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* ================= HEADER DASHBOARD ================= */
    .dashboard-header {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 16px; margin-bottom: 24px;
        animation: fadeIn 0.4s ease-out;
    }
    .header-info h2 {
        font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px 0;
    }
    .header-info p {
        font-size: 13.5px; color: var(--muted); margin: 0; line-height: 1.4;
    }

    /* ================= STATS GRID (BENTO KARTU) ================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
        animation: fadeInUp 0.4s ease-out;
    }
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        display: flex;
        justify-content: space-between; /* Menjauhkan teks ke kiri dan ikon ke kanan */
        align-items: center;
        box-shadow: 0 4px 14px rgba(0,0,0,0.02);
        border: 1px solid var(--border);
        transition: 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.05);
        border-color: #CBD5E1;
    }

    /* Modifikasi Pembungkus Teks agar Label ada di atas Nilai */
    .stat-text-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .stat-label {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--muted);
    }
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: var(--text);
        line-height: 1;
    }

    /* Modifikasi Ikon di sebelah kanan */
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .stat-icon.primary { background: var(--primary-soft); color: var(--primary-dark); }
    .stat-icon.success { background: #ECFDF5; color: #059669; }
    .stat-icon.purple { background: #F5F3FF; color: #8B5CF6; } /* Warna icon disesuaikan dengan referensi gambar */

    /* ================= DAFTAR KARTU EVALUASI ================= */
    .eval-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .eval-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.02);
        animation: fadeInUp 0.4s ease-out both;
    }

    /* Staggered Animation */
    .eval-list > .eval-card:nth-child(1) { animation-delay: 0.1s; }
    .eval-list > .eval-card:nth-child(2) { animation-delay: 0.2s; }
    .eval-list > .eval-card:nth-child(3) { animation-delay: 0.3s; }

    .eval-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
        border-bottom: 1px dashed var(--page);
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .eval-meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
        min-width: 250px;
    }
    .eval-meta-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        color: var(--muted);
    }
    .eval-meta-row i { color: var(--primary); width: 16px; text-align: center; }
    .eval-meta-row strong { color: var(--text); font-weight: 700; }

    /* Skor Utama Kanan */
    .eval-score-box {
        display: flex;
        align-items: center;
        gap: 16px;
        text-align: right;
        flex-wrap: wrap;
    }
    .score-wrap {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
    }
    .score-lbl {
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--muted);
        letter-spacing: 0.05em;
    }
    .score-val {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary-dark);
        line-height: 1;
    }
    .score-val small {
        font-size: 14px;
        color: var(--muted);
        font-weight: 600;
    }

    .status-badge {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 9999px;
        font-size: 11.5px; font-weight: 700; white-space: nowrap;
        background: var(--primary-soft); color: var(--primary-dark); border: 1px solid rgba(0,0,0,0.05);
    }

    /* Rincian Kriteria Penilaian */
    .crit-title {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .crit-title i { color: var(--primary); }

    .crit-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }
    .crit-box {
        background: var(--page);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .crit-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }
    .crit-name {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text);
        line-height: 1.4;
    }
    .crit-score {
        font-size: 16px;
        font-weight: 800;
        color: var(--primary);
    }
    .crit-bot {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        font-size: 12.5px;
        align-items: flex-start;
    }
    .crit-weight {
        white-space: nowrap;
        font-weight: 700;
        color: var(--muted);
    }
    .crit-note {
        text-align: right;
        line-height: 1.4;
        color: var(--muted);
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Catatan Pembimbing */
    .note-box {
        background: #F8FAFC;
        border: 1px solid var(--border);
        border-left: 4px solid var(--primary);
        border-radius: 8px;
        padding: 16px;
        color: var(--text);
        font-size: 13.5px;
        line-height: 1.6;
    }
    .note-box strong {
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 13.5px;
        font-weight: 800;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--muted);
        background: var(--page);
        border-radius: 16px;
        border: 2px dashed var(--border);
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.4;
        color: var(--muted);
    }
    .empty-state h3 {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 8px 0;
    }
    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    /* RESPONSIVE MOBILE */
    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .dashboard-header { flex-direction: column; align-items: flex-start; gap: 12px; }

        .eval-card { padding: 18px; }
        .eval-head { flex-direction: column; align-items: stretch; gap: 16px; }
        .eval-score-box { width: 100%; justify-content: space-between; flex-direction: row-reverse; padding-top: 12px; border-top: 1px dashed var(--border); }
        .score-wrap { align-items: flex-start; }

        .crit-bot { flex-direction: column; align-items: flex-start; text-align: left; }
        .crit-note { text-align: left; margin-top: 4px; }
    }
</style>
@endpush

@section('content')

<!-- ================= HEADER DASHBOARD ================= -->
<div class="dashboard-header">
    <div class="header-info">
        <h2>Arsip Nilai & Rekapitulasi Evaluasi</h2>
        <p>Pantau hasil nilai keseluruhan berdasarkan kontrak magang dan evaluasi performa yang telah dipublikasikan.</p>
    </div>
</div>

<!-- ================= STATS GRID KARTU (BENTO) ================= -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-text-group">
            <span class="stat-label">Rata-rata Nilai</span>
            <div class="stat-value">{{ ($averageScore ?? 0) > 0 ? number_format($averageScore, 1) : '-' }}</div>
        </div>
        <div class="stat-icon purple"><i class="fa-solid fa-star"></i></div>
    </div>

    <div class="stat-card">
        <div class="stat-text-group">
            <span class="stat-label">Nilai Tertinggi Dicapai</span>
            <div class="stat-value">{{ ($highestScore ?? 0) > 0 ? $highestScore : '-' }}</div>
        </div>
        <div class="stat-icon success"><i class="fa-solid fa-arrow-trend-up"></i></div>
    </div>

    <div class="stat-card">
        <div class="stat-text-group">
            <span class="stat-label">Sesi Penilaian Publikasi</span>
            <div class="stat-value">{{ ($evaluations ?? collect())->count() }}</div>
        </div>
        <div class="stat-icon primary"><i class="fa-solid fa-clipboard-check"></i></div>
    </div>
</div>

<!-- ================= DAFTAR KARTU PENILAIAN ================= -->
<div class="eval-list">
    @forelse($evaluations ?? [] as $eval)
        <div class="eval-card">

            <div class="eval-head">
                <div class="eval-meta">
                    <div class="eval-meta-row">
                        <i class="fa-regular fa-calendar-check"></i>
                        Tanggal: <strong>{{ $eval->evaluation_date ? \Carbon\Carbon::parse($eval->evaluation_date)->translatedFormat('l, d F Y') : '-' }}</strong>
                    </div>
                    <div class="eval-meta-row">
                        <i class="fa-regular fa-user"></i>
                        Penilai: <strong>{{ $eval->evaluator->nama_lengkap ?? 'Pembimbing Lapangan' }}</strong>
                    </div>
                </div>

                <div class="eval-score-box">
                    <span class="status-badge"><i class="fa-solid fa-check"></i> Dipublikasikan</span>
                    <div class="score-wrap">
                        <span class="score-lbl">Total Skor Akhir</span>
                        <div class="score-val">{{ $eval->total_score ?? '0' }} <small>/ 100</small></div>
                    </div>
                </div>
            </div>

            <!-- Rincian Kriteria -->
            <div>
                <h4 class="crit-title"><i class="fa-solid fa-list-check"></i> Rincian Skor Per Kriteria Penilaian</h4>

                <div class="crit-grid">
                    @forelse($eval->details ?? [] as $d)
                        <div class="crit-box">
                            <div class="crit-top">
                                <span class="crit-name">{{ $d->criterion->criterion_name ?? 'Kriteria Penilaian' }}</span>
                                <span class="crit-score">{{ $d->score ?? '0' }}</span>
                            </div>
                            <div class="crit-bot">
                                <span class="crit-weight">Bobot: {{ $d->criterion->weight ?? '10' }}%</span>
                                <span class="crit-note">{{ $d->notes ?: 'Memenuhi standar dan ekspektasi.' }}</span>
                            </div>
                        </div>
                    @empty
                        <div style="font-size: 13.5px; color: var(--muted); font-style: italic; grid-column: 1/-1;">
                            Rincian sub-kriteria tidak tersedia.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Catatan Pembimbing -->
            @if($eval->participant_note ?? false)
                <div class="note-box">
                    <strong><i class="fa-solid fa-comment-dots"></i> Catatan & Rekomendasi Pembimbing:</strong>
                    <div style="word-wrap: break-word; overflow-wrap: break-word;">{{ $eval->participant_note }}</div>
                </div>
            @endif

        </div>
    @empty
        <div class="empty-state">
            <i class="fa-solid fa-star-half-stroke"></i>
            <h3>Belum Ada Penilaian yang Dipublikasikan</h3>
            <p>Hasil rekapitulasi nilai keseluruhan berdasarkan kontrak magang Anda akan muncul di sini jika telah disetujui pembimbing.</p>
        </div>
    @endforelse
</div>

@endsection
