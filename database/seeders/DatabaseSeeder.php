<?php

namespace Database\Seeders;

use App\Models\Lecture;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем классы
        $classes = StudentClass::factory()->count(3)->create();

        // Создаем лекции
        $lectures = Lecture::factory()->count(5)->create();

        // Создаем студентов для каждого класса
        foreach ($classes as $class) {
            $students = Student::factory()->count(8)->create([
                'student_class_id' => $class->id,
            ]);

            // Создаем учебный план для класса (5-8 лекций в случайном порядке)
            $classLectures = $lectures->random(2);
            $order = 1;

            foreach ($classLectures as $lecture) {
                $class->lectures()->attach($lecture->id, ['order' => $order++]);
            }

            // Для каждого студента отмечаем прослушанные лекции (3-7 лекций)
            foreach ($students as $student) {
                $attendedLectures = $classLectures->random(1);
                $student->lectures()->attach($attendedLectures->pluck('id'));
            }
        }

        // Создаем несколько студентов без класса
        Student::factory()->count(5)->create([
            'student_class_id' => null,
        ]);
    }
}
