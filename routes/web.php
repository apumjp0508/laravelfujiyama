<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin_items\insert_items_Controller;
use App\Http\Controllers\EC\MarketHomeController;
use App\Http\Controllers\EC\CartController;
use App\Http\Controllers\EC\SearchController;
use App\Http\Controllers\EC\FavoriteProductController;
use App\Http\Controllers\EC\PayController;
use App\Http\Controllers\EC\ReviewProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EC\ConfirmItemsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\admin_items\insert_PinbackButton_Controller;
use App\Http\Controllers\EC\SelectProductController;
use App\Http\Controllers\Admin\ConfirmOrderController;
use App\Models\Product;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminRegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//顧客用ログイン画面
Route::get('/',function(){
    return view('manageView.custmerLogin');
});



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| 管理者用ルーティング
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin'], function () {
    // 登録
    Route::get('register', [AdminRegisterController::class, 'create'])
        ->name('admin.register');

    Route::post('register', [AdminRegisterController::class, 'store']);

    // ログイン
    Route::get('login', [AdminLoginController::class, 'showLoginPage'])
        ->name('admin.login');

    Route::post('login', [AdminLoginController::class, 'login']);

    // 以下の中は認証必須のエンドポイントとなる
    Route::middleware(['auth:admin'])->group(function () {
        // ダッシュボード
    Route::get('dashboard', fn() => view('auth.adminLogin.dashboard'))->name('admin.dashboard');
    Route::resource('products', insert_items_Controller::class, [
        'as' => 'admin'  // ★ これを追加することで `admin.products.index` などの名前が付く
    ]);
    Route::get('products/adminReview/{product}',[insert_items_Controller::class,'adminReview'])->name('products.adminReview');
    Route::delete('products/review/delete/{review}',[insert_items_Controller::class,'deleteReview'])->name('products.deleteReview');
    Route::controller(insert_PinbackButton_Controller::class)->group(function(){
    Route::resource('badge',insert_PinbackButton_Controller::class);
            });
    });
});


//ここから上ログイン関係
Route::controller(MarketHomeController::class)->group(function () {
    Route::resource('cmart',MarketHomeController::class);
    Route::get('/mart/{product}', 'show')->name('mart.show');
    Route::get('/category/{product}','categorySearch')->name('categorySearch');
});


Route::controller(ConfirmOrderController::class)->group(function () {
   Route::get('confirm/order','index')->name('order.index');
   Route::get('confirm/oreder/ship/{orderItem}','shipping')->name('order.ship');
   Route::get('confirm/order/shipped','shipped')->name('order.shipped');
   Route::get('confirm/order/selected/{orderItem}','confirmSet')->name('order.confirmSet');
});
Route::get('search',[SearchController::class,'search'])->name('search');


Route::get('confirm',[ConfirmItemsController::class,'confirmItems'])->name('confirmItems');

Route::controller(FavoriteProductController::class)->group(function () {
    Route::get('favorites/show','show')->name('favorites.show');
    Route::post('favorites/{products_id}','store')->name('favorites.store');
    Route::delete('favorites/{products_id}','destroy')->name('favorites.destroy');
});

Route::controller(CartController::class)->group(function () {
    Route::get('users/carts', 'index')->name('carts.index');
    Route::post('users/carts/add', 'store')->name('carts.store');
    Route::delete('users/carts/{product}','destroy')->name('carts.destroy');
    Route::post('carts/update', 'update')->name('carts.update');
    Route::get('carts/confirm/{product}','confirmItems')->name('carts.confirmItems');
});

Route::post('reviews', [ReviewProductController::class, 'store'])->name('reviews.store');

Route::controller(PayController::class)->group(function(){
    Route::get('users/carts/pay','index')->name('pay.index');
    Route::post('users/carts/pay/store','store')->name('pay.store');
});

Route::controller(CheckoutController::class)->group(function () {
    Route::get('checkout', 'index')->name('checkout.index');
    Route::post('checkout', 'store')->name('checkout.store');
    Route::get('checkout/success', 'success')->name('checkout.success');
});


Route::controller(UserController::class)->group(function () {
    Route::get('users/mypage', 'mypage')->name('mypage');
    Route::get('users/confirmOrder','ConfirmOrder')->name('confirmOrder');
    Route::get('users/mypage/edit', 'edit')->name('mypage.edit');
    Route::put('users/mypage', 'update')->name('mypage.update');
});

Route::controller(SelectProductController::class)->group(function(){
    Route::get('mart/select/{product}','index')->name('select.index');
    Route::post('mart/select/decide','store')->name('select.store');
});