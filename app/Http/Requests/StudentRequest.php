<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'student_class_id' => ['nullable', 'exists:student_classes'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
