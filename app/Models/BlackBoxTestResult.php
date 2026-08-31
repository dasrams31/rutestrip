<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlackBoxTestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_case_id',
        'feature',
        'scenario',
        'expected_result',
        'actual_result',
        'status',
        'notes',
        'tested_at',
    ];

    protected $casts = [
        'tested_at' => 'datetime',
    ];
}
