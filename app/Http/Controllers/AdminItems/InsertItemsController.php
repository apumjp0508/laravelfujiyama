<?php

namespace App\Http\Controllers\AdminItems;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\Review;
use App\Services\InsertItemsService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;

class InsertItemsController extends Controller
{
    use ErrorHandlingTrait;

    protected $insertItemsService;

    public function __construct(InsertItemsService $insertItemsService)
    {
        $this->insertItemsService = $insertItemsService;
    }

    public function index()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $products = $this->insertItemsService->getAllProducts();
                return view('manageView.index', compact('products'));
            },
            'product_retrieval'
        );
    }

    public function create()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                return view('manageView.create');
            },
            'product_creation_page_display'
        );
    }

    public function store(StoreProductRequest $request)
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request) {
                $validated = $request->validated();
                $validated['img'] = $this->insertItemsService->handleImageUpload($request);
                $this->insertItemsService->createProduct($validated);
                return to_route('admin.products.list');
            },
            'product_creation',
            ['validated_data' => $request->validated()]
        );
    }

    public function edit(Product $product)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($product) {
                return view('manageView.edit', compact('product'));
            },
            'product_edit_page_display',
            ['product_id' => $product->id]
        );
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request, $product) {
                $validated = $request->validated();
                $validated['img'] = $this->insertItemsService->handleImageUpload($request, $product);
                $this->insertItemsService->updateProduct($product, $validated);
                return to_route('admin.products.list');
            },
            'product_update',
            [
                'product_id' => $product->id,
                'validated_data' => $request->validated()
            ]
        );
    }

    public function destroy(Product $product)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($product) {
                $this->insertItemsService->deleteProduct($product);
                return redirect()->back()->with('success', '商品を削除しました。');
            },
            'product_deletion',
            ['product_id' => $product->id]
        );
    }

    public function review(Product $product)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($product) {
                $reviews = $this->insertItemsService->getProductReviews($product);
                return view('manageView.review', compact('product', 'reviews'));
            },
            'product_review_retrieval',
            ['product_id' => $product->id]
        );
    }

    public function deleteReview(Review $review)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($review) {
                $this->insertItemsService->deleteReview($review);
                return redirect()->back()->with('success', 'レビューを削除しました。');
            },
            'product_review_deletion',
            [
                'review_id' => $review->id,
                'product_id' => $review->product_id
            ]
        );
    }
}
