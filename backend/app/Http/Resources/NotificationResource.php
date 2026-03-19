<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the notification model into a clean API response.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'message'    => $this->message,
            'type'       => $this->type,
            'is_read'    => $this->is_read,
            'report'     => $this->whenLoaded('report', fn () => [
                'id'      => $this->report->id,
                'address' => $this->report->address,
                'status'  => $this->report->status,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
