<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\User\UserLoginRequest;
use App\Services\UserAuthService;
use App\Traits\ErrorHandlingTrait;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    use ErrorHandlingTrait;

    protected $userAuthService;

    public function __construct(UserAuthService $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                return view('auth.userLogin.login');
            },
            'user_login_page_display'
        );
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(UserLoginRequest $request): RedirectResponse
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($request) {
                $credentials = $request->only('email', 'password');
                $remember = $request->boolean('remember');
                
                $result = $this->userAuthService->login($credentials, $remember);
                $this->userAuthService->regenerateSession();
                
                return redirect()->intended(RouteServiceProvider::HOME);
            },
            'user_login',
            ['email' => $request->input('email')]
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($request) {
                $this->userAuthService->logout();
                $this->userAuthService->invalidateSession();
                
                return redirect('/');
            },
            'user_logout'
        );
    }
}
