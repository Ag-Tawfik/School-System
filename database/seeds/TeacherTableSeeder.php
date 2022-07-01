<?php

use App\Models\Gender;
use App\Models\Specialization;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherTableSeeder extends Seeder
{
    public function run()
    {
        $fakerAr = Faker\Factory::create('ar_SA');
        $fakerEn = Faker\Factory::create('en_US');
        $numbers = 10;

        for ($i = 0; $i < $numbers; $i++) {
            Teacher::create([
                'email' => $fakerEn->unique()->safeEmail,
                'password' => bcrypt('password'),
                'name' => [
                    'en' => $fakerEn->name,
                    'ar' => $fakerAr->name,
                ],
                'specialization_id' => Specialization::all()->random()->id,
                'gender_id' => Gender::all()->random()->id,
                'joining_date' => $fakerEn->date,
                'address' => $fakerEn->address,
            ]);
        }
    }
}
