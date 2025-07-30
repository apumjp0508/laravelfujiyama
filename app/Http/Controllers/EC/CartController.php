<?php

namespace App\Http\Controllers\EC;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Services\CartService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use ErrorHandlingTrait;

    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $userId = Auth::user()->id;
                $data = $this->cartService->getCartViewData($userId);
                return view('cartsView.cartIndex', $data);
            },
            'cart_page_display',
            ['user_id' => Auth::user()->id ?? null]
        );
    }

    public function store(AddToCartRequest $request)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($request) {
                $userId = Auth::user()->id;
                $validated = $request->validated();
                $result = $this->cartService->addToCart($userId, $validated);
                
                // AJAX requestの場合はJSONを返す
                if ($request->expectsJson()) {
                    return response()->json($result);
                }
                
                // 通常のHTTPリクエストの場合はリダイレクト
                return redirect()->back()->with('success', $result['message']);
            },
            'add_to_cart',
            [
                'user_id' => Auth::user()->id ?? null,
                'validated_data' => $request->validated()
            ]
        );
    }

    public function update(UpdateCartItemRequest $request)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($request) {
                $userId = Auth::user()->id;
                $validated = $request->validated();
                $productId = $validated['product_id'];
                $qty = $validated['qty'];
                $result = $this->cartService->updateCartItem($userId, $productId, $qty);
                return response()->json($result);
            },
            'cart_item_update',
            [
                'user_id' => Auth::user()->id ?? null,
                'product_id' => $request->validated()['product_id'] ?? null,
                'qty' => $request->validated()['qty'] ?? null
            ]
        );
    }

    public function destroy($rowId)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($rowId) {
                $userId = Auth::user()->id;
                $this->cartService->removeCartItem($userId, $rowId);
                return redirect()->back()->with('success', '商品をカートから削除しました。');
            },
            'cart_item_removal',
            [
                'user_id' => Auth::user()->id ?? null,
                'row_id' => $rowId
            ]
        );
    }

    public function confirmItems()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $userId = Auth::user()->id;
                $data = $this->cartService->getCartViewData($userId);
                return view('ec.confirmItems', $data);
            },
            'cart_page_display',
            ['user_id' => Auth::user()->id ?? null]
        );
    }
}