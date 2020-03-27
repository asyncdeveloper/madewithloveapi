<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="Product"))
 * @SWG\Property(type="string", property="id"),
 * @SWG\Property(type="string", property="name"),
 * @SWG\Property(type="number", property="price"),
 */
class Product extends Model
{
    //
}
