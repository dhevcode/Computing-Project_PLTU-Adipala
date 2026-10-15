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
        return match ($type) {
            'personal_leave' => 'Cuti Pribadi',
            'sick_leave' => 'Izin Sakit',
            'academic_leave' => 'Izin Kegiatan Akademik',
            'official_permission' => 'Izin Dinas / Resmi',
            'emergency' => 'Keadaan Darurat',
            default => 'Lainnya',
        };
    }
}
