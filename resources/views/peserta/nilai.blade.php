@extends('peserta.layout')

@section('title', 'Arsip Nilai Harian')

@section('content')

<style>
    /* ==========================================================================
       ARSIP NILAI - PANEL KACA & KARTU STATISTIK (TEMA DASHBOARD)
       ========================================================================== */
    .nl-page {
        box-sizing: border-box; width: 100%; max-width: 1200px;
        margin-left: auto; margin-right: auto; color: var(--text);
    }
    .nl-page *, .nl-page *::before, .nl-page *::after { box-sizing: border-box; }

    /* ---------- Panel Utama ---------- */
    .nl-panel {
        background: var(--panel);
        backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 40px; padding: 30px;
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.22);
    }
    .nl-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 18px 24px; margin-bottom: 26px; }
    .nl-top-text { flex: 1 1 360px; min-width: 0; }
    .nl-title { margin: 0; font-size: clamp(24px, 3vw, 36px); font-weight: 700; letter-spacing: -0.025em; line-height: 1.15; color: #ffffff; }
    .nl-sub { margin: 10px 0 0; font-size: 13.5px; line-height: 1.6; color: var(--muted); font-weight: 500; }

    /* ---------- Bento Grid (Statistik Ringkasan) ---------- */
    .nl-bento { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 24px; }
    .nl-stat {
        background: var(--card); backdrop-filter: blur(16px) saturate(130%); -webkit-backdrop-filter: blur(16px) saturate(130%);
        border: 1px solid var(--border); border-radius: 22px; padding: 20px; display: flex; flex-direction: column;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
    }
    .nl-stat-head { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .nl-stat-ico {
        width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
        font-size: 16px; flex-shrink: 0; border: 1px solid transparent;
    }
    .ico-violet { background: var(--violet-bg); color: var(--violet); border-color: var(--violet-bd); }
    .ico-ok { background: var(--ok-bg); color: var(--ok); border-color: var(--ok-bd); }
    .ico-info { background: var(--info-bg); color: var(--info); border-color: var(--info-bd); }

    .nl-stat-lbl { font-size: 12.5px; font-weight: 600; color: var(--text-2); line-height: 1.2; }
    .nl-stat-val { font-family: var(--font-num); font-size: 32px; font-weight: 600; letter-spacing: -0.04em; color: #ffffff; line-height: 1; }

    /* ---------- Daftar Evaluasi Harian ---------- */
    .nl-list { display: flex; flex-direction: column; gap: 20px; }
    .nl-card {
        background: var(--card); border: 1px solid var(--border); border-radius: 26px; padding: 24px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
    }

    /* Header Kartu Evaluasi */
    .nl-card-head {
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;
        border-bottom: 1px solid var(--border); padding-bottom: 18px; margin-bottom: 18px;
    }
    .nl-meta { display: flex; flex-direction: column; gap: 6px; }
    .nl-meta-row { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-2); }
    .nl-meta-row i { color: var(--accent); width: 16px; text-align: center; }
    .nl-meta-row strong { color: #ffffff; font-weight: 600; }

    .nl-score-box { display: flex; align-items: center; gap: 16px; text-align: right; }
    .nl-score-wrap { display: flex; flex-direction: column; align-items: flex-end; gap: 2px; }
    .nl-score-lbl { font-family: var(--font-num); font-size: 10px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); }
    .nl-score-val { font-family: var(--font-num); font-size: 30px; font-weight: 700; color: var(--accent); line-height: 1; letter-spacing: -0.03em; }
    .nl-score-val small { font-size: 14px; color: var(--muted); font-weight: 500; letter-spacing: 0; }

    .nl-badge {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 9999px;
        font-size: 11px; font-weight: 700; white-space: nowrap;
        background: var(--ok-bg); color: var(--ok); border: 1px solid var(--ok-bd);
    }

    /* Grid Rincian Kriteria */
    .nl-crit-title { font-size: 12px; font-weight: 700; color: var(--text-2); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; font-family: var(--font-num); }
    .nl-crit-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px; margin-bottom: 18px; }
    .nl-crit {
        background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-strong); border-radius: 14px; padding: 14px 16px;
        transition: background 0.2s ease;
    }
    .nl-crit:hover { background: rgba(255, 255, 255, 0.07); }
    .nl-crit-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 6px; }
    .nl-crit-name { font-size: 13.5px; font-weight: 600; color: #ffffff; line-height: 1.3; }
    .nl-crit-score { font-family: var(--font-num); font-size: 16px; font-weight: 700; color: var(--accent); }

    .nl-crit-bot { display: flex; justify-content: space-between; gap: 10px; font-size: 11.5px; color: var(--muted); align-items: flex-start; }
    .nl-crit-weight { font-family: var(--font-num); white-space: nowrap; }
    .nl-crit-note { text-align: right; line-height: 1.4; color: var(--text-2); }

    /* Catatan Pembimbing */
    .nl-note-box {
        background: var(--info-bg); border-left: 4px solid var(--info); border-radius: 12px; padding: 14px 18px;
        color: var(--text); font-size: 13px; line-height: 1.5;
    }
    .nl-note-box strong { color: var(--info); display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 13.5px; }

    /* Empty State */
    .nl-empty { text-align: center; padding: 50px 20px; color: var(--muted); background: var(--card-2); border-radius: 24px; border: 1px dashed var(--border-strong); }
    .nl-empty i { font-size: 42px; margin-bottom: 16px; opacity: 0.5; color: var(--accent); }
    .nl-empty h3 { font-size: 16px; color: #ffffff; margin: 0 0 6px 0; }
    .nl-empty p { font-size: 13px; margin: 0; }

    /* Responsif */
    @media (max-width: 900px) {
        .nl-bento { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .nl-bento { grid-template-columns: 1fr; }
        .nl-panel { padding: 18px; border-radius: 30px; }
        .nl-card { padding: 18px; border-radius: 22px; }
        .nl-score-box { width: 100%; justify-content: space-between; flex-direction: row-reverse; }
        .nl-score-wrap { align-items: flex-start; }
    }
</style>

<div class="nl-page">
<div class="nl-panel">

    <!-- ================= KEPALA PANEL ================= -->
    <header class="nl-top">
        <div class="nl-top-text">
            <h1 class="nl-title">Arsip Nilai & Rekapitulasi Evaluasi</h1>
            <p class="nl-sub">Pantau hasil penilaian harian dan performa magang yang telah dipublikasikan oleh pembimbing lapangan.</p>
        </div>
    </header>

    <!-- ================= BENTO STATISTIK ================= -->
    <div class="nl-bento">
        <div class="nl-stat">
            <div class="nl-stat-head">
                <span class="nl-stat-ico ico-violet"><i class="fa-solid fa-award"></i></span>
                <span class="nl-stat-lbl">Rata-rata Nilai Keseluruhan</span>
            </div>
            <div class="nl-stat-val">{{ $averageScore > 0 ? $averageScore : '-' }}</div>
        </div>

        <div class="nl-stat">
            <div class="nl-stat-head">
                <span class="nl-stat-ico ico-ok"><i class="fa-solid fa-arrow-trend-up"></i></span>
                <span class="nl-stat-lbl">Nilai Tertinggi Dicapai</span>
            </div>
            <div class="nl-stat-val">{{ $highestScore > 0 ? $highestScore : '-' }}</div>
        </div>

        <div class="nl-stat">
            <div class="nl-stat-head">
                <span class="nl-stat-ico ico-info"><i class="fa-solid fa-clipboard-check"></i></span>
                <span class="nl-stat-lbl">Sesi Penilaian Dipublikasikan</span>
            </div>
            <div class="nl-stat-val">{{ $evaluations->count() }}</div>
        </div>
    </div>

    <!-- ================= DAFTAR PENILAIAN ================= -->
    <div class="nl-list">
        @forelse($evaluations as $eval)
            <div class="nl-card">
                <div class="nl-card-head">
                    <div class="nl-meta">
                        <div class="nl-meta-row">
                            <i class="fa-regular fa-calendar-check"></i>
                            Tanggal: <strong>{{ $eval->evaluation_date->translatedFormat('l, d F Y') }}</strong>
                        </div>
                        <div class="nl-meta-row">
                            <i class="fa-regular fa-user"></i>
                            Penilai: <strong>{{ $eval->evaluator->nama_lengkap ?? 'Pembimbing Lapangan' }}</strong>
                        </div>
                    </div>

                    <div class="nl-score-box">
                        <span class="nl-badge"><i class="fa-solid fa-check"></i> Dipublikasikan</span>
                        <div class="nl-score-wrap">
                            <span class="nl-score-lbl">Total Skor Akhir</span>
                            <div class="nl-score-val">{{ $eval->total_score }} <small>/ 100</small></div>
                        </div>
                    </div>
                </div>

                <!-- Rincian Kriteria -->
                <div>
                    <h4 class="nl-crit-title">Rincian Skor Per Kriteria Penilaian</h4>

                    <div class="nl-crit-grid">
                        @forelse($eval->details as $d)
                            <div class="nl-crit">
                                <div class="nl-crit-top">
                                    <span class="nl-crit-name">{{ $d->criterion->criterion_name ?? 'Kriteria' }}</span>
                                    <span class="nl-crit-score">{{ $d->score }}</span>
                                </div>
                                <div class="nl-crit-bot">
                                    <span class="nl-crit-weight">Bobot: {{ $d->criterion->weight ?? 10 }}%</span>
                                    <span class="nl-crit-note">{{ $d->notes ?: 'Memenuhi standar' }}</span>
                                </div>
                            </div>
                        @empty
                            <div style="font-size: 12.5px; color: var(--muted); font-style: italic; grid-column: 1/-1;">
                                Rincian sub-kriteria tidak tersedia.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Catatan Pembimbing -->
                @if($eval->participant_note)
                    <div class="nl-note-box">
                        <strong><i class="fa-solid fa-comment-dots"></i> Catatan & Rekomendasi Pembimbing:</strong>
                        {{ $eval->participant_note }}
                    </div>
                @endif
            </div>
        @empty
            <div class="nl-empty">
                <i class="fa-regular fa-star"></i>
                <h3>Belum Ada Penilaian yang Dipublikasikan</h3>
                <p>Nilai harian yang telah ditinjau dan dipublikasikan oleh pembimbing akan tampil secara otomatis di sini.</p>
            </div>
        @endforelse
    </div>

</div>
</div>

@endsection
