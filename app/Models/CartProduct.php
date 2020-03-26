<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{

    protected $fillable = [
        'cart_id', 'product_id', 'quantity'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
