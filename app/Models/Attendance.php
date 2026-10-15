<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';
    protected $primaryKey = 'id_attendance';

    protected $fillable = [
        'mahasiswa_id',
        'attendance_date',
        'check_in_at',
        'check_out_at',
        'check_in_status',
        'check_out_status',
        'work_status',
        'late_minutes',
        'total_work_minutes',
        'latitude_in',
        'longitude_in',
        'distance_in_meters',
        'accuracy_in',
        'latitude_out',
        'longitude_out',
        'distance_out_meters',
        'qr_token',
        'selfie_path',
        'selfie_out_path',
        'verification_method',
        'approval_status',
        'notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'late_minutes' => 'integer',
        'total_work_minutes' => 'integer',
        'distance_in_meters' => 'float',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id_mhs');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id_users');
    }

    public function correction()
    {
        return $this->hasOne(AttendanceCorrection::class, 'attendance_id', 'id_attendance');
    }
}
