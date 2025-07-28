<?php

namespace App\Services;

use App\Models\User;
use App\Models\OrderItem;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class UserService
{
    use ErrorHandlingTrait;

    public function getUserProfile($userId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId) {
                $user = User::findOrFail($userId);
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
                $orderItems = OrderItem::where('user_id', $userId)
                    ->with('product')
                    ->get();

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
                $user->name = $data['name'] ?? $user->name;
                $user->email = $data['email'] ?? $user->email;
                $user->postal_code = $data['postal_code'] ?? $user->postal_code;
                $user->address = $data['address'] ?? $user->address;
                $user->phone = $data['phone'] ?? $user->phone;

                $user->save();

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