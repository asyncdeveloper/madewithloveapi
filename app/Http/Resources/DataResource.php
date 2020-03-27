<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        $removedProducts = collect(json_decode($this->removed_products));
        $products = $removedProducts->map(function ($value, $key) {
            $product = Product::find($key);
            if(is_null($product)) {
                return $value;
            }

            return new ProductResource($product);
        });

        return [
            'cartId' => $this->id,
            'userId' => $this->user_id,
            'removedProducts' => $products
        ];
    }
}
