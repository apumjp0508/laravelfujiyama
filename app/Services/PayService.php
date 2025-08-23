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
                    if ($c->options->shipping_fee>0) {
                        $total += $c->qty * $c->options->shipping_fee;
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
                    if (isset($product->options->shipping_fee)) {
                        $unitAmount += $product->options->shipping_fee;
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

                // $line_itemsの中身をログに出力
                \Log::info('Stripe line_items contents:', [
                    'line_items' => $line_items,
                    'user_id' => $userId,
                    'cart_count' => count($cart)
                ]);

                // 開発環境でのみHTMLコメントとして出力（本番環境では削除）
                if (app()->environment('local', 'development')) {
                    \Log::info('DEBUG - Line items HTML comment: <!-- ' . json_encode($line_items, JSON_PRETTY_PRINT) . ' -->');
                    
                    // セッションに一時保存（デバッグ用）
                    session()->flash('debug_line_items', $line_items);
                    session()->flash('debug_cart_summary', [
                        'cart_count' => count($cart),
                        'has_carriage_cost' => $hasCarriageCost,
                        'carriage_amount' => env('CARRIAGE')
                    ]);
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