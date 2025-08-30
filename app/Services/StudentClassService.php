<?php

namespace App\Services;

use App\Models\StudentClass;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Сервис для работы с классами студентов
 *
 * Содержит бизнес-логику операций с сущностью StudentClass
 */
class StudentClassService
{
    /**
     * Получить все классы
     *
     * @return LengthAwarePaginator Коллекция всех классов
     */
    public function getAllClasses(): LengthAwarePaginator
    {
        return StudentClass::paginate(15);
    }

    /**
     * Получить класс с информацией о студентах
     *
     * @param StudentClass $class Модель класса
     * @return StudentClass Класс с загруженными студентами
     */
    public function getClassWithStudents($class): StudentClass
    {
        return $class->load('students');
    }

    /**
     * Получить учебный план класса
     *
     * @param StudentClass $class Модель класса
     * @return StudentClass Класс с загруженным учебным планом
     */
    public function getClassCurriculum(StudentClass $class): StudentClass
    {
        return $class->load('lectures');
    }

    /**
     * Создать новый класс
     *
     * @param array $data Валидированные данные класса
     * @return StudentClass Созданный класс
     */
    public function createClass(array $data): StudentClass
    {
        return StudentClass::create($data);
    }

    /**
     * Обновить данные класса
     *
     * @param StudentClass $class Модель класса
     * @param array $data Валидированные данные для обновления
     * @return StudentClass Обновленный класс
     */
    public function updateClass(StudentClass $class, array $data): StudentClass
    {
        $class->update($data);
        return $class;
    }

    /**
     * Удалить класс
     *
     * @param StudentClass $class Модель класса
     * @return void
     */
    public function deleteClass(StudentClass $class): void
    {
        try {
            DB::transaction(function () use ($class) {
                $class->students()->update(['student_class_id' => null]);
                $class->delete();
            });
        } catch (Exception $e) {
            throw new Exception("Failed to delete class: " . $e->getMessage());
        }
    }

    /**
     * Обновить учебный план класса
     *
     * @param StudentClass $class Модель класса
     * @param array $lecturesData Данные лекций для учебного плана
     * @return StudentClass Класс с обновленным учебным планом
     * @throws \Throwable
     */
    public function updateCurriculum(StudentClass $class, array $lecturesData): StudentClass
    {
        try {
            DB::transaction(function () use ($class, $lecturesData) {
                $class->lectures()->detach();

                $syncData = [];

                foreach ($lecturesData as $lectureData) {
                    $syncData[$lectureData['lecture_id']] = ['order' => $lectureData['order']];
                }

                $class->lectures()->sync($syncData);
            });

            return $class->load('lectures');
        } catch (Exception $e) {
            throw new Exception("Failed to update curriculum: " . $e->getMessage());
        }
    }
}
