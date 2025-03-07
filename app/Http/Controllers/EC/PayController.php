<?php

namespace App\Http\Controllers\EC;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PayController extends Controller
{
    public function index(){
        if (!Auth::check()) {
            return redirect()->route('login'); // ログインしていなければログインページへリダイレクト
        }
        
        $cart = Cart::instance(Auth::user()->id)->content();

        $total = 0;

        foreach ($cart as $c) {
            $total += $c->qty * $c->price;
        }
        return view('pay.index',compact('cart','total'));
    }
}
