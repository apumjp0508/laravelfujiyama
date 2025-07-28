<?php

namespace App\Http\Controllers\AdminItems;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Badge\StoreBadgeRequest;
use App\Http\Requests\Admin\Badge\UpdateBadgeRequest;
use App\Models\Badge;
use App\Services\BadgeService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;

class InsertPinbackButtonController extends Controller
{
    use ErrorHandlingTrait;

    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function index()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $badges = $this->badgeService->getAllBadges();
                return view('badge.index', compact('badges'));
            },
            'badge_list_display'
        );
    }

    public function create()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                return view('badge.create');
            },
            'badge_creation_page_display'
        );
    }

    public function store(StoreBadgeRequest $request)
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request) {
                $validated = $request->validated();
                $validated['img'] = $this->badgeService->handleImageUpload($request);
                $this->badgeService->createBadge($validated);
                return to_route('badge.index');
            },
            'badge_creation',
            ['validated_data' => $request->validated()]
        );
    }

    public function edit(Badge $badge)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($badge) {
                return view('badge.edit', compact('badge'));
            },
            'badge_edit_page_display',
            ['badge_id' => $badge->id]
        );
    }

    public function update(UpdateBadgeRequest $request, Badge $badge)
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request, $badge) {
                $validated = $request->validated();
                $validated['img'] = $this->badgeService->handleImageUpload($request, $badge);
                $this->badgeService->updateBadge($badge, $validated);
                return to_route('badge.index');
            },
            'badge_update',
            [
                'badge_id' => $badge->id,
                'validated_data' => $request->validated()
            ]
        );
    }

    public function destroy(Badge $badge)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($badge) {
                $this->badgeService->deleteBadge($badge);
                return to_route('badge.index');
            },
            'badge_deletion',
            ['badge_id' => $badge->id]
        );
    }
}
