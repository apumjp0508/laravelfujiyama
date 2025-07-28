<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\User\RegisterUserRequest;
use App\Services\UserAuthService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    use ErrorHandlingTrait;

    protected $userAuthService;

    public function __construct(UserAuthService $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                return view('auth.userLogin.register');
            },
            'user_registration_page_display'
        );
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request) {
                $validated = $request->validated();
                $result = $this->userAuthService->register($validated);
                
                return redirect($result['redirect']);
            },
            'user_registration',
            [
                'name' => $request->validated()['name'] ?? null,
                'email' => $request->validated()['email'] ?? null
            ]
        );
    }
}
