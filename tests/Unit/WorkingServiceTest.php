<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SearchService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Mockery;

class WorkingServiceTest extends TestCase
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

    public function test_search_service_handles_null_keyword()
    {
        // Act - This test doesn't need database mocking because null keyword returns early
        $result = $this->searchService->searchProducts(null);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertNull($result['products']);
        $this->assertNull($result['keyword']);
    }

    public function test_search_service_handles_empty_string_keyword()
    {
        // Mock the repository to avoid database calls
        $this->productRepository
            ->shouldReceive('searchByName')
            ->once()
            ->with('')
            ->andReturn(new EloquentCollection([])); // Return empty Eloquent collection for empty search

        // Act
        $result = $this->searchService->searchProducts('');

        // Assert
        $this->assertTrue($result['success']);
        $this->assertNotNull($result['products']); // Should return empty collection, not null
        $this->assertEquals('', $result['keyword']);
        $this->assertCount(0, $result['products']); // Empty collection
    }
}