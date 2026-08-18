<?php

namespace App\Models;

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Divisi;
use App\Models\Jurusan;
use App\Models\Universitas;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // 1. Opsi Dropdown Filter
        $listJurusan = Jurusan::orderBy('nama_jurusan')->get();
        $listUniversitas = Universitas::orderBy('nama_universitas')->get();
        $listDivisi = Divisi::orderBy('nama_divisi')->get();

        // 2. Query Base Pengajuan Magang dengan Filter
        $query = PengajuanMagang::with(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jurusan_id')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }

        if ($request->filled('universitas_id')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('universitas_id', $request->universitas_id);
            });
        }

        if ($request->filled('jenis_kelamin')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('jenis_kelamin', $request->jenis_kelamin);
            });
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
        // 3. Menghitung Cards Statistik
        // Magang Hari Ini: Sedang Magang dan tanggal hari ini di dalam rentang
        $magangHariIni = (clone $query)
            ->where('status', 'Sedang Magang')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();

        $totalMahasiswa = (clone $query)->distinct('mahasiswa_id')->count('mahasiswa_id');

        $belumSelesai = (clone $query)->whereIn('status', ['Akan Magang', 'Sedang Magang'])->count();

        $akanDatang = (clone $query)->where('status', 'Akan Magang')->count();

        $divisiTerlibat = (clone $query)->where('status', 'Sedang Magang')->distinct('divisi_id')->count('divisi_id');

        // 4. Data Chart: Status Magang
        $statusAktif = (clone $query)->where('status', 'Sedang Magang')->count();
        $statusAkanDatang = (clone $query)->where('status', 'Akan Magang')->count();
        $statusSelesai = (clone $query)->where('status', 'Selesai Magang')->count();

        // 5. Data Chart: Jumlah Mahasiswa per Divisi
        $divisiStats = (clone $query)
            ->join('divisi', 'pengajuan_magang.divisi_id', '=', 'divisi.id_divisi')
            ->select(
                'divisi.nama_divisi',
                DB::raw('COUNT(DISTINCT pengajuan_magang.mahasiswa_id) as total')
            )
            ->groupBy('divisi.id_divisi', 'divisi.nama_divisi')
            ->get();

        $bidangLabels = $divisiStats->pluck('nama_divisi')->toArray();
        $bidangData = $divisiStats->pluck('total')->toArray();


        // 6. Data Chart: Sebaran Mahasiswa per Universitas
        $univStats = (clone $query)
            ->join('mahasiswa', 'pengajuan_magang.mahasiswa_id', '=', 'mahasiswa.id_mhs')
            ->join('universitas', 'mahasiswa.universitas_id', '=', 'universitas.id_universitas')
            ->select('universitas.nama_universitas', DB::raw('count(DISTINCT mahasiswa.id_mhs) as total'))
            ->groupBy('universitas.id_universitas', 'universitas.nama_universitas')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $univLabels = $univStats->pluck('nama_universitas')->toArray();
        $univData = $univStats->pluck('total')->toArray();

        $pengajuans = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('dashboard', compact(
            'pengajuans',
            'magangHariIni',
            'totalMahasiswa',
            'belumSelesai',
            'akanDatang',
            'divisiTerlibat',
            'listJurusan',
            'listUniversitas',
            'listDivisi',
            'bidangLabels',
            'bidangData',
            'statusAktif',
            'statusAkanDatang',
            'statusSelesai',
            'univLabels',
            'univData'
        ));
    }
}
