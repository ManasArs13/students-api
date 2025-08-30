<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(
 *     schema="Lecture",
 *     type="object",
 *     title="Lecture",
 *     description="Модель лекции",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="topic", type="string", example="Математика: Алгебра"),
 *     @OA\Property(property="description", type="string", example="Основы алгебраических выражений"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic',
        'description',
    ];

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(StudentClass::class, 'student_class_lecture', 'lecture_id', 'student_class_id')
            ->withPivot('order');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_lecture')
            ->withTimestamps();
    }
}
