<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\StoreLectureRequest;
use App\Http\Requests\Lecture\UpdateLectureRequest;
use App\Http\Resources\LectureResource;
use App\Models\Lecture;
use App\Services\LectureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Контроллер для управления лекциями
 *
 * Обеспечивает CRUD операции для сущности Lecture
 */
class LectureController extends Controller
{
    /**
     * Конструктор контроллера
     *
     * @param LectureService $lectureService Сервис для работы с лекциями
     */
    public function __construct(
        private readonly LectureService $lectureService
    ) {
    }

    /**
     * Получить список всех лекций
     *
     * @return AnonymousResourceCollection Коллекция лекций в формате ресурса
     */
    public function index(): AnonymousResourceCollection
    {
        $lectures = $this->lectureService->getAllLectures();
        return LectureResource::collection($lectures);
    }

    /**
     * Создать новую лекцию
     *
     * @param StoreLectureRequest $request Запрос с валидированными данными
     * @return LectureResource Ресурс созданной лекции
     */
    public function store(StoreLectureRequest $request): LectureResource
    {
        $lecture = $this->lectureService->createLecture($request->validated());
        return new LectureResource($lecture);
    }

    /**
     * Получить информацию о конкретной лекции
     *
     * @param Lecture $lecture Модель лекции
     * @return LectureResource Ресурс лекции с детальной информацией
     */
    public function show(Lecture $lecture): LectureResource
    {
        $lecture = $this->lectureService->getLectureWithDetails($lecture);
        return new LectureResource($lecture);
    }

    /**
     * Обновить информацию о лекции
     *
     * @param UpdateLectureRequest $request Запрос с валидированными данными
     * @param Lecture $lecture Модель лекции
     * @return LectureResource Ресурс обновленной лекции
     */
    public function update(UpdateLectureRequest $request, Lecture $lecture): LectureResource
    {
        $lecture = $this->lectureService->updateLecture($lecture, $request->validated());
        return new LectureResource($lecture);
    }

    /**
     * Удалить лекцию
     *
     * @param Lecture $lecture Модель лекции
     * @return JsonResponse Ответ с кодом 204 (No Content)
     */
    public function destroy(Lecture $lecture): JsonResponse
    {
        $this->lectureService->deleteLecture($lecture);
        return response()->json(null, 204);
    }
}
