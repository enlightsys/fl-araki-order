<?php

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
Route::get('/', [App\Http\Controllers\SiteController::class, 'index']);
Route::get('/list', [App\Http\Controllers\SiteController::class, 'list']);
Route::get('/detail/{id}', [App\Http\Controllers\SiteController::class, 'detail'])->name('detail');
Route::post('/put_in', [App\Http\Controllers\SiteController::class, 'put_in'])->name('put_in');
Route::get('/cart', [App\Http\Controllers\SiteController::class, 'cart'])->name('cart');
Route::get('/term', [App\Http\Controllers\SiteController::class, 'term'])->name('term');
Route::get('/privacy', [App\Http\Controllers\SiteController::class, 'privacy'])->name('privacy');
Route::get('contact', [App\Http\Controllers\SiteController::class, 'contact'])->name('contact');
Route::post('contact_confirm', [App\Http\Controllers\SiteController::class, 'contact_confirm'])->name('contact_confirm');
Route::post('contact_store', [App\Http\Controllers\SiteController::class, 'contact_store'])->name('contact_store');
Route::get('contact_complete', [App\Http\Controllers\SiteController::class, 'contact_complete'])->name('contact_complete');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/mypage', [App\Http\Controllers\SiteController::class, 'mypage'])->name('mypage');
	Route::get('/checkout', [App\Http\Controllers\SiteController::class, 'checkout'])->name('checkout');
	Route::post('/purchase', [App\Http\Controllers\SiteController::class, 'purchase'])->name('purchase');
	Route::get('/complete', [App\Http\Controllers\SiteController::class, 'complete'])->name('complete');
});
