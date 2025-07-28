<?php

namespace App\Services;

use App\Models\Review;
use App\Traits\ErrorHandlingTrait;

class AdminReviewService
{
    use ErrorHandlingTrait;

    public function deleteReview($reviewId)
    {
        return $this->executeWithErrorHandling(
            function() use ($reviewId) {
                $review = Review::findOrFail($reviewId);
                $review->delete();

                return [
                    'success' => true,
                    'message' => 'レビューを削除しました。'
                ];
            },
            'admin_review_deletion',
            ['review_id' => $reviewId]
        );
    }
} 