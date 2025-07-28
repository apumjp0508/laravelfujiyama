<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    use ErrorHandlingTrait;

    public function getUserFavorites($userId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId) {
                $user = User::findOrFail($userId);
                $products = $user->favorite_products()->get();

                return [
                    'success' => true,
                    'products' => $products
                ];
            },
            'user_favorites_retrieval',
            ['user_id' => $userId]
        );
    }

    public function addToFavorites($userId, $productId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId, $productId) {
                $user = User::findOrFail($userId);
                $user->favorite_products()->attach($productId);

                return [
                    'success' => true,
                    'message' => 'お気に入りに追加しました。'
                ];
            },
            'add_to_favorites',
            [
                'user_id' => $userId,
                'product_id' => $productId
            ]
        );
    }

    public function removeFromFavorites($userId, $productId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId, $productId) {
                $user = User::findOrFail($userId);
                $user->favorite_products()->detach($productId);

                return [
                    'success' => true,
                    'message' => 'お気に入りから削除しました。'
                ];
            },
            'remove_from_favorites',
            [
                'user_id' => $userId,
                'product_id' => $productId
            ]
        );
    }
} 