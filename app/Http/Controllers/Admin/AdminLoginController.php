<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Admin\AdminLoginRequest;
use App\Services\AdminAuthService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    use ErrorHandlingTrait;

    protected $adminAuthService;

    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    // ログイン画面呼び出し
    public function showLoginPage(): View
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                return view('auth.adminLogin.login');
            },
            'admin_login_page_display'
        );
    }

    // ログイン実行
    public function login(AdminLoginRequest $request): RedirectResponse
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($request) {
                $credentials = $request->validated();
                $result = $this->adminAuthService->login($credentials);
                
                if ($result['success']) {
                    return redirect($result['redirect'])->with([
                        'login_msg' => $result['message'],
                    ]);
                }
                
                return back()->withErrors([
                    'login' => [$result['message']],
                ]);
            },
            'admin_login',
            ['email' => $request->validated()['email'] ?? null]
        );
    }
}
