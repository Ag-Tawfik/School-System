<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

class GraduationTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    public function test_graduating_a_section_soft_deletes_only_its_students(): void
    {
        $section = $this->createSection($this->createClassroom($this->createGrade()));
        $other = $this->createSection($this->createClassroom($this->createGrade('Other', 'آخر')));
        $graduate = $this->createStudent($section);
        $stays = $this->createStudent($other);

        $this->post($this->url('Graduated'), [
            'Grade_id' => $section->grade_id,
            'Classroom_id' => $section->class_id,
            'section_id' => $section->id,
        ])->assertRedirect(route('Graduated.index'));

        $this->assertSoftDeleted('students', ['id' => $graduate->id]);
        $this->assertNotNull(Student::find($stays->id));
    }

    public function test_restoring_a_graduate_brings_them_back(): void
    {
        $student = $this->createStudent($this->createSection($this->createClassroom($this->createGrade())));
        $student->delete();

        $this->patch($this->url('Graduated/' . $student->id), ['id' => $student->id]);

        $this->assertNotNull(Student::find($student->id));
    }

    public function test_destroying_a_graduate_removes_them_for_good(): void
    {
        $student = $this->createStudent($this->createSection($this->createClassroom($this->createGrade())));
        $student->delete();

        $this->delete($this->url('Graduated/' . $student->id), ['id' => $student->id]);

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
