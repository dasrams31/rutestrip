@extends('layouts.app')

@section('title', 'Evaluasi Provenance GPX')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 mb-4 font-medium">
            ← Kembali ke Admin Dashboard
        </a>
        <div class="bg-gradient-to-r from-emerald-700 to-teal-700 rounded-3xl p-8 text-white shadow-xl">
            <p class="text-emerald-100 text-sm font-semibold mb-2">Paper Evaluation Module</p>
            <h1 class="text-3xl md:text-4xl font-extrabold mb-3">Evaluasi GPX Provenance</h1>
            <p class="text-emerald-50 max-w-3xl">
                Ringkasan metrik version control dan integrity verification untuk kebutuhan tabel hasil paper SINTA 3.
            </p>
            <div class="flex flex-wrap gap-3 mt-5">
                <a href="{{ route('admin.evaluation.provenance.export-summary') }}" class="px-5 py-3 bg-white text-emerald-700 rounded-2xl font-bold shadow-lg hover:bg-emerald-50 transition-colors">
                    Export Summary CSV
                </a>
                <a href="{{ route('admin.evaluation.provenance.export-versions') }}" class="px-5 py-3 bg-emerald-900/40 text-white rounded-2xl font-bold border border-white/30 hover:bg-emerald-900/60 transition-colors">
                    Export GPX Versions CSV
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($metrics as $name => $metric)
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <p class="text-sm text-slate-500 mb-1">{{ $name }}</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $metric['value'] }}</p>
            <p class="text-xs text-slate-500 mt-3 leading-relaxed">{{ $metric['notes'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Daftar GPX Versions</h2>
                    <p class="text-sm text-slate-500">Data utama untuk tabel versi GPX.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Route</th>
                            <th class="px-4 py-3 text-left">Version</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Active</th>
                            <th class="px-4 py-3 text-left">Hash</th>
                            <th class="px-4 py-3 text-left">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($versions as $version)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ Str::limit($version->route?->name, 28) }}</td>
                            <td class="px-4 py-3">v{{ $version->version_number }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-lg text-xs font-bold
                                    @if($version->verification_status === 'verified') bg-emerald-100 text-emerald-700
                                    @elseif($version->verification_status === 'invalid') bg-red-100 text-red-700
                                    @else bg-amber-100 text-amber-700
                                    @endif">
                                    {{ strtoupper($version->verification_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $version->is_active ? 'yes' : 'no' }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $version->short_hash }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $version->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($versions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $versions->links() }}
            </div>
            @endif
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="text-xl font-bold text-slate-800">Log Verifikasi Terbaru</h2>
                <p class="text-sm text-slate-500">Validasi hash lokal terbaru.</p>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentLogs as $log)
                <div class="p-4">
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <span class="px-2 py-1 rounded-lg text-xs font-bold
                            @if($log->status === 'verified') bg-emerald-100 text-emerald-700
                            @elseif($log->status === 'invalid') bg-red-100 text-red-700
                            @else bg-amber-100 text-amber-700
                            @endif">
                            {{ strtoupper($log->status) }}
                        </span>
                        <span class="text-xs text-slate-400">{{ $log->verified_at->format('d M Y H:i') }}</span>
                    </div>
                    <p class="font-semibold text-slate-800 text-sm">{{ Str::limit($log->gpxVersion?->route?->name, 34) }}</p>
                    <p class="text-xs text-slate-500 mt-1">v{{ $log->gpxVersion?->version_number }} - {{ $log->message }}</p>
                </div>
                @empty
                <div class="p-6 text-center text-slate-500">Belum ada log verifikasi.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
