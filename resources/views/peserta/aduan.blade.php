@extends('peserta.layout')

@section('title', 'Aduan Masalah')

@section('content')

    <!-- Header Section -->
    <div class="page-header-card">
        <div class="page-header-content">
            <div class="page-icon">
                <i class="fa-regular fa-comment-dots"></i>
            </div>
            <div>
                <h1>Aduan Masalah & Kendala</h1>
                <p>Saluran resmi bagi peserta untuk melaporkan kendala teknis, lingkungan kerja, K3, atau administrasi secara rahasia dan aman.</p>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-primary" onclick="openAduanModal()">
                <i class="fa-solid fa-plus"></i> Buat Aduan Baru
            </button>
        </div>
    </div>

    <!-- Daftar Aduan -->
    <div style="display: flex; flex-direction: column; gap: 18px;">
        @forelse($complaints as $c)
            <div class="card" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <span style="font-size: 13px; font-weight: 800; color: var(--primary-color);">#{{ $c->complaint_number }}</span>
                            <span class="badge badge-info">{{ match($c->category) {
                                'technical' => 'Kendala Teknis/Sistem',
                                'work_environment' => 'Lingkungan Kerja',
                                'schedule' => 'Jadwal Kerja',
                                'supervision' => 'Proses Pembimbingan',
                                'administration' => 'Administrasi/Dokumen',
                                'safety' => 'Keselamatan Kerja (K3)',
                                'harassment' => 'Perilaku Tidak Pantas',
                                default => 'Lainnya',
                            } }}</span>

                            <span class="badge {{ $c->priority === 'urgent' ? 'badge-danger' : ($c->priority === 'high' ? 'badge-warning' : 'badge-secondary') }}">
                                Prioritas: {{ strtoupper($c->priority) }}
                            </span>
                        </div>
                        <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 6px;">{{ $c->title }}</h2>
                    </div>

                    <div>
                        <span class="badge badge-{{ $c->status }}">
                            {{ match($c->status) {
                                'submitted' => 'Aduan Diterima',
                                'verified' => 'Terverifikasi',
                                'in_progress' => 'Sedang Ditangani',
                                'waiting_for_participant' => 'Menunggu Respon Anda',
                                'resolved' => 'Selesai Ditangani',
                                'closed' => 'Aduan Ditutup',
                                'rejected' => 'Ditolak',
                                default => $c->status,
                            } }}
                        </span>
                    </div>
                </div>

                <p style="font-size: 13px; color: #334155; line-height: 1.5; margin-bottom: 14px;">
                    {{ $c->description }}
                </p>

                @if($c->attachment_path)
                    <div style="margin-bottom: 14px;">
                        <a href="{{ asset($c->attachment_path) }}" target="_blank" style="font-size: 12px; color: var(--primary-color); font-weight: 600; text-decoration: none;">
                            <i class="fa-solid fa-paperclip"></i> Lihat Bukti Lampiran Aduan
                        </a>
                    </div>
                @endif

                <!-- Timeline Tanggapan -->
                <div style="background: #f8fafc; border-radius: 10px; padding: 14px; margin-top: 10px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-regular fa-comments"></i> Riwayat Tanggapan & Penanganan ({{ $c->responses->where('is_internal_note', false)->count() }})
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @forelse($c->responses->where('is_internal_note', false) as $resp)
                            <div style="background: #ffffff; padding: 10px 14px; border-radius: 8px; border-left: 3px solid {{ $resp->user_id === Auth::id() ? '#0284c7' : 'var(--primary-color)' }};">
                                <div style="display: flex; justify-content: space-between; font-size: 11px; color: #64748b; margin-bottom: 3px;">
                                    <strong>{{ $resp->user->nama_lengkap ?? 'Petugas' }} ({{ $resp->user->role ?? 'Petugas' }})</strong>
                                    <span>{{ $resp->created_at->diffForHumans() }}</span>
                                </div>
                                <div style="font-size: 12.5px; color: #1e293b;">
                                    {{ $resp->message }}
                                </div>
                            </div>
                        @empty
                            <div style="font-size: 12px; color: #94a3b8; font-style: italic;">
                                Belum ada tanggapan resmi dari pembimbing atau administrator.
                            </div>
                        @endforelse
                    </div>

                    @if(!in_array($c->status, ['closed', 'resolved']))
                        <!-- Form Balas Tanggapan -->
                        <form action="{{ route('peserta.aduan.reply', $c->id_complaint) }}" method="POST" style="margin-top: 12px; display: flex; gap: 8px;">
                            @csrf
                            <input type="text" name="message" placeholder="Tulis balasan atau informasi tambahan untuk petugas..." required class="form-control" style="font-size: 12.5px;">
                            <button type="submit" class="btn btn-sm btn-primary" style="white-space: nowrap;">
                                <i class="fa-solid fa-paper-plane"></i> Kirim
                            </button>
                        </form>
                    @endif
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px; font-size: 11px; color: #94a3b8;">
                    <span>Akses Kerahasiaan: {{ ucwords(str_replace('_', ' ', $c->confidentiality)) }}</span>
                    <span>Diajukan: {{ $c->created_at->format('d/m/Y H:i') }} WIB</span>
                </div>
            </div>
        @empty
            <div class="card" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                <i class="fa-regular fa-comment-dots" style="font-size: 40px; margin-bottom: 12px;"></i>
                <h3 style="font-size: 16px; color: #475569;">Belum Ada Aduan yang Diajukan</h3>
                <p style="font-size: 13px; margin-top: 4px;">Jika Anda menghadapi kendala teknis atau masalah selama magang, jangan ragu untuk menyampaikan aduan.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 20px;">
        {{ $complaints->links() }}
    </div>

    <!-- ================= MODAL BUAT ADUAN (README SECTION 8) ================= -->
    <div class="modal-overlay" id="aduanModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fa-regular fa-comment-dots" style="color: var(--primary-color);"></i> Buat Aduan Masalah Magang</h3>
                <button type="button" class="modal-close" onclick="closeAduanModal()">&times;</button>
            </div>
            <form action="{{ route('peserta.aduan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ad_cat">Kategori Aduan *</label>
                            <select id="ad_cat" name="category" class="form-select" required>
                                <option value="technical">Kendala Perangkat / Sistem</option>
                                <option value="work_environment">Masalah Lingkungan Kerja</option>
                                <option value="schedule">Masalah Jadwal Kerja</option>
                                <option value="supervision">Masalah Proses Pembimbingan</option>
                                <option value="administration">Masalah Dokumen / Administrasi</option>
                                <option value="safety">Keselamatan Kerja (K3)</option>
                                <option value="harassment">Perilaku Tidak Pantas / Pelecehan</option>
                                <option value="other">Masalah Lainnya</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ad_priority">Tingkat Prioritas *</label>
                            <select id="ad_priority" name="priority" class="form-select" required>
                                <option value="low">Rendah (Tidak mengganggu kegiatan)</option>
                                <option value="medium" selected>Sedang (Perlu penanganan, kegiatan jalan)</option>
                                <option value="high">Tinggi (Menghambat kegiatan magang)</option>
                                <option value="urgent">Mendesak (Butuh perhatian segera / Darurat)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ad_title">Judul Aduan *</label>
                        <input type="text" id="ad_title" name="title" placeholder="Contoh: Akun jaringan lokal tidak dapat mengakses server PLC" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="ad_desc">Deskripsi Masalah Lengkap *</label>
                        <textarea id="ad_desc" name="description" rows="4" class="form-control" placeholder="Jelaskan detail waktu kejadian, kronologi, dampak terhadap pekerjaan Anda..." required minlength="20"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ad_conf">Tingkat Kerahasiaan Akses *</label>
                            <select id="ad_conf" name="confidentiality" class="form-select" required>
                                <option value="visible_to_company">Hanya Pihak Perusahaan (PLTU)</option>
                                <option value="visible_to_campus">Hanya Pihak Kampus / Dosen</option>
                                <option value="visible_to_both" selected>Perusahaan & Kampus</option>
                                <option value="restricted">Akses Terbatas (Hanya Petugas Khusus)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ad_file">Lampiran Bukti (Screenshot / Foto / Dokumen)</label>
                            <input type="file" id="ad_file" name="attachment" accept=".pdf,.docx,.jpg,.jpeg,.png" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeAduanModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Aduan Resmi
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openAduanModal() {
        document.getElementById('aduanModal').classList.add('active');
    }
    function closeAduanModal() {
        document.getElementById('aduanModal').classList.remove('active');
    }
</script>
@endpush
