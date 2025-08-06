<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class UserService
{
    use ErrorHandlingTrait;

    protected $userRepository;
    protected $orderItemRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        OrderItemRepositoryInterface $orderItemRepository
    ) {
        $this->userRepository = $userRepository;
        $this->orderItemRepository = $orderItemRepository;
    }

    public function getUserProfile($userId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId) {
                $user = $this->userRepository->findById($userId);
                
                if (!$user) {
                    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
                }
                
                return [
                    'success' => true,
                    'user' => $user
                ];
            },
            'user_profile_retrieval',
            ['user_id' => $userId]
        );
    }

    public function getUserOrders($userId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId) {
                $orderItems = $this->orderItemRepository->findByUserIdWithProduct($userId);

                return [
                    'success' => true,
                    'orderItems' => $orderItems
                ];
            },
            'user_orders_retrieval',
            ['user_id' => $userId]
        );
    }

    public function updateUser(User $user, array $data)
    {
        return $this->executeWithErrorHandling(
            function() use ($user, $data) {
                // 各フィールドを個別に更新（nullの場合は既存値を保持）
                $updateData = [
                    'name' => $data['name'] ?? $user->name,
                    'email' => $data['email'] ?? $user->email,
                    'postal_code' => $data['postal_code'] ?? $user->postal_code,
                    'address' => $data['address'] ?? $user->address,
                    'phone' => $data['phone'] ?? $user->phone,
                ];

                $this->userRepository->update($user, $updateData);

                return [
                    'success' => true,
                    'user' => $user,
                    'message' => 'ユーザー情報を更新しました。'
                ];
            },
            'user_update',
            [
                'user_id' => $user->id,
                'updated_fields' => array_keys(array_filter($data))
            ]
        );
    }

    public function getCurrentUser()
    {
        return $this->executeWithErrorHandling(
            function() {
                $user = Auth::user();
                if (!$user) {
                    throw new \Exception('ユーザーが認証されていません。', 401);
                }

                return [
                    'success' => true,
                    'user' => $user
                ];
            },
            'current_user_retrieval'
        );
    }
} 