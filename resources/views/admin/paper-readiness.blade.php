@extends('layouts.app')

@section('title', 'Paper Dataset Readiness')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex text-emerald-700 font-semibold hover:text-emerald-800 mb-4">Kembali ke Dashboard</a>
        <div class="bg-gradient-to-r from-indigo-800 to-emerald-800 rounded-3xl p-8 text-white shadow-xl">
            <p class="text-emerald-100 text-sm font-bold uppercase tracking-wide mb-2">Paper Evidence</p>
            <h1 class="text-3xl md:text-4xl font-extrabold mb-3">Dataset Readiness Checker</h1>
            <p class="text-emerald-50 max-w-3xl">Checklist kesiapan data dan evidence untuk eksperimen paper: dataset rute, SBERT, GPX provenance, IPFS, blockchain, Precision@K, black box testing, dan SUS.</p>
        </div>
    </div>

    <div class="grid md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow p-5 md:col-span-2">
            <p class="text-sm text-slate-500 mb-1">Overall Status</p>
            <p class="text-3xl font-extrabold {{ $paperReadiness['status'] === 'ready' ? 'text-emerald-700' : 'text-amber-700' }}">{{ strtoupper(str_replace('_', ' ', $paperReadiness['status'])) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow p-5">
            <p class="text-sm text-slate-500 mb-1">Score</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ number_format($paperReadiness['score'], 2) }}%</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow p-5">
            <p class="text-sm text-slate-500 mb-1">Passed</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $paperReadiness['passed'] }}/{{ $paperReadiness['total'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Readiness Criteria</h2>
                <p class="text-sm text-slate-500 mt-1">Gunakan item TODO sebagai daftar kerja sebelum finalisasi tabel hasil paper.</p>
            </div>
            <a href="{{ route('admin.paper-readiness.export') }}" class="inline-flex px-5 py-3 rounded-2xl bg-indigo-700 text-white font-bold hover:bg-indigo-800">Download Readiness CSV</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-slate-200">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-3 text-left border-b">Criterion</th>
                        <th class="px-3 py-3 text-left border-b">Status</th>
                        <th class="px-3 py-3 text-left border-b">Current</th>
                        <th class="px-3 py-3 text-left border-b">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($paperReadiness['items'] as $item)
                    <tr>
                        <td class="px-3 py-3 font-bold text-slate-800">{{ $item['name'] }}</td>
                        <td class="px-3 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item['ready'] ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $item['ready'] ? 'READY' : 'TODO' }}</span>
                        </td>
                        <td class="px-3 py-3">{{ $item['current'] }}</td>
                        <td class="px-3 py-3 text-slate-600">{{ $item['notes'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6">
        <h2 class="text-lg font-bold text-amber-900 mb-2">Catatan Interpretasi</h2>
        <p class="text-sm text-amber-800 leading-relaxed">Checklist ini adalah readiness operasional untuk evidence sistem. Kualitas metodologi paper tetap perlu dijelaskan di naskah: sumber dataset, definisi ground truth, skenario black box, profil responden SUS, dan batasan eksperimen.</p>
    </div>
</div>
@endsection
