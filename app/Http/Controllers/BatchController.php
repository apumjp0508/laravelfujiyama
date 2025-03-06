<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Batch;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        $batches=Batch::all();

        return view('batch.index',compact('batches'));
    }

    public function create(){
        return view('batch.create');
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
        Batch::create($validated);

        return to_route('batch.index');
    }

    public function edit(Batch $batch)
    {
        return view('batch.edit', compact('batch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Batch $batch)
    {
        $validated=$request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('img')) {
            // 古い画像があれば削除
            if ($batch->img) {
                $oldImagePath = str_replace('storage/', 'public/', $batch->img);
                Storage::delete($oldImagePath);
            }
            $path = $request->file('img')->store('public/images'); // storage/app/public/images/ に保存
            $validated['img'] = str_replace('public/', 'storage/', $path); // 表示用のパスに変換
        }
        $batch->update($validated);

        return to_route('batch.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();
        return to_route('batch.index');
    }

}
