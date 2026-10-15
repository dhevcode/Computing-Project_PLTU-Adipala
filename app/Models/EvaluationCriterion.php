<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationCriterion extends Model
{
    use HasFactory;

    protected $table = 'evaluation_criteria';
    protected $primaryKey = 'id_criterion';

    protected $fillable = [
        'criterion_name',
        'description',
        'weight',
        'maximum_score',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'weight' => 'integer',
        'maximum_score' => 'integer',
    ];

    public function details()
    {
        return $this->hasMany(EvaluationDetail::class, 'criterion_id', 'id_criterion');
    }
}
