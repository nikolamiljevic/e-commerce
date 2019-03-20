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

Route::get('products',['uses'=>'ProductsController@index', 'as'=>'allProducts']);

Route::get('product/addToCard/{id}',['uses'=>'ProductsController@addProductToCard','as'=>'AddToCartProduct']);

//show items in cart
Route::get('cart', ["uses"=>"ProductsController@showCart", "as"=> "cartproducts"]);