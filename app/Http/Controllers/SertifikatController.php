<?php

namespace App\Http\Controllers;
use App\Models\Sertifikat;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\Universitas;
use App\Models\Jurusan;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    /**
     * =====================================================
     * HALAMAN SERTIFIKAT VERIFICATION
     * =====================================================
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::with([
            'sertifikat',
            'pengajuanMagang.divisi',
            'universitas',
            'jurusan'
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | JURUSAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jurusan')) {

            $query->where(
                'jurusan_id',
                $request->jurusan
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UNIVERSITAS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('universitas')) {

            $query->where(
                'universitas_id',
                $request->universitas
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DIVISI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('divisi')) {

            $query->whereHas(
                'pengajuanMagang',
                function ($q) use ($request) {

                    $q->where(
                        'divisi_id',
                        $request->divisi
                    );

                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | JENIS KELAMIN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jenis_kelamin')) {

            $query->where(
                'jenis_kelamin',
                $request->jenis_kelamin
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS MAGANG
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $status = $request->status;

            $statusDatabase = match ($status) {

                'Akan Datang' => 'Akan Magang',

                'Aktif' => 'Sedang Magang',

                'Selesai' => 'Selesai Magang',

                default => $status
            };

            $query->whereHas(
                'pengajuanMagang',
                function ($q) use ($statusDatabase) {

                    $q->where(
                        'status',
                        $statusDatabase
                    );

                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TANGGAL MULAI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tanggal_mulai')) {

            $query->whereHas(
                'pengajuanMagang',
                function ($q) use ($request) {

                    $q->whereDate(
                        'tanggal_mulai',
                        '>=',
                        $request->tanggal_mulai
                    );

                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TANGGAL SELESAI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tanggal_selesai')) {

            $query->whereHas(
                'pengajuanMagang',
                function ($q) use ($request) {

                    $q->whereDate(
                        'tanggal_selesai',
                        '<=',
                        $request->tanggal_selesai
                    );

                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        $mahasiswa = $query
            ->orderBy('nama_lengkap')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DATA FILTER
        |--------------------------------------------------------------------------
        */

        $universitas = Universitas::orderBy(
            'nama_universitas'
        )->get();

        $jurusan = Jurusan::orderBy(
            'nama_jurusan'
        )->get();

        $divisi = Divisi::orderBy(
            'nama_divisi'
        )->get();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.sertifikat',
            compact(
                'mahasiswa',
                'universitas',
                'jurusan',
                'divisi'
            )
        );
    }

    /**
 * =====================================================
 * VERIFIKASI ID CARD
 * =====================================================
 */
