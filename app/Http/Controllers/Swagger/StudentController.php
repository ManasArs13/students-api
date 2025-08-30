<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Students",
 *     description="Управление студентами"
 * )
 */
class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/students",
     *     summary="Получить список всех студентов",
     *     tags={"Students"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Student")
     *         )
     *     )
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/students",
     *     summary="Создать студента",
     *     tags={"Students"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreStudentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Студент создан",
     *         @OA\JsonContent(ref="#/components/schemas/Student")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */
    public function store()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/students/{id}",
     *     summary="Получить информацию о конкретном студенте",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/Student")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Студент не найден"
     *     )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Put(
     *     path="/api/students/{id}",
     *     summary="Обновить студента",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateStudentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Студент обновлен",
     *         @OA\JsonContent(ref="#/components/schemas/Student")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Студент не найден"
     *     )
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *     path="/api/students/{id}",
     *     summary="Удалить студента",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Студент удален"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Студент не найден"
     *     )
     * )
     */
    public function destroy()
    {
    }
}
