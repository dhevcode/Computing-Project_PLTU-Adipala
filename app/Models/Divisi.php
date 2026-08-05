<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';

    protected $fillable = [
        'nama_divisi'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_divisi', 'id_divisi');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(
            PengajuanMagang::class,
            'divisi_id',
            'id_divisi'
        );
    }
}