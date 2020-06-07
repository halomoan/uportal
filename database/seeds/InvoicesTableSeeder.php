<?php

use Illuminate\Database\Seeder;
use App\Invoice;
use Illuminate\Support\Facades\DB;

class InvoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Invoice::truncate();
        $faker = \Faker\Factory::create();
        $userIDs = DB::table('users')->pluck('id');

        for ($i = 0; $i < 50; $i++) {

            $date = $faker->dateTimeBetween($startDate = '-12 months', $endDate = 'now', $timezone = null);
            Invoice::create([
                //'user_id' => $faker->randomElement($userIDs),
                'user_id' => '1',
                'inv_no' => $faker->ean13,
                'inv_date' => $date,
                'year' => $date->format('Y'),
                'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'amount' => $faker->randomNumber(4),
                'filename' => $faker->bankAccountNumber . ".pdf",
                'unread' => $faker->randomElement($array = array(false, true)),
            ]);
        }
    }
}
