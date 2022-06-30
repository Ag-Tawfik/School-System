<?php

use App\Models\Classroom;
use App\Models\Grade;
use Illuminate\Database\Seeder;

class ClassroomTableSeeder extends Seeder
{
    public function run()
    {
        $fakerAr = Faker\Factory::create('ar_SA');
        $fakerEn = Faker\Factory::create('en_US');
        $numbers = 5;

        for ($i = 0; $i < $numbers; $i++) {
            Classroom::create([
                'Name_Class' => [
                    'en' => $fakerEn->unique()->randomElement(['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5']),
                    'ar' => $fakerAr->unique()->randomElement(['الصف الاول', 'الصف الثاني', 'الصف الثالث', 'الصف الرابع', 'الصف الخامس']),
                ],
                'grade_id' => Grade::all()->random()->id,
            ]);
        }
    }
}
