<?php

namespace Database\Factories;

use App\Models\Gender;
use App\Models\Specialization;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Name' => $this->faker->name,
            'Email' => $this->faker->unique()->safeEmail,
            'Password' => bcrypt('password'),
            'Specialization_id' => randomOrCreateFactory(Specialization::class),
            'Gender_id' => randomOrCreateFactory(Gender::class),
            'Joining_Date' => $this->faker->date(),
            'Address' => $this->faker->address
        ];
    }
}