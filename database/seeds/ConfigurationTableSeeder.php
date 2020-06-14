<?php

use Illuminate\Database\Seeder;
use App\TLType;

class ConfigurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //TLType configuration

        TLType::truncate();
        TLType::create([
            'id' => 1,
            'name' => 'Message'
        ]);
        TLType::create([
            'id' => 2,
            'name' => 'Support'
        ]);
        TLType::create([
            'id' => 3,
            'name' => 'Announcement'
        ]);
    }
}
