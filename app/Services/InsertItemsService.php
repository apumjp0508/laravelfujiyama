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
            function () use ($request, $product) {
                if (!$request->hasFile('img')) {
                    return $product ? $product->img : null;
                }

                $file = $request->file('img');
                if (!$file->isValid()) {
                    throw new \RuntimeException('画像のアップロードに失敗しました（isValid=false, code=' . $file->getError() . ')');
                }

                if ($product && $product->img) {
                    $parsedPath = parse_url($product->img, PHP_URL_PATH) ?: $product->img;
                    $path = ltrim($parsedPath, '/');

                    if (app()->environment('production')) {
                        $key = preg_replace('#^storage/#', '', $path);
                        Storage::disk('s3')->delete($key);
                    } else {
                        $localKey = str_replace('storage/', 'public/', $path);
                        Storage::disk('public')->delete($localKey);
                    }
                }

                if (app()->environment('production')) {
                    $key = $file->store('images', 's3');
                    if (!$key) {
                        throw new \RuntimeException('S3への画像保存に失敗しました');
                    }
                    return Storage::disk('s3')->url($key);
                } else {
                    $saved = $file->store('public/images');
                    if (!$saved) {
                        throw new \RuntimeException('ローカルへの画像保存に失敗しました');
                    }
                    return str_replace('public/', 'storage/', $saved);
                }
            },
            'image_upload',
            [
                'product_id' => $product ? $product->id : null,
                'has_file' => $request->hasFile('img'),
                'environment' => app()->environment(),
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