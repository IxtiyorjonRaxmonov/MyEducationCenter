<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentRateResource extends JsonResource
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
            'group_name'=>$this->groupDate->group->group_name,
            'grade'=>$this->grade,
            'date'=>$this->groupDate->date,
            'subject'=>$this->groupDate?->group->subject->subject,
            'is_good'=>$this->grade > 80 ? "Excellent job" : ($this->grade > 60 ? "good" : "study more!!!")
        ];
    }

}
