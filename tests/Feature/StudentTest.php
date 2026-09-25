<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    private function payload($section, array $overrides = []): array
    {
        return array_merge([
            'name_en' => 'Omar',
            'name_ar' => 'عمر',
            'email' => 'omar@example.com',
            'password' => 'secret1',
            'gender_id' => $this->createGender()->id,
            'nationalitie_id' => $this->createNationality()->id,
            'blood_id' => $this->createBloodType()->id,
            'birthday' => '2015-05-05',
            'grade_id' => $section->grade_id,
            'classroom_id' => $section->class_id,
            'section_id' => $section->id,
            'parent_id' => $this->createParent()->id,
            'academic_year' => '2025',
        ], $overrides);
    }

    public function test_store_saves_the_student(): void
    {
        $section = $this->createSection($this->createClassroom($this->createGrade()));

        $this->post($this->url('Students'), $this->payload($section))
            ->assertRedirect(route('Students.create'));

        $student = Student::where('email', 'omar@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('secret1', $student->password));
        $this->assertSame('عمر', $student->getTranslation('name', 'ar'));
        $this->assertEquals($section->id, $student->section_id);
    }

    public function test_store_validates_birthday_format(): void
    {
        $section = $this->createSection($this->createClassroom($this->createGrade()));

        $this->post($this->url('Students'), $this->payload($section, ['birthday' => '05/05/2015']))
            ->assertSessionHasErrors('birthday');

        $this->assertSame(0, Student::count());
    }

    public function test_update_changes_the_student(): void
    {
        $section = $this->createSection($this->createClassroom($this->createGrade()));
        $student = $this->createStudent($section);

        $this->patch($this->url('Students/' . $student->id), $this->payload($section, [
            'id' => $student->id, 'email' => 'renamed@example.com', 'name_en' => 'Omer',
        ]))->assertRedirect(route('Students.index'));

        $student->refresh();
        $this->assertSame('renamed@example.com', $student->email);
        $this->assertSame('Omer', $student->getTranslation('name', 'en'));
    }

    public function test_destroy_soft_deletes(): void
    {
        $student = $this->createStudent($this->createSection($this->createClassroom($this->createGrade())));

        $this->delete($this->url('Students/' . $student->id), ['id' => $student->id]);

        $this->assertSoftDeleted('students', ['id' => $student->id]);
    }

    public function test_dependent_dropdown_endpoints(): void
    {
        $grade = $this->createGrade();
        $classroom = $this->createClassroom($grade, 'First', 'الأول');
        $section = $this->createSection($classroom, 'A', 'أ');

        $this->get($this->url('Get_classrooms/' . $grade->id))
            ->assertOk()
            ->assertExactJson([(string) $classroom->id => 'First']);

        $this->get($this->url('Get_Sections/' . $classroom->id))
            ->assertOk()
            ->assertExactJson([(string) $section->id => 'A']);
    }
}
