<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    $faker = \Faker\Factory::create('ru_RU');
    return [
        'user_id' => rand(1,6),
        'post_id' => rand(1,10),
        'content' => $faker->text(120)
    ];
});
