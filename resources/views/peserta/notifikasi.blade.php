@extends('peserta.layout')

@section('title', 'Notifikasi Aktivitas')

@section('content')

    <!-- Header Section -->
    <div class="page-header-card">
        <div class="page-header-content">
            <div class="page-icon">
                <i class="fa-regular fa-bell"></i>
            </div>
            <div>
                <h1>Pusat Notifikasi & Pemberitahuan</h1>
                <p>Riwayat pemberitahuan status absensi, peninjauan logbook, keputusan cuti, aduan, dan publikasi nilai.</p>
            </div>
        </div>
        <div>
            <form action="{{ route('peserta.notifikasi.readall') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline">
                    <i class="fa-solid fa-check-double"></i> Tandai Semua Sudah Dibaca
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Notifikasi -->
    <div class="card">
        <div style="display: flex; flex-direction: column; gap: 12px;">
            @forelse($notifications as $n)
                <div style="padding: 16px; border-radius: 10px; background: {{ $n->is_read ? '#ffffff' : '#f0f9ff' }}; border: 1px solid {{ $n->is_read ? '#e2e8f0' : '#bae6fd' }}; display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; transition: 0.2s ease;">
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: {{ match($n->type) {
                            'success' => '#dcfce7',
                            'warning' => '#fef3c7',
                            'danger' => '#fee2e2',
                            default => '#e0f2fe',
                        } }}; color: {{ match($n->type) {
                            'success' => '#16a34a',
                            'warning' => '#d97706',
                            'danger' => '#dc2626',
                            default => '#0284c7',
                        } }}; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                            <i class="fa-solid {{ match($n->type) {
                                'success' => 'fa-circle-check',
                                'warning' => 'fa-triangle-exclamation',
                                'danger' => 'fa-circle-xmark',
                                default => 'fa-circle-info',
                            } }}"></i>
                        </div>

                        <div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <h3 style="font-size: 14px; font-weight: 700; color: #0f172a;">{{ $n->title }}</h3>
                                @if(!$n->is_read)
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #0284c7; display: inline-block;"></span>
                                @endif
                            </div>
                            <p style="font-size: 13px; color: #334155; margin-top: 4px; line-height: 1.4;">{{ $n->message }}</p>
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 6px;">
                                <i class="fa-regular fa-clock"></i> {{ $n->created_at->diffForHumans() }} ({{ $n->created_at->format('d/m/Y H:i') }} WIB)
                            </div>
                        </div>
                    </div>

                    <div>
                        @if(!$n->is_read)
                            <form action="{{ route('peserta.notifikasi.read', $n->id_notification) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline" title="Tandai Sudah Dibaca">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                    <i class="fa-regular fa-bell-slash" style="font-size: 40px; margin-bottom: 12px;"></i>
                    <h3 style="font-size: 16px; color: #475569;">Tidak Ada Notifikasi</h3>
                    <p style="font-size: 13px; margin-top: 4px;">Pemberitahuan aktivitas akan tampil di sini saat terjadi pembaharuan status.</p>
                </div>
            @endforelse
        </div>

        <div style="margin-top: 20px;">
            {{ $notifications->links() }}
        </div>
    </div>

@endsection
