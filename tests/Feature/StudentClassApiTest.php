<?php

namespace Tests\Feature;

use App\Models\Lecture;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StudentClassApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_get_all_classes()
    {
        StudentClass::factory()->count(3)->create();

        $response = $this->getJson('/api/classes');

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    #[Test]
    public function it_returns_empty_array_when_no_classes_exist()
    {
        $response = $this->getJson('/api/classes');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    #[Test]
    public function it_can_create_a_class()
    {
        $classData = [
            'name' => '10-A'
        ];

        $response = $this->postJson('/api/classes', $classData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => '10-A'
            ]);

        $this->assertDatabaseHas('student_classes', $classData);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_class()
    {
        $response = $this->postJson('/api/classes', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function it_validates_name_uniqueness_when_creating_class()
    {
        $existingClass = StudentClass::factory()->create();
        $classData = [
            'name' => $existingClass->name
        ];

        $response = $this->postJson('/api/classes', $classData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function it_can_get_a_specific_class()
    {
        $class = StudentClass::factory()->create();

        $response = $this->getJson("/api/classes/{$class->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $class->id,
                'name' => $class->name
            ]);
    }

    #[Test]
    public function it_returns_404_when_class_not_found()
    {
        $response = $this->getJson('/api/classes/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_get_class_with_students()
    {
        $class = StudentClass::factory()->create();
        Student::factory()->count(2)->create(['student_class_id' => $class->id]);

        $response = $this->getJson("/api/classes/{$class->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'students' => [
                        '*' => [
                            'id',
                            'name',
                            'email'
                        ]
                    ],
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJsonCount(2, 'data.students');
    }

    #[Test]
    public function it_can_get_class_curriculum()
    {
        $class = StudentClass::factory()->create();
        $lectures = Lecture::factory()->count(3)->create();

        $class->lectures()->attach($lectures->pluck('id')->toArray(), [
            'order' => 1
        ]);

        $response = $this->getJson("/api/classes/{$class->id}/curriculum");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'lectures' => [
                        '*' => [
                            'id',
                            'topic',
                            'description',
                            'order'
                        ]
                    ],
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJsonCount(3, 'data.lectures');
    }

    #[Test]
    public function it_returns_empty_curriculum_when_no_lectures()
    {
        $class = StudentClass::factory()->create();

        $response = $this->getJson("/api/classes/{$class->id}/curriculum");

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data.lectures');
    }

    #[Test]
    public function it_can_update_a_class()
    {
        $class = StudentClass::factory()->create();
        $updateData = [
            'name' => '10-B'
        ];

        $response = $this->putJson("/api/classes/{$class->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => '10-B'
            ]);

        $this->assertDatabaseHas('student_classes', array_merge(['id' => $class->id], $updateData));
    }

    #[Test]
    public function it_validates_fields_when_updating_class()
    {
        $class = StudentClass::factory()->create();
        $anotherClass = StudentClass::factory()->create();

        $invalidData = [
            'name' => $anotherClass->name // Дублирующееся название
        ];

        $response = $this->putJson("/api/classes/{$class->id}", $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function it_can_update_class_curriculum()
    {
        $class = StudentClass::factory()->create();
        $lectures = Lecture::factory()->count(2)->create();

        $curriculumData = [
            'lectures' => [
                ['lecture_id' => $lectures[0]->id, 'order' => 1],
                ['lecture_id' => $lectures[1]->id, 'order' => 2]
            ]
        ];

        $response = $this->putJson("/api/classes/{$class->id}/curriculum", $curriculumData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'lectures',
                ]
            ]);

        $this->assertDatabaseHas('student_class_lecture', [
            'student_class_id' => $class->id,
            'lecture_id' => $lectures[0]->id,
            'order' => 1
        ]);
    }

    #[Test]
    public function it_validates_curriculum_data()
    {
        $class = StudentClass::factory()->create();

        $invalidData = [
            'lectures' => [
                ['lecture_id' => 999, 'order' => 1],
                ['order' => 2]
            ]
        ];

        $response = $this->putJson("/api/classes/{$class->id}/curriculum", $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['lectures.0.lecture_id', 'lectures.1.lecture_id']);
    }

    #[Test]
    public function it_can_delete_a_class()
    {
        $class = StudentClass::factory()->create();

        $response = $this->deleteJson("/api/classes/{$class->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('student_classes', ['id' => $class->id]);
    }

    #[Test]
    public function it_detaches_students_when_class_is_deleted()
    {
        $class = StudentClass::factory()->create();
        $student = Student::factory()->create(['student_class_id' => $class->id]);

        $this->assertEquals($class->id, $student->fresh()->student_class_id);

        $this->deleteJson("/api/classes/{$class->id}");

        $this->assertNull($student->fresh()->class_id);
    }

    #[Test]
    public function it_deletes_class_lecture_relations_when_class_is_deleted()
    {
        $class = StudentClass::factory()->create();
        $lecture = Lecture::factory()->create();

        $class->lectures()->attach($lecture->id, ['order' => 1]);

        $this->assertDatabaseHas('student_class_lecture', [
            'student_class_id' => $class->id,
            'lecture_id' => $lecture->id
        ]);

        $this->deleteJson("/api/classes/{$class->id}");

        $this->assertDatabaseMissing('student_class_lecture', [
            'student_class_id' => $class->id
        ]);
    }

    #[Test]
    public function it_returns_404_when_deleting_nonexistent_class()
    {
        $response = $this->deleteJson('/api/classes/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_reorder_lectures_in_curriculum()
    {
        $class = StudentClass::factory()->create();
        $lectures = Lecture::factory()->count(3)->create();

        $curriculumData = [
            'lectures' => [
                ['lecture_id' => $lectures[2]->id, 'order' => 1],
                ['lecture_id' => $lectures[0]->id, 'order' => 2],
                ['lecture_id' => $lectures[1]->id, 'order' => 3]
            ]
        ];

        $response = $this->putJson("/api/classes/{$class->id}/curriculum", $curriculumData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('student_class_lecture', [
            'student_class_id' => $class->id,
            'lecture_id' => $lectures[2]->id,
            'order' => 1
        ]);
    }
}
