<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    use ErrorHandlingTrait;

    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function index()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $userId = Auth::user()->id;
                $result = $this->checkoutService->getCartSummary($userId);
                return view('pay.index', $result);
            },
            'cart_summary_retrieval',
            ['user_id' => Auth::user()->id ?? null]
        );
    }

    public function store(Request $request)
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $userId = Auth::user()->id;
                $checkoutUrl = $this->checkoutService->createStripeSession($userId);
                return redirect($checkoutUrl);
            },
            'stripe_session_creation',
            ['user_id' => Auth::user()->id ?? null]
        );
    }

    public function success()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $userId = Auth::user()->id;
                $this->checkoutService->finalizeOrder($userId);
                return view('checkout.success');
            },
            'order_finalization',
            ['user_id' => Auth::user()->id ?? null]
        );
    }
}