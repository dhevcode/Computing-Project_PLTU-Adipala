<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $table = 'sertifikat';

    protected $primaryKey = 'id_sertifikat';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'mahasiswa_id',
        'pengajuan_id',
        'file_sertifikat',
        'status',
        'tanggal_terbit',
        'uploaded_by',
        'uploaded_at',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'uploaded_at' => 'datetime',
    ];

    /**
     * Relasi ke mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(
            Mahasiswa::class,
            'mahasiswa_id',
            'id_mhs'
        );
    }

    /**
     * Relasi ke pengajuan magang
     */
    public function pengajuan()
    {
        return $this->belongsTo(
            PengajuanMagang::class,
            'pengajuan_id',
            'id_pengajuan'
        );
    }

    /**
     * Relasi ke user/admin yang mengupload
     */
    public function uploader()
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by',
            'id_users'
        );
    }
}