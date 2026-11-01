<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotLog extends Model
{
    use HasFactory;

    protected $table = 'chatbot_logs';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'faq_id',
        'user_id',
        'user_type',
        'pertanyaan',
        'session_id',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function faq()
    {
        return $this->belongsTo(ChatbotFaq::class, 'faq_id', 'id_faq');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_users');
    }
}
