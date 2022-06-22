<?php

use App\Models\My_Parent;
use App\Models\Nationalitie;
use App\Models\Religion;
use App\Models\Type_Blood;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ParentTableSeeder extends Seeder
{
    public function run()
    {
        $fakerAr = Faker\Factory::create( 'ar_SA' );
        $fakerEn = Faker\Factory::create( 'en_US' );
        $numbers = 5;

        for ($i = 0; $i < $numbers; $i++) {
            My_Parent::create([
                'email' => $fakerEn->unique()->safeEmail,
                'password' => bcrypt('password'),
                'Name_Father' => [
                    'en' => $fakerEn->name,
                    'ar' => $fakerAr->name,
                ],
                'National_ID_Father' => Str::random(10),
                'Passport_ID_Father' => Str::random(10),
                'Phone_Father' => $fakerEn->phoneNumber,
                'Job_Father' => [
                    'en' => $fakerEn->jobTitle,
                    'ar' => $fakerAr->jobTitle,
                ],
                'Nationality_Father_id' => Nationalitie::all()->random()->id,
                'Blood_Type_Father_id' => Type_Blood::all()->random()->id,
                'Religion_Father_id' => Religion::all()->random()->id,
                'Address_Father' => $fakerEn->address,

                'Name_Mother' => [
                    'en' => $fakerEn->name,
                    'ar' => $fakerAr->name,
                ],
                'National_ID_Mother' => Str::random(10),
                'Passport_ID_Mother' => Str::random(10),
                'Phone_Mother' => $fakerEn->phoneNumber,
                'Job_Mother' => [
                    'en' => $fakerEn->jobTitle,
                    'ar' => $fakerAr->jobTitle,
                ],
                'Nationality_Mother_id' => Nationalitie::all()->random()->id,
                'Blood_Type_Mother_id' => Type_Blood::all()->random()->id,
                'Religion_Mother_id' => Religion::all()->random()->id,
                'Address_Mother' => $fakerEn->address,
            ]);

        }
    }
}
