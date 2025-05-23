<?php

namespace App\Http\Controllers\admin_items;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;/*extends Controllerというのはココを
参照しているnamespaceを変えたら要注意
*/
class insert_items_Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::all();

        return view('manageView.index' ,compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manageView.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'setNum' => $request->input('setNum') === '' ? null : $request->input('setNum'),
        ]);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category'=>'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'productType'=>'required|string',
            'setNum'=>'nullable|integer'
        ]);
        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('public/images'); // storage/app/public/images/ に保存
            $validated['img'] = str_replace('public/', 'storage/', $path); // 表示用のパスに変換
        }
        Product::create($validated);

        return to_route('admin.products.list');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        dump($product);
        return view('manageView.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->merge([
            'setNum' => $request->input('setNum') === '' ? null : $request->input('setNum'),
        ]);
        $validated=$request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category'=>'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'productType'=>'required|string',
            'setNum'=>'nullable|integer'
        ]);

        if ($request->hasFile('img')) {
            // 古い画像があれば削除
            if ($product->img) {
                $oldImagePath = str_replace('storage/', 'public/', $product->img);
                Storage::delete($oldImagePath);
            }
            $path = $request->file('img')->store('public/images'); // storage/app/public/images/ に保存
            $validated['img'] = str_replace('public/', 'storage/', $path); // 表示用のパスに変換
        }
        $product->update($validated);

        return to_route('admin.products.list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return to_route('admin.products.list');
    }

    public function adminReview(Product $product){
        $reviews = $product->reviews()->get();

        return view('manageView.review',compact('product','reviews'));
    }

    public function deleteReview(Review $review){
        $review->delete();
        return back();
    }
}
