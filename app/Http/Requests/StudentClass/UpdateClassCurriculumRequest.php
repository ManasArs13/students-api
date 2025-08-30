<?php

namespace App\Http\Requests\StudentClass;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateClassCurriculumRequest",
 *     type="object",
 *     required={"lectures"},
 *     @OA\Property(
 *         property="lectures",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={"lecture_id", "order"},
 *             @OA\Property(property="lecture_id", type="integer", example=1),
 *             @OA\Property(property="order", type="integer", example=0)
 *         )
 *     )
 * )
 */
class UpdateClassCurriculumRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'lectures' => 'required|array',
            'lectures.*.lecture_id' => 'required|exists:lectures,id',
            'lectures.*.order' => 'required|integer|min:0',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
