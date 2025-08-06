<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description','category', 'price', 'shipping_fee', 'stock','productType','setNum', 'img'];
    
    use HasFactory;

    public function favorited_users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }
    
    // New relationship for product_id functionality in ProductSets
    public function productSets()
    {
        return $this->hasMany(ProductSet::class);
    }
}
