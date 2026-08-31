@extends('layouts.app')

@section('title', 'SUS Questionnaire')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 bg-gradient-to-r from-emerald-700 to-teal-700 rounded-3xl p-8 text-white shadow-xl">
        <p class="text-emerald-100 text-sm font-bold uppercase tracking-wide mb-2">Usability Evaluation</p>
        <h1 class="text-3xl font-extrabold mb-3">System Usability Scale (SUS)</h1>
        <p class="text-emerald-50">Isi 10 pertanyaan berikut setelah mencoba fitur rekomendasi, provenance, IPFS, dan blockchain registry.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 font-semibold">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-red-700">
        <p class="font-bold mb-2">Mohon lengkapi jawaban.</p>
        <ul class="list-disc pl-5 text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('evaluation.sus.store') }}" method="POST" class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 space-y-6">
        @csrf
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Responden (opsional)</label>
                <input name="respondent_name" value="{{ old('respondent_name') }}" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: Responden 1">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Peran (opsional)</label>
                <input name="respondent_role" value="{{ old('respondent_role') }}" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: Pendaki / Admin / Reviewer">
            </div>
        </div>

        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4 text-sm text-slate-600">
            Skala: 1 = Sangat tidak setuju, 2 = Tidak setuju, 3 = Netral, 4 = Setuju, 5 = Sangat setuju.
        </div>

        @foreach($questions as $number => $question)
        <div class="rounded-2xl border border-slate-100 p-4">
            <p class="font-bold text-slate-800 mb-3">{{ $number }}. {{ $question }}</p>
            <div class="grid grid-cols-5 gap-2">
                @for($score = 1; $score <= 5; $score++)
                <label class="flex flex-col items-center gap-2 rounded-xl bg-slate-50 border border-slate-100 p-3 cursor-pointer hover:bg-emerald-50 hover:border-emerald-200">
                    <input type="radio" name="q{{ $number }}" value="{{ $score }}" @checked((int) old('q' . $number) === $score) required>
                    <span class="font-bold text-slate-700">{{ $score }}</span>
                </label>
                @endfor
            </div>
        </div>
        @endforeach

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Catatan (opsional)</label>
            <textarea name="notes" rows="4" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukan tambahan untuk usability sistem">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="w-full py-3 rounded-2xl bg-emerald-700 text-white font-extrabold hover:bg-emerald-800">
            Simpan Jawaban SUS
        </button>
    </form>
</div>
@endsection
