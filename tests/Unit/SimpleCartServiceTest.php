<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CartService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Mockery;

class SimpleCartServiceTest extends TestCase
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

    public function test_update_cart_item_removes_when_qty_zero()
    {
        // Arrange
        $productId = 1;
        $qty = 0;

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
            ->andReturn(true);

        // Mock the product repository
        $product = new Product(['id' => $productId, 'price' => 1000]);
        
        $this->productRepository
            ->shouldReceive('findById')
            ->with($productId)
            ->andReturn($product);

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
}