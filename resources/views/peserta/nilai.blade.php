@extends('peserta.layout')

@section('title', 'Arsip Nilai Harian')

@section('content')

    <!-- Header Section -->
    <div class="page-header-card">
        <div class="page-header-content">
            <div class="page-icon">
                <i class="fa-regular fa-star"></i>
            </div>
            <div>
                <h1>Arsip Nilai & Rekapitulasi Evaluasi</h1>
                <p>Pantau hasil penilaian harian dan performa magang yang telah dipublikasikan oleh pembimbing lapangan.</p>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik Nilai -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fa-solid fa-award"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $averageScore > 0 ? $averageScore : '-' }}</div>
                <div class="stat-label">Rata-rata Nilai Keseluruhan</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-arrow-trend-up"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $highestScore > 0 ? $highestScore : '-' }}</div>
                <div class="stat-label">Nilai Tertinggi Dicapai</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue"><i class="fa-solid fa-clipboard-check"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $evaluations->count() }}</div>
                <div class="stat-label">Sesi Penilaian Dipublikasikan</div>
            </div>
        </div>
    </div>

    <!-- Daftar Penilaian Harian -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        @forelse($evaluations as $eval)
            <div class="card" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <div style="font-size: 12px; color: #64748b;">
                            <i class="fa-regular fa-calendar-check"></i> Tanggal Penilaian: <strong>{{ $eval->evaluation_date->translatedFormat('l, d F Y') }}</strong>
                        </div>
                        <div style="font-size: 13px; color: #334155; margin-top: 2px;">
                            Penilai: <strong>{{ $eval->evaluator->nama_lengkap ?? 'Pembimbing Lapangan' }}</strong>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="text-align: right;">
                            <div style="font-size: 11px; color: #64748b; text-transform: uppercase;">Total Skor Akhir</div>
                            <div style="font-size: 26px; font-weight: 800; color: var(--primary-color);">
                                {{ $eval->total_score }} <span style="font-size: 14px; font-weight: 500; color: #64748b;">/ 100</span>
                            </div>
                        </div>
                        <span class="badge badge-success">Dipublikasikan</span>
                    </div>
                </div>

                <!-- Rincian Kriteria Penilaian -->
                <div style="margin-bottom: 16px;">
                    <h4 style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 10px;">
                        Rincian Skor Per Kriteria Penilaian
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px;">
                        @forelse($eval->details as $d)
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                    <span style="font-weight: 600; font-size: 13px; color: #1e293b;">
                                        {{ $d->criterion->criterion_name ?? 'Kriteria' }}
                                    </span>
                                    <span style="font-weight: 800; font-size: 14px; color: var(--primary-color);">
                                        {{ $d->score }}
                                    </span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 11px; color: #64748b;">
                                    <span>Bobot: {{ $d->criterion->weight ?? 10 }}%</span>
                                    <span>{{ $d->notes ?: 'Memenuhi standar' }}</span>
                                </div>
                            </div>
                        @empty
                            <div style="font-size: 12px; color: #94a3b8; font-style: italic;">
                                Rincian sub-kriteria tidak tersedia.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Catatan Pembimbing untuk Peserta -->
                @if($eval->participant_note)
                    <div style="background: #ecfdf5; border-left: 4px solid #10b981; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #065f46;">
                        <strong><i class="fa-solid fa-comment-dots"></i> Catatan & Rekomendasi Pembimbing:</strong>
                        <p style="margin-top: 4px; line-height: 1.4;">{{ $eval->participant_note }}</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="card" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                <i class="fa-regular fa-star" style="font-size: 40px; margin-bottom: 12px;"></i>
                <h3 style="font-size: 16px; color: #475569;">Belum Ada Penilaian yang Dipublikasikan</h3>
                <p style="font-size: 13px; margin-top: 4px;">Nilai harian yang telah ditinjau dan dipublikasikan oleh pembimbing akan tampil secara otomatis di sini.</p>
            </div>
        @endforelse
    </div>

@endsection
