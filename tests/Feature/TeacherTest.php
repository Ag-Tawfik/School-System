<?php

namespace Tests\Feature;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'email' => 'new.teacher@example.com',
            'password' => 'secret123',
            'name_en' => 'Mehmet',
            'name_ar' => 'محمد',
            'specialization_id' => $this->createSpecialization()->id,
            'gender_id' => $this->createGender()->id,
            'joining_date' => '2024-09-01',
            'address' => 'Izmir',
        ], $overrides);
    }

    public function test_store_saves_teacher_with_hashed_password(): void
    {
        $this->post($this->url('Teachers'), $this->payload())
            ->assertRedirect(route('Teachers.create'));

        $teacher = Teacher::where('email', 'new.teacher@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('secret123', $teacher->password));
        $this->assertSame('محمد', $teacher->getTranslation('name', 'ar'));
    }

    public function test_store_rejects_duplicate_email(): void
    {
        $this->createTeacher(['email' => 'new.teacher@example.com']);

        $this->post($this->url('Teachers'), $this->payload())->assertSessionHasErrors('email');
    }

    public function test_update_changes_details(): void
    {
        $teacher = $this->createTeacher();

        $this->patch($this->url('Teachers/' . $teacher->id), $this->payload(['id' => $teacher->id, 'address' => 'Bursa']))
            ->assertRedirect(route('Teachers.index'));

        $this->assertSame('Bursa', $teacher->fresh()->address);
    }

    public function test_destroy_removes_the_teacher(): void
    {
        $teacher = $this->createTeacher();

        $this->delete($this->url('Teachers/' . $teacher->id), ['id' => $teacher->id]);

        $this->assertDatabaseMissing('teachers', ['id' => $teacher->id]);
    }
}
