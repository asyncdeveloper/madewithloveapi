<?php

namespace Tests\Feature;

use App\Models\Product;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CartTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function loggedInUserCanCreateCartWithItems()
    {
        $user = factory(User::class)->create();
        $products = factory(Product::class, 5)->create();

        Passport::actingAs($user);

        $cartData =  [
            'user_id' => $user->id,
            'productId' => $products->first()->id,
            'quantity' => 4
        ];
        $response = $this->post(route('carts.store'), $cartData);

        $response->assertSuccessful()
            ->assertJsonStructure([ 'cartId', 'message'])
            ->assertJsonFragment([
                'message' => 'Cart created successfully with items'
            ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $cartData['user_id'],
        ])->assertDatabaseHas('cart_items', [
            'product_id' => $cartData['productId'],
            'quantity' => $cartData['quantity']
        ]);
    }

    /**
     * @test
     */
    public function userCanCreateCartWithNoItems()
    {
        $response = $this->post(route('carts.store'));

        $response->assertSuccessful()
            ->assertJsonStructure([ 'cartId', 'message'])
            ->assertJsonFragment([
                'message' => 'Cart created successfully with no items'
            ]);
    }


}
