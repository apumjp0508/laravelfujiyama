<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    use ErrorHandlingTrait;

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateProfile(User $user, array $data)
    {
        return $this->executeWithErrorHandling(
            function() use ($user, $data) {
                $user->fill($data);

                // メールアドレスが変更された場合、メール認証をリセット
                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                }

                $user->save();

                return [
                    'success' => true,
                    'user' => $user,
                    'message' => 'プロフィールを更新しました。'
                ];
            },
            'profile_update',
            [
                'user_id' => $user->id,
                'updated_fields' => array_keys($data)
            ]
        );
    }

    public function deleteAccount(User $user, string $password)
    {
        return $this->executeWithErrorHandling(
            function() use ($user, $password) {
                // パスワード確認は既にコントローラーで実行済み
                
                // ログアウト
                Auth::logout();

                // アカウント削除
                $user->delete();

                return [
                    'success' => true,
                    'message' => 'アカウントを削除しました。'
                ];
            },
            'account_deletion',
            ['user_id' => $user->id]
        );
    }

    public function getUserProfile($userId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId) {
                $user = $this->userRepository->findByIdOrFail($userId);
                return [
                    'success' => true,
                    'user' => $user
                ];
            },
            'profile_retrieval',
            ['user_id' => $userId]
        );
    }
} 