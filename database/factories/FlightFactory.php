<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExternalOffice\Models\Cv;
use Modules\ExternalOffice\Models\Flight;
use Modules\ExternalOffice\Models\CvFlight;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Flight::class, function (Faker $faker) {
	return [
        'departure_at' => now(),
        'arrival_at' => now(),
        'departure_airport' => $faker->name,
        'arrival_airport' => $faker->name,
        'trip_number' => rand(1230, 9999),
        'airline_name' => $faker->name,
		'status' => rand(0, 1),
	];
});

$factory->define(CvFlight::class, function (Faker $faker) {
    return [
        'flight_id' => factory(Flight::class),
        'cv_id' => factory(Cv::class),
        'status' => rand(0, 1),
    ];
});
