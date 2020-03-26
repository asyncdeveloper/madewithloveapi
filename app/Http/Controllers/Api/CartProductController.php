<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartProductResource;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartProductController extends Controller
{

    public function index(Cart $cart)
    {
        $cart->load('products');
        return CartProductResource::collection($cart->products);
    }

    public function update(Request $request, Cart $cart, Product $product)
    {
        $data = $request->only([ 'quantity' ]);

        $validator = Validator::make($data, [
            'quantity' => 'required|numeric|min:1|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $cart->products()->where('product_id', $product->id)->update([
            'quantity' => $data['quantity']
        ]);

        return response()->noContent();
    }

    public function store(Request $request, Cart $cart)
    {
        $data = $request->only(['productId', 'quantity']);

        $validator = Validator::make($data, [
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        CartProduct::create([
            'cart_id' => $cart->id,
            'product_id' => $data['productId'],
            'quantity' => $data['quantity']
        ]);

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
