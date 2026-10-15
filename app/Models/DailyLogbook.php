<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLogbook extends Model
{
    use HasFactory;

    protected $table = 'daily_logbooks';
    protected $primaryKey = 'id_logbook';

    protected $fillable = [
        'mahasiswa_id',
        'logbook_date',
        'title',
        'start_time',
        'end_time',
        'activity',
        'result',
        'obstacle',
        'solution',
        'next_plan',
        'attachment_path',
        'attachment_name',
        'status',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'review_note',
    ];

    protected $casts = [
        'logbook_date' => 'date',
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

    public function comments()
    {
        return $this->hasMany(LogbookComment::class, 'logbook_id', 'id_logbook')->orderBy('created_at', 'asc');
    }
}
