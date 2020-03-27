<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DataResource;
use App\Models\Cart;

class DataController extends Controller
{

    /**
     * @SWG\Get(
     *   tags={"Data"},
     *   path="/data",
     *   summary="Display removed products in cart",
     *   @SWG\Response(response=200, description="Successful")
     * )
     */
    public function removedProducts()
    {
        return DataResource::collection(Cart::where('removed_products', '<>', NULL)->get());
    }
}
