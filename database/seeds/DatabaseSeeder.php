<?php

use Illuminate\Database\Seeder;
use SAPNWRFC\Connection as SapConnection;
use App\Models\SAPConfig;
use App\Models\FAConfig;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        // SAPConfig::truncate();

        // SAPConfig::create([
        //     'desc' => 'ERP DEV Server',
        //     'ashost' => '172.16.1.4',
        //     'sysnr'  => '00',
        //     'client' => '200',
        //     'user'   => 'khalomoan',
        //     'passwd' => 'n3tw0rk1',
        //     'trace'  => SapConnection::TRACE_LEVEL_OFF,

        // ]);


        // SAPConfig::create([
        //     'desc' => 'ERP QAS Server',
        //     'ashost' => '172.16.1.11',
        //     'sysnr'  => '00',
        //     'client' => '320',
        //     'user'   => 'khalomoan',
        //     'passwd' => 'boss2218',
        //     'trace'  => SapConnection::TRACE_LEVEL_OFF,

        // ]);

        // SAPConfig::create([
        //     'desc' => 'ERP PRD Server',
        //     'ashost' => '172.16.1.64',
        //     'sysnr'  => '00',
        //     'client' => '800',
        //     'user'   => 'khalomoan',
        //     'passwd' => 'boss2218',
        //     'trace'  => SapConnection::TRACE_LEVEL_OFF,

        // ]);

        FAConfig::truncate();
        FAConfig::create([
            'desc' => 'Init Config',
            'sub1len' => 4,
            'sub2len' => 6,
            'sub3len' => 4,
            'runlen' => 4,
        ]);
    }
}
