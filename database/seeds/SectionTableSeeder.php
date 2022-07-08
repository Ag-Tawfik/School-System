<?php

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionTableSeeder extends Seeder
{
    public function run()
    {
        $fakerAr = Faker\Factory::create('ar_SA');
        $fakerEn = Faker\Factory::create('en_US');
        $numbers = 10;

        for ($i = 0; $i < $numbers; $i++) {
            Section::create([
                'name' => [
                    'en' => $fakerEn->unique()->randomElement(['Section 1', 'Section 2', 'Section 3', 'Section 4', 'Section 5', 'Section 6', 'Section 7', 'Section 8', 'Section 9', 'Section 10']),
                    'ar' => $fakerAr->unique()->randomElement(['القسم الاول', 'القسم الثاني', 'القسم الثالث', 'القسم الرابع', 'القسم الخامس', 'القسم السادس', 'القسم السابع', 'القسم الثامن', 'القسم التاسع', 'القسم العاشر']),
                ],
                'status' => $fakerEn->boolean(),
                'grade_id' => Grade::all()->random()->id,
                'class_id' => Classroom::all()->random()->id,
            ]);
        }
    }
}
