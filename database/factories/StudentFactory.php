<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'student_class_id' => StudentClass::factory(),
        ];
    }

    public function withoutClass(): static
    {
        return $this->state(fn (array $attributes) => [
            'student_class_id' => null,
        ]);
    }
}
