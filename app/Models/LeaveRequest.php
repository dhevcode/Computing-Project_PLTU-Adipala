<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $table = 'leave_requests';
    protected $primaryKey = 'id_leave';

    protected $fillable = [
        'mahasiswa_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'emergency_contact',
        'attachment_path',
        'status',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'review_note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id_mhs');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id_users');
    }

    public static function getLeaveTypeLabel($type)
    {
        return match (strtolower(trim((string)$type))) {
            'personal_leave', 'cuti', 'cuti pribadi' => 'Cuti Pribadi',
            'sick_leave', 'sakit', 'izin sakit' => 'Izin Sakit',
            'academic_leave', 'izin', 'akademik', 'izin akademik' => 'Izin Kegiatan Akademik',
            'official_permission', 'dinas', 'izin dinas' => 'Izin Dinas / Resmi',
            'emergency', 'darurat', 'keadaan darurat' => 'Keadaan Darurat',
            default => !empty($type) ? ucwords(str_replace('_', ' ', $type)) : 'Cuti / Izin',
        };
    }
}
