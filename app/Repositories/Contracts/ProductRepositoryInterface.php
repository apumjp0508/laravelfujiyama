<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;
    
    public function findByAttributes(array $attributes): ?Product;
    
    public function create(array $data): Product;
    
    public function update(Product $product, array $data): bool;
    
    public function delete(Product $product): bool;
    
    public function all(): Collection;
    
    public function whereIn(string $column, array $values): Collection;
    
    public function findByIds(array $ids): Collection;
    
    public function searchByName(string $keyword): Collection;
    
    public function findByCategory(string $category): Collection;
    
    public function findByCategories(array $categories): Collection;
    
    public function updateStock(int $productId, int $quantity): bool;
    
    public function decreaseStock(int $productId, int $quantity): bool;
}