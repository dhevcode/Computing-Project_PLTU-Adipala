<?php

namespace App\Http\Controllers\Api;

use App\Mail\PengajuanMagangBaruMail;
use App\Models\AppNotification;
use App\Models\Divisi;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\Universitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Pengajuan magang baru oleh calon peserta dari aplikasi mobile (tanpa akun / tanpa login).
 * Alur & aturan sama dengan formulir web PengajuanMagangPublicController (/pengajuan-magang):
 * status awal "Menunggu Verifikasi", akun dibuat admin setelah diterima, notifikasi ke Admin/Super Admin,
 * dan email pemberitahuan ke Outlook internship (OUTLOOK_INTERNSHIP_EMAIL).
 */
class PendaftaranApiController extends ApiController
{
    /** GET /api/pendaftaran/referensi → pilihan universitas, jurusan, divisi (sama dengan dropdown web). */
    public function references()
    {
        return $this->ok([
            'universitas' => Universitas::orderBy('nama_universitas')->get()
                ->map(fn ($u) => ['id' => $u->id_universitas, 'nama' => $u->nama_universitas])->values(),
            'jurusan' => Jurusan::orderBy('nama_jurusan')->get()
                ->map(fn ($j) => ['id' => $j->id_jurusan, 'nama' => $j->nama_jurusan])->values(),
            'divisi' => Divisi::orderBy('nama_divisi')->get()
                ->map(fn ($d) => ['id' => $d->id_divisi, 'nama' => $d->nama_divisi])->values(),
            'jenis_kelamin' => [
                ['value' => 'Laki-laki', 'label' => 'Laki-laki'],
                ['value' => 'Perempuan', 'label' => 'Perempuan'],
            ],
        ]);
    }

