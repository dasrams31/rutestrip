<?php

namespace App\Services;

use App\Models\HikingRoute;
use App\Models\RecommendationEvaluationQuery;
use Illuminate\Support\Collection;

class RecommendationEvaluationService
{
    public function __construct(private PythonProcessorService $pythonService)
    {
    }

    public function evaluate(): array
    {
        $routes = HikingRoute::whereNotNull('sbert_embedding')->get();
        $queries = RecommendationEvaluationQuery::query()->oldest()->get();

        $rows = $queries->map(function (RecommendationEvaluationQuery $query) use ($routes) {
            return $this->evaluateQuery($query, $routes);
        });

        return [
            'rows' => $rows,
            'average_precision_at_5' => round((float) $rows->avg('precision_at_5'), 4),
            'average_precision_at_10' => round((float) $rows->avg('precision_at_10'), 4),
        ];
    }

    public function evaluateQuery(RecommendationEvaluationQuery $query, Collection $routes): array
    {
        $queryEmbedding = $query->query_embedding ?: $this->pythonService->getQueryEmbedding($query->query);
        $relevantIds = collect($query->relevant_route_ids)->map(fn ($id) => (int) $id)->all();

        $ranked = $routes
            ->map(function (HikingRoute $route) use ($queryEmbedding) {
                return [
                    'route_id' => $route->id,
                    'route_name' => $route->name,
                    'cosine_value' => round($this->cosineSimilarity($queryEmbedding, $route->sbert_embedding ?: []), 4),
                ];
            })
            ->filter(fn (array $row) => $row['cosine_value'] > 0)
            ->sortByDesc('cosine_value')
            ->values();

        return [
            'query_id' => $query->id,
            'query' => $query->query,
            'relevant_route_ids' => $relevantIds,
            'top_5_route_ids' => $ranked->take(5)->pluck('route_id')->all(),
            'top_10_route_ids' => $ranked->take(10)->pluck('route_id')->all(),
            'top_5_route_names' => $ranked->take(5)->pluck('route_name')->all(),
            'top_10_route_names' => $ranked->take(10)->pluck('route_name')->all(),
            'precision_at_5' => $this->precisionAt($ranked, $relevantIds, 5),
            'precision_at_10' => $this->precisionAt($ranked, $relevantIds, 10),
            'notes' => $query->notes,
        ];
    }

    private function precisionAt(Collection $ranked, array $relevantIds, int $k): float
    {
        if ($k <= 0 || $ranked->isEmpty()) {
            return 0.0;
        }

        $hits = $ranked
            ->take($k)
            ->filter(fn (array $row) => in_array((int) $row['route_id'], $relevantIds, true))
            ->count();

        return round($hits / $k, 4);
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        $length = min(count($a), count($b));

        for ($i = 0; $i < $length; $i++) {
            $dotProduct += $a[$i] * $b[$i];
            $normA += $a[$i] * $a[$i];
            $normB += $b[$i] * $b[$i];
        }

        $denominator = sqrt($normA) * sqrt($normB);

        return $denominator > 0 ? $dotProduct / $denominator : 0.0;
    }
}
