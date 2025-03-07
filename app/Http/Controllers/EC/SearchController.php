<?php

namespace App\Http\Controllers\EC;
use App\Models\Product; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function search(Request $request){
        $keyword=$request->keyword;
        if($keyword!==null){
            $products=Product::where('name','like',"%{$keyword}%")->get();
        }else{
            $products=null;
        }
        
        return view('ec.search', compact('products','keyword'));
    }
}
