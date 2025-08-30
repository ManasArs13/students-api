<?php

namespace App\Http\Requests\StudentClass;

use Illuminate\Foundation\Http\FormRequest;

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
