<?php

namespace App\Http\Controllers\EC;

use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Badge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // ログインしていなければログインページへリダイレクト
        }
        
        $cart = Cart::instance(Auth::user()->id)->content();
      
        $userId = Auth::user()->id;
       
        $total = 0;

        foreach ($cart as $c) {
            $total += $c->qty * $c->price;
        }
        $products=Product::all();

        $categories=$products->pluck('category')->toArray();
        $keywords=[];
       
        $keywords=Product::where('category','like',"%セット%")->get();
       
       

        return view('cartsView.cartIndex', compact('userId','cart', 'total','products','keywords','categories'));
    }

    public function store(Request $request)
    {
        Cart::instance(Auth::id())->add(
            [
                'id' => $request->id, 
                'name' => $request->name, 
                'qty' => $request->qty, 
                'price' => $request->price, 
                'weight' => $request->weight, 
                'options' => [
                'img'=>$request->img,
                'setNum'=>$request->setNum,
                'productType'=>$request->productType,
                'selectedBadges'=>$request->selectedBadges
                ]
            ] 
        );
        
        return response()->json([
            'success' => true,
            'message' => 'カートに追加しました！',
            'cart_count' => Cart::instance(Auth::id())->count(), // カート内の商品数を返す
        ]);

    }

  
   public function update(Request $request)
{
    $productId = $request->product_id;
    $qty = $request->qty;

    // カートの該当商品を取得
    $cartItem = Cart::instance(Auth::user()->id)->content()->where('id', $productId)->first();

    if ($cartItem) {
        if ($qty == 0) {
            // 数量が0の場合はカートから削除
            Cart::instance(Auth::user()->id)->remove($cartItem->rowId);
        } else {
            // 数量が0でない場合は更新
            Cart::instance(Auth::user()->id)->update($cartItem->rowId, $qty);
        }
    }

    
    // 商品の新しい合計金額
    $product = Product::find($productId);
    $productTotal = $product->price * $qty;

   
    // カート全体の合計金額を再計算
    /*失敗例::これはクエリビルだではない、Gloudemans\Shoppingcart ライブラリ のメソッドdearu 
    $cartTotal = Cart::content()->reduce(function ($total, $cartItem) {
        return $total + ($cartItem->price * $cartItem->qty);
    }, 0);
    */
    $cartTotal = Cart::instance(Auth::user()->id)->content()->sum(function ($cartItem) {
        // qtyが0の場合、その商品は合計金額に含めない
        return $cartItem->qty > 0 ? $cartItem->price * $cartItem->qty : 0;
    });
    

    return response()->json([
        'success' => true,
        'product_total' => $productTotal,
        'cart_total' => $cartTotal
    ]);
    
}

public function destroy($rowId)
    {
        
        Cart::instance(Auth::user()->id);

        Cart::remove($rowId);
        return back();
    }

    public function confirmItems(Product $product){
        $user=Auth::user();
        return view('ec.confirmItems');
    }
}

