<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeforeBuySelectedProductSet extends Model
{
    use HasFactory;
    protected $table = 'before_buy_selected_product_sets';
    protected $fillable = ['product_id', 'product_set_id','user_id', 'widthSize', 'heightSize', 'set_id'];
    
    public function productSet()
    {
        return $this->belongsTo(ProductSet::class);
    }
    

}
