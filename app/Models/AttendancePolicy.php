<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendancePolicy extends Model
{
    use HasFactory;

    protected $table = 'attendance_policies';
    protected $primaryKey = 'id_policy';

    protected $fillable = [
        'company_id',
        'check_in_start',
        'check_in_deadline',
        'check_out_start',
        'check_out_deadline',
        'work_days',
        'allowed_radius_meters',
        'location_accuracy_limit',
        'late_tolerance_minutes',
        'attendance_method',
        'require_selfie',
        'allow_manual_correction',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id_company');
    }
}
