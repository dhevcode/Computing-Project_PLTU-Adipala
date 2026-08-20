<?php

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

        // =====================================================
        // DROPDOWN FILTER
        // =====================================================

        $listJurusan = Jurusan::orderBy('nama_jurusan')->get();

        $listUniversitas = Universitas::orderBy('nama_universitas')->get();

        $listDivisi = Divisi::orderBy('nama_divisi')->get();


        // =====================================================
        // QUERY UTAMA
        // Semua card + semua chart menggunakan query ini
        // =====================================================

        $query = PengajuanMagang::query();


        // =====================================================
        // FILTER PENCARIAN
        // =====================================================

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('mahasiswa', function ($q) use ($search) {

                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");

            });
        }


        // =====================================================
        // FILTER JURUSAN
        // =====================================================

        if ($request->filled('jurusan_id')) {

            $query->whereHas('mahasiswa', function ($q) use ($request) {

                $q->where(
                    'jurusan_id',
                    $request->jurusan_id
                );

            });
        }


        // =====================================================
        // FILTER UNIVERSITAS
        // =====================================================

        if ($request->filled('universitas_id')) {

            $query->whereHas('mahasiswa', function ($q) use ($request) {

                $q->where(
                    'universitas_id',
                    $request->universitas_id
                );

            });
        }


        // =====================================================
        // FILTER DIVISI
        // =====================================================

        if ($request->filled('divisi_id')) {

            $query->where(
                'divisi_id',
                $request->divisi_id
            );
        }


        // =====================================================
        // FILTER JENIS KELAMIN
        // =====================================================

        if ($request->filled('jenis_kelamin')) {

            $query->whereHas('mahasiswa', function ($q) use ($request) {

                $q->where(
                    'jenis_kelamin',
                    $request->jenis_kelamin
                );

            });
        }


        // =====================================================
        // FILTER STATUS
        // =====================================================

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        // =====================================================
        // FILTER TANGGAL MULAI
        // =====================================================

        if ($request->filled('tanggal_mulai')) {

            $query->whereDate(
                'tanggal_mulai',
                $request->tanggal_mulai
            );
        }


        // =====================================================
        // FILTER TANGGAL SELESAI
        // =====================================================

        if ($request->filled('tanggal_selesai')) {

            $query->whereDate(
                'tanggal_selesai',
                $request->tanggal_selesai
            );
        }


        // =====================================================
        // FILTER TAHUN
        // =====================================================

        if ($request->filled('tahun')) {

            $query->whereYear(
                'tanggal_mulai',
                $request->tahun
            );
        }


        // =====================================================
        // CARD STATISTIK
        // =====================================================

        // Magang Hari Ini
        $magangHariIni = (clone $query)
            ->where('status', 'Sedang Magang')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();


        // Total Mahasiswa
        $totalMahasiswa = (clone $query)
            ->distinct('mahasiswa_id')
            ->count('mahasiswa_id');


        // Belum Selesai
        $belumSelesai = (clone $query)
            ->whereIn('status', [
                'Akan Magang',
                'Sedang Magang'
            ])
            ->count();


        // Akan Datang
        $akanDatang = (clone $query)
            ->where('status', 'Akan Magang')
            ->count();


        // Divisi Terlibat
        $divisiTerlibat = (clone $query)
            ->where('status', 'Sedang Magang')
            ->distinct('divisi_id')
            ->count('divisi_id');


        // =====================================================
        // CHART STATUS MAGANG
        // =====================================================

        $statusAktif = (clone $query)
            ->where('status', 'Sedang Magang')
            ->count();


        $statusAkanDatang = (clone $query)
            ->where('status', 'Akan Magang')
            ->count();


        $statusSelesai = (clone $query)
            ->where('status', 'Selesai Magang')
            ->count();


        // =====================================================
// CHART JUMLAH MAHASISWA PER DIVISI
// Semua divisi tetap ditampilkan, termasuk yang 0
// =====================================================

$divisiStats = Divisi::query()
    ->leftJoinSub(
        (clone $query)
            ->select(
                'divisi_id',
                DB::raw('COUNT(DISTINCT mahasiswa_id) as total')
            )
            ->groupBy('divisi_id'),
        'filtered_pengajuan',
        'divisi.id_divisi',
        '=',
        'filtered_pengajuan.divisi_id'
    )
    ->select(
        'divisi.id_divisi',
        'divisi.nama_divisi',
        DB::raw('COALESCE(filtered_pengajuan.total, 0) as total')
    )
    ->orderBy('divisi.id_divisi')
    ->get();

$bidangLabels = $divisiStats
    ->pluck('nama_divisi')
    ->toArray();

$bidangData = $divisiStats
    ->pluck('total')
    ->map(fn ($total) => (int) $total)
    ->toArray();


        // =====================================================
        // CHART SEBARAN MAHASISWA PER UNIVERSITAS
        // =====================================================

        $univStats = (clone $query)
            ->join(
                'mahasiswa',
                'pengajuan_magang.mahasiswa_id',
                '=',
                'mahasiswa.id_mhs'
            )
            ->join(
                'universitas',
                'mahasiswa.universitas_id',
                '=',
                'universitas.id_universitas'
            )
            ->select(
                'universitas.nama_universitas',
                DB::raw(
                    'COUNT(DISTINCT mahasiswa.id_mhs) as total'
                )
            )
            ->groupBy(
                'universitas.id_universitas',
                'universitas.nama_universitas'
            )
            ->orderByDesc('total')
            ->get();


        $univLabels = $univStats
            ->pluck('nama_universitas')
            ->toArray();


        $univData = $univStats
            ->pluck('total')
            ->toArray();


        // =====================================================
        // DATA PENGAJUAN
        // =====================================================

        $pengajuans = (clone $query)
            ->latest()
            ->paginate(10)
            ->withQueryString();


        // =====================================================
        // RETURN DASHBOARD
        // =====================================================

        return view('dashboard', compact(

            'pengajuans',

            // Cards
            'magangHariIni',
            'totalMahasiswa',
            'belumSelesai',
            'akanDatang',
            'divisiTerlibat',

            // Dropdown
            'listJurusan',
            'listUniversitas',
            'listDivisi',

            // Chart Divisi
            'bidangLabels',
            'bidangData',

            // Chart Status
            'statusAktif',
            'statusAkanDatang',
            'statusSelesai',

            // Chart Universitas
            'univLabels',
            'univData'
        ));
    }


    // =========================================================
    // FUNCTION DATA
    // FUNCTION INI TETAP PUNYA KAMU
    // =========================================================

    public function data(Request $request)
    {
        // KODE DATA KAMU YANG SEKARANG
        // TIDAK PERLU DIUBAH
    }
}