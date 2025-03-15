<?php

namespace App\Http\Controllers\EC;
use App\Models\Product;
use App\Models\SelectedBadge;
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

    public function store(Request $request)
    {
        // 選択されたバッジIDの配列
        $selectedBadges = $request->input('select', []); 
        $productId=$request->input('product_id');
        // デバッグ用: 選択されたIDを表示
      

        // 選択したバッジを保存する（例: `selected_badges` テーブルに保存）
        foreach ($selectedBadges as $badgeId) {
            SelectedBadge::create([
                'badge_id' => $badgeId,
                'product_id'=>$productId,
                'user_id'=>$userId
            ]);
        }

        return redirect()->route('mart.show', ['product' => $productId])->with('success', '選択したバッジを保存しました');
    }
}


