<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Badge;
use App\Traits\ErrorHandlingTrait;

class ConfirmItemsService
{
    use ErrorHandlingTrait;

    public function getConfirmItemsData($productId, $selectedBadgeIds)
    {
        return $this->executeWithErrorHandling(
            function() use ($productId, $selectedBadgeIds) {
                $product = Product::findOrFail($productId);
                $badges = Badge::whereIn('id', $selectedBadgeIds)->get();

                return [
                    'success' => true,
                    'product' => $product,
                    'badges' => $badges
                ];
            },
            'confirm_items_data_retrieval',
            [
                'product_id' => $productId,
                'selected_badge_ids' => $selectedBadgeIds
            ]
        );
    }
} 