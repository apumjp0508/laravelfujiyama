<?php

namespace App\Repositories;

use App\Models\ProductSet;
use App\Repositories\Contracts\ProductSetRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductSetRepository implements ProductSetRepositoryInterface
{
    public function findById(int $id): ?ProductSet
    {
        return ProductSet::find($id);
    }
    
    public function findByAttributes(array $attributes): ?ProductSet
    {
        $query = ProductSet::query();
        
        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->first();
    }
    
    public function create(array $data): ProductSet
    {
        return ProductSet::create($data);
    }
    
    public function update(ProductSet $productSet, array $data): bool
    {
        return $productSet->update($data);
    }
    
    public function delete(ProductSet $productSet): bool
    {
        return $productSet->delete();
    }
    
    public function all(): Collection
    {
        return ProductSet::all();
    }
    
    public function findByName(string $name): ?ProductSet
    {
        return ProductSet::where('name', $name)->first();
    }
    
    // New methods for product_id functionality
    public function findByProductId(int $productId): Collection
    {
        return ProductSet::where('product_id', $productId)->get();
    }
    
    public function findByProductIds(array $productIds): Collection
    {
        return ProductSet::whereIn('product_id', $productIds)->get();
    }
    
    public function getAllForProduct(int $productId): Collection
    {
        // For backward compatibility, if product_id is null, return all
        // If product_id is specified, return only those for the specific product
        return ProductSet::where(function($query) use ($productId) {
            $query->where('product_id', $productId)
                  ->orWhereNull('product_id');
        })->get();
    }
}