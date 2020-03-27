<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('api')->only(['index', 'show' ]);
    }

    /**
     * @SWG\Get(
     *   tags={"Product"},
     *   path="/products",
     *   summary="Display all products",
     *   @SWG\Response(response=200, description="Successful")
     * )
     */
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    /**
     * @SWG\Get(
     *   tags={"Product"},
     *   path="/products/{id}",
     *   summary="Show one product",
     *   @SWG\Response(response=200, description="Successful"),
     *   @SWG\Response(response=404, description="Product not found")
     * )
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }


}
