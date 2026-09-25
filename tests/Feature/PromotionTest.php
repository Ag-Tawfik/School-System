<?php

namespace Tests\Feature;

use App\Models\Promotion;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

/**
 * Uses DatabaseMigrations, not RefreshDatabase: reverting calls
 * Promotion::truncate(), which commits the test transaction in MySQL and
 * would leak rows into later tests.
 */
class PromotionTest extends TestCase
{
    use DatabaseMigrations;
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
            'academic_year' => '2025',
            'academic_year_new' => '2026',
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
            $this->assertSame('2026', $student->academic_year);
        }
        $this->assertSame(2, Promotion::count());
        $this->assertSame(['2025'], Promotion::pluck('academic_year')->unique()->values()->all());
        $this->assertSame(['2026'], Promotion::pluck('academic_year_new')->unique()->values()->all());
    }

    public function test_promotion_from_an_empty_section_changes_nothing(): void
    {
        $from = $this->createSection($this->createClassroom($this->createGrade('One', 'واحد')));
        $to = $this->createSection($this->createClassroom($this->createGrade('Two', 'اثنان')));

        $this->promote($from, $to)->assertSessionHas('error_promotions');

        $this->assertSame(0, Promotion::count());
    }

    public function test_promotion_requires_both_academic_years(): void
    {
        $from = $this->createSection($this->createClassroom($this->createGrade('One', 'واحد')));
        $this->createStudent($from);

        $this->post($this->url('Promotion'), [
            'grade_id' => $from->grade_id, 'classroom_id' => $from->class_id, 'section_id' => $from->id,
        ])->assertSessionHasErrors(['academic_year', 'grade_id_new', 'academic_year_new']);

        $this->assertSame(0, Promotion::count());
    }

    // The shared dropdown script fills selects by these exact (case-sensitive) names.
    public function test_promotion_form_uses_the_names_the_dropdown_script_expects(): void
    {
        $this->get($this->url('Promotion'))
            ->assertOk()
            ->assertSee('name="grade_id"', false)
            ->assertSee('name="classroom_id"', false)
            ->assertSee('name="grade_id_new"', false)
            ->assertSee('name="classroom_id_new"', false);
    }

    public function test_revert_all_puts_every_student_back(): void
    {
        $from = $this->createSection($this->createClassroom($this->createGrade('One', 'واحد')));
        $to = $this->createSection($this->createClassroom($this->createGrade('Two', 'اثنان')));
        $a = $this->createStudent($to);
        $b = $this->createStudent($to);
        foreach ([$a, $b] as $student) {
            Promotion::create([
                'student_id' => $student->id,
                'from_grade' => $from->grade_id, 'from_classroom' => $from->class_id, 'from_section' => $from->id,
                'to_grade' => $to->grade_id, 'to_classroom' => $to->class_id, 'to_section' => $to->id,
                'academic_year' => '2025', 'academic_year_new' => '2026',
            ]);
        }

        $this->delete($this->url('Promotion/1'), ['page_id' => 1]);

        foreach ([$a, $b] as $student) {
            $this->assertEquals($from->id, $student->fresh()->section_id);
        }
        $this->assertSame(0, Promotion::count());
    }
}
