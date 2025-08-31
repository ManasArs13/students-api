<?php

namespace Tests\Feature;

use App\Models\Lecture;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_get_all_students()
    {
        Student::factory()->count(3)->create();

        $response = $this->getJson('/api/students');

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'class',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    #[Test]
    public function it_returns_empty_array_when_no_students_exist()
    {
        $response = $this->getJson('/api/students');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    #[Test]
    public function it_can_create_a_student()
    {
        $class = StudentClass::factory()->create();
        $studentData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'student_class_id' => $class->id
        ];

        $response = $this->postJson('/api/students', $studentData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ]);

        $this->assertDatabaseHas('students', $studentData);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_student()
    {
        $response = $this->postJson('/api/students', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }

    #[Test]
    public function it_validates_email_uniqueness_when_creating_student()
    {
        $existingStudent = Student::factory()->create();
        $studentData = [
            'name' => 'John Doe',
            'email' => $existingStudent->email,
            'student_class_id' => null
        ];

        $response = $this->postJson('/api/students', $studentData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function it_can_get_a_specific_student()
    {
        $student = Student::factory()->create();

        $response = $this->getJson("/api/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email
            ]);
    }

    #[Test]
    public function it_returns_404_when_student_not_found()
    {
        $response = $this->getJson('/api/students/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_get_student_with_details()
    {
        $class = StudentClass::factory()->create();
        $student = Student::factory()->create(['student_class_id' => $class->id]);
        $lecture = Lecture::factory()->create();
        $student->lectures()->attach($lecture->id);

        $response = $this->getJson("/api/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'class' => [
                            'id',
                            'name'
                        ],
                        'lectures' => [
                            '*' => [
                                'id',
                                'topic',
                                'description',
                                'listened_at'
                            ]
                        ],
                        'created_at',
                        'updated_at'
                    ]
                ]
            );
    }

    #[Test]
    public function it_can_update_a_student()
    {
        $student = Student::factory()->create();
        $newClass = StudentClass::factory()->create();
        $updateData = [
            'name' => 'Updated Name',
            'student_class_id' => $newClass->id
        ];

        $response = $this->putJson("/api/students/{$student->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Updated Name'
            ]);

        $this->assertDatabaseHas('students', array_merge(['id' => $student->id], $updateData));
    }

    #[Test]
    public function it_validates_fields_when_updating_student()
    {
        $student = Student::factory()->create();
        $invalidData = [
            'email' => 'invalid-email',
            'student_class_id' => 999
        ];

        $response = $this->putJson("/api/students/{$student->id}", $invalidData);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_delete_a_student()
    {
        $student = Student::factory()->create();

        $response = $this->deleteJson("/api/students/{$student->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    #[Test]
    public function it_returns_404_when_deleting_nonexistent_student()
    {
        $response = $this->deleteJson('/api/students/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_deletes_student_lecture_relations_when_student_is_deleted()
    {
        $student = Student::factory()->create();
        $lecture = Lecture::factory()->create();
        $student->lectures()->attach($lecture->id);

        $this->assertDatabaseHas('student_lecture', [
            'student_id' => $student->id,
            'lecture_id' => $lecture->id
        ]);

        $this->deleteJson("/api/students/{$student->id}");

        $this->assertDatabaseMissing('student_lecture', [
            'student_id' => $student->id,
            'lecture_id' => $lecture->id
        ]);
    }
}
