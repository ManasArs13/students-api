<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentClassRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
