<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    /**
     * @SWG\Post(
     *  tags={"Order"},
     *  path="/orders",
     *  summary="Checkout from cart",
     *  @SWG\Parameter(
     *    name="body",
     *    in="body",
     *    required=true,
     *    @SWG\Schema(ref="#/definitions/Order"),
     *  ),
     *  @SWG\Response(response=201, description="Product added to cartsuccessfully"),
     *  @SWG\Response(response=400, description="Invalid body"),
     *  @SWG\Response(response=404, description="Cart not found")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->only([ 'cartId', 'name', 'address' ]);

        if (Auth::guard('api')->check()) {
            $user = auth('api')->user();
        }

        $validator = Validator::make($data, [
            'cartId' => 'required|exists:carts,id',
            'name' => 'required|string|min:5|max:191',
            'address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $cartProducts = CartProduct::with('product')
            ->where('cart_id', $data['cartId'])
            ->get();

        $totalCost = $cartProducts->sum(function ($cartProduct) {
            return $cartProduct->quantity * $cartProduct->product->price;
        });

        $products = $cartProducts->pluck('product')->map->only([ 'id', 'name', 'price' ]);

        Order::create([
            'user_id' => isset($user) ? $user->id : NULL,
            'cart_id' => $data['cartId'],
            'name' => $data['name'],
            'address' => $data['address'],
            'total_cost' => $totalCost,
            'products' => json_encode($products)
        ]);

        return response()->json([
            'message' => 'Order created successfully'
        ], 201);
    }

}
