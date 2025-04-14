<?php

namespace App\Http\Controllers\Admin;
use App\Models\Order;
use App\Models\Badge;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfirmOrderController extends Controller
{
    public function index(){
        
        $orderItems=OrderItem::where('statusItem','paid')->get();

        
    // 二重配列に整形
    $selectedBadgesArray = $orderItems->map(function ($item) {
        return is_array($item->selected_badges)
            ? $item->selected_badges
            : json_decode($item->selected_badges, true) ?? [];
    })->all(); // ->all() でコレクションから通常の配列に変換
    
        return view('manageView.confirmOrder',compact('orderItems','selectedBadgesArray'));
    }

    public function shipping(OrderItem $orderItem){
        
        $orderItem->statusItem='shipped';
        $orderItem->save();
        return to_route('order.index');
    }

    public function shipped(){
        $orderItems=OrderItem::where('statusItem','shipped')->get();

        return view('manageView.shipped',compact('orderItems'));
    }

    public function confirmSet(OrderItem $orderItem){
        $selectedBadges=[];
        
        $Badges=$orderItem->selected_badges;
        foreach($Badges as $Badge){
            $selectedBadges[]=Badge::find($Badge);
        }
       
        return view('manageView.confirmSelectedBadges',compact('selectedBadges'));
    }
}
