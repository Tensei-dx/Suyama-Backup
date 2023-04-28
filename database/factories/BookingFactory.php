<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BookPMS;
use Faker\Generator as Faker;

$factory->define(BookPMS::class, function (Faker $faker) {
    return [
        'CONTACT_NUMBER' => $faker->phoneNumber,
        'EMAIL' => $faker->safeEmail,
        'FIRST_NAME' => $faker->firstName,
        'LAST_NAME' => $faker->lastName,
        'ADDRESS' => $faker->address
    ];
});
