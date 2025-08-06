<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    use ErrorHandlingTrait;

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserFavorites($userId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId) {
                $products = $this->userRepository->getUserFavoriteProducts($userId);

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
                $user = $this->userRepository->findById($userId);
                
                if (!$user) {
                    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
                }
                
                $this->userRepository->addToFavorites($userId, $productId);

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
                $user = $this->userRepository->findById($userId);
                
                if (!$user) {
                    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
                }
                
                $this->userRepository->removeFromFavorites($userId, $productId);

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