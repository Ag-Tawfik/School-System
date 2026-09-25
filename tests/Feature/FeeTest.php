<?php

namespace Tests\Feature;

use App\Models\Fee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

class FeeTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    private function payload($classroom, array $overrides = []): array
    {
        return array_merge([
            'title_en' => 'Tuition',
            'title_ar' => 'رسوم دراسية',
            'amount' => '1500',
            'Grade_id' => $classroom->grade_id,
            'Classroom_id' => $classroom->id,
            'description' => 'Autumn term',
            'year' => '2025',
            'Fee_type' => 1,
        ], $overrides);
    }

    public function test_store_saves_the_fee(): void
    {
        $classroom = $this->createClassroom($this->createGrade());

        $this->post($this->url('Fees'), $this->payload($classroom))
            ->assertRedirect(route('Fees.create'));

        $fee = Fee::firstOrFail();
        $this->assertEquals(1500, $fee->amount);
        $this->assertSame('رسوم دراسية', $fee->getTranslation('title', 'ar'));
    }

    public function test_store_requires_a_numeric_amount(): void
    {
        $classroom = $this->createClassroom($this->createGrade());

        $this->post($this->url('Fees'), $this->payload($classroom, ['amount' => 'lots']))
            ->assertSessionHasErrors('amount');

        $this->assertSame(0, Fee::count());
    }

    public function test_update_changes_the_amount(): void
    {
        $classroom = $this->createClassroom($this->createGrade());
        $fee = $this->createFee($classroom);

        $this->patch($this->url('Fees/' . $fee->id), $this->payload($classroom, ['id' => $fee->id, 'amount' => '2000']))
            ->assertRedirect(route('Fees.index'));

        $this->assertEquals(2000, $fee->fresh()->amount);
    }

    public function test_destroy_removes_the_fee(): void
    {
        $fee = $this->createFee($this->createClassroom($this->createGrade()));

        $this->delete($this->url('Fees/' . $fee->id), ['id' => $fee->id]);

        $this->assertDatabaseMissing('fees', ['id' => $fee->id]);
    }
}
