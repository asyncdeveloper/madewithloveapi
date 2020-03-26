<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([ 'productId', 'quantity' ]);

        if (Auth::guard('api')->check()) {
            $user = auth('api')->user();
        }

        $validator = Validator::make($data, [
            'productId' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|numeric|min:1|max:20|required_with:productId'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $cart = Cart::create([ 'user_id' => isset($user) ? $user->id : NULL ]);

        if(isset($data['productId'])) {
            $cartItem = CartProduct::create([
                'cart_id' => $cart->id,
                'product_id' => $data['productId'],
                'quantity' => $data['quantity']
            ]);
            $cart->items()->save($cartItem);
            $message = 'Cart created successfully with product';
        }

        return response()->json([
            'message' => $message ?? 'Cart created successfully with no product',
            'cartId' => $cart->id,
        ], 201);
    }

}
