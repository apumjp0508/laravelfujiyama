<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Traits\ErrorHandlingTrait;

class SearchService
{
    use ErrorHandlingTrait;

    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function searchProducts($keyword)
    {
        return $this->executeWithErrorHandling(
            function() use ($keyword) {
                if ($keyword !== null) {
                    $products = $this->productRepository->searchByName($keyword);
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