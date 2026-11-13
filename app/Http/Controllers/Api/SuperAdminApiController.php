<?php

namespace App\Http\Controllers\Api;

use App\Models\Divisi;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\Universitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * API Portal Super Admin untuk aplikasi mobile.
 * Mengikuti SuperAdminController (kelola akun, pembimbing, peserta) dan ProfileSuperAdminController.
 * Mewarisi AdminApiController hanya untuk helper (format pengajuan, opsi "new", unggah berkas, referensi).
 */
class SuperAdminApiController extends AdminApiController
{
    public const ROLES = ['Admin', 'Super Admin', 'Pembimbing', 'Pembimbing Lapangan', 'Peserta'];

    protected function formatAkun(User $u): array
    {
        return [
            'id' => $u->id_users,
            'nama_lengkap' => $u->nama_lengkap,
            'username' => $u->username,
            'email' => $u->email,
            'phone' => $u->phone,
            'role' => $u->role,
            'id_divisi' => $u->id_divisi,
            'divisi' => $u->divisi->nama_divisi ?? null,
            'id_mhs' => $u->id_mhs,
            'mahasiswa' => $u->mahasiswa ? ['id' => $u->mahasiswa->id_mhs, 'nim' => $u->mahasiswa->nim, 'nama_lengkap' => $u->mahasiswa->nama_lengkap] : null,
            'is_active' => (bool) $u->is_active,
            'last_login_at' => static::dateTime($u->last_login_at),
            'created_at' => static::dateTime($u->created_at),
        ];
    }

    protected function formatPeserta(Mahasiswa $m): array
    {
        return $this->formatMahasiswa($m, $m->latestPengajuan) + [
            'akun' => $m->user ? [
                'id' => $m->user->id_users,
                'username' => $m->user->username,
                'email' => $m->user->email,
                'is_active' => (bool) $m->user->is_active,
            ] : null,
            'universitas_id' => $m->universitas_id,
            'jurusan_id' => $m->jurusan_id,
            'divisi_id' => $m->latestPengajuan->divisi_id ?? null,
        ];
    }

    protected function divisiAtauBaru(Request $request): ?int
    {
        if ($request->id_divisi === 'new') {
            $request->validate([
                'nama_divisi_baru' => 'required|string|max:100|unique:divisi,nama_divisi',
            ], [
                'nama_divisi_baru.required' => 'Nama divisi baru wajib diisi.',
                'nama_divisi_baru.unique' => 'Nama divisi tersebut sudah ada.',
            ]);

            return Divisi::create(['nama_divisi' => trim($request->nama_divisi_baru)])->id_divisi;
        }

        return $request->id_divisi ?: null;
    }

    protected static function mahasiswaTanpaAkun()
    {
        return Mahasiswa::whereNull('user_id')->orWhereDoesntHave('user');
    }

    // =========================================================================
    // DASHBOARD & KELOLA AKUN
    // =========================================================================

    /** GET /api/superadmin/dashboard?role=&q= — daftar akun sistem + statistik. */
    public function dashboard(Request $request)
    {
        $query = User::with(['divisi', 'mahasiswa']);
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        if ($request->filled('q')) {
            $kw = $request->q;
            $query->where(fn ($q) => $q->where('nama_lengkap', 'like', "%{$kw}%")->orWhere('username', 'like', "%{$kw}%")->orWhere('email', 'like', "%{$kw}%"));
        }

        return $this->paginated(
            $query->latest('id_users')->paginate($this->perPage($request, 20)),
            fn ($u) => $this->formatAkun($u),
            [
                'total_admin' => User::whereIn('role', ['Admin', 'Super Admin'])->count(),
                'total_pembimbing' => User::whereIn('role', ['Pembimbing', 'Pembimbing Lapangan'])->count(),
                'total_peserta_user' => User::where('role', 'Peserta')->count(),
                'total_mahasiswa' => Mahasiswa::count(),
                'mahasiswa_tanpa_akun' => static::mahasiswaTanpaAkun()->count(),
            ]
        );
    }

