<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'url' => $this->url,
            'file_path' => $this->file_path,
            'school_id' => $this->school_id,
            'school' => new SchoolResource($this->whenLoaded('school')),
            'activities_count' => $this->whenCounted('activities'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}