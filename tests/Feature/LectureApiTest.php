<?php

namespace Tests\Feature;

use App\Models\Lecture;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LectureApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_get_all_lectures()
    {
        Lecture::factory()->count(3)->create();

        $response = $this->getJson('/api/lectures');

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'topic',
                        'description',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    #[Test]
    public function it_returns_empty_array_when_no_lectures_exist()
    {
        $response = $this->getJson('/api/lectures');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    #[Test]
    public function it_can_create_a_lecture()
    {
        $lectureData = [
            'topic' => 'Математика: Алгебра',
            'description' => 'Основы алгебраических выражений'
        ];

        $response = $this->postJson('/api/lectures', $lectureData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'topic' => 'Математика: Алгебра',
                'description' => 'Основы алгебраических выражений'
            ]);

        $this->assertDatabaseHas('lectures', $lectureData);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_lecture()
    {
        $response = $this->postJson('/api/lectures', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['topic', 'description']);
    }

    #[Test]
    public function it_validates_topic_uniqueness_when_creating_lecture()
    {
        $existingLecture = Lecture::factory()->create();
        $lectureData = [
            'topic' => $existingLecture->topic,
            'description' => 'Новое описание'
        ];

        $response = $this->postJson('/api/lectures', $lectureData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['topic']);
    }

    #[Test]
    public function it_can_get_a_specific_lecture()
    {
        $lecture = Lecture::factory()->create();

        $response = $this->getJson("/api/lectures/{$lecture->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $lecture->id,
                'topic' => $lecture->topic,
                'description' => $lecture->description
            ]);
    }

    #[Test]
    public function it_returns_404_when_lecture_not_found()
    {
        $response = $this->getJson('/api/lectures/999');

        $response->assertStatus(404);
    }

    public function it_can_get_lecture_with_details()
    {
        $lecture = Lecture::factory()->create();
        $class = StudentClass::factory()->create();
        $student = Student::factory()->create();

        $class->lectures()->attach($lecture->id, ['order' => 1]);

        $student->lectures()->attach($lecture->id);

        $response = $this->getJson("/api/lectures/{$lecture->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'topic',
                'description',
                'classes' => [
                    '*' => [
                        'id',
                        'name',
                        'order'
                    ]
                ],
                'students' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'class',
                        'listened_at'
                    ]
                ],
                'created_at',
                'updated_at'
            ]);
    }

    #[Test]
    public function it_can_update_a_lecture()
    {
        $lecture = Lecture::factory()->create();
        $updateData = [
            'topic' => 'Обновленная тема',
            'description' => 'Обновленное описание'
        ];

        $response = $this->putJson("/api/lectures/{$lecture->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'topic' => 'Обновленная тема',
                'description' => 'Обновленное описание'
            ]);

        $this->assertDatabaseHas('lectures', array_merge(['id' => $lecture->id], $updateData));
    }

    #[Test]
    public function it_validates_fields_when_updating_lecture()
    {
        $lecture = Lecture::factory()->create();
        $anotherLecture = Lecture::factory()->create();

        $invalidData = [
            'topic' => $anotherLecture->topic,
            'description' => ''
        ];

        $response = $this->putJson("/api/lectures/{$lecture->id}", $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['topic', 'description']);
    }

    #[Test]
    public function it_can_delete_a_lecture()
    {
        $lecture = Lecture::factory()->create();

        $response = $this->deleteJson("/api/lectures/{$lecture->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('lectures', ['id' => $lecture->id]);
    }

    #[Test]
    public function it_returns_404_when_deleting_nonexistent_lecture()
    {
        $response = $this->deleteJson('/api/lectures/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_deletes_lecture_relations_when_lecture_is_deleted()
    {
        $lecture = Lecture::factory()->create();
        $class = StudentClass::factory()->create();
        $student = Student::factory()->create();

        $class->lectures()->attach($lecture->id, ['order' => 1]);
        $student->lectures()->attach($lecture->id);

        $this->assertDatabaseHas('student_class_lecture', [
            'lecture_id' => $lecture->id,
            'student_class_id' => $class->id
        ]);

        $this->assertDatabaseHas('student_lecture', [
            'lecture_id' => $lecture->id,
            'student_id' => $student->id
        ]);

        $this->deleteJson("/api/lectures/{$lecture->id}");

        $this->assertDatabaseMissing('student_class_lecture', [
            'lecture_id' => $lecture->id
        ]);

        $this->assertDatabaseMissing('student_lecture', [
            'lecture_id' => $lecture->id
        ]);
    }

    #[Test]
    public function it_can_partially_update_lecture()
    {
        $lecture = Lecture::factory()->create(['description' => 'Старое описание']);

        $updateData = [
            'topic' => 'Новая тема'
        ];

        $response = $this->putJson("/api/lectures/{$lecture->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'topic' => 'Новая тема',
                'description' => 'Старое описание'
            ]);
    }
}
