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

        for ($i = 0; $i < 200; $i++) {

            Invoice::create([
                'user_id' => $faker->randomElement($userIDs),
                'inv_no' => $faker->ean13,
                'inv_date' => $faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now', $timezone = null),
                'year' => $faker->randomElement($array = array ('2020')),
                'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'amount' => $faker->randomNumber(8),
                'filename' => $faker->bankAccountNumber . ".pdf",
                'unread' => false
            ]);
        }
    }
}
