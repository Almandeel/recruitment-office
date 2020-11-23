<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExternalOffice\Models\Marketer;

$factory->define(Marketer::class, function (Faker $faker) {
    return [
	    'name' => $faker->name,
	    'phone' => (integer) $faker->e164PhoneNumber,
    ];
});
