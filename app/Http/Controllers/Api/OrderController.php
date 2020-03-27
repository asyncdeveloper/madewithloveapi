<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

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
     *    @SWG\Schema(ref="#/definitions/OrderRequest"),
     *  ),
     *  @SWG\Response(response=201, description="Product added to cartsuccessfully"),
     *  @SWG\Response(response=400, description="Invalid body"),
     *  @SWG\Response(response=404, description="Cart not found")
     * )
     */
    public function store(OrderRequest $request)
    {
        $data = $request->validated();
        $userId = NULL;

        if (Auth::guard('api')->check()) {
            $user = auth('api')->user();
            $userId = $user->id;
        }

        $cart = Cart::find($data['cartId']);
        if($cart->user_id !== $userId) {
            return response()->json([ 'message' => 'This action is unauthorized.'], 401);
        }

        $cartProducts = CartProduct::with('product')
            ->where('cart_id', $data['cartId'])
            ->get();

        $totalCost = $cartProducts->sum(function ($cartProduct) {
            return $cartProduct->quantity * $cartProduct->product->price;
        });

        $products = $cartProducts->pluck('product')->map->only([ 'id', 'name', 'price' ]);

        Order::create([
            'user_id' => $userId,
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
