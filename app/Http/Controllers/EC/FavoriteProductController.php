<?php

namespace App\Http\Controllers\EC;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class FavoriteProductController extends Controller
{
    public function show(){
        $products = Auth::user()->favorite_products()->get();
        return view('ec.favorite',compact('products'));
    }

    public function store($product_id)
    {
        Auth::user()->favorite_products()->attach($product_id);

        return back();
    }

    public function destroy($product_id)
    {
        Auth::user()->favorite_products()->detach($product_id);

        return back();
    }
}
