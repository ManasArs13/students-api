<?php

namespace Database\Factories;

use App\Models\Lecture;
use Illuminate\Database\Eloquent\Factories\Factory;

class LectureFactory extends Factory
{
    protected $model = Lecture::class;

    public function definition(): array
    {
        $subjects = ['Математика', 'Физика', 'Химия', 'Биология', 'История', 'Литература', 'Информатика', 'Английский'];

        $subject = $this->faker->randomElement($subjects);

        return [
            'topic' => $subject . ': ' . $this->faker->sentence(6),
            'description' => $this->faker->sentence(10),
        ];
    }
}
