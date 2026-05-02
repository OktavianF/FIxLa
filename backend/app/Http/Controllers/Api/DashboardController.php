<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get overview statistics for the admin dashboard.
     */
    public function overview(): JsonResponse
    {
        $avgResponseDays = Report::whereNotNull('verified_at')
            ->selectRaw('AVG(EXTRACT(EPOCH FROM (verified_at - created_at)) / 86400) as avg_days')
            ->value('avg_days');

        $stats = [
            'total_reports'        => Report::count(),
            'in_progress'          => Report::whereNotIn('status', ['submitted', 'completed'])->count(),
            'completed_this_month' => Report::where('status', 'completed')
                ->whereMonth('completed_at', now()->month)
                ->whereYear('completed_at', now()->year)
                ->count(),
            'avg_response_days'    => round($avgResponseDays ?? 0, 1),
            'total_estimated_cost' => Report::sum('estimated_cost_asphalt'),
            'ai_reports_count'     => Report::where('is_ai_classified', true)->count(),
        ];

        return response()->json([
            'success' => true,
            'data'    => $stats,
        ]);
    }

    /**
     * Get report count grouped by district.
     */
    public function reportsByDistrict(): JsonResponse
    {
        $data = Report::select('district', DB::raw('COUNT(*) as total'))
            ->whereNotNull('district')
            ->groupBy('district')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Get report count grouped by damage level.
     */
    public function damageDistribution(): JsonResponse
    {
        $data = Report::select('damage_level', DB::raw('COUNT(*) as total'))
            ->groupBy('damage_level')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Get top reports ranked by priority score.
     */
    public function priorityRanking(Request $request): JsonResponse
    {
        $limit = $request->integer('limit', 10);

        $reports = Report::with('photos')
            ->where('status', '!=', 'completed')
            ->orderByDesc('priority_score')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $reports,
        ]);
    }

    /**
     * Get coordinate data for heatmap visualization.
     */
    public function heatmapData(): JsonResponse
    {
        $data = Report::select('latitude', 'longitude', 'priority_score', 'damage_level')
            ->where('status', '!=', 'completed')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Get monthly trend of reports (last 12 months).
     */
    public function monthlyTrend(): JsonResponse
    {
        $data = Report::select(
            DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
            DB::raw('COUNT(*) as total'),
            DB::raw("COUNT(*) FILTER (WHERE status = 'completed') as completed")
        )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Get summary of repair costs from reports.
     */
    public function costSummary(): JsonResponse
    {
        $reports = Report::whereNotNull('estimated_cost_asphalt')
            ->where('status', '!=', 'completed')
            ->select([
                'id', 'address', 'district', 'damage_level',
                'road_length', 'road_width',
                'estimated_cost_asphalt', 'estimated_cost_concrete',
                'confidence_score', 'is_ai_classified'
            ])
            ->orderByDesc('estimated_cost_asphalt')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'reports' => $reports,
                'total_asphalt' => $reports->sum('estimated_cost_asphalt'),
                'total_concrete' => $reports->sum('estimated_cost_concrete'),
                'count' => $reports->count(),
            ],
        ]);
    }
}