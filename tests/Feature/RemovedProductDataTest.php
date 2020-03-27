<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RemovedProductDataTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test void
     */
    public function userCanViewRemovedProductsData()
    {
        $cart = factory(Cart::class)->create([
            'user_id' => NULL
        ]);

        $cartProducts = factory(CartProduct::class, 9)->create([
            'cart_id' => $cart->id
        ]);

        //Remove 2 random cartproducts from cart
        $randomCartProducts = $cartProducts->random(2);
        $firstRemovedCartProduct = $randomCartProducts->get(0)->load('product');
        $secondRemovedCartProduct = $randomCartProducts->get(1)->load('product');

        foreach ($randomCartProducts as $cartProduct) {
            $this->delete(
                route('carts.products.destroy', [
                    "cart" => $cartProduct->cart_id,
                    "product" => $cartProduct->product_id
                ])
            );
        }

        $response = $this->get(route('data'));

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    [
                        'cartId' => $cart->id,
                        'userId' => NULL,
                        'removedProducts' => [
                            $firstRemovedCartProduct->product->id => [
                               'id' => $firstRemovedCartProduct->product->id ,
                               'name' => $firstRemovedCartProduct->product->name,
                               'price' => $firstRemovedCartProduct->product->price
                            ],
                            $secondRemovedCartProduct->product->id => [
                                'id' => $secondRemovedCartProduct->product->id ,
                                'name' => $secondRemovedCartProduct->product->name,
                                'price' => $secondRemovedCartProduct->product->price
                            ]
                        ]
                    ],
                ]
            ]);
    }
}
