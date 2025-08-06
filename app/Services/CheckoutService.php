<?php

namespace App\Services;

use Gloudemans\Shoppingcart\Facades\Cart;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutService
{
    use ErrorHandlingTrait;

    protected $productRepository;
    protected $orderItemRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        OrderItemRepositoryInterface $orderItemRepository
    ) {
        $this->productRepository = $productRepository;
        $this->orderItemRepository = $orderItemRepository;
    }

    public function getCartSummary($userId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId) {
                $cart = Cart::instance($userId)->content();
                $total = 0;
                $hasCarriageCost = false;
                $carriageCost = 0;

                foreach ($cart as $c) {
                    $total += $c->qty * $c->price;
                    if (isset($c->options->shippingFee)) {
                        $total += $c->qty * $c->options->shippingFee;
                    }
                    if ($c->options->carriage) {
                        $hasCarriageCost = true;
                    }
                }

                if ($hasCarriageCost) {
                    $total += env('CARRIAGE');
                    $carriageCost = env('CARRIAGE');
                }

                return [
                    'cart' => $cart,
                    'total' => $total,
                    'carriage_cost' => $carriageCost,
                ];
            },
            'cart_summary_retrieval',
            ['user_id' => $userId]
        );
    }

    public function createStripeSession($userId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId) {
                $cart = Cart::instance($userId)->content();
                $hasCarriageCost = false;
                $line_items = [];

                foreach ($cart as $product) {
                    if ($product->options->carriage) {
                        $hasCarriageCost = true;
                    }
                    $unitAmount = $product->price;
                    if (isset($product->options->shippingFee)) {
                        $unitAmount += $product->options->shippingFee;
                    }
                    $line_items[] = [
                        'price_data' => [
                            'currency' => 'jpy',
                            'product_data' => [
                                'name' => $product->name,
                            ],
                            'unit_amount' => $unitAmount,
                        ],
                        'quantity' => $product->qty,
                    ];
                }

                if ($hasCarriageCost) {
                    $line_items[] = [
                        'price_data' => [
                            'currency' => 'jpy',
                            'product_data' => [
                                'name' => '送料',
                            ],
                            'unit_amount' => env('CARRIAGE'),
                        ],
                        'quantity' => 1,
                    ];
                }

                Stripe::setApiKey(config('services.stripe.secret'));
                $checkout_session = Session::create([
                    'line_items' => $line_items,
                    'mode' => 'payment',
                    'success_url' => route('checkout.success'),
                    'cancel_url' => route('checkout.index'),
                ]);
                return $checkout_session->url;
            },
            'stripe_session_creation',
            [
                'user_id' => $userId,
                'line_items_count' => count($line_items ?? [])
            ]
        );
    }

    public function finalizeOrder($userId)
    {
        $cart = Cart::instance($userId)->content();
        return $this->executeWithErrorHandling(
            function() use ($userId, $cart) {
                DB::beginTransaction();
                
                $cart = Cart::instance($userId)->content();
                $cartItems = Cart::content();
                $cartIds = $cartItems->pluck('id')->toArray();
                $products = $this->productRepository->findByIds($cartIds);

                // 在庫減算
                foreach ($products as $product) {
                    $cartItem = $cartItems->firstWhere('id', $product->id);
                    if ($cartItem) {
                        $quantityInCart = $cartItem->qty;
                        if ($product->stock < $quantityInCart) {
                            throw new \Exception("商品「{$product->name}」の在庫が不足しています。", 400);
                        }
                        $newStock = $product->stock - $quantityInCart;
                        $this->productRepository->updateStock($product->id, $newStock);
                    }
                }

                $price_total = 0;
                $qty_total = 0;
                $has_carriage_cost = false;
                foreach ($cart as $c) {
                    $price_total += $c->qty * $c->price;
                    if (isset($c->options->shippingFee)) {
                        $price_total += $c->qty * $c->options->shippingFee;
                    }
                    $qty_total += $c->qty;
                    if ($c->options->carriage) {
                        $has_carriage_cost = true;
                    }
                }
                if ($has_carriage_cost) {
                    $price_total += env('CARRIAGE');
                }

                foreach (Cart::content() as $product) {
                    $this->orderItemRepository->create([
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'price'        => $product->price,
                        'shipping_fee' => $product->options->shippingFee ?? 0,
                        'quantity'     => $product->qty,
                        'user_id'      => $userId,
                        'total_price'  => $price_total,
                        'statusItem'   => 'paid',
                        'productType'  => $product->options->productType,
                        'selected_product_sets' => $product->options->selectedProductSets ?? [],
                    ]);
                }

                Cart::instance($userId)->destroy();
                
                DB::commit();
            },
            'order_finalization',
            [
                'user_id' => $userId,
                'cart_count' => $cart->count() ?? 0
            ]
        );
    }
}