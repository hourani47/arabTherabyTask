<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

 //home page
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// admin route add for do CRUD operation on product must have role admin
Route::group(['prefix' => '/admin'], function() {
    Route::group(['middleware' => ['auth','role:admin']], function() {
        Route::resource('products', ProductController::class);
    });
});

//cart route any user can see cart and edit on it
Route::group(['prefix' => '/products'], function() {
    Route::post('/cart/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::put('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'removeItemFromCart'])->name('cart.remove');

});

//checkout page must be login
Route::group(['prefix' => '/checkout'], function() {
    Route::group(['middleware' => ['auth']], function() {
        Route::get('/index', [CheckoutController::class, 'index'])->name('cart.checkout');
        Route::post('/store', [CheckoutController::class, 'store'])->name('checkout');
        Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
    });
});
