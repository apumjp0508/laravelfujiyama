<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }
    
    public function findByIdOrFail(int $id): User
    {
        return User::findOrFail($id);
    }
    
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
    
    public function findByAttributes(array $attributes): ?User
    {
        $query = User::query();
        
        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->first();
    }
    
    public function create(array $data): User
    {
        return User::create($data);
    }
    
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }
    
    public function delete(User $user): bool
    {
        return $user->delete();
    }
    
    public function all(): Collection
    {
        return User::all();
    }
    
    public function getUserOrders(int $userId): Collection
    {
        $user = $this->findById($userId);
        
        if (!$user) {
            return collect();
        }
        
        return $user->orderItems()->with('product')->get();
    }
    
    public function getUserFavoriteProducts(int $userId): Collection
    {
        $user = $this->findById($userId);
        
        if (!$user) {
            return collect();
        }
        
        return $user->favorite_products()->get();
    }
    
    public function addToFavorites(int $userId, int $productId): void
    {
        $user = $this->findById($userId);
        
        if ($user) {
            $user->favorite_products()->attach($productId);
        }
    }
    
    public function removeFromFavorites(int $userId, int $productId): void
    {
        $user = $this->findById($userId);
        
        if ($user) {
            $user->favorite_products()->detach($productId);
        }
    }
}