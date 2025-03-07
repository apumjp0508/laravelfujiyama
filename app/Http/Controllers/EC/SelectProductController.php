<?php

namespace App\Http\Controllers\EC;
use App\Models\Product;
use App\Models\Badge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SelectProductController extends Controller
{
    public function index(Product $product){
        $badges=Badge::all();

        preg_match_all('/\d+/', $product->category, $matches);
        $categoryNumbers = $matches[0]; // 数字を格納
        $categoryNumber=$categoryNumbers[0];
        return view('ec.select',compact('badges','product','categoryNumber'));
    }

    public function store(Request $request){
        $selectProduct=$request->select;

        return view('ec.show');
    }

}
