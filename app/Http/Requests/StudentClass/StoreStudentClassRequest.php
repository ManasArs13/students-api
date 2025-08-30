<?php

namespace App\Http\Requests\StudentClass;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreStudentClassRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="10-A")
 * )
 */
class StoreStudentClassRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:student_classes,name',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
