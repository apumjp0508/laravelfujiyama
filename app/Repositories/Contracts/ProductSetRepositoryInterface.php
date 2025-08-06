<?php

namespace App\Repositories\Contracts;

use App\Models\ProductSet;
use Illuminate\Database\Eloquent\Collection;

interface ProductSetRepositoryInterface
{
    public function findById(int $id): ?ProductSet;
    
    public function findByAttributes(array $attributes): ?ProductSet;
    
    public function create(array $data): ProductSet;
    
    public function update(ProductSet $productSet, array $data): bool;
    
    public function delete(ProductSet $productSet): bool;
    
    public function all(): Collection;
    
    public function findByName(string $name): ?ProductSet;
    
    // New methods for product_id functionality
    public function findByProductId(int $productId): Collection;
    
    public function findByProductIds(array $productIds): Collection;
    
    public function getAllForProduct(int $productId): Collection;
}