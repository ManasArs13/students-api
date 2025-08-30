<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

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
