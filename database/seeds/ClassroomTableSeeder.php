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
        $numbers = 10;

        for ($i = 0; $i < $numbers; $i++) {
            Classroom::create([
                'class_name' => [
                    'en' => $fakerEn->unique()->randomElement(['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10']),
                    'ar' => $fakerAr->unique()->randomElement(['الصف الاول', 'الصف الثاني', 'الصف الثالث', 'الصف الرابع', 'الصف الخامس', 'الصف السادس', 'الصف السابع', 'الصف الثامن', 'الصف التاسع', 'الصف العاشر']),
                ],
                'grade_id' => Grade::all()->random()->id,
            ]);
        }
    }
}