public function verifyIdCard(Request $request, $id)
{
    $mahasiswa = Mahasiswa::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | PASTIKAN LAPORAN SUDAH ADA
    |--------------------------------------------------------------------------
    */

    if (!$mahasiswa->file_laporan) {

        return redirect()
            ->route('admin.sertifikat')
            ->with(
                'error',
                'Mahasiswa harus mengupload laporan terlebih dahulu.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN STATUS ID CARD
    |--------------------------------------------------------------------------
    */

    $mahasiswa->id_card_verified = true;

    $mahasiswa->save();

    return redirect()
        ->route('admin.sertifikat')
        ->with(
            'success',
            'ID Card berhasil diverifikasi. Mahasiswa sudah memenuhi persyaratan.'
        );
}
    /**
     * =====================================================
     * UPLOAD / UPDATE SERTIFIKAT
     * =====================================================
     */
    /**
 * =====================================================
 * UPLOAD / UPDATE SERTIFIKAT
 * =====================================================
 */
public function upload(Request $request, $id)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDASI FILE
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'certificate' => [
            'required',
            'file',
            'mimes:pdf,jpg,jpeg,png',
            'max:5120',
        ],
    ], [
        'certificate.required' =>
            'Silakan pilih berkas sertifikat.',

        'certificate.file' =>
            'Berkas sertifikat tidak valid.',

        'certificate.mimes' =>
            'Sertifikat harus berupa PDF, JPG, JPEG, atau PNG.',

        'certificate.max' =>
            'Ukuran sertifikat maksimal 5 MB.',
    ]);


    /*
    |--------------------------------------------------------------------------
    | CARI MAHASISWA
    |--------------------------------------------------------------------------
    */

    $mahasiswa = Mahasiswa::findOrFail($id);


    /*
    |--------------------------------------------------------------------------
    | PASTIKAN LAPORAN SUDAH ADA
    |--------------------------------------------------------------------------
    */

    if (!$mahasiswa->file_laporan) {

        return redirect()
            ->route('admin.sertifikat')
            ->with(
                'error',
                'Sertifikat belum dapat diupload. Laporan mahasiswa belum tersedia.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PASTIKAN ID CARD SUDAH DIVERIFIKASI
    |--------------------------------------------------------------------------
    */

    if (!$mahasiswa->id_card_verified) {

        return redirect()
            ->route('admin.sertifikat')
            ->with(
                'error',
                'Sertifikat belum dapat diupload. ID Card belum diverifikasi.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CARI PENGAJUAN MAGANG TERBARU
    |--------------------------------------------------------------------------
    */

    $pengajuan = PengajuanMagang::where(
        'mahasiswa_id',
        $mahasiswa->id_mhs
    )
        ->latest('id_pengajuan')
        ->first();


    /*
    |--------------------------------------------------------------------------
    | PASTIKAN ADA PENGAJUAN
    |--------------------------------------------------------------------------
    */

    if (!$pengajuan) {

        return redirect()
            ->route('admin.sertifikat')
            ->with(
                'error',
                'Mahasiswa belum memiliki data pengajuan magang.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CARI SERTIFIKAT TERBARU
    |--------------------------------------------------------------------------
    */

    $sertifikat = Sertifikat::where(
        'mahasiswa_id',
        $mahasiswa->id_mhs
    )
        ->latest('id_sertifikat')
        ->first();


    /*
    |--------------------------------------------------------------------------
    | JIKA BELUM ADA DATA SERTIFIKAT
    |--------------------------------------------------------------------------
    */

    if (!$sertifikat) {

        $sertifikat = new Sertifikat();

        $sertifikat->mahasiswa_id =
            $mahasiswa->id_mhs;
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS FILE SERTIFIKAT LAMA
    |--------------------------------------------------------------------------
    */

    if ($sertifikat->file_sertifikat) {

        $oldPath = public_path(
            'uploads/sertifikat_mhs/' .
            basename($sertifikat->file_sertifikat)
        );

        if (file_exists($oldPath)) {

            unlink($oldPath);

        }
    }


    /*
    |--------------------------------------------------------------------------
    | SIAPKAN FOLDER
    |--------------------------------------------------------------------------
    */

    $folder = public_path(
        'uploads/sertifikat_mhs'
    );

    if (!is_dir($folder)) {

        mkdir(
            $folder,
            0755,
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NAMA FILE
    |--------------------------------------------------------------------------
    */

    $extension =
        $request
            ->file('certificate')
            ->getClientOriginalExtension();

    $namaFile =
        'sertifikat_' .
        $mahasiswa->nim .
        '_' .
        time() .
        '.' .
        $extension;


    /*
    |--------------------------------------------------------------------------
    | SIMPAN FILE
    |--------------------------------------------------------------------------
    */

    $request
        ->file('certificate')
        ->move(
            $folder,
            $namaFile
        );


    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA DATABASE
    |--------------------------------------------------------------------------
    */

    $sertifikat->mahasiswa_id =
        $mahasiswa->id_mhs;

    $sertifikat->pengajuan_id =
        $pengajuan->id_pengajuan;

    $sertifikat->file_sertifikat =
        'uploads/sertifikat_mhs/' .
        $namaFile;

    $sertifikat->status =
        'Diterbitkan';

    $sertifikat->tanggal_terbit =
        now()->toDateString();

    $sertifikat->uploaded_by =
        Auth::id();

    $sertifikat->uploaded_at =
        now();

    $sertifikat->save();


    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('admin.sertifikat')
        ->with(
            'success',
            'Sertifikat berhasil diupload.'
        );
}
    /**
 * =====================================================
 * UPLOAD / UPDATE LAPORAN MAHASISWA
 * =====================================================
 */
public function uploadLaporan(Request $request, $id)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'laporan' => [
            'required',
            'file',
            'mimes:pdf',
            'max:10240',
        ],
    ], [
        'laporan.required' =>
            'Silakan pilih file laporan.',

        'laporan.file' =>
            'File laporan tidak valid.',

        'laporan.mimes' =>
            'Laporan harus berupa file PDF.',

        'laporan.max' =>
            'Ukuran laporan maksimal 10 MB.',
    ]);


    /*
    |--------------------------------------------------------------------------
    | CARI MAHASISWA
    |--------------------------------------------------------------------------
    */

    $mahasiswa = Mahasiswa::findOrFail($id);


    /*
    |--------------------------------------------------------------------------
    | HAPUS FILE LAMA
    |--------------------------------------------------------------------------
    */

    if ($mahasiswa->file_laporan) {

        $oldPath = public_path(
            'uploads/laporan_mhs/' .
            basename($mahasiswa->file_laporan)
        );

        if (file_exists($oldPath)) {

            unlink($oldPath);

        }
    }


    /*
    |--------------------------------------------------------------------------
    | SIAPKAN FOLDER
    |--------------------------------------------------------------------------
    */

    $folder = public_path(
        'uploads/laporan_mhs'
    );

    if (!is_dir($folder)) {

        mkdir(
            $folder,
            0755,
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NAMA FILE
    |--------------------------------------------------------------------------
    */

    $namaFile =
        'laporan_' .
        $mahasiswa->nim .
        '_' .
        time() .
        '.pdf';


    /*
    |--------------------------------------------------------------------------
    | SIMPAN FILE
    |--------------------------------------------------------------------------
    */

    $request
        ->file('laporan')
        ->move(
            $folder,
            $namaFile
        );


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PATH KE DATABASE
    |--------------------------------------------------------------------------
    |
    | Yang disimpan cukup:
    |
    | uploads/laporan_mhs/nama_file.pdf
    |
    */

    $mahasiswa->file_laporan =
        'uploads/laporan_mhs/' .
        $namaFile;

    $mahasiswa->save();


    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('admin.sertifikat')
        ->with(
            'success',
            'Laporan mahasiswa berhasil diupload.'
        );
}
}