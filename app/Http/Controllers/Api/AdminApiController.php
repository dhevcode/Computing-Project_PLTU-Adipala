<?php

namespace App\Http\Controllers\Api;

use App\Mail\SertifikatMail;
use App\Mail\SuratBalasanMail;
use App\Models\AppNotification;
use App\Models\AttendancePolicy;
use App\Models\ChatbotFaq;
use App\Models\ChatbotLog;
use App\Models\Company;
use App\Models\CompanyInformation;
use App\Models\Divisi;
use App\Models\EvaluationCriterion;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\Sertifikat;
use App\Models\Universitas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * API Portal Admin untuk aplikasi mobile.
 * Monitoring presensi/logbook/cuti/aduan/nilai memakai logika PembimbingApiController dengan cakupan
 * seluruh mahasiswa dan teks notifikasi web admin (AdminAbsensiController, AdminLogbookController, dst).
 * Fitur lain mengikuti AdminController, DocumentController, SertifikatController, AdminPengumumanController,
 * AdminChatbotController dan AdminProfileController.
 */
class AdminApiController extends PembimbingApiController
{
    // =========================================================================
    // CAKUPAN & TEKS (menimpa versi pembimbing)
    // =========================================================================

    /** Admin memantau seluruh mahasiswa. */
    protected function bimbinganIds(Request $request): array
    {
        return Mahasiswa::pluck('id_mhs')->all();
    }

    protected function authorizeMahasiswa(Request $request, int $mahasiswaId): void
    {
        abort_unless(Mahasiswa::whereKey($mahasiswaId)->exists(), 404, 'Data mahasiswa tidak ditemukan.');
    }

    protected function teks(string $jenis, array $v): array
    {
        return match ($jenis) {
            'koreksi' => [
                'Koreksi Absensi ' . ucfirst($v['status']),
                "Pengajuan koreksi tanggal {$v['tanggal']} telah " . ($v['status'] === 'approved' ? 'disetujui' : 'ditolak') . '. ' . (!empty($v['catatan']) ? "Catatan: {$v['catatan']}" : ''),
            ],
            'catatan_koreksi' => ['Dikoreksi admin: '],
            'logbook' => ["Logbook {$v['label']}", "Logbook Anda untuk tanggal {$v['tanggal']} telah {$v['label']} oleh pembimbing. " . (!empty($v['catatan']) ? "Catatan: {$v['catatan']}" : '')],
            'cuti' => ["Pengajuan Cuti / Izin {$v['label']}", "Pengajuan {$v['jenis']} ({$v['periode']}) telah {$v['label']}. " . (!empty($v['catatan']) ? "Alasan: {$v['catatan']}" : '')],
            'catatan_cuti' => [': '],
            'aduan' => ['Aduan Mendapat Tanggapan', "Aduan #{$v['nomor']} ({$v['judul']}) telah ditanggapi oleh admin/petugas. Status: {$v['status']}"],
            'nilai' => ['Nilai Harian Telah Dipublikasikan', "Penilaian untuk tanggal {$v['tanggal']} telah dipublikasikan dengan Skor: {$v['nilai']}. Silakan periksa di menu Nilai."],
        };
    }

    // =========================================================================
    // FORMAT DATA
    // =========================================================================

    /** URL berkas pendaftaran (nama file di public/uploads/{folder}, atau path lengkap). */
    protected static function berkasUrl(?string $file, string $folder): ?string
    {
        if (!$file) {
            return null;
        }

        return asset(str_starts_with($file, 'uploads/') ? $file : "uploads/{$folder}/{$file}");
    }

    protected function formatPengajuan(PengajuanMagang $p): array
    {
        $m = $p->mahasiswa;

        return [
            'id' => $p->id_pengajuan,
            'mahasiswa' => $m ? $this->formatMahasiswa($m, $p) : null,
            'divisi_id' => $p->divisi_id,
            'divisi' => $p->divisi->nama_divisi ?? null,
            'pembimbing_id' => $p->pembimbing_id,
            'nama_pembimbing' => $p->pembimbing->nama_lengkap ?? $p->nama_pembimbing,
            'tanggal_mulai' => static::date($p->tanggal_mulai),
            'tanggal_selesai' => static::date($p->tanggal_selesai),
            'status' => $p->status,
            'surat_permohonan_url' => static::berkasUrl($p->surat_permohonan, 'surat_permohonan'),
            'proposal_url' => static::berkasUrl($p->proposal, 'proposal'),
            'cv_url' => static::berkasUrl($p->cv, 'cv'),
            // Surat balasan disimpan di disk "public" (perlu php artisan storage:link)
            'surat_balasan_url' => $p->surat_balasan ? asset('storage/' . $p->surat_balasan) : null,
            'catatan_admin' => $p->catatan_admin,
            'alasan_penolakan' => $p->alasan_penolakan,
            'verified_at' => static::dateTime($p->verified_at),
            'verified_by' => static::userSummary($p->verifiedBy),
            'created_at' => static::dateTime($p->created_at),
        ];
    }

