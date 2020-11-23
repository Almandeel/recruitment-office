<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Modules\Accounting\Models\Account;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'name' => $faker->name, 
        'main_account' => Account::inRandomOrder()->first()->id,
    ];
});
