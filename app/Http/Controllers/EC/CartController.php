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

        
       
        $total = 0;

        foreach ($cart as $c) {
            $total += $c->qty * $c->price;
        }
        $products=Product::all();

        $categories=$products->pluck('category')->toArray();
        $keywords=[];
       
        $keywords=Product::where('category','like',"%セット%")->get();

       

        return view('cartsView.cartIndex', compact('cart', 'total','products','keywords','categories'));
    }

    public function store(Request $request)
    {
        Cart::instance(Auth::id())->add(
            [
                'id' => $request->id, 
                'img'=>$request->img,
                'name' => $request->name, 
                'qty' => $request->qty, 
                'price' => $request->price, 
                'weight' => $request->weight, 
            ] 
        );

        return response()->json([
            'success' => true,
            'message' => 'カートに追加しました！',
            'cart_count' => Cart::instance(Auth::id())->count(), // カート内の商品数を返す
        ]);
    }

    public function destroy(Request $request,Product $product)
    {
        $cart = Cart::instance(Auth::user()->id)->content();
       
        // リクエストから送られてきた商品ID
        $productId = $product->id;
        // カートの中から一致する商品を探す
        $cartItem = $cart->firstWhere('id', $productId);
    
        if ($cartItem) {
            // 見つかったら、その商品だけ削除
            Cart::instance(Auth::user()->id)->remove($cartItem->rowId);
        }
       return to_route('carts.index');
   }

   public function update(Request $request)
{
    $productId = $request->product_id;
    $qty = $request->qty;

    // カートの該当商品を取得
    $cartItem = Cart::instance(Auth::user()->id)->content()->where('id', $productId)->first();

    
    if ($cartItem) {
        Cart::instance(Auth::user()->id)->update($cartItem->rowId, $qty);
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
        return $cartItem->price * $cartItem->qty;
    });
    

    return response()->json([
        'success' => true,
        'product_total' => $productTotal,
        'cart_total' => $cartTotal
    ]);
    
}

    public function confirmItems(Product $product){
        $user=Auth::user();
        return view('ec.confirmItems');
    }
}

