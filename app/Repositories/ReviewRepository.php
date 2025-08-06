<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function findById(int $id): ?Review
    {
        return Review::find($id);
    }
    
    public function findByAttributes(array $attributes): ?Review
    {
        $query = Review::query();
        
        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->first();
    }
    
    public function create(array $data): Review
    {
        return Review::create($data);
    }
    
    public function update(Review $review, array $data): bool
    {
        return $review->update($data);
    }
    
    public function delete(Review $review): bool
    {
        return $review->delete();
    }
    
    public function all(): Collection
    {
        return Review::all();
    }
    
    public function findByProductId(int $productId): Collection
    {
        return Review::where('product_id', $productId)->get();
    }
    
    public function findByUserId(int $userId): Collection
    {
        return Review::where('user_id', $userId)->get();
    }
}