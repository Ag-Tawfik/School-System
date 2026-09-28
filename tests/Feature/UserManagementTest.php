<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

/**
 * Admins create every account; a teacher or parent account must point at the
 * record that decides which students it sees.
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ayse',
            'email' => 'ayse@example.com',
            'password' => 'secret123',
            'role' => 'teacher',
            'teacher_id' => null,
            'parent_id' => null,
        ], $overrides);
    }

    public function test_admin_creates_a_teacher_account_linked_to_a_teacher(): void
    {
        $this->signIn();
        $teacher = $this->createTeacher();

        $this->post($this->url('Users'), $this->payload(['teacher_id' => $teacher->id]))
            ->assertRedirect(route('Users.index'));

        $user = User::where('email', 'ayse@example.com')->firstOrFail();
        $this->assertSame(Role::Teacher, $user->role);
        $this->assertSame($teacher->id, $user->teacher_id);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_new_account_can_log_in(): void
    {
        $this->signIn();
        $this->post($this->url('Users'), $this->payload(['teacher_id' => $this->createTeacher()->id]));
        auth()->logout();

        $this->post('/login', ['email' => 'ayse@example.com', 'password' => 'secret123'])
            ->assertRedirect('/dashboard');
    }

    public function test_teacher_account_requires_a_teacher_record(): void
    {
        $this->signIn();

        $this->post($this->url('Users'), $this->payload())->assertSessionHasErrors('teacher_id');
        $this->assertFalse(User::where('email', 'ayse@example.com')->exists());
    }

    public function test_parent_account_requires_a_parent_record(): void
    {
        $this->signIn();

        $this->post($this->url('Users'), $this->payload(['role' => 'parent']))->assertSessionHasErrors('parent_id');
    }

    public function test_unknown_role_is_rejected(): void
    {
        $this->signIn();

        $this->post($this->url('Users'), $this->payload(['role' => 'superuser']))->assertSessionHasErrors('role');
    }

    public function test_only_the_link_matching_the_role_is_kept(): void
    {
        $this->signIn();
        $parent = $this->createParent();

        $this->post($this->url('Users'), $this->payload([
            'role' => 'parent',
            'parent_id' => $parent->id,
            'teacher_id' => $this->createTeacher()->id,
        ]));

        $user = User::where('email', 'ayse@example.com')->firstOrFail();
        $this->assertSame($parent->id, $user->parent_id);
        $this->assertNull($user->teacher_id);
    }

    public function test_blank_password_on_edit_keeps_the_old_one(): void
    {
        $this->signIn();
        $user = $this->createUser(Role::Parent, ['parent_id' => $this->createParent()->id]);
        $oldHash = $user->password;

        $this->put($this->url('Users/' . $user->id), $this->payload([
            'email' => $user->email,
            'password' => '',
            'role' => 'parent',
            'parent_id' => $user->parent_id,
        ]))->assertRedirect(route('Users.index'));

        $this->assertSame($oldHash, $user->fresh()->password);
    }

    public function test_admin_cannot_demote_themselves(): void
    {
        $admin = $this->signIn();

        $this->put($this->url('Users/' . $admin->id), $this->payload([
            'email' => $admin->email,
            'teacher_id' => $this->createTeacher()->id,
        ]))->assertSessionHasErrors('role');

        $this->assertSame(Role::Admin, $admin->fresh()->role);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = $this->signIn();

        $this->delete($this->url('Users/' . $admin->id))->assertSessionHasErrors('error');

        $this->assertModelExists($admin);
    }

    public function test_admin_deletes_another_account(): void
    {
        $this->signIn();
        $user = $this->createUser(Role::Admin);

        $this->delete($this->url('Users/' . $user->id))->assertRedirect(route('Users.index'));

        $this->assertModelMissing($user);
    }
}
