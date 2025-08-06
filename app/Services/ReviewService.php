<?php

namespace App\Services;

use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class ReviewService
{
    use ErrorHandlingTrait;

    protected $reviewRepository;
    protected $productRepository;

    public function __construct(
        ReviewRepositoryInterface $reviewRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->productRepository = $productRepository;
    }

    public function createReview(array $data)
    {
        return $this->executeWithErrorHandling(
            function() use ($data) {
                $review = $this->reviewRepository->create([
                    'content' => $data['content'],
                    'product_id' => $data['product_id'],
                    'user_id' => Auth::user()->id,
                    'score' => $data['score'] ?? null,
                ]);

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
                $product = $this->productRepository->findById($productId);
                if (!$product) {
                    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
                }
                $reviews = $this->reviewRepository->findByProductId($productId);

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
                $review = $this->reviewRepository->findById($reviewId);
                if (!$review) {
                    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
                }
                $this->reviewRepository->delete($review);

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