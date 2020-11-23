<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

// use App\User;
use Faker\Generator as Faker;
use Modules\ExternalOffice\Models\Cv;
use Modules\ExternalOffice\Models\{User, Contract, Profession};
use Modules\Main\Models\Office;

$factory->define(Cv::class, function (Faker $faker) {
	return [
		'name' => $faker->name,
		'passport' => $faker->bankAccountNumber,
		'amount' => rand(100, 10000),
		'procedure' => $faker->word,
		'gender' => random_int(1, 2),
		'birth_date' => $faker->date,
		'country_id' => Office::first()->country_id,
		'office_id' => Office::first()->id,
		'status' => Cv::STATUSES[array_rand(CV::STATUSES)],
		'nationality' => Office::first()->country->name,
		'religion' => $faker->word,
		'children' => random_int(0, 10),
		'profession_id' => Profession::inRandomOrder()->first()->id ?? factory(Profession::class)->create(),
		'user_id' => User::inRandomOrder()->first()->id ?? factory(User::class)->create(),
	];
});
