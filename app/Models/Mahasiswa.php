<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mhs';

    protected $fillable = [
        'nim',
        'nama_lengkap',
        'email',
        'no_telepon',
        'universitas_id',
        'jurusan_id',
    ];

    public function universitas()
    {
        return $this->belongsTo(Universitas::class, 'universitas_id', 'id_universitas');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'id_jurusan');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagang::class, 'mahasiswa_id', 'id_mhs');
    }
}