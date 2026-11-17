<?php

namespace App\Services;

use App\Mail\SertifikatMail;
use App\Models\AppNotification;
use App\Models\Company;
use App\Models\DailyEvaluation;
use App\Models\Mahasiswa;
use App\Models\Sertifikat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Penerbitan sertifikat penyelesaian magang dari Penilaian Akhir (template 2 halaman, A4 landscape):
 * halaman 1 sertifikat + halaman 2 daftar nilai per aspek. Dipakai halaman web admin dan API mobile.
 *
 * Placeholder template → sumber data:
 *  {{KODE_SERTIFIKAT}}            nomor_sertifikat (otomatis NNN/SERT-PKL/UBP ADIPALA/TAHUN, bisa diubah admin)
 *  {{NAMA_PESERTA}}, {{NIM}}      mahasiswa
 *  {{NAMA_INSTANSI}}              companies.name (penyelenggara)
 *  {{TANGGAL_MULAI/SELESAI}}      pengajuan magang terbaru
 *  {{PREDIKAT}}, {{NILAI_AKHIR}}  penilaian akhir terbit (grade + predikat, total_score)
 *  {{ASPEK_n}}, {{NILAI_n}}, {{HURUF_n}}  detail penilaian per kriteria
 *  {{KOTA_PENERBITAN}}, {{TANGGAL_TERBIT}}, {{JABATAN/NAMA/NIP_PENANDATANGAN}}  diisi admin saat menerbitkan
 *  {{ALAMAT_WEBSITE_INSTANSI}}    companies.website
 */
class SertifikatService
{
    /** Skala predikat — sama dengan DailyEvaluation::grade / predicate. */
    public const SKALA = [
        ['rentang' => '85 – 100', 'grade' => 'A', 'keterangan' => 'Sangat Baik'],
        ['rentang' => '75 – 84,99', 'grade' => 'B', 'keterangan' => 'Baik'],
        ['rentang' => '60 – 74,99', 'grade' => 'C', 'keterangan' => 'Cukup'],
        ['rentang' => '< 60', 'grade' => 'D', 'keterangan' => 'Kurang'],
    ];

    public static function grade(float $nilai): string
    {
        return match (true) {
            $nilai >= 85 => 'A',
            $nilai >= 75 => 'B',
            $nilai >= 60 => 'C',
            default => 'D',
        };
    }

    public static function predikat(float $nilai): string
    {
        return match (true) {
            $nilai >= 85 => 'Sangat Baik',
            $nilai >= 75 => 'Baik',
            $nilai >= 60 => 'Cukup',
            default => 'Kurang',
        };
    }

    public function penilaianAkhir(Mahasiswa $mhs): ?DailyEvaluation
    {
        return DailyEvaluation::where('mahasiswa_id', $mhs->id_mhs)
            ->where('status', 'published')
            ->with(['details.criterion'])
            ->latest('updated_at')
            ->first();
    }

    /** Pesan bila sertifikat belum boleh diterbitkan (syarat sama dengan unggah manual + nilai akhir terbit). */
    public function alasanBelumBisa(Mahasiswa $mhs): ?string
    {
        if (!$mhs->latestPengajuan) {
            return 'Mahasiswa belum memiliki data pengajuan magang.';
        }
        if (!$this->penilaianAkhir($mhs)) {
            return 'Penilaian akhir magang belum dipublikasikan.';
        }
        if (!$mhs->file_laporan) {
            return 'Laporan akhir mahasiswa belum tersedia.';
        }
        if (!$mhs->id_card_verified) {
            return 'ID Card mahasiswa belum diverifikasi.';
        }

        return null;
    }

    public function sertifikatTerakhir(Mahasiswa $mhs): ?Sertifikat
    {
        return Sertifikat::where('mahasiswa_id', $mhs->id_mhs)->latest('id_sertifikat')->first();
    }

