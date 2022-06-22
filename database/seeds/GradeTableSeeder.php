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
                    'en' => $fakerEn->word,
                    'ar' => $fakerAr->word,
                ],
                'Notes' => $fakerEn->text,
            ]);
        }
    }
}
