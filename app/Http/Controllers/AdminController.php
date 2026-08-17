<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Divisi;
use App\Models\Jurusan;
use App\Models\Universitas;
use App\Models\PengajuanMagang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $pengajuanQuery = PengajuanMagang::query();
        $mahasiswaQuery = Mahasiswa::query();

        // Search Nama / NIM
        if ($request->filled('search')) {
            $search = $request->search;

            $mahasiswaQuery->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });

            $pengajuanQuery->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Filter Jurusan
        if ($request->filled('jurusan_id')) {
            $mahasiswaQuery->where('jurusan_id', $request->jurusan_id);
            $pengajuanQuery->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }

        // Filter Universitas
        if ($request->filled('universitas_id')) {
            $mahasiswaQuery->where('universitas_id', $request->universitas_id);
            $pengajuanQuery->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('universitas_id', $request->universitas_id);
            });
        }

        // Filter Divisi
        if ($request->filled('divisi_id')) {
            $pengajuanQuery->where('divisi_id', $request->divisi_id);
        }

        // Filter Jenis Kelamin
        if ($request->filled('jenis_kelamin')) {
            $mahasiswaQuery->where('jenis_kelamin', $request->jenis_kelamin);

            $pengajuanQuery->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('jenis_kelamin', $request->jenis_kelamin);
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $pengajuanQuery->where('status', $request->status);
        }

        // Filter Tanggal Mulai
        if ($request->filled('tanggal_mulai')) {
            $pengajuanQuery->whereDate('tanggal_mulai', $request->tanggal_mulai);
        }

        // Filter Tanggal Selesai
        if ($request->filled('tanggal_selesai')) {
            $pengajuanQuery->whereDate('tanggal_selesai', $request->tanggal_selesai);
        }

        // Filter Tahun
        if ($request->filled('tahun')) {
            $pengajuanQuery->whereYear('tanggal_mulai', $request->tahun);
        }

        /*
        |--------------------------------------------------------------------------
        | CARD STATISTIK
        |--------------------------------------------------------------------------
        */

        $magangHariIniQuery = clone $pengajuanQuery;
        $magangHariIni = $magangHariIniQuery
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();

        $totalMahasiswaQuery = clone $mahasiswaQuery;
        $totalMahasiswa = $totalMahasiswaQuery->count();

        $belumSelesaiQuery = clone $pengajuanQuery;
        $belumSelesai = $belumSelesaiQuery
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();

        $akanDatangQuery = clone $pengajuanQuery;
        $akanDatang = $akanDatangQuery
            ->whereDate('tanggal_mulai', '>', $today)
            ->count();

        $divisiTerlibatQuery = clone $pengajuanQuery;
        $divisiTerlibat = $divisiTerlibatQuery
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->distinct('divisi_id')
            ->count('divisi_id');

        /*
        |--------------------------------------------------------------------------
        | CHART DIVISI
        |--------------------------------------------------------------------------
        */

        $chartDivisi = (clone $pengajuanQuery)
            ->join('divisi', 'pengajuan_magang.divisi_id', '=', 'divisi.id_divisi')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->select(
                'divisi.nama_divisi',
                DB::raw('COUNT(DISTINCT pengajuan_magang.mahasiswa_id) as total')
            )
            ->groupBy('divisi.id_divisi', 'divisi.nama_divisi')
            ->orderBy('divisi.nama_divisi')
            ->get();

        $bidangLabels = $chartDivisi->pluck('nama_divisi');

        $bidangData = $chartDivisi->pluck('total');


        /*
        |--------------------------------------------------------------------------
        | CHART STATUS
        |--------------------------------------------------------------------------
        */

        $statusAktifQuery = clone $pengajuanQuery;
        $statusAktif = $statusAktifQuery
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();

        $statusAkanDatangQuery = clone $pengajuanQuery;
        $statusAkanDatang = $statusAkanDatangQuery
            ->whereDate('tanggal_mulai', '>', $today)
            ->count();

        $statusSelesaiQuery = clone $pengajuanQuery;
        $statusSelesai = $statusSelesaiQuery
            ->whereDate('tanggal_selesai', '<', $today)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | CHART UNIVERSITAS
        |--------------------------------------------------------------------------
        */

        $chartUniv = Universitas::withCount('mahasiswa')
            ->having('mahasiswa_count', '>', 0)
            ->orderByDesc('mahasiswa_count')
            ->get();

        $univLabels = $chartUniv->pluck('nama_universitas');
        $univData = $chartUniv->pluck('mahasiswa_count');

        /*
        |--------------------------------------------------------------------------
        | DROPDOWN & MONITORED DATA
        |--------------------------------------------------------------------------
        */

        $listDivisi = Divisi::orderBy('nama_divisi')->get();
        $listJurusan = Jurusan::orderBy('nama_jurusan')->get();
        $listUniversitas = Universitas::orderBy('nama_universitas')->get();

        // Ambil data monitoring ter-filter dengan relasinya
        $pengajuans = $pengajuanQuery->with(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.dashboard', compact(
            'magangHariIni',
            'totalMahasiswa',
            'belumSelesai',
            'akanDatang',
            'divisiTerlibat',
            'bidangLabels',
            'bidangData',
            'statusAktif',
            'statusAkanDatang',
            'statusSelesai',
            'univLabels',
            'univData',
            'listDivisi',
            'listJurusan',
            'listUniversitas',
            'pengajuans'
        ));
    }

    public function data(Request $request)
    {
        // Query utama data mahasiswa magang
        $query = PengajuanMagang::with([
            'mahasiswa.universitas',
            'mahasiswa.jurusan',
            'divisi'
        ]);

        // =========================
        // PENCARIAN NAMA / NIM
        // =========================

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('mahasiswa', function ($q) use ($search) {

                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }


        // =========================
        // FILTER JURUSAN
        // =========================

        if ($request->filled('jurusan_id')) {

            $query->whereHas('mahasiswa', function ($q) use ($request) {

                $q->where('jurusan_id', $request->jurusan_id);
            });
        }


        // =========================
        // FILTER UNIVERSITAS
        // =========================

        if ($request->filled('universitas_id')) {

            $query->whereHas('mahasiswa', function ($q) use ($request) {

                $q->where('universitas_id', $request->universitas_id);
            });
        }


        // =========================
        // FILTER DIVISI
        // =========================

        if ($request->filled('divisi_id')) {

            $query->where(
                'divisi_id',
                $request->divisi_id
            );
        }


        // =========================
        // FILTER JENIS KELAMIN
        // =========================

        if ($request->filled('jenis_kelamin')) {

            $query->whereHas('mahasiswa', function ($q) use ($request) {

                $q->where(
                    'jenis_kelamin',
                    $request->jenis_kelamin
                );
            });
        }


        // =========================
        // FILTER STATUS
        // =========================

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        // =========================
        // FILTER TANGGAL MULAI
        // =========================

        if ($request->filled('tanggal_mulai')) {

            $query->whereDate(
                'tanggal_mulai',
                $request->tanggal_mulai
            );
        }


        // =========================
        // FILTER TANGGAL SELESAI
        // =========================

        if ($request->filled('tanggal_selesai')) {

            $query->whereDate(
                'tanggal_selesai',
                $request->tanggal_selesai
            );
        }


        // =========================
        // FILTER TAHUN
        // =========================

        if ($request->filled('tahun')) {

            $query->whereYear(
                'tanggal_mulai',
                $request->tahun
            );
        }


        // =========================
        // DATA MAHASISWA
        // =========================

        $pengajuans = $query
            ->latest('id_pengajuan')
            ->paginate(10)
            ->withQueryString();


        // =========================
        // DATA DROPDOWN FILTER
        // =========================

        $listJurusan = Jurusan::orderBy(
            'nama_jurusan'
        )->get();

        $listUniversitas = Universitas::orderBy(
            'nama_universitas'
        )->get();

        $listDivisi = Divisi::orderBy(
            'nama_divisi'
        )->get();


        return view('admin.data', compact(
            'pengajuans',
            'listJurusan',
            'listUniversitas',
            'listDivisi'
        ));
    }

    public function create()
    {
        $divisi = Divisi::all();
        $jurusan = Jurusan::all();
        $universitas = Universitas::all();

        return view('admin.create', compact(
            'divisi',
            'jurusan',
            'universitas'
        ));
    }

    public function store(Request $request)
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

        $universitasId = $request->universitas_id;
        if ($universitasId === 'new') {
            $request->validate([
                'nama_universitas_baru' => 'required|string|max:150|unique:universitas,nama_universitas',
            ], [
                'nama_universitas_baru.required' => 'Nama universitas baru wajib diisi.',
                'nama_universitas_baru.unique' => 'Nama universitas tersebut sudah ada.',
            ]);
            $univ = Universitas::create(['nama_universitas' => $request->nama_universitas_baru]);
            $universitasId = $univ->id_universitas;
        }

        $jurusanId = $request->jurusan_id;
        if ($jurusanId === 'new') {
            $request->validate([
                'nama_jurusan_baru' => 'required|string|max:100|unique:jurusan,nama_jurusan',
            ], [
                'nama_jurusan_baru.required' => 'Nama jurusan baru wajib diisi.',
                'nama_jurusan_baru.unique' => 'Nama jurusan tersebut sudah ada.',
            ]);
            $jur = Jurusan::create(['nama_jurusan' => $request->nama_jurusan_baru]);
            $jurusanId = $jur->id_jurusan;
        }

        $divisiId = $request->divisi_id;
        if ($divisiId === 'new') {
            $request->validate([
                'nama_divisi_baru' => 'required|string|max:100|unique:divisi,nama_divisi',
            ], [
                'nama_divisi_baru.required' => 'Nama divisi baru wajib diisi.',
                'nama_divisi_baru.unique' => 'Nama divisi tersebut sudah ada.',
            ]);
            $div = Divisi::create(['nama_divisi' => $request->nama_divisi_baru]);
            $divisiId = $div->id_divisi;
        }

        $mahasiswa = Mahasiswa::create([
            'nim' => $request->nim,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'universitas_id' => $universitasId,
            'jurusan_id' => $jurusanId,
        ]);

        $suratPath = null;
        if ($request->hasFile('surat_permohonan')) {
            $file = $request->file('surat_permohonan');
            $suratPath = time() . '_' . $file->getClientOriginalName();
            if (!file_exists(public_path('uploads/surat_permohonan'))) {
                mkdir(public_path('uploads/surat_permohonan'), 0777, true);
            }
            $file->move(public_path('uploads/surat_permohonan'), $suratPath);
        }

        // =========================
        // UPLOAD PROPOSAL
        // =========================

        $proposalPath = null;

        if ($request->hasFile('proposal')) {

            $file = $request->file('proposal');

            $fileName = time() . '_proposal_' . $file->getClientOriginalName();

            $proposalFolder = public_path('uploads/proposal');

            if (!file_exists($proposalFolder)) {
                mkdir($proposalFolder, 0777, true);
            }

            $file->move(
                $proposalFolder,
                $fileName
            );

            $proposalPath = $fileName;
        }


        // =========================
        // UPLOAD CV
        // =========================

        $cvPath = null;

        if ($request->hasFile('cv')) {

            $file = $request->file('cv');

            $fileName = time() . '_cv_' . $file->getClientOriginalName();

            $cvFolder = public_path('uploads/cv');

            if (!file_exists($cvFolder)) {
                mkdir($cvFolder, 0777, true);
            }

            $file->move(
                $cvFolder,
                $fileName
            );

            $cvPath = $fileName;
        }

        PengajuanMagang::create([
            'mahasiswa_id' => $mahasiswa->id_mhs,
            'divisi_id' => $divisiId,
            'nama_pembimbing' => $request->nama_pembimbing,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $this->generateStatus(
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ),
            'surat_permohonan' => $suratPath,
            'proposal' => $proposalPath,
            'cv' => $cvPath,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.data')
            ->with('success', 'Data Mahasiswa Magang berhasil ditambahkan!');
    }

    public function detail($id)
    {
        $pengajuan = PengajuanMagang::with([
            'mahasiswa.universitas',
            'mahasiswa.jurusan',
            'divisi'
        ])->findOrFail($id);

        return view('admin.detail', compact('pengajuan'));
    }


    public function edit($id)
    {
        $pengajuan = PengajuanMagang::with('mahasiswa')->findOrFail($id);
        $divisi = Divisi::all();
        $jurusan = Jurusan::all();
        $universitas = Universitas::all();

        return view('admin.edit', compact(
            'pengajuan',
            'divisi',
            'jurusan',
            'universitas'
        ));
    }

    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        $mahasiswa = $pengajuan->mahasiswa;

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
            'surat_permohonan' => 'required|file|mimes:pdf,doc,docx,txt|max:4096',
            'proposal' => 'required|file|mimes:pdf,doc,docx,txt|max:4096',
            'cv' => 'required|file|mimes:pdf,doc,docx,txt|max:4096',
        ]);

        $universitasId = $request->universitas_id;
        if ($universitasId === 'new') {
            $request->validate([
                'nama_universitas_baru' => 'required|string|max:150|unique:universitas,nama_universitas',
            ]);
            $univ = Universitas::create(['nama_universitas' => $request->nama_universitas_baru]);
            $universitasId = $univ->id_universitas;
        }

        $jurusanId = $request->jurusan_id;
        if ($jurusanId === 'new') {
            $request->validate([
                'nama_jurusan_baru' => 'required|string|max:100|unique:jurusan,nama_jurusan',
            ]);
            $jur = Jurusan::create(['nama_jurusan' => $request->nama_jurusan_baru]);
            $jurusanId = $jur->id_jurusan;
        }

        $divisiId = $request->divisi_id;
        if ($divisiId === 'new') {
            $request->validate([
                'nama_divisi_baru' => 'required|string|max:100|unique:divisi,nama_divisi',
            ]);
            $div = Divisi::create(['nama_divisi' => $request->nama_divisi_baru]);
            $divisiId = $div->id_divisi;
        }

        $mahasiswa->update([
            'nim' => $request->nim,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'universitas_id' => $universitasId,
            'jurusan_id' => $jurusanId,
        ]);

        $suratPath = $pengajuan->surat_permohonan;
        if ($request->hasFile('surat_permohonan')) {
            // Delete old file
            if ($suratPath && file_exists(public_path('uploads/surat_permohonan/' . $suratPath))) {
                @unlink(public_path('uploads/surat_permohonan/' . $suratPath));
            }

            $file = $request->file('surat_permohonan');
            $suratPath = time() . '_' . $file->getClientOriginalName();
            if (!file_exists(public_path('uploads/surat_permohonan'))) {
                mkdir(public_path('uploads/surat_permohonan'), 0777, true);
            }
            $file->move(public_path('uploads/surat_permohonan'), $suratPath);
        }

        // =========================
        // UPDATE PROPOSAL
        // =========================

        $proposalPath = $pengajuan->proposal;

        if ($request->hasFile('proposal')) {

            // Hapus proposal lama
            if (
                $proposalPath &&
                file_exists(public_path('uploads/proposal/' . $proposalPath))
            ) {
                @unlink(
                    public_path('uploads/proposal/' . $proposalPath)
                );
            }

            $file = $request->file('proposal');

            $proposalPath = time() . '_proposal_' . $file->getClientOriginalName();

            $proposalFolder = public_path('uploads/proposal');

            if (!file_exists($proposalFolder)) {
                mkdir($proposalFolder, 0777, true);
            }

            $file->move(
                $proposalFolder,
                $proposalPath
            );
        }


        // =========================
        // UPDATE CV
        // =========================

        $cvPath = $pengajuan->cv;

        if ($request->hasFile('cv')) {

            // Hapus CV lama
            if (
                $cvPath &&
                file_exists(public_path('uploads/cv/' . $cvPath))
            ) {
                @unlink(
                    public_path('uploads/cv/' . $cvPath)
                );
            }

            $file = $request->file('cv');

            $cvPath = time() . '_cv_' . $file->getClientOriginalName();

            $cvFolder = public_path('uploads/cv');

            if (!file_exists($cvFolder)) {
                mkdir($cvFolder, 0777, true);
            }

            $file->move(
                $cvFolder,
                $cvPath
            );
        }

        $pengajuan->update([
            'divisi_id' => $divisiId,
            'nama_pembimbing' => $request->nama_pembimbing,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $this->generateStatus(
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ),
            'surat_permohonan' => $suratPath,
            'proposal' => $proposalPath,
            'cv' => $cvPath,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Data Mahasiswa Magang berhasil diperbarui!');
    }

    public function destroy($id)
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

        return redirect()->route('admin.dashboard')
            ->with('success', 'Data Mahasiswa Magang berhasil dihapus!');
    }

    public function export(Request $request)
    {
        $pengajuanQuery = PengajuanMagang::with(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi']);

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $pengajuanQuery->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Filter Jurusan
        if ($request->filled('jurusan_id')) {
            $pengajuanQuery->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }

        // Filter Universitas
        if ($request->filled('universitas_id')) {
            $pengajuanQuery->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('universitas_id', $request->universitas_id);
            });
        }

        // Filter Divisi
        if ($request->filled('divisi_id')) {
            $pengajuanQuery->where('divisi_id', $request->divisi_id);
        }

        // Filter Jenis Kelamin
        if ($request->filled('jenis_kelamin')) {
            $pengajuanQuery->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('jenis_kelamin', $request->jenis_kelamin);
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $pengajuanQuery->where('status', $request->status);
        }

        // Filter Tanggal Mulai
        if ($request->filled('tanggal_mulai')) {
            $pengajuanQuery->whereDate('tanggal_mulai', $request->tanggal_mulai);
        }

        // Filter Tanggal Selesai
        if ($request->filled('tanggal_selesai')) {
            $pengajuanQuery->whereDate('tanggal_selesai', $request->tanggal_selesai);
        }

        // Filter Tahun
        if ($request->filled('tahun')) {
            $pengajuanQuery->whereYear('tanggal_mulai', $request->tahun);
        }

        $data = $pengajuanQuery->latest()->get();
        $filename = "data-monitoring-magang-" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($file, ['No', 'NIM', 'Nama Lengkap', 'Email', 'No Telepon', 'Universitas', 'Jurusan', 'Divisi', 'Tanggal Mulai', 'Tanggal Selesai', 'Status', 'Nama Pembimbing']);

            foreach ($data as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row->mahasiswa->nim ?? '-',
                    $row->mahasiswa->nama_lengkap ?? '-',
                    $row->mahasiswa->email ?? '-',
                    $row->mahasiswa->no_telepon ?? '-',
                    $row->mahasiswa->universitas->nama_universitas ?? '-',
                    $row->mahasiswa->jurusan->nama_jurusan ?? '-',
                    $row->divisi->nama_divisi ?? '-',
                    $row->tanggal_mulai,
                    $row->tanggal_selesai,
                    $row->status,
                    $row->nama_pembimbing ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generateStatus($tanggalMulai, $tanggalSelesai)
    {
        $today = Carbon::today();

        if ($today->lt(Carbon::parse($tanggalMulai))) {
            return 'Akan Magang';
        }

        if (
            $today->between(
                Carbon::parse($tanggalMulai),
                Carbon::parse($tanggalSelesai)
            )
        ) {
            return 'Sedang Magang';
        }

        return 'Selesai Magang';
    }
}
