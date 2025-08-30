<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

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
