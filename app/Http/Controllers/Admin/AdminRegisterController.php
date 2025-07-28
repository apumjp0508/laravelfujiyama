<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Admin\AdminRegisterRequest;
use App\Services\AdminAuthService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminRegisterController extends Controller
{
    use ErrorHandlingTrait;

    protected $adminAuthService;

    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    // 登録画面呼び出し
    public function create(): View
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                return view('auth.adminLogin.register');
            },
            'admin_registration_page_display'
        );
    }

    // 登録実行
    public function store(AdminRegisterRequest $request): RedirectResponse
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request) {
                $validated = $request->validated();
                $result = $this->adminAuthService->register($validated);
                
                return redirect($result['redirect']);
            },
            'admin_registration',
            [
                'name' => $request->validated()['name'] ?? null,
                'email' => $request->validated()['email'] ?? null
            ]
        );
    }
}
