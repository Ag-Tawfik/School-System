<?php

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeTableSeeder extends Seeder
{
    public function run()
    {
        $fakerAr = Faker\Factory::create('ar_SA');
        $fakerEn = Faker\Factory::create('en_US');
        $numbers = 5;

        for ($i = 0; $i < $numbers; $i++) {
            Grade::create([
                'Name' => [
                    'en' => $fakerEn->unique()->randomElement(['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5']),
                    'ar' => $fakerAr->unique()->randomElement(['المرحلة 1', 'المرحلة 2', 'المرحلة 3', 'المرحلة 4', 'المرحلة 5']),
                ],
                'Notes' => $fakerEn->paragraph(1),
            ]);
        }
    }
}
