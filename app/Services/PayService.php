<?php

namespace App\Services;

use Gloudemans\Shoppingcart\Facades\Cart;
use App\Traits\ErrorHandlingTrait;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PayService
{
    use ErrorHandlingTrait;

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
                    $line_items[] = [
                        'price_data' => [
                            'currency' => 'jpy',
                            'product_data' => [
                                'name' => $product->name,
                            ],
                            'unit_amount' => $product->price,
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

                Stripe::setApiKey(env('STRIPE_SECRET'));
                $checkout_session = Session::create([
                    'line_items' => $line_items,
                    'mode' => 'payment',
                    'success_url' => route('pay.success'),
                    'cancel_url' => route('pay.index'),
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
}