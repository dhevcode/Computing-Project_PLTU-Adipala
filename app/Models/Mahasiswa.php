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
        'user_id',
        'nim',
        'nama_lengkap',
        'jenis_kelamin',
        'email',
        'no_telepon',
        'emergency_contact_name',
        'emergency_contact_phone',
        'universitas_id',
        'jurusan_id',
        'file_laporan',
    ];
    protected $casts = [
        'id_card_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_users');
    }

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

    public function latestPengajuan()
    {
        return $this->hasOne(PengajuanMagang::class, 'mahasiswa_id', 'id_mhs')->latestOfMany('id_pengajuan');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'mahasiswa_id', 'id_mhs')->orderBy('attendance_date', 'desc');
    }

    public function attendanceCorrections()
    {
        return $this->hasMany(AttendanceCorrection::class, 'mahasiswa_id', 'id_mhs')->orderBy('created_at', 'desc');
    }

    public function logbooks()
    {
        return $this->hasMany(DailyLogbook::class, 'mahasiswa_id', 'id_mhs')->orderBy('logbook_date', 'desc');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'mahasiswa_id', 'id_mhs')->orderBy('created_at', 'desc');
    }

    public function evaluations()
    {
        return $this->hasMany(DailyEvaluation::class, 'mahasiswa_id', 'id_mhs')->orderBy('evaluation_date', 'desc');
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'mahasiswa_id', 'id_mhs')->orderBy('created_at', 'desc');
    }

    public function sertifikat()
    {
        return $this->hasOne(
            Sertifikat::class,
            'mahasiswa_id',
            'id_mhs'
        )->latestOfMany('id_sertifikat');
    }
}