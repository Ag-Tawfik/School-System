<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type__bloods')->insert([
            'Name' => 'A+',
        ]);
        DB::table('type__bloods')->insert([
            'Name' => 'A-',
        ]);
        DB::table('type__bloods')->insert([
            'Name' => 'B+',
        ]);
        DB::table('type__bloods')->insert([
            'Name' => 'B-',
        ]);
        DB::table('type__bloods')->insert([
            'Name' => 'AB+',
        ]);
        DB::table('type__bloods')->insert([
            'Name' => 'AB-',
        ]);
        DB::table('type__bloods')->insert([
            'Name' => 'O+',
        ]);
        DB::table('type__bloods')->insert([
            'Name' => 'O-',
        ]);
    }
}
