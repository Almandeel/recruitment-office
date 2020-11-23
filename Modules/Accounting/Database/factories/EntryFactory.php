<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Modules\Accounting\Models\{Entry, Account};
use Faker\Generator as Faker;

$factory->define(Entry::class, function (Faker $faker) {
    return [
        'amount' => $faker->randomFloat($nbMaxDecimals = null, $min = 1000, $max = 10000), 
        'details' => $faker->sentence(10), 
        'entry_date' => $entry_date = $faker->date, 
        'type' => Entry::TYPES[array_rand(Entry::TYPES)], 
        'entry_id' => null, 
        'year_id' => date('Ym', strtotime($entry_date)),
    ];
});

$factory->afterMaking(Entry::class, function ($entry, $faker) {
    // ...
});

$factory->afterCreating(Entry::class, function ($entry, $faker) {
    $debts = random_int(1, 5);
    $credits = random_int(1, 5);
    for ($i=1; $i < $debts; $i++) { 
        $amount = $entry->amount / $debts;
        $side = Entry::SIDE_DEBTS;
        $account = random_int(0, 1) ? factory(Account::class)->create() : Account::inRandomOrder()->first();
        $entry->accounts()->attach($account->id, [
            'amount' => $amount,
            'side' => $side,
        ]);
    }
    for ($i=1; $i < $credits; $i++) { 
        $amount = $entry->amount / $credits;
        $side = Entry::SIDE_CREDITS;
        
        $account = random_int(0, 1) ? factory(Account::class)->create() : Account::inRandomOrder()->first();
        $entry->accounts()->attach($account->id, [
            'amount' => $amount,
            'side' => $side,
        ]);
    }
});
