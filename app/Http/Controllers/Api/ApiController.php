<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

/**
 * Dasar semua controller API mobile.
 * Format respons: { success, message, data, meta? }.
 * Tanggal dikirim 'Y-m-d', waktu dikirim ISO-8601 dengan offset WIB (+07:00).
 */
abstract class ApiController extends Controller
{
    protected function ok($data = null, ?string $message = null, array $meta = [], int $status = 200): JsonResponse
    {
        $body = ['success' => true, 'message' => $message, 'data' => $data];
        if ($meta) {
            $body['meta'] = $meta;
        }

        return response()->json($body, $status);
    }

    protected function fail(string $message, int $status = 422, array $extra = []): JsonResponse
    {
        return response()->json(array_merge(['success' => false, 'message' => $message], $extra), $status);
    }

    /**
     * Daftar ber-halaman: data = item yang sudah diformat, meta = info halaman.
     */
    protected function paginated(LengthAwarePaginator $paginator, callable $format, array $meta = [], ?string $message = null): JsonResponse
    {
        return $this->ok(
            collect($paginator->items())->map($format)->values(),
            $message,
            array_merge([
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ], $meta)
        );
    }

    protected function perPage($request, int $default): int
    {
        return max(1, min(50, (int) $request->input('per_page', $default)));
    }

    protected static function date($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    protected static function dateTime($value): ?string
    {
        return $value ? Carbon::parse($value)->timezone(config('app.timezone'))->toIso8601String() : null;
    }

    protected static function time($value): ?string
    {
        return $value ? substr((string) $value, 0, 5) : null;
    }

    protected static function fileUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return preg_match('#^https?://#', $path) ? $path : asset($path);
    }

    /**
     * Simpan unggahan ke public/uploads/{folder} dengan pola nama yang sama seperti web.
     */
    protected function storeUpload(UploadedFile $file, string $folder, string $prefix): string
    {
        $target = public_path('uploads/' . $folder);
        if (!File::exists($target)) {
            File::makeDirectory($target, 0755, true);
        }

        $fileName = $prefix . '_' . time() . '_' . substr(md5(uniqid('', true)), 0, 6) . '.' . static::ekstensiAman($file);
        $file->move($target, $fileName);

        return 'uploads/' . $folder . '/' . $fileName;
    }

    /** Ekstensi berkas yang boleh disimpan di folder publik. */
    protected const EKSTENSI_AMAN = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'doc', 'docx', 'txt'];

    /**
     * Ekstensi berdasarkan ISI berkas (bukan nama dari pengirim) dan hanya dari daftar aman,
     * agar berkas seperti "x.php" berisi PDF tidak tersimpan sebagai skrip yang dapat dieksekusi di public/.
     */
    protected static function ekstensiAman(UploadedFile $file): string
    {
        $ext = strtolower((string) $file->guessExtension());

        return in_array($ext, self::EKSTENSI_AMAN, true) ? $ext : 'bin';
    }

    /** Nama asli yang sudah dibersihkan + ekstensi aman (dipakai berkas pendaftaran). */
    protected static function namaAman(UploadedFile $file): string
    {
        $dasar = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        return substr($dasar ?: 'berkas', 0, 100) . '.' . static::ekstensiAman($file);
    }

    /**
     * Simpan gambar base64 (data:image/...) seperti selfie di web.
     */
    protected function storeBase64Image(string $dataUri, string $folder, string $prefix): ?string
    {
        if (!str_starts_with($dataUri, 'data:image')) {
            return null;
        }

        $target = public_path('uploads/' . $folder);
        if (!File::exists($target)) {
            File::makeDirectory($target, 0755, true);
        }

        $fileName = $prefix . '_' . time() . '.png';
        File::put($target . '/' . $fileName, base64_decode(substr($dataUri, strpos($dataUri, ',') + 1)));

        return 'uploads/' . $folder . '/' . $fileName;
    }

    protected static function userSummary($user): ?array
    {
        if (!$user) {
            return null;
        }

        return [
            'id' => $user->id_users,
            'nama_lengkap' => $user->nama_lengkap,
            'role' => $user->role,
        ];
    }
}
