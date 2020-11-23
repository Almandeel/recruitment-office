<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Modules\ExternalOffice\Models\User;
use Modules\Main\Models\Office;
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

$factory->define(User::class, function (Faker $faker) {
	return [
		'name' => $faker->name,
		'username' => $faker->name,
		'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
		'phone' => $faker->e164PhoneNumber,
		'status' => rand(0, 1),
		'office_id' => Office::inRandomOrder()->first()->id ?? factory(Office::class)->create()->id,
	];
});
