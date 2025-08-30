<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentClass\StoreStudentClassRequest;
use App\Http\Requests\StudentClass\UpdateClassCurriculumRequest;
use App\Http\Requests\StudentClass\UpdateStudentClassRequest;
use App\Http\Resources\StudentClassResource;
use App\Models\StudentClass;
use App\Services\StudentClassService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudentClassController extends Controller
{
    public function __construct(
        private readonly StudentClassService $classService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $classes = $this->classService->getAllClasses();
        return StudentClassResource::collection($classes);
    }

    public function store(StoreStudentClassRequest $request): StudentClassResource
    {
        $studentClass = $this->classService->createClass($request->validated());
        return new StudentClassResource($studentClass);
    }

    public function show(StudentClass $class): StudentClassResource
    {
        $studentClass = $this->classService->getClassWithStudents($class);
        return new StudentClassResource($studentClass);
    }

    public function update(UpdateStudentClassRequest $request, StudentClass $class): StudentClassResource
    {
        $studentClass = $this->classService->updateClass($class, $request->validated());
        return new StudentClassResource($studentClass);
    }

    public function destroy(StudentClass $class): JsonResponse
    {
        $this->classService->deleteClass($class);
        return response()->json(null, 204);
    }

    public function curriculum(StudentClass $class): StudentClassResource
    {
        $studentClass = $this->classService->getClassCurriculum($class);
        return new StudentClassResource($studentClass);
    }

    public function updateCurriculum(UpdateClassCurriculumRequest $request, StudentClass $class): StudentClassResource
    {
        $studentClass = $this->classService->updateCurriculum($class, $request->validated()['lectures']);
        return new StudentClassResource($studentClass->lectures);
    }
}
