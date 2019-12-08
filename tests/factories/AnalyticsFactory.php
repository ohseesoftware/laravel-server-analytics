<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

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

$factory->define(Analytics::class, function (Faker $faker) {
    return [
        'path'        => $faker->slug,
        'method'      => $faker->randomElement(['GET', 'POST', 'PUT', 'PATCH', 'DELETE']),
        'status_code' => 200,
        'user_agent'  => $faker->userAgent,
        'ip_address'  => $faker->ipv4,
        'duration_ms' => $faker->numberBetween(5, 250)
    ];
});
