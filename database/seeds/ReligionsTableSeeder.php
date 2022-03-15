<?php

use App\Models\Religion;
use Illuminate\Database\Seeder;

class ReligionsTableSeeder extends Seeder
{

    public function run()
    {
        $religions = [
            [
                'en' => 'Muslim',
                'ar' => 'إسلام'

            ],
            [
                'en' => 'Other',
                'ar' => 'أخرى',
            ],
        ];

        foreach ($religions as $religion) {
            Religion::create(['Name' => $religion]);
        }
    }
}
