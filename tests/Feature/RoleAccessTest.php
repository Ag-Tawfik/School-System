<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Livewire\AddParent;
use App\Models\Image;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

/**
 * Teachers see only the students in their own sections, parents see only
 * their own children, and neither can reach anything that changes data.
 *
 * Two sections: the teacher is assigned to "mine", not to "other". The
 * parent is the parent of the student in "mine" only.
 */
class RoleAccessTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    private Section $mine;

    private Section $other;

    private Student $myStudent;

    private Student $otherStudent;

    private User $teacher;

    private User $parent;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('upload_attachments');

        $classroom = $this->createClassroom($this->createGrade());
        $this->mine = $this->createSection($classroom, 'A', 'أ');
        $this->other = $this->createSection($classroom, 'B', 'ب');

        $this->myStudent = $this->createStudent($this->mine, ['name' => ['en' => 'Mine Student', 'ar' => 'م']]);
        $this->otherStudent = $this->createStudent($this->other, ['name' => ['en' => 'Other Student', 'ar' => 'غ']]);

        $teacherRecord = $this->createTeacher();
        $this->mine->teachers()->attach($teacherRecord->id);

        $this->teacher = $this->createUser(Role::Teacher, ['teacher_id' => $teacherRecord->id]);
        $this->parent = $this->createUser(Role::Parent, ['parent_id' => $this->myStudent->parent_id]);
    }

    private function as(string $role): User
    {
        return $role === 'teacher' ? $this->teacher : $this->parent;
    }

    private function attachmentFor(Student $student): Image
    {
        $path = UploadedFile::fake()->create('card.png', 1, 'image/png')
            ->storeAs('students/' . $student->id, 'card.png', 'upload_attachments');

        $image = new Image();
        $image->filename = 'card.png';
        $image->path = $path;
        $image->imageable_id = $student->id;
        $image->imageable_type = Student::class;
        $image->save();

        return $image;
    }

    public static function restrictedRoles(): array
    {
        return ['teacher' => ['teacher'], 'parent' => ['parent']];
    }

    #[DataProvider('restrictedRoles')]
    public function test_student_list_shows_only_visible_students(string $role): void
    {
        $this->actingAs($this->as($role))
            ->get($this->url('Students'))
            ->assertOk()
            ->assertSee('Mine Student')
            ->assertDontSee('Other Student')
            ->assertDontSee(route('Students.create'))
            ->assertDontSee(route('Students.edit', $this->myStudent->id));
    }

    #[DataProvider('restrictedRoles')]
    public function test_can_open_a_visible_student(string $role): void
    {
        $this->actingAs($this->as($role))
            ->get($this->url('Students/' . $this->myStudent->id))
            ->assertOk()
            ->assertDontSee(route('Upload_attachment'));
    }

    #[DataProvider('restrictedRoles')]
    public function test_cannot_open_another_student(string $role): void
    {
        $this->actingAs($this->as($role))
            ->get($this->url('Students/' . $this->otherStudent->id))
            ->assertForbidden();
    }

    #[DataProvider('restrictedRoles')]
    public function test_can_download_a_visible_students_attachment(string $role): void
    {
        $image = $this->attachmentFor($this->myStudent);

        $this->actingAs($this->as($role))
            ->get($this->url('Download_attachment/' . $image->id))
            ->assertOk();
    }

    #[DataProvider('restrictedRoles')]
    public function test_cannot_download_another_students_attachment(string $role): void
    {
        $image = $this->attachmentFor($this->otherStudent);

        $this->actingAs($this->as($role))
            ->get($this->url('Download_attachment/' . $image->id))
            ->assertForbidden();
    }

    #[DataProvider('restrictedRoles')]
    public function test_cannot_download_a_graduated_students_attachment(string $role): void
    {
        $image = $this->attachmentFor($this->myStudent);
        $this->myStudent->delete();

        $this->actingAs($this->as($role))
            ->get($this->url('Download_attachment/' . $image->id))
            ->assertForbidden();
    }

    public function test_admin_can_still_download_a_graduated_students_attachment(): void
    {
        $image = $this->attachmentFor($this->myStudent);
        $this->myStudent->delete();
        $this->signIn();

        $this->get($this->url('Download_attachment/' . $image->id))->assertOk();
    }

    public static function adminOnlyRequests(): array
    {
        $requests = [
            ['get', 'Users'],
            ['get', 'Users/create'],
            ['post', 'Users'],
            ['get', 'Users/{teacherUser}/edit'],
            ['put', 'Users/{teacherUser}'],
            ['delete', 'Users/{teacherUser}'],
            ['get', 'Grades'],
            ['post', 'Grades'],
            ['get', 'Classrooms'],
            ['post', 'Classrooms'],
            ['post', 'delete_all'],
            ['post', 'Filter_Classes'],
            ['get', 'Sections'],
            ['post', 'Sections'],
            ['get', 'classes/1'],
            ['get', 'add_parent'],
            ['get', 'Teachers'],
            ['get', 'Teachers/create'],
            ['post', 'Teachers'],
            ['get', 'Students/create'],
            ['post', 'Students'],
            ['get', 'Students/{mine}/edit'],
            ['put', 'Students/{mine}'],
            ['delete', 'Students/{mine}'],
            ['get', 'Get_classrooms/1'],
            ['get', 'Get_Sections/1'],
            ['post', 'Upload_attachment'],
            ['post', 'Delete_attachment'],
            ['get', 'Promotion'],
            ['get', 'Promotion/create'],
            ['post', 'Promotion'],
            ['delete', 'Promotion/1'],
            ['get', 'Graduated'],
            ['get', 'Graduated/create'],
            ['post', 'Graduated'],
            ['put', 'Graduated/1'],
            ['delete', 'Graduated/1'],
            ['get', 'Fees'],
            ['get', 'Fees/create'],
            ['post', 'Fees'],
            ['put', 'Fees/1'],
            ['delete', 'Fees/1'],
        ];

        $cases = [];
        foreach (['teacher', 'parent'] as $role) {
            foreach ($requests as [$method, $path]) {
                $cases["$role $method $path"] = [$role, $method, $path];
            }
        }

        return $cases;
    }

    #[DataProvider('adminOnlyRequests')]
    public function test_admin_only_request_is_forbidden(string $role, string $method, string $path): void
    {
        $path = str_replace(
            ['{mine}', '{teacherUser}'],
            [$this->myStudent->id, $this->teacher->id],
            $path
        );

        $this->actingAs($this->as($role))
            ->call(strtoupper($method), $this->url($path), ['id' => $this->myStudent->id, 'student_id' => $this->myStudent->id])
            ->assertForbidden();

        $this->assertNotSoftDeleted($this->myStudent);
    }

    #[DataProvider('restrictedRoles')]
    public function test_parent_form_component_is_forbidden(string $role): void
    {
        Livewire::actingAs($this->as($role))->test(AddParent::class)->assertForbidden();
    }

    #[DataProvider('restrictedRoles')]
    public function test_sidebar_hides_admin_pages(string $role): void
    {
        $this->actingAs($this->as($role))
            ->get($this->url('dashboard'))
            ->assertOk()
            ->assertSee(route('Students.index'))
            ->assertDontSee(route('Grades.index'))
            ->assertDontSee(route('Fees.index'))
            ->assertDontSee(route('Users.index'));
    }

    public function test_teacher_moved_off_a_section_loses_its_students(): void
    {
        $this->mine->teachers()->detach();

        $this->actingAs($this->teacher)->get($this->url('Students'))->assertDontSee('Mine Student');
        $this->actingAs($this->teacher)->get($this->url('Students/' . $this->myStudent->id))->assertForbidden();
    }

    public static function unlinkedAccounts(): array
    {
        return ['teacher' => [Role::Teacher], 'parent' => [Role::Parent]];
    }

    #[DataProvider('unlinkedAccounts')]
    public function test_account_with_no_linked_record_sees_no_students(Role $role): void
    {
        $this->actingAs($this->createUser($role))
            ->get($this->url('Students'))
            ->assertOk()
            ->assertDontSee('Mine Student')
            ->assertDontSee('Other Student');
    }
}
