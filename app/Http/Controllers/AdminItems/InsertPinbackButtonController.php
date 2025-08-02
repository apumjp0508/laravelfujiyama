<?php

namespace App\Http\Controllers\AdminItems;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductSet\StoreProductSetRequest;
use App\Http\Requests\Admin\ProductSet\UpdateProductSetRequest;
use App\Models\ProductSet;
use App\Services\ProductSetService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;

class InsertPinbackButtonController extends Controller
{
    use ErrorHandlingTrait;

    protected $productSetService;

    public function __construct(ProductSetService $productSetService)
    {
        $this->productSetService = $productSetService;
    }

    public function index()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $productSets = $this->productSetService->getAllProductSets();
                return view('productSets.index', compact('productSets'));
            },
            'productSet_list_display'
        );
    }

    public function create()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                return view('productSets.create');
            },
            'productSet_creation_page_display'
        );
    }

    public function store(StoreProductSetRequest $request)
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request) {
                $validated = $request->validated();
                $validated['img'] = $this->productSetService->handleImageUpload($request);
                $this->productSetService->createProductSet($validated);
                return to_route('productSets.index');
            },
            'productSet_creation',
            ['validated_data' => $request->validated()]
        );
    }

    public function edit(ProductSet $productSet)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($productSet) {
                return view('productSets.edit', compact('productSet'));
            },
            'productSet_edit_page_display',
            ['productSet_id' => $productSet->id]
        );
    }

    public function update(UpdateProductSetRequest $request, ProductSet $productSet)
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request, $productSet) {
                $validated = $request->validated();
                $validated['img'] = $this->productSetService->handleImageUpload($request, $productSet);
                $this->productSetService->updateProductSet($productSet, $validated);
                return to_route('productSets.index');
            },
            'productSet_update',
            [
                'productSet_id' => $productSet->id,
                'validated_data' => $request->validated()
            ]
        );
    }

    public function destroy(ProductSet $productSet)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($productSet) {
                $this->productSetService->deleteProductSet($productSet);
                return to_route('productSets.index');
            },
            'productSet_deletion',
            ['productSet_id' => $productSet->id]
        );
    }
}
