<?php

namespace App\Http\Resources;

use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StudentClass */
class StudentClassResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'students' => StudentResource::collection($this->whenLoaded('students')),
            'lectures' => $this->whenLoaded('lectures', function () {
                return $this->lectures->map(function ($lecture) {
                    return [
                        'id' => $lecture->id,
                        'topic' => $lecture->topic,
                        'description' => $lecture->description,
                        'order' => $lecture->pivot->order,
                    ];
                });
            }),
        ];
    }
}
