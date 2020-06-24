<?php

use Illuminate\Database\Seeder;
use App\TLType;
use App\Company;

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

        //Company Configuration
        Company::truncate();
        Company::create(['CoCode' => '1001', 'Name' => 'UOL Group Limited']);
        Company::create(['CoCode' => '1002', 'Name' => 'UOL Property Investments Pte. Ltd.']);
        Company::create(['CoCode' => '1003', 'Name' => 'Novena Square Development Pte. Ltd.']);
    }
}
