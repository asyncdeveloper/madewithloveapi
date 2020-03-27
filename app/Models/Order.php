<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="Order"))
 * @SWG\Property(type="string", property="id"),
 * @SWG\Property(type="string", property="user_id"),
 * @SWG\Property(type="string", property="cart_id"),
 * @SWG\Property(type="string", property="name"),
 * @SWG\Property(type="string", property="address"),
 * @SWG\Property(type="string", property="products"),
 * @SWG\Property(type="number",property="total_cost")
 */
class Order extends Model
{
    protected $fillable = [
        'user_id', 'cart_id', 'name', 'address', 'products' , 'total_cost'
    ];
}
