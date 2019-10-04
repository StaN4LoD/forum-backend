<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('secret'), // secret
        'role' => 'user'
    ];
});

$factory->defineAs(App\User::class, 'admin', function(){
    return [
        'name' => 'admin',
        'email' => 'admin@mail.ru',
        'password' => Hash::make('secret'),
        'role' => 'admin'
    ];
});
