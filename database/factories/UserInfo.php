<?php

use Faker\Generator as Faker;

$factory->define(App\UsersInfo::class, function (Faker $faker) {
    return [
        'avatar' => $faker->image(),
        'full_name' => $faker->firstName,
        'phone' => $faker->phoneNumber,
        'about' => $faker->realText(),
        'job' => $faker->word,
        'location' => $faker->city
    ];
});
