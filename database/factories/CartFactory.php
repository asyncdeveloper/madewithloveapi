<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

    use App\Models\Cart;
    use App\User;
    use Faker\Generator as Faker;

    $factory->define(Cart::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'removed_products' => NULL
    ];
});
