$user_shoppingcarts = DB::table('shoppingcart')->get();
    $number = DB::table('shoppingcart')->where('instance', Auth::user()->id)->count();

    $count = $user_shoppingcarts->count();

    $count += 1;
    $number += 1;
    $cart = Cart::instance(Auth::user()->id)->content();

    $price_total = 0;
    $qty_total = 0;
    $has_carriage_cost = false;

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

    Cart::instance(Auth::user()->id)->store($count);

    DB::table('shoppingcart')->where('instance', Auth::user()->id)
        ->where('number', null)
        ->update(
            [
                'code' => substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 10),
                'number' => $number,
                'price_total' => $price_total,
                'qty' => $qty_total,
                'buy_flag' => true,
                'updated_at' => date("Y/m/d H:i:s")
            ]
        );

    Cart::instance(Auth::user()->id)->destroy();

    return view('checkout.success');