<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExternalOffice\Models\User;
use Modules\Services\Models\Office;
use Modules\ExternalOffice\Models\Country;

$factory->define(Office::class, function (Faker $faker) {
	return [
	    'name' => $faker->word,
	    'status' => rand(0, 1),
	    'country_id' => Country::inRandomOrder()->first()->id ?? factory(Country::class)->create()->id,
	    'email' => $faker->safeEmail,
	    'phone' => $faker->e164PhoneNumber,
	    'admin_id' => User::inRandomOrder()->first()->id ?? factory(User::class)->create()->id,
	];
});
