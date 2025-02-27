<?php

namespace App\Http\Controllers;
use App\Models\Product; 
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request){
        $keyword=$request->keyword;
        if($keyword!==null){
            $products=Product::where('name','like',"%{$keyword}%")->get();
        }else{
            $products=null;
        }
        
        return view('mart.search', compact('products','keyword'));
    }
}
