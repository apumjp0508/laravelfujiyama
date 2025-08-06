<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CartService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Mockery;

class CartServiceTest extends TestCase
{
    protected $cartService;
    protected $userId;
    protected $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->productRepository = Mockery::mock(ProductRepositoryInterface::class);
        
        $this->cartService = new CartService($this->productRepository);
        $this->userId = 1;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_cart_view_data_returns_correct_structure()
    {
        // Arrange
        $mockProducts = new EloquentCollection([
            new Product(['id' => 1, 'name' => 'Test Product', 'price' => 1000, 'category' => 'セット']),
            new Product(['id' => 2, 'name' => 'Another Product', 'price' => 2000, 'category' => 'テスト']),
        ]);


        $mockKeywords = new EloquentCollection([
            (object)['id' => 1, 'name' => 'セット商品', 'category' => 'セット'],
        ]);


        // ProductRepositoryInterface のモック定義
        $this->productRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($mockProducts);

        $this->productRepository
            ->shouldReceive('findByCategory')
            ->with('セット')
            ->once()
            ->andReturn($mockKeywords);

        // カートモック
        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->andReturn(\Illuminate\Support\Collection::make([
                (object)[
                    'id' => 1,
                    'qty' => 2,
                    'price' => 1000,
                    'options' => (object)['shippingFee' => 500]
                ]
            ]));

        // Act
        $result = $this->cartService->getCartViewData($this->userId);

        // Assert
        $this->assertArrayHasKey('userId', $result);
        $this->assertArrayHasKey('cart', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('keywords', $result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertEquals($this->userId, $result['userId']);
        $this->assertEquals(3000, $result['total']); // (1000 + 500) * 2
    }


    public function test_add_to_cart_successfully_adds_item()
    {
        // Arrange
        $itemData = [
            'id' => 1,
            'name' => 'Test Product',
            'qty' => 1,
            'price' => 1000,
            'weight' => 100,
            'img' => 'test.jpg',
            'setNum' => 1,
            'productType' => 'single',
            'selectedProductSets' => [],
            'shipping_fee' => 500
        ];

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('add')
            ->with([
                'id' => 1,
                'name' => 'Test Product',
                'qty' => 1,
                'price' => 1000,
                'weight' => 100,
                'options' => [
                    'img' => 'test.jpg',
                    'setNum' => 1,
                    'productType' => 'single',
                    'selectedProductSets' => [],
                    'shippingFee' => 500,
                ]
            ])
            ->andReturn(true);

        Cart::shouldReceive('count')
            ->andReturn(1);

        // Act
        $result = $this->cartService->addToCart($this->userId, $itemData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('カートに追加しました！', $result['message']);
        $this->assertEquals(1, $result['cart_count']);
    }

    public function test_update_cart_item_updates_quantity()
    {
        // Arrange
        $productId = 1;
        $qty = 3;

        $product = new Product([
            'id' => $productId,
            'price' => 1000,
        ]);

        // ProductRepositoryInterfaceのモック設定
        $this->productRepository
            ->shouldReceive('findById')
            ->with($productId)
            ->once()
            ->andReturn($product);

        $cartItem = (object)[
            'rowId' => 'test-row-id',
            'id' => $productId,
            'qty' => 2,
            'price' => 1000,
            'options' => (object)['shippingFee' => 500]
        ];

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->andReturn(\Illuminate\Support\Collection::make([$cartItem]));

        Cart::shouldReceive('update')
            ->with('test-row-id', $qty)
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->cartService->updateCartItem($this->userId, $productId, $qty);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals(3000, $result['product_total']); // 1000 * 3
        $this->assertArrayHasKey('cart_total', $result);
    }


   public function test_update_cart_item_removes_when_qty_zero()
    {
        // Arrange
        $productId = 1;
        $qty = 0;

        $product = new Product([
            'id' => $productId,
            'price' => 1000,
        ]);

        // リポジトリのモック：findById
        $this->productRepository
            ->shouldReceive('findById')
            ->with($productId)
            ->once()
            ->andReturn($product);

        $cartItem = (object)[
            'rowId' => 'test-row-id',
            'id' => $productId,
            'qty' => 2,
            'price' => 1000,
        ];

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->andReturn(\Illuminate\Support\Collection::make([$cartItem]));

        Cart::shouldReceive('remove')
            ->with('test-row-id')
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->cartService->updateCartItem($this->userId, $productId, $qty);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals(0, $result['product_total']);
    }

    public function test_remove_cart_item_removes_successfully()
    {
        // Arrange
        $rowId = 'test-row-id';

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('remove')
            ->with($rowId)
            ->andReturn(true);

        // Act
        $result = $this->cartService->removeCartItem($this->userId, $rowId);

        // Assert
        $this->assertNull($result);
    }

    // Additional tests for productType='set' functionality
    public function test_add_to_cart_with_set_product_includes_selected_product_sets()
    {
        // Arrange
        $itemData = [
            'id' => 1,
            'name' => 'Set Product',
            'qty' => 1,
            'price' => 2000,
            'weight' => 200,
            'img' => 'set.jpg',
            'setNum' => 3,
            'productType' => 'set',
            'selectedProductSets' => [1, 2, 3], // Selected ProductSet IDs
            'shipping_fee' => 800
        ];

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('add')
            ->with([
                'id' => 1,
                'name' => 'Set Product',
                'qty' => 1,
                'price' => 2000,
                'weight' => 200,
                'options' => [
                    'img' => 'set.jpg',
                    'setNum' => 3,
                    'productType' => 'set',
                    'selectedProductSets' => [1, 2, 3],
                    'shippingFee' => 800,
                ]
            ])
            ->andReturn(true);

        Cart::shouldReceive('count')
            ->andReturn(1);

        // Act
        $result = $this->cartService->addToCart($this->userId, $itemData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('カートに追加しました！', $result['message']);
        $this->assertEquals(1, $result['cart_count']);
    }

    public function test_get_cart_view_data_with_set_products()
    {
        // Arrange
        $mockProducts = new EloquentCollection([
            new Product(['id' => 1, 'name' => 'Set Product', 'price' => 2000, 'category' => 'セット', 'productType' => 'set']),
            new Product(['id' => 2, 'name' => 'Single Product', 'price' => 1000, 'category' => 'テスト', 'productType' => 'single']),
        ]);

        $mockKeywords = new EloquentCollection([
            (object)['id' => 1, 'name' => 'セット商品', 'category' => 'セット'],
        ]);

        $this->productRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($mockProducts);

        $this->productRepository
            ->shouldReceive('findByCategory')
            ->with('セット')
            ->once()
            ->andReturn($mockKeywords);

        // Mock cart with set product
        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->andReturn(\Illuminate\Support\Collection::make([
                (object)[
                    'id' => 1,
                    'qty' => 1,
                    'price' => 2000,
                    'options' => (object)[
                        'shippingFee' => 800,
                        'productType' => 'set',
                        'selectedProductSets' => [1, 2, 3],
                        'setNum' => 3
                    ]
                ]
            ]));

        // Act
        $result = $this->cartService->getCartViewData($this->userId);

        // Assert
        $this->assertArrayHasKey('userId', $result);
        $this->assertArrayHasKey('cart', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals($this->userId, $result['userId']);
        $this->assertEquals(2800, $result['total']); // (2000 + 800) * 1

        // Verify cart contains set product information
        $cartItem = $result['cart']->first();
        $this->assertEquals('set', $cartItem->options->productType);
        $this->assertEquals([1, 2, 3], $cartItem->options->selectedProductSets);
        $this->assertEquals(3, $cartItem->options->setNum);
    }

    public function test_get_cart_view_data_calculates_total_correctly_for_mixed_products()
    {
        // Arrange
        $mockProducts = new EloquentCollection([
            new Product(['id' => 1, 'name' => 'Set Product', 'price' => 2000, 'category' => 'セット']),
            new Product(['id' => 2, 'name' => 'Single Product', 'price' => 1000, 'category' => 'テスト']),
        ]);

        $mockKeywords = new EloquentCollection([
            (object)['id' => 1, 'name' => 'セット商品', 'category' => 'セット'],
        ]);

        $this->productRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($mockProducts);

        $this->productRepository
            ->shouldReceive('findByCategory')
            ->with('セット')
            ->once()
            ->andReturn($mockKeywords);

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        // Mock cart with both set and single products
        Cart::shouldReceive('content')
            ->andReturn(\Illuminate\Support\Collection::make([
                (object)[
                    'id' => 1,
                    'qty' => 1,
                    'price' => 2000,
                    'options' => (object)[
                        'shippingFee' => 800,
                        'productType' => 'set'
                    ]
                ],
                (object)[
                    'id' => 2,
                    'qty' => 2,
                    'price' => 1000,
                    'options' => (object)[
                        'shippingFee' => 500,  
                        'productType' => 'single'
                    ]
                ]
            ]));

        // Act
        $result = $this->cartService->getCartViewData($this->userId);

        // Assert
        // Total should be: (2000 + 800) * 1 + (1000 + 500) * 2 = 2800 + 3000 = 5800
        $this->assertEquals(5800, $result['total']);
        $this->assertCount(2, $result['cart']);
    }

    public function test_add_to_cart_with_empty_selected_product_sets()
    {
        // Test that set products can be added even with empty selectedProductSets
        $itemData = [
            'id' => 1,
            'name' => 'Set Product',
            'qty' => 1,
            'price' => 2000,
            'weight' => 200,
            'img' => 'set.jpg',
            'setNum' => 0,
            'productType' => 'set',
            'selectedProductSets' => [], // Empty array
            'shipping_fee' => 800
        ];

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('add')
            ->with([
                'id' => 1,
                'name' => 'Set Product',
                'qty' => 1,
                'price' => 2000,
                'weight' => 200,
                'options' => [
                    'img' => 'set.jpg',
                    'setNum' => 0,
                    'productType' => 'set',
                    'selectedProductSets' => [],
                    'shippingFee' => 800,
                ]
            ])
            ->andReturn(true);

        Cart::shouldReceive('count')
            ->andReturn(1);

        // Act
        $result = $this->cartService->addToCart($this->userId, $itemData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('カートに追加しました！', $result['message']);
    }
}