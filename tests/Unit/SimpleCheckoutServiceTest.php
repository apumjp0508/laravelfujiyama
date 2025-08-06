<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CheckoutService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use Gloudemans\Shoppingcart\Facades\Cart;
use Mockery;

class SimpleCheckoutServiceTest extends TestCase
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

    public function test_get_cart_summary_calculates_correct_total_without_carriage()
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
        $this->assertEquals(3000, $result['total']); // (1000 + 500) * 2
        $this->assertEquals(0, $result['carriage_cost']);
    }

    public function test_get_cart_summary_includes_carriage_cost_when_needed()
    {
        // Arrange
        $cartItems = \Illuminate\Support\Collection::make([
            (object)[
                'id' => 1,
                'qty' => 1,
                'price' => 1000,
                'options' => (object)[
                    'shippingFee' => 500,
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
        $this->assertEquals(2500, $result['total']); // 1000 + 500 + 1000
        $this->assertEquals(1000, $result['carriage_cost']);
    }

    public function test_create_stripe_session_creates_correct_line_items()
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
                       count($args['line_items']) === 1 && // Only product, no carriage
                       $args['line_items'][0]['price_data']['unit_amount'] === 1500; // 1000 + 500
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
}