    /** Filter daftar pengajuan sama dengan AdminController::data / index / export. */
    protected function filterPengajuan(Request $request, $query): void
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', fn ($q) => $q->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%"));
        }
        foreach (['jurusan_id', 'universitas_id', 'jenis_kelamin'] as $kolom) {
            if ($request->filled($kolom)) {
                $query->whereHas('mahasiswa', fn ($q) => $q->where($kolom, $request->input($kolom)));
            }
        }
        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_mulai', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_selesai', $request->tanggal_selesai);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }
    }

    protected function urutkanPengajuan(Request $request, $query): void
    {
        match ($request->get('sort', 'terbaru')) {
            'terlama' => $query->orderBy('id_pengajuan', 'asc'),
            'nama_asc', 'nama_desc' => $query->join('mahasiswa', 'pengajuan_magang.mahasiswa_id', '=', 'mahasiswa.id_mhs')
                ->orderBy('mahasiswa.nama_lengkap', $request->sort === 'nama_asc' ? 'asc' : 'desc')
                ->select('pengajuan_magang.*'),
            'mulai_asc' => $query->orderBy('tanggal_mulai', 'asc'),
            'mulai_desc' => $query->orderBy('tanggal_mulai', 'desc'),
            'selesai_asc' => $query->orderBy('tanggal_selesai', 'asc'),
            'selesai_desc' => $query->orderBy('tanggal_selesai', 'desc'),
            default => $query->orderBy('id_pengajuan', 'desc'),
        };
    }

    protected static function statusDariTanggal($mulai, $selesai): string
    {
        $today = Carbon::today();
        if ($today->lt(Carbon::parse($mulai))) {
            return 'Akan Magang';
        }

        return $today->between(Carbon::parse($mulai), Carbon::parse($selesai)) ? 'Sedang Magang' : 'Selesai Magang';
    }

    /** Universitas/jurusan/divisi "new" dibuat baru seperti form web. */
    protected function idAtauBaru(Request $request, string $field, string $model, string $kolomNama, string $fieldBaru, int $max): ?int
    {
        $nilai = $request->input($field);
        if ($nilai !== 'new') {
            return $nilai !== null && $nilai !== '' ? (int) $nilai : null;
        }
        $tabel = (new $model())->getTable();
        $request->validate([$fieldBaru => "required|string|max:{$max}|unique:{$tabel},{$kolomNama}"]);

        return $model::create([$kolomNama => trim($request->input($fieldBaru))])->getKey();
    }

    protected function simpanBerkasPendaftaran(Request $request, string $field, string $folder, string $sisipan): ?string
    {
        if (!$request->hasFile($field)) {
            return null;
        }
        $file = $request->file($field);
        $nama = time() . $sisipan . static::namaAman($file);
        $target = public_path("uploads/{$folder}");
        if (!is_dir($target)) {
            mkdir($target, 0755, true);
        }
        $file->move($target, $nama);

        return $nama;
    }

    protected function cariPembimbing(Request $request): ?User
    {
        return User::whereIn('role', ['Pembimbing', 'Pembimbing Lapangan'])
            ->where(function ($q) use ($request) {
                if ($request->filled('pembimbing_id')) {
                    $q->where('id_users', $request->pembimbing_id);
                } else {
                    $q->whereRaw('LOWER(TRIM(nama_lengkap)) = ?', [strtolower(trim((string) $request->nama_pembimbing))]);
                }
            })->first();
    }

    // =========================================================================
    // DASHBOARD & REFERENSI
    // =========================================================================

    /** GET /api/admin/dashboard — statistik & grafik dashboard admin web (filter sama). */
    public function dashboard(Request $request)
    {
        $today = Carbon::today();
        $pengajuanQuery = PengajuanMagang::query();
        $mahasiswaQuery = Mahasiswa::query();
        $this->filterPengajuan($request, $pengajuanQuery);
        if ($request->filled('search')) {
            $s = $request->search;
            $mahasiswaQuery->where(fn ($q) => $q->where('nama_lengkap', 'like', "%{$s}%")->orWhere('nim', 'like', "%{$s}%"));
        }
        foreach (['jurusan_id', 'universitas_id', 'jenis_kelamin'] as $kolom) {
            if ($request->filled($kolom)) {
                $mahasiswaQuery->where($kolom, $request->input($kolom));
            }
        }

        $aktif = fn () => (clone $pengajuanQuery)->whereDate('tanggal_mulai', '<=', $today)->whereDate('tanggal_selesai', '>=', $today);

        $chartDivisi = (clone $pengajuanQuery)
            ->join('divisi', 'pengajuan_magang.divisi_id', '=', 'divisi.id_divisi')
            ->select('divisi.nama_divisi', DB::raw('COUNT(DISTINCT pengajuan_magang.mahasiswa_id) as total'))
            ->groupBy('divisi.id_divisi', 'divisi.nama_divisi')
            ->orderBy('divisi.nama_divisi')
            ->get();

        $chartUniv = (clone $pengajuanQuery)
            ->join('mahasiswa', 'pengajuan_magang.mahasiswa_id', '=', 'mahasiswa.id_mhs')
            ->join('universitas', 'mahasiswa.universitas_id', '=', 'universitas.id_universitas')
            ->select('universitas.nama_universitas', DB::raw('COUNT(DISTINCT pengajuan_magang.mahasiswa_id) as total'))
            ->groupBy('universitas.id_universitas', 'universitas.nama_universitas')
            ->orderByDesc('total')
            ->get();

        return $this->ok([
            'summary' => [
                'magang_hari_ini' => $aktif()->count(),
                'total_mahasiswa' => (clone $mahasiswaQuery)->count(),
                'belum_selesai' => (clone $pengajuanQuery)->whereDate('tanggal_selesai', '>=', $today)->count(),
                'akan_datang' => (clone $pengajuanQuery)->whereDate('tanggal_mulai', '>', $today)->count(),
                'divisi_terlibat' => $aktif()->distinct('divisi_id')->count('divisi_id'),
                'menunggu_verifikasi' => PengajuanMagang::where('status', 'Menunggu Verifikasi')->count(),
                'selesai' => (clone $pengajuanQuery)->whereDate('tanggal_selesai', '<', $today)->count(),
            ],
            'chart_divisi' => $chartDivisi->map(fn ($r) => ['label' => $r->nama_divisi, 'total' => (int) $r->total])->values(),
            'chart_universitas' => $chartUniv->map(fn ($r) => ['label' => $r->nama_universitas, 'total' => (int) $r->total])->values(),
            'pengajuan_terbaru' => (clone $pengajuanQuery)->with(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi', 'pembimbing'])
                ->latest()->limit(10)->get()->map(fn ($p) => $this->formatPengajuan($p))->values(),
        ]);
    }

    /** GET /api/admin/referensi — pilihan dropdown form & filter. */
    public function references(Request $request)
    {
        return $this->ok([
            'divisi' => Divisi::orderBy('nama_divisi')->get()->map(fn ($d) => ['id' => $d->id_divisi, 'nama' => $d->nama_divisi])->values(),
            'jurusan' => Jurusan::orderBy('nama_jurusan')->get()->map(fn ($j) => ['id' => $j->id_jurusan, 'nama' => $j->nama_jurusan])->values(),
            'universitas' => Universitas::orderBy('nama_universitas')->get()->map(fn ($u) => ['id' => $u->id_universitas, 'nama' => $u->nama_universitas])->values(),
            'pembimbing' => User::whereIn('role', ['Pembimbing', 'Pembimbing Lapangan'])->orderBy('nama_lengkap')->get()
                ->map(fn ($u) => ['id' => $u->id_users, 'nama' => $u->nama_lengkap, 'is_active' => (bool) $u->is_active])->values(),
            'mahasiswa' => Mahasiswa::with('universitas')->orderBy('nama_lengkap')->get()
                ->map(fn ($m) => ['id' => $m->id_mhs, 'nim' => $m->nim, 'nama_lengkap' => $m->nama_lengkap, 'universitas' => $m->universitas->nama_universitas ?? null])->values(),
            'criteria' => EvaluationCriterion::where('is_active', true)->get()->map(fn ($c) => [
                'id' => $c->id_criterion,
                'name' => $c->criterion_name,
                'description' => $c->description,
                'weight' => (int) $c->weight,
                'maximum_score' => (int) $c->maximum_score,
            ])->values(),
            'status_pengajuan' => ['Menunggu Verifikasi', 'Akan Magang', 'Sedang Magang', 'Selesai Magang', 'Ditolak'],
            'information_categories' => static::options([
                'general' => 'Umum',
                'work_rule' => 'Tata Tertib',
                'safety' => 'Prosedur K3',
                'announcement' => 'Pengumuman',
                'contact' => 'Kontak Penting',
                'document' => 'Dokumen & Panduan',
            ]),
            'logbook_status' => static::options(self::LOGBOOK_STATUS),
            'leave_status' => static::options(self::LEAVE_STATUS),
            'leave_types' => static::options(self::LEAVE_TYPES),
            'complaint_status' => static::options(self::COMPLAINT_STATUS),
            'complaint_categories' => static::options(self::COMPLAINT_CATEGORIES),
            'complaint_priorities' => static::options(self::COMPLAINT_PRIORITIES),
            'attendance_status' => static::options(self::ATTENDANCE_STATUS),
        ]);
    }

    // =========================================================================
    // DATA MAHASISWA / PENGAJUAN MAGANG
    // =========================================================================

    /** GET /api/admin/mahasiswa?search=&jurusan_id=&universitas_id=&divisi_id=&jenis_kelamin=&status=&tahun=&sort=&page= */
    public function dataMahasiswa(Request $request)
    {
        $query = PengajuanMagang::with(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi', 'pembimbing']);
        $this->filterPengajuan($request, $query);
        $this->urutkanPengajuan($request, $query);

        return $this->paginated(
            $query->paginate($this->perPage($request, 10)),
            fn ($p) => $this->formatPengajuan($p),
            ['menunggu_verifikasi' => PengajuanMagang::where('status', 'Menunggu Verifikasi')->count()]
        );
    }

    /** GET /api/admin/mahasiswa/{id} — id = id_pengajuan (sama dengan halaman detail web). */
    public function detailPengajuan(Request $request, $id)
    {
        $p = PengajuanMagang::with(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi', 'pembimbing', 'verifiedBy'])->findOrFail($id);

        return $this->ok($this->formatPengajuan($p));
    }

    /** POST /api/admin/mahasiswa — tambah mahasiswa & pengajuan (multipart, field sama dengan form web). */
    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nim' => 'required|string|max:30|unique:mahasiswa,nim',
            'email' => 'required|email|max:150',
            'no_telepon' => 'required|string|max:20',
            'universitas_id' => 'required',
            'jurusan_id' => 'required',
            'divisi_id' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nama_pembimbing' => 'required|string|max:150',
            'surat_permohonan' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,txt|max:4096',
            'proposal' => 'nullable|file|mimes:pdf,doc,docx,txt|max:4096',
            'cv' => 'nullable|file|mimes:pdf,doc,docx,txt|max:4096',
        ]);

        $universitasId = $this->idAtauBaru($request, 'universitas_id', Universitas::class, 'nama_universitas', 'nama_universitas_baru', 150);
        $jurusanId = $this->idAtauBaru($request, 'jurusan_id', Jurusan::class, 'nama_jurusan', 'nama_jurusan_baru', 100);
        $divisiId = $this->idAtauBaru($request, 'divisi_id', Divisi::class, 'nama_divisi', 'nama_divisi_baru', 100);

        $mahasiswa = Mahasiswa::create([
            'nim' => $request->nim,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'universitas_id' => $universitasId,
            'jurusan_id' => $jurusanId,
        ]);

        $pembimbing = $this->cariPembimbing($request);

        $pengajuan = PengajuanMagang::create([
            'mahasiswa_id' => $mahasiswa->id_mhs,
            'divisi_id' => $divisiId,
            'nama_pembimbing' => $pembimbing ? $pembimbing->nama_lengkap : $request->nama_pembimbing,
            'pembimbing_id' => $pembimbing?->id_users,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => static::statusDariTanggal($request->tanggal_mulai, $request->tanggal_selesai),
            'surat_permohonan' => $this->simpanBerkasPendaftaran($request, 'surat_permohonan', 'surat_permohonan', '_'),
            'proposal' => $this->simpanBerkasPendaftaran($request, 'proposal', 'proposal', '_proposal_'),
            'cv' => $this->simpanBerkasPendaftaran($request, 'cv', 'cv', '_cv_'),
            'created_by' => $request->user()->id_users,
        ]);

        return $this->ok(
            $this->formatPengajuan($pengajuan->load(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi', 'pembimbing'])),
            'Data Mahasiswa Magang berhasil ditambahkan!',
            [],
            201
        );
    }

    /** POST /api/admin/mahasiswa/{id}/update — edit (multipart; berkas lama diganti jika diunggah). */
    public function updateMahasiswa(Request $request, $id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        $mahasiswa = $pengajuan->mahasiswa;
        abort_if(!$mahasiswa, 404, 'Data mahasiswa tidak ditemukan.');

        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nim' => 'required|string|max:30|unique:mahasiswa,nim,' . $mahasiswa->id_mhs . ',id_mhs',
            'email' => 'required|email|max:150',
            'no_telepon' => 'required|string|max:20',
            'universitas_id' => 'required',
            'jurusan_id' => 'required',
            'divisi_id' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nama_pembimbing' => 'nullable|string|max:150',
            'surat_permohonan' => 'nullable|file|mimes:pdf,doc,docx,txt|max:4096',
            'proposal' => 'nullable|file|mimes:pdf,doc,docx,txt|max:4096',
            'cv' => 'nullable|file|mimes:pdf,doc,docx,txt|max:4096',
        ]);

        $universitasId = $this->idAtauBaru($request, 'universitas_id', Universitas::class, 'nama_universitas', 'nama_universitas_baru', 150);
        $jurusanId = $this->idAtauBaru($request, 'jurusan_id', Jurusan::class, 'nama_jurusan', 'nama_jurusan_baru', 100);
        $divisiId = $this->idAtauBaru($request, 'divisi_id', Divisi::class, 'nama_divisi', 'nama_divisi_baru', 100);

        $mahasiswa->update([
            'nim' => $request->nim,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'universitas_id' => $universitasId,
            'jurusan_id' => $jurusanId,
        ]);

        // Berkas baru menggantikan berkas lama (berkas lama dihapus seperti di web)
        $berkas = [];
        foreach (['surat_permohonan' => '_', 'proposal' => '_proposal_', 'cv' => '_cv_'] as $field => $sisipan) {
            $baru = $this->simpanBerkasPendaftaran($request, $field, $field, $sisipan);
            if ($baru) {
                $lama = $pengajuan->{$field};
                if ($lama && file_exists(public_path("uploads/{$field}/{$lama}"))) {
                    @unlink(public_path("uploads/{$field}/{$lama}"));
                }
                $berkas[$field] = $baru;
            }
        }

        $pembimbing = ($request->filled('pembimbing_id') || $request->filled('nama_pembimbing')) ? $this->cariPembimbing($request) : null;

        $pengajuan->update(array_merge([
            'divisi_id' => $divisiId,
            'nama_pembimbing' => $pembimbing ? $pembimbing->nama_lengkap : $request->nama_pembimbing,
            'pembimbing_id' => $pembimbing ? $pembimbing->id_users : $pengajuan->pembimbing_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => static::statusDariTanggal($request->tanggal_mulai, $request->tanggal_selesai),
        ], $berkas));

        return $this->ok(
            $this->formatPengajuan($pengajuan->fresh(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi', 'pembimbing'])),
            'Data Mahasiswa Magang berhasil diperbarui!'
        );
    }

    /** DELETE /api/admin/mahasiswa/{id} */
    public function destroyMahasiswa(Request $request, $id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        $mahasiswa = $pengajuan->mahasiswa;

        if ($pengajuan->surat_permohonan && file_exists(public_path('uploads/surat_permohonan/' . $pengajuan->surat_permohonan))) {
            @unlink(public_path('uploads/surat_permohonan/' . $pengajuan->surat_permohonan));
        }
        $pengajuan->delete();
        if ($mahasiswa && $mahasiswa->pengajuanMagang()->count() === 0) {
            $mahasiswa->delete();
        }

        return $this->ok(null, 'Data Mahasiswa Magang berhasil dihapus!');
    }

    /** POST /api/admin/pengajuan/{id}/terima {pembimbing_id, divisi_id, tanggal_mulai, tanggal_selesai, catatan_admin} */
    public function terimaPengajuan(Request $request, $id)
    {
        $request->validate([
            'pembimbing_id' => 'required|exists:users,id_users',
            'divisi_id' => 'required|exists:divisi,id_divisi',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        $pengajuan = PengajuanMagang::with(['mahasiswa', 'divisi'])->findOrFail($id);
        $pembimbing = User::whereIn('role', ['Pembimbing', 'Pembimbing Lapangan'])->where('id_users', $request->pembimbing_id)->first();
        if (!$pembimbing) {
            return $this->fail('Pembimbing Lapangan yang dipilih tidak valid.', 422);
        }

        $pengajuan->update([
            'pembimbing_id' => $pembimbing->id_users,
            'nama_pembimbing' => $pembimbing->nama_lengkap,
            'divisi_id' => $request->divisi_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => static::statusDariTanggal($request->tanggal_mulai, $request->tanggal_selesai),
            'catatan_admin' => $request->catatan_admin,
            'alasan_penolakan' => null,
            'verified_at' => now(),
            'verified_by' => $request->user()->id_users,
        ]);

        try {
            AppNotification::send(
                $pembimbing->id_users,
                'Ploting Bimbingan Magang Baru',
                'Anda telah ditugaskan sebagai Pembimbing Lapangan untuk peserta ' . ($pengajuan->mahasiswa->nama_lengkap ?? 'Peserta Magang')
                    . ' (' . ($pengajuan->mahasiswa->nim ?? '-') . ') pada divisi ' . ($pengajuan->fresh()->divisi->nama_divisi ?? 'Divisi Terkait') . '.',
                'success',
                route('pembimbing.dashboard')
            );
        } catch (Throwable $e) {
            // Abaikan kegagalan notifikasi agar transaksi tetap sukses (sama dengan web)
        }

        $nama = $pengajuan->mahasiswa->nama_lengkap ?? 'Peserta';

        return $this->ok(
            $this->formatPengajuan($pengajuan->fresh(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi', 'pembimbing', 'verifiedBy'])),
            "Pendaftaran atas nama {$nama} BERHASIL DITERIMA! Pembimbing Lapangan telah diplot ke {$pembimbing->nama_lengkap}."
        );
    }

    /** POST /api/admin/pengajuan/{id}/tolak {alasan_penolakan} */
    public function tolakPengajuan(Request $request, $id)
    {
        $request->validate(['alasan_penolakan' => 'required|string|max:1000']);

        $pengajuan = PengajuanMagang::with('mahasiswa')->findOrFail($id);
        $pengajuan->update([
            'status' => 'Ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'verified_at' => now(),
            'verified_by' => $request->user()->id_users,
        ]);

        return $this->ok(
            $this->formatPengajuan($pengajuan->fresh(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi', 'pembimbing', 'verifiedBy'])),
            'Pendaftaran atas nama ' . ($pengajuan->mahasiswa->nama_lengkap ?? 'Peserta') . ' telah DITOLAK.'
        );
    }

    // =========================================================================
    // DOKUMEN SURAT BALASAN
    // =========================================================================

    /** GET /api/admin/dokumen?status_verifikasi=verified|not_verified (+ filter data mahasiswa) */
    public function dokumen(Request $request)
    {
        $query = PengajuanMagang::with(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi', 'pembimbing']);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', fn ($q) => $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
            $request->request->remove('search');
        }
        $this->filterPengajuan($request, $query);
        if ($request->status_verifikasi === 'verified') {
            $query->whereNotNull('surat_balasan');
        } elseif ($request->status_verifikasi === 'not_verified') {
            $query->whereNull('surat_balasan');
        }
        $this->urutkanPengajuan($request, $query);

        return $this->paginated(
            $query->paginate($this->perPage($request, 10)),
            fn ($p) => $this->formatPengajuan($p),
            [
                'verified' => PengajuanMagang::whereNotNull('surat_balasan')->count(),
                'not_verified' => PengajuanMagang::whereNull('surat_balasan')->count(),
            ]
        );
    }

    /** POST /api/admin/dokumen/{id}/upload {surat_balasan: PDF} — simpan & kirim email (dibatalkan jika email gagal, seperti web). */
    public function uploadSuratBalasan(Request $request, $id)
    {
        $request->validate(['surat_balasan' => 'required|file|mimes:pdf|max:4096']);

        $pengajuan = PengajuanMagang::with('mahasiswa')->findOrFail($id);
        if (!$pengajuan->mahasiswa || !$pengajuan->mahasiswa->email) {
            return $this->fail('Email mahasiswa belum tersedia.', 422);
        }

        $path = $request->file('surat_balasan')->storeAs(
            'uploads/surat_balasan',
            'surat_balasan_' . $pengajuan->mahasiswa_id . '_' . time() . '.pdf',
            'public'
        );
        $oldPath = $pengajuan->surat_balasan;
        $pengajuan->update(['surat_balasan' => $path]);

        try {
            Mail::to($pengajuan->mahasiswa->email)->send(new SuratBalasanMail($pengajuan, $path));
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        } catch (Throwable $e) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $pengajuan->update(['surat_balasan' => $oldPath]);

            return $this->fail('File gagal dikirim ke email mahasiswa. Silakan coba lagi.', 502);
        }

        return $this->ok(
            $this->formatPengajuan($pengajuan->fresh(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi', 'pembimbing'])),
            'Surat balasan berhasil diupload dan dikirim ke email mahasiswa.'
        );
    }

    // =========================================================================
    // SERTIFIKAT, LAPORAN & ID CARD
    // =========================================================================

    protected function formatSertifikatMahasiswa(Mahasiswa $m): array
    {
        $p = $m->pengajuanMagang->sortByDesc('id_pengajuan')->first();
        $s = $m->sertifikat instanceof \Illuminate\Support\Collection ? $m->sertifikat->sortByDesc('id_sertifikat')->first() : $m->sertifikat;

        return $this->formatMahasiswa($m, $p) + [
            'file_laporan_url' => $m->file_laporan ? asset($m->file_laporan) : null,
            'id_card_verified' => (bool) $m->id_card_verified,
            'sertifikat' => $s ? [
                'id' => $s->id_sertifikat,
                'status' => $s->status,
                'file_url' => $s->file_sertifikat ? asset($s->file_sertifikat) : null,
                'tanggal_terbit' => static::date($s->tanggal_terbit),
                'uploaded_at' => static::dateTime($s->uploaded_at),
            ] : null,
            'syarat_terpenuhi' => $m->file_laporan && $m->id_card_verified,
        ];
    }

    /** GET /api/admin/sertifikat — hanya mahasiswa yang sudah selesai magang (sama dengan web). */
    public function sertifikat(Request $request)
    {
        $query = Mahasiswa::with(['sertifikat', 'pengajuanMagang.divisi', 'pengajuanMagang.pembimbing', 'universitas', 'jurusan'])
            ->whereHas('pengajuanMagang', fn ($q) => $q->whereDate('tanggal_selesai', '<=', Carbon::today()));

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(fn ($q) => $q->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }
        foreach (['jurusan' => 'jurusan_id', 'universitas' => 'universitas_id', 'jenis_kelamin' => 'jenis_kelamin'] as $param => $kolom) {
            if ($request->filled($param)) {
                $query->where($kolom, $request->input($param));
            }
        }
        if ($request->filled('divisi')) {
            $query->whereHas('pengajuanMagang', fn ($q) => $q->where('divisi_id', $request->divisi));
        }

        return $this->paginated(
            $query->orderBy('nama_lengkap')->paginate($this->perPage($request, 10)),
            fn ($m) => $this->formatSertifikatMahasiswa($m)
        );
    }

    /** POST /api/admin/sertifikat/{id}/laporan {laporan: PDF} — id = id_mhs */
    public function uploadLaporan(Request $request, $id)
    {
        $request->validate(['laporan' => 'required|file|mimes:pdf|max:10240']);
        $mahasiswa = Mahasiswa::findOrFail($id);

        if ($mahasiswa->file_laporan && file_exists(public_path('uploads/laporan_mhs/' . basename($mahasiswa->file_laporan)))) {
            unlink(public_path('uploads/laporan_mhs/' . basename($mahasiswa->file_laporan)));
        }
        $folder = public_path('uploads/laporan_mhs');
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
        $nama = 'laporan_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $mahasiswa->nim) . '_' . time() . '.pdf';
        $request->file('laporan')->move($folder, $nama);
        $mahasiswa->file_laporan = 'uploads/laporan_mhs/' . $nama;
        $mahasiswa->save();

        return $this->ok($this->formatSertifikatMahasiswa($mahasiswa->fresh(['sertifikat', 'pengajuanMagang.divisi', 'universitas', 'jurusan'])), 'Laporan mahasiswa berhasil diupload.');
    }

    /** POST /api/admin/sertifikat/{id}/verify-id-card */
    public function verifyIdCard(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        if (!$mahasiswa->file_laporan) {
            return $this->fail('Mahasiswa harus mengupload laporan terlebih dahulu.', 409);
        }
        $mahasiswa->id_card_verified = true;
        $mahasiswa->save();

        return $this->ok(
            $this->formatSertifikatMahasiswa($mahasiswa->fresh(['sertifikat', 'pengajuanMagang.divisi', 'universitas', 'jurusan'])),
            'ID Card berhasil diverifikasi. Mahasiswa sudah memenuhi persyaratan.'
        );
    }

    /** POST /api/admin/sertifikat/{id}/upload {certificate: pdf/jpg/png} — syarat: laporan & ID card terverifikasi. */
    public function uploadSertifikat(Request $request, $id)
    {
        $request->validate(['certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120']);
        $mahasiswa = Mahasiswa::findOrFail($id);

        if (!$mahasiswa->file_laporan) {
            return $this->fail('Sertifikat belum dapat diupload. Laporan mahasiswa belum tersedia.', 409);
        }
        if (!$mahasiswa->id_card_verified) {
            return $this->fail('Sertifikat belum dapat diupload. ID Card belum diverifikasi.', 409);
        }
        $pengajuan = PengajuanMagang::where('mahasiswa_id', $mahasiswa->id_mhs)->latest('id_pengajuan')->first();
        if (!$pengajuan) {
            return $this->fail('Mahasiswa belum memiliki data pengajuan magang.', 409);
        }

        $sertifikat = Sertifikat::where('mahasiswa_id', $mahasiswa->id_mhs)->latest('id_sertifikat')->first() ?? new Sertifikat(['mahasiswa_id' => $mahasiswa->id_mhs]);
        if ($sertifikat->file_sertifikat && file_exists(public_path('uploads/sertifikat_mhs/' . basename($sertifikat->file_sertifikat)))) {
            unlink(public_path('uploads/sertifikat_mhs/' . basename($sertifikat->file_sertifikat)));
        }
        $folder = public_path('uploads/sertifikat_mhs');
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
        $nama = 'sertifikat_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $mahasiswa->nim) . '_' . time() . '.' . static::ekstensiAman($request->file('certificate'));
        $request->file('certificate')->move($folder, $nama);

        $sertifikat->mahasiswa_id = $mahasiswa->id_mhs;
        $sertifikat->pengajuan_id = $pengajuan->id_pengajuan;
        $sertifikat->file_sertifikat = 'uploads/sertifikat_mhs/' . $nama;
        $sertifikat->status = 'Diterbitkan';
        $sertifikat->tanggal_terbit = now()->toDateString();
        $sertifikat->uploaded_by = $request->user()->id_users;
        $sertifikat->uploaded_at = now();
        $sertifikat->save();

        if ($mahasiswa->email && file_exists(public_path($sertifikat->file_sertifikat))) {
            Mail::to($mahasiswa->email)->send(new SertifikatMail($mahasiswa, public_path($sertifikat->file_sertifikat)));
        }

        return $this->ok(
            $this->formatSertifikatMahasiswa($mahasiswa->fresh(['sertifikat', 'pengajuanMagang.divisi', 'universitas', 'jurusan'])),
            'Sertifikat berhasil diupload.'
        );
    }

    // =========================================================================
    // PENGUMUMAN & PROFIL PERUSAHAAN
    // =========================================================================

    /** Profil perusahaan & kebijakan presensi (bagian atas halaman pengumuman web). */
    protected function companyData(): array
    {
        $company = Company::first();
        $policy = AttendancePolicy::first();

        return [
            'company' => $company ? [
                'name' => $company->name,
                'industry' => $company->industry,
                'description' => $company->description,
                'address' => $company->address,
                'phone' => $company->phone,
                'email' => $company->email,
                'website' => $company->website,
                'latitude' => (float) $company->latitude,
                'longitude' => (float) $company->longitude,
                'attendance_radius_meters' => (int) $company->attendance_radius_meters,
                'work_start_time' => static::time($company->work_start_time),
                'work_end_time' => static::time($company->work_end_time),
            ] : null,
            'policy' => $policy ? [
                'late_tolerance_minutes' => (int) $policy->late_tolerance_minutes,
                'check_in_start' => static::time($policy->check_in_start),
                'check_in_deadline' => static::time($policy->check_in_deadline),
                'check_out_start' => static::time($policy->check_out_start),
                'allowed_radius_meters' => (int) $policy->allowed_radius_meters,
                'require_selfie' => (bool) $policy->require_selfie,
            ] : null,
        ];
    }

    /** GET /api/admin/pengumuman — semua informasi (terbit maupun tidak); meta berisi profil perusahaan & kebijakan presensi. */
    public function pengumuman(Request $request)
    {
        return $this->paginated(
            CompanyInformation::orderBy('created_at', 'desc')->paginate($this->perPage($request, 15)),
            fn ($i) => $this->formatInformation($i),
            $this->companyData()
        );
    }

    /** POST /api/admin/pengumuman/store {title, category, content, attachment?} — kategori pengumuman dikirim ke semua peserta. */
    public function storePengumuman(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'category' => 'required|in:general,work_rule,safety,announcement,contact,document',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,docx,jpg,jpeg,png|max:10240',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $nama = 'info_' . time() . '.' . static::ekstensiAman($file);
            $file->move(public_path('uploads/info'), $nama);
            $attachmentPath = 'uploads/info/' . $nama;
        }

        $info = CompanyInformation::create([
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'attachment_path' => $attachmentPath,
            'is_published' => true,
            'published_at' => Carbon::now(),
            'created_by' => $request->user()->id_users,
        ]);

        if ($request->category === 'announcement') {
            foreach (User::where('role', 'Peserta')->get() as $peserta) {
                AppNotification::send(
                    $peserta->id_users,
                    'Pengumuman Baru: ' . $request->title,
                    substr(strip_tags($request->content), 0, 150) . '...',
                    'info',
                    route('peserta.informasi')
                );
            }
        }

        return $this->ok($this->formatInformation($info), 'Informasi / Pengumuman berhasil disimpan.', [], 201);
    }

    /** DELETE /api/admin/pengumuman/{id} */
    public function destroyPengumuman(Request $request, $id)
    {
        CompanyInformation::findOrFail($id)->delete();

        return $this->ok(null, 'Informasi berhasil dihapus.');
    }

    /** POST /api/admin/pengumuman/company — profil perusahaan & radius presensi (+ sinkron kebijakan presensi, sama dengan web). */
    public function updateCompany(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'industry' => 'required|string|max:100',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'attendance_radius_meters' => 'required|integer|min:10',
            'work_start_time' => 'required',
            'work_end_time' => 'required',
        ]);

        $company = Company::first();
        if (!$company) {
            return $this->fail('Data perusahaan belum tersedia.', 404);
        }

        $company->update($request->only([
            'name', 'industry', 'address', 'latitude', 'longitude',
            'attendance_radius_meters', 'work_start_time', 'work_end_time',
            'phone', 'email', 'website', 'description',
        ]));

        $policy = AttendancePolicy::firstOrCreate(['company_id' => $company->id_company]);
        $mulai = Carbon::createFromTimeString($company->work_start_time);
        $policy->update([
            'check_in_start' => $mulai->copy()->subHours(2)->format('H:i:s'),
            'check_in_deadline' => $mulai->copy()->addMinutes($policy->late_tolerance_minutes ?: 15)->format('H:i:s'),
            'check_out_start' => $company->work_end_time,
            'allowed_radius_meters' => $company->attendance_radius_meters,
        ]);

        return $this->ok($this->companyData(), 'Data profil perusahaan dan radius absensi berhasil diperbarui.');
    }

    // =========================================================================
    // CHATBOT FAQ
    // =========================================================================

    protected function formatFaq(ChatbotFaq $f): array
    {
        return [
            'id' => $f->id_faq,
            'kode' => $f->kode,
            'pertanyaan' => $f->pertanyaan,
            'jawaban' => $f->jawaban,
            'kategori' => $f->kategori,
            'role_access' => $f->role_access,
            'role_label' => $f->role_label,
            'urutan' => (int) $f->urutan,
            'is_active' => (bool) $f->is_active,
            'click_count' => (int) $f->click_count,
        ];
    }

    /** GET /api/admin/chatbot?search=&role_access=&status=active|inactive — FAQ + statistik. */
    public function chatbot(Request $request)
    {
        $query = ChatbotFaq::query();
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('pertanyaan', 'like', "%{$s}%")->orWhere('jawaban', 'like', "%{$s}%")->orWhere('kode', 'like', "%{$s}%"));
        }
        if ($request->filled('role_access') && $request->role_access !== 'all_filter') {
            $query->where('role_access', $request->role_access);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        return $this->paginated(
            $query->orderBy('urutan')->orderBy('id_faq')->paginate($this->perPage($request, 15)),
            fn ($f) => $this->formatFaq($f),
            [
                'total_faq' => ChatbotFaq::count(),
                'total_faq_aktif' => ChatbotFaq::where('is_active', true)->count(),
                'total_interaksi' => ChatbotLog::count(),
                'interaksi_guest' => ChatbotLog::where('user_type', 'guest')->count(),
                'interaksi_student' => ChatbotLog::where('user_type', 'student')->count(),
                'top_faq' => ChatbotFaq::orderByDesc('click_count')->take(5)->get()->map(fn ($f) => $this->formatFaq($f))->values(),
            ]
        );
    }

    /** GET /api/admin/chatbot/logs?search=&user_type= */
    public function chatbotLogs(Request $request)
    {
        $query = ChatbotLog::with(['faq', 'user']);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('pertanyaan', 'like', "%{$s}%")
                ->orWhere('ip_address', 'like', "%{$s}%")
                ->orWhereHas('user', fn ($u) => $u->where('nama_lengkap', 'like', "%{$s}%")));
        }
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        return $this->paginated(
            $query->orderByDesc('id_log')->paginate($this->perPage($request, 15)),
            fn ($l) => [
                'id' => $l->id_log,
                'pertanyaan' => $l->pertanyaan,
                'kode_faq' => $l->faq->kode ?? null,
                'user' => static::userSummary($l->user),
                'user_type' => $l->user_type,
                'ip_address' => $l->ip_address,
                'created_at' => static::dateTime($l->created_at),
            ]
        );
    }

    protected function validasiFaq(Request $request, ?ChatbotFaq $faq = null): void
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:chatbot_faqs,kode' . ($faq ? ',' . $faq->id_faq . ',id_faq' : ''),
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
            'kategori' => 'nullable|string|max:50',
            'role_access' => 'required|in:all,guest,student',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
    }

    /** POST /api/admin/chatbot */
    public function storeFaq(Request $request)
    {
        $this->validasiFaq($request);

        $faq = ChatbotFaq::create([
            'kode' => strtoupper(trim($request->kode)),
            'pertanyaan' => trim($request->pertanyaan),
            'jawaban' => trim($request->jawaban),
            'kategori' => trim($request->kategori ?: 'Umum'),
            'role_access' => $request->role_access,
            'urutan' => $request->urutan ?: (ChatbotFaq::max('urutan') + 1),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return $this->ok($this->formatFaq($faq), 'FAQ baru berhasil ditambahkan ke database!', [], 201);
    }

    /** PUT /api/admin/chatbot/{id} — is_active tidak dikirim = nonaktif (sama dengan checkbox form web). */
    public function updateFaq(Request $request, $id)
    {
        $faq = ChatbotFaq::findOrFail($id);
        $this->validasiFaq($request, $faq);

        $faq->update([
            'kode' => strtoupper(trim($request->kode)),
            'pertanyaan' => trim($request->pertanyaan),
            'jawaban' => trim($request->jawaban),
            'kategori' => trim($request->kategori ?: 'Umum'),
            'role_access' => $request->role_access,
            'urutan' => $request->urutan ?? $faq->urutan,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : false,
        ]);

        return $this->ok($this->formatFaq($faq), 'FAQ ' . $faq->kode . ' berhasil diperbarui!');
    }

    /** DELETE /api/admin/chatbot/{id} */
    public function destroyFaq(Request $request, $id)
    {
        $faq = ChatbotFaq::findOrFail($id);
        $kode = $faq->kode;
        $faq->delete();

        return $this->ok(null, 'FAQ ' . $kode . ' berhasil dihapus dari sistem!');
    }

    /** POST /api/admin/chatbot/{id}/toggle */
    public function toggleFaq(Request $request, $id)
    {
        $faq = ChatbotFaq::findOrFail($id);
        $faq->is_active = !$faq->is_active;
        $faq->save();

        return $this->ok($this->formatFaq($faq), "Status FAQ {$faq->kode} berhasil " . ($faq->is_active ? 'diaktifkan' : 'dinonaktifkan') . '.');
    }

    /** POST /api/admin/chatbot/clear-logs */
    public function clearChatbotLogs(Request $request)
    {
        ChatbotLog::truncate();

        return $this->ok(null, 'Seluruh riwayat percakapan chatbot berhasil dibersihkan.');
    }

    // =========================================================================
    // PROFIL ADMIN (AdminProfileController)
    // =========================================================================

    /** POST /api/admin/profil/update {nama_lengkap, email} */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id_users . ',id_users',
        ]);
        $user->nama_lengkap = $request->nama_lengkap;
        $user->email = $request->email;
        $user->save();

        return $this->ok($this->profileData($request), 'Informasi profil berhasil diperbarui.');
    }

    /** POST /api/admin/profil/password {current_password, password, password_confirmation} — min 8 (sama dengan web admin). */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = $request->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->fail('Password saat ini (lama) tidak sesuai.', 422, ['errors' => ['current_password' => ['Password saat ini (lama) tidak sesuai.']]]);
        }
        $user->password = Hash::make($request->password);
        $user->save();

        return $this->ok(null, 'Password berhasil diubah.');
    }
}
