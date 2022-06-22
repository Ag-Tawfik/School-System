<?php

use App\Models\Grade;
use App\Models\Gender;
use App\Models\Section;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\My_Parent;
use App\Models\Type_Blood;
use App\Models\Nationalitie;
use Illuminate\Database\Seeder;

class StudentTableSeeder extends Seeder
{
    public function run()
    {
        $fakerAr = Faker\Factory::create('ar_SA');
        $fakerEn = Faker\Factory::create('en_US');
        $numbers = 5;

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
                'blood_id' => Type_Blood::all()->random()->id,
                'Date_Birth' => $fakerEn->date,
                'Grade_id' => Grade::all()->random()->id,
                'Classroom_id' => Classroom::all()->random()->id,
                'section_id' => Section::all()->random()->id,
                'parent_id' => My_Parent::all()->random()->id,
                'academic_year' => $fakerEn->year,
            ]);
        }
    }
}
