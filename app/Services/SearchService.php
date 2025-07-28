<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\ErrorHandlingTrait;

class SearchService
{
    use ErrorHandlingTrait;

    public function searchProducts($keyword)
    {
        return $this->executeWithErrorHandling(
            function() use ($keyword) {
                if ($keyword !== null) {
                    $products = Product::where('name', 'like', "%{$keyword}%")->get();
                } else {
                    $products = null;
                }

                return [
                    'success' => true,
                    'products' => $products,
                    'keyword' => $keyword
                ];
            },
            'product_search',
            ['keyword' => $keyword]
        );
    }
} 