<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the report model into a clean API response.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'user_id'            => $this->user_id,
            'latitude'           => $this->latitude,
            'longitude'          => $this->longitude,
            'address'            => $this->address,
            'district'           => $this->district,
            'damage_level'       => $this->damage_level,
            'description'        => $this->description,
            'road_length'        => $this->road_length,
            'road_width'         => $this->road_width,
            'priority_score'     => $this->priority_score,
            'report_count'       => $this->report_count,
            'traffic_level'      => $this->traffic_level,
            'facility_proximity' => $this->facility_proximity,
            'status'             => $this->status,
            'verified_at'        => $this->verified_at,
            'scheduled_at'       => $this->scheduled_at,
            'repair_started_at'  => $this->repair_started_at,
            'completed_at'       => $this->completed_at,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
            'user'               => new UserResource($this->whenLoaded('user')),
            'photos'             => $this->whenLoaded('photos'),
            'status_histories'   => $this->whenLoaded('statusHistories'),
        ];
    }
}
