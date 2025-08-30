<?php

use App\Http\Controllers\Api\LectureController;
use App\Http\Controllers\Api\StudentClassController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

// Студенты
Route::apiResource('students', StudentController::class);

// Лекции
Route::apiResource('lectures', LectureController::class);

// Класс
Route::apiResource('classes', StudentClassController::class);
Route::prefix('classes/{class}')->group(function () {
    Route::get('curriculum', [StudentClassController::class, 'curriculum']);
    Route::put('curriculum', [StudentClassController::class, 'updateCurriculum']);
});
