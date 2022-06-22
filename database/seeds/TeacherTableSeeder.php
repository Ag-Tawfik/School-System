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
        $numbers = 5;

        for ($i = 0; $i < $numbers; $i++) {
            Teacher::create([
                'Email' => $fakerEn->unique()->safeEmail,
                'Password' => bcrypt('password'),
                'Name' => [
                    'en' => $fakerEn->name,
                    'ar' => $fakerAr->name,
                ],
                'Specialization_id' => Specialization::all()->random()->id,
                'Gender_id' => Gender::all()->random()->id,
                'Joining_Date' => $fakerEn->date,
                'Address' => $fakerEn->address,
            ]);
        }
    }
}
