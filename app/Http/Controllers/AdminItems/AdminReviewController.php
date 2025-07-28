<?php

namespace App\Http\Controllers\AdminItems;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\AdminReviewService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    use ErrorHandlingTrait;

    protected $adminReviewService;

    public function __construct(AdminReviewService $adminReviewService)
    {
        $this->adminReviewService = $adminReviewService;
    }

    public function deleteReview(Review $review)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($review) {
                $result = $this->adminReviewService->deleteReview($review->id);
                return back()->with('success', $result['message']);
            },
            'admin_review_deletion',
            ['review_id' => $review->id]
        );
    }
}
