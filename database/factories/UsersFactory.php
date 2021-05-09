<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Users as User;
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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$uE4NcYl3nUXUHyzRSV04Cu4ke8Tsm9XhSe6zMozmi.3bsT/KqCCfe', // password
        'remember_token' => Str::random(10),
        'menuroles' => 'user'
    ];
});

$factory->state(User::class, 'admin', function (Faker $faker) {
    return [
        'menuroles' => 'user,admin',
    ];
});
