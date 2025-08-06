<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CheckoutService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Models\Product;
use App\Models\OrderItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Mockery;

class CheckoutServiceTest extends TestCase
{
    protected $checkoutService;
    protected $userId;
    protected $productRepository;
    protected $orderItemRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->productRepository = Mockery::mock(ProductRepositoryInterface::class);
        $this->orderItemRepository = Mockery::mock(OrderItemRepositoryInterface::class);
        
        $this->checkoutService = new CheckoutService(
            $this->productRepository,
            $this->orderItemRepository
        );
        $this->userId = 1;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_cart_summary_returns_correct_total()
    {
        // Arrange
        $cartItems = \Illuminate\Support\Collection::make([
            (object)[
                'id' => 1,
                'qty' => 2,
                'price' => 1000,
                'options' => (object)[
                    'shippingFee' => 500,
                    'carriage' => false
                ]
            ],
            (object)[
                'id' => 2,
                'qty' => 1,
                'price' => 2000,
                'options' => (object)[
                    'shippingFee' => 300,
                    'carriage' => true
                ]
            ]
        ]);

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->andReturn($cartItems);

        // Mock environment variable
        putenv('CARRIAGE=1000');

        // Act
        $result = $this->checkoutService->getCartSummary($this->userId);

        // Assert
        $this->assertArrayHasKey('cart', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('carriage_cost', $result);
        $this->assertEquals(6300, $result['total']); // (1000+500)*2 + (2000+300)*1 + 1000
        $this->assertEquals(1000, $result['carriage_cost']);
    }

    public function test_get_cart_summary_without_carriage_cost()
    {
        // Arrange
        $cartItems = \Illuminate\Support\Collection::make([
            (object)[
                'id' => 1,
                'qty' => 1,
                'price' => 1000,
                'options' => (object)[
                    'shippingFee' => 500,
                    'carriage' => false
                ]
            ]
        ]);

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->andReturn($cartItems);

        // Act
        $result = $this->checkoutService->getCartSummary($this->userId);

        // Assert
        $this->assertEquals(1500, $result['total']); // 1000 + 500
        $this->assertEquals(0, $result['carriage_cost']);
    }

    public function test_create_stripe_session_returns_url()
    {
        // Mock Stripe Session class
        $mockSession = Mockery::mock('alias:Stripe\Checkout\Session');
        $mockSessionObject = Mockery::mock('stdClass');
        $mockSessionObject->url = 'https://checkout.stripe.com/test';
        
        $mockSession->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function($args) {
                return isset($args['line_items']) && 
                       isset($args['mode']) && 
                       $args['mode'] === 'payment' &&
                       count($args['line_items']) === 1; // Only product, no carriage
            }))
            ->andReturn($mockSessionObject);

        // Mock Stripe class
        $mockStripe = Mockery::mock('alias:Stripe\Stripe');
        $mockStripe->shouldReceive('setApiKey')
            ->once()
            ->with('sk_test_fake_for_testing');

        // Arrange
        $cartItems = \Illuminate\Support\Collection::make([
            (object)[
                'id' => 1,
                'name' => 'Test Product',
                'qty' => 1,
                'price' => 1000,
                'options' => (object)[
                    'shippingFee' => 500,
                    'carriage' => false
                ]
            ]
        ]);

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->andReturn($cartItems);

        // Mock config for Stripe
        config(['services.stripe.secret' => 'sk_test_fake_for_testing']);

        // Act
        $result = $this->checkoutService->createStripeSession($this->userId);

