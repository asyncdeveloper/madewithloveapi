<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="CartRequest"))
 * @SWG\Property(type="string", property="productId"),
 * @SWG\Property(type="number", property="quantity"),
 */
class CartRequest extends FormRequest
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
        $routeName = $this->route()->getName();
        switch ($routeName) {
            case 'carts.store':
                return $this->cartStoreRules();
                break;
            case 'carts.products.update':
                return $this->cartProductUpdateRules();
                break;
            case 'carts.products.store':
                return $this->cartProductStoreRules();
                break;
        }

        return  [];
    }

    private function cartStoreRules()
    {
        return [
            'productId' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|numeric|min:1|max:20|required_with:productId'
        ];
    }

    private function cartProductStoreRules()
    {
        return [
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1|max:20'
        ];
    }

    private function cartProductUpdateRules()
    {
        return [
            'quantity' => 'required|numeric|min:1|max:20'
        ];
    }
}
