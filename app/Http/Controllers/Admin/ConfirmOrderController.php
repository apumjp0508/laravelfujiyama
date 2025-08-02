<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Services\ConfirmOrderService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;

class ConfirmOrderController extends Controller
{
    use ErrorHandlingTrait;

    protected $confirmOrderService;

    public function __construct(ConfirmOrderService $confirmOrderService)
    {
        $this->confirmOrderService = $confirmOrderService;
    }

    public function index()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $orderItems = $this->confirmOrderService->getPaidOrderItemsWithProductSets();
                return view('manageView.confirmOrder', compact('orderItems'));
            },
            'order_confirmation_page_display'
        );
    }

    public function shipping($orderItemId)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($orderItemId) {
                $this->confirmOrderService->shipOrderItem($orderItemId);
                return redirect()->back()->with('success', '商品を発送しました。');
            },
            'order_item_shipping',
            ['order_item_id' => $orderItemId]
        );
    }

    public function shipped()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $orderItems = $this->confirmOrderService->getShippedOrderItems();
                return view('manageView.shipped', compact('orderItems'));
            },
            'shipped_orders_page_display'
        );
    }

    public function confirmSet($orderItemId)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($orderItemId) {
                $selectedProductSets = $this->confirmOrderService->getSelectedProductSetsForOrderItem($orderItemId);
                return view('manageView.confirmSelectedProductSets', compact('selectedProductSets'));
            },
            'selected_product_sets_page_display',
            ['order_item_id' => $orderItemId]
        );
    }
}
