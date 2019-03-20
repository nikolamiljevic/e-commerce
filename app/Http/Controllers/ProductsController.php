<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Cart;

class ProductsController extends Controller
{
   public function index(){
  
       $products = Product::all();
       return view('allproducts',compact('products'));
   }

   public function addProductToCard(Request $request,$id){

            //session request to see if there is any added items
        $prevCart = $request->session()->get('cart');
            //instantiate class Cart which has method for adding items and constructor to see if there is any previous added items
        $cart = new Cart($prevCart);
            //finding  product by id
        $product = Product::find($id);
            //calling the method in Cart class to add new item to the cart
        $cart->addItem($id,$product);
            //creating session
        $request->session()->put('cart', $cart);

       return redirect()->route('allProducts');
   }
}
