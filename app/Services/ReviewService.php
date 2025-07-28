<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Product;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class ReviewService
{
    use ErrorHandlingTrait;

    public function createReview(array $data)
    {
        return $this->executeWithErrorHandling(
            function() use ($data) {
                $review = new Review();
                $review->content = $data['content'];
                $review->product_id = $data['product_id'];
                $review->user_id = Auth::user()->id;
                $review->score = $data['score'] ?? null;
                $review->save();

                return [
                    'success' => true,
                    'review' => $review,
                    'message' => 'レビューを投稿しました。'
                ];
            },
            'review_creation',
            [
                'product_id' => $data['product_id'] ?? null,
                'user_id' => Auth::user()->id ?? null
            ]
        );
    }

    public function getProductReviews($productId)
    {
        return $this->executeWithErrorHandling(
            function() use ($productId) {
                $product = Product::findOrFail($productId);
                $reviews = $product->reviews()->get();

                return [
                    'success' => true,
                    'reviews' => $reviews,
                    'product' => $product
                ];
            },
            'product_reviews_retrieval',
            ['product_id' => $productId]
        );
    }

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
            'review_deletion',
            [
                'review_id' => $reviewId,
                'product_id' => $review->product_id ?? null
            ]
        );
    }
} 