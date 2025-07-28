<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UserAuthService
{
    use ErrorHandlingTrait;

    public function login(array $credentials, $remember = false)
    {
        return $this->executeWithErrorHandling(
            function() use ($credentials, $remember) {
                if (Auth::attempt($credentials, $remember)) {
                    return [
                        'success' => true,
                        'user' => Auth::user(),
                        'message' => 'ログインしました。'
                    ];
                }
                
                throw new \Exception('認証に失敗しました。', 401);
            },
            'user_login',
            ['email' => $credentials['email'] ?? null]
        );
    }

    public function register(array $data)
    {
        return $this->executeWithErrorHandling(
            function() use ($data) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'postal_code' => $data['postal_code'],
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['password']),
                ]);

                event(new Registered($user));

                Auth::login($user);

                return [
                    'success' => true,
                    'user' => $user,
                    'redirect' => '/verify-email'
                ];
            },
            'user_registration',
            [
                'name' => $data['name'] ?? null,
                'email' => $data['email'] ?? null
            ]
        );
    }

    public function logout()
    {
        return $this->executeWithErrorHandling(
            function() {
                Auth::guard('web')->logout();
                return [
                    'success' => true,
                    'message' => 'ログアウトしました。'
                ];
            },
            'user_logout'
        );
    }

    public function regenerateSession()
    {
        return $this->executeWithErrorHandling(
            function() {
                request()->session()->regenerate();
                return [
                    'success' => true
                ];
            },
            'session_regeneration'
        );
    }

    public function invalidateSession()
    {
        return $this->executeWithErrorHandling(
            function() {
                request()->session()->invalidate();
                request()->session()->regenerateToken();
                return [
                    'success' => true
                ];
            },
            'session_invalidation'
        );
    }
} 