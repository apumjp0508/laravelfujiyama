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
use App\Http\Controllers\admin_items\insert_PinbackButton_Controller;
use App\Http\Controllers\EC\SelectProductController;
use App\Models\Product;
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



Route::get('/dashboard', function () {
    $products=Product::all();
    return view('manageView.index',compact('products'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/', function(){
    return view('manageView.home');
});
Route::controller(MarketHomeController::class)->group(function () {
    Route::resource('cmart',MarketHomeController::class);
    Route::get('/mart/{product}', 'show')->name('mart.show');
    Route::get('/category/{product}','categorySearch')->name('categorySearch');
});

Route::get('search',[SearchController::class,'search'])->name('search');


Route::controller(insert_items_Controller::class)->group(function () {
    Route::resource('products', insert_items_Controller::class);
    Route::get('products/adminReview/{product}','adminReview')->name('products.adminReview');
    Route::delete('products/review/delete/{review}','deleteReview')->name('products.deleteReview');
});

Route::controller(FavoriteProductController::class)->group(function () {
    Route::post('favorites/{products_id}','store')->name('favorites.store');
    Route::delete('favorites/{products_id}','destroy')->name('favorites.destroy');
});

Route::controller(CartController::class)->group(function () {
    Route::get('users/carts', 'index')->name('carts.index');
    Route::post('users/carts/add', 'store')->name('carts.store');
    Route::delete('users/carts/{product}','destroy')->name('carts.destroy');
    Route::post('carts/update', 'update')->name('carts.update');
});

Route::post('reviews', [ReviewProductController::class, 'store'])->name('reviews.store');

Route::controller(PayController::class)->group(function(){
    Route::get('users/carts/pay','index')->name('pay.index');
});


Route::controller(UserController::class)->group(function () {
    Route::get('users/mypage', 'mypage')->name('mypage');
    Route::get('users/mypage/edit', 'edit')->name('mypage.edit');
    Route::put('users/mypage', 'update')->name('mypage.update');
});

Route::controller(insert_PinbackButton_Controller::class)->group(function(){
    Route::resource('batch',insert_PinbackButton_Controller::class);
});

Route::controller(SelectProductController::class)->group(function(){
    Route::get('mart/select/{product}','index')->name('select.index');
    Route::post('mart/select/decide/{product}','store')->name('select.store');
});