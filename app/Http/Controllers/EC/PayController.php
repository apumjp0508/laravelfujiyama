<?php

namespace App\Http\Controllers\EC;

use App\Http\Controllers\Controller;
use App\Services\PayService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayController extends Controller
{
    use ErrorHandlingTrait;

    protected $payService;

    public function __construct(PayService $payService)
    {
        $this->payService = $payService;
    }

    public function index()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $userId = Auth::user()->id;
                $result = $this->payService->getCartSummary($userId);
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
                $checkoutUrl = $this->payService->createStripeSession($userId);
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
                return view('checkout.success');
            },
            'checkout_success_page_display'
        );
    }
}
