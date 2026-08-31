@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800 mb-2">Admin Dashboard</h1>
        <p class="text-slate-600">Kelola dataset dan lihat proses SBERT embedding</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Total Rute</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $stats['total_routes'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Total Jarak</p>
                    <p class="text-3xl font-bold text-slate-800">{{ number_format($stats['total_distance'], 1) }} <span class="text-lg font-normal text-slate-500">km</span></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Rata-rata Elevasi</p>
                    <p class="text-3xl font-bold text-slate-800">{{ number_format($stats['avg_elevation'], 0) }} <span class="text-lg font-normal text-slate-500">m</span></p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Dengan Embedding</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $stats['routes_with_embedding'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Export Dataset</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.export.csv') }}"
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download Full Dataset (CSV)
            </a>
            <a href="{{ route('admin.export.embeddings') }}"
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
                Download Embeddings Only (CSV)
            </a>
            <a href="{{ route('admin.evaluation.provenance') }}"
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-700 to-slate-900 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m2 4H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0120 6.414V19a2 2 0 01-2 2z"/>
                </svg>
                Evaluasi GPX Provenance
            </a>
            <a href="{{ route('admin.paper-outputs.provenance') }}"
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0120 6.414V19a2 2 0 01-2 2z"/>
                </svg>
                Paper Outputs Provenance
            </a>
        </div>
        <p class="mt-4 text-sm text-slate-500">
            <strong>Full Dataset:</strong> Semua data termasuk metadata dan embedding dalam satu file.<br>
            <strong>Embeddings Only:</strong> Format tabel dengan 384 kolom dimensi untuk analisis ML.
        </p>
    </div>

    <!-- Paper Table Navigation -->
    <div class="bg-gradient-to-r from-slate-900 to-emerald-900 rounded-2xl shadow-xl p-6 mb-8 text-white">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div>
                <p class="text-emerald-200 text-sm font-bold uppercase tracking-wide mb-2">Paper SINTA 3</p>
                <h2 class="text-2xl font-extrabold mb-2">Navigasi Tabel Provenance</h2>
                <p class="text-slate-200 text-sm max-w-2xl">
                    Akses ringkasan evaluasi dan tabel siap ekspor untuk GPX versioning, integrity verification, IPFS performance, dan blockchain registry.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                <a href="{{ route('admin.evaluation.provenance') }}"
                   class="inline-flex items-center justify-center px-5 py-3 bg-white text-emerald-800 font-bold rounded-2xl shadow-lg hover:bg-emerald-50 transition-colors">
                    Lihat Evaluasi
                </a>
                <a href="{{ route('admin.paper-outputs.provenance') }}"
                   class="inline-flex items-center justify-center px-5 py-3 bg-amber-500 text-white font-bold rounded-2xl shadow-lg hover:bg-amber-600 transition-colors">
                    Buka Tabel Paper
                </a>
            </div>
        </div>
    </div>

    <!-- Feature Navigation -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Navigasi Fitur Admin</h2>
                <p class="text-sm text-slate-500 mt-1">Akses cepat ke fitur utama dan fitur baru provenance untuk kebutuhan sistem dan paper.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            <a href="{{ route('routes.index') }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black">R</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-emerald-800">Daftar Rute</h3>
                        <p class="text-sm text-slate-500 mt-1">Lihat semua rute pendakian, detail GPX, dan badge verifikasi.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('routes.create') }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-black">U</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-blue-800">Upload GPX Baru</h3>
                        <p class="text-sm text-slate-500 mt-1">Tambah rute baru, generate fitur GPX, hash, versi awal, dan IPFS.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('routes.batch') }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-cyan-50 hover:border-cyan-200 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center font-black">B</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-cyan-800">Batch Upload GPX</h3>
                        <p class="text-sm text-slate-500 mt-1">Upload banyak file GPX sekaligus untuk mempercepat dataset paper.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('search.index') }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-lime-50 hover:border-lime-200 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-lime-100 text-lime-700 flex items-center justify-center font-black">S</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-lime-800">Pencarian Rekomendasi</h3>
                        <p class="text-sm text-slate-500 mt-1">Cek hasil rekomendasi SBERT dan trust badge pada rute.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('chat.index') }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-rose-50 hover:border-rose-200 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-black">AI</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-rose-800">AI Chat RAG</h3>
                        <p class="text-sm text-slate-500 mt-1">Buka chatbot RAG untuk tanya jawab berbasis data rute.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.evaluation.provenance') }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-purple-50 hover:border-purple-200 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-black">E</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-purple-800">Evaluasi Provenance</h3>
                        <p class="text-sm text-slate-500 mt-1">Ringkasan versioning, hash verification, restore, IPFS, dan blockchain.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.paper-outputs.provenance') }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-amber-50 hover:border-amber-200 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-black">P</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-amber-800">Tabel Paper</h3>
                        <p class="text-sm text-slate-500 mt-1">Download CSV untuk GPX versions, integrity, IPFS, dan blockchain registry.</p>
                    </div>
                </div>
            </a>

            @if($routes->count() > 0)
            @php
                $latestRoute = $routes->first();
            @endphp
            <a href="{{ route('routes.provenance', $latestRoute) }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-slate-100 hover:border-slate-300 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-slate-200 text-slate-700 flex items-center justify-center font-black">G</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-slate-900">Contoh Provenance</h3>
                        <p class="text-sm text-slate-500 mt-1">Buka provenance rute terbaru: {{ Str::limit($latestRoute->name, 36) }}.</p>
                    </div>
                </div>
            </a>
            @endif

            <a href="{{ route('admin.export.csv') }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-teal-50 hover:border-teal-200 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center font-black">CSV</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-teal-800">Export Full Dataset</h3>
                        <p class="text-sm text-slate-500 mt-1">Download dataset rute lengkap untuk analisis dan lampiran paper.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.export.embeddings') }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-200 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black">384</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-indigo-800">Export Embeddings</h3>
                        <p class="text-sm text-slate-500 mt-1">Download matriks embedding SBERT 384 dimensi.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.paper-evidence-pack') }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-orange-50 hover:border-orange-200 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center font-black">EV</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-orange-800">Paper Evidence Pack</h3>
                        <p class="text-sm text-slate-500 mt-1">Kumpulkan CID, tx hash, contract, log, dan export CSV untuk bukti paper.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('evaluation.sus.create') }}" class="group p-5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black">SUS</div>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-emerald-800">SUS Questionnaire</h3>
                        <p class="text-sm text-slate-500 mt-1">Isi form usability untuk menghitung SUS score paper.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Provenance Health Panel -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Provenance Health Panel</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
            @php
                $healthItems = [
                    ['label' => 'Versioning', 'ready' => $provenanceStats['total_versions'] > 0, 'detail' => $provenanceStats['total_versions'] . ' versi GPX'],
                    ['label' => 'Hash Verification', 'ready' => $provenanceStats['verification_logs'] > 0, 'detail' => $provenanceStats['verification_logs'] . ' log'],
                    ['label' => 'IPFS', 'ready' => $provenanceStats['ipfs_ready'] > 0, 'detail' => $provenanceStats['ipfs_ready'] . ' CID'],
                    ['label' => 'Blockchain', 'ready' => $provenanceStats['blockchain_ready'] > 0, 'detail' => $provenanceStats['blockchain_ready'] . ' tx'],
                    ['label' => 'CSV Outputs', 'ready' => true, 'detail' => 'exports ready'],
                ];
            @endphp
            @foreach($healthItems as $item)
            <div class="rounded-2xl border p-4 {{ $item['ready'] ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200' }}">
                <p class="font-bold {{ $item['ready'] ? 'text-emerald-800' : 'text-amber-800' }}">{{ $item['label'] }}</p>
                <p class="text-sm {{ $item['ready'] ? 'text-emerald-700' : 'text-amber-700' }} mt-1">{{ $item['ready'] ? 'Ready' : 'Needs data' }} - {{ $item['detail'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Provenance Status Panels -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Status IPFS</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Sudah punya CID</span><span class="font-bold text-emerald-700">{{ $provenanceStats['ipfs_ready'] }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Pending IPFS</span><span class="font-bold text-amber-700">{{ $provenanceStats['ipfs_missing'] }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Failed IPFS</span><span class="font-bold text-red-700">{{ $provenanceStats['ipfs_failed'] }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Rata-rata upload</span><span class="font-bold text-slate-700">{{ $provenanceStats['avg_ipfs_upload_ms'] ? number_format($provenanceStats['avg_ipfs_upload_ms'], 0) . ' ms' : '-' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Rata-rata retrieval</span><span class="font-bold text-slate-700">{{ $provenanceStats['avg_ipfs_retrieval_ms'] ? number_format($provenanceStats['avg_ipfs_retrieval_ms'], 0) . ' ms' : '-' }}</span></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Status Blockchain</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Registered</span><span class="font-bold text-emerald-700">{{ $provenanceStats['blockchain_ready'] }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Pending blockchain</span><span class="font-bold text-amber-700">{{ $provenanceStats['blockchain_missing'] }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Failed blockchain</span><span class="font-bold text-red-700">{{ $provenanceStats['blockchain_failed'] }}</span></div>
                <div><span class="text-slate-500 block mb-1">Contract</span>
                    @if($provenanceStats['contract_address'])
                    <a href="https://sepolia.etherscan.io/address/{{ $provenanceStats['contract_address'] }}" target="_blank" rel="noopener" class="text-xs break-all text-emerald-700 font-semibold hover:text-emerald-800">{{ $provenanceStats['contract_address'] }}</a>
                    @else
                    <code class="text-xs text-slate-700">-</code>
                    @endif
                </div>
                <div><span class="text-slate-500 block mb-1">Latest tx</span>
                    @if($provenanceStats['latest_tx_hash'])
                    <a href="https://sepolia.etherscan.io/tx/{{ $provenanceStats['latest_tx_hash'] }}" target="_blank" rel="noopener" class="text-xs break-all text-emerald-700 font-semibold hover:text-emerald-800">{{ $provenanceStats['latest_tx_hash'] }}</a>
                    @else
                    <code class="text-xs text-slate-700">-</code>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Status Integrity</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Verified</span><span class="font-bold text-emerald-700">{{ $provenanceStats['verified_versions'] }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Invalid</span><span class="font-bold text-red-700">{{ $provenanceStats['invalid_versions'] }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Verification failed</span><span class="font-bold text-amber-700">{{ $provenanceStats['failed_versions'] }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Restore events</span><span class="font-bold text-slate-700">{{ $provenanceStats['restore_events'] }}</span></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Penjelasan Status Provenance</h2>
        <div class="grid md:grid-cols-3 gap-4 text-sm">
            <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4">
                <p class="font-bold text-emerald-800 mb-1">Verified GPX</p>
                <p class="text-emerald-700">File GPX cocok dengan hash tersimpan dan belum terdeteksi berubah.</p>
            </div>
            <div class="rounded-2xl bg-purple-50 border border-purple-100 p-4">
                <p class="font-bold text-purple-800 mb-1">Uploaded IPFS</p>
                <p class="text-purple-700">Salinan GPX sudah tersedia di IPFS dan dapat dilacak memakai CID.</p>
            </div>
            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                <p class="font-bold text-slate-800 mb-1">Registered Blockchain</p>
                <p class="text-slate-600">Metadata minimal GPX sudah tercatat di blockchain sebagai bukti registrasi.</p>
            </div>
        </div>
    </div>

    <!-- Provenance Charts -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Grafik Status Provenance</h2>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach([
                'Integrity' => ['items' => $chartStats['integrity'], 'colors' => ['verified' => 'bg-emerald-500', 'invalid' => 'bg-red-500', 'failed' => 'bg-amber-500']],
                'IPFS' => ['items' => $chartStats['ipfs'], 'colors' => ['ready' => 'bg-purple-500', 'missing' => 'bg-slate-300', 'failed' => 'bg-red-500']],
                'Blockchain' => ['items' => $chartStats['blockchain'], 'colors' => ['ready' => 'bg-slate-800', 'missing' => 'bg-slate-300', 'failed' => 'bg-red-500']],
            ] as $chartTitle => $chart)
            @php
                $totalChart = max(array_sum($chart['items']), 1);
            @endphp
            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                <h3 class="font-bold text-slate-800 mb-4">{{ $chartTitle }}</h3>
                <div class="space-y-3">
                    @foreach($chart['items'] as $label => $value)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="capitalize text-slate-600">{{ str_replace('_', ' ', $label) }}</span>
                            <span class="font-bold text-slate-800">{{ $value }}</span>
                        </div>
                        <div class="h-3 rounded-full bg-white overflow-hidden border border-slate-100">
                            <div class="h-full {{ $chart['colors'][$label] }}" style="width: {{ round(($value / $totalChart) * 100, 1) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Bulk Provenance Actions -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Bulk Action Provenance</h2>
        <p class="text-sm text-slate-500 mb-4">Aksi berjalan melalui Laravel queue agar proses IPFS/blockchain tidak timeout. Jalankan worker dengan <code class="text-xs bg-slate-100 px-2 py-1 rounded">php artisan queue:work</code>.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <form action="{{ route('admin.bulk-provenance-action') }}" method="POST" onsubmit="return confirm('Verify ulang semua versi aktif?')" class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                @csrf
                <input type="hidden" name="action" value="verify_active">
                <h3 class="font-bold text-emerald-800 mb-2">Verify Active GPX</h3>
                <p class="text-sm text-emerald-700 mb-4">Hitung ulang hash lokal untuk semua versi aktif.</p>
                <button class="px-4 py-2 rounded-xl bg-emerald-700 text-white font-bold hover:bg-emerald-800">Jalankan</button>
            </form>
            <form action="{{ route('admin.bulk-provenance-action') }}" method="POST" onsubmit="return confirm('Upload IPFS untuk versi yang belum punya CID?')" class="rounded-2xl border border-purple-100 bg-purple-50 p-4">
                @csrf
                <input type="hidden" name="action" value="upload_missing_ipfs">
                <h3 class="font-bold text-purple-800 mb-2">Upload Missing IPFS</h3>
                <p class="text-sm text-purple-700 mb-4">Coba upload ulang GPX version yang belum punya CID.</p>
                <button class="px-4 py-2 rounded-xl bg-purple-700 text-white font-bold hover:bg-purple-800">Jalankan</button>
            </form>
            <form action="{{ route('admin.bulk-provenance-action') }}" method="POST" onsubmit="return confirm('Register blockchain untuk versi yang sudah punya CID?')" class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                @csrf
                <input type="hidden" name="action" value="register_missing_blockchain">
                <h3 class="font-bold text-slate-800 mb-2">Register Blockchain</h3>
                <p class="text-sm text-slate-600 mb-4">Daftarkan metadata minimal untuk versi yang sudah punya CID.</p>
                <button class="px-4 py-2 rounded-xl bg-slate-800 text-white font-bold hover:bg-slate-900">Jalankan</button>
            </form>
            <form action="{{ route('admin.bulk-provenance-action') }}" method="POST" onsubmit="return confirm('Refresh receipt Etherscan untuk transaksi blockchain?')" class="rounded-2xl border border-blue-100 bg-blue-50 p-4">
                @csrf
                <input type="hidden" name="action" value="refresh_etherscan_receipts">
                <h3 class="font-bold text-blue-800 mb-2">Refresh Etherscan</h3>
                <p class="text-sm text-blue-700 mb-4">Ambil block number, gas used, status, dan timestamp block.</p>
                <button class="px-4 py-2 rounded-xl bg-blue-700 text-white font-bold hover:bg-blue-800">Jalankan</button>
            </form>
        </div>

        <div class="mt-6 rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 font-bold text-slate-700">Progress Bulk Terbaru</div>
            <div class="divide-y divide-slate-100">
                @forelse($recentBulkActions as $bulkAction)
                <div class="px-4 py-3 text-sm grid md:grid-cols-5 gap-2">
                    <span class="font-bold text-slate-800">{{ str_replace('_', ' ', strtoupper($bulkAction->action)) }}</span>
                    <span class="text-slate-600">{{ strtoupper($bulkAction->status) }}</span>
                    <span class="text-emerald-700 font-semibold">OK: {{ $bulkAction->processed_count }}</span>
                    <span class="text-red-700 font-semibold">Fail: {{ $bulkAction->failed_count }}</span>
                    <span class="text-slate-500 truncate" title="{{ $bulkAction->last_error }}">{{ $bulkAction->last_error ?: '-' }}</span>
                </div>
                @empty
                <div class="px-4 py-3 text-sm text-slate-500">Belum ada bulk action.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Incomplete Routes Shortcuts -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Shortcut Rute Belum Lengkap</h2>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @php
                $issueSections = [
                'missing_ipfs' => 'Belum Punya CID IPFS',
                'missing_blockchain' => 'Belum Register Blockchain',
                'invalid' => 'Status Invalid',
            ];
            @endphp
            @foreach($issueSections as $key => $title)
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <h3 class="font-bold text-slate-800 mb-3">{{ $title }}</h3>
                <a href="{{ route('routes.index', ['provenance_filter' => $key]) }}" class="inline-flex mb-3 text-xs font-bold text-emerald-700 hover:text-emerald-800">Lihat semua</a>
                <div class="space-y-2">
                    @forelse($issueRoutes[$key] as $version)
                    <a href="{{ route('routes.provenance', $version->route) }}" class="block text-sm text-emerald-700 hover:text-emerald-800 font-semibold">
                        {{ Str::limit($version->route?->name, 34) }} v{{ $version->version_number }}
                    </a>
                    @empty
                    <p class="text-sm text-slate-500">Tidak ada data.</p>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Paper Output Quick Downloads -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Quick Download Tabel Paper</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.evaluation.provenance.export-versions') }}" class="px-4 py-2 rounded-xl bg-blue-50 text-blue-700 font-bold hover:bg-blue-100">GPX Versions CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-integrity') }}" class="px-4 py-2 rounded-xl bg-red-50 text-red-700 font-bold hover:bg-red-100">Integrity CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-version-recovery') }}" class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 font-bold hover:bg-emerald-100">Recovery CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-ipfs-performance') }}" class="px-4 py-2 rounded-xl bg-purple-50 text-purple-700 font-bold hover:bg-purple-100">IPFS CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-blockchain-registry') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-800 font-bold hover:bg-slate-200">Blockchain CSV</a>
            <a href="{{ route('admin.evaluation.provenance.export-summary') }}" class="px-4 py-2 rounded-xl bg-amber-50 text-amber-700 font-bold hover:bg-amber-100">Summary CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-black-box-testing') }}" class="px-4 py-2 rounded-xl bg-blue-50 text-blue-700 font-bold hover:bg-blue-100">Black Box CSV</a>
            <a href="{{ route('admin.paper-outputs.provenance.export-sus-responses') }}" class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 font-bold hover:bg-emerald-100">SUS CSV</a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Dataset Readiness Checker</h2>
                <p class="text-sm text-slate-500 mt-1">Kesiapan evidence untuk paper: {{ $paperReadiness['passed'] }}/{{ $paperReadiness['total'] }} kriteria terpenuhi.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.paper-readiness') }}" class="px-4 py-2 rounded-xl bg-indigo-700 text-white font-bold hover:bg-indigo-800">Lihat Checklist</a>
                <a href="{{ route('admin.paper-readiness.export') }}" class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 font-bold hover:bg-indigo-100">Readiness CSV</a>
            </div>
        </div>
        <div class="h-3 rounded-full bg-slate-100 overflow-hidden mb-3">
            <div class="h-full {{ $paperReadiness['status'] === 'ready' ? 'bg-emerald-500' : 'bg-amber-500' }}" style="width: {{ $paperReadiness['score'] }}%"></div>
        </div>
        <p class="text-sm font-bold {{ $paperReadiness['status'] === 'ready' ? 'text-emerald-700' : 'text-amber-700' }}">{{ strtoupper(str_replace('_', ' ', $paperReadiness['status'])) }} - {{ number_format($paperReadiness['score'], 2) }}%</p>
    </div>

    <!-- SBERT Process Explanation -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-xl p-8 mb-8 text-white">
        <h2 class="text-2xl font-bold mb-4">Proses SBERT & Cosine Similarity</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mb-3">
                    <span class="text-xl font-bold">1</span>
                </div>
                <h3 class="font-semibold mb-2">GPX Processing</h3>
                <p class="text-sm text-purple-100">File GPX diekstrak untuk mendapatkan jarak, elevasi, durasi Naismith, dan grade.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mb-3">
                    <span class="text-xl font-bold">2</span>
                </div>
                <h3 class="font-semibold mb-2">Narrative Generation</h3>
                <p class="text-sm text-purple-100">Statistik diubah menjadi teks naratif deskriptif untuk input SBERT.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mb-3">
                    <span class="text-xl font-bold">3</span>
                </div>
                <h3 class="font-semibold mb-2">SBERT Embedding</h3>
                <p class="text-sm text-purple-100">Model paraphrase-multilingual-MiniLM-L12-v2 menghasilkan vector 384 dimensi.</p>
            </div>
        </div>
        <div class="mt-6 bg-white/10 backdrop-blur-sm rounded-xl p-5">
            <h3 class="font-semibold mb-2">Cosine Similarity Search</h3>
            <p class="text-sm text-purple-100">Saat user mencari rute, query diubah menjadi embedding, lalu dihitung cosine similarity dengan semua rute untuk menemukan yang paling mirip.</p>
            <code class="block mt-2 bg-black/20 rounded-lg p-3 text-xs font-mono">similarity = cos(θ) = (A · B) / (||A|| × ||B||)</code>
        </div>
    </div>

    <!-- Routes Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-slate-800">Dataset Rute Pendakian</h2>
            <span class="text-sm text-slate-500">{{ $routes->total() }} total rute</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama Rute</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Jarak</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Elevasi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Durasi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Grade</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Embedding</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($routes as $route)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-4 text-sm text-slate-600">{{ $route->id }}</td>
                        <td class="px-4 py-4">
                            <div class="font-medium text-slate-800">{{ Str::limit($route->name, 30) }}</div>
                            <div class="text-xs text-slate-500">{{ $route->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-600">{{ $route->distance_km ?? '-' }} km</td>
                        <td class="px-4 py-4 text-sm text-slate-600">{{ $route->elevation_gain_m ?? '-' }} m</td>
                        <td class="px-4 py-4 text-sm text-slate-600">{{ $route->naismith_duration_hour ?? '-' }} jam</td>
                        <td class="px-4 py-4 text-sm text-slate-600">{{ $route->average_grade_pct ?? '-' }}%</td>
                        <td class="px-4 py-4">
                            @if($route->sbert_embedding)
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        384 dim
                                    </span>
                                    <button onclick="showEmbedding({{ $route->id }}, '{{ $route->name }}')"
                                            class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                        Lihat
                                    </button>
                                </div>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                    Tidak ada
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ route('routes.show', $route) }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-slate-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p>Belum ada data rute.</p>
                            <a href="{{ route('routes.create') }}" class="text-emerald-600 hover:underline mt-2 inline-block">Upload GPX pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($routes->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $routes->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Embedding Modal -->
<div id="embeddingModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
            <div>
                <h3 class="text-lg font-bold">SBERT Embedding</h3>
                <p class="text-sm text-purple-100" id="modalRouteName">-</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[70vh]">
            <div class="mb-4">
                <h4 class="font-semibold text-slate-700 mb-2">Narrative Text (Input SBERT)</h4>
                <p class="text-sm text-slate-600 bg-slate-50 rounded-xl p-4" id="modalNarrative">-</p>
            </div>
            <div>
                <h4 class="font-semibold text-slate-700 mb-2">Embedding Vector (384 dimensi)</h4>
                <div class="bg-slate-900 rounded-xl p-4 overflow-x-auto">
                    <code class="text-xs text-green-400 font-mono whitespace-pre-wrap" id="modalEmbedding">-</code>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Store route data for modal
    const routeData = @json($routes->items());

    function showEmbedding(id, name) {
        const route = routeData.find(r => r.id === id);
        if (!route) return;

        document.getElementById('modalRouteName').textContent = name;
        document.getElementById('modalNarrative').textContent = route.narrative_text || 'Tidak ada narasi';

        const embedding = route.sbert_embedding;
        if (embedding && Array.isArray(embedding)) {
            const formatted = embedding.map((v, i) => `[${i}]: ${v.toFixed(6)}`).join('\n');
            document.getElementById('modalEmbedding').textContent = formatted;
        } else {
            document.getElementById('modalEmbedding').textContent = 'Tidak ada embedding';
        }

        const modal = document.getElementById('embeddingModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('embeddingModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal on backdrop click
    document.getElementById('embeddingModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endsection
