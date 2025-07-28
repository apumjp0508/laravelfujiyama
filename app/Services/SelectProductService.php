<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\SelectedBadge;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Auth;

class SelectProductService
{
    use ErrorHandlingTrait;

    public function getBadgesAndUser()
    {
        return $this->executeWithErrorHandling(
            function() {
                $badges = Badge::all();
                $user = Auth::user();
                return [
                    'badges' => $badges,
                    'user' => $user,
                ];
            },
            'badges_and_user_retrieval',
            ['user_id' => Auth::user()->id ?? null]
        );
    }

    public function createSelectedBadges(array $selectedBadgeIds)
    {
        return $this->executeWithErrorHandling(
            function() use ($selectedBadgeIds) {
                $selectedBadges = [];
                foreach ($selectedBadgeIds as $badgeId) {
                    $selectedBadges[] = SelectedBadge::create([
                        'badge_id' => $badgeId,
                        'user_id' => Auth::user()->id,
                    ]);
                }
                return $selectedBadges;
            },
            'selected_badges_creation',
            [
                'user_id' => Auth::user()->id ?? null,
                'selected_badge_ids' => $selectedBadgeIds
            ]
        );
    }
} 