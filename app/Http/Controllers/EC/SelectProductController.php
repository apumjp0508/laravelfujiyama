<?php

namespace App\Http\Controllers\EC;
use App\Models\Product;
use App\Models\SelectedBadge;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SelectProductController extends Controller
{
    public function index(Product $product){
        $badges=Badge::all();
        $user=Auth::user();
        
        return view('ec.select',compact('badges','product','user'));
    }

    public function store(Request $request)
    {
        // 選択されたバッジIDの配列
    
        $selectedBadges = $request->input('select', []); 
        $productId=$request->input('product_id');
        $userId=$request->input('user_id');
        $setId = Str::uuid();

        // 選択したバッジを保存する（例: `selected_badges` テーブルに保存）
        foreach ($selectedBadges as $badgeId) {
            SelectedBadge::create([
                'set_id'=>$setId,
                'badge_id' => $badgeId,
                'product_id'=>$productId,
                'user_id'=>$userId
            ]);
        }

        return redirect()->route('mart.show', ['product' => $productId ,'selectedBadges' => $selectedBadges,
        'userId' => $userId, 'setId' => $setId])->with('success', '選択したバッジを保存しました');
    }
}


