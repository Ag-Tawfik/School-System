<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'Admin@Admin.com',
            'password' => bcrypt('password')
        ]);
    }
}
