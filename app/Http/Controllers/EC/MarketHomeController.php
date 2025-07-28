<?php

namespace App\Http\Controllers\EC;

use App\Http\Controllers\Controller;
use App\Services\ProductDisplayService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;

class MarketHomeController extends Controller
{
    use ErrorHandlingTrait;

    protected $productDisplayService;

    public function __construct(ProductDisplayService $productDisplayService)
    {
        $this->productDisplayService = $productDisplayService;
    }

    public function index()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $result = $this->productDisplayService->getAllProducts();
                
                return view('ec.MartIndex', [
                    'products' => $result['products'],
                    'categories' => $result['categories']
                ]);
            },
            'products_display'
        );
    }

    public function show(Request $request, $productId)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($request, $productId) {
                $selectedBadges = $request->query('selectedBadges');
                $userId = $request->query('userId');
                $setId = $request->query('setId');
                
                $result = $this->productDisplayService->getProductDetails(
                    $productId, 
                    $selectedBadges, 
                    $userId, 
                    $setId
                );
                
                return view('ec.show', [
                    'product' => $result['product'],
                    'products' => $result['products'],
                    'categories' => $result['categories'],
                    'keywords' => $result['keywords'],
                    'reviews' => $result['reviews'],
                    'selectedBadges' => $result['selectedBadges'],
                    'userId' => $result['userId'],
                    'setId' => $result['setId']
                ]);
            },
            'product_details_display',
            [
                'product_id' => $productId,
                'selected_badges' => $request->query('selectedBadges'),
                'user_id' => $request->query('userId')
            ]
        );
    }

    public function categorySearch($category)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($category) {
                $result = $this->productDisplayService->getProductsByCategory($category);
                
                return view('ec.categorySearch', [
                    'products' => $result['products'],
                    'category' => $result['category']
                ]);
            },
            'category_products_display',
            ['category' => $category]
        );
    }
}
