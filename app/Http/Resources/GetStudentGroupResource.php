<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetStudentGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "subject" => $this->group->subject->subject,
            "group_name" => $this->group->group_name,
            "start_time" => $this->group->start_time,
            "end_time" => $this->group->end_time,

        ];
    }
}
