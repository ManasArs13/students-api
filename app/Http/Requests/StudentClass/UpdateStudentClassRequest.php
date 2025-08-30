<?php

namespace App\Http\Requests\StudentClass;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentClassRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:student_classes,name,' . $this->route('class')->id,
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
