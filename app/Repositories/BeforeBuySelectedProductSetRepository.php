<?php

namespace App\Repositories;

use App\Models\BeforeBuySelectedProductSet;
use App\Repositories\Contracts\BeforeBuySelectedProductSetRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BeforeBuySelectedProductSetRepository implements BeforeBuySelectedProductSetRepositoryInterface
{
    public function findById(int $id): ?BeforeBuySelectedProductSet
    {
        return BeforeBuySelectedProductSet::find($id);
    }
    
    public function findByAttributes(array $attributes): ?BeforeBuySelectedProductSet
    {
        $query = BeforeBuySelectedProductSet::query();
        
        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->first();
    }
    
    public function create(array $data): BeforeBuySelectedProductSet
    {
        return BeforeBuySelectedProductSet::create($data);
    }
    
    public function update(BeforeBuySelectedProductSet $item, array $data): bool
    {
        return $item->update($data);
    }
    
    public function delete(BeforeBuySelectedProductSet $item): bool
    {
        return $item->delete();
    }
    
    public function all(): Collection
    {
        return BeforeBuySelectedProductSet::all();
    }
    
    public function findByUserId(int $userId): Collection
    {
        return BeforeBuySelectedProductSet::where('user_id', $userId)->get();
    }
    
    public function getByProductAndUser(int $productId, int $userId): array
    {
        return BeforeBuySelectedProductSet::where('product_id', $productId)
            ->where('user_id', $userId)
            ->pluck('product_set_id')
            ->toArray();
    }
    
    public function deleteByUserId(int $userId): bool
    {
        return BeforeBuySelectedProductSet::where('user_id', $userId)->delete();
    }
}