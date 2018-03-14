<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    $firstName = $faker->firstName;
    $lastName = $faker->lastName;

    return [
		'username'       => strtolower($firstName) . "." . strtolower($lastName),
		'name'           => $firstName,
		'lastname'       => $lastName,
		'email'          => strtolower($firstName) . "." . strtolower($lastName) . "@example.com",
		'password'       => $password ?: $password = bcrypt('secret'),
		'role'           => \App\User::ROLES[$faker->numberBetween(0,2)],
		'remember_token' => str_random(10),
    ];
});
