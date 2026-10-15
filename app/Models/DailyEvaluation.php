<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyEvaluation extends Model
{
    use HasFactory;

    protected $table = 'daily_evaluations';
    protected $primaryKey = 'id_evaluation';

    protected $fillable = [
        'mahasiswa_id',
        'evaluation_date',
        'total_score',
        'participant_note',
        'internal_note',
        'evaluator_id',
        'status',
        'published_at',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'published_at' => 'datetime',
        'total_score' => 'float',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id_mhs');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id', 'id_users');
    }

    public function details()
    {
        return $this->hasMany(EvaluationDetail::class, 'daily_evaluation_id', 'id_evaluation');
    }
}
