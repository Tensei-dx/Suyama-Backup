<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Book_Room;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Book_Room::class, function (Faker $faker) {
    return [
        'CHECK_IN_TIME' => today(),
        'CHECK_OUT_TIME' => Carbon::today()->addDays(5),
        'PIN' => $faker->randomNumber(6),
        'ROOM_MESSAGE' => null,
        'ACTIVE' => 1
    ];
});
