<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="UserRequest"))
 * @SWG\Property(type="string", property="name"),
 * @SWG\Property(type="string", property="email"),
 * @SWG\Property(type="string", property="password"),
 */
class UserRequest extends FormRequest
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

        if($routeName === 'login') {
            return [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ];
        }else if($routeName === 'register') {
            return [
                'name' => 'required|string|min:3|max:191',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:3|max:191'
            ];
        }

        return  [];
    }
}
