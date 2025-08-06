<?php

namespace App\Repositories\Contracts;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Collection;

interface AdminRepositoryInterface
{
    public function findById(int $id): ?Admin;
    
    public function findByEmail(string $email): ?Admin;
    
    public function findByAttributes(array $attributes): ?Admin;
    
    public function create(array $data): Admin;
    
    public function update(Admin $admin, array $data): bool;
    
    public function delete(Admin $admin): bool;
    
    public function all(): Collection;
}