<?php

use Illuminate\Database\Seeder;
use App\Models\TLType;
use App\Models\Company;
use App\Models\Group;
use App\Models\SAPConfig;
use App\Models\FAConfig;
use App\Models\User;
use SAPNWRFC\Connection as SapConnection;


class ConfigurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        SAPConfig::truncate();
        Group::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');


        // Initial User
        $faker = \Faker\Factory::create();
        $password = Hash::make('sap12345');

        User::create([
            'name' => 'Halomoan',
            'email' => 'halomoan@uportal.test',
            'password' => $password,
            'urole' => 'admin',
            'email_verified_at' => now()
        ]);


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

        //Pre-defined SAP system

        SAPConfig::create([
            'desc' => 'ERP QAS Server',
            'ashost' => '172.16.1.11',
            'sysnr'  => '00',
            'client' => '320',
            'user'   => 'khalomoan',
            'passwd' => 'boss2218',
            'trace'  => SapConnection::TRACE_LEVEL_OFF,

        ]);

        SAPConfig::create([
            'desc' => 'ERP PRD Server',
            'ashost' => '172.16.1.64',
            'sysnr'  => '00',
            'client' => '800',
            'user'   => 'khalomoan',
            'passwd' => 'boss2218',
            'trace'  => SapConnection::TRACE_LEVEL_OFF,

        ]);
        // Pre-defined Groups
        Group::create(['name' => 'QAS Barcode Server', 'is_enabled' => 1, 'type' => 'barcd']);
        Group::create(['name' => 'PRD Barcode Server', 'is_enabled' => 1, 'type' => 'barcd']);

        //Pre-defined FA config
        FAConfig::truncate();
        FAConfig::create([
            'sub1len' => 4,
            'sub2len' => 6,
            'sub3len' => 4,
            'runlen' => 4,
        ]);
    }
}
