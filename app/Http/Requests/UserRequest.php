<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
