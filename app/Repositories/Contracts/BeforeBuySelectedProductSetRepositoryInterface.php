<?php

namespace App\Repositories\Contracts;

use App\Models\BeforeBuySelectedProductSet;
use Illuminate\Database\Eloquent\Collection;

interface BeforeBuySelectedProductSetRepositoryInterface
{
    public function findById(int $id): ?BeforeBuySelectedProductSet;
    
    public function findByAttributes(array $attributes): ?BeforeBuySelectedProductSet;
    
    public function create(array $data): BeforeBuySelectedProductSet;
    
    public function update(BeforeBuySelectedProductSet $item, array $data): bool;
    
    public function delete(BeforeBuySelectedProductSet $item): bool;
    
    public function all(): Collection;
    
    public function findByUserId(int $userId): Collection;
    
    public function getByProductAndUser(int $productId, int $userId): array;
    
    public function deleteByUserId(int $userId): bool;
}