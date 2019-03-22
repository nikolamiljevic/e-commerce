<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;

class AdminProductsController extends Controller
{
   public function index(){

        $products = Product::all();
        return view('admin.displayProducts',['products'=>$products]);
   }

   public function editProductForm($id){
      $product = Product::find($id);
      return view('admin.editProductForm',['product'=>$product]);
   }

   public function editProductImageForm($id){
      $product = Product::find($id);
      return view('admin.editProductImageForm',['product'=>$product]);
   }
}
