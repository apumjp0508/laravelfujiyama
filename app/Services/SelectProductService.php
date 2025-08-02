<?php

namespace App\Services;

use App\Models\ProductSet;
use App\Models\BeforeBuySelectedProductSet;
use App\Models\Product;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class SelectProductService
{
    use ErrorHandlingTrait;

    public function getProductSetsAndUser(Product $product)
    {
        return $this->executeWithErrorHandling(
            function() use ($product) {
                $productSets = ProductSet::all();
                $user = Auth::user();
                return [
                    'productSets' => $productSets,
                    'user' => $user,
                    'product' => $product,
                ];
            },
            'product_sets_and_user_retrieval',
            ['user_id' => Auth::user()->id ?? null, 'product_id' => $product->id]
        );
    }

    public function createSelectedProductSets(array $selectedProductSetIds, $productId, $userId, $setId = null)
    {
        return $this->executeWithErrorHandling(
            function() use ($selectedProductSetIds, $productId, $userId, $setId) {
                BeforeBuySelectedProductSet::where('product_id', $productId)->delete();
                
                $selectedProductSets = [];
                foreach ($selectedProductSetIds as $selectedProductSetId) {
                    $productSet = ProductSet::find($selectedProductSetId);
                    if ($productSet) {
                        $selectedProductSets[] = BeforeBuySelectedProductSet::create([
                            'product_id' => $productId,
                            'product_set_id' => $selectedProductSetId,
                            'user_id' => $userId,
                            'widthSize' => $productSet->widthSize,
                            'heightSize' => $productSet->heightSize,
                            'set_id' => $setId,
                        ]);
                    }
                }
              
                return $selectedProductSets;
            },
            'selected_product_sets_creation',
            [
                'user_id' => $userId,
                'product_id' => $productId,
                'selected_product_set_ids' => $selectedProductSetIds,
                'set_id' => $setId
            ]
        );
    }
} 