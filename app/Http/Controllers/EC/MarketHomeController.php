<?php

namespace App\Http\Controllers\EC;

use App\Models\Product;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarketHomeController extends Controller
{
    public function index(){

        $products=Product::all();

        $categories=$products->pluck('category')->toArray();
        $keywords=[];
       
        $keywords=Product::where('category','like',"%セット%")->get();
    
        return view('ec.MartIndex',compact('products','categories','keywords'));
    }

    

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $products=Product::all();
        $categories=$products->pluck('category')->toArray();
        $keywords=[];
       
        $keywords=Product::where('category','like',"%セット%")->get();
        $reviews = $product->reviews()->get();
 
        return view('ec.show', compact('product', 'reviews','categories','keywords'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Product $product )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function categorySearch($category)
    {
        $products=Product::where('category',$category)->get();
        
        return view('ec.categorySearch',compact('products','category'));
    }
}
