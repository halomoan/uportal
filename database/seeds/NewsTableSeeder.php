<?php

use Illuminate\Database\Seeder;
use App\News;
use Illuminate\Support\Facades\DB;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        News::truncate();
        $faker = \Faker\Factory::create();
        $userIDs = DB::table('users')->pluck('id');

        for ($i = 0; $i < 20; $i++) {

            News::create([
                //'user_id' => $faker->randomElement($userIDs),
                'user_id' => 1,
                'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'author' => $faker->name,
                'showauthor' => $faker->boolean,
                'validFrom' => $faker->dateTime($max = 'now', $timezone = null),
                'validTo' => $faker->dateTime($min = 'now', $timezone = null),

            ]);
        }
    }
}
