<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Modules\Accounting\Models\Center;
use Faker\Generator as Faker;

$factory->define(Center::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'type' => Center::TYPES[array_rand(Center::TYPES)], 
    ];
});
