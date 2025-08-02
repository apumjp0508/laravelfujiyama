<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    

    protected $fillable = ['product_id','product_name','price','shipping_fee','quantity','user_id','total_price','statusItem','productType','selected_product_sets'];
    protected $casts = [
        'selected_product_sets' => 'array',
    ];


public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'id');
}

public function user()
{
    return $this->belongsTo(User::class);
}

}
