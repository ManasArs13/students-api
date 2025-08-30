<?php

namespace App\Http\Resources;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Student */
class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'class' => $this->whenLoaded('class', function () {
                return [
                    'id' => $this->class->id,
                    'name' => $this->class->name,
                ];
            }),
            'lectures' => $this->whenLoaded('lectures', function () {
                return $this->lectures->map(function ($lecture) {
                    return [
                        'id' => $lecture->id,
                        'topic' => $lecture->topic,
                        'description' => $lecture->description,
                        'listened_at' => $lecture->pivot->created_at->format('Y-m-d H:i:s'),
                    ];
                });
            }),
        ];
    }
}
