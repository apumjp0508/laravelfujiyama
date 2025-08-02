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
                
                $selectedProductSetIds = $request->query('selectedProductSets');
                $setId = $request->query('setId');
                // If setId is provided, clear previous selected product sets for this set and update with new selection
                if ($setId !== null && !empty($selectedProductSetIds)) {
                    $this->confirmItemsService->updateSelectedProductSetsForSet($product->id, $selectedProductSetIds, $setId);
                }
      
                $result = $this->confirmItemsService->getConfirmItemsData($product->id, $selectedProductSetIds, $setId);
                return view('ec.confirmItems', [
                    'product' => $result['product'],
                    'productSets' => $result['productSets'],
                    'setId' => $setId
                ]);
            },
            'confirm_items_display',
            [
                'product_id' => $product->id,
                'selected_product_set_ids' => $request->query('selectedProductSets'),
                'set_id' => $request->query('setId')
            ]
        );
    }
}
