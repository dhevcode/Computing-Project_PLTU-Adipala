<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintResponse extends Model
{
    use HasFactory;

    protected $table = 'complaint_responses';
    protected $primaryKey = 'id_response';

    protected $fillable = [
        'complaint_id',
        'user_id',
        'message',
        'is_internal_note',
    ];

    protected $casts = [
        'is_internal_note' => 'boolean',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'id_complaint');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_users');
    }
}
