<?php

use Faker\Generator as Faker;

$factory->define(App\Item::class, function (Faker $faker) {
    $title = $faker->text(100);
    $parent_category_id = factory(App\Category::class)->create()->id;
    return [
        'slug' => str_slug($title),
        'title' => $title,
        'body' => $faker->paragraph,
        'parent_category_id' => $parent_category_id,   
        'child_category_id' => function () use ($parent_category_id) {
            return factory(App\Category::class)->create([
                'parent_id' => $parent_category_id
            ])->id;
        }  
    ];
});