    /** Usulan nomor: urutan berikut dari sertifikat terbitan sistem tahun ini. */
    public function nomorBerikut(?Carbon $tanggal = null): string
    {
        $tahun = ($tanggal ?? now())->year;
        $terpakai = Sertifikat::whereNotNull('nomor_sertifikat')
            ->where('nomor_sertifikat', 'like', "%/{$tahun}")
            ->pluck('nomor_sertifikat')
            ->map(fn ($n) => (int) strtok($n, '/'))
            ->max() ?? 0;

        return sprintf('%03d/SERT-PKL/UBP ADIPALA/%d', $terpakai + 1, $tahun);
    }

    /** Isian awal form: nomor usulan + penandatangan terakhir yang dipakai admin. */
    public function isianAwal(Mahasiswa $mhs): array
    {
        $terakhirGenerate = Sertifikat::where('sumber', 'generate')->whereNotNull('penandatangan_nama')->latest('id_sertifikat')->first();
        $milikMhs = $this->sertifikatTerakhir($mhs);
        $punyaNomor = $milikMhs && $milikMhs->sumber === 'generate' && $milikMhs->nomor_sertifikat;

        return [
            'nomor_sertifikat' => $punyaNomor ? $milikMhs->nomor_sertifikat : $this->nomorBerikut(),
            'tanggal_terbit' => now()->toDateString(),
            'kota_terbit' => $terakhirGenerate->kota_terbit ?? 'Cilacap',
            'penandatangan_jabatan' => $terakhirGenerate->penandatangan_jabatan ?? 'General Manager',
            'penandatangan_nama' => $terakhirGenerate->penandatangan_nama ?? '',
            'penandatangan_identitas' => $terakhirGenerate->penandatangan_identitas ?? '',
        ];
    }

    public static function aturanValidasi(): array
    {
        return [
            'nomor_sertifikat' => 'required|string|max:100',
            'tanggal_terbit' => 'required|date',
            'kota_terbit' => 'required|string|max:100',
            'penandatangan_jabatan' => 'required|string|max:150',
            'penandatangan_nama' => 'required|string|max:150',
            'penandatangan_identitas' => 'nullable|string|max:150',
        ];
    }

    public static function pesanValidasi(): array
    {
        return [
            'nomor_sertifikat.required' => 'Nomor sertifikat wajib diisi.',
            'tanggal_terbit.required' => 'Tanggal terbit wajib diisi.',
            'kota_terbit.required' => 'Kota penerbitan wajib diisi.',
            'penandatangan_jabatan.required' => 'Jabatan penandatangan wajib diisi.',
            'penandatangan_nama.required' => 'Nama penandatangan wajib diisi.',
        ];
    }

