<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExternalOffice\Models\Country;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'name' => $faker->country
    ];
});
