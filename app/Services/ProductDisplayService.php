<?php

namespace App\Services;

use App\Models\BeforeBuySelectedProductSet;
use App\Models\Product;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class ProductDisplayService
{
    use ErrorHandlingTrait;

    public function getAllProducts()
    {
        return $this->executeWithErrorHandling(
            function() {
                $products = Product::all();
                $categories = $products->pluck('category')->toArray();

                return [
                    'success' => true,
                    'products' => $products,
                    'categories' => $categories
                ];
            },
            'products_retrieval'
        );
    }

    public function getProductDetails($productId, $selectedProductSets = null, $userId = null, $setId = null)
    {
        return $this->executeWithErrorHandling(
            function() use ($productId, $selectedProductSets, $userId, $setId) {
                $product = Product::findOrFail($productId);
                $products = Product::all();
                $categories = $products->pluck('category')->toArray();
                $keywords = Product::where('category', 'like', "%セット%")->get();
                $reviews = $product->reviews()->get();

                // For set products, get selected product sets from database if user is logged in
                $finalSelectedProductSets = $selectedProductSets;
                if ($product->productType === 'set' && Auth::check()) {
                    $userSelectedProductSets = BeforeBuySelectedProductSet::where('product_id', $productId)
                        ->where('user_id', Auth::id())
                        ->pluck('product_set_id')
                        ->toArray();
                    
                    // Use database product sets if available, otherwise fall back to query parameter
                    if (!empty($userSelectedProductSets)) {
                        $finalSelectedProductSets = $userSelectedProductSets;
                    }
                }

                return [
                    'success' => true,
                    'product' => $product,
                    'products' => $products,
                    'categories' => $categories,
                    'keywords' => $keywords,
                    'reviews' => $reviews,
                    'selectedProductSets' => $finalSelectedProductSets,
                    'userId' => $userId,
                    'setId' => $setId
                ];
            },
            'product_details_retrieval',
            [
                'product_id' => $productId,
                'selected_product_sets' => $selectedProductSets,
                'user_id' => $userId
            ]
        );
    }

    public function getProductsByCategory($category)
    {
        return $this->executeWithErrorHandling(
            function() use ($category) {
                $products = Product::where('category', $category)->get();

                return [
                    'success' => true,
                    'products' => $products,
                    'category' => $category
                ];
            },
            'products_by_category_retrieval',
            ['category' => $category]
        );
    }
} 