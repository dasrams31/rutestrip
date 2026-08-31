<?php
namespace App\Http\Controllers;

use App\Models\SusResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SusResponseController extends Controller
{
    public function create(): View
    {
        return view('evaluation.sus-form', [
            'questions' => $this->questions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(array_merge([
            'respondent_name' => 'nullable|string|max:255',
            'respondent_role' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ], collect(range(1, 10))->mapWithKeys(fn($i) => ['q' . $i => 'required|integer|min:1|max:5'])->all()));

        $score = SusResponse::calculateScore($validated);

        SusResponse::create(array_merge($validated, [
            'score' => $score,
            'submitted_at' => now(),
        ]));

        return redirect()->route('evaluation.sus.create')
            ->with('success', 'Terima kasih. Jawaban SUS tersimpan dengan skor ' . number_format($score, 2) . '.');
    }

    private function questions(): array
    {
        return [
            1 => 'Saya akan sering menggunakan sistem ini.',
            2 => 'Saya merasa sistem ini terlalu kompleks.',
            3 => 'Saya merasa sistem ini mudah digunakan.',
            4 => 'Saya membutuhkan bantuan teknis untuk menggunakan sistem ini.',
            5 => 'Saya merasa fitur-fitur sistem ini terintegrasi dengan baik.',
            6 => 'Saya merasa ada terlalu banyak inkonsistensi dalam sistem ini.',
            7 => 'Saya membayangkan kebanyakan orang akan cepat belajar menggunakan sistem ini.',
            8 => 'Saya merasa sistem ini rumit digunakan.',
            9 => 'Saya merasa percaya diri saat menggunakan sistem ini.',
            10 => 'Saya perlu belajar banyak hal sebelum dapat menggunakan sistem ini.',
        ];
    }
}
