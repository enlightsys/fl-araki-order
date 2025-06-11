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
Route::get('/', [App\Http\Controllers\SiteController::class, 'index'])->name('index');
Route::get('/list', [App\Http\Controllers\SiteController::class, 'list']);
Route::get('/detail/{id}', [App\Http\Controllers\SiteController::class, 'detail'])->name('detail');
Route::post('/put_in', [App\Http\Controllers\SiteController::class, 'put_in'])->name('put_in');
Route::get('/cart', [App\Http\Controllers\SiteController::class, 'cart'])->name('cart');
Route::post('/cart_quantity', [App\Http\Controllers\SiteController::class, 'cart_quantity'])->name('cart_quantity');
Route::get('/term', [App\Http\Controllers\SiteController::class, 'term'])->name('term');
Route::get('/privacy', [App\Http\Controllers\SiteController::class, 'privacy'])->name('privacy');
Route::get('/guide', [App\Http\Controllers\SiteController::class, 'guide'])->name('guide');
Route::get('/cancel_policy', [App\Http\Controllers\SiteController::class, 'cancel_policy'])->name('cancel_policy');
Route::get('/contact', [App\Http\Controllers\SiteController::class, 'contact'])->name('contact');
Route::post('/contact_confirm', [App\Http\Controllers\SiteController::class, 'contact_confirm'])->name('contact_confirm');
Route::post('/contact_store', [App\Http\Controllers\SiteController::class, 'contact_store'])->name('contact_store');
Route::get('/contact_complete', [App\Http\Controllers\SiteController::class, 'contact_complete'])->name('contact_complete');
Route::get('/products/image', [App\Http\Controllers\SiteController::class, 'image']);

Route::group(['middleware' => 'auth'], function () {
	Route::get('/mypage', [App\Http\Controllers\SiteController::class, 'mypage'])->name('mypage');
	Route::get('/profile', [App\Http\Controllers\SiteController::class, 'profile'])->name('profile');
	Route::get('/profile_edit', [App\Http\Controllers\SiteController::class, 'profile_edit'])->name('profile_edit');
	Route::post('/profile_update', [App\Http\Controllers\SiteController::class, 'profile_update'])->name('profile_update');
	Route::get('/profile_password', [App\Http\Controllers\SiteController::class, 'profile_password'])->name('profile_password');
	Route::post('/profile_password_update', [App\Http\Controllers\SiteController::class, 'profile_password_update'])->name('profile_password_update');
	Route::get('/history', [App\Http\Controllers\SiteController::class, 'history'])->name('history');
	Route::get('/history_detail/{id}', [App\Http\Controllers\SiteController::class, 'history_detail'])->name('history_detail');
	Route::get('/estimate/{id}', [App\Http\Controllers\SiteController::class, 'estimate'])->name('estimate');
	Route::post('/reorder/{id}', [App\Http\Controllers\SiteController::class, 'reorder'])->name('reorder');
	Route::get('/checkout', [App\Http\Controllers\SiteController::class, 'checkout'])->name('checkout');
	Route::post('/purchase_check', [App\Http\Controllers\SiteController::class, 'purchase_check'])->name('purchase_check');
	Route::post('/purchase', [App\Http\Controllers\SiteController::class, 'purchase'])->name('purchase');
	Route::get('/complete', [App\Http\Controllers\SiteController::class, 'complete'])->name('complete');
	Route::post('/zeus_enroll', [App\Http\Controllers\SiteController::class, 'zeus_enroll'])->name('zeus_enroll');
	Route::any('/zeus_term', [App\Http\Controllers\SiteController::class, 'zeus_term'])->name('zeus_term');
});
