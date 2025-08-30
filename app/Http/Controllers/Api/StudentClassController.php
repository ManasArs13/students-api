<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentClass\StoreStudentClassRequest;
use App\Http\Requests\StudentClass\UpdateClassCurriculumRequest;
use App\Http\Requests\StudentClass\UpdateStudentClassRequest;
use App\Http\Resources\StudentClassResource;
use App\Models\StudentClass;
use App\Services\StudentClassService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Контроллер для управления классами студентов
 *
 * Обеспечивает CRUD операции для сущности StudentClass
 */
class StudentClassController extends Controller
{
    /**
     * Конструктор контроллера
     *
     * @param StudentClassService $classService Сервис для работы с классами
     */
    public function __construct(
        private readonly StudentClassService $classService
    ) {}

    /**
     * Получить список всех классов
     *
     * @return AnonymousResourceCollection Коллекция классов в формате ресурса
     */
    public function index(): AnonymousResourceCollection
    {
        $classes = $this->classService->getAllClasses();
        return StudentClassResource::collection($classes);
    }

    /**
     * Создать новый класс
     *
     * @param StoreStudentClassRequest $request Запрос с валидированными данными
     * @return StudentClassResource Ресурс созданного класса
     */
    public function store(StoreStudentClassRequest $request): StudentClassResource
    {
        $studentClass = $this->classService->createClass($request->validated());
        return new StudentClassResource($studentClass);
    }

    /**
     * Получить информацию о конкретном классе
     *
     * @param StudentClass $class Модель класса
     * @return StudentClassResource Ресурс класса с детальной информацией
     */
    public function show(StudentClass $class): StudentClassResource
    {
        $studentClass = $this->classService->getClassWithStudents($class);
        return new StudentClassResource($studentClass);
    }

    /**
     * Обновить информацию о классе
     *
     * @param UpdateStudentClassRequest $request Запрос с валидированными данными
     * @param StudentClass $class Модель класса
     * @return StudentClassResource Ресурс обновленного класса
     */
    public function update(UpdateStudentClassRequest $request, StudentClass $class): StudentClassResource
    {
        $studentClass = $this->classService->updateClass($class, $request->validated());
        return new StudentClassResource($studentClass);
    }

    /**
     * Удалить класс
     *
     * @param StudentClass $class Модель класса
     * @return JsonResponse Ответ с кодом 204 (No Content)
     * @throws \Exception
     */
    public function destroy(StudentClass $class): JsonResponse
    {
        $this->classService->deleteClass($class);
        return response()->json(null, 204);
    }

    /**
     * Получить учебный план класса
     *
     * @param StudentClass $class Модель класса
     * @return StudentClassResource Ресурс класса с учебным планом
     */
    public function curriculum(StudentClass $class): StudentClassResource
    {
        $studentClass = $this->classService->getClassCurriculum($class);
        return new StudentClassResource($studentClass);
    }

    /**
     * Обновить учебный план класса
     *
     * @param UpdateClassCurriculumRequest $request Запрос с данными учебного плана
     * @param StudentClass $class Модель класса
     * @return StudentClassResource Ресурс с обновленным учебным планом
     * @throws \Exception
     */
    public function updateCurriculum(UpdateClassCurriculumRequest $request, StudentClass $class): StudentClassResource
    {
        $studentClass = $this->classService->updateCurriculum($class, $request->validated()['lectures']);
        return new StudentClassResource($studentClass);
    }
}
