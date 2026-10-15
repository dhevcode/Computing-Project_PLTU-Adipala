<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationDetail extends Model
{
    use HasFactory;

    protected $table = 'evaluation_details';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'daily_evaluation_id',
        'criterion_id',
        'score',
        'notes',
    ];

    protected $casts = [
        'score' => 'float',
    ];

    public function evaluation()
    {
        return $this->belongsTo(DailyEvaluation::class, 'daily_evaluation_id', 'id_evaluation');
    }

    public function criterion()
    {
        return $this->belongsTo(EvaluationCriterion::class, 'criterion_id', 'id_criterion');
    }
}
