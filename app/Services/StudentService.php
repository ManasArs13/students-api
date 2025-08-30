<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Сервис для работы со студентами
 *
 * Содержит бизнес-логику операций с сущностью Student
 */
class StudentService
{
    /**
     * Получить всех студентов
     *
     * @return LengthAwarePaginator Коллекция всех студентов
     */
    public function getAllStudents(): LengthAwarePaginator
    {
        return Student::with('class')->paginate(15);
    }

    /**
     * Получить студента с детальной информацией
     *
     * @param Student $student Модель студента
     * @return Student Студент с загруженными связями
     */
    public function getStudentWithDetails($student): Student
    {
        return $student->load(['class', 'lectures']);
    }

    /**
     * Создать нового студента
     *
     * @param array $data Валидированные данные студента
     * @return Student Созданный студент
     */
    public function createStudent(array $data): Student
    {
        return Student::create($data);
    }

    /**
     * Обновить данные студента
     *
     * @param Student $student Модель студента
     * @param array $data Валидированные данные для обновления
     * @return Student Обновленный студент
     */
    public function updateStudent(Student $student, array $data): Student
    {
        $student->update($data);
        return $student->load(['class']);
    }

    /**
     * Удалить студента
     *
     * @param Student $student Модель студента
     * @return void
     */
    public function deleteStudent(Student $student): void
    {
        $student->delete();
    }
}
