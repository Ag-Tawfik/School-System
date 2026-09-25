<?php

namespace Tests\Feature;

use App\Livewire\AddParent;
use App\Models\Image;
use App\Models\ParentAttachment;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\Concerns\CreatesSchoolData;
use Tests\TestCase;

/**
 * Student and parent attachments: stored off the public web root under
 * server-generated names, images only, served through an authenticated route.
 */
class AttachmentTest extends TestCase
{
    use RefreshDatabase;
    use CreatesSchoolData;

    // Smallest valid PNG: 1x1 transparent pixel.
    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
        Storage::fake('upload_attachments');
        Storage::fake('parent_attachments');
    }

    private function png(string $name = 'report card.png'): UploadedFile
    {
        return $this->file($name, base64_decode(self::PNG), 'image/png');
    }

    private function phpShell(string $name = 'shell.php'): UploadedFile
    {
        return $this->file($name, '<?php system($_GET["c"]);', 'image/png');
    }

    // A real upload, so validation detects the type from the bytes. A fake file
    // would report whatever mime type the test claims, hiding disguised uploads.
    // Built from bytes rather than UploadedFile::fake()->image(), which needs GD.
    private function file(string $name, string $contents, string $mime): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'upload');
        file_put_contents($path, $contents);

        return new UploadedFile($path, $name, $mime, null, true);
    }

    // Livewire's test uploader only accepts fake files.
    private function livewireFile(UploadedFile $file): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($file->getClientOriginalName(), file_get_contents($file->getPathname()));
    }

    private function studentPayload($section, array $overrides = []): array
    {
        return array_merge([
            'name_en' => 'Omar',
            'name_ar' => 'عمر',
            'email' => 'omar@example.com',
            'password' => 'secret1',
            'gender_id' => $this->createGender()->id,
            'nationalitie_id' => $this->createNationality()->id,
            'blood_id' => $this->createBloodType()->id,
            'birthday' => '2015-05-05',
            'grade_id' => $section->grade_id,
            'classroom_id' => $section->class_id,
            'section_id' => $section->id,
            'parent_id' => $this->createParent()->id,
            'academic_year' => '2025',
        ], $overrides);
    }

    public function test_registration_is_closed(): void
    {
        auth()->logout();

        $this->get('/register')->assertNotFound();
        $this->post('/register', [
            'name' => 'Stranger', 'email' => 'x@example.com',
            'password' => 'password', 'password_confirmation' => 'password',
        ])->assertNotFound();
    }

    public function test_student_created_with_a_photo_stores_it_under_a_generated_name(): void
    {
        $section = $this->createSection($this->createClassroom($this->createGrade()));

        $this->post($this->url('Students'), $this->studentPayload($section, ['photos' => [$this->png()]]))
            ->assertSessionHasNoErrors();

        $student = Student::firstOrFail();
        $image = Image::firstOrFail();
        $this->assertSame('report card.png', $image->filename);
        $this->assertStringStartsWith('students/' . $student->id . '/', $image->path);
        $this->assertStringNotContainsString('report card', $image->path);
        Storage::disk('upload_attachments')->assertExists($image->path);
    }

    public function test_student_store_rejects_a_php_file(): void
    {
        $section = $this->createSection($this->createClassroom($this->createGrade()));

        $this->post($this->url('Students'), $this->studentPayload($section, ['photos' => [$this->phpShell()]]))
            ->assertSessionHasErrors('photos.0');

        $this->assertSame(0, Student::count());
        $this->assertSame([], Storage::disk('upload_attachments')->allFiles());
    }

    public function test_upload_attachment_adds_an_image_to_an_existing_student(): void
    {
        $student = $this->createStudent($this->createSection($this->createClassroom($this->createGrade())));

        $this->post($this->url('Upload_attachment'), ['student_id' => $student->id, 'photos' => [$this->png()]])
            ->assertRedirect(route('Students.show', $student->id));

        Storage::disk('upload_attachments')->assertExists(Image::firstOrFail()->path);
    }

    public function test_upload_attachment_rejects_a_php_file_disguised_as_an_image(): void
    {
        $student = $this->createStudent($this->createSection($this->createClassroom($this->createGrade())));

        $this->post($this->url('Upload_attachment'), [
            'student_id' => $student->id, 'photos' => [$this->phpShell('photo.png')],
        ])->assertSessionHasErrors('photos.0');

        $this->assertSame(0, Image::count());
    }

    public function test_download_returns_the_file_under_its_original_name(): void
    {
        $student = $this->createStudent($this->createSection($this->createClassroom($this->createGrade())));
        $this->post($this->url('Upload_attachment'), ['student_id' => $student->id, 'photos' => [$this->png()]]);
        $image = Image::firstOrFail();

        $response = $this->get($this->url('Download_attachment/' . $image->id))->assertOk();

        $this->assertStringContainsString('report card.png', $response->headers->get('content-disposition'));
    }

    public function test_download_requires_login(): void
    {
        $student = $this->createStudent($this->createSection($this->createClassroom($this->createGrade())));
        $this->post($this->url('Upload_attachment'), ['student_id' => $student->id, 'photos' => [$this->png()]]);
        auth()->logout();

        $this->get($this->url('Download_attachment/' . Image::firstOrFail()->id))->assertRedirect('/login');
    }

    public function test_download_of_an_unknown_attachment_is_not_found(): void
    {
        $this->get($this->url('Download_attachment/999'))->assertNotFound();
    }

    public function test_delete_removes_the_file_and_the_row(): void
    {
        $student = $this->createStudent($this->createSection($this->createClassroom($this->createGrade())));
        $this->post($this->url('Upload_attachment'), ['student_id' => $student->id, 'photos' => [$this->png()]]);
        $image = Image::firstOrFail();

        $this->post($this->url('Delete_attachment'), ['id' => $image->id])
            ->assertRedirect(route('Students.show', $student->id));

        $this->assertSame(0, Image::count());
        Storage::disk('upload_attachments')->assertMissing($image->path);
    }

    public function test_parent_form_stores_photos_under_the_parent_id(): void
    {
        $nationality = $this->createNationality()->id;
        $blood = $this->createBloodType()->id;
        $religion = $this->createReligion()->id;

        Livewire::test(AddParent::class)
            ->set('email', 'family@example.com')->set('password', 'secret')
            ->set('fatherName', 'علي')->set('fatherName_en', 'Ali')
            ->set('fatherJobTitle', 'مهندس')->set('fatherJobTitle_en', 'Engineer')
            ->set('fatherNationalID', '1234567890')->set('fatherPassportID', '1234567891')
            ->set('fatherPhoneNumber', '05551234567')->set('fatherAddress', 'Ankara')
            ->set('fatherNationalty_id', $nationality)->set('fatherBloodType_id', $blood)->set('fatherReligion_id', $religion)
            ->set('motherName', 'فاطمة')->set('motherName_en', 'Fatma')
            ->set('motherJobTitle', 'طبيبة')->set('motherJobTitle_en', 'Doctor')
            ->set('motherNationalID', '2234567890')->set('motherPassportID', '2234567891')
            ->set('motherPhoneNumber', '05557654321')->set('motherAddress', 'Ankara')
            ->set('motherNationalty_id', $nationality)->set('motherBloodType_id', $blood)->set('motherReligion_id', $religion)
            ->set('photos', [$this->livewireFile($this->png())])
            ->call('submitForm')
            ->assertHasNoErrors()
            ->assertSet('catchError', null);

        $attachment = ParentAttachment::firstOrFail();
        $this->assertStringStartsWith($attachment->parent_id . '/', $attachment->file_name);
        Storage::disk('parent_attachments')->assertExists($attachment->file_name);
    }

    public function test_parent_form_rejects_a_php_file(): void
    {
        Livewire::test(AddParent::class)
            ->set('photos', [$this->livewireFile($this->phpShell())])
            ->call('submitForm')
            ->assertHasErrors('photos.0');

        $this->assertSame(0, ParentAttachment::count());
        $this->assertSame([], Storage::disk('parent_attachments')->allFiles());
    }
}
