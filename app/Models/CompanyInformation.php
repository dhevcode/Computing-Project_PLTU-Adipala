<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInformation extends Model
{
    use HasFactory;

    protected $table = 'company_information';
    protected $primaryKey = 'id_info';

    protected $fillable = [
        'title',
        'category',
        'content',
        'attachment_path',
        'is_published',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_users');
    }

    public static function getCategoryLabel($category)
    {
        return match ($category) {
            'announcement' => 'Pengumuman',
            'work_rule' => 'Tata Tertib',
            'safety' => 'Prosedur K3',
            'contact' => 'Kontak Penting',
            'document' => 'Dokumen & Panduan',
            default => 'Umum',
        };
    }
}
