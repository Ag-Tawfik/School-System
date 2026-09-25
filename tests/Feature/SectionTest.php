<?php

namespace Tests\Feature;

use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

class SectionTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    public function test_store_creates_an_active_section_with_its_teachers(): void
    {
        $classroom = $this->createClassroom($this->createGrade());
        $teacher = $this->createTeacher();

        $this->post($this->url('Sections'), [
            'name_en' => 'A', 'name_ar' => 'أ',
            'grade_id' => $classroom->grade_id, 'class_id' => $classroom->id,
            'teacher_id' => [$teacher->id],
        ])->assertRedirect(route('Sections.index'));

        $section = Section::firstOrFail();
        $this->assertEquals(1, $section->status);
        $this->assertSame([$teacher->id], $section->teachers()->pluck('teachers.id')->all());
    }

    public function test_update_without_status_deactivates_and_resyncs_teachers(): void
    {
        $section = $this->createSection($this->createClassroom($this->createGrade()));
        $old = $this->createTeacher();
        $new = $this->createTeacher();
        $section->teachers()->attach($old->id);

        $this->patch($this->url('Sections/' . $section->id), [
            'id' => $section->id, 'name_en' => 'B', 'name_ar' => 'ب',
            'grade_id' => $section->grade_id, 'class_id' => $section->class_id,
            'teacher_id' => [$new->id],
        ])->assertRedirect(route('Sections.index'));

        $section->refresh();
        $this->assertEquals(2, $section->status);
        $this->assertSame('B', $section->getTranslation('name', 'en'));
        $this->assertSame([$new->id], $section->teachers()->pluck('teachers.id')->all());
    }

    public function test_classes_endpoint_lists_classrooms_of_a_grade(): void
    {
        $grade = $this->createGrade();
        $classroom = $this->createClassroom($grade, 'First', 'الأول');
        $this->createClassroom($this->createGrade('Other', 'آخر'), 'Elsewhere', 'مكان');

        $this->get($this->url('classes/' . $grade->id))
            ->assertOk()
            ->assertExactJson([(string) $classroom->id => 'First']);
    }

    public function test_destroy_removes_the_section(): void
    {
        $section = $this->createSection($this->createClassroom($this->createGrade()));

        $this->delete($this->url('Sections/' . $section->id), ['id' => $section->id]);

        $this->assertDatabaseMissing('sections', ['id' => $section->id]);
    }
}
