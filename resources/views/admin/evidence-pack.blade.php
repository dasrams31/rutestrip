@extends('layouts.app')

@section('title', 'Paper Evidence Pack')

@push('styles')
<style>
@media print {
    nav, .no-print { display: none !important; }
    body { background: white !important; }
    .print-card { box-shadow: none !important; border-color: #cbd5e1 !important; break-inside: avoid; }
}
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex flex-wrap items-center justify-between gap-3 no-print mb-4">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex text-emerald-700 font-semibold hover:text-emerald-800">Kembali ke Dashboard</a>
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800">Print Evidence Pack</button>
        </div>
        <div class="bg-gradient-to-r from-slate-900 to-orange-700 rounded-3xl p-8 text-white shadow-xl">
            <p class="text-orange-100 text-sm font-bold uppercase tracking-wide mb-2">Paper Evidence</p>
            <h1 class="text-3xl md:text-4xl font-extrabold mb-3">Evidence Pack Provenance</h1>
            <p class="text-orange-50 max-w-3xl">Ringkasan bukti sistem untuk paper: CID IPFS, Sepolia transaction, contract address, log verifikasi terbaru, dan quick download CSV.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        @foreach($metrics as $label => $value)
        <div class="bg-white rounded-2xl border border-slate-100 shadow p-5 print-card">
            <p class="text-sm text-slate-500 mb-1">{{ str_replace('_', ' ', strtoupper($label)) }}</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $value }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow p-6 print-card">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Latest IPFS Evidence</h2>
            @if($latestIpfs)
            <div class="space-y-3 text-sm">
                <div><span class="block text-slate-500">Route</span><span class="font-bold text-slate-800">{{ $latestIpfs->route?->name }} v{{ $latestIpfs->version_number }}</span></div>
                <div><span class="block text-slate-500">CID</span><code class="text-xs break-all text-slate-700">{{ $latestIpfs->ipfs_cid }}</code></div>
                <div><span class="block text-slate-500">IPFS Status</span><span class="font-bold text-slate-800">{{ strtoupper(str_replace('_', ' ', $latestIpfs->ipfs_status ?? 'pending_ipfs')) }}</span></div>
                <div><span class="block text-slate-500">Status Meaning</span><p class="text-slate-700">{{ $latestIpfs->ipfs_status_explanation }}</p></div>
                <div><span class="block text-slate-500">Gateway</span><a href="{{ $latestIpfs->ipfs_url }}" target="_blank" rel="noopener" class="text-emerald-700 font-bold break-all hover:text-emerald-800">{{ $latestIpfs->ipfs_url }}</a></div>
                <div><span class="block text-slate-500">SHA-256</span><code class="text-xs break-all text-slate-700">{{ $latestIpfs->file_hash }}</code></div>
                <a href="{{ route('routes.provenance.download-version', [$latestIpfs->route, $latestIpfs]) }}" class="inline-flex px-4 py-2 rounded-xl bg-blue-50 text-blue-700 font-bold hover:bg-blue-100 no-print">Download GPX Evidence</a>
            </div>
            @else
            <p class="text-slate-500">Belum ada versi dengan CID IPFS.</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow p-6 print-card">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Latest Blockchain Evidence</h2>
            @if($latestRegistered)
            <div class="space-y-3 text-sm">
                <div><span class="block text-slate-500">Route</span><span class="font-bold text-slate-800">{{ $latestRegistered->route?->name }} v{{ $latestRegistered->version_number }}</span></div>
                <div><span class="block text-slate-500">Contract</span><a href="{{ $etherscanBaseUrl }}/address/{{ $latestRegistered->blockchain_contract_address }}" target="_blank" rel="noopener" class="text-emerald-700 font-bold break-all hover:text-emerald-800">{{ $latestRegistered->blockchain_contract_address }}</a></div>
                <div><span class="block text-slate-500">Transaction</span><a href="{{ $etherscanBaseUrl }}/tx/{{ $latestRegistered->blockchain_tx_hash }}" target="_blank" rel="noopener" class="text-emerald-700 font-bold break-all hover:text-emerald-800">{{ $latestRegistered->blockchain_tx_hash }}</a></div>
                <div><span class="block text-slate-500">Blockchain Status</span><span class="font-bold text-slate-800">{{ strtoupper(str_replace('_', ' ', $latestRegistered->blockchain_status ?? 'pending_blockchain')) }}</span></div>
                <div><span class="block text-slate-500">Status Meaning</span><p class="text-slate-700">{{ $latestRegistered->blockchain_status_explanation }}</p></div>
                <div><span class="block text-slate-500">Registered By</span><code class="text-xs break-all text-slate-700">{{ $latestRegistered->blockchain_registered_by }}</code></div>
            </div>
            @else
            <p class="text-slate-500">Belum ada versi yang registered ke blockchain.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow p-6 mb-8 print-card">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Ready for Paper Checklist</h2>
        <div class="grid md:grid-cols-2 gap-3">
            @foreach($readyChecklist as $label => $ready)
            <div class="flex items-center gap-3 rounded-xl p-3 {{ $ready ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                <span class="font-black">{{ $ready ? 'READY' : 'TODO' }}</span>
                <span class="text-sm font-semibold">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow p-6 mb-8 print-card">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Dataset Readiness Checker</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $paperReadiness['passed'] }}/{{ $paperReadiness['total'] }} kriteria terpenuhi ({{ number_format($paperReadiness['score'], 2) }}%).</p>
            </div>
            <a href="{{ route('admin.paper-readiness.export') }}" class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 font-bold hover:bg-indigo-100 no-print">Readiness CSV</a>
        </div>
        <div class="grid md:grid-cols-2 gap-3">
            @foreach($paperReadiness['items'] as $item)
            <div class="flex items-start gap-3 rounded-xl p-3 {{ $item['ready'] ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                <span class="font-black">{{ $item['ready'] ? 'READY' : 'TODO' }}</span>
                <div>
                    <p class="text-sm font-semibold">{{ $item['name'] }}: {{ $item['current'] }}</p>
                    <p class="text-xs opacity-80 mt-1">{{ $item['notes'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow p-6 mb-8 print-card">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Latest Verification Log</h2>
        @if($latestLog)
        <div class="grid md:grid-cols-2 gap-4 text-sm">
            <div><span class="block text-slate-500">Route</span><span class="font-bold text-slate-800">{{ $latestLog->gpxVersion?->route?->name }} v{{ $latestLog->gpxVersion?->version_number }}</span></div>
            <div><span class="block text-slate-500">Source</span><span class="font-bold text-slate-800">{{ strtoupper($latestLog->source) }}</span></div>
            <div><span class="block text-slate-500">Status</span><span class="font-bold text-slate-800">{{ strtoupper($latestLog->status) }}</span></div>
            <div><span class="block text-slate-500">Verified At</span><span class="font-bold text-slate-800">{{ $latestLog->verified_at?->format('d M Y, H:i') }}</span></div>
            <div class="md:col-span-2"><span class="block text-slate-500">Message</span><p class="text-slate-700">{{ $latestLog->message }}</p></div>
        </div>
        @else
        <p class="text-slate-500">Belum ada log verifikasi.</p>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow p-6 mb-8 print-card">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Blockchain Registry On-chain vs DB</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-3 text-left">Route</th>
                        <th class="px-3 py-3 text-left">Tx</th>
                        <th class="px-3 py-3 text-left">DB CID</th>
                        <th class="px-3 py-3 text-left">Receipt</th>
                        <th class="px-3 py-3 text-left">Block</th>
                        <th class="px-3 py-3 text-left">Gas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($registeredVersions as $version)
                    <tr>
                        <td class="px-3 py-3 font-semibold text-slate-800">{{ Str::limit($version->route?->name, 28) }} v{{ $version->version_number }}</td>
                        <td class="px-3 py-3"><a href="{{ $etherscanBaseUrl }}/tx/{{ $version->blockchain_tx_hash }}" target="_blank" rel="noopener" class="text-emerald-700 font-bold">Explorer</a></td>
                        <td class="px-3 py-3"><code class="text-xs">{{ Str::limit($version->ipfs_cid, 18) }}</code></td>
                        <td class="px-3 py-3 font-bold {{ $version->blockchain_confirmation_status === 'confirmed' ? 'text-emerald-700' : 'text-amber-700' }}">{{ $version->blockchain_confirmation_status ?: 'not checked' }}</td>
                        <td class="px-3 py-3">{{ $version->blockchain_block_number ?: '-' }}</td>
                        <td class="px-3 py-3">{{ $version->blockchain_gas_used ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-3 py-6 text-center text-slate-500">Belum ada transaksi blockchain.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow p-6 mb-8 print-card">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Bulk Action Progress Log</h2>
        <div class="divide-y divide-slate-100">
            @forelse($recentBulkActions as $bulkAction)
            <div class="py-3 text-sm grid md:grid-cols-5 gap-2">
                <span class="font-bold text-slate-800">{{ str_replace('_', ' ', strtoupper($bulkAction->action)) }}</span>
                <span class="text-slate-600">{{ strtoupper($bulkAction->status) }}</span>
                <span class="text-emerald-700 font-semibold">OK: {{ $bulkAction->processed_count }}</span>
                <span class="text-red-700 font-semibold">Fail: {{ $bulkAction->failed_count }}</span>
                <span class="text-slate-500 truncate" title="{{ $bulkAction->last_error }}">{{ $bulkAction->last_error ?: '-' }}</span>
            </div>
            @empty
            <p class="text-sm text-slate-500">Belum ada bulk action.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow p-6 print-card">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Download Evidence CSV</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.evaluation.provenance.export-versions') }}" class="px-4 py-2 rounded-xl bg-blue-50 text-blue-700 font-bold hover:bg-blue-100">GPX Versions CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-integrity') }}" class="px-4 py-2 rounded-xl bg-red-50 text-red-700 font-bold hover:bg-red-100">Integrity CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-version-recovery') }}" class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 font-bold hover:bg-emerald-100">Recovery CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-ipfs-performance') }}" class="px-4 py-2 rounded-xl bg-purple-50 text-purple-700 font-bold hover:bg-purple-100">IPFS CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-blockchain-registry') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-800 font-bold hover:bg-slate-200">Blockchain CSV</a>
            <a href="{{ route('admin.evaluation.provenance.export-summary') }}" class="px-4 py-2 rounded-xl bg-amber-50 text-amber-700 font-bold hover:bg-amber-100">Summary CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-black-box-testing') }}" class="px-4 py-2 rounded-xl bg-blue-50 text-blue-700 font-bold hover:bg-blue-100">Black Box CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-sus-responses') }}" class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 font-bold hover:bg-emerald-100">SUS CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-ipfs-upload-logs') }}" class="px-4 py-2 rounded-xl bg-purple-50 text-purple-700 font-bold hover:bg-purple-100">IPFS Audit CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-blockchain-registry-logs') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-800 font-bold hover:bg-slate-200">Blockchain Audit CSV</a>
        </div>
    </div>
</div>
@endsection
