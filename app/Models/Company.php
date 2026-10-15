<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $primaryKey = 'id_company';

    protected $fillable = [
        'name',
        'description',
        'industry',
        'address',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'logo_path',
        'work_start_time',
        'work_end_time',
        'timezone',
        'attendance_radius_meters',
        'is_active',
    ];

    public function policies()
    {
        return $this->hasOne(AttendancePolicy::class, 'company_id', 'id_company');
    }
}
