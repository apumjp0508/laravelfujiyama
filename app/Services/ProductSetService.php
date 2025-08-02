<?php

namespace App\Services;

use App\Models\ProductSet;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductSetService
{
    use ErrorHandlingTrait;

    public function getAllProductSets()
    {
        return $this->executeWithErrorHandling(
            fn() => ProductSet::all(),
            'product_set_retrieval'
        );
    }

    public function createProductSet(array $data)
    {
        return $this->executeWithErrorHandling(
            fn() => ProductSet::create($data),
            'product_set_creation',
            ['data' => $data]
        );
    }

    public function updateProductSet(ProductSet $productSet, array $data)
    {
        return $this->executeWithErrorHandling(
            function() use ($productSet, $data) {
                $productSet->update($data);
                return $productSet;
            },
            'product_set_update',
            [
                'product_set_id' => $productSet->id,
                'data' => $data
            ]
        );
    }

    public function deleteProductSet(ProductSet $productSet)
    {
        return $this->executeWithErrorHandling(
            fn() => $productSet->delete(),
            'product_set_deletion',
            ['product_set_id' => $productSet->id]
        );
    }

    public function handleImageUpload(Request $request, ?ProductSet $productSet = null)
    {
        return $this->executeWithErrorHandling(
            function() use ($request, $productSet) {
                if ($request->hasFile('img')) {
                    // 古い画像があれば削除
                    if ($productSet && $productSet->img) {
                        $parsedPath = parse_url($productSet->img, PHP_URL_PATH);
                        $path = ltrim($parsedPath, '/');

                        if (app()->environment('production')) {
                            Storage::disk('s3')->delete($path);
                        } else {
                            $localPath = str_replace('storage/', 'public/', $path);
                            Storage::disk('public')->delete($localPath);
                        }
                    }

                    // 新しい画像を保存
                    if (app()->environment('production')) {
                        $path = $request->file('img')->store('images', 's3');
                        return Storage::disk('s3')->url($path);
                    } else {
                        $path = $request->file('img')->store('public/images');
                        return str_replace('public/', 'storage/', $path);
                    }
                }
                return $productSet ? $productSet->img : null;
            },
            'product_set_image_upload',
            [
                'product_set_id' => $productSet ? $productSet->id : null,
                'has_file' => $request->hasFile('img'),
                'environment' => app()->environment()
            ]
        );
    }
} 