<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogbookComment extends Model
{
    use HasFactory;

    protected $table = 'logbook_comments';
    protected $primaryKey = 'id_comment';

    protected $fillable = [
        'logbook_id',
        'user_id',
        'comment',
    ];

    public function logbook()
    {
        return $this->belongsTo(DailyLogbook::class, 'logbook_id', 'id_logbook');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_users');
    }
}
