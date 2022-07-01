<?php

use App\Models\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecializationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('specializations')->Delete();

        $specializations = [
            ['en' => 'Arabic', 'ar' => 'عربي'],
            ['en' => 'English', 'ar' => 'انجليزي'],
            ['en' => 'Fransh', 'ar' => 'الفرنسية'],
            ['en' => 'Sciences', 'ar' => 'علوم'],
            ['en' => 'Computer Science', 'ar' => 'علوم الكمبيوتر'],
            ['en' => 'Mathematics', 'ar' => 'الرياضيات'],
            ['en' => 'Geography', 'ar' => 'جغرافية'],
            ['en' => 'Geometry', 'ar' => 'الهندسة'],
            ['en' => 'Geology', 'ar' => 'جيولوجيا'],
            ['en' => 'Philosophy', 'ar' => 'فلسفة'],
            ['en' => 'Physics', 'ar' => 'الفيزياء'],
        ];
        foreach ($specializations as $specialization) {
            Specialization::create(['name' => $specialization]);
        }
    }
}
