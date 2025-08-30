<?php

namespace App\Services;

use App\Models\Lecture;
use Illuminate\Pagination\LengthAwarePaginator;

class LectureService
{
    public function getAllLectures(): LengthAwarePaginator
    {
        return Lecture::paginate(15);
    }

    public function getLectureWithDetails($lecture): Lecture
    {
        return $lecture->load(['classes', 'students.class']);
    }

    public function createLecture(array $data): Lecture
    {
        return Lecture::create($data);
    }

    public function updateLecture(Lecture $lecture, array $data): Lecture
    {
        $lecture->update($data);
        return $lecture->load(['classes', 'students.class']);
    }

    public function deleteLecture(Lecture $lecture): void
    {
        $lecture->delete();
    }
}
