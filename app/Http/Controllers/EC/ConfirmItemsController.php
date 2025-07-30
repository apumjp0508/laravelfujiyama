<?php

namespace App\Http\Controllers\EC;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ConfirmItemsService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;

class ConfirmItemsController extends Controller
{
    use ErrorHandlingTrait;

    protected $confirmItemsService;

    public function __construct(ConfirmItemsService $confirmItemsService)
    {
        $this->confirmItemsService = $confirmItemsService;
    }

    public function confirmItems(Request $request, Product $product)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($request, $product) {
                $selectedBadgeIds = $request->query('selectedBadges');
                dd($selectedBadgeIds);
                $setId = $request->query('setId');
                
                // If setId is provided, clear previous selected badges for this set and update with new selection
                if ($setId !== null && !empty($selectedBadgeIds)) {
                    $this->confirmItemsService->updateSelectedBadgesForSet($product->id, $selectedBadgeIds, $setId);
                }
                
                $result = $this->confirmItemsService->getConfirmItemsData($product->id, $selectedBadgeIds, $setId);
                return view('ec.confirmItems', [
                    'product' => $result['product'],
                    'badges' => $result['badges'],
                    'setId' => $setId
                ]);
            },
            'confirm_items_display',
            [
                'product_id' => $product->id,
                'selected_badge_ids' => $request->query('selectedBadges'),
                'set_id' => $request->query('setId')
            ]
        );
    }
}
