<?php
namespace App\Http\Controllers;

use App\Jobs\RunBulkProvenanceAction;
use App\Models\BulkProvenanceAction;
use App\Models\GpxVersion;
use App\Models\HikingRoute;
use App\Models\VerificationLog;
use App\Services\PaperReadinessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * Admin dashboard - Display all routes with SBERT details
     */
    public function dashboard(PaperReadinessService $paperReadinessService)
    {
        $routes = HikingRoute::latest()->paginate(20);

        // Calculate statistics
        $stats = [
            'total_routes'          => HikingRoute::count(),
            'total_distance'        => HikingRoute::sum('distance_km'),
            'avg_elevation'         => HikingRoute::avg('elevation_gain_m'),
            'routes_with_embedding' => HikingRoute::whereNotNull('sbert_embedding')->count(),
        ];

        $totalVersions = GpxVersion::count();
        $ipfsReady = GpxVersion::where('ipfs_status', 'uploaded_ipfs')->count();
        $ipfsPending = GpxVersion::where('ipfs_status', 'pending_ipfs')->count();
        $ipfsFailed = GpxVersion::where('ipfs_status', 'failed_ipfs')->count();
        $blockchainReady = GpxVersion::where('blockchain_status', 'registered_blockchain')->count();
        $blockchainPending = GpxVersion::where('blockchain_status', 'pending_blockchain')->count();
        $blockchainFailed = GpxVersion::where('blockchain_status', 'failed_blockchain')->count();

        $provenanceStats = [
            'total_versions'         => $totalVersions,
            'verified_versions'      => GpxVersion::where('verification_status', 'verified')->count(),
            'invalid_versions'       => GpxVersion::where('verification_status', 'invalid')->count(),
            'failed_versions'        => GpxVersion::where('verification_status', 'verification_failed')->count(),
            'ipfs_ready'             => $ipfsReady,
            'ipfs_missing'           => $ipfsPending,
            'ipfs_failed'            => $ipfsFailed,
            'avg_ipfs_upload_ms'     => GpxVersion::whereNotNull('ipfs_upload_time_ms')->avg('ipfs_upload_time_ms'),
            'avg_ipfs_retrieval_ms'  => GpxVersion::whereNotNull('ipfs_retrieval_time_ms')->avg('ipfs_retrieval_time_ms'),
            'blockchain_ready'       => $blockchainReady,
            'blockchain_missing'     => $blockchainPending,
            'blockchain_failed'      => $blockchainFailed,
            'contract_address'       => config('services.blockchain.contract_address'),
            'latest_tx_hash'         => GpxVersion::whereNotNull('blockchain_tx_hash')->latest('blockchain_registered_at')->value('blockchain_tx_hash'),
            'verification_logs'      => VerificationLog::count(),
            'restore_events'         => VerificationLog::where('message', 'like', '%dipulihkan sebagai versi aktif%')->count(),
        ];

        $chartStats = [
            'integrity' => [
                'verified' => $provenanceStats['verified_versions'],
                'invalid' => $provenanceStats['invalid_versions'],
                'failed' => $provenanceStats['failed_versions'],
            ],
            'ipfs' => [
                'ready' => $provenanceStats['ipfs_ready'],
                'missing' => $provenanceStats['ipfs_missing'],
                'failed' => $provenanceStats['ipfs_failed'],
            ],
            'blockchain' => [
                'ready' => $provenanceStats['blockchain_ready'],
                'missing' => $provenanceStats['blockchain_missing'],
                'failed' => $provenanceStats['blockchain_failed'],
            ],
        ];

        $issueRoutes = [
            'missing_ipfs' => GpxVersion::with('route')->where('ipfs_status', 'pending_ipfs')->latest()->limit(5)->get(),
            'missing_blockchain' => GpxVersion::with('route')->where('blockchain_status', 'pending_blockchain')->latest()->limit(5)->get(),
            'invalid' => GpxVersion::with('route')->where('verification_status', 'invalid')->latest('updated_at')->limit(5)->get(),
        ];

        $recentBulkActions = BulkProvenanceAction::with('starter')->latest()->limit(5)->get();
        $paperReadiness = $paperReadinessService->checklist();

        return view('admin.dashboard', compact('routes', 'stats', 'provenanceStats', 'issueRoutes', 'chartStats', 'recentBulkActions', 'paperReadiness'));
    }

    public function evidencePack(PaperReadinessService $paperReadinessService)
    {
        $latestRegistered = GpxVersion::with('route')
            ->whereNotNull('blockchain_tx_hash')
            ->latest('blockchain_registered_at')
            ->first();

        $latestIpfs = GpxVersion::with('route')
            ->whereNotNull('ipfs_cid')
            ->latest('updated_at')
            ->first();

        return view('admin.evidence-pack', [
            'latestRegistered' => $latestRegistered,
            'latestIpfs' => $latestIpfs,
            'latestLog' => VerificationLog::with(['gpxVersion.route'])->latest('verified_at')->first(),
            'registeredVersions' => GpxVersion::with('route')->whereNotNull('blockchain_tx_hash')->latest('blockchain_registered_at')->limit(10)->get(),
            'recentBulkActions' => BulkProvenanceAction::with('starter')->latest()->limit(5)->get(),
            'readyChecklist' => $this->readyChecklist(),
            'paperReadiness' => $paperReadinessService->checklist(),
            'metrics' => [
                'total_versions' => GpxVersion::count(),
                'verified_versions' => GpxVersion::where('verification_status', 'verified')->count(),
                'ipfs_ready' => GpxVersion::whereNotNull('ipfs_cid')->count(),
                'blockchain_ready' => GpxVersion::whereNotNull('blockchain_tx_hash')->count(),
                'pending_ipfs' => GpxVersion::where('ipfs_status', 'pending_ipfs')->count(),
                'pending_blockchain' => GpxVersion::where('blockchain_status', 'pending_blockchain')->count(),
            ],
            'etherscanBaseUrl' => 'https://sepolia.etherscan.io',
        ]);
    }

    public function paperReadiness(PaperReadinessService $paperReadinessService)
    {
        return view('admin.paper-readiness', [
            'paperReadiness' => $paperReadinessService->checklist(),
        ]);
    }

    public function exportPaperReadiness(PaperReadinessService $paperReadinessService): StreamedResponse
    {
        $readiness = $paperReadinessService->checklist();
        $fileName = 'paper_readiness_checklist_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($readiness) {
            echo "\xEF\xBB\xBF";
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['overall_status', $readiness['status']]);
            fputcsv($handle, ['score_percent', $readiness['score']]);
            fputcsv($handle, ['passed', $readiness['passed']]);
            fputcsv($handle, ['total', $readiness['total']]);
            fputcsv($handle, []);
            fputcsv($handle, ['criterion', 'ready', 'current_value', 'notes']);

            foreach ($readiness['items'] as $item) {
                fputcsv($handle, [
                    $item['name'],
                    $item['ready'] ? 'yes' : 'no',
                    $item['current'],
                    $item['notes'],
                ]);
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function bulkProvenanceAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:verify_active,upload_missing_ipfs,register_missing_blockchain,refresh_etherscan_receipts',
        ]);

        $bulkAction = BulkProvenanceAction::create([
            'action' => $request->action,
            'status' => 'queued',
            'started_by' => Auth::id(),
        ]);

        RunBulkProvenanceAction::dispatch($bulkAction->id);

        return back()->with('success', 'Bulk action masuk queue. Jalankan worker: php artisan queue:work');
    }

    private function readyChecklist(): array
    {
        return [
            'Minimal 1 route punya CID IPFS' => GpxVersion::where('ipfs_status', 'uploaded_ipfs')->exists(),
            'Minimal 1 route punya tx hash blockchain' => GpxVersion::where('blockchain_status', 'registered_blockchain')->exists(),
            'Tidak ada pending IPFS untuk versi aktif' => ! GpxVersion::where('is_active', true)->where('ipfs_status', 'pending_ipfs')->exists(),
            'Tidak ada pending blockchain untuk versi aktif' => ! GpxVersion::where('is_active', true)->where('blockchain_status', 'pending_blockchain')->exists(),
            'Minimal 1 invalid tamper scenario' => GpxVersion::where('verification_status', 'invalid')->exists() || VerificationLog::where('status', 'invalid')->exists(),
            'CSV outputs tersedia' => true,
            'Evidence pack lengkap' => GpxVersion::where('ipfs_status', 'uploaded_ipfs')->exists() && GpxVersion::where('blockchain_status', 'registered_blockchain')->exists() && VerificationLog::exists(),
        ];
    }

    /**
     * Export all routes to CSV
     */
    public function exportCsv(): StreamedResponse
    {
        $routes = HikingRoute::all();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rutestrip_dataset_' . date('Y-m-d_His') . '.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($routes) {
            // Add UTF-8 BOM for Excel compatibility
            echo "\xEF\xBB\xBF";

            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'id',
                'name',
                'gpx_file_path',
                'distance_km',
                'elevation_gain_m',
                'naismith_duration_hour',
                'average_grade_pct',
                'narrative_text',
                'sbert_embedding_dimensions',
                'sbert_embedding_preview',
                'sbert_embedding_full',
                'created_at',
                'updated_at',
            ]);

            foreach ($routes as $route) {
                $embedding           = $route->sbert_embedding;
                $embeddingDimensions = is_array($embedding) ? count($embedding) : 0;
                $embeddingPreview    = is_array($embedding) ? implode(',', array_slice($embedding, 0, 5)) . '...' : '';
                $embeddingFull       = is_array($embedding) ? json_encode($embedding) : '';

                fputcsv($handle, [
                    $route->id,
                    $route->name,
                    $route->gpx_file_path,
                    $route->distance_km,
                    $route->elevation_gain_m,
                    $route->naismith_duration_hour,
                    $route->average_grade_pct,
                    $route->narrative_text,
                    $embeddingDimensions,
                    $embeddingPreview,
                    $embeddingFull,
                    $route->created_at,
                    $route->updated_at,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export embeddings only to CSV (lighter file)
     */
    public function exportEmbeddings(): StreamedResponse
    {
        $routes = HikingRoute::whereNotNull('sbert_embedding')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rutestrip_embeddings_' . date('Y-m-d_His') . '.csv"',
        ];

        return response()->stream(function () use ($routes) {
            echo "\xEF\xBB\xBF";

            $handle = fopen('php://output', 'w');

            // Header: id, name, then 384 embedding columns
            $header = ['id', 'name', 'narrative_text'];
            for ($i = 0; $i < 384; $i++) {
                $header[] = "dim_{$i}";
            }
            fputcsv($handle, $header);

            foreach ($routes as $route) {
                $row = [
                    $route->id,
                    $route->name,
                    $route->narrative_text,
                ];

                $embedding = $route->sbert_embedding ?: [];
                for ($i = 0; $i < 384; $i++) {
                    $row[] = $embedding[$i] ?? 0;
                }

                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
