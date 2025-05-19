<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedBadge extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'badge_id','user_id', 'widthSize', 'heightSize'];
    
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'selectedBadge');
    }
    

}
