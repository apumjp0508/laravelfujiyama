<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ProfileService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use ErrorHandlingTrait;

    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($request) {
                return view('profile.edit', [
                    'user' => $request->user(),
                ]);
            },
            'profile_edit_page_display'
        );
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request) {
                $user = $request->user();
                $validated = $request->validated();
                
                $result = $this->profileService->updateProfile($user, $validated);
                
                return Redirect::route('profile.edit')->with('status', 'profile-updated');
            },
            'profile_update',
            [
                'user_id' => $request->user()->id ?? null,
                'updated_fields' => array_keys($request->validated())
            ]
        );
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request) {
                $user = $request->user();
                $password = $request->input('password');
                
                $result = $this->profileService->deleteAccount($user, $password);
                
                return Redirect::to('/');
            },
            'account_deletion',
            ['user_id' => $request->user()->id ?? null]
        );
    }
}
