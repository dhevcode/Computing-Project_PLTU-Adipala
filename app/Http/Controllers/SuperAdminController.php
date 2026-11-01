<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\Divisi;
use App\Models\Universitas;
use App\Models\Jurusan;
use App\Models\Attendance;
use App\Models\DailyLogbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    // =========================================================================
    // 1. DASHBOARD OVERVIEW & KELOLA AKUN USER (ADMIN, PEMBIMBING, PESERTA)
    // =========================================================================
    public function index()
    {
        // Data akun sistem (Admin, Super Admin, Pembimbing, Peserta)
        $admins = User::with(['divisi', 'mahasiswa'])
            ->latest('id_users')
            ->get();

        // Statistik ringkas untuk dashboard Super Admin
        $totalAdmin = User::whereIn('role', ['Admin', 'Super Admin'])->count();
        $totalPembimbing = User::whereIn('role', ['Pembimbing', 'Pembimbing Lapangan'])->count();
        $totalPesertaUser = User::where('role', 'Peserta')->count();
        $totalMahasiswa = Mahasiswa::count();
        $mahasiswaTanpaAkun = Mahasiswa::whereNull('user_id')->orWhereDoesntHave('user')->count();

        return view('superadmin.dashboard', compact(
            'admins',
            'totalAdmin',
            'totalPembimbing',
            'totalPesertaUser',
            'totalMahasiswa',
            'mahasiswaTanpaAkun'
        ));
    }

    public function create()
    {
        $divisi = Divisi::orderBy('nama_divisi')->get();
        // Mahasiswa yang belum memiliki akun user
        $mahasiswaTanpaAkun = Mahasiswa::whereNull('user_id')
            ->orWhereDoesntHave('user')
            ->orderBy('nama_lengkap')
            ->get();
        
        return view('superadmin.create', compact('divisi', 'mahasiswaTanpaAkun'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Akun
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:100|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
            'role'         => 'required|in:Admin,Super Admin,Pembimbing,Pembimbing Lapangan,Peserta',
            'id_divisi'    => 'nullable',
            'id_mhs'       => 'nullable|exists:mahasiswa,id_mhs',
        ]);

        $idDivisi = null;

        // 2. Jika Memilih "Tambah Divisi Baru"
        if ($request->id_divisi === 'new') {
            $request->validate([
                'nama_divisi_baru' => 'required|string|max:100|unique:divisi,nama_divisi',
            ], [
                'nama_divisi_baru.required' => 'Nama divisi baru wajib diisi.',
                'nama_divisi_baru.unique'   => 'Nama divisi tersebut sudah ada.',
            ]);

            $divisiBaru = Divisi::create([
                'nama_divisi' => trim($request->nama_divisi_baru),
            ]);

            $idDivisi = $divisiBaru->id_divisi;
        } else {
            $idDivisi = $request->id_divisi ?: null;
        }

        // 3. Simpan Akun User
        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'id_divisi'    => $idDivisi,
            'id_mhs'       => $request->role === 'Peserta' ? $request->id_mhs : null,
            'role'         => $request->role,
            'is_active'    => $request->input('is_active', 1),
            'created_by'   => Auth::id() ?? 1,
        ]);

        // Jika role Peserta dan ditautkan ke Mahasiswa, perbarui kolom user_id di mahasiswa
        if ($request->role === 'Peserta' && $request->filled('id_mhs')) {
            $mhs = Mahasiswa::find($request->id_mhs);
            if ($mhs) {
                $mhs->update(['user_id' => $user->id_users]);
            }
        }

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Akun ' . $request->role . ' atas nama ' . $request->nama_lengkap . ' berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $admin = User::findOrFail($id);
        $divisi = Divisi::orderBy('nama_divisi')->get();
        $mahasiswaList = Mahasiswa::orderBy('nama_lengkap')->get();
        
        return view('superadmin.edit', compact('admin', 'divisi', 'mahasiswaList'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:100|unique:users,username,' . $admin->id_users . ',id_users',
            'email'        => 'required|email|unique:users,email,' . $admin->id_users . ',id_users',
            'password'     => 'nullable|min:6',
            'role'         => 'required|in:Admin,Super Admin,Pembimbing,Pembimbing Lapangan,Peserta',
            'id_divisi'    => 'nullable',
            'is_active'    => 'required|in:0,1',
        ]);

        $idDivisi = null;

        if ($request->id_divisi === 'new') {
            $request->validate([
                'nama_divisi_baru' => 'required|string|max:100|unique:divisi,nama_divisi',
            ], [
                'nama_divisi_baru.required' => 'Nama divisi baru wajib diisi.',
                'nama_divisi_baru.unique'   => 'Nama divisi tersebut sudah ada.',
            ]);

            $divisiBaru = Divisi::create([
                'nama_divisi' => trim($request->nama_divisi_baru),
            ]);

            $idDivisi = $divisiBaru->id_divisi;
        } else {
            $idDivisi = $request->id_divisi ?: null;
        }

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'id_divisi'    => $idDivisi,
            'role'         => $request->role,
            'is_active'    => $request->is_active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->role === 'Peserta' && $request->filled('id_mhs')) {
            $data['id_mhs'] = $request->id_mhs;
            Mahasiswa::where('id_mhs', $request->id_mhs)->update(['user_id' => $admin->id_users]);
        }

        $admin->update($data);

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Data Akun berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Lepaskan tautan user_id di mahasiswa jika ada
        if ($user->id_mhs) {
            Mahasiswa::where('id_mhs', $user->id_mhs)->update(['user_id' => null]);
        }

        $user->delete();

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Data Akun berhasil dihapus!');
    }

    // =========================================================================
    // 2. DASHBOARD & DATA PEMBIMBING LAPANGAN (MONITORING MENTOR & MAHASISWA)
    // =========================================================================
    public function pembimbing()
    {
        $pembimbings = User::with('divisi')
            ->whereIn('role', ['Pembimbing', 'Pembimbing Lapangan'])
            ->latest('id_users')
            ->get();

        // Ambil data bimbingan untuk masing-masing pembimbing
        $pembimbingData = $pembimbings->map(function ($p) {
            $bimbingan = $p->getBimbinganPengajuan();
            return [
                'user'             => $p,
                'total_mahasiswa'  => $bimbingan->count(),
                'sedang_magang'    => $bimbingan->where('status', 'Sedang Magang')->count(),
                'akan_magang'      => $bimbingan->where('status', 'Akan Magang')->count(),
                'selesai_magang'   => $bimbingan->where('status', 'Selesai Magang')->count(),
                'daftar_bimbingan' => $bimbingan,
            ];
        });

        // Statistik Pembimbing
        $totalPembimbing = $pembimbings->count();
        $totalMahasiswaDibimbing = PengajuanMagang::whereNotNull('pembimbing_id')
            ->orWhereNotNull('nama_pembimbing')
            ->count();
        $pembimbingAktif = $pembimbings->where('is_active', 1)->count();

        return view('superadmin.pembimbing.index', compact(
            'pembimbingData',
            'totalPembimbing',
            'totalMahasiswaDibimbing',
            'pembimbingAktif'
        ));
    }

    // =========================================================================
    // 3. KELOLA DATA PESERTA MAGANG (CRUD LENGKAP & MANAJEMEN AKUN PESERTA)
    // =========================================================================
    public function peserta(Request $request)
    {
        $query = Mahasiswa::with([
            'universitas',
            'jurusan',
            'user',
            'latestPengajuan.divisi',
            'latestPengajuan.pembimbing'
        ]);

        // Filter Divisi
        if ($request->filled('divisi_id') && $request->divisi_id !== 'all') {
            $query->whereHas('pengajuanMagang', function ($q) use ($request) {
                $q->where('divisi_id', $request->divisi_id);
            });
        }

        // Filter Status Magang
        if ($request->filled('status') && $request->status !== 'all') {
            $query->whereHas('latestPengajuan', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Filter Status Akun (Sudah punya akun / Belum)
        if ($request->filled('status_akun') && $request->status_akun !== 'all') {
            if ($request->status_akun === 'ada_akun') {
                $query->whereNotNull('user_id')->has('user');
            } elseif ($request->status_akun === 'belum_akun') {
                $query->where(function ($q) {
                    $q->whereNull('user_id')->orWhereDoesntHave('user');
                });
            }
        }

        // Search Keyword (Nama, NIM, Email)
        if ($request->filled('q')) {
            $kw = $request->q;
            $query->where(function ($q) use ($kw) {
                $q->where('nama_lengkap', 'like', "%{$kw}%")
                  ->orWhere('nim', 'like', "%{$kw}%")
                  ->orWhere('email', 'like', "%{$kw}%");
            });
        }

        $mahasiswas = $query->latest('id_mhs')->paginate(12)->withQueryString();

        // Data Penunjang Filter
        $divisiList = Divisi::orderBy('nama_divisi')->get();

        // Statistik Peserta
        $totalPeserta = Mahasiswa::count();
        $sedangMagang = PengajuanMagang::where('status', 'Sedang Magang')->count();
        $akanMagang = PengajuanMagang::where('status', 'Akan Magang')->count();
        $belumPunyaAkun = Mahasiswa::whereNull('user_id')->orWhereDoesntHave('user')->count();

        return view('superadmin.peserta.index', compact(
            'mahasiswas',
            'divisiList',
            'totalPeserta',
            'sedangMagang',
            'akanMagang',
            'belumPunyaAkun'
        ));
    }

    public function createPeserta()
    {
        $universitas = Universitas::orderBy('nama_universitas')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        $divisi = Divisi::orderBy('nama_divisi')->get();
        $pembimbings = User::whereIn('role', ['Pembimbing', 'Pembimbing Lapangan'])
            ->where('is_active', 1)
            ->orderBy('nama_lengkap')
            ->get();

        return view('superadmin.peserta.create', compact('universitas', 'jurusan', 'divisi', 'pembimbings'));
    }

    public function storePeserta(Request $request)
    {
        $request->validate([
            'nama_lengkap'    => 'required|string|max:150',
            'nim'             => 'required|string|max:30|unique:mahasiswa,nim',
            'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
            'email'           => 'required|email|max:150|unique:mahasiswa,email',
            'no_telepon'      => 'required|string|max:20',
            'universitas_id'  => 'required',
            'jurusan_id'      => 'required',
            'divisi_id'       => 'required|exists:divisi,id_divisi',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'pembimbing_id'   => 'nullable',
            'surat_permohonan'=> 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'proposal'        => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cv'              => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // 1. Handle Universitas jika baru
        $universitasId = $request->universitas_id;
        if ($universitasId === 'new') {
            $request->validate([
                'nama_universitas_baru' => 'required|string|max:150|unique:universitas,nama_universitas',
            ]);
            $univ = Universitas::create(['nama_universitas' => trim($request->nama_universitas_baru)]);
            $universitasId = $univ->id_universitas;
        }

        // 2. Handle Jurusan jika baru
        $jurusanId = $request->jurusan_id;
        if ($jurusanId === 'new') {
            $request->validate([
                'nama_jurusan_baru' => 'required|string|max:100|unique:jurusan,nama_jurusan',
            ]);
            $jur = Jurusan::create(['nama_jurusan' => trim($request->nama_jurusan_baru)]);
            $jurusanId = $jur->id_jurusan;
        }

        // 3. Upload Berkas
        $suratPath = null;
        if ($request->hasFile('surat_permohonan')) {
            $file = $request->file('surat_permohonan');
            $suratPath = time() . '_surat_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $target = public_path('uploads/surat_permohonan');
            if (!File::exists($target)) File::makeDirectory($target, 0755, true);
            $file->move($target, $suratPath);
        }

        $proposalPath = null;
        if ($request->hasFile('proposal')) {
            $file = $request->file('proposal');
            $proposalPath = time() . '_proposal_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $target = public_path('uploads/proposal');
            if (!File::exists($target)) File::makeDirectory($target, 0755, true);
            $file->move($target, $proposalPath);
        }

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $cvPath = time() . '_cv_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $target = public_path('uploads/cv');
            if (!File::exists($target)) File::makeDirectory($target, 0755, true);
            $file->move($target, $cvPath);
        }

        // 4. Pembimbing
        $pembimbingUser = null;
        if ($request->filled('pembimbing_id')) {
            $pembimbingUser = User::find($request->pembimbing_id);
        }

        // 5. Hitung Status
        $now = Carbon::today();
        $mulai = Carbon::parse($request->tanggal_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai);
        $status = $now->lt($mulai) ? 'Akan Magang' : ($now->between($mulai, $selesai) ? 'Sedang Magang' : 'Selesai Magang');

        // 6. DB Transaction
        DB::transaction(function () use (
            $request, $universitasId, $jurusanId, $suratPath, $proposalPath, $cvPath, $pembimbingUser, $status
        ) {
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

            // Jika opsi Buatkan Akun dicentang
            if ($request->boolean('buat_akun')) {
                $password = $request->filled('password_akun') ? $request->password_akun : 'mhs123';
                $user = User::create([
                    'nama_lengkap' => $request->nama_lengkap,
                    'username'     => $request->nim,
                    'email'        => $request->email,
                    'password'     => Hash::make($password),
                    'phone'        => $request->no_telepon,
                    'role'         => 'Peserta',
                    'id_divisi'    => $request->divisi_id,
                    'id_mhs'       => $mahasiswa->id_mhs,
                    'is_active'    => true,
                    'created_by'   => Auth::id(),
                ]);

                $mahasiswa->update(['user_id' => $user->id_users]);
            }

            PengajuanMagang::create([
                'mahasiswa_id'     => $mahasiswa->id_mhs,
                'divisi_id'        => $request->divisi_id,
                'nama_pembimbing'  => $pembimbingUser ? $pembimbingUser->nama_lengkap : $request->nama_pembimbing,
                'pembimbing_id'    => $pembimbingUser ? $pembimbingUser->id_users : null,
                'tanggal_mulai'    => $request->tanggal_mulai,
                'tanggal_selesai'  => $request->tanggal_selesai,
                'status'           => $status,
                'surat_permohonan' => $suratPath,
                'proposal'         => $proposalPath,
                'cv'               => $cvPath,
                'created_by'       => Auth::id(),
            ]);
        });

        return redirect()->route('superadmin.peserta')
            ->with('success', 'Data peserta magang ' . $request->nama_lengkap . ' berhasil ditambahkan!');
    }

    public function showPeserta($id)
    {
        $mahasiswa = Mahasiswa::with([
            'universitas',
            'jurusan',
            'user',
            'latestPengajuan.divisi',
            'latestPengajuan.pembimbing',
            'attendances',
            'logbooks',
            'leaveRequests'
        ])->findOrFail($id);

        return view('superadmin.peserta.show', compact('mahasiswa'));
    }

    public function editPeserta($id)
    {
        $mahasiswa = Mahasiswa::with(['latestPengajuan'])->findOrFail($id);
        $universitas = Universitas::orderBy('nama_universitas')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        $divisi = Divisi::orderBy('nama_divisi')->get();
        $pembimbings = User::whereIn('role', ['Pembimbing', 'Pembimbing Lapangan'])
            ->where('is_active', 1)
            ->orderBy('nama_lengkap')
            ->get();

        return view('superadmin.peserta.edit', compact('mahasiswa', 'universitas', 'jurusan', 'divisi', 'pembimbings'));
    }

    public function updatePeserta(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $request->validate([
            'nama_lengkap'    => 'required|string|max:150',
            'nim'             => 'required|string|max:30|unique:mahasiswa,nim,' . $mahasiswa->id_mhs . ',id_mhs',
            'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
            'email'           => 'required|email|max:150|unique:mahasiswa,email,' . $mahasiswa->id_mhs . ',id_mhs',
            'no_telepon'      => 'required|string|max:20',
            'universitas_id'  => 'required',
            'jurusan_id'      => 'required',
            'divisi_id'       => 'required|exists:divisi,id_divisi',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'          => 'required|in:Akan Magang,Sedang Magang,Selesai Magang',
        ]);

        $universitasId = $request->universitas_id;
        if ($universitasId === 'new') {
            $univ = Universitas::create(['nama_universitas' => trim($request->nama_universitas_baru)]);
            $universitasId = $univ->id_universitas;
        }

        $jurusanId = $request->jurusan_id;
        if ($jurusanId === 'new') {
            $jur = Jurusan::create(['nama_jurusan' => trim($request->nama_jurusan_baru)]);
            $jurusanId = $jur->id_jurusan;
        }

        $pembimbingUser = null;
        if ($request->filled('pembimbing_id')) {
            $pembimbingUser = User::find($request->pembimbing_id);
        }

        DB::transaction(function () use ($mahasiswa, $request, $universitasId, $jurusanId, $pembimbingUser) {
            $mahasiswa->update([
                'nim'                     => $request->nim,
                'nama_lengkap'            => $request->nama_lengkap,
                'jenis_kelamin'           => $request->jenis_kelamin,
                'email'                   => $request->email,
                'no_telepon'              => $request->no_telepon,
                'emergency_contact_name'  => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'universitas_id'          => $universitasId,
                'jurusan_id'              => $jurusanId,
            ]);

            // Sinkronkan akun user jika ada
            if ($mahasiswa->user) {
                $mahasiswa->user->update([
                    'nama_lengkap' => $request->nama_lengkap,
                    'username'     => $request->nim,
                    'email'        => $request->email,
                    'phone'        => $request->no_telepon,
                    'id_divisi'    => $request->divisi_id,
                ]);
            }

            $pengajuan = $mahasiswa->latestPengajuan;
            if ($pengajuan) {
                $pengajuanData = [
                    'divisi_id'       => $request->divisi_id,
                    'pembimbing_id'   => $pembimbingUser ? $pembimbingUser->id_users : null,
                    'nama_pembimbing' => $pembimbingUser ? $pembimbingUser->nama_lengkap : $request->nama_pembimbing,
                    'tanggal_mulai'   => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                    'status'          => $request->status,
                ];

                if ($request->hasFile('surat_permohonan')) {
                    $file = $request->file('surat_permohonan');
                    $fn = time() . '_surat_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                    $file->move(public_path('uploads/surat_permohonan'), $fn);
                    $pengajuanData['surat_permohonan'] = $fn;
                }

                if ($request->hasFile('proposal')) {
                    $file = $request->file('proposal');
                    $fn = time() . '_proposal_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                    $file->move(public_path('uploads/proposal'), $fn);
                    $pengajuanData['proposal'] = $fn;
                }

                if ($request->hasFile('cv')) {
                    $file = $request->file('cv');
                    $fn = time() . '_cv_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                    $file->move(public_path('uploads/cv'), $fn);
                    $pengajuanData['cv'] = $fn;
                }

                $pengajuan->update($pengajuanData);
            }
        });

        return redirect()->route('superadmin.peserta')
            ->with('success', 'Data peserta magang ' . $mahasiswa->nama_lengkap . ' berhasil diperbarui!');
    }

    public function destroyPeserta($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $nama = $mahasiswa->nama_lengkap;

        DB::transaction(function () use ($mahasiswa) {
            // Hapus pengajuan
            PengajuanMagang::where('mahasiswa_id', $mahasiswa->id_mhs)->delete();
            
            // Hapus akun user peserta jika ada
            if ($mahasiswa->user_id) {
                User::where('id_users', $mahasiswa->user_id)->delete();
            }
            User::where('id_mhs', $mahasiswa->id_mhs)->delete();

            // Hapus mahasiswa
            $mahasiswa->delete();
        });

        return redirect()->route('superadmin.peserta')
            ->with('success', 'Data peserta magang ' . $nama . ' beserta seluruh riwayat terkait berhasil dihapus!');
    }

    // =========================================================================
    // 4. AKSI PEMBUATAN & RESET AKUN PESERTA MAGANG OLEH SUPER ADMIN
    // =========================================================================
    public function buatAkunPeserta(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        if ($mahasiswa->user) {
            return back()->with('error', 'Mahasiswa ini sudah memiliki akun portal: ' . $mahasiswa->user->email);
        }

        $request->validate([
            'password' => 'nullable|min:6',
        ]);

        $password = $request->filled('password') ? $request->password : 'mhs123';
        $divisiId = $mahasiswa->latestPengajuan?->divisi_id;

        // Cek username atau email di users
        $username = $mahasiswa->nim;
        $email = $mahasiswa->email;

        // Jika username sudah terpakai
        if (User::where('username', $username)->exists()) {
            $username = $username . '_' . rand(10, 99);
        }
        if (User::where('email', $email)->exists()) {
            $email = 'mhs_' . rand(10, 99) . '_' . $email;
        }

        $user = User::create([
            'nama_lengkap' => $mahasiswa->nama_lengkap,
            'username'     => $username,
            'email'        => $email,
            'password'     => Hash::make($password),
            'phone'        => $mahasiswa->no_telepon,
            'role'         => 'Peserta',
            'id_divisi'    => $divisiId,
            'id_mhs'       => $mahasiswa->id_mhs,
            'is_active'    => true,
            'created_by'   => Auth::id(),
        ]);

        $mahasiswa->update(['user_id' => $user->id_users]);

        return back()->with('success', "Akun portal peserta untuk {$mahasiswa->nama_lengkap} berhasil dibuat! (Username: {$username}, Password: {$password})");
    }

    public function resetPasswordPeserta(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $user = $mahasiswa->user;

        if (!$user) {
            return back()->with('error', 'Mahasiswa ini belum memiliki akun user.');
        }

        $request->validate([
            'new_password' => 'nullable|min:6',
        ]);

        $newPass = $request->filled('new_password') ? $request->new_password : 'mhs123';
        $user->update([
            'password' => Hash::make($newPass),
        ]);

        return back()->with('success', "Password akun peserta {$mahasiswa->nama_lengkap} berhasil di-reset menjadi: {$newPass}");
    }
}