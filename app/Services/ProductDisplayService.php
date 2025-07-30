<?php

namespace App\Services;

use App\Models\BeforeBuySelectedBadge;
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

    public function getProductDetails($productId, $selectedBadges = null, $userId = null, $setId = null)
    {
        return $this->executeWithErrorHandling(
            function() use ($productId, $selectedBadges, $userId, $setId) {
                $product = Product::findOrFail($productId);
                $products = Product::all();
                $categories = $products->pluck('category')->toArray();
                $keywords = Product::where('category', 'like', "%セット%")->get();
                $reviews = $product->reviews()->get();

                // For set products, get selected badges from database if user is logged in
                $finalSelectedBadges = $selectedBadges;
                if ($product->productType === 'set' && Auth::check()) {
                    $userSelectedBadges = BeforeBuySelectedBadge::where('product_id', $productId)
                        ->where('user_id', Auth::id())
                        ->pluck('badge_id')
                        ->toArray();
                    
                    // Use database badges if available, otherwise fall back to query parameter
                    if (!empty($userSelectedBadges)) {
                        $finalSelectedBadges = $userSelectedBadges;
                    }
                }

                return [
                    'success' => true,
                    'product' => $product,
                    'products' => $products,
                    'categories' => $categories,
                    'keywords' => $keywords,
                    'reviews' => $reviews,
                    'selectedBadges' => $finalSelectedBadges,
                    'userId' => $userId,
                    'setId' => $setId
                ];
            },
            'product_details_retrieval',
            [
                'product_id' => $productId,
                'selected_badges' => $selectedBadges,
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