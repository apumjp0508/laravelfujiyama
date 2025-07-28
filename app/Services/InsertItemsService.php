<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Review;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class InsertItemsService
{
    use ErrorHandlingTrait;

    public function getAllProducts()
    {
        return $this->executeWithErrorHandling(
            fn() => Product::all(),
            'product_retrieval'
        );
    }

    public function createProduct(array $data)
    {
        return $this->executeWithErrorHandling(
            fn() => Product::create($data),
            'product_creation',
            ['data' => $data]
        );
    }

    public function updateProduct(Product $product, array $data)
    {
        return $this->executeWithErrorHandling(
            function() use ($product, $data) {
                $product->update($data);
                return $product;
            },
            'product_update',
            [
                'product_id' => $product->id,
                'data' => $data
            ]
        );
    }

    public function deleteProduct(Product $product)
    {
        return $this->executeWithErrorHandling(
            fn() => $product->delete(),
            'product_deletion',
            ['product_id' => $product->id]
        );
    }

    public function handleImageUpload(Request $request, ?Product $product = null)
    {
        return $this->executeWithErrorHandling(
            function() use ($request, $product) {
                //本番環境と開発環境でのimg保存場所変更
                if ($request->hasFile('img')) {
                    if ($product && $product->img) {
                        $parsedUrl = parse_url($product->img);
                        if (isset($parsedUrl['path'])) {
                            $path = ltrim($parsedUrl['path'], '/');
                            if (App::environment('production')) {
                                Storage::disk('s3')->delete($path);
                            } else {
                                Storage::disk('public')->delete($path);
                            }
                        }
                    }

                    if (App::environment('production')) {
                        $path = $request->file('img')->store('images', 's3');
                        return Storage::disk('s3')->url($path);
                    } else {
                        $path = $request->file('img')->store('img', 'public');
                        return asset('storage/' . $path);
                    }
                }

                return $product ? $product->img : null;
            },
            'image_upload',
            [
                'product_id' => $product ? $product->id : null,
                'has_file' => $request->hasFile('img')
            ]
        );
    }

    public function getProductReviews(Product $product)
    {
        return $this->executeWithErrorHandling(
            fn() => $product->reviews()->get(),
            'product_review_retrieval',
            ['product_id' => $product->id]
        );
    }

    public function deleteReview(Review $review)
    {
        return $this->executeWithErrorHandling(
            fn() => $review->delete(),
            'product_review_deletion',
            [
                'review_id' => $review->id,
                'product_id' => $review->product_id
            ]
        );
    }
}