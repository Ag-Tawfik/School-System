<?php

use App\Models\Grade;
use App\Models\Section;
use App\Models\Classroom;
use Illuminate\Database\Seeder;

class SectionTableSeeder extends Seeder
{
    public function run()
    {
        $fakerAr = Faker\Factory::create('ar_SA');
        $fakerEn = Faker\Factory::create('en_US');
        $numbers = 5;

        for ($i = 0; $i < $numbers; $i++) {
            Section::create([
                'Name_Section' => [
                    'en' => $fakerEn->unique()->randomElement(['Section 1', 'Section 2', 'Section 3', 'Section 4', 'Section 5']),
                    'ar' => $fakerAr->unique()->randomElement(['القسم الاول', 'القسم الثاني', 'القسم الثالث', 'القسم الرابع', 'القسم الخامس']),
                ],
                'Status' => $fakerEn->boolean(),
                'grade_id' => Grade::all()->random()->id,
                'Class_id' => Classroom::all()->random()->id,
            ]);
        }
    }
}
