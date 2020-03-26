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
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }


}
