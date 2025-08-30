<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
            ->orderBy('order');
    }
}
