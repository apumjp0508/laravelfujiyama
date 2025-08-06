<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SearchService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Mockery;

class SearchServiceTest extends TestCase
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

    public function test_search_products_returns_matching_products()
    {
        // Arrange
        $keyword = 'テスト';
        $product1 = new Product(['id' => 1, 'name' => 'テスト商品1']);
        $product1->id = 1;
        $product2 = new Product(['id' => 2, 'name' => 'テスト商品2']);
        $product2->id = 2;
        $matchingProducts = new EloquentCollection([$product1, $product2]);

        $this->productRepository
            ->shouldReceive('searchByName')
            ->with($keyword)
            ->once()
            ->andReturn($matchingProducts);

        // Act
        $result = $this->searchService->searchProducts($keyword);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($matchingProducts, $result['products']);
        $this->assertEquals($keyword, $result['keyword']);
    }

    public function test_search_products_returns_null_for_null_keyword()
    {
        // Arrange
        $keyword = null;

        // Act
        $result = $this->searchService->searchProducts($keyword);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertNull($result['products']);
        $this->assertNull($result['keyword']);
    }

    public function test_search_products_returns_empty_collection_for_no_matches()
    {
        // Arrange
        $keyword = '存在しない商品';
        $emptyCollection = new EloquentCollection([]);

        $this->productRepository
            ->shouldReceive('searchByName')
            ->with($keyword)
            ->once()
            ->andReturn($emptyCollection);

        // Act
        $result = $this->searchService->searchProducts($keyword);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($emptyCollection, $result['products']);
        $this->assertEquals($keyword, $result['keyword']);
    }

    public function test_search_products_handles_empty_string_keyword()
    {
        // Arrange
        $keyword = '';
        $product1 = new Product(['id' => 1, 'name' => '商品1']);
        $product1->id = 1;
        $product2 = new Product(['id' => 2, 'name' => '商品2']);
        $product2->id = 2;
        $matchingProducts = new EloquentCollection([$product1, $product2]);

        $this->productRepository
            ->shouldReceive('searchByName')
            ->with($keyword)
            ->once()
            ->andReturn($matchingProducts);

        // Act
        $result = $this->searchService->searchProducts($keyword);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($matchingProducts, $result['products']);
        $this->assertEquals($keyword, $result['keyword']);
    }

    public function test_search_products_handles_special_characters_in_keyword()
    {
        // Arrange
        $keyword = 'テスト%商品_セット';
        $product = new Product(['id' => 1, 'name' => 'テスト%商品_セット']);
        $product->id = 1;
        $matchingProducts = new EloquentCollection([$product]);

        $this->productRepository
            ->shouldReceive('searchByName')
            ->with($keyword)
            ->once()
            ->andReturn($matchingProducts);

        // Act
        $result = $this->searchService->searchProducts($keyword);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($matchingProducts, $result['products']);
        $this->assertEquals($keyword, $result['keyword']);
    }

    public function test_search_products_handles_database_error()
    {
        // Arrange
        $keyword = 'テスト';

        $this->productRepository
            ->shouldReceive('searchByName')
            ->with($keyword)
            ->once()
            ->andThrow(new \Illuminate\Database\QueryException('test', [], new \Exception('Database error')));

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('商品検索に失敗しました。データベースエラーが発生しました。');
        $this->expectExceptionCode(500);
        
        $this->searchService->searchProducts($keyword);
    }

    public function test_search_products_handles_general_exception()
    {
        // Arrange
        $keyword = 'テスト';

        $this->productRepository
            ->shouldReceive('searchByName')
            ->with($keyword)
            ->once()
            ->andThrow(new \Exception('General error'));

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('商品検索に失敗しました。');
        $this->expectExceptionCode(500);
        
        $this->searchService->searchProducts($keyword);
    }
}