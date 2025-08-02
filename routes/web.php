<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminItems\InsertItemsController;
use App\Http\Controllers\EC\MarketHomeController;
use App\Http\Controllers\EC\CartController;
use App\Http\Controllers\EC\SearchController;
use App\Http\Controllers\EC\FavoriteProductController;
use App\Http\Controllers\EC\PayController;
use App\Http\Controllers\EC\ReviewProductController;
use App\Http\Controllers\Auth\User\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EC\ConfirmItemsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminItems\InsertPinbackButtonController;
use App\Http\Controllers\EC\SelectProductController;
use App\Http\Controllers\AdminItems\AdminReviewController;
use App\Http\Controllers\Admin\ConfirmOrderController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminRegisterController;
use Illuminate\Support\Facades\Route;

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
    Route::resource('products', InsertItemsController::class)->names([
    'index' => 'admin.products.list',
    'create' => 'admin.products.create',
    'store' => 'admin.products.store',
    'show' => 'admin.products.show',
    'edit' => 'admin.products.edit',
    'update' => 'admin.products.update',
    'destroy' => 'admin.products.destroy',
    ]);
    Route::get('products/adminReview/{product}',[ReviewProductController::class,'adminReview'])->name('products.adminReview');
    Route::delete('products/review/delete/{review}',[AdminReviewController::class,'deleteReview'])->name('products.deleteReview');
    Route::controller(InsertPinbackButtonController::class)->group(function(){
        Route::get('productSets', 'index')->name('productSets.index'); // 一覧表示
        Route::get('productSets/create', 'create')->name('productSets.create'); // 作成ページ表示
        Route::post('productSets', 'store')->name('productSets.store'); // 新規保存
        Route::get('productSets/{productSet}/edit', 'edit')->name('productSets.edit'); // 編集ページ表示
        Route::put('productSets/{productSet}', 'update')->name('productSets.update'); // 更新（PUT or PATCH）
        Route::delete('productSets/{productSet}', 'destroy')->name('productSets.destroy'); // 削除
    });
    Route::controller(ConfirmOrderController::class)->group(function () {
        Route::get('confirm/order','index')->name('order.index');
        Route::get('confirm/oreder/ship/{orderItem}','shipping')->name('order.ship');
        Route::get('confirm/order/shipped','shipped')->name('order.shipped');
        Route::get('confirm/order/selected/{orderItem}','confirmSet')->name('order.confirmSet');
        });
    });
});


//ここから上ログイン関係
Route::controller(MarketHomeController::class)->group(function () {
    Route::resource('cmart',MarketHomeController::class);
    Route::get('/mart/{product}', 'show')->name('mart.show');
    Route::get('/category/{product}','categorySearch')->name('categorySearch');
});

Route::get('search',[SearchController::class,'search'])->name('search');



Route::middleware(['verified'])->group(function () {
    Route::get('confirm/{product}',[ConfirmItemsController::class,'confirmItems'])->name('confirmItems');

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
});