<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'difficulty' => $this->difficulty,
            'max_score' => $this->max_score,
            'school_id' => $this->school_id,
            'content_id' => $this->content_id,
            'school' => new SchoolResource($this->whenLoaded('school')),
            'content' => new ContentResource($this->whenLoaded('content')),
            'student_activities_count' => $this->whenCounted('studentActivities'),
            'forum_posts_count' => $this->whenCounted('forumPosts'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}