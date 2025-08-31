<?php

namespace Tests\Unit;

use App\Models\Lecture;
use App\Models\Student;
use App\Models\StudentClass;
use App\Services\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;


class StudentServiceTest extends TestCase
{
    use RefreshDatabase;

    private StudentService $studentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->studentService = new StudentService();
    }

    #[Test]
    public function it_can_get_all_students()
    {
        Student::factory()->count(3)->create();

        $students = $this->studentService->getAllStudents();

        $this->assertCount(3, $students);
    }

    #[Test]
    public function it_can_create_a_student()
    {
        $data = [
            'name' => 'Test Student',
            'email' => 'test@example.com',
            'student_class_id' => null
        ];

        $student = $this->studentService->createStudent($data);

        $this->assertDatabaseHas('students', $data);
        $this->assertEquals('Test Student', $student->name);
    }

    #[Test]
    public function it_can_update_a_student()
    {
        $student = Student::factory()->create();
        $newClass = StudentClass::factory()->create();

        $updatedStudent = $this->studentService->updateStudent($student, [
            'name' => 'Updated Name',
            'student_class_id' => $newClass->id
        ]);

        $this->assertEquals('Updated Name', $updatedStudent->name);
        $this->assertEquals($newClass->id, $updatedStudent->student_class_id);
    }

    #[Test]
    public function it_can_delete_a_student()
    {
        $student = Student::factory()->create();

        $this->studentService->deleteStudent($student);

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    #[Test]
    public function it_can_get_student_with_details()
    {
        $class = StudentClass::factory()->create();
        $student = Student::factory()->create(['student_class_id' => $class->id]);
        $lecture = Lecture::factory()->create();
        $student->lectures()->attach($lecture->id);

        $studentWithDetails = $this->studentService->getStudentWithDetails($student);

        $this->assertTrue($studentWithDetails->relationLoaded('class'));
        $this->assertTrue($studentWithDetails->relationLoaded('lectures'));
        $this->assertCount(1, $studentWithDetails->lectures);
    }
}
