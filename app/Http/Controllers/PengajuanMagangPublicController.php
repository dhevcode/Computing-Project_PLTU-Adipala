<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\Universitas;
use App\Models\Jurusan;
use App\Models\Divisi;
use App\Models\AppNotification;

class PengajuanMagangPublicController extends Controller
{
    /**
     * Tampilkan formulir pendaftaran / pengajuan magang untuk calon peserta.
     */
    public function create()
    {
        $universitas = Universitas::orderBy('nama_universitas')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        $divisi = Divisi::orderBy('nama_divisi')->get();

        return view('magang.daftar', compact('universitas', 'jurusan', 'divisi'));
    }

    /**
     * Simpan data pengajuan magang calon peserta.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Data Pribadi
            'nama_lengkap'            => 'required|string|max:150',
            'nim'                     => 'required|string|max:30|unique:mahasiswa,nim|unique:users,username',
            'jenis_kelamin'           => 'required|in:Laki-laki,Perempuan',
            'email'                   => 'required|email|max:150|unique:mahasiswa,email|unique:users,email',
            'no_telepon'              => 'required|string|max:20',
            'emergency_contact_name'  => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:20',

            // Akademik & Penempatan
            'universitas_id'          => 'required',
            'jurusan_id'              => 'required',
            'divisi_id'               => 'required|exists:divisi,id_divisi',
            'tanggal_mulai'           => 'required|date',
            'tanggal_selesai'         => 'required|date|after_or_equal:tanggal_mulai',

            // Dokumen Persyaratan
            'cv'                      => 'required|file|mimes:pdf,doc,docx|max:5120',
            'proposal'                => 'required|file|mimes:pdf,doc,docx|max:5120',
            'surat_permohonan'        => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'nama_lengkap.required'    => 'Nama lengkap wajib diisi.',
            'nim.required'             => 'NIM / NIS wajib diisi.',
            'nim.unique'               => 'NIM tersebut sudah terdaftar dalam sistem.',
            'jenis_kelamin.required'   => 'Jenis kelamin wajib dipilih.',
            'email.required'           => 'Alamat email aktif wajib diisi.',
            'email.email'              => 'Format alamat email tidak valid.',
            'email.unique'             => 'Alamat email ini sudah terdaftar.',
            'no_telepon.required'      => 'Nomor telepon / WhatsApp wajib diisi.',
            'universitas_id.required'  => 'Pilih universitas / kampus asal.',
            'jurusan_id.required'      => 'Pilih jurusan / program studi.',
            'divisi_id.required'       => 'Pilih divisi penempatan yang dituju.',
            'divisi_id.exists'         => 'Divisi yang dipilih tidak valid.',
            'tanggal_mulai.required'   => 'Rencana tanggal mulai magang wajib diisi.',
            'tanggal_selesai.required' => 'Rencana tanggal selesai magang wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai magang harus sama atau setelah tanggal mulai.',
            'cv.required'              => 'File CV (Curriculum Vitae) wajib diunggah.',
            'cv.mimes'                 => 'Format berkas CV harus berupa PDF, DOC, atau DOCX.',
            'cv.max'                   => 'Ukuran berkas CV maksimal 5MB.',
            'proposal.required'        => 'File Proposal Magang wajib diunggah.',
            'proposal.mimes'           => 'Format Proposal Magang harus berupa PDF, DOC, atau DOCX.',
            'proposal.max'             => 'Ukuran berkas Proposal maksimal 5MB.',
            'surat_permohonan.required'=> 'Surat Pengantar dari Kampus wajib diunggah.',
            'surat_permohonan.mimes'   => 'Format Surat Pengantar harus berupa PDF, DOC, DOCX, JPG, atau PNG.',
            'surat_permohonan.max'     => 'Ukuran berkas Surat Pengantar maksimal 5MB.',
        ]);

        // 1. Handle Universitas jika baru
        $universitasId = $request->universitas_id;
        if ($universitasId === 'new') {
            $request->validate([
                'nama_universitas_baru' => 'required|string|max:150|unique:universitas,nama_universitas',
            ], [
                'nama_universitas_baru.required' => 'Nama universitas / institusi baru wajib diisi.',
                'nama_universitas_baru.unique'   => 'Nama universitas tersebut sudah ada dalam daftar.',
            ]);
            $univ = Universitas::create(['nama_universitas' => trim($request->nama_universitas_baru)]);
            $universitasId = $univ->id_universitas;
        }

        // 2. Handle Jurusan jika baru
        $jurusanId = $request->jurusan_id;
        if ($jurusanId === 'new') {
            $request->validate([
                'nama_jurusan_baru' => 'required|string|max:100|unique:jurusan,nama_jurusan',
            ], [
                'nama_jurusan_baru.required' => 'Nama program studi / jurusan baru wajib diisi.',
                'nama_jurusan_baru.unique'   => 'Nama jurusan tersebut sudah ada dalam daftar.',
            ]);
            $jur = Jurusan::create(['nama_jurusan' => trim($request->nama_jurusan_baru)]);
            $jurusanId = $jur->id_jurusan;
        }

        // 3. Upload Berkas Persyaratan
        // A. Surat Permohonan / Pengantar
        $suratFileName = null;
        if ($request->hasFile('surat_permohonan')) {
            $file = $request->file('surat_permohonan');
            $cleanName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $suratFileName = time() . '_surat_' . $cleanName;
            $targetDir = public_path('uploads/surat_permohonan');
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            $file->move($targetDir, $suratFileName);
        }

        // B. Proposal Magang
        $proposalFileName = null;
        if ($request->hasFile('proposal')) {
            $file = $request->file('proposal');
            $cleanName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $proposalFileName = time() . '_proposal_' . $cleanName;
            $targetDir = public_path('uploads/proposal');
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            $file->move($targetDir, $proposalFileName);
        }

        // C. CV
        $cvFileName = null;
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $cleanName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $cvFileName = time() . '_cv_' . $cleanName;
            $targetDir = public_path('uploads/cv');
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            $file->move($targetDir, $cvFileName);
        }

        // 4. Status Awal Pengajuan Baru: Menunggu Verifikasi Admin
        $status = 'Menunggu Verifikasi';

        // 5. Simpan Data Mahasiswa & Pengajuan Magang (Akun User akan dibuat oleh Super Admin)
        DB::transaction(function () use (
            $request, $universitasId, $jurusanId, $suratFileName, $proposalFileName, $cvFileName, $status
        ) {
            // A. Simpan data Mahasiswa (user_id dibiarkan null dulu)
            $mahasiswa = Mahasiswa::create([
                'nim'                     => $request->nim,
                'nama_lengkap'            => $request->nama_lengkap,
                'jenis_kelamin'           => $request->jenis_kelamin,
                'email'                   => $request->email,
                'no_telepon'              => $request->no_telepon,
                'emergency_contact_name'  => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'universitas_id'          => $universitasId,
                'jurusan_id'              => $jurusanId,
                'user_id'                 => null,
            ]);

            // B. Buat Pengajuan Magang dengan status 'Menunggu Verifikasi'
            $pengajuan = PengajuanMagang::create([
                'mahasiswa_id'     => $mahasiswa->id_mhs,
                'divisi_id'        => $request->divisi_id,
                'nama_pembimbing'  => null,
                'pembimbing_id'    => null,
                'tanggal_mulai'    => $request->tanggal_mulai,
                'tanggal_selesai'  => $request->tanggal_selesai,
                'status'           => $status,
                'surat_permohonan' => $suratFileName,
                'proposal'         => $proposalFileName,
                'cv'               => $cvFileName,
                'created_by'       => null,
            ]);

            // C. Kirim notifikasi sistem ke para Admin & Super Admin
            try {
                $divisiName = $pengajuan->divisi->nama_divisi ?? 'Divisi Terkait';
                $admins = User::whereIn('role', ['Admin', 'Super Admin'])->get();
                foreach ($admins as $adminUser) {
                    AppNotification::send(
                        $adminUser->id_users,
                        'Pengajuan Magang Baru',
                        "{$request->nama_lengkap} ({$request->nim}) telah mengajukan permohonan magang di {$divisiName}.",
                        'info',
                        route('admin.detail', $pengajuan->id_pengajuan)
                    );
                }
            } catch (\Exception $e) {
                // Jangan hentikan transaksi jika notifikasi gagal
            }
        });

        return redirect()->route('login')->with('success', 'Pengajuan magang atas nama ' . $request->nama_lengkap . ' berhasil dikirim! Berkas Anda akan diverifikasi oleh pihak PLTU. Akun portal peserta magang akan dibuatkan oleh Administrator/Super Admin.');
    }
}
