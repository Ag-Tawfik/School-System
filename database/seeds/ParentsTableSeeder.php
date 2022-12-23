<?php

use App\Models\TheParent;
use App\Models\Nationalitie;
use App\Models\Religion;
use App\Models\BloodType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ParentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $fakerAr = Faker\Factory::create( 'ar_SA' );
        $fakerEn = Faker\Factory::create( 'en_US' );
        $numbers = 10;

        for ($i = 0; $i < $numbers; $i++) {
            TheParent::create([
                'email' => $fakerEn->unique()->safeEmail,
                'password' => bcrypt('password'),
                'fatherName' => [
                    'en' => $fakerEn->name,
                    'ar' => $fakerAr->name,
                ],
                'fatherNationalID' => Str::random(10),
                'fatherPassportID' => Str::random(10),
                'fatherPhoneNumber' => $fakerEn->phoneNumber,
                'fatherJobTitle' => [
                    'en' => $fakerEn->jobTitle,
                    'ar' => $fakerAr->jobTitle,
                ],
                'fatherNationalty_id' => Nationalitie::all()->random()->id,
                'fatherBloodType_id' => BloodType::all()->random()->id,
                'fatherReligion_id' => Religion::all()->random()->id,
                'fatherAddress' => $fakerEn->address,

                'motherName' => [
                    'en' => $fakerEn->name,
                    'ar' => $fakerAr->name,
                ],
                'motherNationalID' => Str::random(10),
                'motherPassportID' => Str::random(10),
                'motherPhoneNumber' => $fakerEn->phoneNumber,
                'motherJobTitle' => [
                    'en' => $fakerEn->jobTitle,
                    'ar' => $fakerAr->jobTitle,
                ],
                'motherNationalty_id' => Nationalitie::all()->random()->id,
                'motherBloodType_id' => BloodType::all()->random()->id,
                'motherReligion_id' => Religion::all()->random()->id,
                'motherAddress' => $fakerEn->address,
            ]);

        }
    }
}
