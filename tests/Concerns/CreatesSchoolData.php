<?php

namespace Tests\Concerns;

use App\Models\BloodType;
use App\Models\Classroom;
use App\Models\Fee;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Nationalitie;
use App\Models\Religion;
use App\Models\Section;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TheParent;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Builds test data with plain Model::create() calls.
 *
 * Deliberately avoids model factories: the closure-style factories this app
 * uses were removed in Laravel 8, and these tests must run unchanged on every
 * Laravel version the upgrade passes through.
 */
trait CreatesSchoolData
{
    protected function signIn(): User
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin' . Str::random(6) . '@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);

        return $user;
    }

    protected function createGender(): Gender
    {
        return Gender::create(['name' => ['en' => 'Male', 'ar' => 'ذكر']]);
    }

    protected function createNationality(): Nationalitie
    {
        return Nationalitie::create(['name' => ['en' => 'Turkish', 'ar' => 'تركي']]);
    }

    protected function createBloodType(): BloodType
    {
        return BloodType::create(['name' => 'O+']);
    }

    protected function createReligion(): Religion
    {
        return Religion::create(['name' => ['en' => 'Muslim', 'ar' => 'مسلم']]);
    }

    protected function createSpecialization(): Specialization
    {
        return Specialization::create(['name' => ['en' => 'Maths', 'ar' => 'رياضيات']]);
    }

    protected function createGrade(string $en = 'Primary', string $ar = 'ابتدائي'): Grade
    {
        return Grade::create(['name' => ['en' => $en, 'ar' => $ar], 'notes' => null]);
    }

    protected function createClassroom(Grade $grade, string $en = 'First', string $ar = 'الأول'): Classroom
    {
        return Classroom::create(['name' => ['en' => $en, 'ar' => $ar], 'grade_id' => $grade->id]);
    }

    protected function createSection(Classroom $classroom, string $en = 'A', string $ar = 'أ'): Section
    {
        // status is not fillable on the model.
        $section = new Section();
        $section->name = ['en' => $en, 'ar' => $ar];
        $section->status = 1;
        $section->grade_id = $classroom->grade_id;
        $section->class_id = $classroom->id;
        $section->save();

        return $section;
    }

    protected function createTeacher(array $overrides = []): Teacher
    {
        return Teacher::create(array_merge([
            'email' => 'teacher' . Str::random(6) . '@example.com',
            'password' => Hash::make('secret'),
            'name' => ['en' => 'Ayse', 'ar' => 'عائشة'],
            'specialization_id' => $this->createSpecialization()->id,
            'gender_id' => $this->createGender()->id,
            'joining_date' => '2024-09-01',
            'address' => 'Istanbul',
        ], $overrides));
    }

    protected function createParent(array $overrides = []): TheParent
    {
        $nationality = $this->createNationality()->id;
        $blood = $this->createBloodType()->id;
        $religion = $this->createReligion()->id;

        return TheParent::create(array_merge([
            'email' => 'parent' . Str::random(6) . '@example.com',
            'password' => Hash::make('secret'),
            'fatherName' => ['en' => 'Ali', 'ar' => 'علي'],
            'fatherNationalID' => (string) random_int(1000000000, 9999999999),
            'fatherPassportID' => (string) random_int(1000000000, 9999999999),
            'fatherPhoneNumber' => '05551234567',
            'fatherJobTitle' => ['en' => 'Engineer', 'ar' => 'مهندس'],
            'fatherNationalty_id' => $nationality,
            'fatherBloodType_id' => $blood,
            'fatherReligion_id' => $religion,
            'fatherAddress' => 'Ankara',
            'motherName' => ['en' => 'Fatma', 'ar' => 'فاطمة'],
            'motherNationalID' => (string) random_int(1000000000, 9999999999),
            'motherPassportID' => (string) random_int(1000000000, 9999999999),
            'motherPhoneNumber' => '05557654321',
            'motherJobTitle' => ['en' => 'Doctor', 'ar' => 'طبيبة'],
            'motherNationalty_id' => $nationality,
            'motherBloodType_id' => $blood,
            'motherReligion_id' => $religion,
            'motherAddress' => 'Ankara',
        ], $overrides));
    }

    protected function createStudent(Section $section, array $overrides = []): Student
    {
        return Student::create(array_merge([
            'name' => ['en' => 'Omar', 'ar' => 'عمر'],
            'email' => 'student' . Str::random(6) . '@example.com',
            'password' => Hash::make('secret'),
            'gender_id' => $this->createGender()->id,
            'nationalitie_id' => $this->createNationality()->id,
            'blood_id' => $this->createBloodType()->id,
            'birthday' => '2015-05-05',
            'grade_id' => $section->grade_id,
            'classroom_id' => $section->class_id,
            'section_id' => $section->id,
            'parent_id' => $this->createParent()->id,
            'academic_year' => '2025',
        ], $overrides));
    }

    protected function createFee(Classroom $classroom, array $overrides = []): Fee
    {
        return Fee::create(array_merge([
            'title' => ['en' => 'Tuition', 'ar' => 'رسوم دراسية'],
            'amount' => 1500,
            'Grade_id' => $classroom->grade_id,
            'Classroom_id' => $classroom->id,
            'description' => null,
            'year' => '2025',
            'Fee_type' => 1,
        ], $overrides));
    }
}
