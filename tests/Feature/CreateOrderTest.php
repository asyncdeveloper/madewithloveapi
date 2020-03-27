<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @test
     */
    public function userCanMakeOrder()
    {
        $cart = factory(Cart::class)->create([
            'user_id' => NULL
        ]);

        $cartProducts = factory(CartProduct::class, 2)->create([
            'cart_id' => $cart->id
        ]);

        $orderData = [
            'cartId' => $cart->id,
            'name' => 'Oluwaseyi Adeogun',
            'address' => 'Lagos, Nigeria'
        ];

        $response = $this->post(route('orders.store'), $orderData);

        $response->assertSuccessful()
            ->assertJsonFragment([ 'message' => 'Order created successfully' ]);

        $this->assertDatabaseHas('orders', [
            'cart_id' => $cart->id,
            'name' => $orderData['name'],
            'address' => $orderData['address']
        ] );
    }
}
