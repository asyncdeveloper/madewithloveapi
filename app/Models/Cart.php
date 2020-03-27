<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="Cart"))
 * @SWG\Property(type="string", property="id"),
 * @SWG\Property(type="string", property="user_id"),
 * @SWG\Property(type="string",property="removed_products")
 */
class Cart extends Model
{
    protected $fillable = [
        'user_id', 'removed_products'
    ];

    public function products()
    {
        return $this->hasMany(CartProduct::class);
    }
}
