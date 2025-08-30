<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Classes",
 *     description="Управление классами"
 * )
 */
class StudentClassController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/classes",
     *     summary="Получить список всех классов",
     *     tags={"Classes"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/StudentClass")
     *         )
     *     )
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/classes",
     *     summary="Создать класс",
     *     tags={"Classes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="10-A")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Класс создан",
     *         @OA\JsonContent(ref="#/components/schemas/StudentClass")
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
     *     path="/api/classes/{id}",
     *     summary="Получить информацию о конкретном классе",
     *     tags={"Classes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/StudentClass"),
     *             @OA\Property(
     *                 property="students",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Student")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Класс не найден"
     *     )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Put(
     *     path="/api/classes/{id}",
     *     summary="Обновить класс",
     *     tags={"Classes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="10-B")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Класс обновлен",
     *         @OA\JsonContent(ref="#/components/schemas/StudentClass")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Класс не найден"
     *     )
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *     path="/api/classes/{id}",
     *     summary="Удалить класс",
     *     tags={"Classes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Класс удален"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Класс не найден"
     *     )
     * )
     */
    public function destroy()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/classes/{id}/curriculum",
     *     summary="Получить учебный план класса",
     *     tags={"Classes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Учебный план",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/StudentClass"),
     *             @OA\Property(
     *                 property="lectures",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="topic", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="order", type="integer")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Класс не найден"
     *     )
     * )
     */
    public function curriculum()
    {
    }

    /**
     * @OA\Put(
     *     path="/api/classes/{id}/curriculum",
     *     summary="Обновить учебный план класса",
     *     tags={"Classes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateClassCurriculumRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Учебный план обновлен",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="topic", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="order", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Класс не найден"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */
    public function updateCurriculum()
    {
    }
}
