<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuratBalasanMail;
use App\Models\PengajuanMagang;
use App\Models\Universitas;
use App\Models\Jurusan;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Throwable;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanMagang::with([
            'mahasiswa.universitas',
            'mahasiswa.jurusan',
            'divisi'
        ]);

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // TANGGAL MULAI
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate(
                'tanggal_mulai',
                $request->tanggal_mulai
            );
        }

        // TANGGAL SELESAI
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate(
                'tanggal_selesai',
                $request->tanggal_selesai
            );
        }

        // JURUSAN
        if ($request->filled('jurusan_id')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }

        // UNIVERSITAS
        if ($request->filled('universitas_id')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('universitas_id', $request->universitas_id);
            });
        }

        // DIVISI
        if ($request->filled('divisi_id')) {
            $query->where(
                'divisi_id',
                $request->divisi_id
            );
        }

        // JENIS KELAMIN
        if ($request->filled('jenis_kelamin')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where(
                    'jenis_kelamin',
                    $request->jenis_kelamin
                );
            });
        }

        // STATUS VERIFIKASI
        if ($request->status_verifikasi === 'verified') {
            $query->whereNotNull('surat_balasan');
        }

        if ($request->status_verifikasi === 'not_verified') {
            $query->whereNull('surat_balasan');
        }

        // DATA MAHASISWA
        $pengajuan = $query
            ->latest('id_pengajuan')
            ->get();

        // STATISTIK
        $verified = PengajuanMagang::whereNotNull(
            'surat_balasan'
        )->count();

        $notVerified = PengajuanMagang::whereNull(
            'surat_balasan'
        )->count();

        $total = $pengajuan->count();

        // DATA FILTER
        $universitas = Universitas::orderBy(
            'nama_universitas'
        )->get();

        $jurusan = Jurusan::orderBy(
            'nama_jurusan'
        )->get();

        $divisi = Divisi::orderBy(
            'nama_divisi'
        )->get();

        return view('admin.dokumen', compact(
            'pengajuan',
            'verified',
            'notVerified',
            'total',
            'universitas',
            'jurusan',
            'divisi'
        ));
    }


    // UPLOAD SURAT BALASAN + KIRIM EMAIL
public function uploadSuratBalasan(Request $request, $id)
{
    $request->validate([
        'surat_balasan' => [
            'required',
            'file',
            'mimes:pdf',
            'max:4096',
        ],
    ], [
        'surat_balasan.required' => 'Silakan pilih file surat balasan.',
        'surat_balasan.file' => 'File yang dipilih tidak valid.',
        'surat_balasan.mimes' => 'Surat balasan harus berupa file PDF.',
        'surat_balasan.max' => 'Ukuran file maksimal 4 MB.',
    ]);

    $pengajuan = PengajuanMagang::with('mahasiswa')
        ->findOrFail($id);

    // Pastikan mahasiswa memiliki email
    if (!$pengajuan->mahasiswa || !$pengajuan->mahasiswa->email) {
        return redirect()
            ->route('admin.dokumen')
            ->with('error', 'Email mahasiswa belum tersedia.');
    }

    $file = $request->file('surat_balasan');

    /*
    |--------------------------------------------------------------------------
    | SIMPAN FILE BARU
    |--------------------------------------------------------------------------
    */

    $filename =
        'surat_balasan_' .
        $pengajuan->mahasiswa_id .
        '_' .
        time() .
        '.pdf';

    $path = $file->storeAs(
        'uploads/surat_balasan',
        $filename,
        'public'
    );

    // Simpan path lama jika sebelumnya sudah ada
    $oldPath = $pengajuan->surat_balasan;

    /*
    |--------------------------------------------------------------------------
    | UPDATE DATABASE
    |--------------------------------------------------------------------------
    */

    $pengajuan->update([
        'surat_balasan' => $path,
    ]);

    /*
    |--------------------------------------------------------------------------
    | KIRIM EMAIL
    |--------------------------------------------------------------------------
    */

    try {

        Mail::to($pengajuan->mahasiswa->email)
            ->send(
                new SuratBalasanMail(
                    $pengajuan,
                    $path
                )
            );

        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE LAMA JIKA EMAIL BERHASIL
        |--------------------------------------------------------------------------
        */

        if (
            $oldPath &&
            Storage::disk('public')->exists($oldPath)
        ) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()
            ->route('admin.dokumen')
            ->with(
                'success',
                'Surat balasan berhasil diupload dan dikirim ke email mahasiswa.'
            );

    } catch (Throwable $e) {

    \Log::error('Gagal mengirim email surat balasan', [
    'message' => $e->getMessage(),
    'file' => $e->getFile(),
    'line' => $e->getLine(),
]);

        /*
        |--------------------------------------------------------------------------
        | JIKA EMAIL GAGAL
        |--------------------------------------------------------------------------
        */

        // Hapus file baru
        if (
            $path &&
            Storage::disk('public')->exists($path)
        ) {
            Storage::disk('public')->delete($path);
        }

        // Kembalikan path database ke file lama
        $pengajuan->update([
            'surat_balasan' => $oldPath,
        ]);

        return redirect()
            ->route('admin.dokumen')
            ->with(
                'error',
                'File gagal dikirim ke email mahasiswa. Silakan coba lagi.'
            );
    }
}

    // // UPLOAD SURAT BALASAN
    // public function uploadSuratBalasan(Request $request, $id)
    // {
    //     $request->validate([
    //         'surat_balasan' => [
    //             'required',
    //             'file',
    //             'mimes:pdf,doc,docx',
    //             'max:4096',
    //         ],
    //     ]);

    //     $pengajuan = PengajuanMagang::findOrFail($id);

    //     $file = $request->file('surat_balasan');

    //     $filename =
    //         'surat_balasan_' .
    //         $pengajuan->mahasiswa_id .
    //         '_' .
    //         time() .
    //         '.' .
    //         $file->getClientOriginalExtension();

    //     $path = $file->storeAs(
    //         'uploads/surat_balasan',
    //         $filename,
    //         'public'
    //     );

    //     $pengajuan->update([
    //         'surat_balasan' => $path
    //     ]);

    //     return redirect()
    //         ->route('admin.dokumen')
    //         ->with(
    //             'success',
    //             'Surat balasan berhasil diupload.'
    //         );
    // }
    // LIHAT SURAT BALASAN
public function lihatSuratBalasan($id)
{
    $pengajuan = PengajuanMagang::findOrFail($id);

    if (!$pengajuan->surat_balasan) {
        abort(404, 'Surat balasan belum tersedia.');
    }

    $disk = Storage::disk('public');

    if (!$disk->exists($pengajuan->surat_balasan)) {
        abort(404, 'File surat balasan tidak ditemukan.');
    }

    return response()->file(
        $disk->path($pengajuan->surat_balasan)
    );
}
}