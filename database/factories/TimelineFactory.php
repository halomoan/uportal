<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Timeline;
use App\Models\TLBody;
use Faker\Generator as Faker;

$factory->define(Timeline::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'title' => 'sent you an email',
        'from' => $faker->name,
        'type' => 1,
        'link' =>  $faker->randomElement(['invoices', null]),
        'param1' => $faker->randomElement(['2020', '2019', '2018', null]),
    ];
});

$factory->define(TLBody::class, function (Faker $faker) {
    return [
        'body' => $faker->realText($maxNbChars = 200, $indexSize = 2)
    ];
});
