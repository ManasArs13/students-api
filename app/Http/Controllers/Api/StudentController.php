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

class StudentController extends Controller
{
    public function __construct(
        private readonly StudentService $studentService
    ) {}
    public function index(): AnonymousResourceCollection
    {
        $students = $this->studentService->getAllStudents();
        return StudentResource::collection($students);
    }

    public function store(StoreStudentRequest $request)
    {
        $student = $this->studentService->createStudent($request->validated());
        return new StudentResource($student);
    }

    public function show(Student $student): StudentResource
    {
        $student = $this->studentService->getStudentWithDetails($student);
        return new StudentResource($student);
    }

    public function update(UpdateStudentRequest $request, Student $student): StudentResource
    {
        $student = $this->studentService->updateStudent($student, $request->validated());
        return new StudentResource($student);
    }

    public function destroy(Student $student): JsonResponse
    {
        $this->studentService->deleteStudent($student);
        return response()->json(null, 204);
    }
}
