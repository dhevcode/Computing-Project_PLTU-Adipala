<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Divisi;
use App\Models\Jurusan;
use App\Models\Universitas;
use App\Models\PengajuanMagang;
use Carbon\Carbon;

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

        /*
        |--------------------------------------------------------------------------
        | CARD STATISTIK
        |--------------------------------------------------------------------------
        */

        $magangHariIni = (clone $pengajuanQuery)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();

        $totalMahasiswa = (clone $mahasiswaQuery)->count();

        $belumSelesai = (clone $pengajuanQuery)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();

        $akanDatang = (clone $pengajuanQuery)
            ->whereDate('tanggal_mulai', '>', $today)
            ->count();

        $divisiTerlibat = (clone $pengajuanQuery)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->distinct('divisi_id')
            ->count('divisi_id');

        /*
        |--------------------------------------------------------------------------
        | CHART DIVISI
        |--------------------------------------------------------------------------
        */

        $chartDivisi = Divisi::withCount('pengajuanMagang')->get();

        $bidangLabels = $chartDivisi->pluck('nama_divisi');

        $bidangData = $chartDivisi->pluck('pengajuan_magang_count');

        /*
        |--------------------------------------------------------------------------
        | CHART STATUS
        |--------------------------------------------------------------------------
        */

        $statusAktif = (clone $pengajuanQuery)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();

        $statusAkanDatang = (clone $pengajuanQuery)
            ->whereDate('tanggal_mulai', '>', $today)
            ->count();

        $statusSelesai = (clone $pengajuanQuery)
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
        | DROPDOWN
        |--------------------------------------------------------------------------
        */

        $listDivisi = Divisi::all();
        $listJurusan = Jurusan::all();
        $listUniversitas = Universitas::all();

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
            'listUniversitas'
        ));
    }

    public function create()
    {
        $divisi = Divisi::all();
        $jurusan = Jurusan::all();

        return view('admin.create', compact(
            'divisi',
            'jurusan'
        ));
    }
}