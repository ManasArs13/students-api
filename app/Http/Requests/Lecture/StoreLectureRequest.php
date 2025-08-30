<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreLectureRequest",
 *     type="object",
 *     required={"topic", "description"},
 *     @OA\Property(property="topic", type="string", example="Математика: Алгебра"),
 *     @OA\Property(property="description", type="string", example="Основы алгебраических выражений")
 * )
 */
class StoreLectureRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'topic' => 'required|string|max:255|unique:lectures,topic',
            'description' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
