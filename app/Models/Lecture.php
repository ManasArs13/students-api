<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
