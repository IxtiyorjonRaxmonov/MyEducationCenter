<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "subject" => $this->subject,
            "teacher" => $this->group->users->role/* ->role === "Teacher" ? $this->user->name : null, */
        ];
    }
    
}
