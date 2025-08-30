<?php

namespace App\Services;

use App\Models\Lecture;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Сервис для работы с лекциями
 *
 * Содержит бизнес-логику операций с сущностью Lecture
 */
class LectureService
{
    /**
     * Получить все лекции
     *
     * @return LengthAwarePaginator Коллекция всех лекций
     */
    public function getAllLectures(): LengthAwarePaginator
    {
        return Lecture::paginate(15);
    }

    /**
     * Получить лекцию с детальной информацией
     *
     * @param Lecture $lecture Модель лекции
     * @return Lecture Лекция с загруженными связями
     */
    public function getLectureWithDetails($lecture): Lecture
    {
        return $lecture->load(['classes', 'students.class']);
    }

    /**
     * Создать новую лекцию
     *
     * @param array $data Валидированные данные лекции
     * @return Lecture Созданная лекция
     */
    public function createLecture(array $data): Lecture
    {
        return Lecture::create($data);
    }

    /**
     * Обновить данные лекции
     *
     * @param Lecture $lecture Модель лекции
     * @param array $data Валидированные данные для обновления
     * @return Lecture Обновленная лекция
     */
    public function updateLecture(Lecture $lecture, array $data): Lecture
    {
        $lecture->update($data);
        return $lecture->load(['classes', 'students.class']);
    }

    /**
     * Удалить лекцию
     *
     * @param Lecture $lecture Модель лекции
     * @return void
     */
    public function deleteLecture(Lecture $lecture): void
    {
        $lecture->delete();
    }
}
