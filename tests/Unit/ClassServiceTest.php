<?php

namespace Tests\Unit;

use App\Models\Lecture;
use App\Models\Student;
use App\Models\StudentClass;
use App\Services\StudentClassService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClassServiceTest extends TestCase
{
    use RefreshDatabase;

    private StudentClassService $classService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->classService = new StudentClassService();
    }

    #[Test]
    public function it_can_get_all_classes()
    {
        StudentClass::factory()->count(3)->create();

        $classes = $this->classService->getAllClasses();

        $this->assertCount(3, $classes);
        $this->assertInstanceOf(StudentClass::class, $classes->first());
    }

    #[Test]
    public function it_can_create_a_class()
    {
        $data = [
            'name' => '10-A'
        ];

        $class = $this->classService->createClass($data);

        $this->assertDatabaseHas('student_classes', $data);
        $this->assertEquals('10-A', $class->name);
    }

    #[Test]
    public function it_can_update_a_class()
    {
        $class = StudentClass::factory()->create();

        $updatedClass = $this->classService->updateClass($class, [
            'name' => '10-B'
        ]);

        $this->assertEquals('10-B', $updatedClass->name);
    }

    #[Test]
    public function it_can_delete_a_class()
    {
        $class = StudentClass::factory()->create();

        $this->classService->deleteClass($class);

        $this->assertDatabaseMissing('student_classes', ['id' => $class->id]);
    }

    #[Test]
    public function it_detaches_students_when_deleting_class()
    {
        $class = StudentClass::factory()->create();
        $student = Student::factory()->create(['student_class_id' => $class->id]);

        $this->classService->deleteClass($class);

        $this->assertNull($student->fresh()->class_id);
    }

    #[Test]
    public function it_can_get_class_with_students()
    {
        $class = StudentClass::factory()->create();
        Student::factory()->count(2)->create(['student_class_id' => $class->id]);

        $classWithStudents = $this->classService->getClassWithStudents($class);

        $this->assertTrue($classWithStudents->relationLoaded('students'));
        $this->assertCount(2, $classWithStudents->students);
    }

    #[Test]
    public function it_can_get_class_curriculum()
    {
        $class = StudentClass::factory()->create();
        $lectures = Lecture::factory()->count(2)->create();

        $class->lectures()->attach($lectures->pluck('id')->toArray(), [
            'order' => 1
        ]);

        $classWithCurriculum = $this->classService->getClassCurriculum($class);

        $this->assertTrue($classWithCurriculum->relationLoaded('lectures'));
        $this->assertCount(2, $classWithCurriculum->lectures);
    }

    #[Test]
    public function it_can_update_class_curriculum()
    {
        $class = StudentClass::factory()->create();
        $lectures = Lecture::factory()->count(2)->create();

        $lecturesData = [
            ['lecture_id' => $lectures[0]->id, 'order' => 1],
            ['lecture_id' => $lectures[1]->id, 'order' => 2]
        ];

        $updatedClass = $this->classService->updateCurriculum($class, $lecturesData);

        $this->assertTrue($updatedClass->relationLoaded('lectures'));
        $this->assertCount(2, $updatedClass->lectures);

        $this->assertDatabaseHas('student_class_lecture', [
            'student_class_id' => $class->id,
            'lecture_id' => $lectures[0]->id,
            'order' => 1
        ]);
    }

    #[Test]
    public function it_replaces_existing_curriculum_when_updating()
    {
        $class = StudentClass::factory()->create();
        $oldLectures = Lecture::factory()->count(2)->create();
        $newLectures = Lecture::factory()->count(1)->create();

        $class->lectures()->attach($oldLectures->pluck('id')->toArray(), [
            'order' => 1
        ]);

         $lecturesData = [
            ['lecture_id' => $newLectures[0]->id, 'order' => 1]
        ];

        $this->classService->updateCurriculum($class, $lecturesData);

        $this->assertDatabaseMissing('student_class_lecture', [
            'student_class_id' => $class->id,
            'lecture_id' => $oldLectures[0]->id
        ]);

        $this->assertDatabaseHas('student_class_lecture', [
            'student_class_id' => $class->id,
            'lecture_id' => $newLectures[0]->id
        ]);
    }
}
