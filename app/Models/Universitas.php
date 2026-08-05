<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universitas extends Model
{
    use HasFactory;

    protected $table = 'universitas';
    protected $primaryKey = 'id_universitas';
    protected $fillable = [
        'nama_universitas',
    ];

    public function mahasiswa()
    {
        return $this->hasMany(
            Mahasiswa::class,
            'universitas_id',
            'id_universitas'
        );
    }
}