    /**
     * POST /api/pendaftaran (multipart)
     * universitas_id / jurusan_id: id dari referensi, atau "new" + nama_universitas_baru / nama_jurusan_baru
     * (nama yang ternyata sudah ada dipakai ulang, tidak dibuat ganda).
     */
    public function store(Request $request)
    {
        $request->validate([
            // Data Pribadi
            'nama_lengkap' => 'required|string|max:150',
            'nim' => 'required|string|max:30|unique:mahasiswa,nim|unique:users,username',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'email' => 'required|email|max:150|unique:mahasiswa,email|unique:users,email',
            'no_telepon' => 'required|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:20',

            // Akademik & Penempatan
            'universitas_id' => 'required',
            'jurusan_id' => 'required',
            'nama_universitas_baru' => 'required_if:universitas_id,new|nullable|string|max:150',
            'nama_jurusan_baru' => 'required_if:jurusan_id,new|nullable|string|max:100',
            'divisi_id' => 'required|exists:divisi,id_divisi',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',

            // Dokumen Persyaratan
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'proposal' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'surat_permohonan' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM / NIS wajib diisi.',
            'nim.unique' => 'NIM tersebut sudah terdaftar dalam sistem.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'email.required' => 'Alamat email aktif wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'no_telepon.required' => 'Nomor telepon / WhatsApp wajib diisi.',
            'universitas_id.required' => 'Pilih universitas / kampus asal.',
            'jurusan_id.required' => 'Pilih jurusan / program studi.',
            'nama_universitas_baru.required_if' => 'Nama universitas / institusi baru wajib diisi.',
            'nama_jurusan_baru.required_if' => 'Nama program studi / jurusan baru wajib diisi.',
            'divisi_id.required' => 'Pilih divisi penempatan yang dituju.',
            'divisi_id.exists' => 'Divisi yang dipilih tidak valid.',
            'tanggal_mulai.required' => 'Rencana tanggal mulai magang wajib diisi.',
            'tanggal_selesai.required' => 'Rencana tanggal selesai magang wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai magang harus sama atau setelah tanggal mulai.',
            'cv.required' => 'File CV (Curriculum Vitae) wajib diunggah.',
            'cv.mimes' => 'Format berkas CV harus berupa PDF, DOC, atau DOCX.',
            'cv.max' => 'Ukuran berkas CV maksimal 5MB.',
            'proposal.required' => 'File Proposal Magang wajib diunggah.',
            'proposal.mimes' => 'Format Proposal Magang harus berupa PDF, DOC, atau DOCX.',
            'proposal.max' => 'Ukuran berkas Proposal maksimal 5MB.',
            'surat_permohonan.required' => 'Surat Pengantar dari Kampus wajib diunggah.',
            'surat_permohonan.mimes' => 'Format Surat Pengantar harus berupa PDF, DOC, DOCX, JPG, atau PNG.',
            'surat_permohonan.max' => 'Ukuran berkas Surat Pengantar maksimal 5MB.',
        ]);

        // Universitas / jurusan: id yang ada, atau nama baru (dipakai ulang bila namanya sudah ada)
        if ($request->universitas_id !== 'new' && !Universitas::whereKey($request->universitas_id)->exists()) {
            return $this->fail('Universitas yang dipilih tidak valid.', 422, ['errors' => ['universitas_id' => ['Universitas yang dipilih tidak valid.']]]);
        }
        if ($request->jurusan_id !== 'new' && !Jurusan::whereKey($request->jurusan_id)->exists()) {
            return $this->fail('Jurusan yang dipilih tidak valid.', 422, ['errors' => ['jurusan_id' => ['Jurusan yang dipilih tidak valid.']]]);
        }

        // Berkas persyaratan (folder & pola nama sama dengan web; ekstensi dari isi berkas)
        $surat = $this->simpanBerkas($request->file('surat_permohonan'), 'surat_permohonan', 'surat');
        $proposal = $this->simpanBerkas($request->file('proposal'), 'proposal', 'proposal');
        $cv = $this->simpanBerkas($request->file('cv'), 'cv', 'cv');

        $saved = DB::transaction(function () use ($request, $surat, $proposal, $cv) {
            $universitasId = $request->universitas_id === 'new'
                ? $this->universitasDariNama($request->nama_universitas_baru)
                : (int) $request->universitas_id;
            $jurusanId = $request->jurusan_id === 'new'
                ? $this->jurusanDariNama($request->nama_jurusan_baru)
                : (int) $request->jurusan_id;

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

            $pengajuan = PengajuanMagang::create([
                'mahasiswa_id' => $mahasiswa->id_mhs,
                'divisi_id' => $request->divisi_id,
                'nama_pembimbing' => null,
                'pembimbing_id' => null,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => 'Menunggu Verifikasi',
                'surat_permohonan' => $surat,
                'proposal' => $proposal,
                'cv' => $cv,
                'created_by' => null,
            ]);

            // Notifikasi sistem ke Admin & Super Admin (sama dengan web)
            try {
                $divisi = $pengajuan->divisi->nama_divisi ?? 'Divisi Terkait';
                foreach (User::whereIn('role', ['Admin', 'Super Admin'])->get() as $admin) {
                    AppNotification::send(
                        $admin->id_users,
                        'Pengajuan Magang Baru',
                        "{$request->nama_lengkap} ({$request->nim}) telah mengajukan permohonan magang di {$divisi}.",
                        'info',
                        route('admin.detail', $pengajuan->id_pengajuan)
                    );
                }
            } catch (\Exception $e) {
                // Notifikasi gagal tidak membatalkan pengajuan
            }

            return compact('mahasiswa', 'pengajuan');
        });

        // Email pemberitahuan ke Outlook internship (sama dengan web)
        try {
            $outlook = env('OUTLOOK_INTERNSHIP_EMAIL', 'internship.ubpadp@plnindonesiapower.co.id');
            Mail::to($outlook)->send(new PengajuanMagangBaruMail(
                $saved['mahasiswa']->load(['universitas', 'jurusan']),
                $saved['pengajuan']->load(['divisi'])
            ));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email pengajuan magang (API) ke Outlook: ' . $e->getMessage());
        }

        $pengajuan = $saved['pengajuan'];

        return $this->ok([
            'id' => $pengajuan->id_pengajuan,
            'nama_lengkap' => $saved['mahasiswa']->nama_lengkap,
            'nim' => $saved['mahasiswa']->nim,
            'email' => $saved['mahasiswa']->email,
            'divisi' => $pengajuan->divisi->nama_divisi ?? null,
            'tanggal_mulai' => static::date($pengajuan->tanggal_mulai),
            'tanggal_selesai' => static::date($pengajuan->tanggal_selesai),
            'status' => $pengajuan->status,
        ], 'Pengajuan magang atas nama ' . $saved['mahasiswa']->nama_lengkap . ' berhasil dikirim! Berkas Anda akan diverifikasi oleh pihak PLTU. Akun portal peserta magang akan dibuatkan oleh Administrator setelah pengajuan diterima dan diinformasikan melalui email.', [], 201);
    }

    protected function simpanBerkas(UploadedFile $file, string $folder, string $prefix): string
    {
        $dir = public_path('uploads/' . $folder);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $nama = time() . '_' . $prefix . '_' . static::namaAman($file);
        $file->move($dir, $nama);

        return $nama;
    }

    protected function universitasDariNama(string $nama): int
    {
        $nama = trim($nama);
        $ada = Universitas::whereRaw('LOWER(nama_universitas) = ?', [mb_strtolower($nama)])->first();

        return ($ada ?? Universitas::create(['nama_universitas' => $nama]))->id_universitas;
    }

    protected function jurusanDariNama(string $nama): int
    {
        $nama = trim($nama);
        $ada = Jurusan::whereRaw('LOWER(nama_jurusan) = ?', [mb_strtolower($nama)])->first();

        return ($ada ?? Jurusan::create(['nama_jurusan' => $nama]))->id_jurusan;
    }
}
