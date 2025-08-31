<?php

namespace Tests\Unit;


use App\Models\Lecture;
use App\Models\Student;
use App\Models\StudentClass;
use App\Services\LectureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LectureServiceTest extends TestCase
{
    use RefreshDatabase;

    private LectureService $lectureService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lectureService = new LectureService();
    }

    #[Test]
    public function it_can_get_all_lectures()
    {
        Lecture::factory()->count(3)->create();

        $lectures = $this->lectureService->getAllLectures();

        $this->assertCount(3, $lectures);
        $this->assertInstanceOf(Lecture::class, $lectures->first());
    }

    #[Test]
    public function it_can_create_a_lecture()
    {
        $data = [
            'topic' => 'Тестовая лекция',
            'description' => 'Тестовое описание'
        ];

        $lecture = $this->lectureService->createLecture($data);

        $this->assertDatabaseHas('lectures', $data);
        $this->assertEquals('Тестовая лекция', $lecture->topic);
        $this->assertEquals('Тестовое описание', $lecture->description);
    }

    #[Test]
    public function it_can_update_a_lecture()
    {
        $lecture = Lecture::factory()->create();

        $updatedLecture = $this->lectureService->updateLecture($lecture, [
            'topic' => 'Обновленная тема',
            'description' => 'Обновленное описание'
        ]);

        $this->assertEquals('Обновленная тема', $updatedLecture->topic);
        $this->assertEquals('Обновленное описание', $updatedLecture->description);
    }

    #[Test]
    public function it_can_delete_a_lecture()
    {
        $lecture = Lecture::factory()->create();

        $this->lectureService->deleteLecture($lecture);

        $this->assertDatabaseMissing('lectures', ['id' => $lecture->id]);
    }

    #[Test]
    public function it_can_get_lecture_with_details()
    {
        $lecture = Lecture::factory()->create();
        $class = StudentClass::factory()->create();
        $student = Student::factory()->create();

        // Добавляем связи
        $class->lectures()->attach($lecture->id, ['order' => 1]);
        $student->lectures()->attach($lecture->id);

        $lectureWithDetails = $this->lectureService->getLectureWithDetails($lecture);

        $this->assertTrue($lectureWithDetails->relationLoaded('classes'));
        $this->assertTrue($lectureWithDetails->relationLoaded('students'));
        $this->assertCount(1, $lectureWithDetails->classes);
        $this->assertCount(1, $lectureWithDetails->students);
    }

    #[Test]
    public function it_returns_fresh_instance_after_update()
    {
        $lecture = Lecture::factory()->create(['topic' => 'Старая тема']);

        $updatedLecture = $this->lectureService->updateLecture($lecture, [
            'topic' => 'Новая тема'
        ]);

        $this->assertEquals('Новая тема', $updatedLecture->topic);
    }

    #[Test]
    public function it_can_get_lecture_with_student_class_info()
    {
        $lecture = Lecture::factory()->create();
        $class = StudentClass::factory()->create();
        $student = Student::factory()->create(['student_class_id' => $class->id]);

        $student->lectures()->attach($lecture->id);

        $lectureWithDetails = $this->lectureService->getLectureWithDetails($lecture);

        $this->assertTrue($lectureWithDetails->relationLoaded('students'));
        $this->assertTrue($lectureWithDetails->students->first()->relationLoaded('class'));
        $this->assertEquals($class->id, $lectureWithDetails->students->first()->class->id);
    }
}
