<?php

namespace App\Http\Controllers\EC;

use App\Models\Product;
use App\Models\Badge;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfirmItemsController extends Controller
{
    public function confirmItems(Product $product){
        return view('ec.confirmItems',compact());
    }
}
