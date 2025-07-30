<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\BeforeBuySelectedBadge;
use App\Models\Product;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class ConfirmItemsService
{
    use ErrorHandlingTrait;

    public function updateSelectedBadgesForSet($productId, $selectedBadgeIds, $setId)
    {
        return $this->executeWithErrorHandling(
            function() use ($productId, $selectedBadgeIds, $setId) {
                if (!Auth::check()) {
                    return ['success' => false, 'message' => 'User not authenticated'];
                }

                // Clear previous selections for this specific set
                BeforeBuySelectedBadge::where('product_id', $productId)
                    ->where('user_id', Auth::id())
                    ->where('set_id', $setId)
                    ->delete();

                // Add new selections for this set
                if (!empty($selectedBadgeIds)) {
                    $badgeIdsArray = is_string($selectedBadgeIds) ? explode(',', $selectedBadgeIds) : $selectedBadgeIds;
                    
                    foreach ($badgeIdsArray as $badgeId) {
                        BeforeBuySelectedBadge::create([
                            'product_id' => $productId,
                            'badge_id' => intval(trim($badgeId)),
                            'user_id' => Auth::id(),
                            'set_id' => $setId
                        ]);
                    }
                }

                return ['success' => true];
            },
            'update_selected_badges_for_set',
            [
                'product_id' => $productId,
                'selected_badge_ids' => $selectedBadgeIds,
                'set_id' => $setId
            ]
        );
    }

    public function getConfirmItemsData($productId, $selectedBadgeIds = null, $setId = null)
    {
        return $this->executeWithErrorHandling(
            function() use ($productId, $selectedBadgeIds, $setId) {
                $product = Product::findOrFail($productId);
                
                // Get selected badges from database if user is logged in and no badges provided via parameter
                $finalSelectedBadgeIds = $selectedBadgeIds;
                if (Auth::check() && (empty($selectedBadgeIds) || $selectedBadgeIds === null)) {
                    $query = BeforeBuySelectedBadge::where('product_id', $productId)
                        ->where('user_id', Auth::id());
                    
                    // If setId is provided, filter by specific set
                    if ($setId !== null) {
                        $query->where('set_id', $setId);
                    }
                    
                    $userSelectedBadgeIds = $query->pluck('badge_id')->toArray();
                    
                    if (!empty($userSelectedBadgeIds)) {
                        $finalSelectedBadgeIds = $userSelectedBadgeIds;
                    }
                }
                
                // Get badges based on the final selected badge IDs
                $badges = [];
                if (!empty($finalSelectedBadgeIds)) {
                    $badges = Badge::whereIn('id', $finalSelectedBadgeIds)->get();
                }

                return [
                    'success' => true,
                    'product' => $product,
                    'badges' => $badges,
                    'setId' => $setId
                ];
            },
            'confirm_items_data_retrieval',
            [
                'product_id' => $productId,
                'selected_badge_ids' => $selectedBadgeIds,
                'set_id' => $setId
            ]
        );
    }
} 