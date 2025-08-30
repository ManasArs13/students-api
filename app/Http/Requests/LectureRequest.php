<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LectureRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'topic' => ['required'],
            'description' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
