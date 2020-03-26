<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

    use App\Models\Cart;
    use App\Models\CartProduct;
    use App\Models\Product;
    use Faker\Generator as Faker;

    $factory->define(CartProduct::class, function (Faker $faker) {
    return [
        'cart_id' => factory(Cart::class)->create()->id,
        'product_id' => factory(Product::class)->create()->id,
        'quantity' => $faker->numberBetween(1,20)
    ];
});
