<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    $name = $faker->sentence(3);
    return [
        'slug' => str_slug($name),
        'name' => $name
    ];
});
