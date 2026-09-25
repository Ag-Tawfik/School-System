<?php

namespace Tests\Feature;

use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    public function test_store_creates_every_classroom_in_the_list(): void
    {
        $grade = $this->createGrade();

        $this->post($this->url('Classrooms'), ['classes_list' => [
            ['name_en' => 'First', 'name_ar' => 'الأول', 'grade_id' => $grade->id],
            ['name_en' => 'Second', 'name_ar' => 'الثاني', 'grade_id' => $grade->id],
        ]])->assertRedirect(route('Classrooms.index'));

        $this->assertSame(2, Classroom::where('grade_id', $grade->id)->count());
    }

    public function test_update_changes_name_and_grade(): void
    {
        $classroom = $this->createClassroom($this->createGrade());
        $other = $this->createGrade('Middle', 'متوسط');

        $this->patch($this->url('Classrooms/' . $classroom->id), [
            'id' => $classroom->id, 'name_en' => 'Renamed', 'name_ar' => 'معدل', 'grade_id' => $other->id,
        ])->assertRedirect(route('Classrooms.index'));

        $classroom->refresh();
        $this->assertSame('Renamed', $classroom->getTranslation('name', 'en'));
        $this->assertSame('معدل', $classroom->getTranslation('name', 'ar'));
        $this->assertEquals($other->id, $classroom->grade_id);
    }

    public function test_delete_all_removes_only_selected_classrooms(): void
    {
        $grade = $this->createGrade();
        $a = $this->createClassroom($grade, 'A', 'أ');
        $b = $this->createClassroom($grade, 'B', 'ب');
        $keep = $this->createClassroom($grade, 'C', 'ج');

        $this->post($this->url('delete_all'), ['delete_all_id' => $a->id . ',' . $b->id]);

        $this->assertSame([$keep->id], Classroom::pluck('id')->all());
    }

    public function test_filter_shows_only_the_selected_grade(): void
    {
        $primary = $this->createGrade('Primary', 'ابتدائي');
        $middle = $this->createGrade('Middle', 'متوسط');
        $this->createClassroom($primary, 'PrimaryRoom', 'غرفة1');
        $this->createClassroom($middle, 'MiddleRoom', 'غرفة2');

        $this->post($this->url('Filter_Classes'), ['grade_id' => $primary->id])
            ->assertOk()
            ->assertSee('PrimaryRoom')
            ->assertDontSee('MiddleRoom');
    }

    public function test_destroy_removes_the_classroom(): void
    {
        $classroom = $this->createClassroom($this->createGrade());

        $this->delete($this->url('Classrooms/' . $classroom->id), ['id' => $classroom->id]);

        $this->assertDatabaseMissing('classrooms', ['id' => $classroom->id]);
    }
}
