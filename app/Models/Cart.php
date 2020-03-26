<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
