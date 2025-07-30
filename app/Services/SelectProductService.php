<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\BeforeBuySelectedBadge;
use App\Models\Product;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class SelectProductService
{
    use ErrorHandlingTrait;

    public function getBadgesAndUser(Product $product)
    {
        return $this->executeWithErrorHandling(
            function() use ($product) {
                $badges = Badge::all();
                $user = Auth::user();
                return [
                    'badges' => $badges,
                    'user' => $user,
                    'product' => $product,
                ];
            },
            'badges_and_user_retrieval',
            ['user_id' => Auth::user()->id ?? null, 'product_id' => $product->id]
        );
    }

    public function createSelectedBadges(array $selectedBadgeIds, $productId, $userId, $setId = null)
    {
        return $this->executeWithErrorHandling(
            function() use ($selectedBadgeIds, $productId, $userId, $setId) {
                $selectedBadges = [];
                foreach ($selectedBadgeIds as $badgeId) {
                    $badge = Badge::find($badgeId);
                    if ($badge) {
                        $selectedBadges[] = BeforeBuySelectedBadge::create([
                            'product_id' => $productId,
                            'badge_id' => $badgeId,
                            'user_id' => $userId,
                            'widthSize' => $badge->widthSize,
                            'heightSize' => $badge->heightSize,
                            'set_id' => $setId,
                        ]);
                    }
                }
                return $selectedBadges;
            },
            'selected_badges_creation',
            [
                'user_id' => $userId,
                'product_id' => $productId,
                'selected_badge_ids' => $selectedBadgeIds,
                'set_id' => $setId
            ]
        );
    }
} 