<?php

use Illuminate\Database\Seeder;
use App\Models\News;
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        News::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        $faker = \Faker\Factory::create();
        $userIDs = DB::table('users')->pluck('id');
        $color = ['warning', 'success', 'danger', 'info'];

        for ($i = 0; $i < 20; $i++) {

            News::create([
                //'user_id' => $faker->randomElement($userIDs),
                'user_id' => 1,
                'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'author' => $faker->name,
                'showauthor' => $faker->boolean,
                'validFrom' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+3 months', $timezone = null),
                'validTo' => $faker->dateTimeBetween($startDate = '+30 days', $endDate = '+3 months', $timezone = null),
                'color' => $faker->randomElement($color),

            ]);
        }
    }
}
