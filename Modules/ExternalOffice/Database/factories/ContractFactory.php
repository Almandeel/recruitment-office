<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExternalOffice\Models\{Contract, Country, Marketer};

$factory->define(Contract::class, function (Faker $faker) {
	return [
		'visa' => $faker->randomNumber(),
		'profession' => $faker->jobTitle,
		'marketing_ratio' => rand(10, 90),
		'gender' => rand(1, 2),
		'details' => $faker->paragraph,
		'marketer_id' => Marketer::inRandomOrder()->first()->id ?? factory(Marketer::class)->create()->id,
		'country_id' => Country::inRandomOrder()->first()->id ?? factory(Country::class)->create()->id,
		'amount' => $faker->numberBetween(100, 10000),
	];
});
