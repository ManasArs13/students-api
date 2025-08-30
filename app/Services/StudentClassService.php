<?php

namespace App\Services;

use App\Models\StudentClass;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StudentClassService
{
    public function getAllClasses(): LengthAwarePaginator
    {
        return StudentClass::paginate(15);
    }

    public function getClassWithStudents($studentClass): StudentClass
    {
        return $studentClass->load('students');
    }

    public function getClassCurriculum(StudentClass $studentClass): StudentClass
    {
        return $studentClass->load('lectures');
    }

    public function createClass(array $data): StudentClass
    {
        return StudentClass::create($data);
    }

    public function updateClass(StudentClass $studentClass, array $data): StudentClass
    {
        $studentClass->update($data);
        return $studentClass;
    }

    public function deleteClass(StudentClass $studentClass): void
    {
        DB::transaction(function () use ($studentClass) {
            // Открепляем студентов от класса
            $studentClass->students()->update(['student_class_id' => null]);
            $studentClass->delete();
        });
    }

    public function updateCurriculum(StudentClass $studentClass, array $lecturesData): StudentClass
    {
        DB::transaction(function () use ($studentClass, $lecturesData) {
            // Очищаем текущий учебный план
            $studentClass->lectures()->detach();

            // Добавляем лекции с порядком
            $syncData = [];
            foreach ($lecturesData as $lectureData) {
                $syncData[$lectureData['lecture_id']] = ['order' => $lectureData['order']];
            }

            $studentClass->lectures()->sync($syncData);
        });

        return $class->load('lectures');
    }
}
