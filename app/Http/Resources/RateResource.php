<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'=>$this->user->name,
            'surname'=>$this->user->surname,
            'subject'=>$this->groupDate?->group->subject->subject,
            'group_name'=>$this->groupDate->group->group_name,
            'grade'=>$this->grade,
            'date'=>$this->groupDate->date,
        ];
    }
}
