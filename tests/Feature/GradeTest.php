<?php

namespace Tests\Feature;

use App\Models\Grade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

class GradeTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    public function test_store_saves_both_translations(): void
    {
        $this->post($this->url('Grades'), ['name_en' => 'Primary', 'name_ar' => 'ابتدائي', 'notes' => 'n'])
            ->assertRedirect(route('Grades.index'));

        $grade = Grade::firstOrFail();
        $this->assertSame('Primary', $grade->getTranslation('name', 'en'));
        $this->assertSame('ابتدائي', $grade->getTranslation('name', 'ar'));
    }

    public function test_store_rejects_duplicate_name(): void
    {
        $this->createGrade('Primary', 'ابتدائي');

        $this->post($this->url('Grades'), ['name_en' => 'Primary', 'name_ar' => 'ابتدائي'])
            ->assertSessionHasErrors(['name_en', 'name_ar']);

        $this->assertSame(1, Grade::count());
    }

    public function test_update_changes_the_name(): void
    {
        $grade = $this->createGrade('Primary', 'ابتدائي');

        $this->patch($this->url('Grades/' . $grade->id), [
            'id' => $grade->id, 'name_en' => 'Middle', 'name_ar' => 'متوسط',
        ]);

        $grade->refresh();
        $this->assertSame('Middle', $grade->getTranslation('name', 'en'));
        $this->assertSame('متوسط', $grade->getTranslation('name', 'ar'));
    }

    public function test_destroy_removes_an_empty_grade(): void
    {
        $grade = $this->createGrade();

        $this->delete($this->url('Grades/' . $grade->id), ['id' => $grade->id])
            ->assertRedirect(route('Grades.index'));

        $this->assertDatabaseMissing('grades', ['id' => $grade->id]);
    }

    public function test_destroy_keeps_a_grade_that_has_classrooms(): void
    {
        $grade = $this->createGrade();
        $this->createClassroom($grade);

        $this->delete($this->url('Grades/' . $grade->id), ['id' => $grade->id]);

        $this->assertDatabaseHas('grades', ['id' => $grade->id]);
    }
}
