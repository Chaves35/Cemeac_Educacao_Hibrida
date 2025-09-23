<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'activity_id' => $this->activity_id,
            'status' => $this->status,
            'score' => $this->score,
            'attempts' => $this->attempts,
            'started_at' => $this->started_at,
            'completed_at' => $this->completed_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'activity' => new ActivityResource($this->whenLoaded('activity')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}