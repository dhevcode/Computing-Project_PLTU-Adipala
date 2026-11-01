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
        'pembimbing_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'surat_permohonan',
        'proposal',
        'cv',
        'surat_balasan',
        'alasan_penolakan',
        'catatan_admin',
        'verified_at',
        'verified_by',
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

    public function pembimbing()
    {
        return $this->belongsTo(User::class, 'pembimbing_id', 'id_users');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by', 'id_users');
    }

    public function sertifikat()
    {
        return $this->hasOne(
            Sertifikat::class,
            'pengajuan_id',
            'id_pengajuan'
        );
    }
}