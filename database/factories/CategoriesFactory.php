<?php

use Faker\Generator as Faker;

$factory->define(App\Categories::class, function (Faker $faker) {
    $faker = \Faker\Factory::create('ru_RU');
    return [
        'name' => $faker->sentence(3),
        'description' => $faker->text(100)
    ];
});
