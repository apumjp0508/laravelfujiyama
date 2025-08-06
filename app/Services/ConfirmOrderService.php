<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\ProductSet;
use App\Models\User;
use App\Traits\ErrorHandlingTrait;

class ConfirmOrderService
{
    use ErrorHandlingTrait;

    public function getPaidOrderItemsWithProductSets()
    {
        return $this->executeWithErrorHandling(
            function() {
                return OrderItem::where('statusItem', 'paid')
                    ->with('user')
                    ->get();
            },
            'paid_order_retrieval'
        );
    }

    public function shipOrderItem($orderItemId)
    {
        return $this->executeWithErrorHandling(
            function() use ($orderItemId) {
                $orderItem = OrderItem::findOrFail($orderItemId);
                $orderItem->update(['statusItem' => 'shipped']);
                return $orderItem;
            },
            'order_item_shipping',
            ['order_item_id' => $orderItemId]
        );
    }

    public function getShippedOrderItems()
    {
        return $this->executeWithErrorHandling(
            function() {
                return OrderItem::where('statusItem', 'shipped')
                    ->with('user')
                    ->get();
            },
            'shipped_order_retrieval'
        );
    }

    public function getSelectedProductSetsForOrderItem($orderItemId)
    {
        return $this->executeWithErrorHandling(
            function() use ($orderItemId) {
                $orderItem = OrderItem::findOrFail($orderItemId);
                $selectedProductSetIds = $orderItem->selected_product_sets;
                
                if (!$selectedProductSetIds || empty($selectedProductSetIds)) {
                    return collect();
                }
                
                return ProductSet::whereIn('id', $selectedProductSetIds)->get();
            },
            'selected_product_sets_retrieval',
            ['order_item_id' => $orderItemId]
        );
    }



    public function markAsUnshipped($orderItemId)
    {
        return $this->executeWithErrorHandling(
            function() use ($orderItemId) {
                $orderItem = OrderItem::findOrFail($orderItemId);
                $orderItem->update(['statusItem' => 'paid']);
                return true;
            },
            'order_item_paid_deletion',
            ['order_item_id' => $orderItemId]
        );
    }

    public function getBuyerDetails($userId)
    {
        return $this->executeWithErrorHandling(
            function() use ($userId) {
                return User::findOrFail($userId);
            },
            'buyer_details_retrieval',
            ['user_id' => $userId]
        );
    }
}