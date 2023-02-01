<?php

use App\Models\Grade;
use App\Models\Gender;
use App\Models\Section;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\TheParent;
use App\Models\BloodType;
use App\Models\Nationalitie;
use Illuminate\Database\Seeder;

class StudentTableSeeder extends Seeder
{
    public function run()
    {
        $fakerAr = Faker\Factory::create('ar_SA');
        $fakerEn = Faker\Factory::create('en_US');
        $numbers = 10;

        for ($i = 0; $i < $numbers; $i++) {
            Student::create([
                'name' => [
                    'en' => $fakerEn->name,
                    'ar' => $fakerAr->name,
                ],
                'email' => $fakerEn->unique()->safeEmail,
                'password' => bcrypt('password'),
                'gender_id' => Gender::all()->random()->id,
                'nationalitie_id' => Nationalitie::all()->random()->id,
                'blood_id' => BloodType::all()->random()->id,
                'birthday' => $fakerEn->date,
                'grade_id' => Grade::all()->random()->id,
                'classroom_id' => Classroom::all()->random()->id,
                'section_id' => Section::all()->random()->id,
                'parent_id' => TheParent::all()->random()->id,
                'academic_year' => $fakerEn->year,
            ]);
        }
    }
}