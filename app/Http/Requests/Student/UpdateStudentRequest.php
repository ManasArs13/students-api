<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateStudentRequest",
 *     type="object",
 *     @OA\Property(property="name", type="string", example="Иван Иванов"),
 *     @OA\Property(property="student_class_id", type="integer", nullable=true, example=1)
 * )
 */
class UpdateStudentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'student_class_id' => 'nullable|exists:student_classes,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
