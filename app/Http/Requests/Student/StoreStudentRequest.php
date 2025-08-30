<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

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
