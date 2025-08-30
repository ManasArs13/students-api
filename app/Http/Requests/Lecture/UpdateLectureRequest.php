<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLectureRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'topic' => 'sometimes|required|string|max:255|unique:lectures,topic,' . $this->route('lecture')->id,
            'description' => 'sometimes|required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
