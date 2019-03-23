<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class AdminProductsController extends Controller
{
   public function index(){

        $products = Product::all();
        return view('admin.displayProducts',['products'=>$products]);
   }

   //create product form
   public function createProductForm(){
        return view('admin.createProductForm');
   }

   //edit product view
   public function editProductForm($id){
      $product = Product::find($id);
      return view('admin.editProductForm',['product'=>$product]);
   }

   public function editProductImageForm($id){
      $product = Product::find($id);
      return view('admin.editProductImageForm',['product'=>$product]);
   }

      //update product Image
      public function updateProductImage(Request $request,$id){

         Validator::make($request->all(),['image'=>"required|file|image|mimes:jpg,png,jpeg|max:5000"])->validate();
 
         if($request->hasFile("image")){
 
            $product = Product::find($id);
            $exists = Storage::disk('local')->exists("public/product_images/".$product->image);
 
           //delete old image
           if($exists){
              Storage::delete('public/product_images/'.$product->image);
 
           }
 
           //upload new image
             $ext = $request->file('image')->getClientOriginalExtension(); //jpg
 
             $request->image->storeAs("public/product_images/",$product->image);
 
             $arrayToUpdate = array('image'=>$product->image);

             DB::table('products')->where('id',$id)->update($arrayToUpdate);
 
 
             return redirect()->route("adminDisplayProducts");
 
         }else{
 
            $error = "NO Image was Selected";
            return $error;
 
         }
 
 
     }

     //update product details
     public function updateProduct(Request $request, $id){

         $name = $request->input('name');
         $description = $request->input('description');
         $type = $request->input('type');
         $price = $request->input('price');

         $arrayToUpdate = array('name'=>$name,'description'=>$description, 'type'=>$type, 'price'=>$price);
         DB::table('products')->where('id',$id)->update($arrayToUpdate);

         return redirect()->route('adminDisplayProducts');
     }

     

}
