<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subject'=>$this->subject->subject,
            'level'=>$this->level->level,
            'group_name'=>$this->group_name,
            'start_time'=>$this->start_time,
            'end_time'=>$this->end_time,
        ];
    }
}
