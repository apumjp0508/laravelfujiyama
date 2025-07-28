<?php

namespace App\Services;

use App\Models\Admin;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AdminAuthService
{
    use ErrorHandlingTrait;

    public function login(array $credentials)
    {
        return $this->executeWithErrorHandling(
            function() use ($credentials) {
                if (Auth::guard('admin')->attempt($credentials)) {
                    return [
                        'success' => true,
                        'message' => 'ログインしました。',
                        'redirect' => route('admin.products.list')
                    ];
                }
                
                return [
                    'success' => false,
                    'message' => 'ログインに失敗しました',
                    'redirect' => null
                ];
            },
            'admin_login',
            ['email' => $credentials['email'] ?? null]
        );
    }

    public function register(array $data)
    {
        return $this->executeWithErrorHandling(
            function() use ($data) {
                $admin = Admin::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);

                event(new Registered($admin));

                Auth::guard('admin')->login($admin);

                return [
                    'success' => true,
                    'admin' => $admin,
                    'redirect' => route('admin.products.list')
                ];
            },
            'admin_registration',
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
                Auth::guard('admin')->logout();
                return [
                    'success' => true,
                    'message' => 'ログアウトしました。'
                ];
            },
            'admin_logout'
        );
    }
} 