<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductsResourceTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function userCanViewAllProducts()
    {
        factory(Product::class, 10)->create();

        $response = $this->get(route('products.index'));

        $response->assertOk()
            ->assertJsonStructure([ 'data' ]);
    }

    /**
     * @test
     */
    public function userCanViewOneProduct()
    {
        factory(Product::class, 10)->create();

        $product = factory(Product::class)->create();

        $response = $this->get(route('products.show', $product->id));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [ 'id', 'name', 'price' ]
            ]);
    }
}
