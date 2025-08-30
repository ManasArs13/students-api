<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Контроллер для управления студентами
 *
 * Обеспечивает CRUD операции для сущности Student
 */
class StudentController extends Controller
{
    /**
     * Конструктор контроллера
     *
     * @param StudentService $studentService Сервис для работы со студентами
     */
    public function __construct(
        private readonly StudentService $studentService
    ) {
    }

    /**
     * Получить список всех студентов
     *
     * @return AnonymousResourceCollection Коллекция студентов в формате ресурса
     */
    public function index(): AnonymousResourceCollection
    {
        $students = $this->studentService->getAllStudents();
        return StudentResource::collection($students);
    }

    /**
     * Создать нового студента
     *
     * @param StoreStudentRequest $request Запрос с валидированными данными
     * @return StudentResource Ресурс созданного студента
     */
    public function store(StoreStudentRequest $request)
    {
        $student = $this->studentService->createStudent($request->validated());
        return new StudentResource($student);
    }

    /**
     * Получить информацию о конкретном студенте
     *
     * @param Student $student Модель студента
     * @return StudentResource Ресурс студента с детальной информацией
     */
    public function show(Student $student): StudentResource
    {
        $student = $this->studentService->getStudentWithDetails($student);
        return new StudentResource($student);
    }

    /**
     * Обновить информацию о студенте
     *
     * @param UpdateStudentRequest $request Запрос с валидированными данными
     * @param Student $student Модель студента
     * @return StudentResource Ресурс обновленного студента
     */
    public function update(UpdateStudentRequest $request, Student $student): StudentResource
    {
        $student = $this->studentService->updateStudent($student, $request->validated());
        return new StudentResource($student);
    }

    /**
     * Удалить студента
     *
     * @param Student $student Модель студента
     * @return JsonResponse Ответ с кодом 204 (No Content)
     */
    public function destroy(Student $student): JsonResponse
    {
        $this->studentService->deleteStudent($student);
        return response()->json(null, 204);
    }
}
