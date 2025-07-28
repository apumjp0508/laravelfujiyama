<?php

namespace App\Services;

use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class CartService
{
    use ErrorHandlingTrait;

    public function getCartViewData($userId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId) {
                $cart = Cart::instance($userId)->content();
                $total = 0;
                foreach ($cart as $c) {
                    $total += $c->qty * $c->price;
                }
                $products = Product::all();
                $categories = $products->pluck('category')->toArray();
                $keywords = Product::where('category', 'like', "%セット%") ->get();
                return [
                    'userId' => $userId,
                    'cart' => $cart,
                    'total' => $total,
                    'products' => $products,
                    'keywords' => $keywords,
                    'categories' => $categories,
                ];
            },
            'cart_view_data_retrieval',
            ['user_id' => $userId]
        );
    }

    public function addToCart($userId, $itemData)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId, $itemData) {
                Cart::instance($userId)->add([
                    'id' => $itemData['id'],
                    'name' => $itemData['name'],
                    'qty' => $itemData['qty'],
                    'price' => $itemData['price'],
                    'weight' => $itemData['weight'],
                    'options' => [
                        'img' => $itemData['img'] ?? null,
                        'setNum' => $itemData['setNum'] ?? null,
                        'productType' => $itemData['productType'] ?? null,
                        'selectedBadges' => $itemData['selectedBadges'] ?? null,
                    ]
                ]);
                return [
                    'success' => true,
                    'message' => 'カートに追加しました！',
                    'cart_count' => Cart::instance($userId)->count(),
                ];
            },
            'add_to_cart',
            [
                'user_id' => $userId,
                'item_data' => $itemData
            ]
        );
    }

    public function updateCartItem($userId, $productId, $qty)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId, $productId, $qty) {
                $cartItem = Cart::instance($userId)->content()->where('id', $productId)->first();
                if ($cartItem) {
                    if ($qty == 0) {
                        Cart::instance($userId)->remove($cartItem->rowId);
                    } else {
                        Cart::instance($userId)->update($cartItem->rowId, $qty);
                    }
                }
                $product = Product::find($productId);
                $productTotal = $product ? $product->price * $qty : 0;
                $cartTotal = Cart::instance($userId)->content()->sum(function ($cartItem) {
                    return $cartItem->qty > 0 ? $cartItem->price * $cartItem->qty : 0;
                });
                return [
                    'success' => true,
                    'product_total' => $productTotal,
                    'cart_total' => $cartTotal
                ];
            },
            'cart_item_update',
            [
                'user_id' => $userId,
                'product_id' => $productId,
                'qty' => $qty
            ]
        );
    }

    public function removeCartItem($userId, $rowId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId, $rowId) {
                Cart::instance($userId);
                Cart::remove($rowId);
            },
            'cart_item_removal',
            [
                'user_id' => $userId,
                'row_id' => $rowId
            ]
        );
    }
}