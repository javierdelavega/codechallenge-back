<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * email, password, name, address
     * 
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required',
            'name' => 'required',
            'address' => 'required',
        ];
    }
}
