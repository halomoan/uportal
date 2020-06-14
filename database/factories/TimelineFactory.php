<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Timeline;
use App\TLBody;
use Faker\Generator as Faker;

$factory->define(Timeline::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'title' => '_FROM_ sent you an email',
        'from' => $faker->name,
        'type' => 1,
        'link' =>  $faker->randomElement(['/invoice', null])
    ];
});

$factory->define(TLBody::class, function (Faker $faker) {
    return [
        'body' => $faker->realText($maxNbChars = 200, $indexSize = 2)
    ];
});
