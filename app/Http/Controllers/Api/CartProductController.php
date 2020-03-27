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

    /**
     * @SWG\Get(
     *   tags={"CartProduct"},
     *   path="/carts/{id}/products",
     *   summary="Display products in a cart",
     *   @SWG\Response(response=200, description="Successful")
     * )
     */
    public function index(Cart $cart)
    {
        $cart->load('products');
        return CartProductResource::collection($cart->products);
    }

    /**
     * @SWG\Patch(
     *  tags={"CartProduct"},
     *  path="/carts/{id}/products/{id}",
     *  summary="Update cart product quantity",
     *  @SWG\Parameter(
     *    name="body",
     *    in="body",
     *    required=true,
     *    @SWG\Schema(ref="#/definitions/CartRequest"),
     *  ),
     *  @SWG\Response(response=204, description="Cart updated successfully"),
     *  @SWG\Response(response=404, description="Cart/Product not found")
     * )
     */
    public function update(CartRequest $request, Cart $cart, Product $product)
    {
        $data = $request->validated();

        $cart->products()->where('product_id', $product->id)->update([
            'quantity' => $data['quantity']
        ]);

        return response()->noContent();
    }

    /**
     * @SWG\Post(
     *  tags={"CartProduct"},
     *  path="/carts/{id}/products",
     *  summary="Add product to cart",
     *  @SWG\Parameter(
     *    name="body",
     *    in="body",
     *    required=true,
     *    @SWG\Schema(ref="#/definitions/CartRequest"),
     *  ),
     *  @SWG\Response(response=201, description="Product added to cartsuccessfully"),
     *  @SWG\Response(response=404, description="Cart/Product not found")
     * )
     */
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

    /**
     * @SWG\Delete(
     *  tags={"CartProduct"},
     *  path="/carts/{id}/products/{id}",
     *  summary="Remove product from cart",
     *  @SWG\Response(response=204, description="Product removed from cart successfully"),
     *  @SWG\Response(response=404, description="Cart/Product not found")
     * )
     */
    public function destroy(Cart $cart, Product $product)
    {
        $userId = auth('api')->user()->id ?? NULL;
        if($cart->user_id !== $userId ) {
            return response()->json([ 'message' => 'This action is unauthorized.'], 401);
        }

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
