<?php

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeTableSeeder extends Seeder
{
    public function run()
    {
        $fakerAr = Faker\Factory::create('ar_SA');
        $fakerEn = Faker\Factory::create('en_US');
        $numbers = 10;

        for ($i = 0; $i < $numbers; $i++) {
            Grade::create([
                'name' => [
                    'en' => $fakerEn->unique()->randomElement(['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10']),
                    'ar' => $fakerAr->unique()->randomElement(['المرحلة 1', 'المرحلة 2', 'المرحلة 3', 'المرحلة 4', 'المرحلة 5', 'المرحلة 6', 'المرحلة 7', 'المرحلة 8', 'المرحلة 9', 'المرحلة 10']),
                ],
                'notes' => $fakerEn->paragraph(1),
            ]);
        }
    }
}
