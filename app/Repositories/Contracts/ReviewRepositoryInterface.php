<?php

namespace App\Repositories\Contracts;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

interface ReviewRepositoryInterface
{
    public function findById(int $id): ?Review;
    
    public function findByAttributes(array $attributes): ?Review;
    
    public function create(array $data): Review;
    
    public function update(Review $review, array $data): bool;
    
    public function delete(Review $review): bool;
    
    public function all(): Collection;
    
    public function findByProductId(int $productId): Collection;
    
    public function findByUserId(int $userId): Collection;
}