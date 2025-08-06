<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Repositories\Contracts\AdminRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AdminRepository implements AdminRepositoryInterface
{
    public function findById(int $id): ?Admin
    {
        return Admin::find($id);
    }
    
    public function findByEmail(string $email): ?Admin
    {
        return Admin::where('email', $email)->first();
    }
    
    public function findByAttributes(array $attributes): ?Admin
    {
        $query = Admin::query();
        
        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->first();
    }
    
    public function create(array $data): Admin
    {
        return Admin::create($data);
    }
    
    public function update(Admin $admin, array $data): bool
    {
        return $admin->update($data);
    }
    
    public function delete(Admin $admin): bool
    {
        return $admin->delete();
    }
    
    public function all(): Collection
    {
        return Admin::all();
    }
}