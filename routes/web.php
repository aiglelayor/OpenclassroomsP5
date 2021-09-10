<?php

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

Route::get('/', function () {
    return view('welcome');
});

/* Products Routes */
Route::get('/boutique', 'ProductController@index')->name('products.index');
Route::get('/boutique/{slug}', 'ProductController@show')->name('products.show');

/* ShoppingCart Routes */
Route::post('/panier/ajouter', 'CartController@store')->name('cart.store');
Route::get('/panier', 'CartController@index')->name('cart.index');
//Patch est utilisé pour modifier une ressource.
Route::patch('/panier/{rowId}', 'CartController@update')->name('cart.update');
Route::delete('/panier/{rowId}', 'CartController@remove')->name('cart.remove');

App::register('Gloudemans\Shoppingcart\ShoppingcartServiceProvider');


/* Checkout Routes */
Route::get('/paiement', 'CheckoutController@index')->name('checkout.index');
Route::post('/paiement', 'CheckoutController@store')->name('checkout.store');
Route::get('/merci', 'CheckoutController@thankYou')->name('checkout.thankYou');