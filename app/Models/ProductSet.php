<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSet extends Model
{
    protected $table = 'product_sets';
    protected $fillable = ['name', 'description', 'stock', 'img', 'widthSize', 'heightSize', 'product_id'];
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'selectedBadge');
    }
    
    // New relationship for product_id functionality
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
