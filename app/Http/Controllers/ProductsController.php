<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Cart;
use\Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
   public function index(){
  
       $products = Product::paginate(3);
       return view('allproducts',compact('products'));
   }

//display only men products
    public function menProducts(){

     $products = DB::table('products')->where('type','men')->get();
     return view('menProducts',compact('products'));
    }

//display only women products
    public function womenProducts(){
      
      $products = DB::table('products')->where('type','women')->get();
      return view('womenProducts',compact('products'));
    }


   public function addProductToCart(Request $request,$id){

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

   public function showCart(){

     $cart = Session::get('cart');
        // cart is not empty
      if($cart){
        return view('cartproducts',['cartItems'=>$cart]);
      //cart is empty
      }else{
        return redirect()->route('allProducts');
      }
   }

   public function deleteItemFromCart(Request $request, $id){

      $cart = $request->session()->get('cart');

      if(array_key_exists($id,$cart->items)){
        unset($cart->items[$id]);
      }

      $prevCart = $request->session()->get('cart');
      $updatedCart = new Cart($prevCart);
      $updatedCart->updatePriceAndQuantity();

      $request->session()->put('cart',$updatedCart);

      return redirect()->route('cartproducts');

   }



}
