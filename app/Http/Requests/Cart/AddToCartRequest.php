<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|integer',
            'name' => 'required|string',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'shipping_fee' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric',
            'img' => 'nullable|string',
            'setNum' => 'nullable|integer',
            'productType' => 'nullable|string',
            'selectedBadges' => 'nullable',
        ];
    }
}







