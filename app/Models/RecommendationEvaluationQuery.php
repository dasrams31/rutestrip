<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationEvaluationQuery extends Model
{
    protected $fillable = [
        'query',
        'query_embedding',
        'relevant_route_ids',
        'notes',
    ];

    protected $casts = [
        'query_embedding' => 'array',
        'relevant_route_ids' => 'array',
    ];
}
