<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StudentService
{
    public function getAllStudents(): LengthAwarePaginator
    {
        return Student::with('class')->paginate(15);
    }

    public function getStudentWithDetails($student): Student
    {
        return $student->load(['class', 'lectures']);
    }

    public function createStudent(array $data): Student
    {
        return Student::create($data);
    }

    public function updateStudent(Student $student, array $data): Student
    {
        $student->update($data);
        return $student->load(['class']);
    }

    public function deleteStudent(Student $student): void
    {
        $student->delete();
    }
}
