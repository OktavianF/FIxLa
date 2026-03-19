<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the user model into a clean API response.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'name'                   => $this->name,
            'email'                  => $this->email,
            'role'                   => $this->role,
            'phone'                  => $this->phone,
            'avatar'                 => $this->avatar,
            'created_at'             => $this->created_at,
            'reports_count'          => $this->whenCounted('reports'),
            'completed_reports_count' => $this->whenCounted('completed_reports'),
        ];
    }
}
