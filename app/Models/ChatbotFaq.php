<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotFaq extends Model
{
    use HasFactory;

    protected $table = 'chatbot_faqs';

    protected $primaryKey = 'id_faq';

    protected $fillable = [
        'kode',
        'pertanyaan',
        'jawaban',
        'kategori',
        'role_access',
        'urutan',
        'is_active',
        'click_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
        'click_count' => 'integer',
    ];

    public function logs()
    {
        return $this->hasMany(ChatbotLog::class, 'faq_id', 'id_faq');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope filter FAQ berdasarkan role akses.
     * - Guest: melihat FAQ dengan role_access 'all' dan 'guest' (FAQ-01 s.d. FAQ-06, FAQ-08, FAQ-10)
     * - Student: melihat seluruh FAQ Guest ditambah 'student' (FAQ-07 status & FAQ-09 aturan)
     */
    public function scopeForRole($query, $role = 'guest')
    {
        if ($role === 'student') {
            return $query->whereIn('role_access', ['all', 'guest', 'student']);
        }

        // Default role: guest
        return $query->whereIn('role_access', ['all', 'guest']);
    }

    /**
     * Label representatif untuk role akses.
     */
    public function getRoleLabelAttribute()
    {
        return match ($this->role_access) {
            'all' => 'Guest & Student',
            'guest' => 'Hanya Guest (Publik)',
            'student' => 'Hanya Student (Peserta)',
            default => ucfirst($this->role_access),
        };
    }
}
