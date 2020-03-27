<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    /**
     * @SWG\Post(
     *   tags={"Cart"},
     *   path="/carts",
     *   summary="Create a cart",
     *  @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/CartRequest"),
     *   ),
     *   @SWG\Response(response=201, description="Cart created successfully"),
     *   @SWG\Response(response=422, description="Invalid body supplied")
     * )
     */
    public function store(CartRequest $request)
    {
        $data = $request->validated();

        if (Auth::guard('api')->check()) {
            $user = auth('api')->user();
        }

        $cart = Cart::create([ 'user_id' => isset($user) ? $user->id : NULL ]);

        if(isset($data['productId'])) {
            CartProduct::create([
                'cart_id' => $cart->id,
                'product_id' => $data['productId'],
                'quantity' => $data['quantity']
            ]);
            $message = 'Cart created successfully with product';
        }

        return response()->json([
            'message' => $message ?? 'Cart created successfully with no product',
            'cartId' => $cart->id,
        ], 201);
    }

}
