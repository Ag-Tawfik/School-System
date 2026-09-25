<?php

namespace Tests\Feature;

use App\Models\Promotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

class PromotionTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    private function promote($from, $to): \Illuminate\Testing\TestResponse
    {
        return $this->post($this->url('Promotion'), [
            'grade_id' => $from->grade_id,
            'classroom_id' => $from->class_id,
            'section_id' => $from->id,
            'grade_id_new' => $to->grade_id,
            'classroom_id_new' => $to->class_id,
            'section_id_new' => $to->id,
        ]);
    }

    public function test_promotion_moves_every_student_in_the_section(): void
    {
        $from = $this->createSection($this->createClassroom($this->createGrade('One', 'واحد')));
        $to = $this->createSection($this->createClassroom($this->createGrade('Two', 'اثنان')));
        $a = $this->createStudent($from);
        $b = $this->createStudent($from);

        $this->promote($from, $to)->assertSessionHasNoErrors();

        foreach ([$a, $b] as $student) {
            $student->refresh();
            $this->assertEquals($to->grade_id, $student->grade_id);
            $this->assertEquals($to->class_id, $student->classroom_id);
            $this->assertEquals($to->id, $student->section_id);
        }
        $this->assertSame(2, Promotion::count());
    }

    public function test_promotion_from_an_empty_section_changes_nothing(): void
    {
        $from = $this->createSection($this->createClassroom($this->createGrade('One', 'واحد')));
        $to = $this->createSection($this->createClassroom($this->createGrade('Two', 'اثنان')));

        $this->promote($from, $to)->assertSessionHas('error_promotions');

        $this->assertSame(0, Promotion::count());
    }

    public function test_revert_all_puts_every_student_back(): void
    {
        $from = $this->createSection($this->createClassroom($this->createGrade('One', 'واحد')));
        $to = $this->createSection($this->createClassroom($this->createGrade('Two', 'اثنان')));
        $a = $this->createStudent($from);
        $b = $this->createStudent($from);
        $this->promote($from, $to);

        $this->delete($this->url('Promotion/1'), ['page_id' => 1]);

        foreach ([$a, $b] as $student) {
            $this->assertEquals($from->id, $student->fresh()->section_id);
        }
        $this->assertSame(0, Promotion::count());
    }
}