        // Assert
        $this->assertEquals('https://checkout.stripe.com/test', $result);
    }

    public function test_create_stripe_session_includes_carriage_cost()
    {
        // Mock Stripe Session class
        $mockSession = Mockery::mock('alias:Stripe\Checkout\Session');
        $mockSessionObject = Mockery::mock('stdClass');
        $mockSessionObject->url = 'https://checkout.stripe.com/test-carriage';
        
        $mockSession->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function($args) {
                return isset($args['line_items']) && 
                       isset($args['mode']) && 
                       $args['mode'] === 'payment' &&
                       count($args['line_items']) === 2; // Product + carriage
            }))
            ->andReturn($mockSessionObject);

        // Mock Stripe class
        $mockStripe = Mockery::mock('alias:Stripe\Stripe');
        $mockStripe->shouldReceive('setApiKey')
            ->once()
            ->with('sk_test_fake_for_testing');

        // Arrange
        $cartItems = \Illuminate\Support\Collection::make([
            (object)[
                'id' => 1,
                'name' => 'Test Product',
                'qty' => 1,
                'price' => 1000,
                'options' => (object)[
                    'shippingFee' => 500,
                    'carriage' => true // This should add carriage cost
                ]
            ]
        ]);

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->andReturn($cartItems);

        // Mock environment variable
        putenv('CARRIAGE=1000');
        
        // Mock config for Stripe
        config(['services.stripe.secret' => 'sk_test_fake_for_testing']);

        // Act
        $result = $this->checkoutService->createStripeSession($this->userId);

        // Assert
        $this->assertEquals('https://checkout.stripe.com/test-carriage', $result);
    }

    public function test_finalize_order_calculates_correct_totals()
    {
        // Arrange
        $cartItems = \Illuminate\Support\Collection::make([
            (object)[
                'id' => 1,
                'name' => 'Test Product',
                'qty' => 2,
                'price' => 1000,
                'options' => (object)[
                    'shippingFee' => 500,
                    'carriage' => false,
                    'productType' => 'single',
                    'selectedProductSets' => []
                ]
            ]
        ]);

        // Mock Cart operations - the service calls instance() 3 times (content, content, destroy)
        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->times(3)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->times(4) // Called 4 times in the service: 2 with instance(), 2 without
            ->andReturn($cartItems);

        Cart::shouldReceive('destroy')
            ->once();

        // Mock DB operations
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        // Mock product repository
        $mockProduct = new Product();
        $mockProduct->id = 1;
        $mockProduct->name = 'Test Product';
        $mockProduct->stock = 10;
        
        $mockProducts = new EloquentCollection([$mockProduct]);

        $this->productRepository
            ->shouldReceive('findByIds')
            ->with([1])
            ->once()
            ->andReturn($mockProducts);

        $this->productRepository
            ->shouldReceive('updateStock')
            ->with(1, 8) // 10 - 2 = 8
            ->once()
            ->andReturn(true);

        // Mock order item repository
        $this->orderItemRepository
            ->shouldReceive('create')
            ->with(Mockery::on(function($args) {
                return $args['product_id'] === 1 &&
                       $args['quantity'] === 2 &&  
                       $args['price'] === 1000 &&
                       $args['shipping_fee'] === 500 &&
                       $args['statusItem'] === 'paid' &&
                       $args['total_price'] === 3000; // (1000 + 500) * 2
            }))
            ->once()
            ->andReturn(new OrderItem(['id' => 1]));

        // Act
        $result = $this->checkoutService->finalizeOrder($this->userId);

        // Assert
        $this->assertNull($result); // Method returns null on success
    }

    public function test_finalize_order_handles_carriage_cost_in_total()
    {
        // Arrange
        $cartItems = \Illuminate\Support\Collection::make([
            (object)[
                'id' => 1,
                'name' => 'Test Product',
                'qty' => 1,
                'price' => 1000,
                'options' => (object)[
                    'shippingFee' => 500,
                    'carriage' => true,
                    'productType' => 'single',
                    'selectedProductSets' => []
                ]
            ]
        ]);

        // Mock environment
        putenv('CARRIAGE=1000');

        // Mock Cart operations - the service calls instance() 3 times (content, content, destroy)
        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->times(3)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->times(4) // Called 4 times in the service: 2 with instance(), 2 without
            ->andReturn($cartItems);

        Cart::shouldReceive('destroy')
            ->once();

        // Mock DB operations
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        // Mock product repository
        $mockProduct = new Product();
        $mockProduct->id = 1;
        $mockProduct->name = 'Test Product';
        $mockProduct->stock = 10;
        
        $mockProducts = new EloquentCollection([$mockProduct]);

        $this->productRepository
            ->shouldReceive('findByIds')
            ->with([1])
            ->once()
            ->andReturn($mockProducts);

        $this->productRepository
            ->shouldReceive('updateStock')
            ->with(1, 9) // 10 - 1 = 9
            ->once()
            ->andReturn(true);

        // Mock order item repository - verify total includes carriage cost
        $this->orderItemRepository
            ->shouldReceive('create')
            ->with(Mockery::on(function($args) {
                // Total should be: (1000 + 500) * 1 + 1000 = 2500
                return $args['total_price'] === 2500;
            }))
            ->once()
            ->andReturn(new OrderItem(['id' => 1]));

        // Act
        $result = $this->checkoutService->finalizeOrder($this->userId);

        // Assert
        $this->assertNull($result);
    }

    // Additional tests for productType='set' checkout functionality
    public function test_finalize_order_handles_set_products_with_selected_product_sets()
    {
        // Arrange
        $cartItems = \Illuminate\Support\Collection::make([
            (object)[
                'id' => 1,
                'name' => 'Set Product',
                'qty' => 1,
                'price' => 2000,
                'options' => (object)[
                    'shippingFee' => 800,
                    'carriage' => false,
                    'productType' => 'set',
                    'selectedProductSets' => [1, 2, 3] // Selected ProductSet IDs
                ]
            ]
        ]);

        // Mock Cart operations
        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->times(3)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->times(4)
            ->andReturn($cartItems);

        Cart::shouldReceive('destroy')
            ->once();

        // Mock DB operations
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        // Mock product repository
        $mockProduct = new Product();
        $mockProduct->id = 1;
        $mockProduct->name = 'Set Product';
        $mockProduct->stock = 5;
        
        $mockProducts = new EloquentCollection([$mockProduct]);

        $this->productRepository
            ->shouldReceive('findByIds')
            ->with([1])
            ->once()
            ->andReturn($mockProducts);

        $this->productRepository
            ->shouldReceive('updateStock')
            ->with(1, 4) // 5 - 1 = 4
            ->once()
            ->andReturn(true);

        // Mock order item repository - verify set product data is stored
        $this->orderItemRepository
            ->shouldReceive('create')
            ->with(Mockery::on(function($args) {
                return $args['product_id'] === 1 &&
                       $args['productType'] === 'set' &&
                       $args['selected_product_sets'] === [1, 2, 3] &&
                       $args['total_price'] === 2800; // (2000 + 800) * 1
            }))
            ->once()
            ->andReturn(new OrderItem(['id' => 1]));

        // Act
        $result = $this->checkoutService->finalizeOrder($this->userId);

        // Assert
        $this->assertNull($result); // Success returns null
    }

    public function test_finalize_order_handles_mixed_single_and_set_products()
    {
        // Arrange
        $cartItems = \Illuminate\Support\Collection::make([
            (object)[
                'id' => 1,
                'name' => 'Single Product',
                'qty' => 2,
                'price' => 1000,
                'options' => (object)[
                    'shippingFee' => 500,
                    'carriage' => false,
                    'productType' => 'single',
                    'selectedProductSets' => []
                ]
            ],
            (object)[
                'id' => 2,
                'name' => 'Set Product',
                'qty' => 1,
                'price' => 2000,
                'options' => (object)[
                    'shippingFee' => 800,
                    'carriage' => false,
                    'productType' => 'set',
                    'selectedProductSets' => [1, 2]
                ]
            ]
        ]);

        // Mock Cart operations
        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->times(3)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->times(4)
            ->andReturn($cartItems);

        Cart::shouldReceive('destroy')
            ->once();

        // Mock DB operations
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        // Mock product repository
        $mockProduct1 = new Product();
        $mockProduct1->id = 1;
        $mockProduct1->name = 'Single Product';
        $mockProduct1->stock = 10;

        $mockProduct2 = new Product();
        $mockProduct2->id = 2;
        $mockProduct2->name = 'Set Product';
        $mockProduct2->stock = 5;
        
        $mockProducts = new EloquentCollection([$mockProduct1, $mockProduct2]);

        $this->productRepository
            ->shouldReceive('findByIds')
            ->with([1, 2])
            ->once()
            ->andReturn($mockProducts);

        $this->productRepository
            ->shouldReceive('updateStock')
            ->with(1, 8) // 10 - 2 = 8
            ->once()
            ->andReturn(true);

        $this->productRepository
            ->shouldReceive('updateStock')  
            ->with(2, 4) // 5 - 1 = 4
            ->once()
            ->andReturn(true);

        // Calculate expected total: (1000 + 500) * 2 + (2000 + 800) * 1 = 3000 + 2800 = 5800
        $expectedTotal = 5800;

        // Mock order item repository - verify both products are stored with correct total
        $this->orderItemRepository
            ->shouldReceive('create')
            ->with(Mockery::on(function($args) use ($expectedTotal) {
                return $args['product_id'] === 1 &&
                       $args['productType'] === 'single' &&
                       $args['total_price'] === $expectedTotal;
            }))
            ->once()
            ->andReturn(new OrderItem(['id' => 1]));

        $this->orderItemRepository
            ->shouldReceive('create')
            ->with(Mockery::on(function($args) use ($expectedTotal) {
                return $args['product_id'] === 2 &&
                       $args['productType'] === 'set' &&
                       $args['selected_product_sets'] === [1, 2] &&
                       $args['total_price'] === $expectedTotal;
            }))
            ->once()
            ->andReturn(new OrderItem(['id' => 2]));

        // Act
        $result = $this->checkoutService->finalizeOrder($this->userId);

        // Assert
        $this->assertNull($result);
    }

    public function test_get_cart_summary_with_set_products_calculates_correctly()
    {
        // Arrange
        $cartItems = \Illuminate\Support\Collection::make([
            (object)[
                'id' => 1,
                'qty' => 1,
                'price' => 2000,
                'options' => (object)[
                    'shippingFee' => 800,
                    'carriage' => false,
                    'productType' => 'set',
                    'selectedProductSets' => [1, 2, 3]
                ]
            ]
        ]);

        Cart::shouldReceive('instance')
            ->with($this->userId)
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->andReturn($cartItems);

        // Act
        $result = $this->checkoutService->getCartSummary($this->userId);

        // Assert
        $this->assertArrayHasKey('cart', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('carriage_cost', $result);
        $this->assertEquals(2800, $result['total']); // (2000 + 800) * 1
        $this->assertEquals(0, $result['carriage_cost']);

        // Verify cart contains set product information
        $cartItem = $result['cart']->first();
        $this->assertEquals('set', $cartItem->options->productType);
        $this->assertEquals([1, 2, 3], $cartItem->options->selectedProductSets);
    }
}