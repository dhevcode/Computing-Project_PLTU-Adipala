<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

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

    public function isPembimbing(): bool
    {
        $role = strtolower(trim($this->role ?? ''));
        return in_array($role, ['pembimbing', 'pembimbing lapangan']);
    }

    public function bimbinganPengajuan()
    {
        return $this->hasMany(PengajuanMagang::class, 'pembimbing_id', 'id_users');
    }

    public function getBimbinganMahasiswaIds(): array
    {
        $userName = trim(strtolower($this->nama_lengkap ?? ''));

        return PengajuanMagang::where(function ($q) use ($userName) {
                $q->where('pembimbing_id', $this->id_users);
                if (!empty($userName)) {
                    $q->orWhereRaw('LOWER(TRIM(nama_pembimbing)) = ?', [$userName]);
                }
            })
            ->pluck('mahasiswa_id')
            ->unique()
            ->toArray();
    }

    public function getBimbinganPengajuan()
    {
        $userName = trim(strtolower($this->nama_lengkap ?? ''));

        return PengajuanMagang::with(['mahasiswa.universitas', 'mahasiswa.jurusan', 'divisi'])
            ->where(function ($q) use ($userName) {
                $q->where('pembimbing_id', $this->id_users);
                if (!empty($userName)) {
                    $q->orWhereRaw('LOWER(TRIM(nama_pembimbing)) = ?', [$userName]);
                }
            })
            ->latest('id_pengajuan')
            ->get();
    }
}