<?php

namespace App\Http\Controllers\EC;

use App\Models\Product;
use App\Models\Badge;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfirmItemsController extends Controller
{
    public function confirmItems(Request $request, Product $product)
{
    $selectedBadgeIds = $request->query('selectedBadges'); // クエリパラメータから取得
    $badges = Badge::whereIn('id', $selectedBadgeIds)->get(); // 選択されたIDに対応するデータを取得

    return view('ec.confirmItems', compact('product', 'badges'));
}

    
    }
