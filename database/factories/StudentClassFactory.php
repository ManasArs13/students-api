<?php

namespace Database\Factories;

use App\Models\StudentClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentClassFactory extends Factory
{
    protected $model = StudentClass::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->regexify('[1-2][0-9]-[A-D]')
        ];
    }
}
