<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_users'; // Custom primary key

    protected $fillable = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'id_divisi', // <-- PERBAIKAN: Wajib ditambahkan di sini
        'id_mhs',
        'phone',
        'role',
        'is_active',
        'created_by',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Relasi ke Divisi
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }

    // Relasi ke Mahasiswa (jika role Peserta)
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mhs', 'id_mhs');
    }

    // Relasi ke Notifikasi
    public function appNotifications()
    {
        return $this->hasMany(AppNotification::class, 'user_id', 'id_users')->orderBy('created_at', 'desc');
    }

    public function unreadNotificationsCount()
    {
        return $this->appNotifications()->where('is_read', false)->count();
    }

    public function sertifikatUploaded()
    {
        return $this->hasMany(
            Sertifikat::class,
            'uploaded_by',
            'id_users'
        );
    }
}