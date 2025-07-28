<?php

namespace App\Http\Controllers\EC;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreReviewRequest;
use App\Services\ReviewService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;

class ReviewProductController extends Controller
{
    use ErrorHandlingTrait;

    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function adminReview($productId)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($productId) {
                $result = $this->reviewService->getProductReviews($productId);
                
                return view('manageView.review', [
                    'product' => $result['product'],
                    'reviews' => $result['reviews']
                ]);
            },
            'admin_review_display',
            ['product_id' => $productId]
        );
    }

    public function store(StoreReviewRequest $request)
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request) {
                $validated = $request->validated();
                $result = $this->reviewService->createReview($validated);
                
                return back()->with('success', $result['message']);
            },
            'review_creation',
            [
                'product_id' => $request->validated()['product_id'] ?? null,
                'user_id' => auth()->user()->id ?? null
            ]
        );
    }
}
