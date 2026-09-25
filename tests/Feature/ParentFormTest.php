<?php

namespace Tests\Feature;

use App\Http\Livewire\AddParent;
use App\Models\TheParent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

/**
 * Uses only Livewire::test()->set()->call(), which works the same on
 * Livewire 2, 3 and 4. Attachments are in AttachmentTest.
 */
class ParentFormTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    private function fillFather($component)
    {
        return $component
            ->set('email', 'family@example.com')
            ->set('password', 'secret')
            ->set('fatherName', 'علي')
            ->set('fatherName_en', 'Ali')
            ->set('fatherJobTitle', 'مهندس')
            ->set('fatherJobTitle_en', 'Engineer')
            ->set('fatherNationalID', '1234567890')
            ->set('fatherPassportID', '1234567891')
            ->set('fatherPhoneNumber', '05551234567')
            ->set('fatherNationalty_id', $this->createNationality()->id)
            ->set('fatherBloodType_id', $this->createBloodType()->id)
            ->set('fatherReligion_id', $this->createReligion()->id)
            ->set('fatherAddress', 'Ankara');
    }

    private function fillMother($component)
    {
        return $component
            ->set('motherName', 'فاطمة')
            ->set('motherName_en', 'Fatma')
            ->set('motherJobTitle', 'طبيبة')
            ->set('motherJobTitle_en', 'Doctor')
            ->set('motherNationalID', '2234567890')
            ->set('motherPassportID', '2234567891')
            ->set('motherPhoneNumber', '05557654321')
            ->set('motherNationalty_id', $this->createNationality()->id)
            ->set('motherBloodType_id', $this->createBloodType()->id)
            ->set('motherReligion_id', $this->createReligion()->id)
            ->set('motherAddress', 'Ankara');
    }

    public function test_three_step_form_creates_a_parent(): void
    {
        $component = $this->fillFather(Livewire::test(AddParent::class))
            ->call('firstStepSubmit')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 2);

        $this->fillMother($component)
            ->call('secondStepSubmit')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 3)
            ->call('submitForm')
            ->assertSet('catchError', null)
            ->assertSet('currentStep', 1);

        $parent = TheParent::where('email', 'family@example.com')->firstOrFail();
        $this->assertSame('Ali', $parent->getTranslation('fatherName', 'en'));
        $this->assertSame('فاطمة', $parent->getTranslation('motherName', 'ar'));
    }

    public function test_step_one_rejects_a_duplicate_father_national_id(): void
    {
        $this->createParent(['fatherNationalID' => '1234567890']);

        $this->fillFather(Livewire::test(AddParent::class))
            ->call('firstStepSubmit')
            ->assertHasErrors(['fatherNationalID' => 'unique'])
            ->assertSet('currentStep', 1);
    }

    public function test_delete_removes_the_parent(): void
    {
        $parent = $this->createParent();

        Livewire::test(AddParent::class)->call('delete', $parent->id);

        $this->assertDatabaseMissing('parents', ['id' => $parent->id]);
    }
}
