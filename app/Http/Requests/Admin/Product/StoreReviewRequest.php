<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'content' => 'required|string',
            'product_id' => 'required|integer|exists:products,id',
            'score' => 'nullable|integer|min:1|max:5',
        ];
    }
} 