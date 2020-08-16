<?php

use Illuminate\Database\Seeder;
use App\Models\Timeline;
use Illuminate\Support\Facades\DB;

class TimelinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Timeline::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        factory(App\Timeline::class, 10)->create()->each(function ($timeline) {
            $body = factory(App\TLBody::class)->make();
            $timeline->body()->save($body);
        });
    }
}
