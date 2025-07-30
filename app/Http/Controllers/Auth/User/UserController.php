<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ErrorHandlingTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function mypage()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $result = $this->userService->getCurrentUser();
                $user = $result['user'];
                return view('users.mypage', compact('user'));
            },
            'user_mypage_display',
            ['user_id' => Auth::user()->id ?? null]
        );
    }

    public function ConfirmOrder()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $userId = Auth::id();
                $result = $this->userService->getUserOrders($userId);
                $orderItems = $result['orderItems'];
                
                return view('users.confirmOrder', compact('orderItems'));
            },
            'user_orders_display',
            ['user_id' => Auth::id() ?? null]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $result = $this->userService->getCurrentUser();
                $user = $result['user'];
                return view('users.edit', compact('user'));
            },
            'user_edit_page_display',
            ['user_id' => Auth::user()->id ?? null]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request) {
                $user = Auth::user();
                $data = [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'postal_code' => $request->input('postal_code'),
                    'address' => $request->input('address'),
                    'phone' => $request->input('phone'),
                ];
                
                $result = $this->userService->updateUser($user, $data);
                
                return to_route('mypage');
            },
            'user_update',
            [
                'user_id' => Auth::user()->id ?? null,
                'updated_fields' => array_keys(array_filter($request->only(['name', 'email', 'postal_code', 'address', 'phone'])))
            ]
        );
    }
}
