<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Name' => $faker->name,
            'Email' => $faker->unique()->safeEmail,
            'Password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'Specialization_id' => 1,
            'Gender_id' => 1,
            'Joining_Date' => $faker->date(),
            'Address' => $faker->address
        ];
    }

    // /** @var \Illuminate\Database\Eloquent\Factory $factory */

    // use App\Models\Teacher;
    // use Faker\Generator as Faker;

    // $factory->define(Teacher::class, function (Faker $faker) {
    //     return [
    //         'Name' => $faker->name,
    //         'Email' => $faker->unique()->safeEmail,
    //         'Password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    //         'Specialization_id' => 1,
    //         'Gender_id' => 1,
    //         'Joining_Date' => $faker->date(),
    //         'Address' => $faker->address
    //     ];
    // });
}
