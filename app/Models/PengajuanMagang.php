<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanMagang extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_magang';
    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'mahasiswa_id',
        'divisi_id',
        'nama_pembimbing',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'surat_permohonan',
        'created_by'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id_mhs');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id', 'id_divisi');
    }
}