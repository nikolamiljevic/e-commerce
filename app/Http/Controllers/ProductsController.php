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

  //search
    public function search(Request $request){
     $searchText = $request->get('searchText');
     //$products = DB::table('products')->where('name','like',$searchText.'%')->get(); this throws an error beacuse of pagination

     //search where name similar to what typed in input field search
     $products = Product::where('name','Like',$searchText.'%')->paginate(3);
     return view('allproducts',compact('products'));
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

   //increase single product quantity to cart in the cart page
   
   public function increaseSingleProduct(Request $request,$id){

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

      return redirect()->route('cartproducts');
    }


    //decrease single product quantity in cart page
    public function decreaseSingleProduct(Request $request, $id){

      $prevCart = $request->session()->get('cart');
      $cart = new Cart($prevCart);

      if ($cart->items[$id]['quantity'] > 1) {
        
        $product = Product::find($id);
        $cart->items[$id]['quantity'] = $cart->items[$id]['quantity']-1;
        $cart->items[$id]['totalSinglePrice'] = $cart->items[$id]['quantity'] * (int) $product['price'];
        $cart->updatePriceAndQuantity();

        $request->session()->put('cart',$cart);

      }
        return redirect()->route('cartproducts');
    }


//create order in cart

    public function createOrder(){
      $cart = Session::get('cart');

      //cart is not empty
      if($cart) {
      // dump($cart);
          $date = date('Y-m-d H:i:s');
          $newOrderArray = array("status"=>"on_hold","date"=>$date,"del_date"=>$date,"price"=>$cart->totalPrice);
          $created_order = DB::table("orders")->insert($newOrderArray);
          $order_id = DB::getPdo()->lastInsertId();;


          foreach ($cart->items as $cart_item){
              $item_id = $cart_item['data']['id'];
              $item_name = $cart_item['data']['name'];
              $item_price = $cart_item['data']['price'];
              $item_price = str_replace('$','',$item_price);
              $newItemsInCurrentOrder = array("item_id"=>$item_id,"order_id"=>$order_id,"item_name"=>$item_name,"item_price"=>$item_price);
              $created_order_items = DB::table("order_items")->insert($newItemsInCurrentOrder);
          }

          //delete cart
          Session::forget('cart');
          Session::flush();
          return redirect()->route("allProducts")->withsuccess("Thanks For Choosing Us");

      }else{

          return redirect()->route("allProducts");

      }


  }

}
