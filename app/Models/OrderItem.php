<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    

    protected $fillable = ['product_id','product_name','price','shipping_fee','quantity','user_id','total_price','statusItem','productType','selected_badges'];
    protected $casts = [
        'selected_badges' => 'array',
    ];


public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'id');
}

}
