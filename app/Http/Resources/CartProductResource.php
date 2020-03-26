<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        $product = Product::find($this->product_id);

        return [
            'id' => $this->product_id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $this->quantity,
        ];

    }
}
