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
                $setId = $request->query('setId');
                $result = $this->confirmItemsService->getConfirmItemsData($product->id, $selectedBadgeIds, $setId);
                return view('ec.confirmItems', [
                    'product' => $result['product'],
                    'badges' => $result['badges']
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
