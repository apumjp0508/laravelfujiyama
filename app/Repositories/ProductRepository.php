<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }
    
    public function findByAttributes(array $attributes): ?Product
    {
        $query = Product::query();
        
        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->first();
    }
    
    public function create(array $data): Product
    {
        return Product::create($data);
    }
    
    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }
    
    public function delete(Product $product): bool
    {
        return $product->delete();
    }
    
    public function all(): Collection
    {
        return Product::all();
    }
    
    public function whereIn(string $column, array $values): Collection
    {
        return Product::whereIn($column, $values)->get();
    }
    
    public function findByIds(array $ids): Collection
    {
        return Product::whereIn('id', $ids)->get();
    }
    
    public function searchByName(string $keyword): Collection
    {
        return Product::where('name', 'like', "%{$keyword}%")->get();
    }
    
    public function findByCategory(string $category): Collection
    {
        return Product::where('category', 'like', "%{$category}%")->get();
    }
    
    public function findByCategories(array $categories): Collection
    {
        return Product::whereIn('category', $categories)->get();
    }
    
    public function updateStock(int $productId, int $quantity): bool
    {
        $product = $this->findById($productId);
        
        if (!$product) {
            return false;
        }
        
        $product->stock = $quantity;
        return $product->save();
    }
    
    public function decreaseStock(int $productId, int $quantity): bool
    {
        $product = $this->findById($productId);
        
        if (!$product || $product->stock < $quantity) {
            return false;
        }
        
        $product->stock -= $quantity;
        return $product->save();
    }
}