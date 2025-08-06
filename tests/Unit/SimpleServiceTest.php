<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SearchService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Mockery;

class SimpleServiceTest extends TestCase
{
    protected $searchService;
    protected $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->productRepository = Mockery::mock(ProductRepositoryInterface::class);
        
        $this->searchService = new SearchService($this->productRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_search_service_returns_null_for_null_keyword()
    {
        // Act
        $result = $this->searchService->searchProducts(null);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertNull($result['products']);
        $this->assertNull($result['keyword']);
    }
}