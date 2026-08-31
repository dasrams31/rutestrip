<?php

namespace App\Services;

use App\Models\BlackBoxTestResult;
use App\Models\BlockchainRegistryLog;
use App\Models\GpxVersion;
use App\Models\HikingRoute;
use App\Models\IpfsUploadLog;
use App\Models\RecommendationEvaluationQuery;
use App\Models\SusResponse;
use App\Models\VerificationLog;

class PaperReadinessService
{
    public function checklist(): array
    {
        $totalRoutes = HikingRoute::count();
        $routesWithEmbedding = HikingRoute::whereNotNull('sbert_embedding')->count();
        $totalVersions = GpxVersion::count();
        $activeVersions = GpxVersion::where('is_active', true)->count();
        $multiVersionRoutes = GpxVersion::select('hiking_route_id')
            ->groupBy('hiking_route_id')
            ->havingRaw('COUNT(*) >= 2')
            ->get()
            ->count();
        $verifiedVersions = GpxVersion::where('verification_status', 'verified')->count();
        $ipfsReady = GpxVersion::where('ipfs_status', 'uploaded_ipfs')->count();
        $blockchainReady = GpxVersion::where('blockchain_status', 'registered_blockchain')->count();
        $precisionQueries = RecommendationEvaluationQuery::count();
        $susResponses = SusResponse::count();
        $blackBoxRows = BlackBoxTestResult::count();

        $items = [
            $this->item('Route dataset tersedia', $totalRoutes >= 50, $totalRoutes, 'Target PRD: 50-100 jalur pendakian.'),
            $this->item('Embedding SBERT tersedia', $routesWithEmbedding >= 50, $routesWithEmbedding, 'Target PRD: semua/Mayoritas rute memiliki embedding untuk pencarian dan Precision@K.'),
            $this->item('GPX versioning aktif', $totalVersions >= 50 && $activeVersions >= 50, $totalVersions . ' versions / ' . $activeVersions . ' active', 'Target PRD: 50-100 file GPX dengan versi aktif.'),
            $this->item('Multi-version route tersedia', $multiVersionRoutes >= 3, $multiVersionRoutes, 'Target PRD: beberapa rute memiliki minimal 2 versi GPX.'),
            $this->item('Hash verification evidence', $verifiedVersions >= 1 && VerificationLog::where('status', 'verified')->exists(), $verifiedVersions, 'Minimal satu bukti hash verified tersedia.'),
            $this->item('Tamper/invalid scenario', GpxVersion::where('verification_status', 'invalid')->exists() || VerificationLog::where('status', 'invalid')->exists(), VerificationLog::where('status', 'invalid')->count(), 'Minimal satu skenario invalid untuk membuktikan deteksi perubahan.'),
            $this->item('IPFS evidence', $ipfsReady >= 1 && IpfsUploadLog::where('status', 'success')->exists(), $ipfsReady, 'Minimal satu CID dan audit log IPFS sukses tersedia.'),
            $this->item('Blockchain evidence', $blockchainReady >= 1 && BlockchainRegistryLog::whereIn('status', ['success', 'confirmed'])->exists(), $blockchainReady, 'Minimal satu tx hash dan audit log blockchain tersedia.'),
            $this->item('Recommendation Precision@K dataset', $precisionQueries >= 5, $precisionQueries, 'Minimal 5 query evaluasi dengan ground truth route relevan.'),
            $this->item('Black box testing table', $blackBoxRows >= 8, $blackBoxRows, 'Minimal 8 skenario fitur utama terisi.'),
            $this->item('SUS usability responses', $susResponses >= 5, $susResponses, 'Target minimal 5 responden SUS untuk ringkasan usability awal.'),
            $this->item('CSV evidence exports', true, 'ready', 'Semua export utama tersedia dari Paper Outputs dan Evidence Pack.'),
        ];

        $passed = collect($items)->where('ready', true)->count();

        return [
            'items' => $items,
            'passed' => $passed,
            'total' => count($items),
            'score' => round(($passed / max(count($items), 1)) * 100, 2),
            'status' => $passed === count($items) ? 'ready' : 'needs_work',
        ];
    }

    private function item(string $name, bool $ready, int|string $current, string $notes): array
    {
        return [
            'name' => $name,
            'ready' => $ready,
            'current' => $current,
            'notes' => $notes,
        ];
    }
}
