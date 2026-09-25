<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    protected $locale = 'ar';

    public function test_arabic_pages_show_arabic_names(): void
    {
        $this->signIn();
        $this->createGrade('Primary', 'ابتدائي');

        $this->get($this->url('Grades'))
            ->assertOk()
            ->assertSee('ابتدائي')
            ->assertDontSee('Primary');
    }
}
