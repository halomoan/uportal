<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's clear the users table first
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $faker = \Faker\Factory::create();
        $password = Hash::make('sap12345');

        User::create([
            'name' => 'Halomoan',
            'email' => 'halomoan@uportal.test',
            'password' => $password,
            'urole' => 'admin'
        ]);

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'urole' => 'user',
                'password' => $password,
            ]);
        }
    }
}
