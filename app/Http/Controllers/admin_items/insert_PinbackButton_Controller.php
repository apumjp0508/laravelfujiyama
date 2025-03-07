<?php

namespace App\Http\Controllers\admin_items;
use App\Models\Product;
use App\Models\Badge;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class insert_PinbackButton_Controller extends Controller
{
    public function index()
    {
        $badges=Badge::all();

        return view('badge.index',compact('badges'));
    }

    public function create(){
        return view('badge.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('public/images'); // storage/app/public/images/ に保存
            $validated['img'] = str_replace('public/', 'storage/', $path); // 表示用のパスに変換
        }
        Badge::create($validated);

        return to_route('badge.index');
    }

    public function edit(Badge $badge)
    {
        return view('badge.edit', compact('badge'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Badge $badge)
    {
        $validated=$request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('img')) {
            // 古い画像があれば削除
            if ($badge->img) {
                $oldImagePath = str_replace('storage/', 'public/', $batch->img);
                Storage::delete($oldImagePath);
            }
            $path = $request->file('img')->store('public/images'); // storage/app/public/images/ に保存
            $validated['img'] = str_replace('public/', 'storage/', $path); // 表示用のパスに変換
        }
        $badge->update($validated);

        return to_route('badge.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Badge $badge)
    {
        $badge->delete();
        return to_route('badge.index');
    }

}
