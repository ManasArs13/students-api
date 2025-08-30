<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Lectures",
 *     description="Управление лекциями"
 * )
 */
class LectureController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/lectures",
     *     summary="Получить список всех лекций",
     *     tags={"Lectures"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Lecture")
     *         )
     *     )
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/lectures",
     *     summary="Создать лекцию",
     *     tags={"Lectures"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreLectureRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Лекция создана",
     *         @OA\JsonContent(ref="#/components/schemas/Lecture")
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
     *     path="/api/lectures/{id}",
     *     summary="Получить информацию о конкретной лекции",
     *     tags={"Lectures"},
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
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="topic", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(
     *                 property="classes",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="order", type="integer")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="students",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="class", type="string", nullable=true),
     *                     @OA\Property(property="listened_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Лекция не найдена"
     *     )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Put(
     *     path="/api/lectures/{id}",
     *     summary="Обновить лекцию",
     *     tags={"Lectures"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateLectureRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Лекция обновлена",
     *         @OA\JsonContent(ref="#/components/schemas/Lecture")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Лекция не найдена"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *     path="/api/lectures/{id}",
     *     summary="Удалить лекцию",
     *     tags={"Lectures"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Лекция удалена"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Лекция не найдена"
     *     )
     * )
     */
    public function destroy()
    {
    }
}
