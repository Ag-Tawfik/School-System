<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

class EnglishLocalizationTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    public function test_english_pages_show_english_names(): void
    {
        $this->signIn();
        $this->createGrade('Primary', 'ابتدائي');

        $this->get($this->url('Grades'))
            ->assertOk()
            ->assertSee('<td>Primary</td>', false)
            ->assertDontSee('<td>ابتدائي</td>', false);
    }
}
