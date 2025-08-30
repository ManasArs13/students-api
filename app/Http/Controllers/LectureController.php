<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lecture\StoreLectureRequest;
use App\Http\Requests\Lecture\UpdateLectureRequest;
use App\Http\Resources\LectureResource;
use App\Models\Lecture;
use App\Services\LectureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LectureController extends Controller
{
    public function __construct(
        private readonly LectureService $lectureService
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        $lectures = $this->lectureService->getAllLectures();
        return LectureResource::collection($lectures);
    }

    public function store(StoreLectureRequest $request): LectureResource
    {
        $lecture = $this->lectureService->createLecture($request->validated());
        return new LectureResource($lecture);
    }

    public function show(Lecture $lecture): LectureResource
    {
        $lecture = $this->lectureService->getLectureWithDetails($lecture);
        return new LectureResource($lecture);
    }

    public function update(UpdateLectureRequest $request, Lecture $lecture): LectureResource
    {
        $lecture = $this->lectureService->updateLecture($lecture, $request->validated());
        return new LectureResource($lecture);
    }

    public function destroy(Lecture $lecture): JsonResponse
    {
        $this->lectureService->deleteLecture($lecture);
        return response()->json(null, 204);
    }
}
