<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="CartProduct"))
 * @SWG\Property(type="string", property="id"),
 * @SWG\Property(type="string", property="user_id"),
 * @SWG\Property(type="string", property="product_id"),
 * @SWG\Property(type="string",property="integer")
 */
class CartProduct extends Model
{

    protected $fillable = [
        'cart_id', 'product_id', 'quantity'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
