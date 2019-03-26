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

   //create product form view
   public function createProductForm(){
        return view('admin.createProductForm');
   }




   //create product
   public function sendCreateProductForm(Request $request){

      $name = $request->input('name');
      $description = $request->input('description');
      $type = $request->input('type');
      $price = $request->input('price');

      //take all from request and make image field required
      Validator::make($request->all(),['image'=>"required|file|image|mimes:jpg,png,jpeg|max:5000"])->validate();

      //get extension of image
      $ext = $request->file('image')->getClientOriginalExtension(); //jpg
            
      //replacing empty space
      $stringImageReFormat = str_replace(' ','',$request->input('name'));

      //full image name with extension
      $imageName = $stringImageReFormat .'.'. $ext;

      //encoding image so we can put it as parameter in storage->put()
      $imageEncoded = File::get($request->image);

      //put image in storage folder
      Storage::disk('local')->put('public/product_images/'.$imageName,$imageEncoded);

      //make array from input fields
      $newProductArray = array('name'=>$name,'description'=>$description, 'type'=>$type, 'image'=>$imageName, 'price'=>$price);

      //put it in database
      $created = DB::table('products')->insert($newProductArray);

      //if created product is successfull then  redirect to page with all products
      if($created){
         return redirect()->route('adminDisplayProducts');
      }else{
         return "Product was not created";
      }


   }






   //edit product view
   public function editProductForm($id){
      $product = Product::find($id);
      return view('admin.editProductForm',['product'=>$product]);
   }

   //edit product image view
   public function editProductImageForm($id){
      $product = Product::find($id);
      return view('admin.editProductImageForm',['product'=>$product]);
   }

   //update(edit) product Image
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

     //delete image
     public function deleteProduct($id){
        
         $product = Product::find($id);
         $exists = Storage::disk('local')->exists("public/product_images/".$product->image);

         //delete from storage folder
         if($exists){
            Storage::delete('public/product_images/'.$product->image);
         }
         Product::destroy($id);

         return redirect()->route('adminDisplayProducts');
     }
}
