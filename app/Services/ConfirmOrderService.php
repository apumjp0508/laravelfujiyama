<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\SelectedBadge;
use App\Traits\ErrorHandlingTrait;

class ConfirmOrderService
{
    use ErrorHandlingTrait;

    public function getPaidOrderItemsWithBadges()
    {
        return $this->executeWithErrorHandling(
            function() {
                return OrderItem::where('statusItem', 'paid')
                    ->with('user')
                    ->get()
                    ->map(function ($orderItem) {
                        $orderItem->selected_badges = json_decode($orderItem->selected_badges, true);
                        return $orderItem;
                    });
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
                    ->get()
                    ->map(function ($orderItem) {
                        $orderItem->selected_badges = json_decode($orderItem->selected_badges, true);
                        return $orderItem;
                    });
            },
            'shipped_order_retrieval'
        );
    }

    public function getSelectedBadgesForOrderItem($orderItemId)
    {
        return $this->executeWithErrorHandling(
            function() use ($orderItemId) {
                $orderItem = OrderItem::findOrFail($orderItemId);
                $selectedBadges = json_decode($orderItem->selected_badges, true);
                
                if (!$selectedBadges) {
                    return collect();
                }
                
                return SelectedBadge::whereIn('id', $selectedBadges)->get();
            },
            'selected_badges_retrieval',
            ['order_item_id' => $orderItemId]
        );
    }
}