<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\Modules\Product\Entities\Product::class, function (Faker $faker) {
    return [
        'product_id' => $faker->unique()->randomDigitNotNull,
        'image' => 'https://picsum.photos/200/300',
        'name' => $faker->sentence(2),
        'categories' => implode(',',$faker->words(4))
    ];
});
