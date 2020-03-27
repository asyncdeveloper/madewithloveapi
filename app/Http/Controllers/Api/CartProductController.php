<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartProductResource;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;


class CartProductController extends Controller
{

    public function index(Cart $cart)
    {
        $cart->load('products');
        return CartProductResource::collection($cart->products);
    }

    public function update(CartRequest $request, Cart $cart, Product $product)
    {
        $data = $request->validated();

        $cart->products()->where('product_id', $product->id)->update([
            'quantity' => $data['quantity']
        ]);

        return response()->noContent();
    }

    public function store(CartRequest $request, Cart $cart)
    {
        $data = $request->validated();

        $cartProduct = CartProduct::where([
            'cart_id' => $cart->id,
            'product_id' => $data['productId']
        ])->first();

        if(! is_null($cartProduct)) {
            $cartProduct->update([
                'quantity' => $data['quantity']
            ]);
        }else {
            CartProduct::create([
                'cart_id' => $cart->id,
                'product_id' => $data['productId'],
                'quantity' => $data['quantity']
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart successfully',
        ], 201);
    }

    public function destroy(Cart $cart, Product $product)
    {
        //Keep track of removed product
        $productId = $product->id;
        $removedProducts = json_encode([ $product->id => $product->name ]);

        if(! is_null($cart->removed_products)) {
            $removedProducts = json_decode($cart->removed_products);
            $removedProducts->$productId= $product->name;
            $removedProducts = json_encode($removedProducts);
        }

        $cart->products()->where('product_id', $productId)->delete();

        $cart->update([ 'removed_products' => $removedProducts ]);

        return response()->noContent();
    }
}
