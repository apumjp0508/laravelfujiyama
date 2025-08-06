<?php

namespace App\Repositories;

use App\Models\OrderItem;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    public function findById(int $id): ?OrderItem
    {
        return OrderItem::find($id);
    }
    
    public function findByAttributes(array $attributes): ?OrderItem
    {
        $query = OrderItem::query();
        
        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->first();
    }
    
    public function create(array $data): OrderItem
    {
        return OrderItem::create($data);
    }
    
    public function update(OrderItem $orderItem, array $data): bool
    {
        return $orderItem->update($data);
    }
    
    public function delete(OrderItem $orderItem): bool
    {
        return $orderItem->delete();
    }
    
    public function all(): Collection
    {
        return OrderItem::all();
    }
    
    public function findByUserId(int $userId): Collection
    {
        return OrderItem::where('user_id', $userId)->get();
    }
    
    public function findByUserIdWithProduct(int $userId): Collection
    {
        return OrderItem::where('user_id', $userId)->with('product')->get();
    }
    
    public function findByStatus(string $status): Collection
    {
        return OrderItem::where('statusItem', $status)->get();
    }
    
    public function updateStatus(int $orderItemId, string $status): bool
    {
        $orderItem = $this->findById($orderItemId);
        
        if (!$orderItem) {
            return false;
        }
        
        $orderItem->statusItem = $status;
        return $orderItem->save();
    }
    
    public function findPaidOrders(): Collection
    {
        return OrderItem::where('statusItem', 'paid')->get();
    }
    
    public function findShippedOrders(): Collection
    {
        return OrderItem::where('statusItem', 'shipped')->get();
    }
}