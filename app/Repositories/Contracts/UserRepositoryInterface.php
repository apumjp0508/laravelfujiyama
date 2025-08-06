<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    
    public function findByIdOrFail(int $id): User;
    
    public function findByEmail(string $email): ?User;
    
    public function findByAttributes(array $attributes): ?User;
    
    public function create(array $data): User;
    
    public function update(User $user, array $data): bool;
    
    public function delete(User $user): bool;
    
    public function all(): Collection;
    
    public function getUserOrders(int $userId): Collection;
    
    public function getUserFavoriteProducts(int $userId): Collection;
    
    public function addToFavorites(int $userId, int $productId): void;
    
    public function removeFromFavorites(int $userId, int $productId): void;
}