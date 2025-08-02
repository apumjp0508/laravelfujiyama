<?php

namespace App\Services;

use App\Models\ProductSet;
use App\Models\BeforeBuySelectedProductSet;
use App\Models\Product;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class ConfirmItemsService
{
    use ErrorHandlingTrait;

    public function clearSelectedProductSets($productId, $userId)
    {
        return $this->executeWithErrorHandling(
            function() use ($productId, $userId) {
                BeforeBuySelectedProductSet::where('product_id', $productId)
                    ->where('user_id', $userId)
                    ->delete();
            },
            'clear_selected_product_sets',
            ['product_id' => $productId, 'user_id' => $userId]
        );
    }

    public function updateSelectedProductSetsForSet($productId, $selectedProductSetIds, $setId)
    {
        return $this->executeWithErrorHandling(
            function() use ($productId, $selectedProductSetIds, $setId) {
                if (!Auth::check()) {
                    return ['success' => false, 'message' => 'User not authenticated'];
                }

                // Clear previous selections for this specific set
                BeforeBuySelectedProductSet::where('product_id', $productId)
                    ->where('user_id', Auth::id())
                    ->where('set_id', $setId)
                    ->delete();

                // Add new selections for this set
                if (!empty($selectedProductSetIds)) {
                    $productSetIdsArray = is_string($selectedProductSetIds) ? explode(',', $selectedProductSetIds) : $selectedProductSetIds;
                    
                    foreach ($productSetIdsArray as $productSetId) {
                        BeforeBuySelectedProductSet::create([
                            'product_id' => $productId,
                            'product_set_id' => intval(trim($productSetId)),
                            'user_id' => Auth::id(),
                            'set_id' => $setId
                        ]);
                    }
                }

                return ['success' => true];
            },
            'update_selected_product_sets_for_set',
            [
                'product_id' => $productId,
                'selected_product_set_ids' => $selectedProductSetIds,
                'set_id' => $setId
            ]
        );
    }

    public function getConfirmItemsData($productId, $selectedProductSetIds = null, $setId = null)
    {
        return $this->executeWithErrorHandling(
            function() use ($productId, $selectedProductSetIds, $setId) {
                $product = Product::findOrFail($productId);
                
                // Get selected product sets from database if user is logged in and no product sets provided via parameter
                $finalSelectedProductSetIds = $selectedProductSetIds;
                if (Auth::check() && (empty($selectedProductSetIds) || $selectedProductSetIds === null)) {
                    $query = BeforeBuySelectedProductSet::where('product_id', $productId)
                        ->where('user_id', Auth::id());
                    
                    // If setId is provided, filter by specific set
                    if ($setId !== null) {
                        $query->where('set_id', $setId);
                    }
                    
                    $userSelectedProductSetIds = $query->pluck('product_set_id')->toArray();
                    
                    if (!empty($userSelectedProductSetIds)) {
                        $finalSelectedProductSetIds = $userSelectedProductSetIds;
                    }
                }
                
                // Get product sets based on the final selected product set IDs
                $productSets = [];
                if (!empty($finalSelectedProductSetIds)) {
                    $productSets = ProductSet::whereIn('id', $finalSelectedProductSetIds)->get();
                }

                return [
                    'success' => true,
                    'product' => $product,
                    'productSets' => $productSets,
                    'setId' => $setId
                ];
            },
            'confirm_items_data_retrieval',
            [
                'product_id' => $productId,
                'selected_product_set_ids' => $selectedProductSetIds,
                'set_id' => $setId
            ]
        );
    }
} 