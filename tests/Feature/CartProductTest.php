<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CartProductTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function userCanAddProductsToCart()
    {
        $products = factory(Product::class, 3)->create();
        $cart = factory(Cart::class)->create([
            'user_id' => NULL
        ]);
        $cartProductsData =   [
            "productId" => $products->first()->id,
            "quantity" => 2
        ];

        $response = $this->post(
            route('carts.products.store', [ 'cart' => $cart->id ]),
            $cartProductsData
        );

        $response->assertSuccessful()
            ->assertExactJson([
                'message' => 'Product added to cart successfully'
            ]);

        $this->assertDatabaseHas('cart_products', [
            'cart_id' => $cart->id,
            'product_id' => $cartProductsData['productId'],
            'quantity' => $cartProductsData['quantity']
        ]);
    }

    /**
     * @test
     */
    public function userCanRemoveProductFromCart()
    {
        $cart = factory(Cart::class)->create([
            'user_id' => NULL
        ]);

        $cartProducts = factory(CartProduct::class, 2)->create([
            'cart_id' => $cart->id
        ]);

        $response = $this->delete(
            route('carts.products.destroy', [
                "cart" => $cart,
                "product" => $cartProducts->first()->id
            ])
        );

        $response->assertSuccessful();

        $this->assertDatabaseMissing('cart_products', [
            'product_id' => $cartProducts->first()->id,
            'cart_id' => $cart->id
        ]);
    }

    /**
     * @test
     */
    public function userCanViewProductsInCart()
    {
        $cart = factory(Cart::class)->create([
            'user_id' => NULL
        ]);

        factory(CartProduct::class, 5)->create([
            'cart_id' => $cart->id
        ]);


        $response = $this->get(route('carts.products.index', [ 'cart' => $cart->id ]));

        $response->assertOk()
            ->assertJsonStructure([ 'data' => [ [ 'id', 'name', 'price', 'quantity' ] ] ]);
    }

    /**
     * @test
     */
    public function userCanUpdateProductInCart()
    {
        $cart = factory(Cart::class)->create([
            'user_id' => NULL
        ]);

        $products = factory(CartProduct::class, 3)->create([
            'cart_id' => $cart->id,
            'quantity' => 2
        ]);

        $response = $this->patch(
            route('carts.products.update', [
                'cart' => $cart->id,
                'product' => $products->first()->id
            ]),
            [ "quantity" => 8 ]
        );

        $response->assertNoContent();

        $this->assertDatabaseHas('cart_products', [
            'quantity' => 8,
            'cart_id' => $cart->id,
            'product_id' => $products->first()->id
        ]);
    }

}
