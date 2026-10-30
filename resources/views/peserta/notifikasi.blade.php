@extends('peserta.layout')

@section('title', 'Notifikasi Aktivitas')

@section('content')

<style>
    /* ==========================================================================
       NOTIFIKASI - PANEL KACA & DAFTAR (TEMA DASHBOARD)
       ========================================================================== */
    .nt-page {
        box-sizing: border-box; width: 100%; max-width: 1200px;
        margin-left: auto; margin-right: auto; color: var(--text);
    }
    .nt-page *, .nt-page *::before, .nt-page *::after { box-sizing: border-box; }

    /* ---------- Panel Utama ---------- */
    .nt-panel {
        background: var(--panel);
        backdrop-filter: blur(40px) saturate(140%); -webkit-backdrop-filter: blur(40px) saturate(140%);
        border: 1px solid var(--panel-border); border-radius: 40px; padding: 30px;
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.22);
    }
    .nt-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 18px 24px; margin-bottom: 26px; }
    .nt-top-text { flex: 1 1 360px; min-width: 0; }
    .nt-title { margin: 0; font-size: clamp(24px, 3vw, 36px); font-weight: 700; letter-spacing: -0.025em; line-height: 1.15; color: #ffffff; }
    .nt-sub { margin: 10px 0 0; font-size: 13.5px; line-height: 1.6; color: var(--muted); font-weight: 500; }
    .nt-controls { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }

    /* ---------- Tombol Dasar ---------- */
    .nt-page .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 40px; padding: 0 20px; border-radius: 9999px; font-family: inherit; font-size: 13.5px; font-weight: 600; line-height: 1;
        border: 1px solid transparent; cursor: pointer; text-decoration: none; white-space: nowrap;
        transition: background .2s ease, border-color .2s ease, transform .2s ease, color .2s ease;
    }
    .nt-page .btn:hover { transform: translateY(-1px); }
    .nt-page .btn-sm { height: 34px; padding: 0 14px; font-size: 12px; }
    .nt-page .btn-outline { background: var(--card-2); color: var(--text); border-color: var(--border-strong); }
    .nt-page .btn-outline:hover { background: var(--card-2-hover); color: #ffffff; }

    /* ---------- Daftar Notifikasi ---------- */
    .nt-card {
        background: var(--card); backdrop-filter: blur(16px) saturate(130%); -webkit-backdrop-filter: blur(16px) saturate(130%);
        border: 1px solid var(--border); border-radius: 26px; padding: 22px; min-width: 0;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
    }
    .nt-list { display: flex; flex-direction: column; gap: 14px; }

    .nt-item {
        display: flex; justify-content: space-between; align-items: flex-start; gap: 14px;
        padding: 18px; border-radius: 18px; transition: 0.2s ease;
        background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border);
    }
    .nt-item.is-unread {
        background: rgba(255, 255, 255, 0.06); border: 1px solid var(--border-strong);
        border-left: 4px solid var(--accent);
    }

    .nt-body { display: flex; gap: 16px; align-items: flex-start; flex: 1; min-width: 0; }

    .nt-ico {
        width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0; border: 1px solid transparent;
    }
    .tone-ok     { background: var(--ok-bg);     color: var(--ok);     border-color: var(--ok-bd); }
    .tone-warn   { background: var(--warn-bg);   color: var(--warn);   border-color: var(--warn-bd); }
    .tone-danger { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-bd); }
    .tone-info   { background: var(--info-bg);   color: var(--info);   border-color: var(--info-bd); }

    .nt-content { display: flex; flex-direction: column; gap: 4px; flex: 1; }
    .nt-item-title { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 700; color: #ffffff; margin: 0; line-height: 1.3; }
    .nt-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); display: inline-block; flex-shrink: 0; box-shadow: 0 0 8px var(--accent); }

    .nt-item-msg { font-size: 13.5px; color: var(--text-2); line-height: 1.5; margin: 0; }
    .nt-item-time { font-size: 11.5px; color: var(--muted); font-family: var(--font-num); margin-top: 4px; display: flex; align-items: center; gap: 6px; }

    /* ---------- State Kosong ---------- */
    .nt-empty { text-align: center; padding: 48px 20px; color: var(--muted); background: rgba(255, 255, 255, 0.02); border-radius: 20px; border: 1px dashed var(--border-strong); }
    .nt-empty i { font-size: 40px; margin-bottom: 16px; opacity: 0.5; color: var(--accent); }
    .nt-empty h3 { font-size: 16px; color: #ffffff; margin: 0 0 6px 0; }
    .nt-empty p { font-size: 13px; margin: 0; }

    /* ---------- Pagination ---------- */
    .nt-pager { margin-top: 24px; }
    .nt-pager nav[role="navigation"] > div:first-child { display: none; }
    .nt-pager nav[role="navigation"] svg { width: 16px; height: 16px; }
    .nt-pager nav[role="navigation"] p { font-size: 12px; color: var(--muted); margin: 0; }
    .nt-pager .pagination { display: flex; flex-wrap: wrap; gap: 6px; list-style: none; padding: 0; margin: 0; }
    .nt-pager a, .nt-pager .page-link, .nt-pager span[aria-current] > span, .nt-pager span[aria-disabled] > span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 12px;
        border-radius: 9999px; font-size: 12.5px; font-weight: 600; text-decoration: none;
        color: var(--text-2); background: var(--card-2); border: 1px solid var(--border);
    }
    .nt-pager .page-item.active .page-link, .nt-pager span[aria-current] > span { background: var(--accent); color: var(--accent-ink); border-color: var(--accent); }

    @media (max-width: 768px) {
        .nt-panel { padding: 18px; border-radius: 30px; }
        .nt-card { padding: 18px; border-radius: 20px; }
        .nt-item { flex-direction: column; }
        .nt-body { width: 100%; }
        .nt-item form { align-self: flex-end; }
    }
