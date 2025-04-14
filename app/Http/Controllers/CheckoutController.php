<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::instance(Auth::user()->id)->content();
        dd($cart);
        $total = 0;
        $has_carriage_cost = false;
        $carriage_cost = 0;

        foreach ($cart as $c) {
            $total += $c->qty * $c->price;
            if ($c->options->carriage) {
                $has_carriage_cost = true;
            }
        }

        if($has_carriage_cost) {
            $total += env('CARRIAGE');
            $carriage_cost = env('CARRIAGE');
        }

        return view('pay.index', compact('cart', 'total', 'carriage_cost'));
    }

    public function store(Request $request)
    {
        $cart = Cart::instance(Auth::user()->id)->content();

        $has_carriage_cost = false;

        foreach ($cart as $product) {
            if ($product->options->carriage) {
                $has_carriage_cost = true;
            }
        }

        

        $line_items = [];

        foreach ($cart as $product) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price,
                ],
                'quantity' => $product->qty,
            ];
        }

        if ($has_carriage_cost) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => '送料',
                    ],
                    'unit_amount' => env('CARRIAGE'),
                ],
                'quantity' => 1,
            ];
        }

     /*   stripe実装部分
     $checkout_session = Session::create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.index'),
        ]);
        
        return redirect($checkout_session->url);
        */
    }

    public function success(){
    $user_shoppingcarts = DB::table('shoppingcart')->get();
    $number = DB::table('shoppingcart')->where('instance', Auth::user()->id)->count();

    $count = $user_shoppingcarts->count();

    $count += 1;
    $number += 1;
    $cart = Cart::instance(Auth::user()->id)->content();

    //numberとcountの意義がわからない
    $price_total = 0;
    $qty_total = 0;
    $has_carriage_cost = false;

    $cartItems = Cart::content(); // カート内の全アイテム
    $cartIds = $cartItems->pluck('id')->toArray(); // カート内の商品IDだけを取得
    $products = Product::whereIn('id', $cartIds)->get(); // 該当する商品を取得
    
    foreach ($products as $product) {
        // 対象の商品に一致するカートアイテムを取得
        $cartItem = $cartItems->firstWhere('id', $product->id);
        if ($cartItem) {
            $quantityInCart = $cartItem->qty;
            $product->stock -= $quantityInCart; // 在庫を減らす
            $product->save(); // 保存
        }
    }
    


    foreach ($cart as $c) {
        $price_total += $c->qty * $c->price;
        $qty_total += $c->qty;
        if ($c->options->carriage) {
            $has_carriage_cost = true;
        }
    }

    if($has_carriage_cost) {
        $price_total += env('CARRIAGE');
    }


$userid=auth()->id();

    foreach (Cart::content() as $product) {
        $orederItems=OrderItem::create([
            'product_id'   => $product->id,
            'product_name' => $product->name,
            'price'        => $product->price,
            'quantity'     => $product->qty,
            'user_id'=>$userid,
            'total_price' => $price_total,
            'statusItem'      => 'paid',
            'productType'   =>$product->options->productType,
            'selected_badges' => $product->options->selectedBadges ?? [],
                ]);
    }



    Cart::instance(Auth::user()->id)->destroy();

    return view('checkout.success');
}
}
