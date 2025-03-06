<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description','category', 'price', 'stock', 'img'];
    
    use HasFactory;

    public function favorited_users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
