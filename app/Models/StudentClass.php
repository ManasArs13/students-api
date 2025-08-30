<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="StudentClass",
 *     type="object",
 *     title="StudentClass",
 *     description="Модель класса",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="10-A"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class StudentClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'student_class_id');
    }

    public function lectures(): BelongsToMany
    {
        return $this->belongsToMany(Lecture::class, 'student_class_lecture', 'student_class_id', 'lecture_id')
            ->withPivot('order')
            ->orderByPivot('order', 'asc');
    }
}
