<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaints';
    protected $primaryKey = 'id_complaint';

    protected $fillable = [
        'mahasiswa_id',
        'complaint_number',
        'category',
        'title',
        'description',
        'priority',
        'confidentiality',
        'attachment_path',
        'status',
        'assigned_to',
        'submitted_at',
        'resolved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id_mhs');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id_users');
    }

    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class, 'complaint_id', 'id_complaint')->orderBy('created_at', 'asc');
    }

    public static function generateComplaintNumber()
    {
        $yearMonth = date('Ym');
        $count = self::where('complaint_number', 'LIKE', "ADU-{$yearMonth}-%")->count() + 1;
        return sprintf("ADU-%s-%03d", $yearMonth, $count);
    }
}
