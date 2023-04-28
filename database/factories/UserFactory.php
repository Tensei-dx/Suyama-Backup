<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

// $factory->define(User::class, function (Faker $faker) {
//     return [
//         'name' => $faker->name,
//         'email' => $faker->unique()->safeEmail,
//         'email_verified_at' => now(),
//         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
//         'remember_token' => Str::random(10),
//     ];
// });

$factory->define(User::class, function (Faker $faker) {
    return [
        'LAST_NAME' => $faker->lastName,
        'FIRST_NAME' => $faker->firstName,
        'USERNAME' => $faker->unique()->userName,
        'EMAIL' => $faker->unique()->safeEmail,
        'CONTACT_NUMBER' => $faker->phoneNumber,
        'ALLOW_ALERT_NOTIFICATION' => null,
        'USER_TYPE' => 2,
        'REG_FLAG' => 1,
        'USER_LOGO' => null,
        'PASSWORD' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'REMEMBER_TOKEN' => Str::random(10),
    ];
});
