<?php

namespace App\Http\Controllers\EC;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\SelectProductService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelectProductController extends Controller
{
    use ErrorHandlingTrait;

    protected $selectProductService;

    public function __construct(SelectProductService $selectProductService)
    {
        $this->selectProductService = $selectProductService;
    }

    public function index(Product $product)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($product) {
                $data = $this->selectProductService->getProductSetsAndUser($product);
                return view('ec.select', $data);
            },
            'product_selection_page_display',
            ['user_id' => Auth::user()->id ?? null, 'product_id' => $product->id]
        );
    }

    public function store(Request $request)
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request) {
                $selectedProductSetIds = $request->input('selected_product_sets', []);
                
                $productId = $request->input('product_id');
                $userId = Auth::user()->id;
                
                // Generate a unique set ID for this product set selection
                $setId = uniqid('set_' . $productId . '_' . $userId . '_');
                
                $this->selectProductService->createSelectedProductSets($selectedProductSetIds, $productId, $userId, $setId);
                
                return redirect()->route('mart.show', $productId)->with('success', 'プロダクトセットを選択しました。');
            },
            'selected_product_sets_creation',
            [
                'user_id' => Auth::user()->id ?? null,
                'product_id' => $request->input('product_id'),
                'selected_product_set_ids' => $request->input('selected_product_sets', [])
            ]
        );
    }
}


