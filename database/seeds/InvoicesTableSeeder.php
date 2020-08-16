<?php

use Illuminate\Database\Seeder;
use App\Models\Invoice;
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

        for ($i = 1; $i <= 12; $i++) {

            $date = $faker->dateTimeBetween($startDate = '-12 months', $endDate = 'now', $timezone = null);
            Invoice::create([
                //'user_id' => $faker->randomElement($userIDs),
                'user_id' => '1',
                'invoiceh_id' => 1,
                'invno' => $faker->ean13,
                'invdate' => sprintf("2020-%02d-01", $i),
                'year' => "2020",
                'desc' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'amount' => $faker->randomNumber(4),
                'filename' => $faker->bankAccountNumber . ".pdf",
                'unread' => $faker->randomElement($array = array(false, true)),
                'published' => false
            ]);
        }
    }
}
