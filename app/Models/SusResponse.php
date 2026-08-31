<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SusResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'respondent_name',
        'respondent_role',
        'q1',
        'q2',
        'q3',
        'q4',
        'q5',
        'q6',
        'q7',
        'q8',
        'q9',
        'q10',
        'score',
        'notes',
        'submitted_at',
    ];

    protected $casts = [
        'score' => 'float',
        'submitted_at' => 'datetime',
    ];

    public static function calculateScore(array $answers): float
    {
        $total = 0;

        for ($i = 1; $i <= 10; $i++) {
            $value = (int) ($answers['q' . $i] ?? 0);
            $total += $i % 2 === 1 ? $value - 1 : 5 - $value;
        }

        return round($total * 2.5, 2);
    }
}