</style>

<div class="nt-page">
<div class="nt-panel">

    <!-- ================= KEPALA PANEL ================= -->
    <header class="nt-top">
        <div class="nt-top-text">
            <h1 class="nt-title">Pusat Notifikasi & Pemberitahuan</h1>
            <p class="nt-sub">Riwayat pemberitahuan status absensi, peninjauan logbook, keputusan cuti, aduan, dan publikasi nilai.</p>
        </div>
        <div class="nt-controls">
            <form action="{{ route('peserta.notifikasi.readall') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline">
                    <i class="fa-solid fa-check-double"></i> Tandai Semua Sudah Dibaca
                </button>
            </form>
        </div>
    </header>

    <!-- ================= DAFTAR NOTIFIKASI ================= -->
    <div class="nt-card">
        <div class="nt-list">
            @forelse($notifications as $n)
                <div class="nt-item {{ !$n->is_read ? 'is-unread' : '' }}">

                    <div class="nt-body">
                        <!-- Ikon Notifikasi -->
                        <div class="nt-ico {{ match($n->type) {
                            'success' => 'tone-ok',
                            'warning' => 'tone-warn',
                            'danger'  => 'tone-danger',
                            default   => 'tone-info',
                        } }}">
                            <i class="fa-solid {{ match($n->type) {
                                'success' => 'fa-circle-check',
                                'warning' => 'fa-triangle-exclamation',
                                'danger'  => 'fa-circle-xmark',
                                default   => 'fa-circle-info',
                            } }}"></i>
                        </div>

                        <!-- Konten Teks -->
                        <div class="nt-content">
                            <h3 class="nt-item-title">
                                {{ $n->title }}
                                @if(!$n->is_read)
                                    <span class="nt-dot" aria-label="Belum dibaca"></span>
                                @endif
                            </h3>
                            <p class="nt-item-msg">{{ $n->message }}</p>
                            <div class="nt-item-time">
                                <i class="fa-regular fa-clock"></i> {{ $n->created_at->diffForHumans() }}
                                ({{ $n->created_at->format('d/m/Y H:i') }} WIB)
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    @if(!$n->is_read)
                        <div>
                            <form action="{{ route('peserta.notifikasi.read', $n->id_notification) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline" title="Tandai Sudah Dibaca">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="nt-empty">
                    <i class="fa-regular fa-bell-slash"></i>
                    <h3>Tidak Ada Notifikasi</h3>
                    <p>Pemberitahuan aktivitas akan tampil di sini saat terjadi pembaharuan status.</p>
                </div>
            @endforelse
        </div>

        <div class="nt-pager">
            {{ $notifications->links() }}
        </div>
    </div>

</div>
</div>

@endsection
