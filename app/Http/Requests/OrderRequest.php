<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="OrderRequest"))
 * @SWG\Property(type="string", property="cartId"),
 * @SWG\Property(type="string", property="name"),
 * @SWG\Property(type="string", property="address"),
 */
class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cartId' => 'required|exists:carts,id',
            'name' => 'required|string|min:5|max:191',
            'address' => 'required|string'
        ];
    }
}
