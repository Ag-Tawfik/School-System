<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get($this->url('dashboard'))->assertRedirect('/login');
    }

    public function test_user_can_log_in(): void
    {
        $user = $this->signIn();
        auth()->logout();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_password_is_rejected(): void
    {
        $user = $this->signIn();
        auth()->logout();

        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
