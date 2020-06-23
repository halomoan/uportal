<?php

use Illuminate\Database\Seeder;
use App\Company;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::truncate();
        
        Company::create([ 'CoCode' => '1001', 'Name' => 'UOL Group Limited' ]);
        Company::create([ 'CoCode' => '1002', 'Name' => 'UOL Property Investments Pte. Ltd.' ]);
        Company::create([ 'CoCode' => '1003', 'Name' => 'Novena Square Development Pte. Ltd.' ]);

    }
}
