<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Http\Resources\ReportResource;
use App\Models\AppNotification;
use App\Models\Report;
use App\Models\ReportPhoto;
use App\Models\StatusHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * List all reports with optional filters (status, damage_level, district).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Report::with(['photos', 'user:id,name']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('damage_level')) {
            $query->where('damage_level', $request->damage_level);
        }
        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('address', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $reports = $query->orderByDesc('created_at')->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $reports,
        ]);
    }

    /**
     * Get reports for map display with optional geographic bounds filter.
     */
    public function mapReports(Request $request): JsonResponse
    {
        $query = Report::select([
            'id', 'latitude', 'longitude', 'damage_level',
            'status', 'priority_score', 'address', 'created_at',
        ]);

        if ($request->filled('bounds')) {
            $bounds = $request->bounds;
            $query->whereBetween('latitude', [$bounds['south'], $bounds['north']])
                ->whereBetween('longitude', [$bounds['west'], $bounds['east']]);
        }

        return response()->json([
            'success' => true,
            'data'    => $query->get(),
        ]);
    }

    /**
     * Create a new damage report with photos.
     */
    public function store(StoreReportRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $nearbyCount = Report::nearby($validated['latitude'], $validated['longitude'])->count();

        $report = Report::create([
            'user_id'      => $request->user()->id,
            'latitude'     => $validated['latitude'],
            'longitude'    => $validated['longitude'],
            'address'      => $validated['address'] ?? null,
            'district'     => $validated['district'] ?? null,
            'damage_level' => $validated['damage_level'],
            'description'  => $validated['description'] ?? null,
            'road_length' => $validated['road_length'],
            'road_width'   => $validated['road_width'],
            'confidence_score' => $validated['confidence_score'] ?? null,
            'is_ai_classified' => $validated['is_ai_classified'] ?? false,
            'report_count' => $nearbyCount + 1,
            'status'       => 'submitted',
        ]);

        $report->priority_score = $report->calculatePriorityScore();
        $report->calculateCostEstimation();
        $report->save();

        $this->uploadPhotos($request, $report);

        StatusHistory::create([
            'report_id'   => $report->id,
            'changed_by'  => $request->user()->id,
            'from_status' => null,
            'to_status'   => 'submitted',
            'notes'       => 'Laporan dibuat',
        ]);

        // Update nearby report counts
        Report::nearby($validated['latitude'], $validated['longitude'])
            ->where('id', '!=', $report->id)
            ->increment('report_count');

        $report->load('photos');

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim',
            'data'    => new ReportResource($report),
        ], 201);
    }

    /**
     * Show a single report with all related data.
     */
    public function show(Report $report): JsonResponse
    {
        $report->load([
            'photos',
            'user:id,name',
            'statusHistories' => fn ($q) => $q->orderBy('created_at'),
        ]);

        return response()->json([
            'success' => true,
            'data'    => new ReportResource($report),
        ]);
    }

    /**
     * Get authenticated user's reports with optional status filter.
     */
    public function myReports(Request $request): JsonResponse
    {
        $query = $request->user()->reports()->with('photos');

        if ($request->filled('status')) {
            match ($request->status) {
                'proses' => $query->whereNotIn('status', ['submitted', 'completed']),
                'selesai' => $query->where('status', 'completed'),
                default  => $query->where('status', $request->status),
            };
        }

        $reports = $query->orderByDesc('created_at')->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $reports,
        ]);
    }

    /**
     * Update a report's status (admin only) and notify the report owner.
     */
    public function updateStatus(UpdateStatusRequest $request, Report $report): JsonResponse
    {
        $validated  = $request->validated();
        $fromStatus = $report->status;

        $report->status = $validated['status'];
        $this->setStatusTimestamp($report, $validated['status']);
        $report->save();

        StatusHistory::create([
            'report_id'   => $report->id,
            'changed_by'  => $request->user()->id,
            'from_status' => $fromStatus,
            'to_status'   => $validated['status'],
            'notes'       => $validated['notes'] ?? null,
        ]);

        $this->notifyReportOwner($report, $validated['status']);

        return response()->json([
            'success' => true,
            'message' => 'Status laporan diperbarui',
            'data'    => new ReportResource($report->fresh(['photos', 'statusHistories'])),
        ]);
    }

    // ─── Private Helpers ────────────────────────────────────────

    /**
     * Upload report photos to storage.
     */
    private function uploadPhotos(Request $request, Report $report): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        foreach ($request->file('photos') as $index => $photo) {
            $path = $photo->store('reports/' . $report->id, 'public');

            ReportPhoto::create([
                'report_id'     => $report->id,
                'photo_path'    => $path,
                'original_name' => $photo->getClientOriginalName(),
                'is_primary'    => $index === 0,
            ]);
        }
    }

    /**
     * Set the appropriate timestamp based on the new status.
     */
    private function setStatusTimestamp(Report $report, string $status): void
    {
        match ($status) {
            'verified'     => $report->verified_at = now(),
            'scheduled'    => $report->scheduled_at = now(),
            'under_repair' => $report->repair_started_at = now(),
            'completed'    => $report->completed_at = now(),
            default        => null,
        };
    }

    /**
     * Send a notification to the report owner when the status changes.
     */
    private function notifyReportOwner(Report $report, string $status): void
    {
        $labels = [
            'verified'     => 'Laporan telah diverifikasi',
            'scheduled'    => 'Perbaikan telah dijadwalkan',
            'under_repair' => 'Perbaikan sedang dilakukan',
            'completed'    => 'Jalan telah selesai diperbaiki',
        ];

        if (! isset($labels[$status])) {
            return;
        }

        AppNotification::create([
            'user_id'   => $report->user_id,
            'report_id' => $report->id,
            'title'     => $labels[$status],
            'message'   => "Status laporan #{$report->id} di {$report->address} telah diperbarui.",
            'type'      => 'status_update',
        ]);
    }

    /**
     * Delete a report and all related data (admin only).
     */
    public function destroy(Report $report): JsonResponse
    {
        // Delete photo files from storage
        foreach ($report->photos as $photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->photo_path);
        }

        // Delete related records (photos, status histories, notifications)
        $report->photos()->delete();
        $report->statusHistories()->delete();
        $report->notifications()->delete();
        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus',
        ]);
    }
}