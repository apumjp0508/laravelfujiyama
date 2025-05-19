<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['name', 'description', 'stock', 'img', 'widthSize', 'heightSize'];
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'selectedBadge');
    }
}
