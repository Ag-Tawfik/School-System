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
                    'en' => $fakerEn->name,
                    'ar' => $fakerAr->name,
                ],
                'Status' => $fakerEn->boolean(),
                'Grade_id' => Grade::all()->random()->id,
                'Class_id' => Classroom::all()->random()->id,
            ]);
        }
    }
}
