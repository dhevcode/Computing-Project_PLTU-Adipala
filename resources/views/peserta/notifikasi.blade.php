@extends('peserta.layouts.app')

@section('title', 'Notifikasi Aktivitas')
@section('page_title', 'Pusat Notifikasi')
@section('page_subtitle', 'Riwayat pemberitahuan status absensi, peninjauan logbook, keputusan izin, aduan, dan publikasi nilai.')

@push('styles')
<style>
    /* ==========================================================================
       MODUL NOTIFIKASI - TEMA CLEAN DASHBOARD & RESPONSIVE
       ========================================================================== */

    /* ANIMATIONS */
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
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

    .btn-outline {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 40px; padding: 0 20px; border-radius: 8px; font-family: inherit; font-size: 13.5px; font-weight: 700;
        border: 1px solid var(--border); background: #fff; color: var(--text); cursor: pointer; text-decoration: none; transition: 0.2s;
    }
    .btn-outline:hover { background: var(--page); border-color: #CBD5E1; }

    /* ================= KARTU NOTIFIKASI ================= */
    .nt-card {
        background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 24px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.02); animation: fadeInUp 0.4s ease-out both;
    }

    .nt-list { display: flex; flex-direction: column; gap: 16px; }

    .nt-item {
        display: flex; justify-content: space-between; align-items: flex-start; gap: 16px;
        padding: 20px; border-radius: 12px; transition: 0.2s ease;
        background: #fff; border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.01);
    }
    .nt-item:hover { border-color: #CBD5E1; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }

    /* Notifikasi Belum Dibaca (Unread) */
    .nt-item.is-unread {
        background: var(--page); border: 1px solid var(--border);
        border-left: 4px solid var(--primary);
    }

    .nt-body { display: flex; gap: 16px; align-items: flex-start; flex: 1; min-width: 0; }

    .nt-ico {
        width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .tone-ok     { background: #ECFDF5; color: #059669; }
    .tone-warn   { background: #FFFBEB; color: #D97706; }
    .tone-danger { background: #FEF2F2; color: #DC2626; }
    .tone-info   { background: var(--primary-soft); color: var(--primary-dark); }

    .nt-content { display: flex; flex-direction: column; gap: 6px; flex: 1; }
    .nt-item-title { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 800; color: var(--text); margin: 0; line-height: 1.3; }
    .nt-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--primary); display: inline-block; flex-shrink: 0; }

    .nt-item-msg { font-size: 13.5px; color: var(--muted); line-height: 1.5; margin: 0; word-wrap: break-word; }
    .nt-item-time { font-size: 12px; color: var(--muted); font-weight: 600; margin-top: 4px; display: flex; align-items: center; gap: 6px; }

    /* ---------- State Kosong ---------- */
    .nt-empty { text-align: center; padding: 60px 20px; color: var(--muted); background: var(--page); border-radius: 12px; border: 2px dashed var(--border); }
    .nt-empty i { font-size: 48px; margin-bottom: 16px; opacity: 0.5; color: var(--muted); }
    .nt-empty h3 { font-size: 18px; font-weight: 800; color: var(--text); margin: 0 0 8px 0; }
    .nt-empty p { font-size: 14px; margin: 0; }

    /* ================= PAGINATION FIX (PERBAIKAN PANAH & LAYOUT) ================= */
    .pager-container { margin-top: 24px; width: 100%; border-top: 1px dashed var(--border); padding-top: 20px; }
    .pager-container nav { display: flex; flex-direction: column; gap: 16px; align-items: center; }
    @media (min-width: 640px) { .pager-container nav { flex-direction: row; justify-content: space-between; } }

    /* Mencegah Icon Panah Raksasa */
    .pager-container svg { width: 18px !important; height: 18px !important; }

    /* Teks Showing 1 to 10 of ... */
    .pager-container p { font-size: 13.5px !important; color: var(--muted) !important; margin: 0; }

    /* Wadah Pembungkus Angka */
    .pager-container .relative.z-0.inline-flex, .pager-container .pagination {
        display: inline-flex; flex-wrap: wrap; gap: 6px; box-shadow: none !important; margin: 0; padding: 0;
    }

    /* Tombol Navigasi & Angka */
    .pager-container a,
    .pager-container span[aria-disabled] > span,
    .pager-container span[aria-current] > span,
    .pager-container .page-link {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 36px; height: 36px; padding: 0 10px;
        font-size: 13.5px; font-weight: 700; text-decoration: none; line-height: 1;
        color: var(--text); background: #fff; border: 1px solid var(--border); border-radius: 8px !important;
        transition: 0.2s; margin: 0 !important;
    }
    .pager-container a:hover, .pager-container .page-link:hover { background: var(--page); border-color: #CBD5E1; z-index: 2; }

    /* Tombol Sedang Aktif (Current Page) */
    .pager-container span[aria-current] > span, .pager-container .page-item.active .page-link {
        background: var(--primary); color: #fff; border-color: var(--primary); z-index: 3;
    }

    /* Tombol Mati (Disabled Page) */
    .pager-container span[aria-disabled] > span, .pager-container .page-item.disabled .page-link {
        background: var(--page); color: #CBD5E1; cursor: not-allowed; border-color: var(--page);
    }

    /* Sembunyikan navigasi mobile ganda dari Laravel Tailwind */
    .pager-container nav > div.flex.justify-between.flex-1.sm\:hidden { display: none; }

    /* ================= RESPONSIVE MOBILE ================= */
    @media (max-width: 640px) {
        .dashboard-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .btn-outline { width: 100%; justify-content: center; }

        .nt-card { padding: 16px; }
        .nt-item { flex-direction: column; padding: 16px; }
        .nt-body { width: 100%; }

        /* Tombol tandai dibaca pindah ke bawah pada mobile */
        .nt-item form { align-self: stretch; width: 100%; margin-top: 12px; border-top: 1px dashed var(--page); padding-top: 12px; }
        .nt-item form button { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

<!-- ================= HEADER DASHBOARD ================= -->
<div class="dashboard-header">
    <div class="header-info">
        <h2>Pusat Notifikasi & Pemberitahuan</h2>
        <p>Kelola semua notifikasi terkait progres aktivitas magang Anda.</p>
    </div>
    <div>
        <form action="{{ route('peserta.notifikasi.readall') }}" method="POST">
            @csrf
            <button type="submit" class="btn-outline">
                <i class="fa-solid fa-check-double"></i> Tandai Semua Sudah Dibaca
            </button>
        </form>
    </div>
</div>

<!-- ================= DAFTAR NOTIFIKASI ================= -->
<div class="nt-card">
    <div class="nt-list">
        @forelse($notifications ?? [] as $n)
            <div class="nt-item {{ !$n->is_read ? 'is-unread' : '' }}">

                <div class="nt-body">
                    <!-- Ikon Notifikasi -->
                    <div class="nt-ico {{ match($n->type ?? 'info') {
                        'success' => 'tone-ok',
                        'warning' => 'tone-warn',
                        'danger'  => 'tone-danger',
                        default   => 'tone-info',
                    } }}">
                        <i class="fa-solid {{ match($n->type ?? 'info') {
                            'success' => 'fa-circle-check',
                            'warning' => 'fa-triangle-exclamation',
                            'danger'  => 'fa-circle-xmark',
                            default   => 'fa-bell',
                        } }}"></i>
                    </div>

                    <!-- Konten Teks -->
                    <div class="nt-content">
                        <h3 class="nt-item-title">
                            {{ $n->title ?? 'Pemberitahuan' }}
                            @if(!$n->is_read)
                                <span class="nt-dot" title="Belum dibaca"></span>
                            @endif
                        </h3>
                        <p class="nt-item-msg">{{ $n->message ?? '-' }}</p>
                        <div class="nt-item-time">
                            <i class="fa-regular fa-clock"></i> {{ isset($n->created_at) ? $n->created_at->diffForHumans() : '' }}
                            @if(isset($n->created_at))
                            <span style="font-weight: 500; font-size: 11px;">({{ $n->created_at->format('d/m/Y H:i') }} WIB)</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi (Hanya muncul jika belum dibaca) -->
                @if(!$n->is_read)
                    <div>
                        <form action="{{ route('peserta.notifikasi.read', $n->id_notification ?? 0) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-outline" style="height: 36px; padding: 0 16px; font-size: 12.5px; border-radius: 8px;">
                                <i class="fa-solid fa-check"></i> Tandai Dibaca
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="nt-empty">
                <i class="fa-regular fa-bell-slash"></i>
                <h3>Tidak Ada Notifikasi Baru</h3>
                <p>Pemberitahuan mengenai peninjauan logbook, cuti, atau informasi sistem akan tampil di sini.</p>
            </div>
        @endforelse
    </div>

    @if(($notifications ?? null) && $notifications->hasPages())
    <div class="pager-container">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

@endsection
