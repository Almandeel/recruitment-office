<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Modules\Accounting\Models\Year;
use Faker\Generator as Faker;

$factory->define(Year::class, function (Faker $faker) {
    return [
        'opened_at' => $opened_at = $faker->date,
        'id' => date('Ymd', strtotime($opened_at)),
        'status' => Year::STATUSES[array_rand(Year::STATUSES)],
    ];
});
