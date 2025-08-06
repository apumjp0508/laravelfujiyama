<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\BeforeBuySelectedProductSetRepositoryInterface;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class ProductDisplayService
{
    use ErrorHandlingTrait;

    protected $productRepository;
    protected $beforeBuySelectedProductSetRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        BeforeBuySelectedProductSetRepositoryInterface $beforeBuySelectedProductSetRepository
    ) {
        $this->productRepository = $productRepository;
        $this->beforeBuySelectedProductSetRepository = $beforeBuySelectedProductSetRepository;
    }

    public function getAllProducts()
    {
        return $this->executeWithErrorHandling(
            function() {
                $products = $this->productRepository->all();
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
                $product = $this->productRepository->findById($productId);
                if (!$product) {
                    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
                }
                $products = $this->productRepository->all();
                $categories = $products->pluck('category')->toArray();
                $keywords = $this->productRepository->findByCategory('セット');
                $reviews = $product->reviews()->get();

                // For set products, get selected product sets from database if user is logged in
                $finalSelectedProductSets = $selectedProductSets;
                if ($product->productType === 'set' && Auth::check()) {
                    $userSelectedProductSets = $this->beforeBuySelectedProductSetRepository->getByProductAndUser($productId, Auth::id());
                    
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
                $products = $this->productRepository->findByCategory($category);

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