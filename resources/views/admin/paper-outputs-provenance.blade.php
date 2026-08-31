@extends('layouts.app')

@section('title', 'Paper Outputs Provenance')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.evaluation.provenance') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 mb-4 font-medium">
            ← Kembali ke Evaluasi Provenance
        </a>

        <div class="bg-gradient-to-r from-slate-800 to-emerald-800 rounded-3xl p-8 text-white shadow-xl">
            <p class="text-emerald-100 text-sm font-semibold mb-2">SINTA 3 Paper Outputs</p>
            <h1 class="text-3xl md:text-4xl font-extrabold mb-3">Tabel Paper GPX Provenance</h1>
            <p class="text-slate-100 max-w-3xl">
                Download tabel CSV yang siap dipakai untuk bagian hasil dan pembahasan paper: version control, integrity verification, version recovery, dan ringkasan evaluasi.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <p class="text-sm text-slate-500 mb-1">GPX Versions</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $totalVersions }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <p class="text-sm text-slate-500 mb-1">Verification Logs</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $totalVerificationLogs }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <p class="text-sm text-slate-500 mb-1">Integrity Rows</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $integrityRows }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <p class="text-sm text-slate-500 mb-1">Recovery Rows</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $recoveryRows }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 md:col-span-4">
            <p class="text-sm text-slate-500 mb-1">IPFS Rows</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $ipfsRows }}</p>
            <p class="text-xs text-slate-500 mt-2">Jumlah versi GPX yang sudah memiliki CID IPFS.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 md:col-span-4">
            <p class="text-sm text-slate-500 mb-1">Blockchain Rows</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $blockchainRows }}</p>
            <p class="text-xs text-slate-500 mt-2">Jumlah versi GPX yang sudah terdaftar di blockchain metadata registry.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 md:col-span-2">
            <p class="text-sm text-slate-500 mb-1">IPFS Audit Logs</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $ipfsLogRows }}</p>
            <p class="text-xs text-slate-500 mt-2">Append-only log untuk upload dan retrieval IPFS.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 md:col-span-2">
            <p class="text-sm text-slate-500 mb-1">Blockchain Audit Logs</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $blockchainLogRows }}</p>
            <p class="text-xs text-slate-500 mt-2">Append-only log untuk register dan receipt check.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 md:col-span-4">
            <p class="text-sm text-slate-500 mb-1">Black Box Test Rows</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $blackBoxRows }}</p>
            <p class="text-xs text-slate-500 mt-2">Jumlah skenario pengujian black box untuk fitur utama sistem.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 md:col-span-4">
            <p class="text-sm text-slate-500 mb-1">SUS Responses</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $susRows }}</p>
            <p class="text-xs text-slate-500 mt-2">Rata-rata SUS Score: {{ number_format($averageSusScore, 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 md:col-span-4">
            <p class="text-sm text-slate-500 mb-1">Recommendation Evaluation Queries</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $recommendationQueryRows }}</p>
            <p class="text-xs text-slate-500 mt-2">Average Precision@5: {{ number_format($averagePrecisionAt5, 4) }} | Average Precision@10: {{ number_format($averagePrecisionAt10, 4) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Preview Tabel Final BAB Hasil</h2>
                <p class="text-sm text-slate-500 mt-1">Tabel HTML siap screenshot/copy untuk naskah, melengkapi CSV mentah.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <form method="POST" action="{{ route('admin.paper-outputs.provenance.capture-evaluation-snapshot') }}">
                    @csrf
                    <button class="px-4 py-2 rounded-xl bg-indigo-700 text-white font-bold hover:bg-indigo-800">Capture Snapshot</button>
                </form>
                <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800">Print Tabel</button>
            </div>
        </div>

        <h3 class="font-bold text-slate-800 mb-3">Tabel Before vs After Provenance</h3>
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-sm border border-slate-200">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-3 text-left border-b">Aspect</th>
                        <th class="px-3 py-3 text-left border-b">Before</th>
                        <th class="px-3 py-3 text-left border-b">After</th>
                        <th class="px-3 py-3 text-left border-b">Contribution</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($beforeAfterRows as $row)
                    <tr>
                        <td class="px-3 py-3 font-bold text-slate-800">{{ $row['aspect'] }}</td>
                        <td class="px-3 py-3">{{ $row['before'] }}</td>
                        <td class="px-3 py-3">{{ $row['after'] }}</td>
                        <td class="px-3 py-3">{{ $row['contribution'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h3 class="font-bold text-slate-800 mb-3">Evaluation Results Snapshots</h3>
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-sm border border-slate-200">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-3 text-left border-b">Metric</th>
                        <th class="px-3 py-3 text-left border-b">Value</th>
                        <th class="px-3 py-3 text-left border-b">Dataset Size</th>
                        <th class="px-3 py-3 text-left border-b">Captured</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($latestEvaluationResults as $result)
                    <tr>
                        <td class="px-3 py-3 font-bold text-slate-800">{{ $result->metric_name }}</td>
                        <td class="px-3 py-3">{{ number_format($result->metric_value, 4) }}</td>
                        <td class="px-3 py-3">{{ $result->dataset_size }}</td>
                        <td class="px-3 py-3">{{ $result->captured_at?->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-3 py-6 text-center text-slate-500">Belum ada snapshot evaluation_results. Klik Capture Snapshot.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="font-bold text-slate-800 mb-3">Tabel Integrity Verification</h3>
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-sm border border-slate-200">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-3 text-left border-b">Rute</th>
                        <th class="px-3 py-3 text-left border-b">Versi</th>
                        <th class="px-3 py-3 text-left border-b">Sumber</th>
                        <th class="px-3 py-3 text-left border-b">Status</th>
                        <th class="px-3 py-3 text-left border-b">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($finalIntegrityRows as $log)
                    <tr>
                        <td class="px-3 py-3">{{ $log->gpxVersion?->route?->name }}</td>
                        <td class="px-3 py-3">v{{ $log->gpxVersion?->version_number }}</td>
                        <td class="px-3 py-3">{{ strtoupper($log->source) }}</td>
                        <td class="px-3 py-3 font-bold">{{ strtoupper($log->status) }}</td>
                        <td class="px-3 py-3">{{ $log->verified_at?->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">Belum ada log integrity.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="font-bold text-slate-800 mb-3">Tabel Blockchain Registry</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-slate-200">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-3 text-left border-b">Rute</th>
                        <th class="px-3 py-3 text-left border-b">Versi</th>
                        <th class="px-3 py-3 text-left border-b">CID</th>
                        <th class="px-3 py-3 text-left border-b">Tx Hash</th>
                        <th class="px-3 py-3 text-left border-b">Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($finalBlockchainRows as $version)
                    <tr>
                        <td class="px-3 py-3">{{ $version->route?->name }}</td>
                        <td class="px-3 py-3">v{{ $version->version_number }}</td>
                        <td class="px-3 py-3"><code class="text-xs">{{ Str::limit($version->ipfs_cid, 18) }}</code></td>
                        <td class="px-3 py-3"><code class="text-xs">{{ Str::limit($version->blockchain_tx_hash, 18) }}</code></td>
                        <td class="px-3 py-3 font-bold">
                            {{ $version->blockchain_confirmation_status ?: 'not checked' }}
                            <form method="POST" action="{{ route('admin.paper-outputs.provenance.verify-on-chain', $version) }}" class="mt-2">
                                @csrf
                                <button class="px-3 py-1 rounded-lg bg-slate-800 text-white text-xs font-bold hover:bg-slate-900">Read On-chain</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">Belum ada data blockchain.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="font-bold text-slate-800 mb-3 mt-6">Tabel IPFS Audit Log</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-slate-200">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-3 text-left border-b">Rute</th>
                        <th class="px-3 py-3 text-left border-b">Versi</th>
                        <th class="px-3 py-3 text-left border-b">Operation</th>
                        <th class="px-3 py-3 text-left border-b">Status</th>
                        <th class="px-3 py-3 text-left border-b">Duration</th>
                        <th class="px-3 py-3 text-left border-b">Logged At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($finalIpfsLogRows as $log)
                    <tr>
                        <td class="px-3 py-3">{{ $log->gpxVersion?->route?->name }}</td>
                        <td class="px-3 py-3">v{{ $log->gpxVersion?->version_number }}</td>
                        <td class="px-3 py-3">{{ strtoupper($log->operation) }}</td>
                        <td class="px-3 py-3 font-bold {{ $log->status === 'success' ? 'text-emerald-700' : 'text-red-700' }}">{{ strtoupper($log->status) }}</td>
                        <td class="px-3 py-3">{{ $log->duration_ms ? number_format($log->duration_ms) . ' ms' : '-' }}</td>
                        <td class="px-3 py-3">{{ $log->logged_at?->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-3 py-6 text-center text-slate-500">Belum ada audit log IPFS.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="font-bold text-slate-800 mb-3 mt-6">Tabel Blockchain Audit Log</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-slate-200">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-3 text-left border-b">Rute</th>
                        <th class="px-3 py-3 text-left border-b">Versi</th>
                        <th class="px-3 py-3 text-left border-b">Operation</th>
                        <th class="px-3 py-3 text-left border-b">Status</th>
                        <th class="px-3 py-3 text-left border-b">Tx Hash</th>
                        <th class="px-3 py-3 text-left border-b">Block</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($finalBlockchainLogRows as $log)
                    <tr>
                        <td class="px-3 py-3">{{ $log->gpxVersion?->route?->name }}</td>
                        <td class="px-3 py-3">v{{ $log->gpxVersion?->version_number }}</td>
                        <td class="px-3 py-3">{{ strtoupper($log->operation) }}</td>
                        <td class="px-3 py-3 font-bold {{ in_array($log->status, ['success', 'confirmed'], true) ? 'text-emerald-700' : 'text-red-700' }}">{{ strtoupper($log->status) }}</td>
                        <td class="px-3 py-3"><code class="text-xs">{{ Str::limit($log->tx_hash, 18) ?: '-' }}</code></td>
                        <td class="px-3 py-3">{{ $log->block_number ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-3 py-6 text-center text-slate-500">Belum ada audit log blockchain.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="font-bold text-slate-800 mb-3 mt-6">Tabel Black Box Testing</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-slate-200">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-3 text-left border-b">ID</th>
                        <th class="px-3 py-3 text-left border-b">Feature</th>
                        <th class="px-3 py-3 text-left border-b">Scenario</th>
                        <th class="px-3 py-3 text-left border-b">Expected</th>
                        <th class="px-3 py-3 text-left border-b">Actual</th>
                        <th class="px-3 py-3 text-left border-b">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($finalBlackBoxRows as $row)
                    <tr>
                        <td class="px-3 py-3 font-bold text-slate-800">{{ $row->test_case_id }}</td>
                        <td class="px-3 py-3">{{ $row->feature }}</td>
                        <td class="px-3 py-3">{{ $row->scenario }}</td>
                        <td class="px-3 py-3">{{ $row->expected_result }}</td>
                        <td class="px-3 py-3">{{ $row->actual_result }}</td>
                        <td class="px-3 py-3 font-bold {{ $row->status === 'pass' ? 'text-emerald-700' : 'text-red-700' }}">{{ strtoupper($row->status) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-3 py-6 text-center text-slate-500">Belum ada data black box testing.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="font-bold text-slate-800 mb-3 mt-6">Tabel SUS Usability Score</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-slate-200">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-3 text-left border-b">Respondent</th>
                        <th class="px-3 py-3 text-left border-b">Role</th>
                        <th class="px-3 py-3 text-left border-b">Score</th>
                        <th class="px-3 py-3 text-left border-b">Submitted</th>
                        <th class="px-3 py-3 text-left border-b">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($finalSusRows as $response)
                    <tr>
                        <td class="px-3 py-3">{{ $response->respondent_name ?: 'Anonymous' }}</td>
                        <td class="px-3 py-3">{{ $response->respondent_role ?: '-' }}</td>
                        <td class="px-3 py-3 font-bold {{ $response->score >= 70 ? 'text-emerald-700' : 'text-amber-700' }}">{{ number_format($response->score, 2) }}</td>
                        <td class="px-3 py-3">{{ $response->submitted_at?->format('d M Y H:i') }}</td>
                        <td class="px-3 py-3">{{ $response->notes ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">Belum ada jawaban SUS.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="font-bold text-slate-800 mb-3 mt-6">Tabel Precision@5 dan Precision@10</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-slate-200">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-3 text-left border-b">Query</th>
                        <th class="px-3 py-3 text-left border-b">Relevant Routes</th>
                        <th class="px-3 py-3 text-left border-b">Top-5 Results</th>
                        <th class="px-3 py-3 text-left border-b">Precision@5</th>
                        <th class="px-3 py-3 text-left border-b">Precision@10</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($finalRecommendationRows as $row)
                    <tr>
                        <td class="px-3 py-3">{{ $row['query'] }}</td>
                        <td class="px-3 py-3"><code class="text-xs">{{ implode(', ', $row['relevant_route_ids']) }}</code></td>
                        <td class="px-3 py-3">{{ implode(', ', array_slice($row['top_5_route_names'], 0, 5)) }}</td>
                        <td class="px-3 py-3 font-bold text-blue-700">{{ number_format($row['precision_at_5'], 4) }}</td>
                        <td class="px-3 py-3 font-bold text-blue-700">{{ number_format($row['precision_at_10'], 4) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">Belum ada query evaluasi rekomendasi. Pastikan route memiliki SBERT embedding.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Tabel Before vs After</h2>
                    <p class="text-sm text-slate-500 mt-1">Perbandingan kontribusi sistem sebelum dan sesudah provenance untuk naskah paper.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">Contribution</span>
            </div>
            <a href="{{ route('admin.paper-outputs.provenance.export-before-after') }}" class="inline-flex items-center px-5 py-3 bg-amber-600 text-white rounded-2xl font-bold hover:bg-amber-700 transition-colors">
                Download Before After CSV
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Evaluation Results Snapshot</h2>
                    <p class="text-sm text-slate-500 mt-1">Export hasil metrik yang sudah disimpan sebagai snapshot eksperimen.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">Snapshot</span>
            </div>
            <a href="{{ route('admin.paper-outputs.provenance.export-evaluation-results') }}" class="inline-flex items-center px-5 py-3 bg-indigo-700 text-white rounded-2xl font-bold hover:bg-indigo-800 transition-colors">
                Download Evaluation Results CSV
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Tabel GPX Versions</h2>
                    <p class="text-sm text-slate-500 mt-1">Daftar versi GPX per rute, status aktif, hash, uploader, dan change log.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">Dataset</span>
            </div>
            <a href="{{ route('admin.evaluation.provenance.export-versions') }}" class="inline-flex items-center px-5 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-colors">
                Download GPX Versions CSV
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">IPFS Audit Logs</h2>
                    <p class="text-sm text-slate-500 mt-1">Log upload/retrieval IPFS lengkap dengan status, CID, durasi, dan pesan error.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-bold">Audit</span>
            </div>
            <a href="{{ route('admin.paper-outputs.provenance.export-ipfs-upload-logs') }}" class="inline-flex items-center px-5 py-3 bg-purple-600 text-white rounded-2xl font-bold hover:bg-purple-700 transition-colors">
                Download IPFS Audit Logs CSV
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Blockchain Audit Logs</h2>
                    <p class="text-sm text-slate-500 mt-1">Log register/receipt blockchain lengkap dengan tx hash, block, gas, status, dan error.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-slate-800 text-white text-xs font-bold">Audit</span>
            </div>
            <a href="{{ route('admin.paper-outputs.provenance.export-blockchain-registry-logs') }}" class="inline-flex items-center px-5 py-3 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition-colors">
                Download Blockchain Audit Logs CSV
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Tabel Verification Logs</h2>
                    <p class="text-sm text-slate-500 mt-1">Log lengkap verifikasi hash lokal untuk setiap versi GPX pada satu rute.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">Audit</span>
            </div>
            <p class="text-sm text-slate-500 mb-4">Gunakan tombol export di halaman provenance per rute untuk log spesifik rute.</p>
            @if($sampleRouteId)
            <a href="{{ route('routes.provenance', $sampleRouteId) }}" class="inline-flex items-center px-5 py-3 bg-slate-700 text-white rounded-2xl font-bold hover:bg-slate-800 transition-colors">
                Buka Contoh Provenance Route {{ $sampleRouteId }}
            </a>
            @else
            <span class="inline-flex items-center px-5 py-3 bg-slate-100 text-slate-500 rounded-2xl font-bold">
                Belum ada route provenance
            </span>
            @endif
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Tabel Integrity Verification</h2>
                    <p class="text-sm text-slate-500 mt-1">Format paper untuk membuktikan hash verification menghasilkan status verified atau invalid.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Integrity</span>
            </div>
            <a href="{{ route('admin.paper-outputs.provenance.export-integrity') }}" class="inline-flex items-center px-5 py-3 bg-red-600 text-white rounded-2xl font-bold hover:bg-red-700 transition-colors">
                Download Integrity Table CSV
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Tabel Version Recovery</h2>
                    <p class="text-sm text-slate-500 mt-1">Format paper untuk membuktikan restore versi GPX berhasil mengubah active version.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Recovery</span>
            </div>
            <a href="{{ route('admin.paper-outputs.provenance.export-version-recovery') }}" class="inline-flex items-center px-5 py-3 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition-colors">
                Download Version Recovery CSV
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Tabel IPFS Performance</h2>
                    <p class="text-sm text-slate-500 mt-1">Format paper untuk upload time, retrieval time, CID, dan status verifikasi IPFS.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-bold">IPFS</span>
            </div>
            <a href="{{ route('admin.paper-outputs.provenance.export-ipfs-performance') }}" class="inline-flex items-center px-5 py-3 bg-purple-600 text-white rounded-2xl font-bold hover:bg-purple-700 transition-colors">
                Download IPFS Performance CSV
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Tabel Blockchain Registry</h2>
                    <p class="text-sm text-slate-500 mt-1">Format paper untuk metadata registry: route ID, version, file hash, CID, dan transaction hash.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-slate-800 text-white text-xs font-bold">Blockchain</span>
            </div>
            <a href="{{ route('admin.paper-outputs.provenance.export-blockchain-registry') }}" class="inline-flex items-center px-5 py-3 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition-colors">
                Download Blockchain Registry CSV
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Tabel Evaluation Summary</h2>
                    <p class="text-sm text-slate-500 mt-1">Ringkasan metrik untuk Hash Verification Accuracy dan Version Recovery Accuracy.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-bold">Summary</span>
            </div>
            <a href="{{ route('admin.evaluation.provenance.export-summary') }}" class="inline-flex items-center px-5 py-3 bg-purple-600 text-white rounded-2xl font-bold hover:bg-purple-700 transition-colors">
                Download Evaluation Summary CSV
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Tabel Black Box Testing</h2>
                    <p class="text-sm text-slate-500 mt-1">Format paper untuk pengujian fungsional fitur rekomendasi, provenance, IPFS, blockchain, dan export.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">Black Box</span>
            </div>
            <a href="{{ route('admin.paper-outputs.provenance.export-black-box-testing') }}" class="inline-flex items-center px-5 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-colors">
                Download Black Box Testing CSV
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Tabel SUS Score</h2>
                    <p class="text-sm text-slate-500 mt-1">Format paper untuk evaluasi usability menggunakan System Usability Scale.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Usability</span>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('evaluation.sus.create') }}" class="inline-flex items-center px-5 py-3 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition-colors">
                    Isi Form SUS
                </a>
                <a href="{{ route('admin.paper-outputs.provenance.export-sus-responses') }}" class="inline-flex items-center px-5 py-3 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition-colors">
                    Download SUS CSV
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Tabel Precision@K</h2>
                    <p class="text-sm text-slate-500 mt-1">Format paper untuk evaluasi akurasi ranking rekomendasi berbasis ground truth query.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">Recommendation</span>
            </div>
            <a href="{{ route('admin.paper-outputs.provenance.export-recommendation-precision') }}" class="inline-flex items-center px-5 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-colors">
                Download Precision@K CSV
            </a>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6">
        <h2 class="text-lg font-bold text-amber-900 mb-2">Catatan Penggunaan Paper</h2>
        <p class="text-sm text-amber-800 leading-relaxed">
            Untuk tabel verification logs spesifik rute, buka halaman provenance rute lalu gunakan tombol Export Verification CSV. Untuk tabel global paper, gunakan export di halaman ini dan halaman evaluasi provenance.
        </p>
    </div>
</div>
@endsection
