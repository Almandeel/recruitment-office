<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExternalOffice\Models\Profession;

$factory->define(Profession::class, function (Faker $faker) {
    return [
	    'name' => $faker->name,
	    'name_en' => $faker->name,
    ];
});
