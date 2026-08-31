<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationResult extends Model
{
    protected $fillable = [
        'metric_name',
        'metric_value',
        'dataset_size',
        'notes',
        'captured_at',
    ];

    protected $casts = [
        'metric_value' => 'float',
        'dataset_size' => 'integer',
        'captured_at' => 'datetime',
    ];
}
