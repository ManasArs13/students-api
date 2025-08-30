<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(
 *     schema="Student",
 *     type="object",
 *     title="Student",
 *     description="Модель студента",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Иван Иванов"),
 *     @OA\Property(property="email", type="string", format="email", example="ivan@example.com"),
 *     @OA\Property(property="student_class_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'student_class_id',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(StudentClass::class, 'student_class_id');
    }

    public function lectures(): BelongsToMany
    {
        return $this->belongsToMany(Lecture::class, 'student_lecture')
            ->withTimestamps();
    }
}
