<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreStudentRequest",
 *     type="object",
 *     required={"name", "email"},
 *     @OA\Property(property="name", type="string", example="Иван Иванов"),
 *     @OA\Property(property="email", type="string", format="email", example="ivan@example.com"),
 *     @OA\Property(property="student_class_id", type="integer", nullable=true, example=1)
 * )
 */
class StoreStudentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'student_class_id' => 'nullable|exists:student_classes,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
