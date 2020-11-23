<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Services\Models\Customer;
$factory->define(Customer::class, function (Faker $faker) {
    return [
		'name' => $faker->name,
		'id_number' => $faker->number,
		'address' => $faker->address,
	    'phones' => $faker->e164PhoneNumber,
    ];
});
