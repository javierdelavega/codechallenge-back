<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartProductRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // The id of the product should exist in the DB
            'id' => 'required|exists:products,id|integer',
            // Quantity of the products too add (min 1)
            'quantity' => 'required|integer|min:1',
        ];
    }
}