    /** GET /api/superadmin/referensi — dropdown form akun & peserta. */
    public function references(Request $request)
    {
        $data = parent::references($request)->getData(true)['data'];

        return $this->ok([
            'roles' => self::ROLES,
            'divisi' => $data['divisi'],
            'jurusan' => $data['jurusan'],
            'universitas' => $data['universitas'],
            'pembimbing' => array_values(array_filter($data['pembimbing'], fn ($p) => $p['is_active'])),
            'mahasiswa_tanpa_akun' => static::mahasiswaTanpaAkun()->orderBy('nama_lengkap')->get()
                ->map(fn ($m) => ['id' => $m->id_mhs, 'nim' => $m->nim, 'nama_lengkap' => $m->nama_lengkap])->values(),
            'mahasiswa' => $data['mahasiswa'],
            'status_magang' => ['Akan Magang', 'Sedang Magang', 'Selesai Magang'],
        ]);
    }

    /** GET /api/superadmin/akun/{id} */
    public function akunDetail(Request $request, $id)
    {
        return $this->ok($this->formatAkun(User::with(['divisi', 'mahasiswa'])->findOrFail($id)));
    }

    /** POST /api/superadmin/akun {nama_lengkap, username, email, password, role, id_divisi|'new', nama_divisi_baru, id_mhs, is_active} */
    public function storeAkun(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:' . implode(',', self::ROLES),
            'id_divisi' => 'nullable',
            'id_mhs' => 'nullable|exists:mahasiswa,id_mhs',
        ]);

        $idDivisi = $this->divisiAtauBaru($request);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_divisi' => $idDivisi,
            'id_mhs' => $request->role === 'Peserta' ? $request->id_mhs : null,
            'role' => $request->role,
            'is_active' => $request->input('is_active', 1),
            'created_by' => $request->user()->id_users,
        ]);

        if ($request->role === 'Peserta' && $request->filled('id_mhs')) {
            Mahasiswa::whereKey($request->id_mhs)->update(['user_id' => $user->id_users]);
        }

        return $this->ok(
            $this->formatAkun($user->load(['divisi', 'mahasiswa'])),
            'Akun ' . $request->role . ' atas nama ' . $request->nama_lengkap . ' berhasil ditambahkan!',
            [],
            201
        );
    }

    /** PUT /api/superadmin/akun/{id} — password kosong = tidak diubah. */
    public function updateAkun(Request $request, $id)
    {
        $akun = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:users,username,' . $akun->id_users . ',id_users',
            'email' => 'required|email|unique:users,email,' . $akun->id_users . ',id_users',
            'password' => 'nullable|min:6',
            'role' => 'required|in:' . implode(',', self::ROLES),
            'id_divisi' => 'nullable',
            'is_active' => 'required|in:0,1',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'email' => $request->email,
            'id_divisi' => $this->divisiAtauBaru($request),
            'role' => $request->role,
            'is_active' => $request->is_active,
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        if ($request->role === 'Peserta' && $request->filled('id_mhs')) {
            $data['id_mhs'] = $request->id_mhs;
            Mahasiswa::where('id_mhs', $request->id_mhs)->update(['user_id' => $akun->id_users]);
        }
        $akun->update($data);

        return $this->ok($this->formatAkun($akun->fresh(['divisi', 'mahasiswa'])), 'Data Akun berhasil diperbarui!');
    }

    /** DELETE /api/superadmin/akun/{id} — akun sendiri tidak boleh dihapus lewat API. */
    public function destroyAkun(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->id_users === $request->user()->id_users) {
            return $this->fail('Akun yang sedang dipakai tidak dapat dihapus.', 409);
        }
        if ($user->id_mhs) {
            Mahasiswa::where('id_mhs', $user->id_mhs)->update(['user_id' => null]);
        }
        $user->tokens()->delete();
        $user->delete();

        return $this->ok(null, 'Data Akun berhasil dihapus!');
    }

    // =========================================================================
    // PEMBIMBING LAPANGAN
    // =========================================================================

    /** GET /api/superadmin/pembimbing — pembimbing + jumlah bimbingan & daftar mahasiswa bimbingan. */
    public function pembimbing(Request $request)
    {
        $pembimbings = User::with('divisi')->whereIn('role', ['Pembimbing', 'Pembimbing Lapangan'])->latest('id_users')->get();

        $data = $pembimbings->map(function ($p) {
            $bimbingan = $p->getBimbinganPengajuan();

            return $this->formatAkun($p) + [
                'total_mahasiswa' => $bimbingan->count(),
                'sedang_magang' => $bimbingan->where('status', 'Sedang Magang')->count(),
                'akan_magang' => $bimbingan->where('status', 'Akan Magang')->count(),
                'selesai_magang' => $bimbingan->where('status', 'Selesai Magang')->count(),
                'bimbingan' => $bimbingan->map(fn ($b) => $this->formatPengajuan($b))->values(),
            ];
        })->values();

        return $this->ok($data, null, [
            'total_pembimbing' => $pembimbings->count(),
            'pembimbing_aktif' => $pembimbings->where('is_active', 1)->count(),
            'total_mahasiswa_dibimbing' => PengajuanMagang::whereNotNull('pembimbing_id')->orWhereNotNull('nama_pembimbing')->count(),
        ]);
    }

    // =========================================================================
    // PESERTA MAGANG
    // =========================================================================

    /** GET /api/superadmin/peserta?divisi_id=&status=&status_akun=ada_akun|belum_akun&q=&page= */
    public function peserta(Request $request)
    {
        $query = Mahasiswa::with(['universitas', 'jurusan', 'user', 'latestPengajuan.divisi', 'latestPengajuan.pembimbing']);

        if ($request->filled('divisi_id') && $request->divisi_id !== 'all') {
            $query->whereHas('pengajuanMagang', fn ($q) => $q->where('divisi_id', $request->divisi_id));
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->whereHas('latestPengajuan', fn ($q) => $q->where('status', $request->status));
        }
        if ($request->status_akun === 'ada_akun') {
            $query->whereNotNull('user_id')->has('user');
        } elseif ($request->status_akun === 'belum_akun') {
            $query->where(fn ($q) => $q->whereNull('user_id')->orWhereDoesntHave('user'));
        }
        if ($request->filled('q')) {
            $kw = $request->q;
            $query->where(fn ($q) => $q->where('nama_lengkap', 'like', "%{$kw}%")->orWhere('nim', 'like', "%{$kw}%")->orWhere('email', 'like', "%{$kw}%"));
        }

        return $this->paginated(
            $query->latest('id_mhs')->paginate($this->perPage($request, 12)),
            fn ($m) => $this->formatPeserta($m),
            [
                'total_peserta' => Mahasiswa::count(),
                'sedang_magang' => PengajuanMagang::where('status', 'Sedang Magang')->count(),
                'akan_magang' => PengajuanMagang::where('status', 'Akan Magang')->count(),
                'belum_punya_akun' => static::mahasiswaTanpaAkun()->count(),
            ]
        );
    }

    /** GET /api/superadmin/peserta/{id} — profil, penempatan, berkas & riwayat ringkas. */
    public function pesertaDetail(Request $request, $id)
    {
        $m = Mahasiswa::with(['universitas', 'jurusan', 'user', 'latestPengajuan.divisi', 'latestPengajuan.pembimbing'])->findOrFail($id);
        $p = $m->latestPengajuan;

        return $this->ok($this->formatPeserta($m) + [
            'pengajuan' => $p ? $this->formatPengajuan($p->load(['mahasiswa.universitas', 'mahasiswa.jurusan'])) : null,
            'statistik' => [
                'total_absensi' => $m->attendances()->count(),
                'hadir' => $m->attendances()->whereIn('check_in_status', ['present', 'late'])->count(),
                'total_logbook' => $m->logbooks()->count(),
                'logbook_disetujui' => $m->logbooks()->where('status', 'approved')->count(),
                'total_cuti' => $m->leaveRequests()->count(),
            ],
            'absensi_terbaru' => $m->attendances()->limit(5)->get()->map(fn ($a) => $this->formatAttendance($a))->values(),
            'logbook_terbaru' => $m->logbooks()->limit(5)->get()->map(fn ($l) => $this->formatLogbook($l))->values(),
        ]);
    }

    protected function statusPeserta(Request $request): string
    {
        return $request->filled('status') ? $request->status : static::statusDariTanggal($request->tanggal_mulai, $request->tanggal_selesai);
    }

    /** POST /api/superadmin/peserta (multipart) — buat_akun=1 membuat akun portal (password_akun, default mhs123). */
    public function storePeserta(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nim' => 'required|string|max:30|unique:mahasiswa,nim',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'email' => 'required|email|max:150|unique:mahasiswa,email',
            'no_telepon' => 'required|string|max:20',
            'universitas_id' => 'required',
            'jurusan_id' => 'required',
            'divisi_id' => 'required|exists:divisi,id_divisi',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'pembimbing_id' => 'nullable',
            'surat_permohonan' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'proposal' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);
        if ($request->boolean('buat_akun')) {
            $request->validate([
                'password_akun' => 'nullable|min:6',
                'nim' => 'unique:users,username',
                'email' => 'unique:users,email',
            ], [
                'nim.unique' => 'NIM sudah dipakai sebagai username akun lain.',
                'email.unique' => 'Email sudah dipakai akun lain.',
            ]);
        }

        $universitasId = $this->idAtauBaru($request, 'universitas_id', Universitas::class, 'nama_universitas', 'nama_universitas_baru', 150);
        $jurusanId = $this->idAtauBaru($request, 'jurusan_id', Jurusan::class, 'nama_jurusan', 'nama_jurusan_baru', 100);
        $surat = $this->simpanBerkasPendaftaran($request, 'surat_permohonan', 'surat_permohonan', '_surat_');
        $proposal = $this->simpanBerkasPendaftaran($request, 'proposal', 'proposal', '_proposal_');
        $cv = $this->simpanBerkasPendaftaran($request, 'cv', 'cv', '_cv_');
        $pembimbing = $request->filled('pembimbing_id') ? User::find($request->pembimbing_id) : null;
        $status = static::statusDariTanggal($request->tanggal_mulai, $request->tanggal_selesai);
        $authId = $request->user()->id_users;

        $mahasiswa = DB::transaction(function () use ($request, $universitasId, $jurusanId, $surat, $proposal, $cv, $pembimbing, $status, $authId) {
            $mahasiswa = Mahasiswa::create([
                'nim' => $request->nim,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'universitas_id' => $universitasId,
                'jurusan_id' => $jurusanId,
                'user_id' => null,
            ]);

            if ($request->boolean('buat_akun')) {
                $user = User::create([
                    'nama_lengkap' => $request->nama_lengkap,
                    'username' => $request->nim,
                    'email' => $request->email,
                    'password' => Hash::make($request->filled('password_akun') ? $request->password_akun : 'mhs123'),
                    'phone' => $request->no_telepon,
                    'role' => 'Peserta',
                    'id_divisi' => $request->divisi_id,
                    'id_mhs' => $mahasiswa->id_mhs,
                    'is_active' => true,
                    'created_by' => $authId,
                ]);
                $mahasiswa->update(['user_id' => $user->id_users]);
            }

            PengajuanMagang::create([
                'mahasiswa_id' => $mahasiswa->id_mhs,
                'divisi_id' => $request->divisi_id,
                'nama_pembimbing' => $pembimbing ? $pembimbing->nama_lengkap : $request->nama_pembimbing,
                'pembimbing_id' => $pembimbing?->id_users,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $status,
                'surat_permohonan' => $surat,
                'proposal' => $proposal,
                'cv' => $cv,
                'created_by' => $authId,
            ]);

            return $mahasiswa;
        });

        return $this->ok(
            $this->formatPeserta($mahasiswa->fresh(['universitas', 'jurusan', 'user', 'latestPengajuan.divisi', 'latestPengajuan.pembimbing'])),
            'Data peserta magang ' . $request->nama_lengkap . ' berhasil ditambahkan!',
            [],
            201
        );
    }

    /** POST /api/superadmin/peserta/{id}/update (multipart) — akun portal ikut disinkronkan. */
    public function updatePeserta(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nim' => 'required|string|max:30|unique:mahasiswa,nim,' . $mahasiswa->id_mhs . ',id_mhs',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'email' => 'required|email|max:150|unique:mahasiswa,email,' . $mahasiswa->id_mhs . ',id_mhs',
            'no_telepon' => 'required|string|max:20',
            'universitas_id' => 'required',
            'jurusan_id' => 'required',
            'divisi_id' => 'required|exists:divisi,id_divisi',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:Akan Magang,Sedang Magang,Selesai Magang',
            'surat_permohonan' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'proposal' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);
        if ($mahasiswa->user) {
            // Akun ikut disinkronkan: cegah bentrok username/email dengan akun lain (web akan gagal SQL di sini)
            $request->validate([
                'nim' => Rule::unique('users', 'username')->ignore($mahasiswa->user->id_users, 'id_users'),
                'email' => Rule::unique('users', 'email')->ignore($mahasiswa->user->id_users, 'id_users'),
            ], [
                'nim.unique' => 'NIM sudah dipakai sebagai username akun lain.',
                'email.unique' => 'Email sudah dipakai akun lain.',
            ]);
        }

        $universitasId = $this->idAtauBaru($request, 'universitas_id', Universitas::class, 'nama_universitas', 'nama_universitas_baru', 150);
        $jurusanId = $this->idAtauBaru($request, 'jurusan_id', Jurusan::class, 'nama_jurusan', 'nama_jurusan_baru', 100);
        $pembimbing = $request->filled('pembimbing_id') ? User::find($request->pembimbing_id) : null;
        $berkas = array_filter([
            'surat_permohonan' => $this->simpanBerkasPendaftaran($request, 'surat_permohonan', 'surat_permohonan', '_surat_'),
            'proposal' => $this->simpanBerkasPendaftaran($request, 'proposal', 'proposal', '_proposal_'),
            'cv' => $this->simpanBerkasPendaftaran($request, 'cv', 'cv', '_cv_'),
        ]);

        DB::transaction(function () use ($mahasiswa, $request, $universitasId, $jurusanId, $pembimbing, $berkas) {
            $mahasiswa->update([
                'nim' => $request->nim,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'universitas_id' => $universitasId,
                'jurusan_id' => $jurusanId,
            ]);

            $mahasiswa->user?->update([
                'nama_lengkap' => $request->nama_lengkap,
                'username' => $request->nim,
                'email' => $request->email,
                'phone' => $request->no_telepon,
                'id_divisi' => $request->divisi_id,
            ]);

            $mahasiswa->latestPengajuan?->update(array_merge([
                'divisi_id' => $request->divisi_id,
                'pembimbing_id' => $pembimbing?->id_users,
                'nama_pembimbing' => $pembimbing ? $pembimbing->nama_lengkap : $request->nama_pembimbing,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $request->status,
            ], $berkas));
        });

        return $this->ok(
            $this->formatPeserta($mahasiswa->fresh(['universitas', 'jurusan', 'user', 'latestPengajuan.divisi', 'latestPengajuan.pembimbing'])),
            'Data peserta magang ' . $mahasiswa->nama_lengkap . ' berhasil diperbarui!'
        );
    }

    /** DELETE /api/superadmin/peserta/{id} — pengajuan & akun portal ikut dihapus. */
    public function destroyPeserta(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $nama = $mahasiswa->nama_lengkap;

        DB::transaction(function () use ($mahasiswa) {
            PengajuanMagang::where('mahasiswa_id', $mahasiswa->id_mhs)->delete();
            $akun = User::where('id_users', $mahasiswa->user_id)->orWhere('id_mhs', $mahasiswa->id_mhs)->get();
            foreach ($akun as $u) {
                $u->tokens()->delete();
                $u->delete();
            }
            $mahasiswa->delete();
        });

        return $this->ok(null, 'Data peserta magang ' . $nama . ' beserta seluruh riwayat terkait berhasil dihapus!');
    }

    /** POST /api/superadmin/peserta/{id}/buat-akun {password?} — default password mhs123. */
    public function buatAkunPeserta(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        if ($mahasiswa->user) {
            return $this->fail('Mahasiswa ini sudah memiliki akun portal: ' . $mahasiswa->user->email, 409);
        }
        $request->validate(['password' => 'nullable|min:6']);

        $password = $request->filled('password') ? $request->password : 'mhs123';
        $username = $mahasiswa->nim;
        $email = $mahasiswa->email;
        if (User::where('username', $username)->exists()) {
            $username .= '_' . rand(10, 99);
        }
        if (User::where('email', $email)->exists()) {
            $email = 'mhs_' . rand(10, 99) . '_' . $email;
        }

        $user = User::create([
            'nama_lengkap' => $mahasiswa->nama_lengkap,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $mahasiswa->no_telepon,
            'role' => 'Peserta',
            'id_divisi' => $mahasiswa->latestPengajuan?->divisi_id,
            'id_mhs' => $mahasiswa->id_mhs,
            'is_active' => true,
            'created_by' => $request->user()->id_users,
        ]);
        $mahasiswa->update(['user_id' => $user->id_users]);

        return $this->ok(
            ['username' => $username, 'email' => $email, 'password' => $password, 'peserta' => $this->formatPeserta($mahasiswa->fresh(['universitas', 'jurusan', 'user', 'latestPengajuan.divisi', 'latestPengajuan.pembimbing']))],
            "Akun portal peserta untuk {$mahasiswa->nama_lengkap} berhasil dibuat! (Username: {$username}, Password: {$password})",
            [],
            201
        );
    }

    /** POST /api/superadmin/peserta/{id}/reset-password {new_password?} — default mhs123. */
    public function resetPasswordPeserta(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $user = $mahasiswa->user;
        if (!$user) {
            return $this->fail('Mahasiswa ini belum memiliki akun user.', 409);
        }
        $request->validate(['new_password' => 'nullable|min:6']);

        $newPass = $request->filled('new_password') ? $request->new_password : 'mhs123';
        $user->update(['password' => Hash::make($newPass)]);

        return $this->ok(['password' => $newPass], "Password akun peserta {$mahasiswa->nama_lengkap} berhasil di-reset menjadi: {$newPass}");
    }

    // =========================================================================
    // PROFIL SUPER ADMIN (ProfileSuperAdminController)
    // =========================================================================

    /** POST /api/superadmin/profil/update {nama_lengkap, username, email, current_password?, password?, password_confirmation?} */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'username' => ['required', 'string', 'max:150', Rule::unique('users', 'username')->ignore($user->id_users, 'id_users')],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id_users, 'id_users')],
            'current_password' => 'nullable|string|required_with:password',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'username.unique' => 'Username tersebut sudah digunakan.',
            'email.unique' => 'Email tersebut sudah digunakan.',
            'current_password.required_with' => 'Password lama wajib diisi jika ingin mengganti password.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if ($request->filled('password') && !Hash::check($request->current_password, $user->password)) {
            return $this->fail('Password lama tidak sesuai.', 422, ['errors' => ['current_password' => ['Password lama tidak sesuai.']]]);
        }

        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return $this->ok($this->profileData($request), 'Profil berhasil diperbarui.');
    }
}
