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

Route::get('/',['uses'=>'ProductsController@index', 'as'=>'allProducts']);
//all products
Route::get('products',['uses'=>'ProductsController@index', 'as'=>'allProducts']);

//men products
Route::get('products/men',['uses'=>'ProductsController@menProducts', 'as'=>'menProducts']);

//women products
Route::get('products/women',['uses'=>'ProductsController@womenProducts', 'as'=>'womenProducts']);




//search
Route::get('search',['uses'=>'ProductsController@search', 'as'=>'searchProducts']);




//add product to cart
Route::get('product/addToCart/{id}',['uses'=>'ProductsController@addProductToCart','as'=>'AddToCartProduct']);

//show items in cart
Route::get('cart', ["uses"=>"ProductsController@showCart", "as"=> "cartproducts"]);

//delete item from cart
Route::get('product/deleteItemFromCart/{id}',['uses'=>'ProductsController@deleteItemFromCart','as'=>'DeleteItemFromCart']);






//checkout page
Route::get('product/checkoutProducts',['uses'=>'ProductsController@checkoutProducts','as'=>'checkoutProducts']);

//create an order
Route::get('product/createOrder',['uses'=>'ProductsController@createOrder','as'=>'createOrder']);

//process checkout page
Route::post('product/createNewOrder',['uses'=>'ProductsController@createNewOrder','as'=>'createNewOrder']);







//increase single product 
Route::get('product/increaseSingleProduct/{id}',['uses'=>'ProductsController@increaseSingleProduct','as'=>'increaseSingleProduct']);

//decrease single product 
Route::get('product/decreaseSingleProduct/{id}',['uses'=>'ProductsController@decreaseSingleProduct','as'=>'decreaseSingleProduct']);





Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



//admin panel
Route::get('admin/products', ["uses"=>"Admin\AdminProductsController@index", "as"=> "adminDisplayProducts"])->middleware('restrictToAdmin');

//display edit product form
Route::get('admin/editProductForm/{id}', ["uses"=>"Admin\AdminProductsController@editProductForm", "as"=> "adminEditProductForm"]);

//display etir product image form
Route::get('admin/editProductImageForm/{id}', ["uses"=>"Admin\AdminProductsController@editProductImageForm", "as"=> "adminEditProductImageForm"]);

//update product image
Route::post('admin/updateProductImage/{id}', ["uses"=>"Admin\AdminProductsController@updateProductImage", "as"=> "adminUpdateProductImage"]);

//update product details
Route::post('admin/updateProduct/{id}', ["uses"=>"Admin\AdminProductsController@updateProduct", "as"=> "adminUpdateProduct"]);

//display create product form
Route::get('admin/createProductForm', ["uses"=>"Admin\AdminProductsController@createProductForm", "as"=> "adminCreateProductForm"]);

//create product
Route::post('admin/sendCreateProductForm', ["uses"=>"Admin\AdminProductsController@sendCreateProductForm", "as"=> "adminSendCreateProductForm"]);

//delete product
Route::get('admin/deleteProduct/{id}', ["uses"=>"Admin\AdminProductsController@deleteProduct", "as"=> "adminDeleteProduct"]);