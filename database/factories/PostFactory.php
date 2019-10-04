<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $faker = \Faker\Factory::create('ru_RU');
    return [
        'category_id' => rand(1,4),
        'user_id' => rand(1,6),
        'title' => $faker->sentence(3),
        'description' => $faker->text(100),
        'content' => $faker->text(250)
    ];
});
