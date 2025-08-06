<?php

namespace App\Repositories\Contracts;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;

interface OrderItemRepositoryInterface
{
    public function findById(int $id): ?OrderItem;
    
    public function findByAttributes(array $attributes): ?OrderItem;
    
    public function create(array $data): OrderItem;
    
    public function update(OrderItem $orderItem, array $data): bool;
    
    public function delete(OrderItem $orderItem): bool;
    
    public function all(): Collection;
    
    public function findByUserId(int $userId): Collection;
    
    public function findByUserIdWithProduct(int $userId): Collection;
    
    public function findByStatus(string $status): Collection;
    
    public function updateStatus(int $orderItemId, string $status): bool;
    
    public function findPaidOrders(): Collection;
    
    public function findShippedOrders(): Collection;
}