    /** Data untuk view sertifikat.pdf. */
    public function dataTemplate(Mahasiswa $mhs, array $isian, bool $draf = false): array
    {
        $mhs->loadMissing(['universitas', 'jurusan', 'latestPengajuan.divisi']);
        $company = Company::first();
        $evaluasi = $this->penilaianAkhir($mhs);
        $nilai = (float) ($evaluasi->total_score ?? 0);
        $pengajuan = $mhs->latestPengajuan;
        $tgl = fn ($v) => $v ? Carbon::parse($v)->locale('id')->translatedFormat('d F Y') : '-';

        $aspek = collect($evaluasi?->details ?? [])->map(fn ($d) => [
            'nama' => $d->criterion->criterion_name ?? '-',
            'nilai' => (float) $d->score,
            'huruf' => static::grade((float) $d->score),
        ])->values()->all();

        $logo = public_path('assets/image/logo-pln.png');

        return [
            'draf' => $draf,
            'kode' => $isian['nomor_sertifikat'] ?? '',
            'nama' => $mhs->nama_lengkap,
            'nim' => $mhs->nim,
            'universitas' => $mhs->universitas->nama_universitas ?? null,
            'instansi' => $company->name ?? 'PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala',
            'website' => preg_replace('#^https?://#', '', $company->website ?? 'plnindonesiapower.co.id'),
            'tanggal_mulai' => $tgl($pengajuan?->tanggal_mulai),
            'tanggal_selesai' => $tgl($pengajuan?->tanggal_selesai),
            'divisi' => $pengajuan?->divisi?->nama_divisi,
            'nilai_akhir' => $nilai,
            'grade' => static::grade($nilai),
            'predikat' => static::predikat($nilai),
            'aspek' => $aspek,
            'skala' => self::SKALA,
            'kota' => $isian['kota_terbit'] ?? 'Cilacap',
            'tanggal_terbit' => $tgl($isian['tanggal_terbit'] ?? now()),
            'jabatan' => $isian['penandatangan_jabatan'] ?? '',
            'penandatangan' => $isian['penandatangan_nama'] ?? '',
            'identitas' => $isian['penandatangan_identitas'] ?? '',
            // dompdf butuh ekstensi GD untuk PNG; tanpa GD logo diganti teks agar penerbitan tidak gagal
            'logo' => is_file($logo) && function_exists('imagecreatefrompng')
                ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo))
                : null,
        ];
    }

    public function pdf(Mahasiswa $mhs, array $isian, bool $draf = false)
    {
        return Pdf::loadView('sertifikat.pdf', $this->dataTemplate($mhs, $isian, $draf))
            ->setPaper('a4', 'landscape')
            ->setOption('isFontSubsettingEnabled', true); // PDF lebih ringan untuk lampiran email
    }

    public static function namaFile(Mahasiswa $mhs): string
    {
        return 'Sertifikat_Magang_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $mhs->nim ?: 'Peserta') . '.pdf';
    }

    /**
     * Buat PDF, simpan ke menu Sertifikat (menggantikan file lama), kirim email & notifikasi ke peserta.
     * Mengembalikan [Sertifikat, bool emailTerkirim].
     */
    public function terbitkan(Mahasiswa $mhs, array $isian, ?int $olehUserId, bool $kirimEmail = true): array
    {
        $pengajuan = $mhs->latestPengajuan;
        $sertifikat = $this->sertifikatTerakhir($mhs) ?? new Sertifikat(['mahasiswa_id' => $mhs->id_mhs]);

        $folder = public_path('uploads/sertifikat_mhs');
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
        if ($sertifikat->file_sertifikat) {
            $lama = public_path('uploads/sertifikat_mhs/' . basename($sertifikat->file_sertifikat));
            if (is_file($lama)) {
                @unlink($lama);
            }
        }

        $nama = 'sertifikat_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $mhs->nim ?: 'mhs') . '_' . time() . '.pdf';
        $this->pdf($mhs, $isian)->save($folder . DIRECTORY_SEPARATOR . $nama);

        $sertifikat->fill([
            'mahasiswa_id' => $mhs->id_mhs,
            'pengajuan_id' => $pengajuan->id_pengajuan,
            'file_sertifikat' => 'uploads/sertifikat_mhs/' . $nama,
            'status' => 'Diterbitkan',
            'sumber' => 'generate',
            'tanggal_terbit' => Carbon::parse($isian['tanggal_terbit'])->toDateString(),
            'nomor_sertifikat' => $isian['nomor_sertifikat'],
            'kota_terbit' => $isian['kota_terbit'],
            'penandatangan_jabatan' => $isian['penandatangan_jabatan'],
            'penandatangan_nama' => $isian['penandatangan_nama'],
            'penandatangan_identitas' => $isian['penandatangan_identitas'] ?? null,
            'uploaded_by' => $olehUserId,
            'uploaded_at' => now(),
        ]);
        $sertifikat->save();

        if ($mhs->user_id) {
            try {
                AppNotification::send(
                    $mhs->user_id,
                    'Sertifikat Magang Telah Diterbitkan',
                    'Selamat! Sertifikat penyelesaian magang Anda (No. ' . $isian['nomor_sertifikat'] . ') telah diterbitkan beserta daftar nilai. Silakan cek email Anda.',
                    'success'
                );
            } catch (\Throwable $e) {
                // notifikasi gagal tidak membatalkan penerbitan
            }
        }

        $terkirim = false;
        if ($kirimEmail && $mhs->email) {
            try {
                Mail::to($mhs->email)->send(new SertifikatMail($mhs, public_path($sertifikat->file_sertifikat)));
                $terkirim = true;
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim email sertifikat: ' . $e->getMessage());
            }
        }

        return [$sertifikat, $terkirim];
    }
}
